<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InternalRegisterTest extends WebTestCase
{
    private KernelBrowser $client;

    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return void
     */
    public function testRegisterSuccess(): void
    {
        $crawler = $this->client->request('GET', '/register/');

        $form = $crawler->selectButton('S\'inscrire')->form();

        $formPrefix = $form->getName();
        $form[$formPrefix . '[firstName]']->setValue('Arnaud');
        $form[$formPrefix . '[lastName]']->setValue('HERVOUET');
        $form[$formPrefix . '[email]']->setValue('new-email.com');
        $form[$formPrefix . '[password]']->setValue('123abcACB%');

        $form[$formPrefix . '[headOffice][name]']->setValue('Mon entreprise 2.0');
        $form[$formPrefix . '[headOffice][address]']->setValue('37 rue Eugène Thomas');
        $form[$formPrefix . '[headOffice][postalCode]']->setValue('44000');
        $form[$formPrefix . '[headOffice][country]']->setValue('FR');
        $form[$formPrefix . '[headOffice][region]']->setValue('Pays de la Loire');

        $this->client->submit($form);

        self::assertResponseStatusCodeSame(200);
    }

    /**
     * Email already exists.
     *
     * @return void
     */
    public function testRegisterFailureEmail(): void
    {
        $crawler = $this->client->request('GET', '/register/');

        $form = $crawler->selectButton('S\'inscrire')->form();

        $formPrefix = $form->getName();
        $form[$formPrefix . '[firstName]']->setValue('Maxime');
        $form[$formPrefix . '[lastName]']->setValue('LE JEUNE');
        $form[$formPrefix . '[email]']->setValue('reichel.zetta@hotmail.com');
        $form[$formPrefix . '[password]']->setValue('123abcACB%');

        $form[$formPrefix . '[headOffice][name]']->setValue('Mon entreprise');
        $form[$formPrefix . '[headOffice][address]']->setValue('37 rue Eugène Thomas');
        $form[$formPrefix . '[headOffice][postalCode]']->setValue('44000');
        $form[$formPrefix . '[headOffice][country]']->setValue('FR');
        $form[$formPrefix . '[headOffice][region]']->setValue('Pays de la Loire');

        $crawler = $this->client->submit($form);
        self::assertResponseIsSuccessful();

        $invalidFeedback = $crawler->filter('form .invalid-feedback');
        self::assertEquals('Un compte existe déjà avec cette adresse email', $invalidFeedback->innerText());
    }
}
