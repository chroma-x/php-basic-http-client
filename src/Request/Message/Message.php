<?php

namespace BasicHttpClient\Request\Message;

use BasicHttpClient\Request\Message\Body\BodyInterface;
use BasicHttpClient\Request\Message\Cookie\CookieInterface;
use BasicHttpClient\Request\Message\Header\HeaderInterface;
use BasicHttpClient\Util\HeaderNameUtil;

/**
 * Class Message
 *
 * @package BasicHttpClient\Request\Message
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
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * @param string $name
	 * @return HeaderInterface[]
	 */
	public function getHeadersByName($name)
	{
		return $this->findHeadersByName($name);
	}

	/**
	 * @param string $name
	 * @return HeaderInterface
	 */
	public function getHeaderByName($name)
	{
		if (!$this->hasHeaderWithName($name)) {
			return null;
		}
		return $this->findHeadersByName($name)[0];
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
		$this->headers = $headers;
		return $this;
	}

	/**
	 * @param HeaderInterface $header
	 * @return $this
	 */
	public function addAdditionalHeader(HeaderInterface $header)
	{
		$this->headers[] = $header;
		return $this;
	}

	/**
	 * @param HeaderInterface $header
	 * @return $this
	 */
	public function addHeader(HeaderInterface $header)
	{
		$this->removeHeadersByName($header->getName());
		$this->headers[] = $header;
		return $this;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function removeHeadersByName($name)
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
	public function hasHeaderWithName($name)
	{
		return count($this->findHeadersByName($name)) > 0;
	}

	/**
	 * @param HeaderInterface $header
	 * @return bool
	 */
	public function hasHeader(HeaderInterface $header)
	{
		return !is_null($this->findHeaderIndex($header));
	}

	/**
	 * @return bool
	 */
	public function hasHeaders()
	{
		return count($this->headers) > 0;
	}

	/**
	 * @return int
	 */
	public function getHeaderCount()
	{
		return (int)count($this->headers);
	}

	/**
	 * @return CookieInterface[]
	 */
	public function getCookies()
	{
		return $this->cookies;
	}

	/**
	 * @param $name
	 * @return CookieInterface
	 */
	public function getCookieByName($name)
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
	public function setCookies($cookies)
	{
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
	public function removeCookieByName($name)
	{
		for ($i = 0; $i < count($this->cookies); $i++) {
			if ($this->cookies[$i]->getName() !== $name) {
				unset($this->cookies[$i]);
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
		for ($i = 0; $i < count($this->cookies); $i++) {
			if ($cookie == $this->cookies[$i]) {
				unset($this->cookies[$i]);
				return $this;
			}
		}
		return $this;
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function hasCookieWithName($name)
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
	public function hasCookie(CookieInterface $cookie)
	{
		foreach ($this->cookies as $existingCookie) {
			if ($existingCookie == $cookie) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @return bool
	 */
	public function hasCookies()
	{
		return count($this->cookies) > 0;
	}

	/**
	 * @return int
	 */
	public function getCookieCount()
	{
		return (int)count($this->cookies);
	}

	/**
	 * @return BodyInterface
	 */
	public function getBody()
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
	public function hasBody()
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
		if ($this->hasBody()) {
			$this->getBody()->configureCurl($curl);
		}
		return $this;
	}

	/**
	 * @param string $name
	 * @return HeaderInterface[]
	 */
	private function findHeadersByName($name)
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
	private function findHeaderIndex(HeaderInterface $header)
	{
		for ($i = 0; $i < count($this->headers); $i++) {
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
	private function findHeadersExcludedByName($name)
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
