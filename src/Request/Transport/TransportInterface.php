<?php

namespace Markenwerk\BasicHttpClient\Request\Transport;

use Markenwerk\BasicHttpClient\Request\Base\CurlConfiguratorInterface;

/**
 * Interface TransportInterface
 *
 * @package Markenwerk\BasicHttpClient\Request\Transport
 */
interface TransportInterface extends CurlConfiguratorInterface
{

	/**
	 * @return int
	 */
	public function getHttpVersion(): int;

	/**
	 * @param int $httpVersion
	 * @return $this
	 */
	public function setHttpVersion(int $httpVersion);

	/**
	 * @return int
	 */
	public function getTimeout(): int;

	/**
	 * @param int $timeout
	 * @return $this
	 */
	public function setTimeout(int $timeout);

	/**
	 * @return bool
	 */
	public function getReuseConnection(): bool;

	/**
	 * @param bool $reuseConnection
	 * @return $this
	 */
	public function setReuseConnection(bool $reuseConnection);

	/**
	 * @return bool
	 */
	public function getAllowCaching(): bool;

	/**
	 * @param bool $allowCaching
	 * @return $this
	 */
	public function setAllowCaching(bool $allowCaching);

	/**
	 * @return bool
	 */
	public function getFollowRedirects(): bool;

	/**
	 * @param bool $followRedirects
	 * @return $this
	 */
	public function setFollowRedirects(bool $followRedirects);

	/**
	 * @return int
	 */
	public function getMaxRedirects(): int;

	/**
	 * @param int $maxRedirects
	 * @return $this
	 */
	public function setMaxRedirects(int $maxRedirects);

}
