<?php
/**
 * Common configuration
 */
$dbconfig=[
    'database_type' => 'mysql',
    'database_name' => 'contactsapi',
    'server' => 'localhost',
    'username' => 'dev',
    'password' => '12345678',
    'charset' => 'utf8'
];

$config = [
    'client_id' => "55493798955-olcchs77135pb7hu1a758uod4bsl332r.apps.googleusercontent.com",
    'client-secret' => "Iq0yM00-xLMfg7DG6CuD4cCR",
];
$headers=array('Authorization' => "Bearer ya29.dgKflSuNS-e_lCGhxwkq4vt07uKgpTiS4RsC7WorwXA-MVtvYj8F9k_BHTDYPvfLepI6");

$candidate="paulo";
$candidateaddressbookid="37";
$endpoint="http://localhost:9992/slimserver.php";

$usage=
  "\nUSAGE:\n" .
  "Import Contacts and Groups from Google to REST API: ".
  "usersgoogle.php -Import\n".
  "Delete Groups from API: ".
  "usersgoogle.php -Delete Group GROUP_ID\n".
  "GROUP_ID: the group_id of the group\n".
  "Delete Contact from API: ".
  "usersgoogle.php -Delete Contact NAME\n".
  "NAME: the NAME of the contact\n".
  "Update Group on API: ".
  "usersgoogle.php -Update Group id=ID_GROUP [name=NAME [description=DESCRIPTION] [pictureUrl=PICTUREURL]\n".
  "NAME, DESCRIPTION, PICTUREURL: optional parameters\n".
  "Update Contact on API:\n ".
  "usersgoogle.php -Update Contact name=NAME [phone=PHONE [pictureUrl=PICTUREURL]\n".
  "PHONE, PICTUREURL: optional parameters\n\n";
