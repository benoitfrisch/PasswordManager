<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ItemControllerTest extends WebTestCase
{
    /**
     * @dataProvider protectedUrlProvider
     */
    public function testPagesAreProperlyProtected($url)
    {
        $client = $client = self::createClient();

        $client->request('GET', $url);

        $this->assertSame($client->getResponse()->getStatusCode(), 302);
        $this->assertSame($client->getResponse()->headers->get('Location'), 'http://localhost/login');
    }

    public function protectedUrlProvider()
    {
        return [
            ['/en/manager/123'],
            ['/en/manager/item/log/123'],
            ['/en/manager/create/123'],
            ['/en/manager/123/edit/456'],
            ['/en/manager/123/delete/456/xyz'],
        ];
    }

}
