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
     * All promises.
     *
     * @return \React\Promise\PromiseInterface|mixed
     */
    public function all()
    {
        return \React\Promise\all($this->resolvePromises());
    }

    /**
     * Map promises.
     *
     * @param callable $callback
     *
     * @return \React\Promise\PromiseInterface|mixed
     */
    public function map(callable $callback)
    {
        return \React\Promise\map($this->resolvePromises(), $callback);
    }

    /**
     * Merge promises to actions.
     *
     * @return array
     */
    protected function resolvePromises()
    {
        $promises = [];

        foreach ($this->buildPromises() as $promise) {
            $promises[] = $promise;
        }

        return $promises;
    }

    /**
     * Build promises.
     *
     * @return void
     */
    protected function buildPromises()
    {
        foreach ($this->promises as $data) {
            $promise = new Promise(function ($resolve) use ($data) {
                $resolve($data);
            });

            yield $this->attachPromisesToActions($promise);
        }
    }
}
