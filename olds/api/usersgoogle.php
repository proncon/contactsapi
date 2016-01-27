<?php
require_once dirname(__FILE__) . '/bootstrap.php';			

$client = new \GuzzleHttp\Client($config);
use GuzzleHttp\Psr7\Response;
use API\Group;
use API\FetchGroup;

if (isset($argv[1]))
{
	if ($argv[1]=="-Import")
		echo "Import Contacts + Groups";
	if ($argv[1]=="-Delete"){
		if (isset($argv[2]) && isset($argv[3]))
		{
			if ($argv[2]=="User")
			{

				echo "Delete User";
				$response = new Response();
				$response = $client->delete('http://localhost:9992/slimserver.php/'.$candidate.'/37/contacts/'.$argv[3].'/',  [
		            'headers' => $headers,
		        ]);
		        echo $response->getStatusCode();
		    }
			if ($argv[2]=="Group")
			{
				echo "Delete Group";
				$grupo=new Group();
				echo "\n".$grupo->deleteGroup($argv[3])."\n";
				//$response = new Response();
				//$response = $client->delete('http://localhost:9992/slimserver.php/'.$candidate.'/37/groups/'.$argv[3].'/',  [
		        //    'headers' => $headers,
		        //]);
		    }

			//$contents = (string) $response->getBody();
			//$body = $response->getBody();
			// Implicitly cast the body to a string and echo it
			//$body = $response->getBody();

		}
	}elseif($argv[1]=="-Sync")
	{
		if ($argv[2]=="Group")
		{
			$grupo= new FetchGroup();

			echo "\n".$grupo->createBulk()."\n";
				//echo "\n".$grupo->deleteGroup($argv[3])."\n";
		}
	}

	die ("end");
}

$groupsArr=array();
$groupsArrAPI=array();

$response = new Response();
$response = $client->get('https://www.google.com/m8/feeds/groups/default/full/?alt=json&v=3.0',  [
            'headers' => $headers,
        ]);

$contents = (string) $response->getBody();
$temp = json_decode($contents,true);
$f=0;
foreach($temp['feed']['entry'] as $cnt) {
	$groupsArr[$f]['name']=$cnt['title']['$t'];
	$groupsArr[$f]['description']=$cnt['content']['$t'];
	$groupsArr[$f]['value']=$cnt['link']['0']['href'];
	$groupsArr[$f]['id_group']=0;

	$groupsArrAPI[$f]['name']=$cnt['title']['$t'];
	$groupsArrAPI[$f]['description']=$cnt['content']['$t'];
	$groupsArrAPI[$f]['pictureURL']="-----";
	$f++;
}

$f=0;
foreach ($groupsArrAPI as $groupItem)
{
	$post_opts = array ("http" => array(
		"method" => "POST",
		"header" => array("Accept: application/json",
			"Content-Type: application/x-www-form-urlencoded"),
		"content" => json_encode($groupItem)
	));
	//echo json_encode($groupItem);
	$url="http://localhost:9992/slimserver.php/".$candidate."/37/groups/";
	$context = stream_context_create($post_opts);
	$post_result = file_get_contents($url, false, $context);

	$data = json_decode($post_result, true);
	//foreach ($data as $d)
	//	echo $d['id'];
	$groupsArr[$f]['id_group']=$data[0]['id'];
	//var_dump($post_result, $http_response_header);
	$f++;
}

//var_dump($groupsArr);
//echo "\n\n\n";


$response = $client->get('https://www.google.com/m8/feeds/contacts/default/full/?alt=json&v=3.0&max-results=50',  [

//$response = $client->get('https://www.google.com/m8/feeds/contacts/default/full/',  [

            'headers' => $headers
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
	$tempArr=array();
	if (isset($cnt['gd$name'])) {
		//echo $cnt['id']['$t']."\n";
		//$tempArr['id']=$cnt['id']['$t'];
		//echo $cnt['title']['$t']."\n";
		//echo $cnt['gd$name']['gd$fullName']['$t']."\n";
		$tempArr['name']=$cnt['gd$name']['gd$fullName']['$t'];
		//print_r($cnt['gd$phoneNumber']);
		$tempArr['phone']="---";
		if (isset($cnt['gd$phoneNumber']['0']['$t'])) 
		{
			//echo $cnt['gd$phoneNumber']['0']['$t']."\n";
			$tempArr['phone']=$cnt['gd$phoneNumber']['0']['$t'];
		}
		else
			if (isset($cnt['gd$phoneNumber']['0']['uri'])) 
			{
				//echo $cnt['gd$phoneNumber']['0']['uri']."\n";
				$tempArr['phone']=$cnt['gd$phoneNumber']['0']['uri'];
			}
		//$tempArr['email']=$cnt['gd$email']['0']['$address'];
		if (isset($cnt['gd$email']['0']['address']))
			$tempArr['email']=$cnt['gd$email']['0']['address'];
		else
			$tempArr['email']="----";
		//echo $cnt['link']['0']['href'];
		if (isset($cnt['link']['0']['href']))
			$tempArr['pictureUrl']=$cnt['link']['0']['href'];
		else
			$tempArr['pictureUrl']="----";
		//echo $tempArr['pictureUrl'];
		$tempArr['id_addressbook']=1;

		if (isset($cnt['gContact$groupMembershipInfo']))
		{
			//print_r($cnt['gContact$groupMembershipInfo']);
			foreach ($cnt['gContact$groupMembershipInfo'] as $item)
			{
				//echo $item['href'];
				foreach($groupsArr as $itemgroupsArr)
				{
					$grp_contact_id=explode("/base/",$item['href']);
					$grp_group_id1=explode("/full/", $itemgroupsArr['value']);
					$grp_group_id2=explode("?", $grp_group_id1[1]);
					//echo $grp_contact_id[1] . " ---> " . $grp_group_id2[0] ."\n";
					if ($grp_group_id2[0]==$grp_contact_id[1])
					{
						$tempArr['groupId']=$itemgroupsArr['id_group'];						
						$post_opts = array ("http" => array(
							"method" => "POST",
							"header" => array("Accept: application/json",
							"Content-Type: application/x-www-form-urlencoded"),
							"content" => json_encode($tempArr)
							));
						$url="http://localhost:9992/slimserver.php/paulo/37/contacts/";
						$context = stream_context_create($post_opts);
						$post_result = file_get_contents($url, false, $context);
						var_dump($post_result, $http_response_header);
					}
				}

			}
		}

		//echo $cnt['link']['0']['href']."\n";
	//echo $cnt['gd$phoneNumber']['0']['uri']."\n";
		//echo json_encode($tempArr);
		//print_r($tempArr);
		$post_opts = array ("http" => array(
			"method" => "POST",
			"header" => array("Accept: application/json",
				"Content-Type: application/x-www-form-urlencoded"),
			"content" => json_encode($tempArr)
		));
		//$url="http://localhost:9992/slimserver.php/paulo/37/contacts/";
		//$context = stream_context_create($post_opts);
		//$post_result = file_get_contents($url, false, $context);
		//var_dump($post_result, $http_response_header);

	}
	//echo $cnt['gd$name']['0']['gd$givenName']."\n";

}

//foreach($temp['feed']['entry'] as $cnt) {
//    echo $cnt['title']['$t'] . " --- " . $cnt['gd$email']['0']['address'];
