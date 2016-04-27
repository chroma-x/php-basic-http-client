<?php

namespace BasicHttpClient\Util;

/**
 * Class UrlUtil
 *
 * @package BasicHttpClient\Util
 */
class UrlUtil
{

	/**
	 * @param string $url
	 * @return string
	 */
	public function normalizeUrl($url)
	{
		return filter_var($url, FILTER_SANITIZE_URL);
	}

	/**
	 * @param string $url
	 * @return bool
	 */
	public function validateUrl($url)
	{
		return !filter_var($url, FILTER_VALIDATE_URL) === false;
	}

}
