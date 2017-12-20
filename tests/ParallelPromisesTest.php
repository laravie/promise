<?php

use Laravie\Promise\ParallelPromises;

class ParallelPromisesTest extends PHPUnit\Framework\TestCase
{
    /** @test */
    public function it_can_handle_async_when_given_object()
    {
        $results = [];
        $promises = ParallelPromises::create();

        $promises->then(function ($value) {
            $value->name .= 'bar';

            return $value;
        })
        ->then(function ($value) {
            $value->age += 10;

            return $value;
        })
        ->queues([(object) ['name' => 'foo', 'age' => 10]])
        ->all()
        ->then(function ($values) use (&$results) {
            $results = $values;
        });

        $this->assertSame('foobar', $results[0]->name);
        $this->assertSame(20, $results[0]->age);
    }

    /** @test */
    public function it_can_throws_exception()
    {
        $results = [];
        $promises = ParallelPromises::create();

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
