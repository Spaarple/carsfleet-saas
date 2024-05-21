<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InternalLoginTest extends WebTestCase
{
    /**
     * @param string $email
     * @param string $password
     * @param bool $valid
     * @return void
     *
     * @dataProvider loginProvider
     * @runInSeparateProcess
    */
    public function testLoginSuccess(string $email, string $password, bool $valid): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();

        $form['email']->setValue($email);
        $form['password']->setValue($password);

        $client->submit($form);

        if ($valid) {
            self::assertResponseRedirects('/');
            $client->followRedirect();
            self::assertResponseIsSuccessful();
        } else {
            self::assertResponseRedirects('/login');
            $client->followRedirect();
            self::assertResponseStatusCodeSame(200);
        }
    }

    /**
     * @return array[]
     */
    protected function loginProvider(): array
    {
        return [
            ['wschneider@schowalter.org', '123abcABC%', true],
            ['testemail@gmail.com', '123abcABC%', false],
            ['wschneider@schowalter.org', 'Wr0nG_P4sSw0rD', false],
        ];
    }


}
