<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Request\Message\Cookie;

/**
 * Interface CookieInterface
 *
 * @package ChromaX\BasicHttpClient\Request\Message\Cookie
 */
interface CookieInterface
{

	public function __construct(string $name, string $value);

	public function getName(): string;

	public function setName(string $name);

	public function getValue(): string;

	public function setValue(string $value);

}
