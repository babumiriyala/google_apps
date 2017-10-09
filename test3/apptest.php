<?php
include_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../src/Google/Service/MyBusiness.php';

session_start();

if (isset($_GET['code'])) {
    // try to get an access token
    $code = $_GET['code'];



	$client = new Google_Client();

	$client->setAccessType('online'); // default: offline
	$client->setApplicationName('googlemybusiness');
	$client->setClientId('574853849267-4ctbhr0tfhg4o72i3vm516c84sm9f0ic.apps.googleusercontent.com');
	$client->setClientSecret('AO1vv4VIqT5ADOJgZ4j1HnSF');
	//$scriptUri = "http://".$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'];
	$client->setRedirectUri('http://localhost/google_apps/test3/apptest.php');
	$client->setScopes(array(
		'https://www.googleapis.com/auth/plus.me',
		'https://www.googleapis.com/auth/userinfo.email',
		'https://www.googleapis.com/auth/userinfo.profile',
		'https://www.googleapis.com/auth/plus.business.manage',
	));
	//$client->setDeveloperKey('INSERT HERE'); // API key
	//require_once __DIR__ . "/../library/Google/mybusiness/Mybusiness.php";
	$service = new Google_Service_Mybusiness($client);


	if (isset($_GET['code']) && !isset($_SESSION['token'])) { // we received the positive auth callback, get the token and store it in session
		print'2';
		$client->authenticate($_GET['code']);
		$_SESSION['token'] = $client->getAccessToken();
	}

	if (isset($_SESSION['token'])) { // extract token from session and configure client
		print'3';
		$token = $_SESSION['token'];
		$client->setAccessToken($token);
		print_r($client);
//here is where it gives the 404, on list accounts
		$test = $service->accounts->listAccounts();
		$test2 = $service->accounts_locations->listAccountsLocations($test[0]['name']);
		$test3 = $service->accounts_locations_reviews->listAccountsLocationsReviews($test2[0]['name']);


		print_r($test);
		print_r($test2);
		print_r($test3);

		print 'Done';
	}
	die();


	$url = 'https://accounts.google.com/o/oauth2/token';
    $params = array(
        "code" => $code,
        "client_id" => '574853849267-4ctbhr0tfhg4o72i3vm516c84sm9f0ic.apps.googleusercontent.com',
        "client_secret" => 'AO1vv4VIqT5ADOJgZ4j1HnSF',
        "redirect_uri" => 'http://localhost/google_apps/test3/apptest.php',
        "grant_type" => "authorization_code"
    );

    $ch = curl_init();
    curl_setopt($ch, constant("CURLOPT_" . 'URL'), $url);
    curl_setopt($ch, constant("CURLOPT_" . 'POST'), true);
    curl_setopt($ch, constant("CURLOPT_" . 'POSTFIELDS'), $params);
    $output = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);

	echo $info['http_code'];
    if ($info['http_code']  === 200) {
        header('Content-Type: ' . $info['content_type']);
        return $output;
    } else {
        return 'An error happened';
    }


} else {

    $url = "https://accounts.google.com/o/oauth2/auth";

    $params = array(
        "response_type" => "code",
        "client_id" => '574853849267-4ctbhr0tfhg4o72i3vm516c84sm9f0ic.apps.googleusercontent.com',
        "redirect_uri" => 'http://localhost/google_apps/test3/apptest.php',
        "scope" => "https://www.googleapis.com/auth/plus.business.manage"
    );

    $request_to = $url . '?' . http_build_query($params);

    header("Location: " . $request_to);
}
