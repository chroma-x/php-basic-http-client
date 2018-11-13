<?php

namespace Markenwerk\BasicHttpClient\Request\Message\Body;

/**
 * Class Body
 *
 * @package Markenwerk\BasicHttpClient\Request\Message\Body
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
	public function __construct(?string $bodyText = null)
	{
		$this->bodyText = $bodyText;
	}

	/**
	 * @return string
	 */
	public function getBodyText(): ?string
	{
		return $this->bodyText;
	}

	/**
	 * @param string $bodyText
	 * @return $this
	 */
	public function setBodyText(string $bodyText)
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
		if (!is_resource($curl)) {
			$argumentType = (is_object($curl)) ? get_class($curl) : gettype($curl);
			throw new \TypeError('curl argument invalid. Expected a valid resource. Got ' . $argumentType);
		}
		curl_setopt($curl, CURLOPT_POSTFIELDS, $this->bodyText);
		return $this;
	}

}
