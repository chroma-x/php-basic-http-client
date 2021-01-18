<?php

namespace ChromaX\BasicHttpClient\Request\Message;

use ChromaX\BasicHttpClient\Request\Base\CurlConfiguratorInterface;
use ChromaX\BasicHttpClient\Request\Message\Body\BodyInterface;
use ChromaX\BasicHttpClient\Request\Message\Cookie\CookieInterface;
use ChromaX\BasicHttpClient\Request\Message\Header\HeaderInterface;

/**
 * Interface MessageInterface
 *
 * @package ChromaX\BasicHttpClient\Request\Message
 */
interface MessageInterface extends CurlConfiguratorInterface
{

	/**
	 * @return HeaderInterface[]
	 */
	public function getHeaders(): array;

	/**
	 * @param string $name
	 * @return HeaderInterface[]
	 */
	public function getHeadersByName(string $name): array;

	/**
	 * @param string $name
	 * @return HeaderInterface
	 */
	public function getHeaderByName(string $name): ?HeaderInterface;

	/**
	 * @return $this
	 */
	public function clearHeaders();

	/**
	 * @param HeaderInterface[] $headers
	 * @return $this
	 */
	public function setHeaders(array $headers);

	/**
	 * @param HeaderInterface $header
	 * @return $this
	 */
	public function addHeader(HeaderInterface $header);

	/**
	 * @param HeaderInterface $header
	 * @return $this
	 */
	public function setHeader(HeaderInterface $header);

	/**
	 * @param string $name
	 * @return $this
	 */
	public function removeHeadersByName(string $name);

	/**
	 * @param HeaderInterface $header
	 * @return $this
	 */
	public function removeHeader(HeaderInterface $header);

	/**
	 * @param $name
	 * @return bool
	 */
	public function hasHeaderWithName(string $name): bool;

	/**
	 * @param HeaderInterface $header
	 * @return bool
	 */
	public function hasHeader(HeaderInterface $header): bool;

	/**
	 * @return bool
	 */
	public function hasHeaders(): bool;

	/**
	 * @return int
	 */
	public function getHeaderCount(): int;

	/**
	 * @return CookieInterface[]
	 */
	public function getCookies(): array;

	/**
	 * @param $name
	 * @return CookieInterface
	 */
	public function getCookieByName(string $name): ?CookieInterface;

	/**
	 * @return $this
	 */
	public function clearCookies();

	/**
	 * @param CookieInterface[] $cookies
	 * @return $this
	 */
	public function setCookies(array $cookies);

	/**
	 * @param CookieInterface $cookie
	 * @return $this
	 */
	public function addCookie(CookieInterface $cookie);

	/**
	 * @param string $name
	 * @return $this
	 */
	public function removeCookieByName(string $name);

	/**
	 * @param CookieInterface $cookie
	 * @return $this
	 */
	public function removeCookie(CookieInterface $cookie);

	/**
	 * @param $name
	 * @return bool
	 */
	public function hasCookieWithName(string $name): bool;

	/**
	 * @param CookieInterface $cookie
	 * @return bool
	 */
	public function hasCookie(CookieInterface $cookie): bool;

	/**
	 * @return bool
	 */
	public function hasCookies(): bool;

	/**
	 * @return int
	 */
	public function getCookieCount(): int;

	/**
	 * @return BodyInterface
	 */
	public function getBody(): ?BodyInterface;

	/**
	 * @param BodyInterface $body
	 * @return $this
	 */
	public function setBody(BodyInterface $body);

	/**
	 * @return bool
	 */
	public function hasBody(): bool;

	/**
	 * @return $this
	 */
	public function removeBody();

}
