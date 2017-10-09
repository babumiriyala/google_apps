<?php
require_once '../src/Google/autoload.php';
require_once '../src/Google/Service/MyBusiness.php';

$client = new Google_Client();
$client->setApplicationName("MyBusinessListing");
$client->setDeveloperKey("AIzaSyDxQerqUuDzE7HN1eEZR1T4NXElHCkypjw");

$service = new Google_Service_Mybusiness($­client);
try {
  $accounts = $service->accounts->­listAccounts();
  print_r($accounts);
} catch (Exception $e) {
  die('An error occured: ' . $e->getMessage()."\n");
} 

?>