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
    protected function resolvePromises(): array
    {
        $promises = [];

        foreach ($this->promises as $data) {
            $promises[] = $this->buildPromise($data);
        }

        return $promises;
    }
}
