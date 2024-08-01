<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Request\Transport;

/**
 * Class HttpsTransport
 *
 * @package ChromaX\BasicHttpClient\Request\Transport
 */
class HttpsTransport extends HttpTransport
{

	/**
	 * Whether to verify the peer SSL certificate
	 *
	 */
	protected bool $verifyHost = true;

	/**
	 * Whether to verify the peer SSL certificate
	 *
	 */
	protected bool $verifyPeer = true;

	/**
	 * @return bool
	 */
	public function getVerifyHost(): bool
	{
		return $this->verifyHost;
	}

	public function setVerifyHost(bool $verifyHost): self
	{
		$this->verifyHost = $verifyHost;
		return $this;
	}

	public function getVerifyPeer(): bool
	{
		return $this->verifyPeer;
	}

	public function setVerifyPeer(bool $verifyPeer): self
	{
		$this->verifyPeer = $verifyPeer;
		return $this;
	}

	public function configureCurl(\CurlHandle|false $curl): self
	{
		parent::configureCurl($curl);
		// Verify host
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $this->verifyHost ? 2 : 0);
		// Verify peer
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->verifyPeer);
		return $this;
	}

}
