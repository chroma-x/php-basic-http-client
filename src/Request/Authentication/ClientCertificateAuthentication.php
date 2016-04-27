<?php

namespace BasicHttpClient\Request\Authentication;

use CommonException\IoException\FileReadableException;

/**
 * Class ClientCertificateAuthentication
 *
 * @package BasicHttpClient\Request\Authentication
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
	 * @throws FileReadableException
	 */
	public function setCaCertPath($caCertPath)
	{
		if (!is_file($caCertPath)) {
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
	 * @throws FileReadableException
	 */
	public function setClientCertPath($clientCertPath)
	{
		if (!is_file($clientCertPath)) {
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
	 * @param resource $curl
	 * @return mixed
	 */
	public function configureCurl($curl)
	{
		curl_setopt($curl, CURLOPT_CAINFO, $this->caCertPath);
		curl_setopt($curl, CURLOPT_SSLCERT, $this->clientCertPath);
		curl_setopt($curl, CURLOPT_SSLCERTPASSWD, $this->clientCertPassword);
		return $this;
	}

}
