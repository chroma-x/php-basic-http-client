<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Request\Authentication;

use ChromaX\BasicHttpClient\Exception\HttpRequestAuthenticationException;
use ChromaX\BasicHttpClient\Request\RequestInterface;
use ChromaX\BasicHttpClient\Request\Transport\HttpsTransport;
use ChromaX\CommonException\IoException\FileNotFoundException;
use ChromaX\CommonException\IoException\FileReadableException;

/**
 * Class ClientCertificateAuthentication
 *
 * @package ChromaX\BasicHttpClient\Request\Authentication
 */
class ClientCertificateAuthentication implements AuthenticationInterface
{

	private string $caCertPath;

	private string $clientCertPath;

	private string $clientCertPassword;

	public function __construct(string $caCertPath, string $clientCertPath, string $clientCertPassword)
	{
		$this->setCaCertPath($caCertPath);
		$this->setClientCertPath($clientCertPath);
		$this->setClientCertPassword($clientCertPassword);
	}

	public function getCaCertPath(): string
	{
		return $this->caCertPath;
	}

	public function setCaCertPath(string $caCertPath): self
	{
		if (!is_file($caCertPath)) {
			throw new FileNotFoundException('CA certificate file not found.');
		}
		if (!is_readable($caCertPath)) {
			throw new FileReadableException('CA certificate file not readable.');
		}
		$this->caCertPath = $caCertPath;
		return $this;
	}

	public function getClientCertPath(): string
	{
		return $this->clientCertPath;
	}

	public function setClientCertPath(string $clientCertPath): self
	{
		if (!is_file($clientCertPath)) {
			throw new FileNotFoundException('Client certificate file not found.');
		}
		if (!is_readable($clientCertPath)) {
			throw new FileReadableException('Client certificate file not readable.');
		}
		$this->clientCertPath = $clientCertPath;
		return $this;
	}

	public function getClientCertPassword(): string
	{
		return $this->clientCertPassword;
	}

	public function setClientCertPassword(string $clientCertPassword): self
	{
		$this->clientCertPassword = $clientCertPassword;
		return $this;
	}

	public function validate(RequestInterface $request): self
	{
		if (!$request->getTransport() instanceof HttpsTransport) {
			throw new HttpRequestAuthenticationException(
				'To perform a ClientCertificateAuthentication a HttpsTransport is required.'
			);
		}
		return $this;
	}

	public function configureCurl(\CurlHandle|false $curl): self
	{
		if ($curl === false) {
			throw new \TypeError('cURL is not a valid CurlHandle class.');
		}
		curl_setopt($curl, CURLOPT_CAINFO, $this->caCertPath);
		curl_setopt($curl, CURLOPT_SSLCERT, $this->clientCertPath);
		curl_setopt($curl, CURLOPT_SSLCERTPASSWD, $this->clientCertPassword);
		return $this;
	}

}
