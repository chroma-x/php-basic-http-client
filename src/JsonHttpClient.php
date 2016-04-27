<?php

namespace BasicHttpClient;

use BasicHttpClient\Request\Message\Header\Header;
use BasicHttpClient\Request\RequestInterface;
use BasicHttpClient\Request\Message\Body\JsonBody;
use BasicHttpClient\Request\Message\Message;
use BasicHttpClient\Request\JsonRequest;
use BasicHttpClient\Request\Transport\HttpsTransport;
use BasicHttpClient\Request\Transport\HttpTransport;
use BasicHttpClient\Response\ResponseInterface;
use BasicHttpClient\Util\UrlUtil;

/**
 * Class JsonHttpClient
 *
 * @package BasicHttpClient
 */
class JsonHttpClient implements HttpClientInterface
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
		$message = new Message();
		$message
			->addHeader(new Header('Accept', array('application/json')))
			->addHeader(new Header('Content-Type', array('application/json')));
		$this->request = new JsonRequest();
		$this->request
			->setTransport($transport)
			->setMessage($message)
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
			->setMethod(RequestInterface::REQUEST_METHOD_GET)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @return ResponseInterface
	 */
	public function head()
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_HEAD)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param array $postData
	 * @return ResponseInterface
	 */
	public function post(array $postData)
	{
		$this->request
			->getMessage()
			->setBody(new JsonBody($postData));
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_POST)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param array $putData
	 * @return ResponseInterface
	 */
	public function put(array $putData)
	{
		$this->request
			->getMessage()
			->setBody(new JsonBody($putData));
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_PUT)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param array $patchData
	 * @return ResponseInterface
	 */
	public function patch(array $patchData)
	{
		$this->request
			->getMessage()
			->setBody(new JsonBody($patchData));
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_PATCH)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @return ResponseInterface
	 */
	public function delete()
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_DELETE)
			->perform();
		return $this->request->getResponse();
	}

}
