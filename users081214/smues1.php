<?php
require_once('../connection/config.php');
require_once('../includes/functions.php');

$currentdate = date('d-m-Y'); //get current date

//get the variables from the SMS link in dispatchedResults.php
$facility = $_GET['facility']; $facilityname = GetFacility($facility);
$pid =  $_GET['pid'];
$res = $_GET['res']; $result = GetResultName($res); //get the result name ie either Positive, Negative....


	//get the facility's sms printer imei and pass
	$facilityquery=mysql_query("SELECT imei as pi, pass as pp FROM facilitys where ID='$facility' ")or die(mysql_error()); 
	$dd=mysql_fetch_array($facilityquery);
/*$imei=$dd['pi'];
$password=$dd['pp'];*/

$imei='357461030005436';
$password='tdaydbwe';
$apiver = 1;
$action = 'print';
$print_message = "Date SMS Sent: ".$currentdate." Hospital Name: ".$facilityname." Sample Request No: ".$pid." Result : ".$result;	
	
	/***begin new method**/
	function getRequest($apiver,$imei,$password,$action,$print_message)
	{
		$host="http://192.168.1.2/manage/api/";
		$format= $host."?apiver=%s&imei=%s&password=%s&action=%s&print_message=%s";
		
		$url = printf($format,$apiver,$imei,$password,$action,$print_message);
	
		return $url;
	}
	
	function ResponseToArray($url)
	{
	  $result=file_get_contents($url);
	  $xml = simplexml_load_string($result);
	  $json = json_encode($xml);
	  $Response = json_decode($json,TRUE);
	  
	  return $Response;
	}
	
	$requestUrl=getRequest($apiver,$imei,$password,$action,$print_message);
	
	$response=ResponseToArray($requestUrl);
	
	
	function ResponseResult($response)
	{
	  $error= $array['error'];
	  
	  if($array['status']=='fail')
	  {
		  ?>
		  <!-- To Do: echo FAIL MESSAGE  -->		  
		  <?php
		  $smsstatus = 0; // fail
		  //..redirect to the dispatchedResults page
		  	echo '<script type="text/javascript">' ;
			echo "window.location.href='dispatchedResults.php?smsstatus=$smsstatus&view=1&sfacility=$facility&spid=$pid'";
			echo '</script>';
	  }
	  else
	  {
		  ?>
		  <!-- To Do echo SUCCESS MESSAGE -->
		  <?php
		  $smsstatus = 1; // success
		  //..redirect to the dispatchedResults page
		  	echo '<script type="text/javascript">' ;
			echo "window.location.href='dispatchedResults.php?smsstatus=$smsstatus&view=1&sfacility=$facility&spid=$pid'";
			echo '</script>';
	  }
	
	}

	/***end new method **/
	?>