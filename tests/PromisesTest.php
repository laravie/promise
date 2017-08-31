<?php

use Laravie\Promise\Promises;

class PromisesTest extends PHPUnit\Framework\TestCase
{
    /** @test */
    public function it_can_throws_exception()
    {
        $promises = Promises::create();

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
        ->then(function ($values) {
            $this->assertEquals([4, 5], $values);
        });
    }
}
