<?php

namespace BasicHttpClient\Request\Base;

use BasicHttpClient\Request\Authentication\Base\AuthenticationInterface;
use BasicHttpClient\Request\Message\Base\MessageInterface;
use BasicHttpClient\Request\Message\Header\Header;
use BasicHttpClient\Request\Transport\Base\TransportInterface;
use BasicHttpClient\Response\Base\ResponseInterface;

/**
 * Class Request
 *
 * @package BasicHttpClient\Request
 */
interface RequestInterface extends CurlConfiguratorInterface
{

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
	public function getEndpoint();

	/**
	 * @param string $endpoint
	 * @return $this
	 */
	public function setEndpoint($endpoint);

	/**
	 * @return int
	 */
	public function getPort();

	/**
	 * @return bool
	 */
	public function hasPort();

	/**
	 * @param int $port
	 * @return $this
	 */
	public function setPort($port);

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
	 * @return Header[]
	 */
	public function getEffectiveHeaders();

}
