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

	private ?string $redirectEndpoint = null;

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
		$redirectUrl = curl_getinfo($curl, CURLINFO_REDIRECT_URL);
		if ($redirectUrl !== false) {
			$this->redirectEndpoint = $redirectUrl;
		}
		return $this;
	}

	public function getTotalTime(): float
	{
		return $this->totalTime;
	}

	public function getHostLookupTime(): float
	{
		return $this->hostLookupTime;
	}

	public function getConnectionEstablishTime(): float
	{
		return $this->connectionEstablishTime;
	}

	public function getPreTransferTime(): float
	{
		return $this->preTransferTime;
	}

	public function getStartTransferTime(): float
	{
		return $this->startTransferTime;
	}

	public function getRedirectCount(): int
	{
		return $this->redirectCount;
	}

	public function getRedirectTime(): float
	{
		return $this->redirectTime;
	}

	public function getRedirectEndpoint(): ?string
	{
		return $this->redirectEndpoint;
	}

}
