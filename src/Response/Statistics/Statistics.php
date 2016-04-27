<?php

namespace BasicHttpClient\Response\Statistics;

/**
 * Class Statistics
 *
 * @package BasicHttpClient\Response\Statistics
 */
class Statistics
{

	/**
	 * @var float
	 */
	private $totalTime;

	/**
	 * @var float
	 */
	private $hostLookupTime;

	/**
	 * @var float
	 */
	private $connectionEstablishTime;

	/**
	 * @var float
	 */
	private $preTransferTime;

	/**
	 * @var float
	 */
	private $startTransferTime;

	/**
	 * @param resource $curl
	 * @return $this
	 */
	public function populateFromCurlResult($curl)
	{
		$this->totalTime = curl_getinfo($curl, CURLINFO_TOTAL_TIME);
		$this->hostLookupTime = curl_getinfo($curl, CURLINFO_NAMELOOKUP_TIME);
		$this->connectionEstablishTime = curl_getinfo($curl, CURLINFO_CONNECT_TIME);
		$this->preTransferTime = curl_getinfo($curl, CURLINFO_PRETRANSFER_TIME);
		$this->startTransferTime = curl_getinfo($curl, CURLINFO_STARTTRANSFER_TIME);
		return $this;
	}

	/**
	 * @return float
	 */
	public function getTotalTime()
	{
		return $this->totalTime;
	}

	/**
	 * @return float
	 */
	public function getHostLookupTime()
	{
		return $this->hostLookupTime;
	}

	/**
	 * @return float
	 */
	public function getConnectionEstablishTime()
	{
		return $this->connectionEstablishTime;
	}

	/**
	 * @return float
	 */
	public function getPreTransferTime()
	{
		return $this->preTransferTime;
	}

	/**
	 * @return float
	 */
	public function getStartTransferTime()
	{
		return $this->startTransferTime;
	}

}
