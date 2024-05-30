<?php

namespace App\Controller;

use App\Entity\User\UserEmployed;
use App\Enum\StatusCars;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/car', name: 'app_car')]
#[IsGranted('ROLE_EMPLOYED')]
class CarController extends AbstractController
{
    /**
     * @param CarRepository $carRepository
     * @param UserEmployed $employed
     * @return Response
     */
    #[Route('/', name: '_index')]
    public function index(CarRepository $carRepository, #[CurrentUser] UserEmployed $employed): Response
    {
        $cars = $carRepository->findBy(
            [
                'status' => StatusCars::AVAILABLE,
                'site' => $employed->getSite(),
            ]
        );

        return $this->render('car/index.html.twig', [
            'cars' => $cars,
        ]);
    }
}
