<?php

namespace BasicHttpClient\Request\Base;

/**
 * Interface CurlConfiguratorInterface
 *
 * @package BasicHttpClient\Request\Base
 */
interface CurlConfiguratorInterface
{

	/**
	 * @param resource $curl
	 * @return $this
	 */
	public function configureCurl($curl);

}
