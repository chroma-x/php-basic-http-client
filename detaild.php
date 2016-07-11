<?php

namespace Project;

use Markenwerk\BasicHttpClient\Request\Authentication\BasicAuthentication;
use Markenwerk\BasicHttpClient\Request\Message\Body\Body;
use Markenwerk\BasicHttpClient\Request\Message\Cookie\Cookie;
use Markenwerk\BasicHttpClient\Request\Message\Header\Header;
use Markenwerk\BasicHttpClient\Request\Message\Message;
use Markenwerk\BasicHttpClient\Request\Request;
use Markenwerk\BasicHttpClient\Request\Transport\HttpsTransport;
use Markenwerk\UrlUtil\Url;

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
	->setHeader(new Header('Content-Type', array('application/json')))
	->setHeader(new Header('Accept', array('application/json', 'text/*')))
	->setHeader(new Header('Runscope-Bucket-Auth', array('7a64dde7-74d5-4eed-b170-a2ab406eff08')))
	->addCookie(new Cookie('PHPSESSID', '<MY_SESSION_ID>'))
	->setBody($messageBody);

$message->setHeader(new Header('Custom-Header', array('CustomHeaderValue')));
$message->addHeader(new Header('Custom-Header', array('AnotherCustomHeaderValue')));

$url = new Url('https://john:secret@yourapihere-com-98yq3775xff0.runscope.net:443/path/to/resource?arg1=123#fragment');

$request = new Request();
$request
	->setUserAgent('PHP Basic HTTP Client Test 1.0')
	->setUrl($url)
	->addAuthentication(new BasicAuthentication('username', 'password'))
	->setMethod(Request::REQUEST_METHOD_POST)
	->setTransport($transport)
	->setMessage($message)
	->perform();

$response = $request->getResponse();
echo print_r($response->getStatusCode(), true) . PHP_EOL;
echo print_r($response->getStatusText(), true) . PHP_EOL;
echo print_r($response->getHeaders(), true) . PHP_EOL;
echo print_r($response->getBody(), true) . PHP_EOL . PHP_EOL;

$statistics = $response->getStatistics();
echo print_r($statistics->getRedirectEndpoint(), true) . PHP_EOL;
echo print_r($statistics->getRedirectCount(), true) . PHP_EOL;
echo print_r($statistics->getRedirectTime(), true) . PHP_EOL;
echo print_r($statistics->getConnectionEstablishTime(), true) . PHP_EOL;
echo print_r($statistics->getHostLookupTime(), true) . PHP_EOL;
echo print_r($statistics->getPreTransferTime(), true) . PHP_EOL;
echo print_r($statistics->getStartTransferTime(), true) . PHP_EOL;
echo print_r($statistics->getTotalTime(), true) . PHP_EOL . PHP_EOL;

echo print_r($request->getEffectiveEndpoint(), true) . PHP_EOL;
echo print_r($request->getEffectiveStatus(), true) . PHP_EOL;
echo print_r($request->getEffectiveRawHeader(), true) . PHP_EOL;
echo print_r($request->getEffectiveHeaders(), true) . PHP_EOL . PHP_EOL;

