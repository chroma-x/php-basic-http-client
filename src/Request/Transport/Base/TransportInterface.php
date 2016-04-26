<?php

namespace BasicHttpClient\Request\Transport\Base;

/**
 * Interface TransportInterface
 *
 * @package BasicHttpClient\Request\Transport\Base
 */
interface TransportInterface
{

	/**
	 * @return string
	 */
	public function getHttpVersion();

	/**
	 * @param string $httpVersion
	 * @return $this
	 */
	public function setHttpVersion($httpVersion);

	/**
	 * @return int
	 */
	public function getTimeout();

	/**
	 * @param int $timeout
	 * @return $this
	 */
	public function setTimeout($timeout);

	/**
	 * @return boolean
	 */
	public function getReuseConnection();

	/**
	 * @param boolean $reuseConnection
	 * @return $this
	 */
	public function setReuseConnection($reuseConnection);

	/**
	 * @return boolean
	 */
	public function getAllowCaching();

	/**
	 * @param boolean $allowCaching
	 * @return $this
	 */
	public function setAllowCaching($allowCaching);

	/**
	 * @return boolean
	 */
	public function getFollowRedirects();

	/**
	 * @param boolean $followRedirects
	 * @return $this
	 */
	public function setFollowRedirects($followRedirects);

	/**
	 * @return int
	 */
	public function getMaxRedirects();

	/**
	 * @param int $maxRedirects
	 * @return $this
	 */
	public function setMaxRedirects($maxRedirects);

}
