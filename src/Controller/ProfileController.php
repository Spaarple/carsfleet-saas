<?php

namespace App\Controller;

use App\Entity\User\AbstractUser;
use App\Form\EditPasswordType;
use App\Form\EditProfileType;
use App\Form\Model\ChangePassword;
use App\Service\AlertServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile', name: 'app_profile')]
class ProfileController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AlertServiceInterface $alertService
    )
    {}

    /**
     * @return Response
     */
    #[IsGranted('ROLE_EMPLOYED')]
    #[Route('/', name: '_index')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig');
    }

    /**
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    #[IsGranted('ROLE_EMPLOYED')]
    #[Route('/change-password', name: '_change_password')]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $changePasswordModel = new ChangePassword();
        $form = $this->createForm(EditPasswordType::class, $changePasswordModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var AbstractUser $user */
            $user = $this->getUser();

            $password = $form->get('newPassword')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $password));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->alertService->success('Votre mot de passe a été mis à jour.');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('profile/change_password.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @param Request $request
     * @param AbstractUser $user
     *
     * @return Response
     */
    #[IsGranted('ROLE_EMPLOYED')]
    #[Route('/edit-profile', name: '_edit_profile')]
    public function editProfile(Request $request, #[CurrentUser] AbstractUser $user): Response
    {
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->alertService->success('Vos informations ont été mises à jour.');

            return $this->redirectToRoute('app_profile_index');
        }

        return $this->render('profile/edit_profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return Response
     */
    #[IsGranted('ROLE_ADMINISTRATOR_HEAD_OFFICE')]
    #[Route('/cancel-subscription', name: '_cancel_subscription')]
    public function cancelSubscription(): Response
    {
        return $this->redirect('https://billing.stripe.com/p/login/test_3csdUw2aB5cMfNm3cc');
    }
}
