<?php

namespace Markenwerk\BasicHttpClient\Util;

/**
 * Class HeaderNameUtil
 *
 * @package Markenwerk\BasicHttpClient\Util
 */
class HeaderNameUtil
{

	/**
	 * @param string $headerName
	 * @return string
	 */
	public function normalizeHeaderName($headerName)
	{
		if (!is_string($headerName)) {
			$argumentType = (is_object($headerName)) ? get_class($headerName) : gettype($headerName);
			throw new \InvalidArgumentException('Expected the header name as string. Got ' . $argumentType);
		}
		$headerName = str_replace('-', ' ', $headerName);
		$headerName = ucwords($headerName);
		$headerName = str_replace(' ', '-', $headerName);
		return $headerName;
	}

}
