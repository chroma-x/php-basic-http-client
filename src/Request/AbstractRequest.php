<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Request;

use ChromaX\BasicHttpClient\Exception\HttpRequestException;
use ChromaX\BasicHttpClient\Request\Authentication\AuthenticationInterface;
use ChromaX\BasicHttpClient\Request\Message\MessageInterface;
use ChromaX\BasicHttpClient\Request\Message\Header\Header;
use ChromaX\BasicHttpClient\Request\Transport\TransportInterface;
use ChromaX\BasicHttpClient\Request\Transport\HttpsTransport;
use ChromaX\BasicHttpClient\Request\Transport\HttpTransport;
use ChromaX\BasicHttpClient\Response\ResponseInterface;
use ChromaX\CommonException\NetworkException\ConnectionTimeoutException;
use ChromaX\CommonException\NetworkException\CurlException;
use ChromaX\UrlUtil\UrlInterface;

/**
 * Class Request
 *
 * @package ChromaX\BasicHttpClient\Request
 */
abstract class AbstractRequest implements RequestInterface
{

	private string $userAgent = 'PHP Basic HTTP Client 1.0';

	private string $method = self::REQUEST_METHOD_GET;

	private UrlInterface $url;

	private TransportInterface $transport;

	/**
	 * @var AuthenticationInterface[]
	 */
	private array $authentications = [];

	private MessageInterface $message;

	private ?ResponseInterface $response = null;

	private string $effectiveStatus;

	private string $effectiveEndpoint;

	private string $effectiveRawHeader;

	/**
	 * @var Header[]
	 */
	private array $effectiveHeaders = [];

	/**
	 * Request constructor.
	 */
	public function __construct()
	{
		$this->transport = new HttpTransport();
	}

	public function getUserAgent(): string
	{
		return $this->userAgent;
	}

	public function setUserAgent(string $userAgent): self
	{
		$this->userAgent = $userAgent;
		return $this;
	}

	public function getMethod(): string
	{
		return $this->method;
	}

	public function setMethod(string $method): self
	{
		$this->method = $method;
		return $this;
	}

	public function getUrl(): ?UrlInterface
	{
		return $this->url;
	}

	public function setUrl(UrlInterface $url): self
	{
		$this->url = $url;
		return $this;
	}

	public function getTransport(): ?TransportInterface
	{
		return $this->transport;
	}

	public function setTransport(TransportInterface $transport): self
	{
		$this->transport = $transport;
		return $this;
	}

	public function getMessage(): ?MessageInterface
	{
		return $this->message;
	}

	public function setMessage(MessageInterface $message): self
	{
		$this->message = $message;
		return $this;
	}

	/**
	 * @return AuthenticationInterface[]
	 */
	public function getAuthentications(): array
	{
		return $this->authentications;
	}

	/**
	 * @param AuthenticationInterface[] $authentications
	 */
	public function setAuthentications(array $authentications): self
	{
		$this->authentications = $authentications;
		return $this;
	}

	public function addAuthentication(AuthenticationInterface $authentication): self
	{
		if (!$this->hasAuthentication($authentication)) {
			$this->authentications[] = $authentication;
		}
		return $this;
	}

	public function removeAuthentication(AuthenticationInterface $authentication): self
	{
		$authenticationCount = count($this->authentications);
		for ($i = 0; $i < $authenticationCount; $i++) {
			if ($this->authentications[$i] === $authentication) {
				unset($this->authentications[$i]);
				$this->authentications = array_values($this->authentications);
				return $this;
			}
		}
		return $this;
	}

	public function hasAuthentication(AuthenticationInterface $authentication): bool
	{
		if (in_array($authentication, $this->authentications, true)) {
			return true;
		}
		return false;
	}

	public function hasAuthentications(): bool
	{
		return count($this->authentications) > 0;
	}

	public function countAuthentications(): int
	{
		return count($this->authentications);
	}

	public function configureCurl(\CurlHandle|false $curl): self
	{
		if ($curl === false) {
			throw new \TypeError('cURL is not a valid CurlHandle class.');
		}
		$url = $this->getUrl();
		if ($url === null) {
			throw new HttpRequestException('No URL available');
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);
		curl_setopt($curl, CURLOPT_USERAGENT, $this->getUserAgent());
		curl_setopt($curl, CURLOPT_URL, $this->calculateEndpoint());
		if ($url->hasPort()) {
			curl_setopt($curl, CURLOPT_PORT, $url->getPort());
		}
		// Request method
		curl_setopt($curl, CURLOPT_HTTPGET, true);
		if ($this->getMethod() !== self::REQUEST_METHOD_GET) {
			curl_setopt($curl, CURLOPT_HTTPGET, false);
		}
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->getMethod());
		return $this;
	}

	public function perform(): self
	{
		// Reset former result
		$this->response = null;
		// Perform hook
		$this->prePerform();
		// Curl basic setup
		$curl = curl_init();
		$this->configureCurl($curl);
		$transport = $this->getTransport();
		if ($transport === null) {
			throw new HttpRequestException('No Transport available');
		}
		$transport->configureCurl($curl);
		$message = $this->getMessage();
		if ($message === null) {
			throw new HttpRequestException('No Message available');
		}
		$message->configureCurl($curl);
		$authenticationCount = count($this->authentications);
		for ($i = 0; $i < $authenticationCount; $i++) {
			$this->authentications[$i]
				->validate($this)
				->configureCurl($curl);
		}
		// Execute request
		$responseBody = curl_exec($curl);
		$curlErrorCode = curl_errno($curl);
		$curlErrorMessage = curl_error($curl);
		if ($curlErrorCode === CURLE_OK) {
			$this->response = $this->buildResponse();
			$this->response->populateFromCurlResult($curl, $responseBody);
			$this->setEffectiveProperties($curl);
			return $this;
		}
		curl_close($curl);
		if ($curlErrorCode === CURLE_OPERATION_TIMEOUTED) {
			throw new ConnectionTimeoutException('The request timed out with message: ' . $curlErrorMessage);
		}
		throw new CurlException('The request failed with message: ' . $curlErrorMessage);
	}

	abstract protected function buildResponse(): ResponseInterface;

	public function getResponse(): ?ResponseInterface
	{
		return $this->response;
	}

	public function getEffectiveStatus(): ?string
	{
		return $this->effectiveStatus;
	}

	public function getEffectiveEndpoint(): ?string
	{
		return $this->effectiveEndpoint;
	}

	public function getEffectiveRawHeader(): ?string
	{
		return $this->effectiveRawHeader;
	}

	/**
	 * @return ?Header[]
	 */
	public function getEffectiveHeaders(): ?array
	{
		return $this->effectiveHeaders;
	}

	protected function calculateEndpoint(): ?string
	{
		$url = $this->getUrl();
		return $url?->buildUrl();
	}

	protected function prePerform(): void
	{
		$url = $this->getUrl();
		if ($url === null) {
			throw new HttpRequestException('No URL available');
		}
		if (
			mb_strtoupper($url->getScheme()) === 'HTTPS'
			&& !$this->getTransport() instanceof HttpsTransport
		) {
			throw new HttpRequestException('Transport misconfiguration. Use HttpsTransport for HTTPS requests.');
		}
	}

	private function setEffectiveProperties(\CurlHandle $curl): self
	{
		$this->effectiveEndpoint = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
		$this->effectiveRawHeader = curl_getinfo($curl, CURLINFO_HEADER_OUT);
		// Build effective request headers
		$requestHeaders = preg_split(
			'/\r\n/',
			$this->effectiveRawHeader,
			0,
			PREG_SPLIT_NO_EMPTY
		);
		foreach ($requestHeaders as $requestHeader) {
			if (str_contains($requestHeader, ':')) {
				$headerName = mb_substr($requestHeader, 0, strpos($requestHeader, ':'));
				$headerValue = mb_substr($requestHeader, strpos($requestHeader, ':') + 1);
				$headerValues = explode(',', $headerValue);
				$this->effectiveHeaders[] = new Header($headerName, $headerValues);
			}
			if (!str_contains($requestHeader, ':')) {
				$this->effectiveStatus = $requestHeader;
			}
		}
		return $this;
	}

}
