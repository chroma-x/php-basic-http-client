<?php

namespace BasicHttpClient\Request\Message\Cookie;

/**
 * Class Cookie
 *
 * @package BasicHttpClient\Request\Message\Cookie
 */
class Cookie implements CookieInterface
{

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $value;

	/**
	 * Cookie constructor.
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
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param string $value
	 * @return $this
	 */
	public function setValue($value)
	{
		$this->value = $value;
		return $this;
	}

}
