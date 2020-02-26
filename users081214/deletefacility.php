<?php
require_once('../connection/config.php');
include ('../includes/functions.php');

$numsamplesever = getsamplesdonebyfacility($ID);

if ($numsamplesever == 0)
{
	 $ID= $_GET["ID"];
	 $fname= $_GET["fname"];
	
	$userid=$_SESSION['uid'] ; //id of user who is updatin th record$code=$_GET['code'];
	
		$facilityrec = mysql_query("UPDATE facilitys
              SET Flag=0  
			  			   WHERE (ID = '$ID')");
	
 
	 if ($facilityrec)
	 {
		//save user activity
		$tasktime= date("h:i:s a");
		$todaysdate=date("Y-m-d");
		$utask = 18; //user task = delete facility
		
		$activity = SaveUserActivity($userid,$utask,$tasktime,$fname,$todaysdate);
				
		$st= $fname ." has been deleted.";
		header("location:facilitieslist.php?p=$st"); //direct to users list view
		exit();
				
	
	 }
}
else
{
header("location:facilitieslist.php"); //direct to users list view
}
 
 ?>