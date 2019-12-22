<?php

namespace Objects;

use Mackerel\Objects\Monitor;

class MonitorTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $obj = new Monitor([
            'id' => 'test_monitor_id',
            'type' => 'connectivity',
            'name' => 'connectivity',
            'isMute' => false,
        ]);
        $this->assertTrue($obj instanceof Monitor);
        $this->assertEquals('connectivity', $obj->type);
    }
}
