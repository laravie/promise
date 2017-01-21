<?php

namespace Laravie\Promise;

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
        $promises = [];

        foreach ($this->promises as $data) {
            $promises[] = $this->buildPromise($data);
        }

        return $callback($promises);
    }
}
