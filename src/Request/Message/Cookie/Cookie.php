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
		if (!is_string($name)) {
			$argumentType = (is_object($name)) ? get_class($name) : gettype($name);
			throw new \InvalidArgumentException('Expected the name as string. Got ' . $argumentType);
		}
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
		if (!is_string($value)) {
			$argumentType = (is_object($value)) ? get_class($value) : gettype($value);
			throw new \InvalidArgumentException('Expected the value as string. Got ' . $argumentType);
		}
		$this->value = $value;
		return $this;
	}

}
