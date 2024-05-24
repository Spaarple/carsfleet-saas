<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Users\UserCrudController;
use App\Entity\Accident;
use App\Entity\Borrow;
use App\Entity\Car;
use App\Entity\Key;
use App\Entity\Site;
use App\Entity\User\UserAdministrator;
use App\Entity\User\UserEmployed;
use App\Entity\User\UserSuperAdministrator;
use App\Entity\HeadOffice;
use App\Enum\Role;
use App\Repository\AccidentRepository;
use App\Repository\BorrowRepository;
use App\Repository\CarRepository;
use App\Repository\KeyRepository;
use App\Repository\SiteRepository;
use App\Repository\User\UserAdministratorRepository;
use App\Repository\User\UserEmployedRepository;
use App\Repository\User\UserSuperAdministratorRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMINISTRATOR')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly Security $security,
        private readonly SiteRepository $siteRepository,
        private readonly AccidentRepository $accidentRepository,
        private readonly BorrowRepository $borrowRepository,
        private readonly KeyRepository $keyRepository,
        private readonly CarRepository $carRepository,
        private readonly UserSuperAdministratorRepository $superAdministratorRepository,
        private readonly UserAdministratorRepository $administrator,
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
            return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
        }


        return $this->render('admin/dashboard.html.twig', [
            'chart' => [10, 20, 30, 40, 10, 23, 30, 400],
            'cars' => count($this->carRepository->findAll()),
            'sites' => count($this->siteRepository->findAll()),
        ]);

    }

    /**
     * @return Dashboard
     */
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('CarsFleet')
            ->setTranslationDomain('admin')
            ->disableUrlSignatures();
    }

    /**
     * @return iterable
     */
    public function configureMenuItems(): iterable
    {
        $user = $this->security->getUser();

        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        if ($this->getUser()?->getRoles()[0] === Role::ROLE_SUPER_ADMINISTRATOR->name) {
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


        $nbSuperAdmin = $this->superAdministratorRepository->getSuperAdmin($user)->getQuery()->getResult();
        $nbAdmin = $this->administrator->getAdmin($user)->getQuery()->getResult();
        $nbEmployed = $this->userEmployed->getEmployees($user)->getQuery()->getResult();

        $subItems = [
            MenuItem::linkToCrud('Administrateurs', null, UserAdministrator::class)
                ->setBadge(count($nbAdmin)),
            MenuItem::linkToCrud('Employé(e)s', null, UserEmployed::class)
                ->setBadge(count($nbEmployed)),
        ];

        if ($this->getUser()?->getRoles()[0] !== Role::ROLE_ADMINISTRATOR->name) {
            array_unshift($subItems,
                MenuItem::linkToCrud('Administrateurs général', null, UserSuperAdministrator::class)
                    ->setBadge(count($nbSuperAdmin))
            );
        }

        yield MenuItem::subMenu('Utilisateurs', 'fas fa-users')->setSubItems($subItems);

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
