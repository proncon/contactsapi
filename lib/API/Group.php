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

	function deleteGroup ($group2delete) //DELETE
	{
		try
		{
			$response = $this->client->delete($this->endpoint.'/'.$this->candidate.'/'.$this->candidateaddressbookid.'/groups/'.$group2delete.'/',  [
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
	function createGroup(array $group){ //POST

		try
		{
			$response = $this->client->post(
				$this->endpoint.'/'.$this->candidate.'/'.$this->candidateaddressbookid.'/groups/',  [
				'json' => $group

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

	function getAllGroups(){ //LIST
		try
		{
			$response = $this->client->get('https://www.google.com/m8/feeds/groups/default/full/?alt=json&v=3.0',  [
			            'headers' => $this->headers,]);

			$contents = (string) $response->getBody();
			$temp = json_decode($contents,true);
			$f=0;
			foreach($temp['feed']['entry'] as $cnt) {
				//echo $cnt['title']['$t'];
				$groupsArr[$f]['name']=$cnt['title']['$t'];
				$groupsArr[$f]['description']=$cnt['content']['$t'];
				$groupsArr[$f]['value']=$cnt['link']['0']['href'];
				$grp_group_id1=explode("/full/", $groupsArr[$f]['value']);
				$grp_group_id2=explode("?", $grp_group_id1[1]);
				$id_grupo=$grp_group_id2[0];
				$groupsArr[$f]['id_group']=0;

				$groupsArrAPI[$f]['name']=$cnt['title']['$t'];
				$groupsArrAPI[$f]['description']=$cnt['content']['$t'];
				$groupsArrAPI[$f]['pictureUrl']="-----";
				$groupsArrAPI[$f]['id_group']=$id_grupo;
				$f++;
			}
	    return array($groupsArrAPI,$groupsArr);

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


	//******************************** GETGroupElement ************************************
	function getGroup($id) //GET
	{
		try
		{
			$res = $this->client->get($this->endpoint."/".$this->candidate."/".$this->candidateaddressbookid."/groups/".$id."/");
			//echo "->".$res->getStatusCode();          // 200
			$contents =  json_decode($res->getBody());
			//echo $contents[0]->pictureUrl;
			$groupsArr[0]['pictureUrl']=$contents[0]->pictureURL;
		}
		catch (RuntimeException $e) {
			$response = $e->getResponse();
			return $response->getStatusCode();
		}catch(RequestException $e){
			$response = $e->getResponse();
			return $response->getStatusCode();
		}
		catch(ClientException $e){
			$response = $e->getResponse();
			return $response->getStatusCode();
		}
		try
		{
			$response = $this->client->get('https://www.google.com/m8/feeds/groups/default/full/'.$id.'/?alt=json&v=3.0',  [
									'headers' => $this->headers]);
			$contents = (string) $response->getBody();
			$temp = json_decode($contents,true);
			$groupsArr[0]['name']=$temp['entry']['title']['$t'];
			$groupsArr[0]['description']=$temp['entry']['content']['$t'];
			$groupsArr[0]['value']=$temp['entry']['link']['0']['href'];
			$grp_group_id1=explode("/full/", $groupsArr[0]['value']);
			$grp_group_id2=explode("?", $grp_group_id1[1]);
			$groupsArr[0]['id_group']=$grp_group_id2[0];
			return $groupsArr[0];

		} catch (RuntimeException $e) {
			$response = $e->getResponse();
			return $response->getStatusCode();
		}catch(RequestException $e){
			$response = $e->getResponse();
			return $response->getStatusCode();
		}
		catch(ClientException $e){
			$response = $e->getResponse();
			return $response->getStatusCode();
		}
	}

	function putGroup ($id, $arr) //PUT
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
			if (!$post_result = @file_get_contents($this->endpoint."/".$this->candidate."/".$this->candidateaddressbookid."/groups/".$id."/", false, $context)) {
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
