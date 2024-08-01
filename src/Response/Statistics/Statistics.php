<?php

declare(strict_types=1);

namespace ChromaX\BasicHttpClient\Response\Statistics;

/**
 * Class Statistics
 *
 * @package ChromaX\BasicHttpClient\Response\Statistics
 */
class Statistics
{

	private float $totalTime;

	private float $hostLookupTime;

	private float $connectionEstablishTime;

	private float $preTransferTime;

	private float $startTransferTime;

	private int $redirectCount;

	private float $redirectTime;

	private string $redirectEndpoint;

	public function populateFromCurlResult(\CurlHandle|false $curl): self
	{
		if ($curl === false) {
			throw new \TypeError('cURL is not a valid CurlHandle class.');
		}
		$this->totalTime = curl_getinfo($curl, CURLINFO_TOTAL_TIME);
		$this->hostLookupTime = curl_getinfo($curl, CURLINFO_NAMELOOKUP_TIME);
		$this->connectionEstablishTime = curl_getinfo($curl, CURLINFO_CONNECT_TIME);
		$this->preTransferTime = curl_getinfo($curl, CURLINFO_PRETRANSFER_TIME);
		$this->startTransferTime = curl_getinfo($curl, CURLINFO_STARTTRANSFER_TIME);
		$this->redirectCount = curl_getinfo($curl, CURLINFO_REDIRECT_COUNT);
		$this->redirectTime = curl_getinfo($curl, CURLINFO_REDIRECT_TIME);
		$this->redirectEndpoint = curl_getinfo($curl, CURLINFO_REDIRECT_URL);
		return $this;
	}

	/**
	 * @return float
	 */
	public function getTotalTime(): float
	{
		return $this->totalTime;
	}

	/**
	 * @return float
	 */
	public function getHostLookupTime(): float
	{
		return $this->hostLookupTime;
	}

	/**
	 * @return float
	 */
	public function getConnectionEstablishTime(): float
	{
		return $this->connectionEstablishTime;
	}

	/**
	 * @return float
	 */
	public function getPreTransferTime(): float
	{
		return $this->preTransferTime;
	}

	/**
	 * @return float
	 */
	public function getStartTransferTime(): float
	{
		return $this->startTransferTime;
	}

	/**
	 * @return int
	 */
	public function getRedirectCount(): int
	{
		return $this->redirectCount;
	}

	/**
	 * @return float
	 */
	public function getRedirectTime(): float
	{
		return $this->redirectTime;
	}

	/**
	 * @return string
	 */
	public function getRedirectEndpoint(): string
	{
		return $this->redirectEndpoint;
	}

}
