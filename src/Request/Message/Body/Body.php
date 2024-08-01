<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Request\Message\Body;

/**
 * Class Body
 *
 * @package ChromaX\BasicHttpClient\Request\Message\Body
 */
class Body implements BodyInterface
{

	private string $bodyText;

	public function __construct(?string $bodyText = null)
	{
		$this->bodyText = $bodyText;
	}

	public function getBodyText(): ?string
	{
		return $this->bodyText;
	}

	public function setBodyText(string $bodyText): self
	{
		$this->bodyText = $bodyText;
		return $this;
	}

	public function setBodyTextFromArray(array $bodyData): self
	{
		$this->bodyText = http_build_query($bodyData);
		return $this;
	}

	public function configureCurl(\CurlHandle|false $curl): self
	{
		if ($curl === false) {
			throw new \TypeError('cURL is not a valid CurlHandle class.');
		}
		curl_setopt($curl, CURLOPT_POSTFIELDS, $this->bodyText);
		return $this;
	}

}
