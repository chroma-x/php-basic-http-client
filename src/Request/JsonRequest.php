<?php

namespace BasicHttpClient\Request;

use BasicHttpClient\Response\JsonResponse;
use BasicHttpClient\Response\ResponseInterface;

/**
 * Class JsonRequest
 *
 * @package BasicHttpClient\Request
 */
class JsonRequest extends AbstractRequest
{

	/**
	 * @return ResponseInterface
	 */
	protected function buildResponse()
	{
		return new JsonResponse($this);
	}

}
