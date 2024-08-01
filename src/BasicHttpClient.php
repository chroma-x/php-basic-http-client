<?php

declare(strict_types=1);

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

	private RequestInterface $request;

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

	public function getRequest(): RequestInterface
	{
		return $this->request;
	}

	public function get(array $queryParameters = []): ResponseInterface
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_GET)
			->getUrl()
			->setQueryParametersFromArray($queryParameters);
		$this->request->perform();
		return $this->request->getResponse();
	}

	public function head(array $queryParameters = []): ResponseInterface
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_HEAD)
			->getUrl()
			->setQueryParametersFromArray($queryParameters);
		$this->request->perform();
		return $this->request->getResponse();
	}

	public function post(array $postData = []): ResponseInterface
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

	public function put(array $putData = []): ResponseInterface
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

	public function patch(array $patchData = []): ResponseInterface
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

	public function delete(array $queryParameters = []): ResponseInterface
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_DELETE)
			->getUrl()
			->setQueryParametersFromArray($queryParameters);
		$this->request->perform();
		return $this->request->getResponse();
	}

}
