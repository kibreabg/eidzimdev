<?php
session_start();
$labss=$_SESSION['lab'];
require_once('../connection/config.php');
//get all th batch nos for th samples
$qury = "SELECT ID,datereceived
            FROM samples
			WHERE Flag=1 AND BatchComplete=0 AND result=0 and  repeatt=0  and receivedstatus !=2
			
			";
			
$result = mysql_query($qury) or die(mysql_error()); //query results
$no=mysql_num_rows($result); //determine number of bathces found

if ($no !=0)
{
 while(list($ID,$datereceived) = mysql_fetch_array($result))
	{
	
	$currentdate=date('d-m-Y'); //get current date
	//get bach received date
	$sdrec=GetDatereceived($batchno);
	$sdrec=date("d-m-Y",strtotime($sdrec)); //format in in day-month-year format
	$extradays = (strtotime($currentdate) - strtotime($sdrec)) / (60 * 60 * 24);
	//count no. of samples per batch
	    $task="Samples Awaiting Testing";
	if ( $extradays  > 1 )
	{
	
		$checkexisiting = "SELECT * FROM pendingtasks WHERE sample='$ID' AND task =1";
		$checkresult = mysql_query($checkexisiting );
		
			if(mysql_num_rows($checkresult) > 0)
			 {
			 		
			 //do nothing
			 }
			else
			{
	
			$activity = "INSERT INTO 		
			pendingtasks(task,sample,status,lab)VALUES(1,'$ID',0,'$labss')";
			$pendingactivity = @mysql_query($activity) or die(mysql_error());
			}
	}
	

	
}
}
else
{
//do nothing
}?>