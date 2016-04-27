<?php

namespace BasicHttpClient;

use BasicHttpClient\Request\RequestInterface;
use BasicHttpClient\Response\ResponseInterface;

/**
 * Interface HttpClientInterface
 *
 * @package BasicHttpClient
 */
interface HttpClientInterface
{

	/**
	 * @return RequestInterface
	 */
	public function getRequest();

	/**
	 * @return ResponseInterface
	 */
	public function get();

	/**
	 * @return ResponseInterface
	 */
	public function head();

	/**
	 * @param array $postData
	 * @return ResponseInterface
	 */
	public function post(array $postData);

	/**
	 * @param array $putData
	 * @return ResponseInterface
	 */
	public function put(array $putData);

	/**
	 * @param array $patchData
	 * @return ResponseInterface
	 */
	public function patch(array $patchData);

	/**
	 * @return ResponseInterface
	 */
	public function delete();

}
