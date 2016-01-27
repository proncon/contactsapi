<?php
namespace API;


class Base
{
	protected $config;
	protected $headers;
	protected $candidate;
	protected $endpoint;
	protected $candidateaddressbookid;

	function __construct()
	{
		require(__DIR__.'/../../config.php');
		$this->config = $config;
		$this->headers = $headers;
		$this->candidate = $candidate;
		$this->endpoint = $endpoint;
		$this->candidateaddressbookid = $candidateaddressbookid;
	}
}
