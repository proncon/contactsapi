<?php
namespace API;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Contact extends Base
{
	protected $client;
	public function __construct(){
		parent::__construct();
		$this->client = new \GuzzleHttp\Client();
	}

	function deleteContact ($contact2delete)
	{
		try
		{
			$response =$this->client->delete(
					$this->endpoint.'/'.$this->candidate.'/'.$this->candidateaddressbookid.'/contacts/'.$contact2delete.'/',  [
			    'headers' => $this->headers,
			]);
			return $response->getStatusCode();
		}
		catch (RequestException $e)
		{
			$response = $e->getResponse();
			return $response->getStatusCode();
		}
		catch (RuntimeException $e) {
			$response = $e->getResponse();
			return $response->getStatusCode();
			echo "\nToken expirado\n";
		}
		catch(ClientException $e){
			$response = $e->getResponse();
			return $response->getStatusCode();
		}
	}

	function createContact(array $contact){

		try
		{
			$response = $this->client->post(
					$this->endpoint.'/'.$this->candidate.'/'.$this->candidateaddressbookid.'/contacts/',  [
					'json' => $contact
			]);
			 return $response->getBody();
		}
		catch (RequestException $e)
		{
			$response = $e->getResponse();
			return $response->getStatusCode();
		}
		catch (RuntimeException $e) {
			$response = $e->getResponse();
			return $response->getStatusCode();
			echo "\nToken expirado\n";
		}
		catch(ClientException $e){
			$response = $e->getResponse();
			return $response->getStatusCode();
		}
	}

	function getAllContacts(){

		try
		{
			$response = $this->client->get('https://www.google.com/m8/feeds/contacts/default/full/?alt=json&v=3.0&max-results=2',
						[ 'headers' => $this->headers]);
			$contents = (string) $response->getBody();
			return json_decode($contents,true);

		}
		catch (RequestException $e)
		{
			$response = $e->getResponse();
			return $response->getStatusCode();
		}
		catch (RuntimeException $e) {
			$response = $e->getResponse();
			return $response->getStatusCode();
			echo "\nToken expirado\n";
		}
		catch(ClientException $e){
			$response = $e->getResponse();
			return $response->getStatusCode();
		}
	}

	function putContact ($id, $arr) //PUT
	{

		try
		{
			$post_opts = array ("http" => array(
				"method" => "PUT",
				"header" => array("Accept: application/json",
				"Content-Type: application/x-www-form-urlencoded"),
				"content" => json_encode($arr)
				));
			$context = stream_context_create($post_opts);
			if (!$post_result = @file_get_contents($this->endpoint."/".$this->candidate."/".$this->candidateaddressbookid."/contacts/".$id."/", false, $context)) {
				$error = error_get_last();
      	echo "400";
				die();
			}

			$data = json_decode($post_result, true);
			return $data;

		}
		catch (RuntimeException $e) {
			$response = $e->getResponse();
			return $response->getStatusCode();
		}
		catch(RequestException $e){
			$response = $e->getResponse();
			return $response->getStatusCode();
		}
		catch(ClientException $e){
			$response = $e->getResponse();
			return $response->getStatusCode();
		}

	}


}
