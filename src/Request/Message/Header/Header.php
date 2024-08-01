<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Request\Message\Header;

use ChromaX\BasicHttpClient\Util\HeaderNameUtil;

/**
 * Class Header
 *
 * @package ChromaX\BasicHttpClient\Request\Message\Header
 */
class Header implements HeaderInterface
{

	private string $name;

	/**
	 * @var string[]
	 */
	private array $values;

	public function __construct(string $name, array $values)
	{
		$this->setName($name);
		$this->setValues($values);
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getNormalizedName(): string
	{
		$headerNameUtil = new HeaderNameUtil();
		return $headerNameUtil->normalizeHeaderName($this->name);
	}

	public function setName(string $name): self
	{
		$this->name = trim($name);
		return $this;
	}

	/**
	 * @return string[]
	 */
	public function getValues(): array
	{
		return $this->values;
	}

	public function getValuesAsString(): string
	{
		return implode(', ', $this->values);
	}

	/**
	 * @param string[] $values
	 */
	public function setValues(array $values): self
	{
		foreach ($values as $value) {
			if (!is_string($value)) {
				$argumentType = (is_object($value)) ? get_class($value) : gettype($value);
				throw new \TypeError('Expected the values as array of strings. Got ' . $argumentType);
			}
		}
		foreach ($values as $value) {
			$this->values[] = trim($value);
		}
		return $this;
	}

}
