<?php

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
	 * @var bool
	 */
	protected $verifyHost = true;

	/**
	 * Whether to verify the peer SSL certificate
	 *
	 * @var bool
	 */
	protected $verifyPeer = true;

	/**
	 * @return bool
	 */
	public function getVerifyHost(): bool
	{
		return $this->verifyHost;
	}

	/**
	 * @param bool $verifyHost
	 */
	public function setVerifyHost(bool $verifyHost)
	{
		$this->verifyHost = $verifyHost;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getVerifyPeer(): bool
	{
		return $this->verifyPeer;
	}

	/**
	 * @param bool $verifyPeer
	 * @return $this
	 */
	public function setVerifyPeer(bool $verifyPeer)
	{
		$this->verifyPeer = $verifyPeer;
		return $this;
	}

	/**
	 * @param resource $curl
	 * @return $this
	 */
	public function configureCurl($curl)
	{
		parent::configureCurl($curl);
		// Verify host
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $this->verifyHost ? 2 : 0);
		// Verify peer
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->verifyPeer);
		return $this;
	}

}
