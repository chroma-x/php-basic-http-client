<?php

namespace Project;

use BasicHttpClient\Request\Authentication\BasicAuthentication;
use BasicHttpClient\Request\Message\Body\Body;
use BasicHttpClient\Request\Message\Cookie\Cookie;
use BasicHttpClient\Request\Message\Header\Header;
use BasicHttpClient\Request\Message\Message;
use BasicHttpClient\Request\Request;
use BasicHttpClient\Request\Transport\HttpsTransport;

require_once('vendor/autoload.php');

$transport = new HttpsTransport();
$transport
	->setHttpVersion(HttpsTransport::HTTP_VERSION_1_1)
	->setTimeout(5)
	->setReuseConnection(true)
	->setAllowCaching(true)
	->setFollowRedirects(true)
	->setMaxRedirects(10)
	->setVerifyPeer(true);

$messageBody = new Body();
$messageBody->setBodyText(
	json_encode(
		array(
			'paramName1' => 'paramValue1',
			'paramName2' => 'paramValue2',
			'paramName3' => array(
				'key1' => 'value1',
				'key2' => 'value2'
			)
		)
	)
);

$message = new Message();
$message
	->addHeader(new Header('Content-Type', array('application/json')))
	->addHeader(new Header('Accept', array('application/json', 'text/*')))
	->addHeader(new Header('Runscope-Bucket-Auth', array('7a64dde7-74d5-4eed-b170-a2ab406eff08')))
	->addCookie(new Cookie('PHPSESSID', '<MY_SESSION_ID>'))
	->setBody($messageBody);

$header1 = $message->getHeaderByName('Content-Type');
$header2 = $message->getHeaderByName('content-type');
$header3 = $message->getHeaderByName('CONTENT-Type');

$message->addHeader(new Header('Custom-Header', array('CustomHeaderValue')));
$message->addAdditionalHeader(new Header('Custom-Header', array('AnotherCustomHeaderValue')));

$request = new Request();
$response = $request
	->setUserAgent('PHP Basic HTTP Client Test 1.0')
	->setEndpoint('https://yourapihere-com-98yq3775xff0.runscope.net/')
	->setPort(443)
	->addAuthentication(new BasicAuthentication('username', 'password'))
	->setQueryParameters(
		array(
			'paramName1' => 'paramValue1',
			'paramName2' => 'paramValue2'
		)
	)
	->setMethod(Request::REQUEST_METHOD_POST)
	->setTransport($transport)
	->setMessage($message)
	->perform();

print_r($request->getEffectiveRawHeader());