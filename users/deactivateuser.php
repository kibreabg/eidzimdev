<?php 
session_start();
require_once('../connection/config.php');
include("../protectpages.php");
include("../includes/functions.php");

$userid = $_GET["ID"];
$sessionuserid = $_SESSION['uid'];
$username = $_GET["name"];
$datedeactivated=date("d-M-Y");
 
   
$del = mysql_query("UPDATE users
              SET flag = 0 ,datedeactivated='$datedeactivated'
			  			   WHERE (ID = '$userid')");
 if ($del)
 { 
 	//..save the user activity
	$tasktime= date("h:i:s a");
	$todaysdate=date("Y-m-d"); //get the patient ID then one can retreave the info saved even though it has been edited
	$utask = 14; //user task = deactivate user
	
	$activity = SaveUserActivity($sessionuserid,$utask,$tasktime,$userid,$todaysdate);
	
	$st="Account for " .$username . " has been deactivated";

	echo '<script type="text/javascript">' ;
	echo "window.location.href='userslist.php?deactsuccess=$st'";
	echo '</script>';
 }
 
?>
