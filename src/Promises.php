<?php

namespace Laravie\Promise;

use React\Promise\Promise;

class Promises extends Actionable
{
    /**
     * Create a new collection.
     *
     * @return $this
     */
    public static function create()
    {
        return new static();
    }

    /**
     * Attach promises to generator and yield the promise.
     *
     * @return void
     */
    protected function attachPromisesToActions()
    {
        foreach ($this->promises as $data) {
            $promise = new Promise(function ($resolve) use ($data) {
                $resolve($data);
            });

            foreach ($this->actions as $action) {
                list($method, $parameters) = $action;

                $promise = $promise->{$method}(...$parameters);
            }

            yield $promise;
        }
    }
}
