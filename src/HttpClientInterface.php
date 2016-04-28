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
	 * @param string[] $queryParameters
	 * @return ResponseInterface
	 */
	public function get(array $queryParameters = null);

	/**
	 * @param string[] $queryParameters
	 * @return ResponseInterface
	 */
	public function head(array $queryParameters = null);

	/**
	 * @param array $postData
	 * @return ResponseInterface
	 */
	public function post(array $postData = null);

	/**
	 * @param array $putData
	 * @return ResponseInterface
	 */
	public function put(array $putData = null);

	/**
	 * @param array $patchData
	 * @return ResponseInterface
	 */
	public function patch(array $patchData = null);

	/**
	 * @param string[] $queryParameters
	 * @return ResponseInterface
	 */
	public function delete(array $queryParameters = null);

}
