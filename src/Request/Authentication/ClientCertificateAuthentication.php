<?php

namespace Markenwerk\BasicHttpClient\Request\Authentication;

use Markenwerk\BasicHttpClient\Exception\HttpRequestAuthenticationException;
use Markenwerk\BasicHttpClient\Request\RequestInterface;
use Markenwerk\BasicHttpClient\Request\Transport\HttpsTransport;
use Markenwerk\CommonException\IoException\FileNotFoundException;
use Markenwerk\CommonException\IoException\FileReadableException;

/**
 * Class ClientCertificateAuthentication
 *
 * @package Markenwerk\BasicHttpClient\Request\Authentication
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
	 */
	public function __construct($caCertPath, $clientCertPath, $clientCertPassword)
	{
		$this->setCaCertPath($caCertPath);
		$this->setClientCertPath($clientCertPath);
		$this->setClientCertPassword($clientCertPassword);
	}

	/**
	 * @return mixed
	 */
	public function getCaCertPath()
	{
		return $this->caCertPath;
	}

	/**
	 * @param mixed $caCertPath
	 * @return $this
	 * @throws FileNotFoundException
	 * @throws FileReadableException
	 */
	public function setCaCertPath($caCertPath)
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
	 * @return mixed
	 */
	public function getClientCertPath()
	{
		return $this->clientCertPath;
	}

	/**
	 * @param mixed $clientCertPath
	 * @return $this
	 * @throws FileNotFoundException
	 * @throws FileReadableException
	 */
	public function setClientCertPath($clientCertPath)
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
	 * @return mixed
	 */
	public function getClientCertPassword()
	{
		return $this->clientCertPassword;
	}

	/**
	 * @param mixed $clientCertPassword
	 * @return $this
	 */
	public function setClientCertPassword($clientCertPassword)
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
			throw new \InvalidArgumentException('curl argument invalid. Expected a valid resource. Got ' . $argumentType);
		}
		curl_setopt($curl, CURLOPT_CAINFO, $this->caCertPath);
		curl_setopt($curl, CURLOPT_SSLCERT, $this->clientCertPath);
		curl_setopt($curl, CURLOPT_SSLCERTPASSWD, $this->clientCertPassword);
		return $this;
	}

}
