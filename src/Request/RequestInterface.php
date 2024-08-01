<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Request;

use ChromaX\BasicHttpClient\Request\Authentication\AuthenticationInterface;
use ChromaX\BasicHttpClient\Request\Base\CurlConfiguratorInterface;
use ChromaX\BasicHttpClient\Request\Message\MessageInterface;
use ChromaX\BasicHttpClient\Request\Message\Header\Header;
use ChromaX\BasicHttpClient\Request\Transport\TransportInterface;
use ChromaX\BasicHttpClient\Response\ResponseInterface;
use ChromaX\UrlUtil\UrlInterface;

/**
 * Interface RequestInterface
 *
 * @package ChromaX\BasicHttpClient\Request
 */
interface RequestInterface extends CurlConfiguratorInterface
{

	public const string REQUEST_METHOD_GET = 'GET';
	public const string REQUEST_METHOD_HEAD = 'HEAD';
	public const string REQUEST_METHOD_POST = 'POST';
	public const string REQUEST_METHOD_PUT = 'PUT';
	public const string REQUEST_METHOD_PATCH = 'PATCH';
	public const string REQUEST_METHOD_DELETE = 'DELETE';

	public function getUserAgent(): string;

	public function setUserAgent(string $userAgent): self;

	public function getMethod(): string;

	public function setMethod(string $method): self;

	public function getUrl(): ?UrlInterface;

	public function setUrl(UrlInterface $url): self;

	public function getTransport(): ?TransportInterface;

	public function setTransport(TransportInterface $transport): self;

	public function getMessage(): ?MessageInterface;

	public function setMessage(MessageInterface $message): self;

	/**
	 * @return AuthenticationInterface[]
	 */
	public function getAuthentications(): array;

	/**
	 * @param AuthenticationInterface[] $authentications
	 */
	public function setAuthentications(array $authentications): self;

	public function addAuthentication(AuthenticationInterface $authentication): self;

	public function removeAuthentication(AuthenticationInterface $authentication): self;

	public function hasAuthentication(AuthenticationInterface $authentication): bool;

	public function hasAuthentications(): bool;

	public function countAuthentications(): int;

	public function perform(): self;

	public function getResponse(): ?ResponseInterface;

	public function getEffectiveStatus(): ?string;

	public function getEffectiveEndpoint(): ?string;

	public function getEffectiveRawHeader(): ?string;

	/**
	 * @return ?Header[]
	 */
	public function getEffectiveHeaders(): ?array;

}
