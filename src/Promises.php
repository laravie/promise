<?php

namespace Laravie\Promise;

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
        return $this->resolvePromises(function (array $promises) {
            return \React\Promise\all($promises);
        });
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
        return $this->resolvePromises(function (array $promises) use ($callback) {
            return \React\Promise\map($promises, $callback);
        });
    }

    /**
     * Merge promises to actions.
     *
     * @param  callable  $callback
     *
     * @return array
     */
    protected function resolvePromises(callable $callback)
    {
        $promises = [];

        foreach ($this->promises as $data) {
            $promises[] = $this->buildPromise($data);
        }

        return $callback($promises);
    }
}
