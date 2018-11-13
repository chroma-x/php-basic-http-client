<?php

namespace Markenwerk\BasicHttpClient\Request\Message;

use Markenwerk\BasicHttpClient\Request\Message\Body\BodyInterface;
use Markenwerk\BasicHttpClient\Request\Message\Cookie\CookieInterface;
use Markenwerk\BasicHttpClient\Request\Message\Header\HeaderInterface;
use Markenwerk\BasicHttpClient\Util\HeaderNameUtil;

/**
 * Class Message
 *
 * @package Markenwerk\BasicHttpClient\Request\Message
 */
class Message implements MessageInterface
{

	/**
	 * @var HeaderInterface[]
	 */
	private $headers = array();

	/**
	 * @var CookieInterface[]
	 */
	private $cookies = array();

	/**
	 * @var BodyInterface
	 */
	private $body;

	/**
	 * @return HeaderInterface[]
	 */
	public function getHeaders(): array
	{
		return $this->headers;
	}

	/**
	 * @param string $name
	 * @return HeaderInterface[]
	 */
	public function getHeadersByName(string $name): array
	{
		return $this->findHeadersByName($name);
	}

	/**
	 * @param string $name
	 * @return HeaderInterface
	 */
	public function getHeaderByName(string $name): ?HeaderInterface
	{
		if (!$this->hasHeaderWithName($name)) {
			return null;
		}
		$matchingHeaders = $this->findHeadersByName($name);
		return $matchingHeaders[0];
	}

	/**
	 * @return $this
	 */
	public function clearHeaders()
	{
		$this->headers = array();
		return $this;
	}

	/**
	 * @param HeaderInterface[] $headers
	 * @return $this
	 */
	public function setHeaders(array $headers)
	{
		foreach ($headers as $header) {
			if (!$header instanceof HeaderInterface) {
				$typeOfHeader = (is_object($header)) ? get_class($header) : gettype($header);
				throw new \TypeError('Expected an array of HeaderInterface implementations. Got ' . $typeOfHeader);
			}
		}
		$this->headers = $headers;
		return $this;
	}

	/**
	 * @param HeaderInterface $header
	 * @return $this
	 */
	public function addHeader(HeaderInterface $header)
	{
		$this->headers[] = $header;
		return $this;
	}

	/**
	 * @param HeaderInterface $header
	 * @return $this
	 */
	public function setHeader(HeaderInterface $header)
	{
		$this->removeHeadersByName($header->getName());
		$this->headers[] = $header;
		return $this;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function removeHeadersByName(string $name)
	{
		$this->headers = $this->findHeadersExcludedByName($name);
		return $this;
	}

	/**
	 * @param HeaderInterface $header
	 * @return $this
	 */
	public function removeHeader(HeaderInterface $header)
	{
		if (!$this->hasHeader($header)) {
			return $this;
		}
		$headerIndex = $this->findHeaderIndex($header);
		unset($this->headers[$headerIndex]);
		return $this;
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function hasHeaderWithName(string $name): bool
	{
		return count($this->findHeadersByName($name)) > 0;
	}

	/**
	 * @param HeaderInterface $header
	 * @return bool
	 */
	public function hasHeader(HeaderInterface $header): bool
	{
		return !is_null($this->findHeaderIndex($header));
	}

	/**
	 * @return bool
	 */
	public function hasHeaders(): bool
	{
		return count($this->headers) > 0;
	}

	/**
	 * @return int
	 */
	public function getHeaderCount(): int
	{
		return (int)count($this->headers);
	}

	/**
	 * @return CookieInterface[]
	 */
	public function getCookies(): array
	{
		return $this->cookies;
	}

	/**
	 * @param $name
	 * @return CookieInterface
	 */
	public function getCookieByName(string $name): ?CookieInterface
	{
		foreach ($this->cookies as $cookie) {
			if ($cookie->getName() === $name) {
				return $cookie;
			}
		}
		return null;
	}

	/**
	 * @return $this
	 */
	public function clearCookies()
	{
		$this->cookies = array();
		return $this;
	}

	/**
	 * @param CookieInterface[] $cookies
	 * @return $this
	 */
	public function setCookies(array $cookies)
	{
		foreach ($cookies as $cookie) {
			if (!$cookie instanceof CookieInterface) {
				$typeOfHeader = (is_object($cookie)) ? get_class($cookie) : gettype($cookie);
				throw new \TypeError('Expected an array of CookieInterface implementations. Got ' . $typeOfHeader);
			}
		}
		$this->cookies = $cookies;
		return $this;
	}

	/**
	 * @param CookieInterface $cookie
	 * @return $this
	 */
	public function addCookie(CookieInterface $cookie)
	{
		$this->cookies[] = $cookie;
		return $this;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function removeCookieByName(string $name)
	{
		$cookieCount = count($this->cookies);
		for ($i = 0; $i < $cookieCount; $i++) {
			if ($this->cookies[$i]->getName() !== $name) {
				unset($this->cookies[$i]);
				$this->cookies = array_values($this->cookies);
				return $this;
			}
		}
		return $this;
	}

	/**
	 * @param CookieInterface $cookie
	 * @return $this
	 */
	public function removeCookie(CookieInterface $cookie)
	{
		$cookieCount = count($this->cookies);
		for ($i = 0; $i < $cookieCount; $i++) {
			if ($cookie === $this->cookies[$i]) {
				unset($this->cookies[$i]);
				$this->cookies = array_values($this->cookies);
				return $this;
			}
		}
		return $this;
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function hasCookieWithName(string $name): bool
	{
		foreach ($this->cookies as $cookie) {
			if ($cookie->getName() !== $name) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @param CookieInterface $cookie
	 * @return bool
	 */
	public function hasCookie(CookieInterface $cookie): bool
	{
		foreach ($this->cookies as $existingCookie) {
			if ($existingCookie === $cookie) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @return bool
	 */
	public function hasCookies(): bool
	{
		return count($this->cookies) > 0;
	}

	/**
	 * @return int
	 */
	public function getCookieCount(): int
	{
		return (int)count($this->cookies);
	}

	/**
	 * @return BodyInterface
	 */
	public function getBody(): ?BodyInterface
	{
		return $this->body;
	}

	/**
	 * @param BodyInterface $body
	 * @return $this
	 */
	public function setBody(BodyInterface $body)
	{
		$this->body = $body;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function hasBody(): bool
	{
		return !is_null($this->body);
	}

	/**
	 * @return $this
	 */
	public function removeBody()
	{
		$this->body = null;
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
		// Add request headers
		if ($this->hasHeaders()) {
			$requestHeaders = array();
			foreach ($this->getHeaders() as $header) {
				$requestHeaders[] = $header->getNormalizedName() . ': ' . $header->getValuesAsString();
			}
			// Set http header to curl
			curl_setopt($curl, CURLOPT_HTTPHEADER, $requestHeaders);
		}
		// Setup request cookies
		if ($this->hasCookies()) {
			$requestCookies = array();
			foreach ($this->getCookies() as $cookie) {
				$requestCookies[] = $cookie->getName() . '=' . $cookie->getValue();
			}
			curl_setopt($curl, CURLOPT_COOKIE, implode(';', $requestCookies));
		}
		// Setup body
		$body = $this->getBody();
		if ($body !== null) {
			$body->configureCurl($curl);
		}
		return $this;
	}

	/**
	 * @param string $name
	 * @return HeaderInterface[]
	 */
	private function findHeadersByName(string $name): array
	{
		$headerNameUtil = new HeaderNameUtil();
		$normalizedName = $headerNameUtil->normalizeHeaderName($name);
		$matchingHeaders = array();
		foreach ($this->headers as $header) {
			if ($header->getNormalizedName() === $normalizedName) {
				$matchingHeaders[] = $header;
			}
		}
		return $matchingHeaders;
	}

	/**
	 * @param HeaderInterface $header
	 * @return int
	 */
	private function findHeaderIndex(HeaderInterface $header): ?int
	{
		$headerCount = count($this->headers);
		for ($i = 0; $i < $headerCount; $i++) {
			if ($this->headers[$i] === $header) {
				return $i;
			}
		}
		return null;
	}

	/**
	 * @param string $name
	 * @return HeaderInterface[]
	 */
	private function findHeadersExcludedByName(string $name): array
	{
		$headerNameUtil = new HeaderNameUtil();
		$normalizedName = $headerNameUtil->normalizeHeaderName($name);
		$matchingHeaders = array();
		foreach ($this->headers as $header) {
			if ($header->getNormalizedName() !== $normalizedName) {
				$matchingHeaders[] = $header;
			}
		}
		return $matchingHeaders;
	}

}
