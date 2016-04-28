<?php

namespace Project;

require_once('vendor/autoload.php');

use BasicHttpClient;
use BasicHttpClient\Request\Authentication;
use BasicHttpClient\Request\Message;

// Instantiating a basic HTTP client with the endpoints URL
$client = new BasicHttpClient\BasicHttpClient('http://requestb.in/1aipzl31');

// Adding an authentication method
$client
	->getRequest()
	->addAuthentication(new Authentication\BasicAuthentication('username', 'password'));

// Adding custom HTTP request headers and a session cookie
$client
	->getRequest()
	->getMessage()
	->addHeader(new Message\Header\Header('Content-Type', array('application/x-www-form-urlencoded')))
	->addHeader(new Message\Header\Header('Accept', array('text/html', 'text/*')))
	->addCookie(new Message\Cookie\Cookie('PHPSESSID', '<MY_SESSION_ID>'));

$response = $client->get(array(
	'paramName1' => 'paramValue1',
	'paramName2' => 'paramValue2'
));


$response = $client->post(array(
	'paramName1' => 'paramValue1',
	'paramName2' => 'paramValue2',
	'paramName3' => array(
		'key1' => 'value1',
		'key2' => 'value2'
	)
));

print_r($client->getRequest()->getEffectiveRawHeader());