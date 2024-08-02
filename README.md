# PHP Basic HTTP Client

[![Build Status](https://travis-ci.org/chroma-x/php-basic-http-client.svg?branch=master)](https://travis-ci.org/chroma-x/php-basic-http-client)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/0bc596c2-5849-4e0f-a46f-a7b94d28f97c.svg)](https://insight.sensiolabs.com/projects/0bc596c2-5849-4e0f-a46f-a7b94d28f97c)
[![Code Climate](https://codeclimate.com/github/chroma-x/php-basic-http-client/badges/gpa.svg)](https://codeclimate.com/github/chroma-x/php-basic-http-client)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/03db69a367dd4bbbbbd03e4612dfdbe5)](https://www.codacy.com/app/Bonscho/php-basic-http-client?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=chroma-x/php-basic-http-client&amp;utm_campaign=Badge_Grade)
[![Latest Stable Version](https://poser.pugx.org/chroma-x/basic-http-client/v/stable)](https://packagist.org/packages/chroma-x/basic-http-client)
[![Total Downloads](https://poser.pugx.org/chroma-x/basic-http-client/downloads)](https://packagist.org/packages/chroma-x/basic-http-client)
[![License](https://poser.pugx.org/chroma-x/basic-http-client/license)](https://packagist.org/packages/chroma-x/basic-http-client)

A basic yet extensible HTTP client library providing different authentication methods written in PHP.

## What about PSR-7?

PHP Basic HTTP Client is an alternative to other very good implementations like [Guzzle](https://github.com/Guzzle3/http) that are following the [PSR-7 guidelines](http://www.php-fig.org/psr/psr-7/meta/).

**This project not follows these guidelines for different reasons.**

1. PSR-7 is heavily over engineered due to also match complex edge cases. 
2. Objects implementing the PSR-7 interfaces have to be immutable wich is resulting in an unusual API from the PHP dev point of view and an unneccessarily increased need of performance. 

Find out more at the [„PSR-7 is imminent, and here's my issues with it“](https://evertpot.com/psr-7-issues/) blog post by PHP-FIG member [Evert Pot](https://evertpot.com/) and this [discussion at Stackoverflow](http://stackoverflow.com/questions/31360786/psr7-http-message-why-immutable).

## Installation

```{json}
{
   	"require": {
        "chroma-x/basic-http-client": "~4.0"
    }
}
```

## Usage

### Autoloading and namesapce

```{php}  
require_once('path/to/vendor/autoload.php');
```

### Simple usage

#### Preparing the HTTP client

```{php}
use ChromaX\BasicHttpClient;
use ChromaX\BasicHttpClient\Request\Authentication;
use ChromaX\BasicHttpClient\Request\Message;

// Instantiating a basic HTTP client with the endpoints URL
// If the endpoint uses the `HTTPS` schema a `HttpsTransport` instance will be used automatically.
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
```

#### Performing requests and read the response

##### Body-less requests (GET, HEAD and DELETE)

Perfoming the following `GET` request with additional query parameters

```{php}
$response = $client->get(array(
	'paramName1' => 'paramValue1',
	'paramName2' => 'paramValue2'
));
```

will result in the following HTTP request.

```{http}
GET /1aipzl31?paramName1=paramValue1&paramName2=paramValue2 HTTP/1.1
Host: requestb.in
Authorization: Basic dXNlcm5hbWU6cGFzc3dvcmQ=
User-Agent: PHP Basic HTTP Client 1.0
Cookie: PHPSESSID=<MY_SESSION_ID>
Content-Type: application/x-www-form-urlencoded
Accept: text/html, text/*
```

The same mechanic is offered to perform `HEAD` and `DELETE` requests wich all are body-less.

##### Body-full requests (POST, PUT, PATCH)

Perfoming the following `POST` request with body data

```{php}
$response = $client->post(array(
	'paramName1' => 'paramValue1',
	'paramName2' => 'paramValue2',
	'paramName3' => array(
		'key1' => 'value1',
		'key2' => 'value2'
	)
));
```

will result in the following HTTP request.

```{http}
POST /1aipzl31?paramName1=paramValue1&paramName2=paramValue2 HTTP/1.1
Host: requestb.in
Authorization: Basic dXNlcm5hbWU6cGFzc3dvcmQ=
User-Agent: PHP Basic HTTP Client 1.0
Cookie: PHPSESSID=<MY_SESSION_ID>
Content-Type: application/x-www-form-urlencoded
Accept: text/html, text/*
Content-Length: 101

paramName1=paramValue1&paramName2=paramValue2&paramName3%5Bkey1%5D=value1&paramName3%5Bkey2%5D=value2
```

The same mechanic is offered to perform `PUT` and `PATCH` requests wich all are body-full.

---

### Detailed usage

The following example shows the usage with a more detailed configuration. 

#### Configuring an HTTP Transport instance

```{php}
use ChromaX\BasicHttpClient\Request\Transport\HttpTransport;

// Configuring a Transport instance
$transport = new HttpTransport();
$transport
	->setHttpVersion(HttpsTransport::HTTP_VERSION_1_1)
	->setTimeout(5)
	->setReuseConnection(true)
	->setAllowCaching(true)
	->setFollowRedirects(true)
	->setMaxRedirects(10);
```

#### Configuring an HTTPS Transport instance

```{php}
use ChromaX\BasicHttpClient\Request\Transport\HttpsTransport;

// Configuring a Transport instance
$transport = new HttpsTransport();
$transport
	->setHttpVersion(HttpsTransport::HTTP_VERSION_1_1)
	->setTimeout(5)
	->setReuseConnection(true)
	->setAllowCaching(true)
	->setFollowRedirects(true)
	->setMaxRedirects(10)
	->setVerifyHost(true)
	->setVerifyPeer(true);
```

#### Configuring a Message instance with Body

```{php}
use ChromaX\BasicHttpClient\Request\Message\Body\Body;
use ChromaX\BasicHttpClient\Request\Message\Cookie\Cookie;
use ChromaX\BasicHttpClient\Request\Message\Header\Header;
use ChromaX\BasicHttpClient\Request\Message\Message;

// Configuring a message Body instance
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

// Configuring a Message instance
$message = new Message();
$message
	->addHeader(new Header('Content-Type', array('application/json')))
	->addHeader(new Header('Accept', array('application/json', 'text/*')))
	->addHeader(new Header('Runscope-Bucket-Auth', array('7a64dde7-74d5-4eed-b170-a2ab406eff08')))
	->addCookie(new Cookie('PHPSESSID', '<MY_SESSION_ID>'))
	->setBody($messageBody);
```

##### Message and request Header instances

**Please note, that headers have some unusual behaviours.** Header names have an uniform way of nomenclature so the following three getter calls would have the same result.

```{php}
$header1 = $message->getHeaderByName('Content-Type');
$header2 = $message->getHeaderByName('content-type');
$header3 = $message->getHeaderByName('CONTENT-Type');
```

To allow multiple request headers using the same name, the method `addAdditionalHeader` provides such a logic.

```{php}
// Add or replace a request header
$message->addHeader(new Header('Custom-Header', array('CustomHeaderValue')));
// Add a request header and keep the existing one untouched
$message->addAdditionalHeader(new Header('Custom-Header', array('AnotherCustomHeaderValue')));
```

#### Configuring an endpoints URL, build the Request instance and perform the HTTP request

For more information about the usage of the URL object please take a look at the [PHP URL Util](https://github.com/chroma-x/php-url-util) project.

```{php}
use ChromaX\BasicHttpClient\Request\Authentication\BasicAuthentication;
use ChromaX\BasicHttpClient\Request\Request;
use ChromaX\UrlUtil\Url;

// Setting up the endpoints URL
$url = new Url('https://john:secret@yourapihere-com-98yq3775xff0.runscope.net:443/path/to/resource?arg1=123#fragment');

// Configuring and performing a Request
$request = new Request();
$request
	->setUserAgent('PHP Basic HTTP Client Test 1.0')
	->setUrl($url)
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
```

The resulting HTTP request would be the following.

```{http}
POST /?arg1=123#fragment HTTP/1.1
Host: yourapihere-com-98yq3775xff0.runscope.net
Authorization: Basic dXNlcm5hbWU6cGFzc3dvcmQ=
User-Agent: PHP Basic HTTP Client Test 1.0
Cookie: PHPSESSID=<MY_SESSION_ID>
Content-Type: application/json
Accept: application/json, text/*
Runscope-Bucket-Auth: 7a64dde7-74d5-4eed-b170-a2ab406eff08
Custom-Header: CustomHeaderValue
Custom-Header: AnotherCustomHeaderValue
Content-Length: 102

{"paramName1":"paramValue1","paramName2":"paramValue2","paramName3":{"key1":"value1","key2":"value2"}}
```

### Usage of authentication methods

You can add one or more Authentication instances to every Request instance. At the moment this project provides classes for [HTTP Basic Authentication](https://en.wikipedia.org/wiki/Basic_access_authentication) and [SSL Client Certificate Authentication](https://en.wikipedia.org/wiki/Transport_Layer_Security#Client-authenticated_TLS_handshake).

#### HTTP Basic Authentication

Required credentials are a *username* and a *password* that get provided to the class constructor as arguments.

```{php}
use ChromaX\BasicHttpClient\Request\Authentication\BasicAuthentication;
use ChromaX\BasicHttpClient\Request\Request;

// Configuring the authentication
$basicAuthentication = new BasicAuthentication('username', 'password');

// Adding the authentication instance to the Request
$request = new Request();
$request->addAuthentication($basicAuthentication);
```

#### SSL Client Certificate Authentication

Required credentials are a *Certificate Authority Certificate*, a *Client Certificate* and the password that is used to decrypt the Client Certificate that get provided to the class constructor as arguments.

```{php}
use ChromaX\BasicHttpClient\Request\Authentication\ClientCertificateAuthentication;
use ChromaX\BasicHttpClient\Request\Request;

// Configuring the authentication
$clientCertificateAuthentication = new ClientCertificateAuthentication(
	'/var/www/project/clientCert/ca.crt',
	'/var/www/project/clientCert/client.crt',
	'clientCertPassword'
);

// Adding the authentication instance to the Request
$request = new Request();
$request->addAuthentication($clientCertificateAuthentication);
```

---

## Reading from the resulting Response object

### Getting the response object

If using the `BasicHttpClient` the response object is returned by the termination methods listed above. If directly using the Request instance, you can get the Response object via a getter.

```{php}
// Getting the response ChromaX\BasicHttpClient\Response\Response object
$response = $request->getResponse();

// Reading the HTTP status code as integer; will return `200`
echo print_r($response->getStatusCode(), true) . PHP_EOL;

// Reading the HTTP status text as string; will return `HTTP/1.1 200 OK`
echo print_r($response->getStatusText(), true) . PHP_EOL;

// Reading the HTTP response headers as array of ChromaX\BasicHttpClient\Response\Header\Header objects
echo print_r($response->getHeaders(), true) . PHP_EOL;

// Reading the HTTP response body as string
echo print_r($response->getBody(), true) . PHP_EOL;
```

---

## Getting effective Request information

After successful performing the request, the effective request information is tracked back to the Request object. They can get accessed as follows.

```{php}
// Getting the effective endpoint URL including the query parameters
echo print_r($request->getEffectiveEndpoint(), true) . PHP_EOL;

// Getting the effective HTTP status, f.e. `POST /?paramName1=paramValue1&paramName2=paramValue2&paramName3=1&paramName4=42 HTTP/1.1`
echo print_r($request->getEffectiveStatus(), true) . PHP_EOL;

// Getting the effective raw request headers as string
echo print_r($request->getEffectiveRawHeader(), true) . PHP_EOL;

// Getting the effective request headers as array of `ChromaX\BasicHttpClient\Request\Message\Header\Header` objects
echo print_r($request->getEffectiveHeaders(), true) . PHP_EOL.PHP_EOL;
```

---

## Getting some transactional statistics

```{php}
// Getting the statistics ChromaX\BasicHttpClient\Response\Statistics\Statistics object
$statistics = $request->getResponse()->getStatistics();

// Reading the redirection URL if the server responds with an redirect HTTP header and 
// followRedirects is set to false
echo print_r($statistics->getRedirectEndpoint(), true).PHP_EOL;

// Reading the numbers of redirection as integer
echo print_r($statistics->getRedirectCount(), true).PHP_EOL;

// Getting the time in seconds the redirect utilized as float
echo print_r($statistics->getRedirectTime(), true).PHP_EOL;

// Getting the time in seconds that was utilized until the connection was established
echo print_r($statistics->getConnectionEstablishTime(), true).PHP_EOL;

// Getting the time in seconds that was utilized until the DNS hostname lookup was done
echo print_r($statistics->getHostLookupTime(), true).PHP_EOL;

// Getting the time in seconds that was utilized before the first data was sent
echo print_r($statistics->getPreTransferTime(), true).PHP_EOL;

// Getting the time in seconds that was utilized before the first data was received
echo print_r($statistics->getStartTransferTime(), true).PHP_EOL;

// Getting the time in seconds that was utilized to perfom the request an read the response
echo print_r($statistics->getTotalTime(), true).PHP_EOL;
```

---

## Extending the Basic HTTP Client

Every part of the client is based upon proper interfaces. Most class instances can get injected into the client itself. 
If you want to extend the client just write some classes implementing the according interface and you´re done with that. 

Take a look at the [PHP JSON HTTP Client](https://github.com/chroma-x/php-json-http-client) which is an extension of the PHP Basic HTTP Client.

---

## Exception handling

PHP Basic HTTP Client provides different exceptions – also provided by the PHP Common Exceptions project – for proper handling.  
You can find more information about [PHP Common Exceptions at Github](https://github.com/chroma-x/php-common-exceptions).

### Exceptions to be expected

In general you should expect that any setter method could thrown an `\InvalidArgumentException`. The following exceptions could get thrown while using PHP Basic HTTP Client.

- `ChromaX\CommonException\IoException\FileNotFoundException` on configuring a `ClientCertificateAuthentication`instance
- `ChromaX\CommonException\IoException\FileReadableException` on configuring a `ClientCertificateAuthentication`instance
- `ChromaX\BasicHttpClient\Exception\HttpRequestAuthenticationException` on performing a request
- `ChromaX\BasicHttpClient\Exception\HttpRequestException` on performing a request
- `ChromaX\CommonException\NetworkException\ConnectionTimeoutException` on performing a request
- `ChromaX\CommonException\NetworkException\CurlException` on performing a request

---

## Contribution

Contributing to our projects is always very appreciated.  
**But: please follow the contribution guidelines written down in the [CONTRIBUTING.md](https://github.com/chroma-x/php-basic-http-client/blob/master/CONTRIBUTING.md) document.**

## License

PHP Basic HTTP Client is under the MIT license.
