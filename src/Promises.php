<?php

namespace Laravie\Promise;

use function Amp\Promise\wait;
use function Amp\ParallelFunctions\parallelMap;

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
     * Merge promises to actions.
     *
     * @param  callable  $callback
     *
     * @return array
     */
    protected function resolvePromises(callable $callback)
    {
        $promises = wait(parallelMap($this->promises, function ($data) {
            return $this->buildPromise($data);
        }));

        return $callback($promises);
    }
}
