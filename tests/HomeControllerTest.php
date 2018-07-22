<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testSearchForm()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $form = $crawler->filter('.form-wrap')->selectButton("Search")->form();
        $form['search[showDate]'] = '2018-08-04';
        $client->followRedirects(true);
        $client->submit($form);
        $this->assertContains("section",  $client->getResponse()->getContent());
    }
}
