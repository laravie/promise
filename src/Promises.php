<?php

namespace Laravie\Promise;

use React\Promise\Promise;
use React\Promise\FulfilledPromise;

class Promises extends Actionable
{
    /**
     * Create a new collection.
     *
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * Queue a promise.
     *
     * @return $this
     */
    public function queue($data)
    {
        $this->resolvePromise($this->buildPromise($data));

        return $this;
    }

    /**
     * Build promises.
     *
     * @return \React\Promise\FulfilledPromise
     */
    protected function buildPromise($data): FulfilledPromise
    {
        $promise = (new Promise(static function ($resolve) use ($data) {
            $resolve($data);
        }));

        foreach ($this->actions as $action) {
            [$method, $parameters] = $action;

            $promise = $promise->{$method}(...$parameters);
        }

        return $promise;
    }
}
