<?php

declare(strict_types=1);

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
	 * @return HeaderInterface[]
	 */
	public function getHeadersByName(string $name): array;

	public function getHeaderByName(string $name): ?HeaderInterface;

	public function clearHeaders(): self;

	/**
	 * @param HeaderInterface[] $headers
	 */
	public function setHeaders(array $headers): self;

	public function addHeader(HeaderInterface $header): self;

	public function setHeader(HeaderInterface $header): self;

	public function removeHeadersByName(string $name): self;

	public function removeHeader(HeaderInterface $header): self;

	public function hasHeaderWithName(string $name): bool;

	public function hasHeader(HeaderInterface $header): bool;

	public function hasHeaders(): bool;

	public function getHeaderCount(): int;

	/**
	 * @return CookieInterface[]
	 */
	public function getCookies(): array;

	public function getCookieByName(string $name): ?CookieInterface;

	public function clearCookies(): self;

	/**
	 * @param CookieInterface[] $cookies
	 */
	public function setCookies(array $cookies): self;

	public function addCookie(CookieInterface $cookie): self;

	public function removeCookieByName(string $name): self;

	public function removeCookie(CookieInterface $cookie): self;

	public function hasCookieWithName(string $name): bool;

	public function hasCookie(CookieInterface $cookie): bool;

	public function hasCookies(): bool;

	public function getCookieCount(): int;

	public function getBody(): ?BodyInterface;

	public function setBody(BodyInterface $body): self;

	public function hasBody(): bool;

	public function removeBody(): self;

}
