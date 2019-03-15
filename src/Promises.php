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
}
