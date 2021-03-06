<?php

namespace Laravie\Promise;

use React\Promise\Promise;
use function React\Promise\all;
use function React\Promise\map;
use React\Promise\PromiseInterface;
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
     * List of resolved promises.
     *
     * @var array
     */
    protected $resolvedPromises = [];

    /**
     * Queue "then" for each promises.
     *
     * @return $this
     */
    final public function then(callable $onFulfilled = null, callable $onRejected = null, callable $onProgress = null)
    {
        $this->actions[] = ['then', [$onFulfilled, $onRejected, $onProgress]];

        return $this;
    }

    /**
     * Queue "otherwise" for each promises.
     *
     * @return $this
     */
    final public function otherwise(callable $onRejected)
    {
        $this->actions[] = ['otherwise', [$onRejected]];

        return $this;
    }

    /**
     * Queue "done" for each promises.
     *
     * @return $this
     */
    final public function done(callable $onFulfilled = null, callable $onRejected = null, callable $onProgress = null)
    {
        $this->actions[] = ['done', [$onFulfilled, $onRejected, $onProgress]];

        return $this;
    }

    /**
     * Queue "always" for each promises.
     *
     * @return $this
     */
    final public function always(callable $onFulfilledOrRejected)
    {
        $this->actions[] = ['always', [$onFulfilledOrRejected]];

        return $this;
    }

    /**
     * Queue "progress" for each promises.
     *
     * @return $this
     */
    final public function progress(callable $onProgress)
    {
        $this->actions[] = ['progress', [$onProgress]];

        return $this;
    }

    /**
     * Queue multiple promises.
     *
     * @param iterable $collection
     *
     * @return $this
     */
    public function queues(iterable $collection)
    {
        foreach ($collection as $data) {
            $this->queue($data);
        }

        return $this;
    }

    /**
     * All promises.
     *
     * @return \React\Promise\PromiseInterface|mixed
     */
    public function all()
    {
        return all($this->resolvedPromises);
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
        return map($this->resolvedPromises, $callback);
    }

    /**
     * Resolved a promise.
     *
     * @param  \React\Promise\PromiseInterface  $promise
     *
     * @return void
     */
    protected function resolvePromise(PromiseInterface $promise): void
    {
        $this->resolvedPromises[] = $promise;
    }

    /**
     * Queue a promise.
     *
     * @param  mixed  $data
     *
     * @return $this
     */
    abstract public function queue($data);
}
