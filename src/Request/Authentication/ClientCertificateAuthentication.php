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
	private $caCertificatePath;
	/**
	 * @var string
	 */
	private $clientCertificatePath;
	/**
	 * @var string
	 */
	private $clientCertificatePassword;

	/**
	 * ClientCertificateAuthentication constructor.
	 *
	 * @param string $caCertificatePath
	 * @param string $clientCertificatePath
	 * @param string $clientCertificatePassword
	 */
	public function __construct($caCertificatePath, $clientCertificatePath, $clientCertificatePassword)
	{
		$this->setCaCertificatePath($caCertificatePath);
		$this->setClientCertificatePath($clientCertificatePath);
		$this->setClientCertificatePassword($clientCertificatePassword);
	}

	/**
	 * @return mixed
	 */
	public function getCaCertificatePath()
	{
		return $this->caCertificatePath;
	}

	/**
	 * @param mixed $caCertificatePath
	 * @return $this
	 * @throws FileReadableException
	 */
	public function setCaCertificatePath($caCertificatePath)
	{
		if (!is_file($caCertificatePath)) {
			throw new FileReadableException('CA certificate file not readable.');
		}
		$this->caCertificatePath = $caCertificatePath;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getClientCertificatePath()
	{
		return $this->clientCertificatePath;
	}

	/**
	 * @param mixed $clientCertificatePath
	 * @return $this
	 * @throws FileReadableException
	 */
	public function setClientCertificatePath($clientCertificatePath)
	{
		if (!is_file($clientCertificatePath)) {
			throw new FileReadableException('Client certificate file not readable.');
		}
		$this->clientCertificatePath = $clientCertificatePath;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getClientCertificatePassword()
	{
		return $this->clientCertificatePassword;
	}

	/**
	 * @param mixed $clientCertificatePassword
	 * @return $this
	 */
	public function setClientCertificatePassword($clientCertificatePassword)
	{
		$this->clientCertificatePassword = $clientCertificatePassword;
		return $this;
	}

	/**
	 * @param resource $curl
	 * @return mixed
	 */
	public function configureCurl($curl)
	{
		curl_setopt($curl, CURLOPT_CAINFO, $this->caCertificatePath);
		curl_setopt($curl, CURLOPT_SSLCERT, $this->clientCertificatePath);
		curl_setopt($curl, CURLOPT_SSLCERTPASSWD, $this->clientCertificatePassword);
		return $this;
	}

}
