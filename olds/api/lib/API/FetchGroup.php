<?php
namespace API;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use API\Group;
class FetchGroup extends Base
{

	public function __construct(){
		parent::__construct();
	}

	function createBulk()
	{

		$groupsArrAPI=array();
		$client = new Client();
		$response = $client->get('https://www.google.com/m8/feeds/groups/default/full/?alt=json&v=3.0',  [
		            'headers' => $this->headers,
		        ]);

		$contents = (string) $response->getBody();
		$temp = json_decode($contents,true);
		
		//$f=0;
		foreach($temp['feed']['entry'] as $key => $cnt) {
			$groupsArrAPI[$key]['name']=$cnt['title']['$t'];
			$groupsArrAPI[$key]['description']=$cnt['content']['$t'];
			$groupsArrAPI[$key]['pictureURL']="-----";
			//$f++;
		/*}

		foreach ($groupsArrAPI as $groupItem)
		{*/
			/*
			$post_opts = array ("http" => array(
				"method" => "POST",
				"header" => array("Accept: application/json",
					"Content-Type: application/x-www-form-urlencoded"),
				"content" => json_encode($groupItem)
			));
			echo json_encode($groupItem);
			$url="http://localhost:9992/slimserver.php/".$this->candidate."/37/groups/";
			$context = stream_context_create($post_opts);
			$post_result = file_get_contents($url, false, $context);

			$data = json_decode($post_result, true);
*/
//die(var_dump($groupItem));
			$group = new Group();
			$response = $group->createGroup($groupsArrAPI[$key]);
			
		}
		return 	$response;
	}
}
