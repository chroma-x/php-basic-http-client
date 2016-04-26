<?php

namespace BasicHttpClient\Util;

/**
 * Class HeaderNameNormalizer
 *
 * @package BasicHttpClient\Util
 */
class HeaderNameNormalizer
{

	/**
	 * @param string $headerName
	 * @return string
	 */
	public function normalizeHeaderName($headerName)
	{
		$headerName = str_replace('-', ' ', $headerName);
		$headerName = ucwords($headerName);
		$headerName = str_replace(' ', '-', $headerName);
		return $headerName;
	}

}
