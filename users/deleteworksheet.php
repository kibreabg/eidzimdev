<?php
require_once('../connection/config.php');

 $wno = $_GET["ID"];
 $serial = $_GET["serial"];
 $qury = "SELECT ID
            FROM samples
			WHERE worksheet='$wno'";
$result = mysql_query($qury) or die(mysql_error()); //for main display
$wsheet="";
  while(list($ID) = mysql_fetch_array($result))
	{ 
	$samplerec = mysql_query("UPDATE samples
              SET worksheet = '$wsheet', Inworksheet=0  
			  			   WHERE (ID = '$ID')");
						   

	
	}
 	$worksheetrec = mysql_query("Delete from worksheets
             	  			   WHERE ID= '$serial'  AND worksheetno='$wno' ");
 if ($worksheetrec)
 {
 
 			$st="Worksheet No: ".$wno ." has been deleted.";
			header("location:worksheetlist.php?p=$st"); //direct to users list view
			exit();
			

 }
 
 ?>