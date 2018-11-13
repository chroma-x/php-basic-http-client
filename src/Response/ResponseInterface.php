<?php

namespace Markenwerk\BasicHttpClient\Response;

use Markenwerk\BasicHttpClient\Request\RequestInterface;
use Markenwerk\BasicHttpClient\Response\Header\Header;
use Markenwerk\BasicHttpClient\Response\Statistics\Statistics;

/**
 * Interface ResponseInterface
 *
 * @package Markenwerk\BasicHttpClient\Response\Base
 */
interface ResponseInterface
{

	/**
	 * Response constructor.
	 *
	 * @param RequestInterface $request
	 */
	public function __construct(RequestInterface $request);

	/**
	 * @param resource $curl
	 * @param string $responseBody
	 * @return $this
	 */
	public function populateFromCurlResult($curl, string $responseBody);

	/**
	 * @return RequestInterface
	 */
	public function getRequest(): RequestInterface;

	/**
	 * @return int
	 */
	public function getStatusCode(): ?int;

	/**
	 * @return string
	 */
	public function getStatusText(): ?string;

	/**
	 * @return Header[]
	 */
	public function getHeaders(): ?array;

	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasHeader(string $name): bool;

	/**
	 * @param string $name
	 * @return Header
	 */
	public function getHeader(string $name): ?Header;

	/**
	 * @return mixed
	 */
	public function getBody();

	/**
	 * @return Statistics
	 */
	public function getStatistics(): ?Statistics;

}
