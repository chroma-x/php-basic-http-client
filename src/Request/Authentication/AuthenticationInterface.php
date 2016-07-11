<?php

namespace Markenwerk\BasicHttpClient\Request\Authentication;

use Markenwerk\BasicHttpClient\Request\Base\CurlConfiguratorInterface;
use Markenwerk\BasicHttpClient\Request\RequestInterface;

/**
 * Interface AuthenticationInterface
 *
 * @package Markenwerk\BasicHttpClient\Request\Authentication
 */
interface AuthenticationInterface extends CurlConfiguratorInterface
{

	/**
	 * @param RequestInterface $request
	 * @return $this
	 */
	public function validate(RequestInterface $request);

}
