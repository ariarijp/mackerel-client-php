<?php

namespace Objects;

use Mackerel\Objects\GraphAnnotation;

class GraphAnnotationTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $obj = new GraphAnnotation([
            'title' => 'test title',
            'description' => 'test desc',
            'from' => 1575123904,
            'to' => 1575123904,
            'service' => 'test service',
            'roles' => ['test role'],
        ]);
        $this->assertTrue($obj instanceof GraphAnnotation);
        $this->assertEquals('test title', $obj->title);
    }
}
