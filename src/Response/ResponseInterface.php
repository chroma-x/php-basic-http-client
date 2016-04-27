<?php

namespace BasicHttpClient\Response;

use BasicHttpClient\Request\RequestInterface;
use BasicHttpClient\Response\Header\Header;
use BasicHttpClient\Response\Statistics\Statistics;

/**
 * Interface ResponseInterface
 *
 * @package BasicHttpClient\Response\Base
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
