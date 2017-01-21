<?php

namespace Laravie\Promise;

use React\EventLoop\Factory;

class EventPromises extends Actionable
{
    /**
     * Create a new collection.
     *
     * @return $this
     */
    public static function create($loop = null)
    {
        return new static($loop);
    }

    /**
     * Construct async promises.
     *
     * @param object|null $loop
     */
    public function __construct($loop = null)
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
    protected function resolvePromises(callable $callback)
    {
        $promises = [];

        foreach ($this->promises as $data) {
            $this->loop->nextTick(function () use ($data, &$promises) {
                $promises[] = $this->buildPromise($data);
            });
        }

        $this->loop->run();

        return $callback($promises);
    }
}
