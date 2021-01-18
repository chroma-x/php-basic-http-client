<?php

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

	/**
	 * @var string
	 */
	private $caCertPath;

	/**
	 * @var string
	 */
	private $clientCertPath;

	/**
	 * @var string
	 */
	private $clientCertPassword;

	/**
	 * ClientCertificateAuthentication constructor.
	 *
	 * @param string $caCertPath
	 * @param string $clientCertPath
	 * @param string $clientCertPassword
	 * @throws FileNotFoundException
	 * @throws FileReadableException
	 */
	public function __construct(string $caCertPath, string $clientCertPath, string $clientCertPassword)
	{
		$this->setCaCertPath($caCertPath);
		$this->setClientCertPath($clientCertPath);
		$this->setClientCertPassword($clientCertPassword);
	}

	/**
	 * @return string
	 */
	public function getCaCertPath():string
	{
		return $this->caCertPath;
	}

	/**
	 * @param string $caCertPath
	 * @return $this
	 * @throws FileNotFoundException
	 * @throws FileReadableException
	 */
	public function setCaCertPath(string $caCertPath)
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

	/**
	 * @return string
	 */
	public function getClientCertPath():string
	{
		return $this->clientCertPath;
	}

	/**
	 * @param string $clientCertPath
	 * @return $this
	 * @throws FileNotFoundException
	 * @throws FileReadableException
	 */
	public function setClientCertPath(string $clientCertPath)
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

	/**
	 * @return string
	 */
	public function getClientCertPassword():string
	{
		return $this->clientCertPassword;
	}

	/**
	 * @param string $clientCertPassword
	 * @return $this
	 */
	public function setClientCertPassword(string $clientCertPassword)
	{
		$this->clientCertPassword = $clientCertPassword;
		return $this;
	}

	/**
	 * @param RequestInterface $request
	 * @return $this
	 * @throws HttpRequestAuthenticationException
	 */
	public function validate(RequestInterface $request)
	{
		if (!$request->getTransport() instanceof HttpsTransport) {
			throw new HttpRequestAuthenticationException(
				'To perform a ClientCertificateAuthentication a HttpsTransport is required.'
			);
		}
		return $this;
	}

	/**
	 * @param resource $curl
	 * @return mixed
	 */
	public function configureCurl($curl)
	{
		if (!is_resource($curl)) {
			$argumentType = (is_object($curl)) ? get_class($curl) : gettype($curl);
			throw new \TypeError('curl argument invalid. Expected a valid resource. Got ' . $argumentType);
		}
		curl_setopt($curl, CURLOPT_CAINFO, $this->caCertPath);
		curl_setopt($curl, CURLOPT_SSLCERT, $this->clientCertPath);
		curl_setopt($curl, CURLOPT_SSLCERTPASSWD, $this->clientCertPassword);
		return $this;
	}

}
