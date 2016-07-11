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

	const REQUEST_METHOD_GET = 'GET';
	const REQUEST_METHOD_HEAD = 'HEAD';
	const REQUEST_METHOD_POST = 'POST';
	const REQUEST_METHOD_PUT = 'PUT';
	const REQUEST_METHOD_PATCH = 'PATCH';
	const REQUEST_METHOD_DELETE = 'DELETE';

	/**
	 * @return string
	 */
	public function getUserAgent();

	/**
	 * @param string $userAgent
	 * @return $this
	 */
	public function setUserAgent($userAgent);

	/**
	 * @return string
	 */
	public function getMethod();

	/**
	 * @param string $method
	 * @return $this
	 */
	public function setMethod($method);

	/**
	 * @return UrlInterface
	 */
	public function getUrl();

	/**
	 * @param UrlInterface $url
	 * @return $this
	 */
	public function setUrl(UrlInterface $url);

	/**
	 * @return TransportInterface
	 */
	public function getTransport();

	/**
	 * @param TransportInterface $transport
	 * @return $this
	 */
	public function setTransport(TransportInterface $transport);

	/**
	 * @return MessageInterface
	 */
	public function getMessage();

	/**
	 * @param MessageInterface $message
	 * @return $this
	 */
	public function setMessage(MessageInterface $message);

	/**
	 * @return AuthenticationInterface[]
	 */
	public function getAuthentications();

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
	public function hasAuthentication(AuthenticationInterface $authentication);

	/**
	 * @return bool
	 */
	public function hasAuthentications();

	/**
	 * @return int
	 */
	public function countAuthentications();

	/**
	 * @return $this
	 */
	public function perform();

	/**
	 * @return ResponseInterface
	 */
	public function getResponse();

	/**
	 * @return string
	 */
	public function getEffectiveStatus();

	/**
	 * @return string
	 */
	public function getEffectiveEndpoint();

	/**
	 * @return string
	 */
	public function getEffectiveRawHeader();

	/**
	 * @return Header[]
	 */
	public function getEffectiveHeaders();

}
