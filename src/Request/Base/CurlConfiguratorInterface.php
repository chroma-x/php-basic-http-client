<?php

namespace ChromaX\BasicHttpClient\Request\Base;

/**
 * Interface CurlConfiguratorInterface
 *
 * @package ChromaX\BasicHttpClient\Request\Base
 */
interface CurlConfiguratorInterface
{

	/**
	 * @param resource $curl
	 * @return $this
	 */
	public function configureCurl($curl);

}
