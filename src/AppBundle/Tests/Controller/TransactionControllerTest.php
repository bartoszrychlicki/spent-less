<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransactionControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testAdd()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/add');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/delete');
    }

    public function testUpdate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/update');
    }

}
