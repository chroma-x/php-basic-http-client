<?php

namespace BasicHttpClient\Response\Statistics;

/**
 * Class StatisticsBuilder
 *
 * @package BasicHttpClient\Response\Statistics
 */
class StatisticsBuilder
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
	 * @param float $totalTime
	 * @return $this
	 */
	public function setTotalTime($totalTime)
	{
		$this->totalTime = $totalTime;
		return $this;
	}

	/**
	 * @param float $hostLookupTime
	 * @return $this
	 */
	public function setHostLookupTime($hostLookupTime)
	{
		$this->hostLookupTime = $hostLookupTime;
		return $this;
	}

	/**
	 * @param float $connectionEstablishTime
	 * @return $this
	 */
	public function setConnectionEstablishTime($connectionEstablishTime)
	{
		$this->connectionEstablishTime = $connectionEstablishTime;
		return $this;
	}

	/**
	 * @param float $preTransferTime
	 * @return $this
	 */
	public function setPreTransferTime($preTransferTime)
	{
		$this->preTransferTime = $preTransferTime;
		return $this;
	}

	/**
	 * @param float $startTransferTime
	 * @return $this
	 */
	public function setStartTransferTime($startTransferTime)
	{
		$this->startTransferTime = $startTransferTime;
		return $this;
	}

	/**
	 * @return Statistics
	 */
	public function buildStatistics()
	{
		return new Statistics(
			$this->totalTime,
			$this->hostLookupTime,
			$this->connectionEstablishTime,
			$this->preTransferTime,
			$this->startTransferTime
		);
	}

}
