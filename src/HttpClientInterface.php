<?php

namespace ChromaX\BasicHttpClient;

use ChromaX\BasicHttpClient\Request\RequestInterface;
use ChromaX\BasicHttpClient\Response\ResponseInterface;

/**
 * Interface HttpClientInterface
 *
 * @package ChromaX\BasicHttpClient
 */
interface HttpClientInterface
{

	/**
	 * @return RequestInterface
	 */
	public function getRequest(): RequestInterface;

	/**
	 * @param mixed[] $queryParameters
	 * @return ResponseInterface
	 */
	public function get(array $queryParameters = array()): ResponseInterface;

	/**
	 * @param mixed[] $queryParameters
	 * @return ResponseInterface
	 */
	public function head(array $queryParameters = array()): ResponseInterface;

	/**
	 * @param array $postData
	 * @return ResponseInterface
	 */
	public function post(array $postData = array()): ResponseInterface;

	/**
	 * @param array $putData
	 * @return ResponseInterface
	 */
	public function put(array $putData = array()): ResponseInterface;

	/**
	 * @param array $patchData
	 * @return ResponseInterface
	 */
	public function patch(array $patchData = array()): ResponseInterface;

	/**
	 * @param mixed[] $queryParameters
	 * @return ResponseInterface
	 */
	public function delete(array $queryParameters = array()): ResponseInterface;

}
