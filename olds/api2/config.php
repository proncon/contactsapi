<?php
/**
 * Common configuration
 */

$config = [
    'client_id' => "55493798955-olcchs77135pb7hu1a758uod4bsl332r.apps.googleusercontent.com",
    'client-secret' => "Iq0yM00-xLMfg7DG6CuD4cCR",
];
$headers=array('Authorization' => "Bearer ya29.dQJsVVYups81wYumdtGd_k8qKAOAZRue1cuA54yLRjx5g6m1_sa_7IovuKMyJ7xLHfnN");

$candidate="paulo";
$candidateaddressbookid="37";
$endpoint="http://localhost:9992/slimserver.php";

$usage=
  "\nUSAGE:\n" .
  "Import Contacts and Groups from Google to REST API: ".
  "usersgoogle.php -Import\n".
  "Delete Groups from REST API: ".
  "usersgoogle.php -Delete Group GROUP_ID\n".
  "GROUP_ID: the group_id of the group\n".
  "Delete Contact from REST API: ".
  "usersgoogle.php -Delete Contact NAME\n".
  "NAME: the NAME of the contact\n".
  "Update Group on REST API: ".
  "usersgoogle.php -Update id=ID_GROUP [name=NAME [description=DESCRIPTION] [pictureUrl=PICTUREURL]\n".
  "NAME, DESCRIPTION, PICTUREURL: optional parameters\n\n";
