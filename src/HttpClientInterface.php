<?php

namespace Markenwerk\BasicHttpClient;

use Markenwerk\BasicHttpClient\Request\RequestInterface;
use Markenwerk\BasicHttpClient\Response\ResponseInterface;

/**
 * Interface HttpClientInterface
 *
 * @package Markenwerk\BasicHttpClient
 */
interface HttpClientInterface
{

	/**
	 * @return RequestInterface
	 */
	public function getRequest();

	/**
	 * @param mixed[] $queryParameters
	 * @return ResponseInterface
	 */
	public function get(array $queryParameters = array());

	/**
	 * @param mixed[] $queryParameters
	 * @return ResponseInterface
	 */
	public function head(array $queryParameters = array());

	/**
	 * @param array $postData
	 * @return ResponseInterface
	 */
	public function post(array $postData = array());

	/**
	 * @param array $putData
	 * @return ResponseInterface
	 */
	public function put(array $putData = array());

	/**
	 * @param array $patchData
	 * @return ResponseInterface
	 */
	public function patch(array $patchData = array());

	/**
	 * @param mixed[] $queryParameters
	 * @return ResponseInterface
	 */
	public function delete(array $queryParameters = array());

}
