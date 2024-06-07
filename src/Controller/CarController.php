<?php

namespace App\Controller;

use App\DTO\SearchCarDTO;
use App\Entity\User\UserEmployed;
use App\Enum\StatusCars;
use App\Form\SearchFormType;
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
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/', name: '_index')]
    public function index(
        Request $request,
        CarRepository $carRepository,
        #[CurrentUser] UserEmployed $employed,
        PaginatorInterface $paginator
    ): Response {
        $search = new SearchCarDTO();
        $form = $this->createForm(SearchFormType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carsQuery = $carRepository->findSearchCar($search, $employed->getSite());
        } else {
            $carsQuery = $carRepository->findBy(
                [
                    'status' => StatusCars::AVAILABLE,
                    'site' => $employed->getSite(),
                ]
            );
        }
        $cars = $paginator->paginate($carsQuery, $request->query->getInt('page', 1), 9);

        return $this->render('car/index.html.twig', [
            'cars' => $cars,
            'form' => $form->createView(),
        ]);
    }
}
