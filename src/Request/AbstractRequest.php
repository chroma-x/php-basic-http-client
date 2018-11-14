<?php

namespace Markenwerk\BasicHttpClient\Request;

use Markenwerk\BasicHttpClient\Exception\HttpRequestException;
use Markenwerk\BasicHttpClient\Request\Authentication\AuthenticationInterface;
use Markenwerk\BasicHttpClient\Request\Message\MessageInterface;
use Markenwerk\BasicHttpClient\Request\Message\Header\Header;
use Markenwerk\BasicHttpClient\Request\Transport\TransportInterface;
use Markenwerk\BasicHttpClient\Request\Transport\HttpsTransport;
use Markenwerk\BasicHttpClient\Request\Transport\HttpTransport;
use Markenwerk\BasicHttpClient\Response\ResponseInterface;
use Markenwerk\CommonException\NetworkException\Base\NetworkException;
use Markenwerk\CommonException\NetworkException\ConnectionTimeoutException;
use Markenwerk\CommonException\NetworkException\CurlException;
use Markenwerk\UrlUtil\UrlInterface;

/**
 * Class Request
 *
 * @package Markenwerk\BasicHttpClient\Request
 */
abstract class AbstractRequest implements RequestInterface
{

	/**
	 * @var string
	 */
	private $userAgent = 'PHP Basic HTTP Client 1.0';

	/**
	 * @var string
	 */
	private $method = self::REQUEST_METHOD_GET;

	/**
	 * @var UrlInterface
	 */
	private $url;

	/**
	 * @var TransportInterface
	 */
	private $transport;

	/**
	 * @var AuthenticationInterface[]
	 */
	private $authentications = array();

	/**
	 * @var MessageInterface
	 */
	private $message;

	/**
	 * @var ResponseInterface
	 */
	private $response;

	/**
	 * @var string
	 */
	private $effectiveStatus;

	/**
	 * @var string
	 */
	private $effectiveEndpoint;

	/**
	 * @var string
	 */
	private $effectiveRawHeader;

	/**
	 * @var Header[]
	 */
	private $effectiveHeaders = array();

	/**
	 * Request constructor.
	 */
	public function __construct()
	{
		$this->transport = new HttpTransport();
	}

	/**
	 * @return string
	 */
	public function getUserAgent(): string
	{
		return $this->userAgent;
	}

	/**
	 * @param string $userAgent
	 * @return $this
	 */
	public function setUserAgent(string $userAgent)
	{
		$this->userAgent = $userAgent;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMethod(): string
	{
		return $this->method;
	}

	/**
	 * @param string $method
	 * @return $this
	 */
	public function setMethod(string $method)
	{
		$this->method = $method;
		return $this;
	}

	/**
	 * @return UrlInterface
	 */
	public function getUrl(): ?UrlInterface
	{
		return $this->url;
	}

	/**
	 * @param UrlInterface $url
	 * @return $this
	 */
	public function setUrl(UrlInterface $url)
	{
		$this->url = $url;
		return $this;
	}

	/**
	 * @return TransportInterface
	 */
	public function getTransport(): ?TransportInterface
	{
		return $this->transport;
	}

	/**
	 * @param TransportInterface $transport
	 * @return $this
	 */
	public function setTransport(TransportInterface $transport)
	{
		$this->transport = $transport;
		return $this;
	}

	/**
	 * @return MessageInterface
	 */
	public function getMessage(): ?MessageInterface
	{
		return $this->message;
	}

	/**
	 * @param MessageInterface $message
	 * @return $this
	 */
	public function setMessage(MessageInterface $message)
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
	 * @return $this
	 */
	public function setAuthentications(array $authentications)
	{
		$this->authentications = $authentications;
		return $this;
	}

	/**
	 * @param AuthenticationInterface $authentication
	 * @return $this
	 */
	public function addAuthentication(AuthenticationInterface $authentication)
	{
		if (!$this->hasAuthentication($authentication)) {
			$this->authentications[] = $authentication;
		}
		return $this;
	}

	/**
	 * @param AuthenticationInterface $authentication
	 * @return $this
	 */
	public function removeAuthentication(AuthenticationInterface $authentication)
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

	/**
	 * @param AuthenticationInterface $authentication
	 * @return bool
	 */
	public function hasAuthentication(AuthenticationInterface $authentication): bool
	{
		foreach ($this->authentications as $existingAuth) {
			if ($authentication === $existingAuth) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @return bool
	 */
	public function hasAuthentications(): bool
	{
		return count($this->authentications) > 0;
	}

	/**
	 * @return int
	 */
	public function countAuthentications(): int
	{
		return count($this->authentications);
	}

	/**
	 * @param resource $curl
	 * @return $this
	 * @throws HttpRequestException
	 */
	public function configureCurl($curl)
	{
		if (!is_resource($curl)) {
			$argumentType = (is_object($curl)) ? get_class($curl) : gettype($curl);
			throw new \TypeError('curl argument invalid. Expected a valid resource. Got ' . $argumentType);
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
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->getMethod());
		}
		return $this;
	}

	/**
	 * @return $this
	 * @throws ConnectionTimeoutException
	 * @throws NetworkException
	 * @throws \Exception
	 */
	public function perform()
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

	/**
	 * @return ResponseInterface
	 */
	abstract protected function buildResponse(): ResponseInterface;

	/**
	 * @return ResponseInterface
	 */
	public function getResponse(): ?ResponseInterface
	{
		return $this->response;
	}

	/**
	 * @return string
	 */
	public function getEffectiveStatus(): ?string
	{
		return $this->effectiveStatus;
	}

	/**
	 * @return string
	 */
	public function getEffectiveEndpoint(): ?string
	{
		return $this->effectiveEndpoint;
	}

	/**
	 * @return string
	 */
	public function getEffectiveRawHeader(): ?string
	{
		return $this->effectiveRawHeader;
	}

	/**
	 * @return Header[]
	 */
	public function getEffectiveHeaders(): ?array
	{
		return $this->effectiveHeaders;
	}

	/**
	 * @return string
	 */
	protected function calculateEndpoint(): ?string
	{
		$url = $this->getUrl();
		if ($url === null) {
			return null;
		}
		return $url->buildUrl();
	}

	/**
	 * @throws \Exception
	 * @return void
	 */
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

	/**
	 * @param resource $curl
	 * @return $this
	 */
	private function setEffectiveProperties($curl)
	{
		$this->effectiveEndpoint = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
		$this->effectiveRawHeader = curl_getinfo($curl, CURLINFO_HEADER_OUT);
		// Build effective request headers
		$requestHeaders = preg_split(
			'/\r\n/',
			$this->effectiveRawHeader,
			null,
			PREG_SPLIT_NO_EMPTY
		);
		foreach ($requestHeaders as $requestHeader) {
			if (strpos($requestHeader, ':') !== false) {
				$headerName = mb_substr($requestHeader, 0, strpos($requestHeader, ':'));
				$headerValue = mb_substr($requestHeader, strpos($requestHeader, ':') + 1);
				$headerValues = explode(',', $headerValue);
				$this->effectiveHeaders[] = new Header($headerName, $headerValues);
			}
			if (strpos($requestHeader, ':') === false) {
				$this->effectiveStatus = $requestHeader;
			}
		}
		return $this;
	}

}
