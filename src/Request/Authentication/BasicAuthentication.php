<?php

namespace BasicHttpClient\Request\Authentication;

/**
 * Class BasicAuthentication
 *
 * @package BasicHttpClient\Request\Authentication
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
	public function __construct($username, $password)
	{

		$this->username = $username;
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 * @return $this
	 */
	public function setUsername($username)
	{
		$this->username = $username;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 * @return $this
	 */
	public function setPassword($password)
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * @param resource $curl
	 * @return $this
	 */
	public function configureCurl($curl)
	{
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, $this->username . ':' . $this->password);
		return $this;
	}

}
