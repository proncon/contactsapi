<?php
require_once 'vendor/autoload.php';
require_once dirname(__FILE__) . '/bootstrap.php';

$database = new medoo($dbconfig);

$app = new \Slim\Slim();


$app->get('/:candidate/', function ($candidate) use (&$database) {
	//echo "GET {$candidate}\n";

	$datas = $database->select("AdressBook", [
		"username",
		"password",
		"id"
	], [
	"candidate[=]" => $candidate
	]);
    if ($datas)
	{
		//echo $database->last_query();
		//header("Content-Type: application/json");
		  //echo json_encode($datas, JSON_PRETTY_PRINT);
		//echo json_encode($datas);
		$app->response->headers->set(
           'Content-Type',
           'application/json'
        );
        $app->response->setBody(json_encode($datas));
	}
	else
	{
		$app->response->headers->set(
           'HTTP/1.1',
           '404'
        );
	}
});

$app->get('/:candidate/:addressbookid/', function ($candidate, $addressbookid) use ($app, &$database) {
	//echo "GET {$candidate}/{$addressbookid}\n";

	$datas = $database->select("AdressBook", [
		"username",
		"password"
	], [
		"AND"=> [
			"id[=]" => $addressbookid,
			"candidate[=]" => $candidate
		]
	]);
	if ($datas)
	{
		//echo $database->last_query();
		//header("Content-Type: application/json");
		  //echo json_encode($datas, JSON_PRETTY_PRINT);
		//echo json_encode($datas);
		$app->response->headers->set(
           'Content-Type',
           'application/json'
        );
        $app->response->setBody(json_encode($datas));
	}
	else
	{
		$app->response->headers->set(
           'HTTP/1.1',
           '404'
        );
	}

});

$app->post('/:candidate/', function ($candidate) use ($app, &$database) {
	$body = $app->request()->getBody();
	$data = json_decode($body, true);

	$datas = $database->insert("AdressBook", [
		"username" => $data['username'],
		"password" => $data['password'],
		"pictureUrl" => $data['pictureUrl'],
		"candidate" => $candidate
	]);

	if ($datas)
	{
		//echo $database->last_query();
		//header("Content-Type: application/json");
		  //echo json_encode($datas, JSON_PRETTY_PRINT);
		//echo json_encode($datas);
		$app->response->headers->set(
           'Content-Type',
           'application/json'
        );
        $app->response->setBody(json_encode($datas));
	}
	else
	{
		$app->response->headers->set(
           'HTTP/1.1',
           '404'
        );
	}
});

$app->put('/:candidate/:addressbookid/', function ($candidate, $addressbookid) use ($app, &$database) {
	$body = $app->request()->getBody();
	$data = json_decode($body, true);

	$datas = $database->update("AdressBook", [
		"username" => $data['username'],
		"password" => $data['password']
	], [
		"AND"=> [
			"id[=]" => $addressbookid,
			"candidate[=]" => $candidate
		]
	]);
	if ($datas)
	{
		//echo $database->last_query();
		//header("Content-Type: application/json");
		  //echo json_encode($datas, JSON_PRETTY_PRINT);
		//echo json_encode($datas);
		$app->response->headers->set(
           'Content-Type',
           'application/json'
        );
        $app->response->setBody(json_encode($datas));
	}
	else
	{
		$app->response->headers->set(
           'HTTP/1.1',
           '404'
        );
	}
});

$app->delete('/:candidate/:addressbookid/', function ($candidate, $addressbookid) use ($app, &$database) {
	$body = $app->request()->getBody();
	$data = json_decode($body, true);

	$datas = $database->delete("AdressBook", [
		"AND"=> [
			"id[=]" => $addressbookid,
			"candidate[=]" => $candidate
		]
	]);
	if ($datas)
	{
		//echo $database->last_query();
		//header("Content-Type: application/json");
		  //echo json_encode($datas, JSON_PRETTY_PRINT);
		//echo json_encode($datas);
		$app->response->headers->set(
           'Content-Type',
           'application/json'
        );
        $app->response->setBody(json_encode($datas));
	}
	else
	{
		$app->response->headers->set(
           'HTTP/1.1',
           '404'
        );
	}
});


$app->get('/:candidate/:addressbookid/groups/:contactgroupid/', function ($candidate, $addressbookid, $contactgroupid) use ($app, &$database) {
	//echo "GET {$candidate}/{$addressbookid}/groups/{$contactgroupid}\n";

	$datas = $database->select("ContactGroup", [
		"name",
		"description",
		"pictureURL"
	 ],[
		"AND"=> [
			"id_adressbook[=]" => $addressbookid,
			"id_group[=]" => $contactgroupid
		]
	]);
	if ($datas)
	{
		//echo $database->last_query();
		//header("Content-Type: application/json");
		  //echo json_encode($datas, JSON_PRETTY_PRINT);
		//echo json_encode($datas);
		$app->response->headers->set(
           'Content-Type',
           'application/json'
        );
        $app->response->setBody(json_encode($datas));
	}
	else
	{
		$app->response->headers->set(
           'HTTP/1.1',
           '404'
        );
	}


});

$app->post('/:candidate/:addressbookid/groups/', function ($candidate, $addressbookid) use ($app, &$database) {
	//echo "POST {$candidate}/{$addressbookid}/groups/\n";
	$body = $app->request()->getBody();
	$data = json_decode($body, true);
	$datas = $database->insert("ContactGroup", [
		"name" => $data['name'],
		"description" => $data['description'],
		"pictureURL" => $data['pictureUrl'],
		"id_adressbook" => $addressbookid,
    "id_group" => $data['id_group']
	]);
  //echo $database->last_query();
	$datas = $database->select("ContactGroup",
		[
		"id_group"
		],[
		"AND"=>[
			"name" => $data['name'],
			"description" => $data['description'],
			"pictureURL" => $data['pictureUrl'],
			"id_adressbook" => $addressbookid,
      "id_group" => $data['id_group']
		]
	]);

	if ($datas)
	{
		//echo $database->last_query();
		//header("Content-Type: application/json");
		  //echo json_encode($datas, JSON_PRETTY_PRINT);
		//echo json_encode($datas);
		$app->response->headers->set(
           'Content-Type',
           'application/json'
        );
        $app->response->setBody(json_encode($datas));
	}
	else
	{
		$app->response->headers->set(
           'HTTP/1.1',
           '404'
        );
	}
});


$app->put('/:candidate/:addressbookid/groups/:contactgroupid/', function ($candidate, $addressbookid, $contactgroupid) use ($app, &$database) {
	//echo "PUT {$candidate}/{$addressbookid}/groups/\n";
	$body = $app->request()->getBody();
	$data = json_decode($body, true);

	$datas = $database->update("ContactGroup", [
		"name" => $data['name'],
		"description" => $data['description'],
		"pictureURL" => $data['pictureUrl'],
    "id_group" => $data['id_group'],
		"id_adressbook" => $addressbookid
	],[
		"AND"=> [
			"id_adressbook[=]" => $addressbookid,
			"id_group[=]" => $contactgroupid
			]
	]);
	if ($datas)
	{
		//echo $database->last_query();
		//header("Content-Type: application/json");
		  //echo json_encode($datas, JSON_PRETTY_PRINT);
		//echo json_encode($datas);
		$app->response->headers->set(
           'Content-Type',
           'application/json'
        );
        $app->response->setBody(json_encode($datas));
	}
	else
	{
		$app->response->headers->set(
           'HTTP/1.1',
           '404'
        );
	}
});

$app->delete('/:candidate/:addressbookid/groups/:contactgroupid/', function ($candidate, $addressbookid, $contactgroupid) use ($app, &$database) {
	//echo "DELETE {$candidate}/{$addressbookid}/groups/\n";
	$body = $app->request()->getBody();
	$data = json_decode($body, true);

	$datas = $database->delete("ContactGroup", [

		"AND"=> [
			"id_adressbook[=]" => $addressbookid,
			"id_group[=]" => $contactgroupid
			]
	]);

	if ($datas)
	{
		//echo $database->last_query();
		//header("Content-Type: application/json");
		  //echo json_encode($datas, JSON_PRETTY_PRINT);
		//echo json_encode($datas);
		$app->response->headers->set(
           'Content-Type',
           'application/json'
        );
        $app->response->setBody(json_encode($datas));
	}
	else
	{
		$app->response->headers->set(
           'HTTP/1.1',
           '404'
        );
	}

});

//**************************Contacts********************************

$app->get('/:candidate/:addressbookid/contacts/:contactname/', function ($candidate, $addressbookid, $contactname) use ($app, &$database) {
	//echo "GET {$candidate}/{$addressbookid}/contacts/{$contactname}\n";

	$datas = $database->select("Contact", [
		"name",
		"phone",
		"pictureUrl",
		"email"
	 ],[
		"AND"=> [
			"id_adressbook[=]" => $addressbookid,
			"name[=]" => $contactname
		]
	]);
	//echo $database->last_query();

    if ($datas)
	{
		//echo $database->last_query();
		//header("Content-Type: application/json");
		  //echo json_encode($datas, JSON_PRETTY_PRINT);
		//echo json_encode($datas);
		$app->response->headers->set(
           'Content-Type',
           'application/json'
        );
        $app->response->setBody(json_encode($datas));
	}
	else
	{
		$app->response->headers->set(
           'HTTP/1.1',
           '404'
        );
	}

});

$app->post('/:candidate/:addressbookid/contacts/', function ($candidate, $addressbookid) use ($app, &$database) {
	//echo "POST {$candidate}/{$addressbookid}/contacts/\n";
	$body = $app->request()->getBody();
	$data = json_decode($body, true);

	$datas = $database->insert("Contact", [
		"name" => $data['name'],
		"phone" => $data['phone'],
		"pictureUrl" => $data['pictureUrl'],
		"email" => $data['email'],
		"id_adressbook" => $addressbookid,
		"groupId" => $data['groupId']
	]);
	if ($datas)
	{
		//echo $database->last_query();
		//header("Content-Type: application/json");
		  //echo json_encode($datas, JSON_PRETTY_PRINT);
		//echo json_encode($datas);
		$app->response->headers->set(
           'Content-Type',
           'application/json'
        );
        $app->response->setBody(json_encode($datas));
	}
	else
	{
		$app->response->headers->set(
           'HTTP/1.1',
           '404'
        );
	}
});


$app->put('/:candidate/:addressbookid/contacts/:contactname/', function ($candidate, $addressbookid, $contactname) use ($app, &$database) {
	//echo "PUT {$candidate}/{$addressbookid}/contacts/\n";
	$body = $app->request()->getBody();
	$data = json_decode($body, true);
	$gpId="0";
	if(isset($data['groupId']))
		$gpId=$data['groupId'];
	$datas = $database->update("Contact", [
		"name" => $data['name'],
		"phone" => $data['phone'],
		"pictureUrl" => $data['pictureUrl'],
		"email" => $data['email'],
		"groupId" => $gpId
	],[
		"AND"=> [
			"id_adressbook[=]" => $addressbookid,
			"name[=]" => $contactname
			]
	]);
  //echo $database->last_query();
	if ($datas)
	{
		//echo $database->last_query();
		//header("Content-Type: application/json");
		  //echo json_encode($datas, JSON_PRETTY_PRINT);
		//echo json_encode($datas);
		$app->response->headers->set(
           'Content-Type',
           'application/json'
        );
        $app->response->setBody(json_encode($datas));
	}
	else
	{
		$app->response->headers->set(
           'HTTP/1.1',
           '404'
        );
	}
});

$app->delete('/:candidate/:addressbookid/contacts/:contactname/', function ($candidate, $addressbookid, $contactname) use ($app, &$database) {
	$body = $app->request()->getBody();
	$data = json_decode($body, true);

	$datas = $database->delete("Contact", [

		"AND"=> [
			"id_adressbook[=]" => $addressbookid,
			"name[=]" => $contactname
			]
	]);
	//echo $database->last_query();
	if ($datas)
	{
		//echo $database->last_query();
		//header("Content-Type: application/json");
		  //echo json_encode($datas, JSON_PRETTY_PRINT);
		//echo json_encode($datas);
		$app->response->headers->set(
           'Content-Type',
           'application/json'
        );
        $app->response->setBody(json_encode($datas));
	}
	else
	{
		$app->response->headers->set(
           'HTTP/1.1',
           '404'
        );
	}
});


$app->run();
