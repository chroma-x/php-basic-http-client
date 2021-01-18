<?php

namespace ChromaX\BasicHttpClient\Request\Authentication;

use ChromaX\BasicHttpClient\Request\Base\CurlConfiguratorInterface;
use ChromaX\BasicHttpClient\Request\RequestInterface;

/**
 * Interface AuthenticationInterface
 *
 * @package ChromaX\BasicHttpClient\Request\Authentication
 */
interface AuthenticationInterface extends CurlConfiguratorInterface
{

	/**
	 * @param RequestInterface $request
	 * @return $this
	 */
	public function validate(RequestInterface $request);

}
