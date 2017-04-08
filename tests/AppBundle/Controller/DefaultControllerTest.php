<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\DatabasePrimer;

class DefaultControllerTest extends WebTestCase
{
    public function setUp()
    {
        self::bootKernel();

        DatabasePrimer::prime(self::$kernel);
    }

    /**
     * @dataProvider urlProvider
     */
    public function testLoginIsSuccessful($url)
    {
        $client = $this->getClient($url);

        $response = $client->getResponse();
        $crawler = $client->getCrawler();

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Create new Folder")')->count());
    }

    public function urlProvider()
    {
        return [
            ['/en/manager'],
        ];
    }

    /**
     * @param $url
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    public function getClient($url): \Symfony\Bundle\FrameworkBundle\Client
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', $url);

        $form = $crawler->selectButton('Log in')->form();

        // Set username and password
        $form['_username'] = 'test';
        $form['_password'] = 'demo';

        $client->submit($form);
        return $client;
    }
}
