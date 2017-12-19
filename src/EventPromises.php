<?php

namespace Laravie\Promise;

use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;

class EventPromises extends Actionable
{
    /**
     * Create a new collection.
     *
     * @param \React\EventLoop\LoopInterface|null $loop
     *
     * @return $this
     */
    public static function create(LoopInterface $loop = null)
    {
        return new static($loop);
    }

    /**
     * Construct async promises.
     *
     * @param \React\EventLoop\LoopInterface|null $loop
     */
    public function __construct(LoopInterface $loop = null)
    {
        if (is_null($loop)) {
            $loop = Factory::create();
        }

        $this->loop = $loop;
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
            $this->loop->nextTick(function () use ($data, &$promises) {
                $promises[] = $this->buildPromise($data);
            });
        }

        $this->loop->run();

        return $promises;
    }
}
