<?php

namespace App\Tests;

use App\Repository\User\UserEmployedRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AccidentControllerTest extends WebTestCase
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
    public function testAccidentDeclarationFailsWhenNotLoggedIn(): void
    {
        $this->client->request('GET', '/borrow/history');

        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsString('/login', $this->client->getResponse()->headers->get('Location'));
    }

    /**
     * @return void
     */
    public function testHistoryDisplaysAccidents(): void
    {
        $userRepository = static::getContainer()->get(UserEmployedRepository::class);
        $user = $userRepository->findOneByEmail('wschneider@schowalter.org');

        $this->client->loginUser($user);
        $this->client->followRedirects();

        $crawler = $this->client->request('GET', '/borrow/history');

        $link = $crawler->selectLink('Déclarer un accident')->link();
        $crawler = $this->client->click($link);

        $form = $crawler->selectButton('Confirmer l\'accident')->form();
        $formPrefix = $form->getName();
        $form->get($formPrefix . '[description]')->setValue('Test Accident');
        $form->get($formPrefix . '[date]')->setValue((new \DateTime())->format('Y-m-d'));

        $this->client->submit($form);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}