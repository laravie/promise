<?php

namespace Laravie\Promise;

use React\Promise\Promise;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Promise\FulfilledPromise;

class EventPromises extends Actionable
{
    /**
     * List of promises.
     *
     * @var array
     */
    protected $promises = [];

    /**
     * The event loop implementation.
     *
     * @var \React\EventLoop\LoopInterface
     */
    protected $eventLoop;

    /**
     * Create a new promises.
     *
     * @param \React\EventLoop\LoopInterface|null $loop
     *
     * @return static
     */
    public static function create(LoopInterface $eventLoop = null)
    {
        return new static($eventLoop);
    }

    /**
     * Construct async promises.
     *
     * @param \React\EventLoop\LoopInterface|null $loop
     */
    public function __construct(LoopInterface $eventLoop = null)
    {
        if (\is_null($eventLoop)) {
            $eventLoop = Factory::create();
        }

        $this->eventLoop = $eventLoop;
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
     * Run the event loop.
     *
     * @return array
     */
    public function run(): void
    {
        $promises = [];

        foreach ($this->promises as $data) {
            $this->eventLoop->futureTick(function () use ($data, &$promises) {
                $this->resolvePromise($this->buildPromise($data));
            });
        }

        $this->eventLoop->run();
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
