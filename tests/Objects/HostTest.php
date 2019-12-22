<?php

namespace Objects;

use Mackerel\Objects\Host;

class HostTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $obj = new Host([
            'id' => 'test id',
            'name' => 'test name',
            'type' => 'unknown',
            'status' => 'poweroff',
            'memo' => 'test memo',
            'isRetired' => false,
            'createdAt' => 1481335949,
            'displayName' => 'test display name',
            'customIdentifier' => 'test custom id',
            'meta' => ['agent-name' => 'mackerel-client-php'],
            'roles' => [],
            'interfaces' => [],
        ]);
        $this->assertTrue($obj instanceof Host);
        $this->assertEquals('test name', $obj->name);
    }
}
