<?php

use Laravie\Promise\EventPromises;

class EventPromisesTest extends PHPUnit\Framework\TestCase
{
    /** @test */
    public function it_can_throws_exception()
    {
        $results = [];
        $promises = EventPromises::create();

        $promises->then(function ($value) {
            return $value + 1;
        })
        ->then(function ($value) {
            throw new Exception($value + 1);
        })
        ->otherwise(function ($value) {
            return $value->getMessage() + 1;
        })
        ->queues([1, 2])
        ->all()
        ->then(function ($values) use (&$results) {
            $results = $values;
        });


        $this->assertEquals([4, 5], $results);
    }
}
