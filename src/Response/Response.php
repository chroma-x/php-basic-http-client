<?php

namespace BasicHttpClient\Response;

use BasicHttpClient\Request\Base\RequestInterface;
use BasicHttpClient\Response\Base\ResponseInterface;
use BasicHttpClient\Response\Header\Header;
use BasicHttpClient\Response\Statistics\Statistics;

/**
 * Class Response
 *
 * @package BasicHttpClient\Response
 */
class Response implements ResponseInterface
{

	/**
	 * @var RequestInterface
	 */
	private $request;

	/**
	 * @var int
	 */
	private $statusCode;

	/**
	 * @var string
	 */
	private $statusText;

	/**
	 * @var Header[]
	 */
	private $headers;

	/**
	 * @var string
	 */
	private $body;

	/**
	 * @var Statistics
	 */
	private $statistics;

	/**
	 * Response constructor.
	 *
	 * @param RequestInterface $request
	 */
	public function __construct(RequestInterface $request)
	{
		$this->request = $request;
	}

	/**
	 * @param resource $curl
	 * @param string $responseBody
	 * @return $this
	 */
	public function populateFromCurlResult($curl, $responseBody)
	{
		$this->statusCode = intval(curl_getinfo($curl, CURLINFO_HTTP_CODE));
		$this->setStatistics($curl);
		$this->setResponseData($responseBody);
		return $this;
	}

	/**
	 * @return RequestInterface
	 */
	public function getRequest()
	{
		return $this->request;
	}

	/**
	 * @return int
	 */
	public function getStatusCode()
	{
		return $this->statusCode;
	}

	/**
	 * @return string
	 */
	public function getStatusText()
	{
		return $this->statusText;
	}

	/**
	 * @return Header[]
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * @return string
	 */
	public function getBody()
	{
		return $this->body;
	}

	/**
	 * @return Statistics
	 */
	public function getStatistics()
	{
		return $this->statistics;
	}

	/**
	 * @param resource $curl
	 * @return $this
	 */
	private function setStatistics($curl)
	{
		$this->statistics = new Statistics();
		$this->statistics->populateFromCurlResult($curl);
		return $this;
	}

	/**
	 * @param string $responseBody
	 * @return $this
	 */
	private function setResponseData($responseBody)
	{
		// Parse response
		$responseHeaders = array();
		$responseStatusText = null;
		$responseStatusCode = null;
		if (strpos($responseBody, "\r\n\r\n") !== false) {
			do {
				list($responseHeader, $responseBody) = explode("\r\n\r\n", $responseBody, 2);
				$responseHeaderLines = explode("\r\n", $responseHeader);
				$responseStatusText = $responseHeaderLines[0];
				$responseStatusCode = (int)substr(
					trim($responseStatusText),
					strpos($responseStatusText, ' ') + 1,
					3
				);
			} while (
				strpos($responseBody, "\r\n\r\n") !== false
				&& (
					!($responseStatusCode >= 200 && $responseStatusCode < 300)
					|| !$responseStatusCode >= 400
				)
			);
			$responseHeaders = preg_split('/\r\n/', $responseHeader, null, PREG_SPLIT_NO_EMPTY);
		}
		$this->setResponseHeader($responseHeaders);
		if (!is_null($responseStatusCode)) {
			$this->statusCode = $responseStatusCode;
		}
		if (!is_null($responseStatusText)) {
			$this->statusText = $responseStatusText;
		}
		$this->body = $responseBody;
		return $this;
	}

	/**
	 * @param string[] $responseHeaders
	 * @return $this
	 */
	private function setResponseHeader(array $responseHeaders)
	{
		foreach ($responseHeaders as $responseHeader) {
			if (strpos($responseHeader, ':') !== false) {
				$headerName = mb_substr($responseHeader, 0, strpos($responseHeader, ':'));
				$headerValue = mb_substr($responseHeader, strpos($responseHeader, ':') + 1);
				$headerValues = explode(',', $headerValue);
				$this->headers[] = new Header($headerName, $headerValues);
			}
		}
		return $this;
	}

}
