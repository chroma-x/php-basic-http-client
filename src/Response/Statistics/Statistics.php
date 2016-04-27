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
	 * Statistics constructor.
	 *
	 * @param float $totalTime
	 * @param float $hostLookupTime
	 * @param float $connectionEstablishTime
	 * @param float $preTransferTime
	 * @param float $startTransferTime
	 */
	public function __construct(
		$totalTime,
		$hostLookupTime,
		$connectionEstablishTime,
		$preTransferTime,
		$startTransferTime
	) {
		$this->totalTime = $totalTime;
		$this->hostLookupTime = $hostLookupTime;
		$this->connectionEstablishTime = $connectionEstablishTime;
		$this->preTransferTime = $preTransferTime;
		$this->startTransferTime = $startTransferTime;
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
