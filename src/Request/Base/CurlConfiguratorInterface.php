<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Request\Base;

/**
 * Interface CurlConfiguratorInterface
 *
 * @package ChromaX\BasicHttpClient\Request\Base
 */
interface CurlConfiguratorInterface
{

	public function configureCurl(\CurlHandle|false $curl): self;

}
