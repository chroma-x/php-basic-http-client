<?php

namespace BasicHttpClient\Request\Transport;

/**
 * Class HttpTransport
 *
 * @package BasicHttpClient\Request\Transport
 */
class HttpTransport implements Base\TransportInterface
{

	const HTTP_VERSION_1_0 = '1.0';
	const HTTP_VERSION_1_1 = '1.1';

	private $httpVersion = self::HTTP_VERSION_1_1;

	/**
	 * @var int
	 */
	private $timeout = 20;

	/**
	 * @var bool
	 */
	private $reuseConnection = true;

	/**
	 * @var bool
	 */
	private $allowCaching = true;

	/**
	 * @var bool
	 */
	private $followRedirects = false;

	/**
	 * @var int
	 */
	private $maxRedirects = 5;

	/**
	 * @return string
	 */
	public function getHttpVersion()
	{
		return $this->httpVersion;
	}

	/**
	 * @param string $httpVersion
	 * @return $this
	 */
	public function setHttpVersion($httpVersion)
	{
		$this->httpVersion = $httpVersion;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getTimeout()
	{
		return $this->timeout;
	}

	/**
	 * @param int $timeout
	 * @return $this
	 */
	public function setTimeout($timeout)
	{
		$this->timeout = $timeout;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getReuseConnection()
	{
		return $this->reuseConnection;
	}

	/**
	 * @param boolean $reuseConnection
	 * @return $this
	 */
	public function setReuseConnection($reuseConnection)
	{
		$this->reuseConnection = $reuseConnection;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getAllowCaching()
	{
		return $this->allowCaching;
	}

	/**
	 * @param boolean $allowCaching
	 * @return $this
	 */
	public function setAllowCaching($allowCaching)
	{
		$this->allowCaching = $allowCaching;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getFollowRedirects()
	{
		return $this->followRedirects;
	}

	/**
	 * @param boolean $followRedirects
	 * @return $this
	 */
	public function setFollowRedirects($followRedirects)
	{
		$this->followRedirects = $followRedirects;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getMaxRedirects()
	{
		return $this->maxRedirects;
	}

	/**
	 * @param int $maxRedirects
	 * @return $this
	 */
	public function setMaxRedirects($maxRedirects)
	{
		$this->maxRedirects = $maxRedirects;
		return $this;
	}

}
