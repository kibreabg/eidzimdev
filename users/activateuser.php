<?php 
session_start();
require_once('../connection/config.php');
include("../protectpages.php");
include("../includes/functions.php");

 $userid = $_GET["ID"];
 $sessionuserid = $_SESSION['uid'];
 $username = $_GET["name"];
 $dateactivated=date("d-M-Y");
 
   
$del = mysql_query("UPDATE users
              SET flag = 1 ,dateactivated='$dateactivated'
			  			   WHERE (ID = '$userid')");
 if ($del)
 { 
 	//..save the user activity
	$tasktime= date("h:i:s a");
	$todaysdate=date("Y-m-d"); //get the patient ID then one can retreave the info saved even though it has been edited
	$utask = 15; //user task = activate user
	
	$activity = SaveUserActivity($sessionuserid,$utask,$tasktime,$userid,$todaysdate);
	
 	$st="Account for " .$username . " has been Activated";

	echo '<script type="text/javascript">' ;
	echo "window.location.href='userslist.php?deactsuccess=$st'";
	echo '</script>';
 }
 
?>
