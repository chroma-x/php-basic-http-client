<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Response;

use ChromaX\BasicHttpClient\Request\RequestInterface;
use ChromaX\BasicHttpClient\Response\Header\Header;
use ChromaX\BasicHttpClient\Response\Statistics\Statistics;
use ChromaX\BasicHttpClient\Util\HeaderNameUtil;

/**
 * Class AbstractResponse
 *
 * @package ChromaX\BasicHttpClient\Response
 */
abstract class AbstractResponse implements ResponseInterface
{

	private RequestInterface $request;

	private int $statusCode;

	private string $statusText;

	/**
	 * @var Header[]
	 */
	private ?array $headers = null;

	private mixed $body;

	private ?Statistics $statistics = null;

	public function __construct(RequestInterface $request)
	{
		$this->request = $request;
	}

	public function populateFromCurlResult(\CurlHandle|false $curl, string $responseBody): self
	{
		if ($curl === false) {
			throw new \TypeError('cURL is not a valid CurlHandle class.');
		}
		$this->statusCode = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$this->setStatistics($curl);
		$this->setResponseData($responseBody);
		return $this;
	}

	public function getRequest(): RequestInterface
	{
		return $this->request;
	}

	public function getStatusCode(): ?int
	{
		return $this->statusCode;
	}

	public function getStatusText(): ?string
	{
		return $this->statusText;
	}

	/**
	 * @return ?Header[]
	 */
	public function getHeaders(): ?array
	{
		return $this->headers;
	}

	public function hasHeader(string $name): bool
	{
		return !is_null($this->getHeader($name));
	}

	public function getHeader(string $name): ?Header
	{
		$headers = $this->getHeaders();
		$headerNameUtil = new HeaderNameUtil();
		$name = $headerNameUtil->normalizeHeaderName($name);
		foreach ($headers as $header) {
			if ($header->getNormalizedName() === $name) {
				return $header;
			}
		}
		return null;
	}

	public function getBody(): mixed
	{
		return $this->body;
	}

	public function getStatistics(): ?Statistics
	{
		return $this->statistics;
	}

	protected function setStatistics(\CurlHandle $curl): self
	{
		$this->statistics = new Statistics();
		$this->statistics->populateFromCurlResult($curl);
		return $this;
	}

	protected function setResponseData(string $responseBody): self
	{
		// Parse response
		$responseHeaders = array();
		$responseStatusText = null;
		$responseStatusCode = null;
		if (strpos($responseBody, "\r\n\r\n") !== false) {
			/** @noinspection SuspiciousBinaryOperationInspection */
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
			$responseHeaders = preg_split('/\r\n/', $responseHeader, 0, PREG_SPLIT_NO_EMPTY);
		}
		$this->setResponseHeader($responseHeaders);
		if (!is_null($responseStatusCode)) {
			$this->setStatusCode($responseStatusCode);
		}
		if (!is_null($responseStatusText)) {
			$this->setStatusText($responseStatusText);
		}
		$this->setBody($responseBody);
		return $this;
	}

	protected function setStatusCode(int $statusCode): self
	{
		$this->statusCode = $statusCode;
		return $this;
	}

	protected function setStatusText(string $statusText): self
	{
		$this->statusText = $statusText;
		return $this;
	}

	protected function setBody(mixed $body): self
	{
		$this->body = $body;
		return $this;
	}

	/**
	 * @param string[] $responseHeaders
	 */
	protected function setResponseHeader(array $responseHeaders): self
	{
		foreach ($responseHeaders as $responseHeader) {
			if (str_contains($responseHeader, ':')) {
				$headerName = mb_substr($responseHeader, 0, strpos($responseHeader, ':'));
				$headerValue = mb_substr($responseHeader, strpos($responseHeader, ':') + 1);
				$headerValues = explode(',', $headerValue);
				$this->headers[] = new Header($headerName, $headerValues);
			}
		}
		return $this;
	}

}
