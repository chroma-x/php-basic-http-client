<?php

declare(strict_types=1);

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

	public function getRequest(): RequestInterface;

	public function get(array $queryParameters = []): ResponseInterface;

	public function head(array $queryParameters = []): ResponseInterface;

	public function post(array $postData = []): ResponseInterface;

	public function put(array $putData = []): ResponseInterface;

	public function patch(array $patchData = []): ResponseInterface;

	public function delete(array $queryParameters = []): ResponseInterface;
}
