<?php

namespace Markenwerk\BasicHttpClient\Request;

use Markenwerk\BasicHttpClient\Request\Authentication\AuthenticationInterface;
use Markenwerk\BasicHttpClient\Request\Base\CurlConfiguratorInterface;
use Markenwerk\BasicHttpClient\Request\Message\MessageInterface;
use Markenwerk\BasicHttpClient\Request\Message\Header\Header;
use Markenwerk\BasicHttpClient\Request\Transport\TransportInterface;
use Markenwerk\BasicHttpClient\Response\ResponseInterface;
use Markenwerk\UrlUtil\UrlInterface;

/**
 * Interface RequestInterface
 *
 * @package Markenwerk\BasicHttpClient\Request
 */
interface RequestInterface extends CurlConfiguratorInterface
{

	public const REQUEST_METHOD_GET = 'GET';
	public const REQUEST_METHOD_HEAD = 'HEAD';
	public const REQUEST_METHOD_POST = 'POST';
	public const REQUEST_METHOD_PUT = 'PUT';
	public const REQUEST_METHOD_PATCH = 'PATCH';
	public const REQUEST_METHOD_DELETE = 'DELETE';

	/**
	 * @return string
	 */
	public function getUserAgent(): string;

	/**
	 * @param string $userAgent
	 * @return $this
	 */
	public function setUserAgent(string $userAgent);

	/**
	 * @return string
	 */
	public function getMethod(): string;

	/**
	 * @param string $method
	 * @return $this
	 */
	public function setMethod(string $method);

	/**
	 * @return UrlInterface
	 */
	public function getUrl(): ?UrlInterface;

	/**
	 * @param UrlInterface $url
	 * @return $this
	 */
	public function setUrl(UrlInterface $url);

	/**
	 * @return TransportInterface
	 */
	public function getTransport(): ?TransportInterface;

	/**
	 * @param TransportInterface $transport
	 * @return $this
	 */
	public function setTransport(TransportInterface $transport);

	/**
	 * @return MessageInterface
	 */
	public function getMessage(): ?MessageInterface;

	/**
	 * @param MessageInterface $message
	 * @return $this
	 */
	public function setMessage(MessageInterface $message);

	/**
	 * @return AuthenticationInterface[]
	 */
	public function getAuthentications(): array;

	/**
	 * @param AuthenticationInterface[] $authentications
	 * @return $this
	 */
	public function setAuthentications(array $authentications);

	/**
	 * @param AuthenticationInterface $authentication
	 * @return $this
	 */
	public function addAuthentication(AuthenticationInterface $authentication);

	/**
	 * @param AuthenticationInterface $authentication
	 * @return $this
	 */
	public function removeAuthentication(AuthenticationInterface $authentication);

	/**
	 * @param AuthenticationInterface $authentication
	 * @return bool
	 */
	public function hasAuthentication(AuthenticationInterface $authentication): bool;

	/**
	 * @return bool
	 */
	public function hasAuthentications(): bool;

	/**
	 * @return int
	 */
	public function countAuthentications(): int;

	/**
	 * @return $this
	 */
	public function perform();

	/**
	 * @return ResponseInterface
	 */
	public function getResponse(): ?ResponseInterface;

	/**
	 * @return string
	 */
	public function getEffectiveStatus(): ?string;

	/**
	 * @return string
	 */
	public function getEffectiveEndpoint(): ?string;

	/**
	 * @return string
	 */
	public function getEffectiveRawHeader(): ?string;

	/**
	 * @return Header[]
	 */
	public function getEffectiveHeaders(): ?array;

}
