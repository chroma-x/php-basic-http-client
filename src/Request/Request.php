<?php

namespace ChromaX\BasicHttpClient\Request;

use ChromaX\BasicHttpClient\Response\Response;
use ChromaX\BasicHttpClient\Response\ResponseInterface;

/**
 * Class Request
 *
 * @package ChromaX\BasicHttpClient\Request
 */
class Request extends AbstractRequest
{

	/**
	 * @return ResponseInterface
	 */
	protected function buildResponse(): ResponseInterface
	{
		return new Response($this);
	}

}
