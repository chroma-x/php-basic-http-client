<?php

namespace ChromaX\BasicHttpClient\Request\Authentication;

use ChromaX\BasicHttpClient\Request\RequestInterface;

/**
 * Class BasicAuthentication
 *
 * @package ChromaX\BasicHttpClient\Request\Authentication
 */
class BasicAuthentication implements AuthenticationInterface
{

	/**
	 * @var string
	 */
	private $username;

	/**
	 * @var string
	 */
	private $password;

	/**
	 * BasicAuthentication constructor.
	 *
	 * @param string $username
	 * @param string $password
	 */
	public function __construct(string $username, string $password)
	{

		$this->username = $username;
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getUsername():string
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 * @return $this
	 */
	public function setUsername(string $username)
	{
		$this->username = $username;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPassword():string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 * @return $this
	 */
	public function setPassword(string $password)
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * @param RequestInterface $request
	 * @return $this
	 */
	public function validate(RequestInterface $request)
	{
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
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, $this->username . ':' . $this->password);
		return $this;
	}

}
