<?php

namespace Markenwerk\BasicHttpClient\Request\Transport;

/**
 * Class HttpTransport
 *
 * @package Markenwerk\BasicHttpClient\Request\Transport
 */
class HttpTransport implements TransportInterface
{

	public const HTTP_VERSION_1_0 = CURL_HTTP_VERSION_1_0;
	public const HTTP_VERSION_1_1 = CURL_HTTP_VERSION_1_1;

	/**
	 * @var int
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
	 * @return int
	 */
	public function getHttpVersion(): int
	{
		return $this->httpVersion;
	}

	/**
	 * @param int $httpVersion
	 * @return $this
	 */
	public function setHttpVersion(int $httpVersion)
	{
		$this->httpVersion = $httpVersion;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getTimeout(): int
	{
		return $this->timeout;
	}

	/**
	 * @param int $timeout
	 * @return $this
	 */
	public function setTimeout(int $timeout)
	{
		$this->timeout = $timeout;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getReuseConnection(): bool
	{
		return $this->reuseConnection;
	}

	/**
	 * @param bool $reuseConnection
	 * @return $this
	 */
	public function setReuseConnection(bool $reuseConnection)
	{
		$this->reuseConnection = $reuseConnection;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getAllowCaching(): bool
	{
		return $this->allowCaching;
	}

	/**
	 * @param bool $allowCaching
	 * @return $this
	 */
	public function setAllowCaching(bool $allowCaching)
	{
		$this->allowCaching = $allowCaching;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getFollowRedirects(): bool
	{
		return $this->followRedirects;
	}

	/**
	 * @param bool $followRedirects
	 * @return $this
	 */
	public function setFollowRedirects(bool $followRedirects)
	{
		$this->followRedirects = $followRedirects;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getMaxRedirects(): int
	{
		return $this->maxRedirects;
	}

	/**
	 * @param int $maxRedirects
	 * @return $this
	 */
	public function setMaxRedirects(int $maxRedirects)
	{
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
			throw new \TypeError('curl argument invalid. Expected a valid resource. Got ' . $argumentType);
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
