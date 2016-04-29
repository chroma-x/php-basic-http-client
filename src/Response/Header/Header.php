<?php

namespace BasicHttpClient\Response\Header;

use BasicHttpClient\Util\HeaderNameUtil;

/**
 * Class Header
 *
 * @package BasicHttpClient\Response\Header
 */
class Header
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
	public function __construct($name, array $values)
	{
		$this->setName($name);
		$this->setValues($values);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getNormalizedName()
	{
		$headerNameUtil = new HeaderNameUtil();
		return $headerNameUtil->normalizeHeaderName($this->name);
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	private function setName($name)
	{
		if (!is_string($name)) {
			$argumentType = (is_object($name)) ? get_class($name) : gettype($name);
			throw new \InvalidArgumentException('Expected the header name as string. Got ' . $argumentType);
		}
		$this->name = trim($name);
		return $this;
	}

	/**
	 * @return string[]
	 */
	public function getValues()
	{
		return $this->values;
	}

	/**
	 * @return string
	 */
	public function getValuesAsString()
	{
		return implode(', ', $this->values);
	}

	/**
	 * @param string[] $values
	 * @return $this
	 */
	private function setValues($values)
	{
		foreach ($values as $value) {
			if (!is_string($value)) {
				$argumentType = (is_object($value)) ? get_class($value) : gettype($value);
				throw new \InvalidArgumentException('Expected the header values as array of strings. Got ' . $argumentType);
			}
		}
		foreach ($values as $value) {
			$this->values[] = trim($value);
		}
		return $this;
	}

}
