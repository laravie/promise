<?php

require "vendor/autoload.php";

Laravie\Promise\Promises::create()
    ->then(function ($url) {
        print("Fetching {$url}".PHP_EOL);
        return [$url, file_get_contents($url)];
    })
    ->then(function ($value) {
        printf("Fetched %s Receiving %d bytes\n", $value[0], strlen($value[1]));
    })
    ->queues(['https://google.com', 'http://katsana.demo', 'https://www.katsana.com', 'https://kakitangan.com'])
    ->all();
