<?php

namespace ChromaX\BasicHttpClient\Request\Message\Header;

/**
 * Interface HeaderInterface
 *
 * @package ChromaX\BasicHttpClient\Request\Message\Header
 */
interface HeaderInterface
{

	/**
	 * Header constructor.
	 *
	 * @param string $name
	 * @param string[] $values
	 */
	public function __construct(string $name, array $values);

	/**
	 * @return string
	 */
	public function getName(): string;

	/**
	 * @return string
	 */
	public function getNormalizedName(): string;

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName(string $name);

	/**
	 * @return string[]
	 */
	public function getValues(): array;

	/**
	 * @return string
	 */
	public function getValuesAsString(): string;

	/**
	 * @param string[] $values
	 * @return $this
	 */
	public function setValues(array $values);

}
