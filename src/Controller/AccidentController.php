<?php

namespace App\Controller;

use App\Entity\Accident;
use App\Entity\User\UserEmployed;
use App\Enum\StatusCars;
use App\Form\AccidentType;
use App\Repository\CarRepository;
use App\Service\AlertServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/accident', name: 'app_accident')]
class AccidentController extends AbstractController
{
    /**
     * @param string $id
     * @param Request $request
     * @param CarRepository $carRepository
     * @param EntityManagerInterface $entityManager
     * @param UserEmployed $user
     * @param AlertServiceInterface $alertService
     *
     * @return Response
     */
    #[Route('/create/{id}', name: '_index')]
    public function index(
        string $id,
        Request $request,
        CarRepository $carRepository,
        EntityManagerInterface $entityManager,
        #[CurrentUser] UserEmployed $user,
        AlertServiceInterface $alertService
    ): Response {
        $accident = new Accident();
        $form = $this->createForm(AccidentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accident->setDescription($form->get('description')->getData());
            $accident->setDate($form->get('date')->getData());
            $accident->setUserEmployed($user);

            $car = $carRepository->find($id);
            $car?->setStatus(StatusCars::REPAIR);

            $accident->setCar($car);

            $entityManager->persist($accident);
            $entityManager->flush();

            $alertService->success('Accident déclaré avec succès');

            return $this->redirectToRoute('app_borrow_history');
        }
        return $this->render('accident/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param UserEmployed $employed
     * @return Response
     */
    #[Route('/history', name: '_history')]
    public function history(#[CurrentUser] UserEmployed $employed): Response
    {
        return $this->render('accident/history.html.twig', [
            'accidents_history' => $employed->getAccidents(),
        ]);
    }
}
