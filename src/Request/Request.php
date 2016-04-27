<?php

namespace BasicHttpClient\Request;

use BasicHttpClient\Request\Authentication\Base\AuthenticationInterface;
use BasicHttpClient\Request\Base\RequestInterface;
use BasicHttpClient\Request\Message\Base\MessageInterface;
use BasicHttpClient\Request\Transport\Base\TransportInterface;
use BasicHttpClient\Request\Transport\HttpTransport;
use BasicHttpClient\Util\UrlUtil;

/**
 * Class Request
 *
 * @package BasicHttpClient\Request
 */
class Request implements RequestInterface
{

	const REQUEST_METHOD_GET = 'GET';
	const REQUEST_METHOD_HEAD = 'HEAD';
	const REQUEST_METHOD_POST = 'POST';
	const REQUEST_METHOD_PUT = 'PUT';
	const REQUEST_METHOD_PATCH = 'PATCH';
	const REQUEST_METHOD_DELETE = 'DELETE';

	/**
	 * @var string
	 */
	private $userAgent = 'PHP Basic HTTP Client 1.0';

	/**
	 * @var string
	 */
	private $endpoint;

	/**
	 * @var int
	 */
	private $port;

	/**
	 * @var string
	 */
	private $method = self::REQUEST_METHOD_GET;

	/**
	 * @var TransportInterface
	 */
	private $transport;

	/**
	 * @var AuthenticationInterface[]
	 */
	private $authentications = array();

	/**
	 * @var MessageInterface
	 */
	private $message;

	/**
	 * Request constructor.
	 */
	public function __construct()
	{
		$this->transport = new HttpTransport();
	}

	/**
	 * @return string
	 */
	public function getUserAgent()
	{
		return $this->userAgent;
	}

	/**
	 * @param string $userAgent
	 * @return $this
	 */
	public function setUserAgent($userAgent)
	{
		$this->userAgent = $userAgent;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEndpoint()
	{
		return $this->endpoint;
	}

	/**
	 * @param string $endpoint
	 * @return $this
	 */
	public function setEndpoint($endpoint)
	{
		$urlUtil = new UrlUtil();
		if (!$urlUtil->validateUrl($endpoint)) {
			throw new \InvalidArgumentException('The given endpoint is not a valid URL');
		}
		$this->endpoint = $endpoint;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getPort()
	{
		return $this->port;
	}

	/**
	 * @param int $port
	 * @return $this
	 */
	public function setPort($port)
	{
		$this->port = $port;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * @param string $method
	 * @return $this
	 */
	public function setMethod($method)
	{
		$this->method = $method;
		return $this;
	}

	/**
	 * @return TransportInterface
	 */
	public function getTransport()
	{
		return $this->transport;
	}

	/**
	 * @param TransportInterface $transport
	 * @return $this
	 */
	public function setTransport(TransportInterface $transport)
	{
		$this->transport = $transport;
		return $this;
	}

	/**
	 * @return MessageInterface
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @param MessageInterface $message
	 * @return $this
	 */
	public function setMessage(MessageInterface $message)
	{
		$this->message = $message;
		return $this;
	}

	/**
	 * @return AuthenticationInterface[]
	 */
	public function getAuthentications()
	{
		return $this->authentications;
	}

	/**
	 * @param AuthenticationInterface[] $authentications
	 * @return $this
	 */
	public function setAuthentications(array $authentications)
	{
		$this->authentications = $authentications;
		return $this;
	}

	/**
	 * @param AuthenticationInterface $authentication
	 * @return $this
	 */
	public function addAuthentication(AuthenticationInterface $authentication)
	{
		if (!$this->hasAuthentication($authentication)) {
			$this->authentications[] = $authentication;
		}
		return $this;
	}

	/**
	 * @param AuthenticationInterface $authentication
	 * @return $this
	 */
	public function removeAuthentication(AuthenticationInterface $authentication)
	{
		for ($i = 0; $i < count($this->authentications); $i++) {
			if ($this->authentications[$i] == $authentication) {
				unset($this->authentications[$i]);
				return $this;
			}
		}
		return $this;
	}

	/**
	 * @param AuthenticationInterface $authentication
	 * @return bool
	 */
	public function hasAuthentication(AuthenticationInterface $authentication)
	{
		foreach ($this->authentications as $existingAuthentication) {
			if ($authentication == $existingAuthentication) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @return bool
	 */
	public function hasAuthentications()
	{
		return count($this->authentications) > 0;
	}

	/**
	 * @return int
	 */
	public function countAuthentications()
	{
		return count($this->authentications);
	}

	/**
	 * @param resource $curl
	 * @return $this
	 */
	public function configureCurl($curl)
	{
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);
		curl_setopt($curl, CURLOPT_USERAGENT, $this->getUserAgent());
		curl_setopt($curl, CURLOPT_URL, $this->getEndpoint());
		// Setup request method
		switch ($this->getMethod()) {
			case self::REQUEST_METHOD_GET:
			case self::REQUEST_METHOD_HEAD:
				curl_setopt($curl, CURLOPT_HTTPGET, true);
				// Modify the URL using the body as query string
				break;
			case self::REQUEST_METHOD_POST:
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
				// curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
				break;
			case self::REQUEST_METHOD_PUT:
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
				// curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
				break;
			case self::REQUEST_METHOD_PATCH:
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
				// curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
				break;
			case self::REQUEST_METHOD_DELETE:
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
				break;
		}
		return $this;
	}

	/**
	 * @return $this
	 */
	public function perform()
	{
		// Curl basic setup
		$curl = curl_init();
		$this->configureCurl($curl);
		$this->getTransport()->configureCurl($curl);
		$this->getMessage()->configureCurl($curl);
		// Execute request
		$responseBody = curl_exec($curl);
		// TODO: Parse the response body
		curl_close($curl);
		return $this;
	}

}
