<?php

namespace App\Tests;

use App\Controller\BorrowController;
use App\Entity\Borrow;
use App\Entity\BorrowMeet;
use App\Repository\BorrowRepository;
use App\Repository\CarRepository;
use App\Repository\User\UserEmployedRepository;
use App\Service\AlertServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BorrowControllerTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @return void
     */
    public function testBorrowFailsWhenNotLoggedIn(): void
    {

        $this->client->request('GET', '/borrow/history');

        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsString('/login', $this->client->getResponse()->headers->get('Location'));
    }

    /**
     * @return void
     */
    public function testAccessToPassenger(): void
    {
        $userRepository = static::getContainer()->get(UserEmployedRepository::class);
        $user = $userRepository->findOneByEmail('wschneider@schowalter.org');

        $this->client->loginUser($user);
        $this->client->followRedirects();

        $this->client->request('GET', '/borrow/passenger');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return void
     */
    public function borrowCanBeCancelledSuccessfully(): void
    {
        $borrowId = '0x018db196c44c7b618db66bda6bd2ac92';

        $borrow = $this->createMock(Borrow::class);
        $borrow->expects($this->once())
            ->method('getBorrowMeet')
            ->willReturn($this->createMock(BorrowMeet::class));

        $borrowRepository = $this->createMock(BorrowRepository::class);
        $borrowRepository->expects($this->once())
            ->method('find')
            ->with($borrowId)
            ->willReturn($borrow);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->exactly(2))
            ->method('remove')
            ->withConsecutive([$borrow], [$borrow->getBorrowMeet()]);

        $alertService = $this->createMock(AlertServiceInterface::class);
        $alertService->expects($this->once())
            ->method('success')
            ->with('L\'emprunt a été annulé avec succès');

        $borrowController = new BorrowController($entityManager, $this->createMock(CarRepository::class), $alertService, $borrowRepository);
        $response = $borrowController->cancelBorrow($borrowId);

        $this->assertEquals(302, $response->getStatusCode()); // Assuming the route 'app_borrow_history' returns a 302 status code
    }

    /**
     * @return void
     */
    public function borrowReserveSuccessfully(): void
    {
        $userRepository = static::getContainer()->get(UserEmployedRepository::class);
        $user = $userRepository->findOneByEmail('wschneider@schowalter.org');

        $this->client->loginUser($user);
        $this->client->followRedirects();

        $crawler = $this->client->request('GET', '/car');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        $link = $crawler->selectLink('Emprunter')->link();
        $crawler = $this->client->click($link);
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Confirmer')->form();
        $formPrefix = $form->getName();
        $form->get($formPrefix . '[startDate]')->setValue((new \DateTime())->format('Y-m-d'));
        $form->get($formPrefix . '[endDate]')->setValue((new \DateTime())->format('Y-m-d'));
        $form->get($formPrefix . '[borrowMeet][date]')->setValue((new \DateTime())->format('Y-m-d'));

        $siteOptions = $form->get($formPrefix . '[borrowMeet][site]');
        $randomSite = $siteOptions[array_rand($siteOptions)];
        $form->get($formPrefix . '[borrowMeet][site]')->setValue($randomSite);

        $this->client->submit($form);

        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
