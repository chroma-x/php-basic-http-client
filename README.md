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

## Exception handling

PHP Basic HTTP Client provides different exceptions – also provided by the PHP Common Exceptions project – for proper handling.  
You can find more information about [PHP Common Exceptions at Github](https://github.com/markenwerk/php-common-exceptions).

## Contribution

Contributing to our projects is always very appreciated.  
**But: please follow the contribution guidelines written down in the [CONTRIBUTING.md](https://github.com/markenwerk/php-basic-http-client/blob/master/CONTRIBUTING.md) document.**

## License

PHP Basic HTTP Client is under the MIT license.