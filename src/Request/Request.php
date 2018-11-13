<?php

namespace Markenwerk\BasicHttpClient\Request;

use Markenwerk\BasicHttpClient\Response\Response;
use Markenwerk\BasicHttpClient\Response\ResponseInterface;

/**
 * Class Request
 *
 * @package Markenwerk\BasicHttpClient\Request
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
