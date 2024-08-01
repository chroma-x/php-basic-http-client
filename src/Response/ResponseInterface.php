<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Response;

use ChromaX\BasicHttpClient\Request\RequestInterface;
use ChromaX\BasicHttpClient\Response\Header\Header;
use ChromaX\BasicHttpClient\Response\Statistics\Statistics;

/**
 * Interface ResponseInterface
 *
 * @package ChromaX\BasicHttpClient\Response\Base
 */
interface ResponseInterface
{
	public function __construct(RequestInterface $request);

	public function populateFromCurlResult(\CurlHandle $curl, string $responseBody): self;

	public function getRequest(): RequestInterface;

	public function getStatusCode(): ?int;

	public function getStatusText(): ?string;

	/**
	 * @return ?Header[]
	 */
	public function getHeaders(): ?array;

	public function hasHeader(string $name): bool;

	public function getHeader(string $name): ?Header;

	public function getBody(): mixed;

	public function getStatistics(): ?Statistics;

}
