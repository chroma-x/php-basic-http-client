<?php

namespace Propeller\Lib\HttpClient\Base;

/**
 * HTTP request header class
 *
 * Exception code range: `25200`
 *
 * @package    Propeller
 * @author     Martin Brecht-Precht (mb@markenwerk.net)
 * @date       2014-06-25
 */
class HttpResponseHeader
{

	/**
	 * The request cookie name
	 * @var string
	 */
	private $name;

	/**
	 * The request cookie value
	 * @var string
	 */
	private $value;

	/**
	 * Setter for the request cookie name
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * Getter for the request cookie name
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Setter for the request cookie value
	 * @param string $value
	 * @return $this
	 */
	public function setValue($value)
	{
		$this->value = $value;
		return $this;
	}

	/**
	 * Getter for the request cookie value
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}

}
