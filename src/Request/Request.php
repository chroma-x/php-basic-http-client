<?php

namespace BasicHttpClient\Request;

use BasicHttpClient\Request\Authentication\Base\AuthenticationInterface;
use BasicHttpClient\Request\Transport\Base\TransportInterface;
use BasicHttpClient\Request\Transport\HttpTransport;

/**
 * Class Request
 *
 * @package BasicHttpClient\Request
 */
class Request
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
	 * Request constructor.
	 */
	public function __construct()
	{
		$this->transport = new HttpTransport();
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
		// TODO: Validate argument as URL
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
		// TODO: Validate argument type
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

}
