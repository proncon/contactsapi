<?php
require_once dirname(__FILE__) . '/bootstrap.php';
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ClientException;
use API\Group;
use API\Contact;

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
	//**********************************Delete ***************************
	if ($argv[1]=="-Delete")
	{
		if (!isset($argv[3]))
			die();
		if (isset($argv[2]) && isset($argv[3]))
		{
			if ($argv[2]=="Group")
			{
				echo "Delete Group";
				$grupo=new Group();
				echo "\n".$grupo->deleteGroup($argv[3])."\n";
				die();
			}

			if ($argv[2]=="Contact")
			{
				echo "Delete Contact";
				$contact=new Contact();
				echo "\n".$contact->deleteContact($argv[3])."\n";
				die();

		  }
			die();
		}
	}
//************************ UPDATE **********************************
	if ($argv[1]=="-Update")
	{
		if (!isset($argv[3]))
			die();
		if ($argv[2]=='Group') //************************ UPDATE GROUP ***************************
		{
			$groupsArr=array();
			parse_str(implode('&', array_slice($argv, 1)), $_GET);
			if (array_key_exists('id',$_GET))
			{
			    $id = $_GET['id'];
					$group=new Group();
					$groupsArr[0]= $group->getGroup($id);

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

			echo $group->putGroup($id, $groupsArr[0]);
			die ();
		}
		if  ($argv[2]=='Contact')
		{
		  //************* UPDATE CONTACT **************************
			//echo "contacto";
			$temp=array();
			$arrUpdate=array();
			$dirty=0;
			parse_str(implode('&', array_slice($argv, 1)), $_GET);
			if (array_key_exists('name',$_GET))
			{
			    $id = $_GET['name'];
					$contact=new Contact();
					$temp= $contact->getAllContacts();

					foreach($temp['feed']['entry'] as $cnt) {
						$tempArr=array();

							if (isset($cnt['gd$name']))
							{
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
								$tempArr['id_addressbook']=$candidateaddressbookid;

										if ($tempArr['name']==$id)
										{
											$arrUpdate[0]=$tempArr;
											if (array_key_exists('phone',$_GET))
											{
											    $description = $_GET['phone'];
													$arrUpdate[0]['phone']=$description;
											}
											if (array_key_exists('pictureUrl',$_GET))
											{
											    $pictureUrl = $_GET['pictureUrl'];
													$arrUpdate[0]['pictureUrl']=$pictureUrl;
											}
											echo $contact->putContact($id, $arrUpdate[0]);
										}
								}

			}

}

		}
	}

//************************ IMPORT *********************************

	if ($argv[1]=="-Import")
			echo "Import Contacts + Groups\n";

			$groupsArr=array();
			$groupsArrAPI=array();

			$group=new Group();
			list($groupsArrAPI,$groupsArr)=$group->getAllGroups();
			if (sizeof($groupsArrAPI)==0)
				die("No Groups");
			$f=0;
			foreach ($groupsArrAPI as $groupItem)
			{
				$grupo=new Group();

				$data=json_decode($grupo->createGroup($groupItem),true);
				$groupsArr[$f]['id_group']=$data[0]['id_group'];
				$f++;
			}


			$contacts = new Contact;
			$temp=$contacts->getAllContacts();

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
					$tempArr['id_addressbook']=$candidateaddressbookid;;

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
