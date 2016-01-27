<?php
namespace proncon\Group;
class Group
{
	
  	function Ola() {
		echo "ola";    
	}

	function LoadArray ()
	{
		require '../../vendor/autoload.php';
		$groupsArr=array();
		$groupsArrAPI=array();
		$config = [
		    'client_id' => "55493798955-olcchs77135pb7hu1a758uod4bsl332r.apps.googleusercontent.com",
		    'client-secret' => "Iq0yM00-xLMfg7DG6CuD4cCR",
		];
		$headers=array('Authorization' => "Bearer ya29.dAJfAfnvx_kK7VG6I30OIyfz4OfBwhdeZgB_fCpXr_DlLkYGS_QgTyv0sTas6JpGMgZv");

		$client = new \GuzzleHttp\Client($config);
		use GuzzleHttp\Psr7\Response;
		echo "ok";
	}
}
