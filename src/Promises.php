<?php

namespace Laravie\Promise;

class Promises extends Actionable
{
    /**
     * Create a new collection.
     *
     * @return static
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

        foreach ($this->promises as $promise) {
            $promises[] = $this->buildPromise($promise);
        }

        return $promises;
    }
}
