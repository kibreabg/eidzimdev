<?php
require_once('../connection/config.php');
require_once('../includes/functions.php');
include error_reporting(0);
$currentdate = date("F j, Y, g:i a");//date('d-m-Y'); //get current date

//get the variables from the SMS link in dispatchedResults.php

$facility = -1;
$pid =-1;
$res =-1;
if(isset($_GET['facility']))
{
	$facility = $_GET['facility'];
}

if(isset($_GET['pid']))
{
	$pid =  $_GET['pid'];
}
if(isset($_GET['res']))
{
	$res = $_GET['res'];
}
$facilityname = GetFacility($facility);
$result = GetResultName($res); //get the result name ie either Positive, Negative....
$smsstatus = -2; 
$apiver = 1;
$action = 'print';

	//get the facility's sms printer imei and pass
	function GetFacilityimei($facility)
	{
		$facilityquery=mysql_query("SELECT imei as pi, pass as pp FROM facilitys where ID=".$facility."")or die(mysql_error()); 
		$dd=mysql_fetch_array($facilityquery);
		return $dd;
	}
	
	function Get_patientInfo($pid)
	{
		$patientquery=mysql_query("SELECT name,dob,testtype FROM patients where ID=".$pid."")or die(mysql_error()); 
		$patient=mysql_fetch_array($patientquery);
		return $patient;
	}
	
		function Get_SampleInfo($pid)
	{
		$samplesquery=mysql_query("SELECT datecollected,datetested,facility FROM samples where patient=".$pid."")or die(mysql_error()); 
		$samples=mysql_fetch_array($samplesquery);
		return $samples;
	}
	function UpdateSentStatus($pid,$stat)
	{
		if($stat==1)
		{
			$flagSmsStat = "UPDATE samples SET Smssent=$stat, NoofSmsSent=NoofSmsSent+1 WHERE patient = '$pid'";
		}
		else
		{
			$flagSmsStat = "UPDATE samples SET Smssent=$stat WHERE patient = '$pid'";
		}
		  $result = mysql_query($flagSmsStat) or die(mysql_error());
		  
		  return $result;
	}

	
	if(isset($_GET['facility']) && isset($_GET['pid']) && isset($_GET['res']))
{
		$patientInfo=Get_patientInfo($pid);
		$sampleInfo=Get_SampleInfo($pid);
	
$print_message = urlencode("Date: ").urlencode($currentdate)."%0A%0A".urlencode("Hospital Name: ").urlencode($facilityname)."%0A%0A".urlencode("DBS Request No: ").urlencode($pid)."%0A".urlencode("Patient Name: ").urlencode($patientInfo['name'])."%0A".urlencode("DOB: ").urlencode($patientInfo['dob'])."%0A%0A".urlencode("Test Type: ").urlencode('DNA PCR')."%0A".urlencode("Date DBS Collected: ").urlencode($sampleInfo['datecollected'])."%0A".urlencode("Date Sample Tested: ").urlencode($sampleInfo['datetested'])."%0A".urlencode("Result: ").urlencode($result)."";



				$urlOk=true;//file_get_conditional_contents(SMSHOST);
					
					
						
						$imeiDetail=GetFacilityimei($facility);
	
						$imei=$imeiDetail['pi'];
						$password=$imeiDetail['pp'];

						$requestUrl=getRequest($apiver,$imei,$password,$action,$print_message);
//echo $requestUrl;
						$response=ResponseToArray($requestUrl);
	if($response==false)
					{
						
						
						echo '<script type="text/javascript">' ;
		echo "window.location.href='dispatchedResults.php?smsstatus=-1&view=1'";
				echo '</script>';
					}
					else
					{
					//echo $print_message;	
				ResponseResult($response,0,0);
					}
}
	/***begin new method**/
	function getRequest($apiver,$imei,$password,$action,$print_message)
	{
		
		$format= SMSHOST."?apiver=%s&imei=%s&password=%s&action=%s&print_message=%s";
		
		$url = sprintf($format,$apiver,$imei,$password,$action,$print_message);
	
		return $url;
	}
	
	function file_get_conditional_contents($szURL)
	{
	  $pCurl = curl_init($szURL);
	  
	  curl_setopt($pCurl, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($pCurl, CURLOPT_FOLLOWLOCATION, true);
	  curl_setopt($pCurl, CURLOPT_TIMEOUT, 10);
  
	  $szContents = curl_exec($pCurl);
	  $aInfo = curl_getinfo($pCurl);
	  
	  if($aInfo['http_code'] === 200)
	  {
		  return $szContents;
	  }
	  
	  
	  return false;
	}
	
	function ResponseToArray($url)
	{
		//$pCurl = curl_init($url);
		//$szContents = curl_exec($pCurl);
		//echo $url;
		
	$result=file_get_contents($url);
$Response=false;
if($result!=false)
{
	  $xml = simplexml_load_string($result);
	  $json = json_encode($xml);
	  $Response = json_decode($json,TRUE);
}
	  
	  return $Response;
	}
	
	
	
	
	function ResponseResult($response,$facilityname,$pid)
	{
	  $error= $response['error'];
	
	  if($response['status']=='fail')
	  {
		  ?>
		  <!-- To Do: echo FAIL MESSAGE  -->		  
		  <?php
		  if(isset($_GET['facility'])&& isset($_GET['pid']) && isset($_GET['res']))
			{
				UpdateSentStatus($pid,0);
			  $smsstatus = 0; 
			  $facility = $_GET['facility'];
			  $pid =  $_GET['pid'];
			  // fail
			  //..redirect to the dispatchedResults page
				echo '<script type="text/javascript">' ;
				echo "window.location.href='dispatchedResults.php?smsstatus=".$smsstatus."&view=1&sfacility=".$facility."&spid=".$pid."&errorCode=".$error['code']."&errormsg=".$error['message']."'";
				echo '</script>';
			}
		  else
		  {
			  UpdateSentStatus($pid,0);
			  $reqsample=Get_SampleInfo($pid);
	
 $sfacilityquery=mysql_query("SELECT imei as pi, pass as pp FROM facilitys where ID=".$reqsample['facility']."")or die(mysql_error()); 

	$sdd=mysql_fetch_array($sfacilityquery);
	$simei=$sdd['pi'];
	$spassword=$sdd['pp'];
	if($simei=='')
	{
		$noimei="<b>GPRS Printer not assigned to the Facility.</b><br>";
	}
	   
			  echo "<div class='error'> ".$noimei." The SMS for " . $facilityname. " [ Sample Request No: " . $pid. "] has <u>NOT</u> been sent.			<br> Error Code: ".$error['code']."<br> Error Message: ".$error['message']."</div>";
		  }
	  }
	  else
	  {
		  ?>
		  <!-- To Do echo SUCCESS MESSAGE -->
		  <?php
		  if(isset($_GET['facility']) && isset($_GET['pid']) && isset($_GET['res']))
{
	
		  $smsstatus = 1; // success
		  $facility = $_GET['facility'];
		  $pid =  $_GET['pid'];
		  UpdateSentStatus($pid,1);
		  //..redirect to the dispatchedResults page
		  	echo '<script type="text/javascript">' ;
				echo "window.location.href='dispatchedResults.php?smsstatus=".$smsstatus."&view=1&sfacility=".$facility."&spid=".$pid."&errorCode=".$error['code']."&errormsg=".$error['message']."'";
				echo '</script>';
}
else
{
	UpdateSentStatus($pid,1);
	
	
	echo "<div class='success'>The SMS for " . $facilityname . " [ Sample Request No: " . $pid . "] has been <u>SENT</u>.</div>";
}


	  }
	
	}

	/***end new method **/
	?>