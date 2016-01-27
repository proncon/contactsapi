<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$client = new \GuzzleHttp\Client($config);
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ClientException;

if (!isset($argv[1]))
{
	echo $usage;
	die();
}
else
	if (($argv[1]!="-Delete") && ($argv[1]!="-Import") && ($argv[1]!="-Update"))
	{
		echo $usage;
		die();
	}

if (isset($argv[1]))
{

	if ($argv[1]=="-Delete")
	if (!isset($argv[3]))
		die();
		if (isset($argv[2]) && isset($argv[3]))
		{
			if ($argv[2]=="Contact")
			{
				echo "Delete Contact";
				$response = new Response();
				try
				{
					$response = $client->delete($endpoint."/".$candidate."/".$candidateaddressbookid."/contacts/".$argv[3]."/",  [
			            'headers' => $headers,
			        ]);
			        echo $response->getStatusCode();

				} catch (RuntimeException $e) {
					$response = $e->getResponse();
					echo $response->getStatusCode();
					die();
				}catch(RequestException $e){
					$response = $e->getResponse();
					echo $response->getStatusCode();
					die();
				}
				catch(ClientException $e){
					$response = $e->getResponse();
					echo $response->getStatusCode();
					die();
				}
				die ("\n");
		  }
			if ($argv[2]=="Group")
			{
				echo "Delete Group";
				$response = new Response();
				try
				{
					$response = $client->delete($endpoint."/".$candidate."/".$candidateaddressbookid."/groups/".$argv[3]."/",  [
			            'headers' => $headers
			        ]);
							  echo $response->getStatusCode();
								$body = $response->getBody();
								echo ($body);

				} catch (RuntimeException $e) {
					$response = $e->getResponse();
					echo $response->getStatusCode();
					die();
				}catch(RequestException $e){
					$response = $e->getResponse();
					echo $response->getStatusCode();
					die();
				}
				catch(ClientException $e){
					$response = $e->getResponse();
					echo $response->getStatusCode();
					die();
				}
				die ();
		  }
			die();
		}
//************************ UPDATE **********************************
	if ($argv[1]=="-Update")
	{
		if ($argv[2]=='Group') //************************ UPDATE GROUP ***************************
		{
			$groupsArr=array();
			parse_str(implode('&', array_slice($argv, 1)), $_GET);
			if (array_key_exists('id',$_GET))
			{
			    $id = $_GET['id'];
					$client = new GuzzleHttp\Client();
					try
					{
						$res = $client->get($endpoint."/".$candidate."/".$candidateaddressbookid."/groups/".$id."/");
						//echo "->".$res->getStatusCode();          // 200
						$contents =  json_decode($res->getBody());
						//echo $contents[0]->pictureUrl;
						$groupsArr[0]['pictureUrl']=$contents[0]->pictureURL;
					}
					catch (RuntimeException $e) {
						$response = $e->getResponse();
						echo $response->getStatusCode();
						die();
					}catch(RequestException $e){
						$response = $e->getResponse();
						echo $response->getStatusCode();
						die();
					}
					catch(ClientException $e){
						$response = $e->getResponse();
						echo $response->getStatusCode();
						die();
					}
					try
					{
						$response = $client->get('https://www.google.com/m8/feeds/groups/default/full/'.$id.'/?alt=json&v=3.0',  [
						            'headers' => $headers]);
						$contents = (string) $response->getBody();
						$temp = json_decode($contents,true);
						$groupsArr[0]['name']=$temp['entry']['title']['$t'];
						$groupsArr[0]['description']=$temp['entry']['content']['$t'];
						$groupsArr[0]['value']=$temp['entry']['link']['0']['href'];
						$grp_group_id1=explode("/full/", $groupsArr[0]['value']);
						$grp_group_id2=explode("?", $grp_group_id1[1]);
						$groupsArr[0]['id_group']=$grp_group_id2[0];

					} catch (RuntimeException $e) {
						$response = $e->getResponse();
						echo $response->getStatusCode();
						die();
					}catch(RequestException $e){
						$response = $e->getResponse();
						echo $response->getStatusCode();
						die();
					}
					catch(ClientException $e){
						$response = $e->getResponse();
						echo $response->getStatusCode();
						die();
					}
			}
			else
			{
				die("Obrigatorio id do Grupo\n");
			}
			if (array_key_exists('name',$_GET))
			{
			    $name = $_GET['name'];
					$groupsArr[0]['name']=$name;
			}
			if (array_key_exists('description',$_GET))
			{
			    $description = $_GET['description'];
					$groupsArr[0]['description']=$description;
			}
			if (array_key_exists('pictureUrl',$_GET))
			{
			    $pictureUrl = $_GET['pictureUrl'];
					$groupsArr[0]['pictureUrl']=$pictureUrl;
			}
			print_r($groupsArr[0]);
			try
			{
				$post_opts = array ("http" => array(
					"method" => "PUT",
					"header" => array("Accept: application/json",
					"Content-Type: application/x-www-form-urlencoded"),
					"content" => json_encode($groupsArr[0])
					));

				print_r($groupsArr[0]);
				$url=$endpoint."/".$candidate."/".$candidateaddressbookid."/groups/".$id."/";
				$context = stream_context_create($post_opts);
				$post_result = file_get_contents($url, false, $context);
				$data = json_decode($post_result, true);
				print_r($data);

			} catch (RuntimeException $e) {
				$response = $e->getResponse();
				echo $response->getStatusCode();
				die();
			}
			catch(RequestException $e){
				$response = $e->getResponse();
				echo $response->getStatusCode();
				die();
			}
			catch(ClientException $e){
				$response = $e->getResponse();
				echo $response->getStatusCode();
				die();
			}
			die ();
		}
		if  ($argv[2]=='Contact')
		{
		  //************* UPDATE CONTACT **************************
			echo "contacto";
		}
	}

//************************ IMPORT *********************************

	if ($argv[1]=="-Import")
			echo "Import Contacts + Groups";

			$groupsArr=array();
			$groupsArrAPI=array();

			$response = new Response();
			try
			{
				$response = $client->get('https://www.google.com/m8/feeds/groups/default/full/?alt=json&v=3.0',  [
				            'headers' => $headers,]);

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
					//echo $id_grupo;
				}
			}
			catch (RuntimeException $e) {
				$response = $e->getResponse();
				echo $response->getStatusCode();
				die();
			}catch(RequestException $e){
				$response = $e->getResponse();
				echo $response->getStatusCode();
				die();
			}
			catch(ClientException $e){
				$response = $e->getResponse();
				echo $response->getStatusCode();
				die();
			}

			$f=0;
			foreach ($groupsArrAPI as $groupItem)
			{
				//print_r($groupItem);
				$post_opts = array ("http" => array(
					"method" => "POST",
					"header" => array("Accept: application/json",
						"Content-Type: application/x-www-form-urlencoded"),
					"content" => json_encode($groupItem)
				));
				$url=$endpoint."/".$candidate."/".$candidateaddressbookid."/groups/";
				$context = stream_context_create($post_opts);
				$post_result = file_get_contents($url, false, $context);

				$data = json_decode($post_result, true);
				$groupsArr[$f]['id_group']=$data[0]['id_group'];
				$f++;
			}

			$response = $client->get('https://www.google.com/m8/feeds/contacts/default/full/?alt=json&v=3.0&max-results=50000',
						[ 'headers' => $headers]);
			$contents = (string) $response->getBody();
			$temp = json_decode($contents,true);

			foreach($temp['feed']['entry'] as $cnt) {
				$tempArr=array();
				if (isset($cnt['gd$name'])) {
					$tempArr['name']=$cnt['gd$name']['gd$fullName']['$t'];
					$tempArr['phone']="---";
					if (isset($cnt['gd$phoneNumber']['0']['$t']))
					{
						$tempArr['phone']=$cnt['gd$phoneNumber']['0']['$t'];
					}
					else
						if (isset($cnt['gd$phoneNumber']['0']['uri']))
						{
							$tempArr['phone']=$cnt['gd$phoneNumber']['0']['uri'];
						}
					if (isset($cnt['gd$email']['0']['address']))
						$tempArr['email']=$cnt['gd$email']['0']['address'];
					else
						$tempArr['email']="----";
					if (isset($cnt['link']['0']['href']))
						$tempArr['pictureUrl']=$cnt['link']['0']['href'];
					else
						$tempArr['pictureUrl']="----";
					$tempArr['id_addressbook']=1;

					if (isset($cnt['gContact$groupMembershipInfo']))
					{
						foreach ($cnt['gContact$groupMembershipInfo'] as $item)
						{
							foreach($groupsArr as $itemgroupsArr)
							{
								$grp_contact_id=explode("/base/",$item['href']);
								$grp_group_id1=explode("/full/", $itemgroupsArr['value']);
								$grp_group_id2=explode("?", $grp_group_id1[1]);

								if ($grp_group_id2[0]==$grp_contact_id[1])
								{
									$tempArr['groupId']=$grp_contact_id[1];

									$post_opts = array ("http" => array(
										"method" => "POST",
										"header" => array("Accept: application/json",
										"Content-Type: application/x-www-form-urlencoded"),
										"content" => json_encode($tempArr)
										));
									$url=$endpoint."/".$candidate."/".$candidateaddressbookid."/contacts/";
									$context = stream_context_create($post_opts);
									$post_result = file_get_contents($url, false, $context);
								}
							}
						}
					}
					$post_opts = array ("http" => array(
						"method" => "POST",
						"header" => array("Accept: application/json",
							"Content-Type: application/x-www-form-urlencoded"),
						"content" => json_encode($tempArr)
					));

				}
			}
}
