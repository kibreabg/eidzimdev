<?php 
session_start();
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
$success=$_GET['p'];
$labss=$_SESSION['lab'];
$userid=$_SESSION['uid'] ;
$accounttype=$_SESSION['accounttype'] ;
$releaseforprinting=$_GET['releaseforprinting'];

if ($accounttype == '1') //data clerk 1
{
	$showlink = '1';
	$showtask = '<th>Action</th>';
}
else //do not show links
{
	$showlink = '';
	$showtask = '';
}
?>
<?php include('../includes/header.php');
$checkbox= $_POST['checkbox'];
$datereleased =date('Y-m-d');
$BatchNo= $_POST['BatchNo'];
$labcode= $_POST['labno'];

if ( $checkbox !="" )
{


	foreach($checkbox as $a => $b)
 	{	//update pending tasks
			$pendingtaskrec = mysql_query("UPDATE pendingtasks
              SET   status = 1 ,dateupdated='$datedispatched'
			  			   WHERE (batchno = '$BatchNo[$a]'  AND sample='$labcode[$a]'  AND task=6)")or die(mysql_error());
		//update samples with date released
		$samplerec = mysql_query("UPDATE samples
					  SET   datereleased = '$datereleased',BatchComplete=1 ,rejectedby='$userid'
								   WHERE (ID = '$labcode[$a]')")or die(mysql_error());
						   
						 
 	}
	 if (($pendingtaskrec ) && ($samplerec))
		{
		$st='<center>'.   ' The Selected Rejected Sample(s) Successfuly Updated and Released for Printing.</center>';
		}
		else
		{
		$rr='<center>'.  ' Updating and Releasing the Selected Rejected Sample(s) Failed to Update, try again.</center>';

		}

	}


?>
<style type="text/css">
select {
width: 250;}
</style>	

		<div  class="section">
		<div class="section-title">REJECTED SAMPLES DISPATCHED  LIST </div>
		<div class="xtop">
		<?php if ($releaseforprinting !="")
		{
		?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="success"><?php 
		
echo  '<strong>'.' <font color="#666600">'.$releaseforprinting.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
<?php } ?>
		<?php if ($st !="")
		{
		?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="success"><?php 
		
echo  '<strong>'.' <font color="#666600">'.$st.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
<?php } ?>
<?php if ($rr !="")
		{
		?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="error"><?php 
		
echo  '<strong>'.' <font color="#666600">'.$rr.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
<?php } ?>


 <?php
 $rowsPerPage = 15; //number of rows to be displayed per page

// by default we show first page
$pageNum = 1;

// if $_GET['page'] defined, use it as page number
if(isset($_GET['page']))
{
$pageNum = $_GET['page'];
}

// counting the offset
$offset = ($pageNum - 1) * $rowsPerPage;
//query database for all districts
   if ($accounttype == '1') //data clerk 1
{
   $query = "SELECT ID as 'labcode',batchno,patient,facility,datereceived,datetested,datemodified,result,datedispatched,datereleased,printed,rejectedreason
   			 FROM samples
			where samples.lab='$labss' AND  samples.BatchComplete=1 and samples.Flag=1 and approved = 2  and resultprinted=1 AND  receivedstatus=2
			ORDER BY datedispatched DESC,facility 
			LIMIT $offset, $rowsPerPage";
			
	}
	else
	{
	
	$query = "SELECT ID as 'labcode',batchno,patient,facility,datereceived,datetested,datemodified,result,datedispatched,datereleased,printed,rejectedreason
   			 FROM samples
			where samples.lab='$labss' AND  samples.BatchComplete=1 and samples.Flag=1 and approved = 2  and datereleased !='' and receivedstatus=2
			ORDER BY datereleased DESC,facility 
			LIMIT $offset, $rowsPerPage";
	
	}		
		
			
		
			
			
			$rejectedresult = mysql_query($query) or die(mysql_error()); //for main display
			$result2 = mysql_query($query) or die(mysql_error()); //for calculating samples with results and those without

$no=mysql_num_rows($rejectedresult);



if ($no !=0)
{ ?>
	
	<?php
// print the districts info in table
echo '<table border="0"   class="data-table">
 <tr ><th>No</th><th>Lab No</th><th>Sample ID</th><th>Batch No</th><th>Facility</th><th>Province</th><th>Date Received</th><th>Rejected Reason</th><th>Date Released </th><th>Printed </th><th>Date Dispatched </th>'.$showtask.'</tr>';	
$i = 0; 
	while(list($labcode,$batchno,$patient,$facility,$datereceived,$datetested,$datemodified,$result,$datedispatched,$datereleased,$printed,$rejectedreason) = mysql_fetch_array($rejectedresult))
	{ 		
		$i=$i+1;
		 if ($printed == 0)
		 {
			$printed="<font color='#FF0000'>N</font>";
		 }
		 else
		 {
			$printed =" <strong><font color='#339900'> Y </font></strong>";
		 }
		
		//get sample facility name based on facility code
		$facilityname=GetFacility($facility);
				
		$facilitydetails= getFacilityDetails($facility);
		extract($facilitydetails);
		//get selected district ID
		$distid=GetDistrictID($facility);	
			//get province ID
		$provid=GetProvid($distid);
			//get province name	
		$provname=GetProvname($provid);	
		 
		if (($datereceived != "" ) && ($datereceived != "0000-00-00") && ($datereceived != "1970-01-01"))
		 {
		$date_received =date("d-M-Y",strtotime($datereceived));
		}
		else
		{
		$date_received ="";
		}
		
			
		if (($datedispatched != "" ) && ($datedispatched != "0000-00-00") && ($datedispatched != "1970-01-01"))
		 {
		$date_dispatched =date("d-M-Y",strtotime($datedispatched));
		}
		else
		{
		$date_dispatched = '';
		}
		
		
		$date_released =date("d-M-Y",strtotime($datereleased));
				
	
		
		$rejectedreason=GetRejectedReason($rejectedreason);
		
?>
<tr bgcolor='#F0F3FA'>
<td ><?php echo $i;?></td>
<td ><?php echo $labcode;?></td>
<td ><?php echo $patient;?></td>
<td ><?php echo $batchno;?></td>
<td ><?php echo $facilityname;?> </td>
<td ><?php echo $provname;?> </td>
<td ><?php echo $date_received;?></td>
<td ><?php echo $rejectedreason;?></td>
<td ><?php echo $date_released;?></td>
<td ><?php echo $printed;?></td>
<td ><?php echo $date_dispatched;?> </td>
<?php
if ($showlink == '1')
{
?>
<td > <a href=rejecteddispatch_summary.php?ID=<?php echo $labcode; ?> target="_blank"> Print </a>   </td>
<?php
}
else
{
}
?>
	</tr>
<?php
	}
	echo '</table>';
	?>
		<?php
	echo '<br>';

	if ($accounttype == '1') //data clerk 1
{
   $qury = mysql_query("SELECT ID as 'labcode',batchno,patient,facility,datereceived,datetested,datemodified,result,datedispatched,datereleased,printed,rejectedreason
   			 FROM samples
			where samples.lab='$labss' AND  samples.BatchComplete=1 and samples.Flag=1 and approved = 2  and resultprinted=1 AND  receivedstatus=2
			ORDER BY datedispatched DESC,facility 
			") or die(mysql_error());
			
	}
	else
	{
	
	$qury =mysql_query( "SELECT ID as 'labcode',batchno,patient,facility,datereceived,datetested,datemodified,result,datedispatched,datereleased,printed,rejectedreason
   			 FROM samples
			where samples.lab='$labss' AND  samples.BatchComplete=1 and samples.Flag=1 and approved = 2  and datereleased !='' and receivedstatus=2
			ORDER BY datereleased DESC,facility 
			") or die(mysql_error());
	
	}	
			$numrows=mysql_num_rows($qury);
	// how many pages we have when using paging?
	$maxPage = ceil($numrows/$rowsPerPage);

// print the link to access each page
$self = $_SERVER['PHP_SELF'];
$nav  = '';
for($page = 1; $page <= $maxPage; $page ++)
		{
		   if ($page == $pageNum)
		   {
			  $nav .= " $page "; // no need to create a link to current page
		   }
		   /*else
		   {
			  $nav .= " <a href=\"$self?page=$page\">$page</a> ";
		   }*/
		}
		
		// creating previous and next link
		// plus the link to go straight to
		// the first and last page
		
		if ($pageNum > 1)
		{
		   $page  = $pageNum - 1;
		   $prev  = " <a href=\"$self?page=$page\">Prev  |</a> ";
		
		   $first = " <a href=\"$self?page=1\">First Page | </a> ";
		}
		else
		{
		   $prev  = '&nbsp;'; // we're on page one, don't print previous link
		   $first = '&nbsp;'; // nor the first page link
		}
		
		if ($pageNum < $maxPage)
		{
		   $page = $pageNum + 1;
		   $next = " <a href=\"$self?page=$page\"> | Next | </a> ";
		
		   $last = " <a href=\"$self?page=$maxPage\">  Last Page </a> ";
		}
		else
		{
		   $next = '&nbsp;'; // we're on the last page, don't print next link
		   $last = '&nbsp;'; // nor the last page link
		}
		
		// print the navigation link
		echo '<center>'. $first . "  ". $prev  ." ". $nav . "  ". $next ."  ". $last .'</center>';
	
}

else
{
?>
<table   >
  <tr>
    <td style="width:auto" ><div class="notice"><?php 
		
echo  '<strong>'.' <font color="#666600">'.'No Rejected Samples Have Been Dispatched'.'</strong>'.' </font>';

?></div></th>
  </tr>
</table><?php
}  
  ?>
	
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>