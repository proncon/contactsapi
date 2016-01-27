<?php
require_once 'vendor/autoload.php';

$database = new medoo([
    'database_type' => 'mysql',
    'database_name' => 'contactsapi',
    'server' => 'localhost',
    'username' => 'dev',
    'password' => '12345678',
    'charset' => 'utf8'
]);

	$datas = $database->select("AdressBook", [
		"username",
		"password"
	], [
	"username[=]" => "paulo"
	]);
    //echo json_encode($datas, JSON_PRETTY_PRINT);

//	foreach($datas as $data)
//	{
//		echo "username:" . $data["username"] . " - password:" . $data["password"] . "\n";
//	}

//var_dump($database->error());

$app = new \Slim\Slim();


$app->get('/:candidate/', function ($candidate) use (&$database) {
	echo "GET {$candidate}\n";

	$datas = $database->select("AdressBook", [
		"username",
		"password",
		"id"
	], [
	"candidate[=]" => $candidate
	]);
    echo json_encode($datas, JSON_PRETTY_PRINT);
});

$app->get('/:candidate/:addressbookid/', function ($candidate, $addressbookid) use ($app, &$database) {
	echo "GET {$candidate}/{$addressbookid}\n";

	$datas = $database->select("AdressBook", [
		"username",
		"password"
	], [
		"AND"=> [
			"id[=]" => $addressbookid,
			"candidate[=]" => $candidate
		]
	]);
	//echo $database->last_query();
    echo json_encode($datas, JSON_PRETTY_PRINT);

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

	header('HTTP/1.1 200 OK');  
	header('Content-type: application/json');
	//var_dump($database->error());
	
	//echo "POST {$candidate}\n";
	//echo "username: {$data['username']}\n";
	//echo "password: {$data['password']}\n";
});

$app->put('/:candidate/:addressbookid/', function ($candidate, $addressbookid) use ($app, &$database) {
	$body = $app->request()->getBody();
	$data = json_decode($body, true);

	$database->update("AdressBook", [
		"username" => $data['username'],
		"password" => $data['password']
	], [
		"AND"=> [
			"id[=]" => $addressbookid,
			"candidate[=]" => $candidate
		]
	]);
 	//echo $database->last_query(); 
	//var_dump($database->error());

	echo "PUT {$candidate}/{$addressbookid}\n";
	echo "username: {$data['username']}\n";
	echo "password: {$data['password']}\n";
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
	//var_dump($database->error());
	
	echo "DELETE {$candidate}/{$addressbookid}\n";
});


$app->get('/:candidate/:addressbookid/groups/:contactgroupid/', function ($candidate, $addressbookid, $contactgroupid) use ($app, &$database) {
	echo "GET {$candidate}/{$addressbookid}/groups/{$contactgroupid}\n";

	$datas = $database->select("ContactGroup", [
		"name",
		"description",
		"pictureURL"
	 ],[
		"AND"=> [
			"id_adressbook[=]" => $addressbookid,
			"id[=]" => $contactgroupid
		]
	]);
	//echo $database->last_query(); 

    echo json_encode($datas, JSON_PRETTY_PRINT);

});

$app->post('/:candidate/:addressbookid/groups/', function ($candidate, $addressbookid) use ($app, &$database) {
	//echo "POST {$candidate}/{$addressbookid}/groups/\n";
	$body = $app->request()->getBody();
	$data = json_decode($body, true);

	$datas = $database->insert("ContactGroup", [
		"name" => $data['name'],
		"description" => $data['description'],
		"pictureURL" => $data['pictureURL'],
		"id_adressbook" => $addressbookid
	]);
	$datas = $database->select("ContactGroup", 
		[
		"id"
		],[
		"AND"=>[
			"name" => $data['name'],
			"description" => $data['description'],
			"pictureURL" => $data['pictureURL'],
			"id_adressbook" => $addressbookid
		]
	]);

 	//echo $database->last_query(); 
	header("Content-Type: application/json");
	 echo json_encode($datas, JSON_PRETTY_PRINT);
});


$app->put('/:candidate/:addressbookid/groups/:contactgroupid/', function ($candidate, $addressbookid, $contactgroupid) use ($app, &$database) {
	echo "PUT {$candidate}/{$addressbookid}/groups/\n";
	$body = $app->request()->getBody();
	$data = json_decode($body, true);

	$datas = $database->update("ContactGroup", [
		"name" => $data['name'],
		"description" => $data['description'],
		"pictureURL" => $data['pictureURL'],
		"id_adressbook" => $data['id_adressbook']
	],[
		"AND"=> [
			"id_adressbook[=]" => $addressbookid,
			"id[=]" => $contactgroupid
			]
	]);
});

$app->delete('/:candidate/:addressbookid/groups/:contactgroupid/', function ($candidate, $addressbookid, $contactgroupid) use ($app, &$database) {
	echo "DELETE {$candidate}/{$addressbookid}/groups/\n";
	$body = $app->request()->getBody();
	$data = json_decode($body, true);

	$datas = $database->delete("ContactGroup", [

		"AND"=> [
			"id_adressbook[=]" => $addressbookid,
			"id[=]" => $contactgroupid
			]
	]);
});

//**************************Contacts********************************

$app->get('/:candidate/:addressbookid/contacts/:contactname/', function ($candidate, $addressbookid, $contactname) use ($app, &$database) {
	echo "GET {$candidate}/{$addressbookid}/contacts/{$contactname}\n";

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

    echo json_encode($datas, JSON_PRETTY_PRINT);

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
 	//echo $database->last_query(); 
	header('HTTP/1.1 200 OK');  
	header('Content-type: application/json');
});


$app->put('/:candidate/:addressbookid/contacts/:contactname/', function ($candidate, $addressbookid, $contactname) use ($app, &$database) {
	echo "PUT {$candidate}/{$addressbookid}/contacts/\n";
	$body = $app->request()->getBody();
	$data = json_decode($body, true);

	$datas = $database->update("Contact", [
		"name" => $data['name'],
		"phone" => $data['phone'],
		"pictureUrl" => $data['pictureUrl'],
		"email" => $data['email'],
		"groupId" => $data['groupId']
	],[
		"AND"=> [
			"id_adressbook[=]" => $addressbookid,
			"name[=]" => $contactname
			]
	]);
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
	if ($datas>0)
	{
		header('Content-Type: Application/json');
		header('HTTP/1.1 200 OK');  
	}
	else
	{
     $app->response->headers->set(
            'Content-Type',
            'application/json'
        );

        echo json_encode(
            array(
                'code' => 404,
                'message' => 'Not found'
            ),
            JSON_PRETTY_PRINT
        );
	}
});


$app->run();