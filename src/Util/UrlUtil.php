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
		if (!is_string($url)) {
			$argumentType = (is_object($url)) ? get_class($url) : gettype($url);
			throw new \InvalidArgumentException('Expected the URL as string. Got ' . $argumentType);
		}
		return filter_var($url, FILTER_SANITIZE_URL);
	}

	/**
	 * @param string $url
	 * @return bool
	 */
	public function validateUrl($url)
	{
		if (!is_string($url)) {
			$argumentType = (is_object($url)) ? get_class($url) : gettype($url);
			throw new \InvalidArgumentException('Expected the URL as string. Got ' . $argumentType);
		}
		return !filter_var($url, FILTER_VALIDATE_URL) === false;
	}

	/**
	 * @param string $url
	 */
	public function getScheme($url)
	{
		if (!is_string($url)) {
			$argumentType = (is_object($url)) ? get_class($url) : gettype($url);
			throw new \InvalidArgumentException('Expected the URL as string. Got ' . $argumentType);
		}
		return mb_strtoupper(parse_url($url, PHP_URL_SCHEME));
	}

}
