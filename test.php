<?php

require_once('vendor/autoload.php');

/*
$transport = new \BasicHttpClient\Request\Transport\HttpsTransport();
$transport
	->setAllowCaching(false)
	->setFollowRedirects(false)
	->setHttpVersion($transport::HTTP_VERSION_1_0)
	->setReuseConnection(false)
	->setTimeout(20)
	->setVerifyPeer(true);

$message = new \BasicHttpClient\Request\Message\Message();
$message
	->addHeader(new \BasicHttpClient\Request\Message\Header\Header('accept', array('application/json', 'application/*')))
	->addHeader(new \BasicHttpClient\Request\Message\Header\Header('Runscope-Bucket-Auth', array('7a64dde7-74d5-4eed-b170-a2ab406eff08')))
	->addCookie(new \BasicHttpClient\Request\Message\Cookie\Cookie('SESSION_ID', 'abc'));

try {
	$request = new \BasicHttpClient\Request\Request();
	$response = $request
		->setTransport($transport)
		->setMessage($message)
		->setEndpoint('http://requestb.in/z86l8oz8')
		->setEndpoint('https://api-yourapihere-com-98yq3775xff0.runscope.net/path/')
		->setPort(443)
		->setMethod($request::REQUEST_METHOD_POST)
		->perform()
		->getResponse();
	print_r($response);
} catch (\CommonException\NetworkException\ConnectionTimeoutException $exception) {
	fwrite(STDERR, $exception->getMessage() . PHP_EOL);
} catch (\CommonException\NetworkException\Base\NetworkException $exception) {
	fwrite(STDERR, $exception->getMessage() . PHP_EOL);
}
*/

$client = new \BasicHttpClient\BasicHttpClient('https://api-yourapihere-com-98yq3775xff0.runscope.net/path/');
$client
	->getRequest()
	->addAuthentication(new \BasicHttpClient\Request\Authentication\BasicAuthentication('username', 'password'))
	->getMessage()
	->addHeader(new \BasicHttpClient\Request\Message\Header\Header('Runscope-Bucket-Auth', array('7a64dde7-74d5-4eed-b170-a2ab406eff08')))
	->addCookie(new \BasicHttpClient\Request\Message\Cookie\Cookie('SESSION_ID', 'abc'));

/**
 * @param int $length
 * @return string
 */
function generateRandomString($length = 10)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

$postData = array();
for ($i = 0; $i < 5; $i++) {
	$postData[generateRandomString()] = generateRandomString();
}
for ($i = 0; $i < 2; $i++) {
	$arrayKey = generateRandomString();
	$postData[$arrayKey] = array();
	for ($j = 0; $j < 2; $j++) {
		$postData[$arrayKey][] = generateRandomString();
	}
}
for ($i = 0; $i < 2; $i++) {
	$arrayKey = generateRandomString();
	$postData[$arrayKey] = array();
	for ($j = 0; $j < 2; $j++) {
		$postData[$arrayKey][generateRandomString()] = generateRandomString();
	}
}

$response = $client->post($postData);

print_r($response);