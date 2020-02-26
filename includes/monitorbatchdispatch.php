<?php
session_start();
$labss=$_SESSION['lab'];
	include("../connection/config.php");

 $qury = "SELECT ID,datemodified
            FROM samples
			WHERE Flag=1 AND BatchComplete=2
			ORDER BY ID DESC
			";
			
			
$result = mysql_query($qury) or die(mysql_error()); //query results
$no=mysql_num_rows($result); //determine number of bathces found

if ($no !=0)
{
	
	 while(list($ID,$datemodified) = mysql_fetch_array($result))
	{ 
	
	
	$date_result_updated =date("d-m-Y",strtotime(datemodified));
	$currentdate=date('d-m-Y'); //get current date
	$extradays = (strtotime($currentdate) - strtotime($date_result_updated)) / (60 * 60 * 24);

   
$job="Batches Awaiting Dispatch";

if ( $date_dispatched =="" && $extradays  > 1)
{

$checkifexisiting = "SELECT * FROM pendingtasks WHERE sample='$ID' AND task =2";
		$checkifresult = mysql_query($checkifexisiting );
		
			if(mysql_num_rows($checkifresult) > 0)
			 {
			 //do nothing
			 }
			else
			{
	
			$jobo = "INSERT INTO 		
			pendingtasks(task,sample,status,lab)VALUES(2,'$ID',0,'$labss')";
			$batchfordispatch = mysql_query($jobo) or die(mysql_error());
			
			
			}

}	
else if ($date_dispatched !="" )
	{
	//update status of batch to completed
	 $batchrek = mysql_query("UPDATE pendingtasks
              SET status = 1 
			  			   WHERE (sample = '$ID' AND task=2)")or die(mysql_error());
	}
	
}
}
else
{
//do nothing
}
?>
