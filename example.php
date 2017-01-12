<?php

require "vendor/autoload.php";

$promises = Laravie\Promise\EventPromises::create();

$promises->then(function ($url) {
    print("Fetching {$url}".PHP_EOL);
    return [$url, file_get_contents($url)];
})
->then(function ($value) {
    printf("Fetched %s Receiving %d bytes\n", $value[0], strlen($value[1]));
})
->queues(['https://google.com', 'https://www.katsana.com', 'https://kakitangan.com'])
->all();
