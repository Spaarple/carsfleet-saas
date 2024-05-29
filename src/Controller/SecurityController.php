<?php

namespace App\Controller;

use App\Enum\Role;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils,): Response
    {
        $user = $this->getUser()?->getRoles()[0];

        if ($user === Role::ROLE_ADMINISTRATOR_SITE->name
            || $user === Role::ROLE_ADMINISTRATOR_HEAD_OFFICE->name
            || $user === Role::ROLE_SUPER_ADMINISTRATOR) {
            return $this->redirectToRoute('admin');
        }

        if ($this->getUser()?->getRoles()[0] === Role::ROLE_EMPLOYED->name) {
            return $this->redirectToRoute('app_car_index');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @return void
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }
}
