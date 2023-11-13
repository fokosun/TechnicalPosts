<?php

use GuzzleHttp\Exception\GuzzleException;
use Practice\Http\Controllers\Controller;

require 'vendor/autoload.php';

$client = new Controller($_POST["website"] ?? "", false);

try {
    var_dump($client->makeGetRequest());
} catch (GuzzleException $e) {
    var_dump($e->getMessage());
}
