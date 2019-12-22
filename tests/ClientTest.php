<?php

use Mackerel\Client;

class ClientTest extends PHPUnit_Framework_TestCase
{
    public function testName()
    {
        $client = new Client([
            'mackerel_api_key' => 'supersecretkey',
        ]);
        $this->assertTrue($client instanceof Client);
    }
}
