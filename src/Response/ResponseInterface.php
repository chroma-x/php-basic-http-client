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
	public function populateFromCurlResult($curl, $responseBody);

	/**
	 * @return RequestInterface
	 */
	public function getRequest();

	/**
	 * @return int
	 */
	public function getStatusCode();

	/**
	 * @return string
	 */
	public function getStatusText();

	/**
	 * @return Header[]
	 */
	public function getHeaders();

	/**
	 * @return string
	 */
	public function getBody();

	/**
	 * @return Statistics
	 */
	public function getStatistics();

}
