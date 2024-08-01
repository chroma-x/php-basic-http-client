<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Request\Authentication;

use ChromaX\BasicHttpClient\Request\RequestInterface;

/**
 * Class BasicAuthentication
 *
 * @package ChromaX\BasicHttpClient\Request\Authentication
 */
class BasicAuthentication implements AuthenticationInterface
{

	private string $username;

	private string $password;

	public function __construct(string $username, string $password)
	{
		$this->username = $username;
		$this->password = $password;
	}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function setUsername(string $username): self
	{
		$this->username = $username;
		return $this;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): self
	{
		$this->password = $password;
		return $this;
	}

	public function validate(RequestInterface $request): self
	{
		return $this;
	}

	public function configureCurl(\CurlHandle|false $curl): self
	{
		if ($curl === false) {
			throw new \TypeError('cURL is not a valid CurlHandle class.');
		}
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, $this->username . ':' . $this->password);
		return $this;
	}

}
