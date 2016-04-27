<?php

namespace BasicHttpClient;

use BasicHttpClient\Request\RequestInterface;
use BasicHttpClient\Request\Message\Body\Body;
use BasicHttpClient\Request\Message\Message;
use BasicHttpClient\Request\Request;
use BasicHttpClient\Request\Transport\HttpsTransport;
use BasicHttpClient\Request\Transport\HttpTransport;
use BasicHttpClient\Response\ResponseInterface;
use BasicHttpClient\Util\UrlUtil;

/**
 * Class BasicHttpClient
 *
 * @package BasicHttpClient
 */
class BasicHttpClient
{

	/**
	 * @var RequestInterface
	 */
	private $request;

	/**
	 * BasicHttpClient constructor.
	 *
	 * @param string $endpoint
	 */
	public function __construct($endpoint)
	{
		$urlUtil = new UrlUtil();
		$transport = new HttpTransport();
		if ($urlUtil->getScheme($endpoint) == 'HTTPS') {
			$transport = new HttpsTransport();
		}
		$this->request = new Request();
		$this->request
			->setTransport($transport)
			->setMessage(new Message())
			->setEndpoint($endpoint);
	}

	/**
	 * @return RequestInterface
	 */
	public function getRequest()
	{
		return $this->request;
	}

	/**
	 * @return ResponseInterface
	 */
	public function get()
	{
		$this->request
			->setMethod(Request::REQUEST_METHOD_GET)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @return ResponseInterface
	 */
	public function head()
	{
		$this->request
			->setMethod(Request::REQUEST_METHOD_HEAD)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param array $postData
	 * @return ResponseInterface
	 */
	public function post(array $postData)
	{
		$body = new Body();
		$body->setBodyTextFromArray($postData);
		$this->request
			->getMessage()
			->setBody($body);
		$this->request
			->setMethod(Request::REQUEST_METHOD_POST)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param array $putData
	 * @return ResponseInterface
	 */
	public function put(array $putData)
	{
		$body = new Body();
		$body->setBodyTextFromArray($putData);
		$this->request
			->getMessage()
			->setBody($body);
		$this->request
			->setMethod(Request::REQUEST_METHOD_PUT)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param array $patchData
	 * @return ResponseInterface
	 */
	public function patch(array $patchData)
	{
		$body = new Body();
		$body->setBodyTextFromArray($patchData);
		$this->request
			->getMessage()
			->setBody($body);
		$this->request
			->setMethod(Request::REQUEST_METHOD_PATCH)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @return ResponseInterface
	 */
	public function delete()
	{
		$this->request
			->setMethod(Request::REQUEST_METHOD_DELETE)
			->perform();
		return $this->request->getResponse();
	}

}
