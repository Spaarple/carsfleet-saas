<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Users\UserCrudController;
use App\Entity\Accident;
use App\Entity\Borrow;
use App\Entity\Car;
use App\Entity\Key;
use App\Entity\Site;
use App\Entity\User\AbstractUser;
use App\Entity\User\UserAdministratorHeadOffice;
use App\Entity\User\UserAdministratorSite;
use App\Entity\User\UserEmployed;
use App\Entity\HeadOffice;
use App\Enum\Role;
use App\Repository\AccidentRepository;
use App\Repository\BorrowRepository;
use App\Repository\CarRepository;
use App\Repository\KeyRepository;
use App\Repository\SiteRepository;
use App\Repository\User\UserAdministratorSiteRepository;
use App\Repository\User\UserEmployedRepository;
use App\Repository\User\UserAdministratorHeadOfficeRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMINISTRATOR_SITE')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly Security $security,
        private readonly SiteRepository $siteRepository,
        private readonly AccidentRepository $accidentRepository,
        private readonly BorrowRepository $borrowRepository,
        private readonly KeyRepository $keyRepository,
        private readonly CarRepository $carRepository,
        private readonly UserAdministratorHeadOfficeRepository $userAdministratorHeadOffice,
        private readonly UserAdministratorSiteRepository $administrator,
        private readonly UserEmployedRepository $userEmployed,
    )
    {
    }

    /**
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        if (!$this->isGranted(Role::ROLE_SUPER_ADMINISTRATOR->name)) {
            return $this->redirect(
                $adminUrlGenerator->setController(UserCrudController::class)->generateUrl()
            );
        }

        /* @var AbstractUser $user */
        $user = $this->security->getUser();
        if (in_array(Role::ROLE_SUPER_ADMINISTRATOR->name, $user->getRoles(), true)) {
            $dataBorrowByYears = $this->borrowRepository->getAllBorrowByDate();
            $dataAccidentByYears = $this->accidentRepository->getAllAccidentByDate();

            $nbCar = $this->carRepository->findAll();
            $nbSite = $this->siteRepository->findAll();
            $nbBorrow = $this->borrowRepository->findAll();
            $nbAccident = $this->accidentRepository->findAll();
        } else {
            $dataBorrowByYears = $this->borrowRepository->getBorrowUserByDate($user);
            $dataAccidentByYears = $this->accidentRepository->getAccidentUserByDate($user);

            $nbCar = $this->carRepository->getCarsByUser($user)->getQuery()->getResult();
            $nbSite = $this->siteRepository->getSitesByHeadOffice($user)->getQuery()->getResult();
            $nbBorrow = $this->borrowRepository->getBorrowByUser($user)->getQuery()->getResult();
            $nbAccident = $this->accidentRepository->getAccidentByUser($user)->getQuery()->getResult();
        }

        return $this->render('admin/dashboard.html.twig', [
            'borrow_chart' => $dataBorrowByYears,
            'accident_chart' => $dataAccidentByYears,
            'cars' => count($nbCar),
            'sites' => count($nbSite),
            'accidents' => count($nbAccident),
            'borrows' => count($nbBorrow),
        ]);
    }

    /**
     * @return Dashboard
     */
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('CarsFleet')
            ->setTranslationDomain('admin');
    }

    /**
     * @return iterable
     */
    public function configureMenuItems(): iterable
    {
        $user = $this->security->getUser();

        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        if (in_array(Role::ROLE_SUPER_ADMINISTRATOR->name, $user?->getRoles(), true)) {
            yield MenuItem::linkToCrud('Siège Social', 'fa fa-building', HeadOffice::class);
            yield MenuItem::linkToCrud('Site', 'fa fa-building', Site::class);
            yield MenuItem::linkToCrud('Voitures', 'fa fa-car', Car::class);
            yield MenuItem::linkToCrud('Clés', 'fa fa-key', Key::class);
            yield MenuItem::linkToCrud('Emprunts', 'fa fa-route', Borrow::class);
            yield MenuItem::linkToCrud('Accidents', 'fa fa-car-crash', Accident::class);
            yield MenuItem::subMenu('Utilisateurs', 'fas fa-users')->setSubItems([
                MenuItem::linkToCrud('Administrateurs Site', null, UserAdministratorSite::class),
                MenuItem::linkToCrud('Employé(e)s', null, UserEmployed::class),
                MenuItem::linkToCrud('Administrateurs général', null, UserAdministratorHeadOffice::class)
            ]);
        } else {

            if (in_array(Role::ROLE_ADMINISTRATOR_HEAD_OFFICE->name, $user?->getRoles(), true)) {
                yield MenuItem::linkToCrud('Siège Social', 'fa fa-building', HeadOffice::class);
            }

            $nbSite = $this->siteRepository->getSitesByHeadOffice($user)->getQuery()->getResult();
            yield MenuItem::linkToCrud('Site', 'fa fa-building', Site::class)
                ->setBadge(count($nbSite));

            $nbCar = $this->carRepository->getCarsByUser($user)->getQuery()->getResult();
            yield MenuItem::linkToCrud('Voitures', 'fa fa-car', Car::class)
                ->setBadge(count($nbCar));

            $nbKey = $this->keyRepository->getKeysByUser($user)->getQuery()->getResult();
            yield MenuItem::linkToCrud('Clés', 'fa fa-key', Key::class)
                ->setBadge(count($nbKey));

            $nbBorrow = $this->borrowRepository->getBorrowByUser($user)->getQuery()->getResult();
            yield MenuItem::linkToCrud('Emprunts', 'fa fa-route', Borrow::class)
                ->setBadge(count($nbBorrow));

            $nbAccident = $this->accidentRepository->getAccidentByUser($user)->getQuery()->getResult();
            yield MenuItem::linkToCrud('Accidents', 'fa fa-car-crash', Accident::class)
                ->setBadge(count($nbAccident), 'danger');


            $nbAdmin = $this->administrator->getAdmin($user)->getQuery()->getResult();
            $nbEmployed = $this->userEmployed->getEmployees($user)->getQuery()->getResult();

            $subItems = [
                MenuItem::linkToCrud('Administrateurs Site', null, UserAdministratorSite::class)
                    ->setBadge(count($nbAdmin)),
                MenuItem::linkToCrud('Employé(e)s', null, UserEmployed::class)
                    ->setBadge(count($nbEmployed)),
            ];

            if (in_array(Role::ROLE_ADMINISTRATOR_SITE->name, $user?->getRoles(), true)) {
                $nbSuperAdmin = $this->userAdministratorHeadOffice->getSuperAdmin($user)->getQuery()->getResult();
                array_unshift($subItems,
                    MenuItem::linkToCrud('Administrateurs général', null, UserAdministratorHeadOffice::class)
                        ->setBadge(count($nbSuperAdmin))
                );
            }

            yield MenuItem::subMenu('Utilisateurs', 'fas fa-users')->setSubItems($subItems);
        }

        yield MenuItem::section('Paramètres du compte');
        yield MenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out');
        yield MenuItem::linkToRoute('Annuler l\'abonnement', 'fa fa-xmark', 'app_profile_cancel_subscription');
    }

    /**
     * @param UserInterface $user
     * @return UserMenu
     */
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setName(sprintf('%s %s | %s',
                $user->getFirstName(),
                $user->getLastName(),
                $user->getEmail()
            ));
    }
}
