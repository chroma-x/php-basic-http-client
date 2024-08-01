<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Request\Transport;

/**
 * Class HttpTransport
 *
 * @package ChromaX\BasicHttpClient\Request\Transport
 */
class HttpTransport implements TransportInterface
{

	public const int HTTP_VERSION_1_0 = CURL_HTTP_VERSION_1_0;
	public const int HTTP_VERSION_1_1 = CURL_HTTP_VERSION_1_1;

	private int $httpVersion = self::HTTP_VERSION_1_1;

	private int $timeout = 20;

	private bool $reuseConnection = true;

	private bool $allowCaching = true;

	private bool $followRedirects = false;

	private int $maxRedirects = 5;

	public function getHttpVersion(): int
	{
		return $this->httpVersion;
	}

	public function setHttpVersion(int $httpVersion): self
	{
		$this->httpVersion = $httpVersion;
		return $this;
	}

	public function getTimeout(): int
	{
		return $this->timeout;
	}

	public function setTimeout(int $timeout): self
	{
		$this->timeout = $timeout;
		return $this;
	}

	public function getReuseConnection(): bool
	{
		return $this->reuseConnection;
	}

	public function setReuseConnection(bool $reuseConnection): self
	{
		$this->reuseConnection = $reuseConnection;
		return $this;
	}

	public function getAllowCaching(): bool
	{
		return $this->allowCaching;
	}

	public function setAllowCaching(bool $allowCaching): self
	{
		$this->allowCaching = $allowCaching;
		return $this;
	}

	public function getFollowRedirects(): bool
	{
		return $this->followRedirects;
	}

	public function setFollowRedirects(bool $followRedirects): self
	{
		$this->followRedirects = $followRedirects;
		return $this;
	}

	public function getMaxRedirects(): int
	{
		return $this->maxRedirects;
	}

	public function setMaxRedirects(int $maxRedirects): self
	{
		$this->maxRedirects = $maxRedirects;
		return $this;
	}

	public function configureCurl(\CurlHandle|false $curl): self
	{
		if ($curl === false) {
			throw new \TypeError('cURL is not a valid CurlHandle class.');
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
