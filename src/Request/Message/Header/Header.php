<?php

namespace ChromaX\BasicHttpClient\Request\Message\Header;

use ChromaX\BasicHttpClient\Util\HeaderNameUtil;

/**
 * Class Header
 *
 * @package ChromaX\BasicHttpClient\Request\Message\Header
 */
class Header implements HeaderInterface
{

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string[]
	 */
	private $values;

	/**
	 * Header constructor.
	 *
	 * @param string $name
	 * @param string[] $values
	 */
	public function __construct(string $name, array $values)
	{
		$this->setName($name);
		$this->setValues($values);
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getNormalizedName(): string
	{
		$headerNameUtil = new HeaderNameUtil();
		return $headerNameUtil->normalizeHeaderName($this->name);
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName(string $name)
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

	/**
	 * @return string
	 */
	public function getValuesAsString(): string
	{
		return implode(', ', $this->values);
	}

	/**
	 * @param string[] $values
	 * @return $this
	 */
	public function setValues(array $values)
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
