<?php

namespace ChromaX\BasicHttpClient;

use ChromaX\BasicHttpClient\Request\RequestInterface;
use ChromaX\BasicHttpClient\Request\Message\Body\Body;
use ChromaX\BasicHttpClient\Request\Message\Message;
use ChromaX\BasicHttpClient\Request\Request;
use ChromaX\BasicHttpClient\Request\Transport\HttpsTransport;
use ChromaX\BasicHttpClient\Request\Transport\HttpTransport;
use ChromaX\BasicHttpClient\Response\ResponseInterface;
use ChromaX\CommonException\NetworkException\Base\NetworkException;
use ChromaX\CommonException\NetworkException\ConnectionTimeoutException;
use ChromaX\UrlUtil\Url;

/**
 * Class BasicHttpClient
 *
 * @package ChromaX\BasicHttpClient
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
	public function __construct(string $endpoint)
	{
		$url = new Url($endpoint);
		$transport = new HttpTransport();
		if (mb_strtoupper($url->getScheme()) === 'HTTPS') {
			$transport = new HttpsTransport();
		}
		$this->request = new Request();
		$this->request
			->setTransport($transport)
			->setMessage(new Message())
			->setUrl(new Url($endpoint));
	}

	/**
	 * @return RequestInterface
	 */
	public function getRequest(): RequestInterface
	{
		return $this->request;
	}

	/**
	 * @param mixed[] $queryParameters
	 * @return ResponseInterface
	 * @throws NetworkException
	 * @throws ConnectionTimeoutException
	 */
	public function get(array $queryParameters = array()): ResponseInterface
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_GET)
			->getUrl()
			->setQueryParametersFromArray($queryParameters);
		$this->request->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param mixed[] $queryParameters
	 * @return ResponseInterface
	 * @throws NetworkException
	 * @throws ConnectionTimeoutException
	 */
	public function head(array $queryParameters = array()): ResponseInterface
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_HEAD)
			->getUrl()
			->setQueryParametersFromArray($queryParameters);
		$this->request->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param array $postData
	 * @return ResponseInterface
	 * @throws NetworkException
	 * @throws ConnectionTimeoutException
	 */
	public function post(array $postData = array()): ResponseInterface
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
	 * @throws NetworkException
	 * @throws ConnectionTimeoutException
	 */
	public function put(array $putData = array()): ResponseInterface
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
	 * @throws NetworkException
	 * @throws ConnectionTimeoutException
	 */
	public function patch(array $patchData = array()): ResponseInterface
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
	 * @param mixed[] $queryParameters
	 * @return ResponseInterface
	 * @throws NetworkException
	 * @throws ConnectionTimeoutException
	 */
	public function delete(array $queryParameters = array()): ResponseInterface
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_DELETE)
			->getUrl()
			->setQueryParametersFromArray($queryParameters);
		$this->request->perform();
		return $this->request->getResponse();
	}

}
