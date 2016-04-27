<?php

namespace BasicHttpClient\Response;

use BasicHttpClient\Exception\HttpResponseException;

/**
 * Class JsonResponse
 *
 * @package BasicHttpClient\Response
 */
class JsonResponse extends AbstractResponse
{

	/**
	 * @param mixed $body
	 * @return $this
	 * @throws HttpResponseException
	 */
	protected function setBody($body)
	{
		$body = @json_decode($body);
		if(is_null($body)){
			throw new HttpResponseException('Response data is no valid JSON string.');
		}
		return parent::setBody($body);
	}

}
