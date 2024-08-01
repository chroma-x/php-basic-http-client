<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Util;

/**
 * Class HeaderNameUtil
 *
 * @package ChromaX\BasicHttpClient\Util
 */
class HeaderNameUtil
{

	/**
	 * @param string $headerName
	 * @return string
	 */
	public function normalizeHeaderName(string $headerName): string
	{
		$headerName = str_replace('-', ' ', $headerName);
		$headerName = ucwords($headerName);
		$headerName = str_replace(' ', '-', $headerName);
		return $headerName;
	}

}
