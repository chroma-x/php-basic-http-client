# PHP Basic HTTP Client

[![Build Status](https://travis-ci.org/markenwerk/php-basic-http-client.svg?branch=master)](https://travis-ci.org/markenwerk/php-basic-http-client)
[![Test Coverage](https://codeclimate.com/github/markenwerk/php-basic-http-client/badges/coverage.svg)](https://codeclimate.com/github/markenwerk/php-basic-http-client/coverage)
[![Dependency Status](https://www.versioneye.com/user/projects/571f8827fcd19a00415b2836/badge.svg)](https://www.versioneye.com/user/projects/571f8827fcd19a00415b2836)
[![Code Climate](https://codeclimate.com/github/markenwerk/php-basic-http-client/badges/gpa.svg)](https://codeclimate.com/github/markenwerk/php-basic-http-client)
[![Latest Stable Version](https://poser.pugx.org/markenwerk/basic-http-client/v/stable)](https://packagist.org/packages/markenwerk/basic-http-client)
[![Total Downloads](https://poser.pugx.org/markenwerk/basic-http-client/downloads)](https://packagist.org/packages/markenwerk/basic-http-client)
[![License](https://poser.pugx.org/markenwerk/basic-http-client/license)](https://packagist.org/packages/markenwerk/basic-http-client)

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
        "markenwerk/basic-http-client": "~1.0"
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
use BasicHttpClient;
use BasicHttpClient\Request\Authentication;
use BasicHttpClient\Request\Message;

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

#### Configuring a HTTP Transport instance

```{php}
use BasicHttpClient\Request\Transport\HttpTransport;

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

#### Configuring a HTTPS Transport instance

```{php}
use BasicHttpClient\Request\Transport\HttpsTransport;

// Configuring a Transport instance
$transport = new HttpsTransport();
$transport
	->setHttpVersion(HttpsTransport::HTTP_VERSION_1_1)
	->setTimeout(5)
	->setReuseConnection(true)
	->setAllowCaching(true)
	->setFollowRedirects(true)
	->setMaxRedirects(10)
	->setVerifyPeer(true);
```

#### Configuring a Message instance with Body

```{php}
use BasicHttpClient\Request\Message\Body\Body;
use BasicHttpClient\Request\Message\Cookie\Cookie;
use BasicHttpClient\Request\Message\Header\Header;
use BasicHttpClient\Request\Message\Message;

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

#### Configuring a Request instance and perform the HTTP request

```{php}
use BasicHttpClient\Request\Authentication\BasicAuthentication;
use BasicHttpClient\Request\Request;

// Configuring and performing a Request
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
```

The resulting HTTP request would be the following.

```{http}
POST /?paramName1=paramValue1&paramName2=paramValue2 HTTP/1.1
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
use BasicHttpClient\Request\Authentication\BasicAuthentication;
use BasicHttpClient\Request\Request;

// Configuring the authentication
$basicAuthentication = new BasicAuthentication('username', 'password');

// Adding the authentication instance to the Request
$request = new Request();
$response = $request->addAuthentication($basicAuthentication);
```

#### SSL Client Certificate Authentication

Required credentials are a *Certificate Authority Certificate*, a *Client Certificate* and the password that is used to decrypt the Client Certificate that get provided to the class constructor as arguments.

```{php}
use BasicHttpClient\Request\Authentication\ClientCertificateAuthentication;
use BasicHttpClient\Request\Request;

// Configuring the authentication
$clientCertificateAuthentication = new ClientCertificateAuthentication(
	'/var/www/project/clientCert/ca.crt',
	'/var/www/project/clientCert/client.crt',
	'clientCertPassword'
);

// Adding the authentication instance to the Request
$request = new Request();
$response = $request->addAuthentication($clientCertificateAuthentication);
```

---

## Reading from the resulting Response instance

TODO

---

## Getting effective Request information

TODO

---

## Getting some transactional statistics

TODO

---

## Extending the Basic HTTP Client

Every part of the client is based upon proper interfaces. Most class instances can get injected into the client itself. 
If you want to extend the client just write some classes implementing the according interface and you´re done with that. 

Take a look at the [PHP JSON HTTP Client](https://github.com/markenwerk/php-json-http-client) which is an extension of the PHP Basic HTTP Client.

---

## Exception handling

PHP Basic HTTP Client provides different exceptions – also provided by the PHP Common Exceptions project – for proper handling.  
You can find more information about [PHP Common Exceptions at Github](https://github.com/markenwerk/php-common-exceptions).

## Contribution

Contributing to our projects is always very appreciated.  
**But: please follow the contribution guidelines written down in the [CONTRIBUTING.md](https://github.com/markenwerk/php-basic-http-client/blob/master/CONTRIBUTING.md) document.**

## License

PHP Basic HTTP Client is under the MIT license.