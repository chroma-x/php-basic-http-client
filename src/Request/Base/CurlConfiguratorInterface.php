<?php

namespace Markenwerk\BasicHttpClient\Request\Base;

/**
 * Interface CurlConfiguratorInterface
 *
 * @package Markenwerk\BasicHttpClient\Request\Base
 */
interface CurlConfiguratorInterface
{

	/**
	 * @param resource $curl
	 * @return $this
	 */
	public function configureCurl($curl);

}
