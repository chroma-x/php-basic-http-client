<?php

namespace Propeller\Lib\HttpClient\Base;

/**
 * HTTP request cookie class
 *
 * Exception code range: `25000`
 *
 * @package    Propeller
 * @author     Martin Brecht-Precht (mb@markenwerk.net)
 * @date       2014-06-12 16:16:41 +0000
 */
class HttpRequestCookie
{

	/**
	 * The request header name
	 *
	 * @var string
	 */
	private $name;

	/**
	 * The request header value
	 *
	 * @var string
	 */
	private $value;

	/**
	 * The constructor
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function __construct($name, $value)
	{
		$this->name = $name;
		$this->value = $value;
	}

	/**
	 * Setter for the request header name
	 *
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * Getter for the request header name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Setter for the request header value
	 *
	 * @param string $value
	 * @return $this
	 */
	public function setValue($value)
	{
		$this->value = $value;
		return $this;
	}

	/**
	 * Getter for the request header value
	 *
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}

}
