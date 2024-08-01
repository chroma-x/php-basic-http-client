<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Request\Transport;

use ChromaX\BasicHttpClient\Request\Base\CurlConfiguratorInterface;

/**
 * Interface TransportInterface
 *
 * @package ChromaX\BasicHttpClient\Request\Transport
 */
interface TransportInterface extends CurlConfiguratorInterface
{

	public function getHttpVersion(): int;

	public function setHttpVersion(int $httpVersion): self;

	public function getTimeout(): int;

	public function setTimeout(int $timeout): self;

	public function getReuseConnection(): bool;

	public function setReuseConnection(bool $reuseConnection): self;

	public function getAllowCaching(): bool;

	public function setAllowCaching(bool $allowCaching): self;

	public function getFollowRedirects(): bool;

	public function setFollowRedirects(bool $followRedirects): self;

	public function getMaxRedirects(): int;

	public function setMaxRedirects(int $maxRedirects): self;

}
