<?php
require_once dirname(__FILE__) . 'bootstrap.php';		
	   require 'vendor/autoload.php';

$config = [
    'client_id' => "55493798955-olcchs77135pb7hu1a758uod4bsl332r.apps.googleusercontent.com",
    'client-secret' => "Iq0yM00-xLMfg7DG6CuD4cCR",
];

$client = new \GuzzleHttp\Client($config);
use GuzzleHttp\Psr7\Response;

$response = new Response();

$response = $client->get('https://www.google.com/m8/feeds/groups/default/full/?alt=json&v=3.0',  [

//$response = $client->get('https://www.google.com/m8/feeds/contacts/default/full/',  [

            'headers' => [
                'Authorization' => "Bearer ya29.cwJXEgnQxRD8Z0O8i1PiDaxyk6XWPbWuF6YnUp8yXzO7xWaXktTUC8Lc1a_hkXN6WhIl"


            ],
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

