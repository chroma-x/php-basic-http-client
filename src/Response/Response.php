<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Response;

/**
 * Class Response
 *
 * @package ChromaX\BasicHttpClient\Response
 */
class Response extends AbstractResponse
{

	/**
	 * @return string
	 */
	public function getBody(): string
	{
		return parent::getBody();
	}

}
