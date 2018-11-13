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
	public function __construct(string $name, string $value);

	/**
	 * @return string
	 */
	public function getName(): string;

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName(string $name);

	/**
	 * @return string
	 */
	public function getValue(): string;

	/**
	 * @param string $value
	 * @return $this
	 */
	public function setValue(string $value);

}
