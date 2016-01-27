<?php
namespace API;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Group extends Base
{
	protected $client;
	public function __construct(){
		parent::__construct();
		$this->client = new \GuzzleHttp\Client();
	}

	function deleteGroup ($group2delete)
	{

		
		try 
		{
			$response = $this->client->delete('http://localhost:9992/slimserver.php/'.$this->candidate.'/37/groups/'.$group2delete.'/',  [
				'headers' => $this->headers,
			]);
			return $response->getStatusCode();
		}
		catch (RequestException $e)
		{
			$response = $e->getResponse();
			return $response->getStatusCode();
		}
	}
	function createGroup(array $group){
	
		try 
		{
			$response = $this->client->post(
				'http://localhost:9992/slimserver.php/'.$this->candidate.'/37/groups/',  [
				//'headers' => ['Content-Type','application/json'],
				'json' => $group

			]);
			//$body = json_decode();
			 return $response->getBody();
			//return $response->getStatusCode();
		}
		catch (RequestException $e)
		{
			$response = $e->getResponse();
			return $response->getStatusCode();
		}
	}

}


