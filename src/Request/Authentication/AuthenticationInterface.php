<?php

namespace BasicHttpClient\Request\Authentication;

use BasicHttpClient\Request\Base\CurlConfiguratorInterface;
use BasicHttpClient\Request\RequestInterface;

/**
 * Interface AuthenticationInterface
 *
 * @package BasicHttpClient\Request\Authentication
 */
interface AuthenticationInterface extends CurlConfiguratorInterface
{

	/**
	 * @param RequestInterface $request
	 * @return $this
	 */
	public function validate(RequestInterface $request);

}
