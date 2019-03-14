<?php

namespace Laravie\Promise;

use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;

class EventPromises extends Actionable
{
    /**
     * The event-loop implementation.
     *
     * @var \React\EventLoop\LoopInterface
     */
    protected $eventLoop;

    /**
     * Create a new collection.
     *
     * @param \React\EventLoop\LoopInterface|null $eventLoop
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
     * @param \React\EventLoop\LoopInterface|null $eventLoop
     */
    public function __construct(LoopInterface $eventLoop = null)
    {
        if (\is_null($eventLoop)) {
            $eventLoop = Factory::create();
        }

        $this->eventLoop = $eventLoop;
    }

    /**
     * Merge promises to actions.
     *
     * @return array
     */
    protected function resolvePromises(): array
    {
        $promises = [];

        foreach ($this->promises as $data) {
            $this->eventLoop->futureTick(function () use ($data, &$promises) {
                $promises[] = $this->buildPromise($data);
            });
        }

        $this->eventLoop->run();

        return $promises;
    }
}
