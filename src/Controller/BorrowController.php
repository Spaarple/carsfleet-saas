<?php

namespace App\Controller;

use App\Entity\Borrow;
use App\Entity\BorrowMeet;
use App\Entity\User\UserEmployed;
use App\Form\BorrowFormType;
use App\Repository\BorrowRepository;
use App\Repository\CarRepository;
use App\Service\AlertServiceInterface;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/borrow', name: 'app_borrow')]
#[IsGranted('ROLE_EMPLOYED')]
class BorrowController extends AbstractController
{

    /**
     * @param EntityManagerInterface $entityManager
     * @param CarRepository $carRepository
     * @param AlertServiceInterface $alertService
     * @param BorrowRepository $borrowRepository
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CarRepository          $carRepository,
        private readonly AlertServiceInterface  $alertService,
        private readonly BorrowRepository       $borrowRepository,
    )
    {}

    /**
     * @param UserEmployed $employed
     * @return Response
     */
    #[Route('/history', name: '_history')]
    public function history(#[CurrentUser] UserEmployed $employed): Response
    {
        return $this->render('borrow/history.html.twig', [
            'borrows_history' => $employed->getBorrows(),
        ]);
    }

    /**
     * @param string $id
     * @param Request $request
     * @param UserEmployed $employed
     *
     * @return Response
     * @throws \Exception
     */
    #[Route('/create/{id}', name: '_index')]
    public function create(string $id, Request $request, #[CurrentUser] UserEmployed $employed): Response {
        $borrow = new Borrow();
        $borrowMeet = new BorrowMeet();

        $form = $this->createForm(BorrowFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fullDate = $request->request->all()['borrow_form']['startDate'];
            $extractDate = explode(' - ', $fullDate);

            $dateStart= new DateTimeImmutable($extractDate[0]);
            $dateEnd = new DateTimeImmutable($extractDate[1]);

            $borrow->setStartDate($dateStart)
                ->setEndDate($dateEnd)
                ->setDriver($employed)
                ->addUserEmployed($employed);

            $borrowMeet->setSite($employed->getSite())
                ->setDate($form->get('borrowMeet')->getData()->getDate())
                ->setTripDestination($form->get('borrowMeet')->getData()->getTripDestination());

            $borrow->setBorrowMeet($borrowMeet)
                ->setCar($this->carRepository->find($id));

            $this->entityManager->persist($borrowMeet);
            $this->entityManager->persist($borrow);
            $this->entityManager->flush();

            $this->alertService->success('La demande de réservation a bien été enregistrée');

            return $this->redirectToRoute('app_car_index');
        }

        return $this->render('borrow/index.html.twig', [
            'form' => $form->createView(),
            'available_dates' => json_encode($this->borrowRepository->findBorrowsByCar($id), JSON_THROW_ON_ERROR),
        ]);
    }

    /**
     * @param Borrow $borrow
     * @param UserEmployed $employed
     * @return Response
     */
    #[Route('/create/passenger/{id}', name: '_index_passenger')]
    public function createPassenger(
        Borrow $borrow,
        #[CurrentUser] UserEmployed $employed
    ): Response
    {
        $borrow->addUserEmployed($employed);

        $this->entityManager->persist($borrow);
        $this->entityManager->flush();

        $this->alertService->success('Vous êtes maintenant passager de la voiture');

        return $this->redirectToRoute('app_car_index');
    }

    /**
     * @param UserEmployed $employed
     * @param BorrowRepository $borrowRepository
     * @return Response
     */
    #[Route('/passenger', name: '_passenger')]
    public function setPassenger(
        #[CurrentUser] UserEmployed $employed,
        BorrowRepository $borrowRepository,
    ): Response {

        $currentBorrows = $borrowRepository->findCurrentBorrowsByUserSite($employed);

        return $this->render('borrow/passenger.html.twig', [
            'cars' => $currentBorrows,
        ]);
    }

    /**
     * @param string $id
     * @param BorrowRepository $borrowRepository
     * @return Response
     */
    #[Route('/{id}', name: '_detail')]
    public function view(string $id, BorrowRepository $borrowRepository): Response
    {
        return $this->render('borrow/detail.html.twig', [
            'borrow' => $borrowRepository->findOneBy(['car' => $id]),
        ]);
    }

    /**
     * @param string $id
     * @param MailerInterface $mailer
     * @param ParameterBagInterface $parameterBag
     * @param UrlGeneratorInterface $urlGenerator
     * @param UserEmployed $currentUser
     * @return Response
     * @throws TransportExceptionInterface
     */
    #[Route('/cancel/{id}', name: '_cancel')]
    public function cancelBorrow(
        string $id,
        MailerInterface $mailer,
        ParameterBagInterface $parameterBag,
        UrlGeneratorInterface $urlGenerator,
        #[CurrentUser] UserEmployed $currentUser
    ): Response
    {
        $borrow = $this->borrowRepository->find($id);

        if (!$borrow) {
            throw $this->createNotFoundException('Aucun emprunt trouvé pour cet id : ' . $id);
        }

        if ($borrow->getDriver() === $currentUser) {
            // Si l'utilisateur qui annule est le conducteur, supprimez le Borrow entièrement
            foreach ($borrow->getUserEmployed() as $user) {
                $this->sendCancellationEmail($user, $borrow, $mailer, $parameterBag, $urlGenerator);
                $user->removeBorrow($borrow);
            }
            $borrowMeet = $borrow->getBorrowMeet()?->removeBorrow($borrow);
            $this->entityManager->remove($borrow);
            if ($borrowMeet->getBorrow()->isEmpty()) {
                $this->entityManager->remove($borrowMeet);
            }
        } else {
            // Si l'utilisateur qui annule est un passager, supprimez uniquement la relation entre le Borrow et l'utilisateur
            $this->sendCancellationEmail($currentUser, $borrow, $mailer, $parameterBag, $urlGenerator);
            $currentUser->removeBorrow($borrow);
            $borrow->removeUserEmployed($currentUser);
        }

        $this->entityManager->flush();

        $this->alertService->success('L\'emprunt a été annulé avec succès');

        return $this->redirectToRoute('app_borrow_history');
    }

    /**
     * @param UserEmployed $user
     * @param Borrow $borrow
     * @param MailerInterface $mailer
     * @param ParameterBagInterface $parameterBag
     * @param UrlGeneratorInterface $urlGenerator
     * @return void
     * @throws TransportExceptionInterface
     */
    private function sendCancellationEmail(
        UserEmployed $user,
        Borrow $borrow,
        MailerInterface $mailer,
        ParameterBagInterface $parameterBag,
        UrlGeneratorInterface $urlGenerator
    ): void {
        $email = (new TemplatedEmail())
            ->from(new Address($parameterBag->get('mail.support'), 'Votre trajet a été annulé'))
            ->to($user->getEmail())
            ->subject('Annulation de votre réservation et instructions pour reprendre le trajet')
            ->htmlTemplate('borrow/email/email.html.twig')
            ->context([
                'info' => sprintf(
                    'du %s départ du %s pour le %s',
                    $borrow->getBorrowMeet()?->getDate()?->format('d/m/Y'),
                    $borrow->getBorrowMeet()?->getSite(),
                    $borrow->getBorrowMeet()?->getTripDestination()
                ),
                'link_to_reserve_new' => $urlGenerator->generate('app_borrow_passenger'),
            ]);
        $mailer->send($email);
    }
}
