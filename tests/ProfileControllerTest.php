<?php

namespace App\Tests;

use App\Repository\User\UserEmployedRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProfileControllerTest extends WebTestCase
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
    public function testEditProfileFailsWhenNotLoggedIn(): void
    {
        $this->client->request('GET', '/profile/');

        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsString('/login', $this->client->getResponse()->headers->get('Location'));
    }

    /**
     * @return void
     */
    public function testEditProfileSuccess(): void
    {
        $userRepository = static::getContainer()->get(UserEmployedRepository::class);
        $user = $userRepository->findOneByEmail('wschneider@schowalter.org');

        $this->client->loginUser($user);
        $this->client->followRedirects();

        $crawler = $this->client->request('GET', '/profile/edit-profile');

        $form = $crawler->selectButton('Enregistrer les modifications')->form();

        $formPrefix = $form->getName();
        $form[$formPrefix . '[lastname]']->setValue('Bernard');
        $form[$formPrefix . '[firstname]']->setValue('Jean');
        $form[$formPrefix . '[drivingLicense]']->setValue(true);

        $this->client->submit($form);

        self::assertResponseStatusCodeSame(200);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

    }
}
