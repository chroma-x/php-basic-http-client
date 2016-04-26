<?php

namespace BasicHttpClient\Request\Message;

use BasicHttpClient\Request\Message\Body\Base\BodyInterface;
use BasicHttpClient\Request\Message\Cookie\Cookie;
use BasicHttpClient\Request\Message\Header\Header;
use BasicHttpClient\Util\HeaderNameNormalizer;

/**
 * Class Message
 *
 * @package BasicHttpClient\Request\Message
 */
class Message
{

	/**
	 * @var Header[]
	 */
	private $headers = array();

	/**
	 * @var Cookie[]
	 */
	private $cookies = array();

	/**
	 * @var BodyInterface
	 */
	private $body;

	/**
	 * @return Header[]
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * @param string $name
	 * @return Header[]
	 */
	public function getHeadersByName($name)
	{
		$headerNameNormalizer = new HeaderNameNormalizer();
		$normalizedName = $headerNameNormalizer->normalizeHeaderName($name);
		$matchingHeaders = array();
		foreach ($this->headers as $header) {
			if ($header->getNormalizedName() === $normalizedName) {
				$matchingHeaders[] = $header;
			}
		}
		return $matchingHeaders;
	}

	/**
	 * @param string $name
	 * @return Header
	 */
	public function getHeaderByName($name)
	{
		$headerNameNormalizer = new HeaderNameNormalizer();
		$normalizedName = $headerNameNormalizer->normalizeHeaderName($name);
		foreach ($this->headers as $header) {
			if ($header->getNormalizedName() === $normalizedName) {
				return $header;
			}
		}
		return null;
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
	 * @param Header[] $headers
	 * @return $this
	 */
	public function setHeaders(array $headers)
	{
		$this->headers = $headers;
		return $this;
	}

	/**
	 * @param Header $header
	 * @param bool $replaceExisting
	 * @return $this
	 */
	public function addHeader(Header $header, $replaceExisting = false)
	{
		if ($replaceExisting) {
			$this->removeHeadersByName($header->getName());
		}
		$this->headers[] = $header;
		return $this;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function removeHeadersByName($name)
	{
		$headerNameNormalizer = new HeaderNameNormalizer();
		$normalizedName = $headerNameNormalizer->normalizeHeaderName($name);
		$remainingHeaders = array();
		foreach ($this->headers as $header) {
			if ($header->getNormalizedName() !== $normalizedName) {
				$remainingHeaders[] = $header;
			}
		}
		return $this;
	}

	/**
	 * @param Header $header
	 * @return $this
	 */
	public function removeHeader(Header $header)
	{
		for ($i = 0; $i < count($this->headers); $i++) {
			if ($header == $this->headers[$i]) {
				unset($this->headers[$i]);
				return $this;
			}
		}
		return $this;
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function hasHeaderWithName($name)
	{
		$headerNameNormalizer = new HeaderNameNormalizer();
		$normalizedName = $headerNameNormalizer->normalizeHeaderName($name);
		foreach ($this->headers as $header) {
			if ($header->getNormalizedName() !== $normalizedName) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @param Header $header
	 * @return bool
	 */
	public function hasHeader(Header $header)
	{
		foreach ($this->headers as $existingHeader) {
			if ($existingHeader == $header) {
				return true;
			}
		}
		return false;
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
	 * @return Cookie[]
	 */
	public function getCookies()
	{
		return $this->cookies;
	}

	/**
	 * @param $name
	 * @return Cookie
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
	 * @param Cookie[] $cookies
	 * @return $this
	 */
	public function setCookies($cookies)
	{
		$this->cookies = $cookies;
		return $this;
	}

	/**
	 * @param Cookie $cookie
	 * @return $this
	 */
	public function addCookie(Cookie $cookie)
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
	 * @param Cookie $cookie
	 * @return $this
	 */
	public function removeCookie(Cookie $cookie)
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
	 * @param Cookie $cookie
	 * @return bool
	 */
	public function hasCookie(Cookie $cookie)
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

}
