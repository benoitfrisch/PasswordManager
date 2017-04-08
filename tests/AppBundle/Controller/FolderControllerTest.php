<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FolderControllerTest extends WebTestCase
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
            ['/en/manager'],
            ['/en/manager/hidden_list'],
            ['/en/manager/create_folder'],
            ['/en/manager/edit_folder/123'],
            ['/en/manager/createFolder/123'],
            ['/en/manager/123/delete/xyz'],
        ];
    }

}
