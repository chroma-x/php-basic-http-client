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
class BasicHttpClient implements HttpClientInterface
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
	 * @param string[] $queryParameters
	 * @return ResponseInterface
	 * @throws \CommonException\NetworkException\Base\NetworkException
	 * @throws \CommonException\NetworkException\ConnectionTimeoutException
	 */
	public function get(array $queryParameters = null)
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_GET)
			->setQueryParameters($queryParameters)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param string[] $queryParameters
	 * @return ResponseInterface
	 * @throws \CommonException\NetworkException\Base\NetworkException
	 * @throws \CommonException\NetworkException\ConnectionTimeoutException
	 */
	public function head(array $queryParameters = null)
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_HEAD)
			->setQueryParameters($queryParameters)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param array $postData
	 * @return ResponseInterface
	 * @throws \CommonException\NetworkException\Base\NetworkException
	 * @throws \CommonException\NetworkException\ConnectionTimeoutException
	 */
	public function post(array $postData = null)
	{
		$body = new Body();
		$body->setBodyTextFromArray($postData);
		$this->request
			->getMessage()
			->setBody($body);
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_POST)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param array $putData
	 * @return ResponseInterface
	 * @throws \CommonException\NetworkException\Base\NetworkException
	 * @throws \CommonException\NetworkException\ConnectionTimeoutException
	 */
	public function put(array $putData = null)
	{
		$body = new Body();
		$body->setBodyTextFromArray($putData);
		$this->request
			->getMessage()
			->setBody($body);
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_PUT)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param array $patchData
	 * @return ResponseInterface
	 * @throws \CommonException\NetworkException\Base\NetworkException
	 * @throws \CommonException\NetworkException\ConnectionTimeoutException
	 */
	public function patch(array $patchData = null)
	{
		$body = new Body();
		$body->setBodyTextFromArray($patchData);
		$this->request
			->getMessage()
			->setBody($body);
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_PATCH)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param string[] $queryParameters
	 * @return ResponseInterface
	 * @throws \CommonException\NetworkException\Base\NetworkException
	 * @throws \CommonException\NetworkException\ConnectionTimeoutException
	 */
	public function delete(array $queryParameters = null)
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_DELETE)
			->setQueryParameters($queryParameters)
			->perform();
		return $this->request->getResponse();
	}

}
