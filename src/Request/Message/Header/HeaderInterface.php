<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Request\Message\Header;

/**
 * Interface HeaderInterface
 *
 * @package ChromaX\BasicHttpClient\Request\Message\Header
 */
interface HeaderInterface
{

	public function __construct(string $name, array $values);

	public function getName(): string;

	public function getNormalizedName(): string;

	public function setName(string $name): self;

	public function getValues(): array;

	public function getValuesAsString(): string;

	public function setValues(array $values): self;

}
