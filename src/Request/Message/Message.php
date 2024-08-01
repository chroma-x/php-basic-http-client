<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Request\Message;

use ChromaX\BasicHttpClient\Request\Message\Body\BodyInterface;
use ChromaX\BasicHttpClient\Request\Message\Cookie\CookieInterface;
use ChromaX\BasicHttpClient\Request\Message\Header\HeaderInterface;
use ChromaX\BasicHttpClient\Util\HeaderNameUtil;

/**
 * Class Message
 *
 * @package ChromaX\BasicHttpClient\Request\Message
 */
class Message implements MessageInterface
{

	/**
	 * @var HeaderInterface[]
	 */
	private array $headers = [];

	/**
	 * @var CookieInterface[]
	 */
	private array $cookies = [];

	private ?BodyInterface $body = null;

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

	public function getHeaderByName(string $name): ?HeaderInterface
	{
		if (!$this->hasHeaderWithName($name)) {
			return null;
		}
		$matchingHeaders = $this->findHeadersByName($name);
		return $matchingHeaders[0];
	}

	public function clearHeaders(): self
	{
		$this->headers = array();
		return $this;
	}

	/**
	 * @param HeaderInterface[] $headers
	 */
	public function setHeaders(array $headers): self
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

	public function addHeader(HeaderInterface $header): self
	{
		$this->headers[] = $header;
		return $this;
	}

	public function setHeader(HeaderInterface $header): self
	{
		$this->removeHeadersByName($header->getName());
		$this->headers[] = $header;
		return $this;
	}

	public function removeHeadersByName(string $name): self
	{
		$this->headers = $this->findHeadersExcludedByName($name);
		return $this;
	}

	public function removeHeader(HeaderInterface $header): self
	{
		if (!$this->hasHeader($header)) {
			return $this;
		}
		$headerIndex = $this->findHeaderIndex($header);
		unset($this->headers[$headerIndex]);
		return $this;
	}

	public function hasHeaderWithName(string $name): bool
	{
		return count($this->findHeadersByName($name)) > 0;
	}

	public function hasHeader(HeaderInterface $header): bool
	{
		return !is_null($this->findHeaderIndex($header));
	}

	public function hasHeaders(): bool
	{
		return count($this->headers) > 0;
	}

	public function getHeaderCount(): int
	{
		return count($this->headers);
	}

	public function getCookies(): array
	{
		return $this->cookies;
	}

	public function getCookieByName(string $name): ?CookieInterface
	{
		foreach ($this->cookies as $cookie) {
			if ($cookie->getName() === $name) {
				return $cookie;
			}
		}
		return null;
	}

	public function clearCookies(): self
	{
		$this->cookies = array();
		return $this;
	}

	/**
	 * @param CookieInterface[] $cookies
	 */
	public function setCookies(array $cookies): self
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

	public function addCookie(CookieInterface $cookie): self
	{
		$this->cookies[] = $cookie;
		return $this;
	}

	public function removeCookieByName(string $name): self
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

	public function removeCookie(CookieInterface $cookie): self
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

	public function hasCookieWithName(string $name): bool
	{
		foreach ($this->cookies as $cookie) {
			if ($cookie->getName() !== $name) {
				return true;
			}
		}
		return false;
	}

	public function hasCookie(CookieInterface $cookie): bool
	{
		if (in_array($cookie, $this->cookies, true)) {
			return true;
		}
		return false;
	}

	public function hasCookies(): bool
	{
		return count($this->cookies) > 0;
	}

	public function getCookieCount(): int
	{
		return count($this->cookies);
	}

	public function getBody(): ?BodyInterface
	{
		return $this->body;
	}

	public function setBody(BodyInterface $body): self
	{
		$this->body = $body;
		return $this;
	}

	public function hasBody(): bool
	{
		return !is_null($this->body);
	}

	public function removeBody(): self
	{
		$this->body = null;
		return $this;
	}

	public function configureCurl(\CurlHandle|false $curl): self
	{
		if ($curl === false) {
			throw new \TypeError('cURL is not a valid CurlHandle class.');
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
		$body?->configureCurl($curl);
		return $this;
	}

	/**
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
