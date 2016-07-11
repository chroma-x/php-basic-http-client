<?php

namespace Markenwerk\BasicHttpClient\Request\Message\Cookie;

/**
 * Interface CookieInterface
 *
 * @package Markenwerk\BasicHttpClient\Request\Message\Cookie
 */
interface CookieInterface
{

	/**
	 * Cookie constructor.
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function __construct($name, $value);

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name);

	/**
	 * @return string
	 */
	public function getValue();

	/**
	 * @param string $value
	 * @return $this
	 */
	public function setValue($value);

}
