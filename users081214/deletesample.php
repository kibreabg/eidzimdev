<?php
require_once('../connection/config.php');
include('../includes/functions.php');

 $ID= $_GET["ID"];
 $batch= $_GET["batch"];
 $userid = $_SESSION['uid'];
 
	$samplerec = mysql_query("UPDATE samples SET Flag=0 WHERE (ID = '$ID')");
 
 if ($samplerec)
 {
 
 	//save user activity
	$tasktime= date("h:i:s a");
	$todaysdate=date("Y-m-d");
	$utask = 3; //user task = delete sample
	
	$activity = SaveUserActivity($userid,$utask,$tasktime,$ID,$todaysdate);
	
 			$pendingtaskdel = mysql_query("UPDATE pendingtasks SET Flag=0 WHERE (ID = '$ID')");
 			$st="Sample : ".$ID ." has been deleted.";
			header("location:BatchDetails.php?p=$st&ID=$batch"); //direct to users list view
			exit();
			

 }
 
 ?>