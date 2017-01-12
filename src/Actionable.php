<?php

namespace Laravie\Promise;

use React\Promise\ExtendedPromiseInterface;

abstract class Actionable implements ExtendedPromiseInterface
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
     * Queue "then" for each promises.
     *
     * @return $this
     */
    public function then(callable $onFulfilled = null, callable $onRejected = null, callable $onProgress = null)
    {
        $this->actions[] = ['then', [$onFulfilled, $onRejected, $onProgress]];

        return $this;
    }

    /**
     * Queue "otherwise" for each promises.
     *
     * @return $this
     */
    public function otherwise(callable $onRejected)
    {
        $this->actions[] = ['otherwise', [$onRejected]];

        return $this;
    }

    /**
     * Queue "done" for each promises.
     *
     * @return $this
     */
    public function done(callable $onFulfilled = null, callable $onRejected = null, callable $onProgress = null)
    {
        $this->actions[] = ['done', [$onFulfilled, $onRejected, $onProgress]];

        return $this;
    }

    /**
     * Queue "always" for each promises.
     *
     * @return $this
     */
    public function always(callable $onFulfilledOrRejected)
    {
        $this->actions[] = ['always', [$onFulfilledOrRejected]];

        return $this;
    }

    /**
     * Queue "progress" for each promises.
     *
     * @return $this
     */
    public function progress(callable $onProgress)
    {
        $this->actions[] = ['progress', [$onProgress]];

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

        foreach ($this->attachPromisesToActions() as $promise) {
            $promises[] = $promise;
        }

        return $promises;
    }

    /**
     * Attach promises to generator and yield the promise.
     *
     * @return void
     */
    abstract protected function attachPromisesToActions();
}
