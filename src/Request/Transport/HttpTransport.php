<?php

namespace Markenwerk\BasicHttpClient\Request\Transport;

/**
 * Class HttpTransport
 *
 * @package Markenwerk\BasicHttpClient\Request\Transport
 */
class HttpTransport implements TransportInterface
{

	const HTTP_VERSION_1_0 = CURL_HTTP_VERSION_1_0;
	const HTTP_VERSION_1_1 = CURL_HTTP_VERSION_1_1;

	/**
	 * @var string
	 */
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
		if (!is_int($httpVersion)) {
			$argumentType = (is_object($httpVersion)) ? get_class($httpVersion) : gettype($httpVersion);
			throw new \InvalidArgumentException('Expected the HTTP version as int represented by a Curl constant. Got ' . $argumentType);
		}
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
		if (!is_int($timeout)) {
			$argumentType = (is_object($timeout)) ? get_class($timeout) : gettype($timeout);
			throw new \InvalidArgumentException('Expected the timeout as int. Got ' . $argumentType);
		}
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
		if (!is_bool($reuseConnection)) {
			$argumentType = (is_object($reuseConnection)) ? get_class($reuseConnection) : gettype($reuseConnection);
			throw new \InvalidArgumentException('Expected the reuse connection value as bool. Got ' . $argumentType);
		}
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
		if (!is_bool($allowCaching)) {
			$argumentType = (is_object($allowCaching)) ? get_class($allowCaching) : gettype($allowCaching);
			throw new \InvalidArgumentException('Expected the allow caching value as bool. Got ' . $argumentType);
		}
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
		if (!is_bool($followRedirects)) {
			$argumentType = (is_object($followRedirects)) ? get_class($followRedirects) : gettype($followRedirects);
			throw new \InvalidArgumentException('Expected the follow redirects value as bool. Got ' . $argumentType);
		}
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
		if (!is_int($maxRedirects)) {
			$argumentType = (is_object($maxRedirects)) ? get_class($maxRedirects) : gettype($maxRedirects);
			throw new \InvalidArgumentException('Expected the max redirects value as int. Got ' . $argumentType);
		}
		$this->maxRedirects = $maxRedirects;
		return $this;
	}

	/**
	 * @param resource $curl
	 * @return $this
	 */
	public function configureCurl($curl)
	{
		if (!is_resource($curl)) {
			$argumentType = (is_object($curl)) ? get_class($curl) : gettype($curl);
			throw new \InvalidArgumentException('curl argument invalid. Expected a valid resource. Got ' . $argumentType);
		}
		// HTTP version
		curl_setopt($curl, CURLOPT_HTTP_VERSION, $this->getHttpVersion());
		// Timeout
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->getTimeout());
		curl_setopt($curl, CURLOPT_TIMEOUT, $this->getTimeout());
		// Caching and connection reusage
		if (!$this->getAllowCaching() || !$this->getReuseConnection()) {
			curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
		}
		// Follow redirects
		if ($this->getFollowRedirects()) {
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_MAXREDIRS, $this->getMaxRedirects());
		}
		return $this;
	}

}
