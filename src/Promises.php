<?php

namespace Laravie\Promise;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use React\Promise\Deferred;
use React\Promise\Promise;

class Promises
{
    /**
     * Lists of actions.
     *
     * @var array
     */
    protected $actions = [];

    /**
     * List of promises.
     *
     * @var array
     */
    protected $promises = [];

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
     * Queue "then" for each promises.
     *
     * @return $this
     */
    public function then(...$parameters)
    {
        $this->actions[] = ['then', $parameters];

        return $this;
    }

    /**
     * Queue "otherwise" for each promises.
     *
     * @return $this
     */
    public function otherwise(...$parameters)
    {
        $this->actions[] = ['otherwise', $parameters];

        return $this;
    }

    /**
     * Queue "done" for each promises.
     *
     * @return $this
     */
    public function done(...$parameters)
    {
        $this->actions[] = ['done', $parameters];

        return $this;
    }

    /**
     * Queue multiple promises.
     *
     * @param \Illuminate\Support\Collection|\Illuminate\Contracts\Support\Arrayable|array $collection
     *
     * @return $this
     */
    public function queues($collection)
    {
        if ($collection instanceof Collection) {
            $collection = $collection->all();
        } elseif ($collection instanceof Arrayable) {
            $collection = $collection->toArray();
        }

        foreach ($collection as $data) {
            $this->queue($data);
        }

        return $this;
    }

    /**
     * Queue a promise.
     *
     * @return $this
     */
    public function queue($data)
    {
        $this->promises[] = $data;

        return $this;
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

        foreach ($this->promises as $data) {
            $deferred = new Deferred();
            $promise = $deferred->promise();

            foreach ($this->actions as $action) {
                list($method, $parameters) = $action;

                $promise = $promise->{$method}(...$parameters);
            }

            $promises[] = $deferred->resolve($data);
        }

        return $promises;
    }
}
