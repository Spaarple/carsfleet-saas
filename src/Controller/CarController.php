<?php

namespace App\Controller;

use App\Entity\User\UserEmployed;
use App\Enum\StatusCars;
use App\Repository\CarRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/car', name: 'app_car')]
#[IsGranted('ROLE_EMPLOYED')]
class CarController extends AbstractController
{
    /**
     * @param Request $request
     * @param CarRepository $carRepository
     * @param UserEmployed $employed
     * @param PaginatorInterface $pagination
     * @return Response
     */
    #[Route('/', name: '_index')]
    public function index(
        Request $request,
        CarRepository $carRepository,
        #[CurrentUser] UserEmployed $employed,
        PaginatorInterface $pagination
    ): Response {
        $cars = $carRepository->findBy(
            [
                'status' => StatusCars::AVAILABLE,
                'site' => $employed->getSite(),
            ]
        );

        /** @var PaginatorInterface $pagination */
        $pagination = $pagination->paginate(
            $cars,
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('car/index.html.twig', [
            'cars' => $pagination,
        ]);
    }
}
