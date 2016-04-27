<?php

namespace BasicHttpClient\Request\Transport;

/**
 * Class HttpsTransport
 *
 * @package BasicHttpClient\Request\Transport
 */
class HttpsTransport extends HttpTransport
{

	/**
	 * Whether to verify the peer SSL certificate
	 *
	 * @var bool
	 */
	protected $verifyPeer = true;

	/**
	 * @return boolean
	 */
	public function getVerifyPeer()
	{
		return $this->verifyPeer;
	}

	/**
	 * @param boolean $verifyPeer
	 * @return $this
	 */
	public function setVerifyPeer($verifyPeer)
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
		// Verify peer
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->verifyPeer);
		return $this;
	}

}
