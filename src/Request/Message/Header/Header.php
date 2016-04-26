<?php

namespace BasicHttpClient\Request\Message\Header;

use BasicHttpClient\Util\HeaderNameNormalizer;

/**
 * Class Header
 *
 * @package BasicHttpClient\Request\Message\Header
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
		$this->name = $name;
		$this->values = $values;
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
		$normalizer = new HeaderNameNormalizer();
		return $normalizer->normalizeHeaderName($this->name);
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
	 * @return \string[]
	 */
	public function getValues()
	{
		return $this->values;
	}

	/**
	 * @param \string[] $values
	 * @return $this
	 */
	public function setValues($values)
	{
		$this->values = $values;
		return $this;
	}

}
