<?php
		
require 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

$client = new Client();
$response = new Response();
$response = $client->get("https://api.github.com/");
//var_dump($response->json());
echo $response->getStatusCode(); // 200
echo $response->getReasonPhrase(); // OK

foreach ($response->getHeaders() as $name => $values) {
    echo $name . ': ' . implode(', ', $values) . "\r\n";
}

$body = $response->getBody();
// Implicitly cast the body to a string and echo it
echo $body;