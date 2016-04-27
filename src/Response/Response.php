<?php

namespace BasicHttpClient\Response;

use BasicHttpClient\Request\Message\Header\Header as RequestHeader;
use BasicHttpClient\Response\Header\Header;
use BasicHttpClient\Response\Statistics\Statistics;
use BasicHttpClient\Response\Statistics\StatisticsBuilder;

/**
 * Class Response
 *
 * @package BasicHttpClient\Response
 */
class Response
{

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
	 * @var int
	 */
	private $redirectCount;

	/**
	 * @var float
	 */
	private $redirectTime;

	/**
	 * @var string
	 */
	private $redirectEndpoint;

	/**
	 * @var Statistics
	 */
	private $statistics;

	/**
	 * @var string
	 */
	private $effectiveRequestStatus;

	/**
	 * @var string
	 */
	private $effectiveRequestEndpoint;

	/**
	 * @var RequestHeader[]
	 */
	private $effectiveRequestHeaders = array();

	/**
	 * @param resource $curl
	 * @param string $responseBody
	 * @return $this
	 */
	public function populateFromCurlResult($curl, $responseBody)
	{
		$this->statusCode = intval(curl_getinfo($curl, CURLINFO_HTTP_CODE));
		$this->effectiveRequestEndpoint = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
		$this->redirectCount = curl_getinfo($curl, CURLINFO_REDIRECT_COUNT);
		$this->redirectTime = curl_getinfo($curl, CURLINFO_REDIRECT_TIME);
		$this->redirectEndpoint = curl_getinfo($curl, CURLINFO_REDIRECT_URL);
		$this->setStatistics($curl);
		$this->setEffectiveRequestHeaders($curl);
		$this->setResponseData($responseBody);
		return $this;
	}

	/**
	 * @param resource $curl
	 * @return $this
	 */
	private function setStatistics($curl)
	{
		$statisticsBuilder = new StatisticsBuilder();
		$this->statistics = $statisticsBuilder
			->setTotalTime(curl_getinfo($curl, CURLINFO_TOTAL_TIME))
			->setHostLookupTime(curl_getinfo($curl, CURLINFO_NAMELOOKUP_TIME))
			->setConnectionEstablishTime(curl_getinfo($curl, CURLINFO_CONNECT_TIME))
			->setPreTransferTime(curl_getinfo($curl, CURLINFO_PRETRANSFER_TIME))
			->setStartTransferTime(curl_getinfo($curl, CURLINFO_STARTTRANSFER_TIME))
			->buildStatistics();
		return $this;
	}

	/**
	 * @param resource $curl
	 * @return $this
	 */
	private function setEffectiveRequestHeaders($curl)
	{
		// Build effective request headers
		$effectiveRequestHeaders = preg_split(
			'/\r\n/',
			curl_getinfo($curl, CURLINFO_HEADER_OUT),
			null,
			PREG_SPLIT_NO_EMPTY
		);
		foreach ($effectiveRequestHeaders as $effectiveRequestHeader) {
			if (strpos($effectiveRequestHeader, ':') !== false) {
				$headerName = mb_substr($effectiveRequestHeader, 0, strpos($effectiveRequestHeader, ':'));
				$headerValue = mb_substr($effectiveRequestHeader, strpos($effectiveRequestHeader, ':') + 1);
				$headerValues = explode(',', $headerValue);
				$this->effectiveRequestHeaders[] = new RequestHeader($headerName, $headerValues);
			} else {
				$this->effectiveRequestStatus = $effectiveRequestHeader;
			}
		}
		return $this;
	}

	/**
	 * @param string $responseBody
	 * @return $this
	 */
	private function setResponseData($responseBody)
	{
		// Parse response
		$parsedResponseHeaders = array();
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
			$parsedResponseHeaders = preg_split('/\r\n/', $responseHeader, null, PREG_SPLIT_NO_EMPTY);
		}
		foreach ($parsedResponseHeaders as $parsedResponseHeader) {
			if (strpos($parsedResponseHeader, ':') !== false) {
				$headerName = mb_substr($parsedResponseHeader, 0, strpos($parsedResponseHeader, ':'));
				$headerValue = mb_substr($parsedResponseHeader, strpos($parsedResponseHeader, ':') + 1);
				$headerValues = explode(',', $headerValue);
				$this->headers[] = new Header($headerName, $headerValues);
			}
		}
		if (!is_null($responseStatusCode)) {
			$this->statusCode = $responseStatusCode;
		}
		if (!is_null($responseStatusText)) {
			$this->statusText = $responseStatusText;
		}
		$this->body = $responseBody;
		return $this;
	}
}
