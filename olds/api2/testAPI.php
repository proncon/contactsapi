<?php
require_once dirname(__FILE__) . '/bootstrap.php';		
//require 'vendor/autoload.php';


$client = new \GuzzleHttp\Client($config);
use GuzzleHttp\Psr7\Response;

$response = new Response();

$response = $client->get(
    'https://www.google.com/m8/feeds/groups/default/full/?alt=json&v=3.0',  
    ['headers' =>  $headers
]);
//var_dump($response->json());
//var_dump($response);
//echo $response->getStatusCode(); // 200
//echo $response->getReasonPhrase(); // OK

//foreach ($response->getHeaders() as $name => $values) {
//    echo $name . ': ' . implode(', ', $values) . "\r\n";
//}
//echo $response->getHeaders();       // >>> HTTP
//echo $response->getProtocolVersion(); // >>> 1.1

$contents = (string) $response->getBody();
//$body = $response->getBody();
// Implicitly cast the body to a string and echo it
//echo ($body);
//print_r ($contents);


$temp = json_decode($contents,true);
//var_dump ($temp);
foreach($temp['feed']['entry'] as $cnt) {
	echo $cnt['title']['$t']."\n";
	echo $cnt['link']['0']['href']."\n";

	//echo $cnt['content']['$t']."\n";
}

