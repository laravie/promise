<?php

namespace Laravie\Promise;

use function Amp\Promise\wait;
use function Amp\ParallelFunctions\parallelMap;

class ParallelPromises extends Actionable
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
     * Merge promises to actions.
     *
     * @param  callable  $callback
     *
     * @return array
     */
    protected function resolvePromises(): array
    {
        return wait(parallelMap($this->promises, function ($promise) {
            return $this->buildPromise($promise);
        }));
    }
}
