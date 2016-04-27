<?php

namespace BasicHttpClient\Request\Message\Body;

/**
 * Class Body
 *
 * @package BasicHttpClient\Request\Message\Body
 */
class Body implements BodyInterface
{

	/**
	 * @var string
	 */
	private $bodyText;

	/**
	 * Body constructor.
	 *
	 * @param string $bodyText
	 */
	public function __construct($bodyText = null)
	{
		$this->bodyText = $bodyText;
	}

	/**
	 * @return string
	 */
	public function getBodyText()
	{
		return $this->bodyText;
	}

	/**
	 * @param string $bodyText
	 * @return $this
	 */
	public function setBodyText($bodyText)
	{
		$this->bodyText = $bodyText;
		return $this;
	}

	/**
	 * @param array $bodyData
	 * @return $this
	 */
	public function setBodyTextFromArray(array $bodyData)
	{
		$this->bodyText = http_build_query($bodyData);
		return $this;
	}

	/**
	 * @param resource $curl
	 * @return $this
	 */
	public function configureCurl($curl)
	{
		curl_setopt($curl, CURLOPT_POSTFIELDS, $this->bodyText);
		return $this;
	}

}
