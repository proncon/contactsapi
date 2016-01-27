<?php
namespace API;


class Base
{
	protected $config;
	protected $headers;
	protected $dbconfig;
	protected $candidate;

	function __construct()
	{
		require(__DIR__.'/../../config.php');
		$this->config = $config;
		$this->headers = $headers;
		$this->dbconfig = $dbconfig;
		$this->candidate = $candidate;
	}
}