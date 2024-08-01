<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Request\Message\Cookie;

/**
 * Class Cookie
 *
 * @package ChromaX\BasicHttpClient\Request\Message\Cookie
 */
class Cookie implements CookieInterface
{

	private string $name;

	private string $value;

	public function __construct(string $name, string $value)
	{
		$this->name = $name;
		$this->value = $value;
	}

	public function getName(): string
	{
		return $this->name;
	}


	public function setName(string $name): self
	{
		$this->name = $name;
		return $this;
	}


	public function getValue(): string
	{
		return $this->value;
	}

	public function setValue(string $value): self
	{
		$this->value = $value;
		return $this;
	}

}
