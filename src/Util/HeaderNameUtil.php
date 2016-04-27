<?php

namespace BasicHttpClient\Util;

/**
 * Class HeaderNameUtil
 *
 * @package BasicHttpClient\Util
 */
class HeaderNameUtil
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
