<?php 
session_start();
require_once('../connection/config.php');
include('../includes/header.php');

$accounttype=$_SESSION['accounttype'] ;
$releaseforprinting=$_GET['releaseforprinting'];

//..get the sms variables from the smues page
$smsstatus = $_GET['smsstatus']; //.. 1= sent; 0 = failed send
$sfacility=$_GET['sfacility'];
$spid=$_GET['spid'];
$sfacilityname=GetFacility($sfacility);

//require_once('classes/tc_calendar.php');
$success=$_GET['p'];
$labss=$_SESSION['lab'];
$userid=$_SESSION['uid'] ; //id of user who is updatin th record

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

$checkbox= $_POST['checkbox'];
$datereleased =date('Y-m-d');
//$datedispatched =date('Y-m-d');
$labcode= $_POST['labcode'];
$BatchNo = $_POST['BatchNo'];
$msg= $_POST['msg'];
$patient= $_POST['patient'];
$dispatch= $_POST['dispatch'];


		if ( $dispatch!="" && $checkbox !="" )
		{
		
			foreach($checkbox as $a => $b)
			{	//update worksheet items with date dispac v
			
		
					$samplerec = mysql_query("UPDATE samples
					  SET   datereleased = '$datereleased',BatchComplete=1 , DispatchComments='$msg[$a]'
								   WHERE (ID = '$labcode[$a]')")or die(mysql_error());
					/*$samplerec = mysql_query("UPDATE samples
					  SET   datedispatched = '$datedispatched',BatchComplete=1 , DispatchComments='$msg[$a]'
								   WHERE (ID = '$labcode[$a]')")or die(mysql_error());*/
					//update pending tasks
					$samplerec = mysql_query("UPDATE pendingtasks
					  SET   task=2, status = 1,dateupdated='$datereleased'
								   WHERE (sample = '$labcode[$a]' AND batchno = '$BatchNo[$a]')")or die(mysql_error());
					
					 if ($samplerec)
						{
						//save user activity
						$tasktime= date("h:i:s a");
						$todaysdate=date("Y-m-d");
						$utask = 23; //user task = release from system
						
						$activity = SaveUserActivity($userid,$utask,$tasktime,$labcode[$a],$todaysdate);	
						}									
			
			 }
			  if ($samplerec  )
				{
						/*save user activity
						$tasktime= date("h:i:s a");
						$todaysdate=date("Y-m-d");
						$utask = 23; //user task = release from system
						
						$activity = SaveUserActivity($userid,$utask,$tasktime,$patient[$a],$todaysdate);*/
				//$st='<center>'.  'Selected Samples Request No  '. $patient[$a] . ' Dispatch Details Successfuly Updated.</center>';
								$st='<center>'.  'Selected Samples have Successfully been Updated and Released for Printing.</center>';
								
				}
				else
				{
				$rr='<center>'.  'Selected Samples Failed to Update, try again.</center>';
				}
		
		 
		}
		else if ($dispatch!="" && $checkbox =="" ) 
		{ 
		$rr='<center>'. ' <font color="#CC6600">'. "You did not check the confirmed batch, go back and try again ".  "<br>". ' </font>'.'</center>' . 
		" <input name='button' type='button' onclick='history.go(-1)' value='<< Back' align='middle' class='button' '/>";
		
		}


?>
<style type="text/css">
select {
width: 250;}
</style>	

		<div  class="section">
		<div class="section-title"><font style="font-family:Verdana, Arial, Helvetica, sans-serif">DISPATCHED RESULTS &nbsp;&nbsp;|&nbsp;&nbsp;<a href="dispatchedrejectedsamples.php" title="Click to View Rejected Samples that ahve been dispatched"> DISPATCHED REJECTED SAMPLES&nbsp;</a></font></div>
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
		<?php 
		} 
		if ($st !="")
		{
		?> 
			<table   >
			<tr>
			<td style="width:auto" ><div class="success"><?php 
			
			echo  '<strong>'.' <font color="#666600">'.$st.'</strong>'.' </font>';
			
			?></div></th>
			</tr>
			</table><?php
 		} 
	    if ($rr !="")
		{
		?> 
			<table   >
			<tr>
			<td style="width:auto" ><div class="error"><?php 
			
			echo  '<strong>'.' <font color="#666600">'.$rr.'</strong>'.' </font>';
			
			?></div></th>
			</tr>
			</table><?php
 		} 
		if ($smsstatus != '')
		{
			echo '<table><tr><th>';
			if ($smsstatus == 1)//success
			{
				echo '<div class="success">The SMS for $sfacilityname [ Sample Request No: $spid] has been <u>SENT</u>.</div>';
			}
			else if ($smsstatus == 1)//fail
			{
				echo '<th><div class="error">The SMS for $sfacilityname [ Sample Request No: $spid] has <u>NOT</u> been sent.</div>';
			}
			echo '</th></tr></table>';
		}
		?>
		
<table style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px">
	<tr>
	<td><div class="notice">Key:<strong> TAT  </strong> Turn Around Time in Days </div></td>
	<td style="color:#FF0000">This list has been <u><strong>ordered by</strong></u> the Date Dispatched Descending.</td>
	</tr>
</table>
 <?php
 $rowsPerPage = 100; //number of rows to be displayed per page

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
$query = "SELECT ID as 'labcode',batchno,patient,facility,datereceived,datetested,datemodified,result,datedispatched,datereleased,printed,nmrlstampno
   			 FROM samples
			where samples.lab='$labss' AND  samples.BatchComplete=1 and samples.Flag=1 and approved = 1  and resultprinted=1
			ORDER BY datedispatched DESC
			LIMIT $offset, $rowsPerPage";
			
}
else
{

$query = "SELECT ID as 'labcode',batchno,patient,facility,datereceived,datetested,datemodified,result,datedispatched,datereleased,printed,nmrlstampno
		 FROM samples
		where samples.lab='$labss' AND  samples.BatchComplete=1 and samples.Flag=1 and approved = 1  and datereleased !=''
		ORDER BY datereleased DESC
		LIMIT $offset, $rowsPerPage";

}			
			
$queryresult = mysql_query($query) or die(mysql_error()); //for main display
			//$result2 = mysql_query($qury) or die(mysql_error()); //for calculating samples with results and those without

$no=mysql_num_rows($queryresult);



if ($no !=0)
{ ?>
	
	<?php
// print the districts info in table

echo '<table border="0"   class="data-table">
 <tr style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px"><th>NMRL No</th><th>Request No</th><th>Batch No</th><th>Facility</th><th>Province</th><th>Date Received</th><th>Date Tested </th><th>Date Updated </th><th> Result</th><th>Date Released </th><th>Date Dispatched </th><th>TAT</th><th>Printed </th>'.$showtask.'</tr>';

$i = 0; 
while(list($labcode,$batchno,$patient,$facility,$datereceived,$datetested,$datemodified,$result,$datedispatched,$datereleased,$printed,$nmrlstampno) = mysql_fetch_array($queryresult))
	{  
		$i=$i+1;
		//..check if result is printed
		 if ($printed == 0)
		 {
			$printed="<font color='#FF0000'>N</font>";
		 }
		 else
		 {
			$printed =" <strong><font color='#339900'> Y </font></strong>";
		 }
		 
		$facilityname=GetFacility($facility);
		$routcome=GetResultType($result);
		$facilitydetails= getFacilityDetails($facility);
		extract($facilitydetails);
		
		$distid=GetDistrictID($facility);	
		$provid=GetProvid($distid);
		$provname=GetProvname($provid);	
		
		//..sanitize date received
		if (($datereceived != "" ) && ($datereceived != "0000-00-00") && ($datereceived != "1970-01-01"))
		{
			$date_received =date("d-M-Y",strtotime($datereceived));
		}
		else
		{
			$date_received ="";
		}
		//..sanitize date tested
		if (($datetested != "" ) && ($datetested != "0000-00-00") && ($datetested != "1970-01-01"))
		{
			$date_datetested =date("d-M-Y",strtotime($datetested));
		}
		else
		{
			$date_datetested ="";
		}
		//..sanitize date result updated		
		$date_result_updated =date("d-M-Y",strtotime($datemodified));
		
		if ($date_result_updated == '01-Jan-1970')
		{
			$date_result_updated ='';
		}
		//..sanitize date dispatched
		$date_dispatched =date("d-M-Y",strtotime($datedispatched));
		
		if ($date_dispatched == '01-Jan-1970')
		{
			$date_dispatched ='';
		}
		//..sanitize date releaased
		$date_released =date("d-M-Y",strtotime($datereleased));		
		
		if ($date_released == '01-Jan-1970')
		{
			$date_released ='';
		}
		
		$currentdate=date('d-m-Y'); //get current date
		if (($datereceived != "" ) && ($datereceived != "0000-00-00") && ($datereceived != "1970-01-01"))
		{
			$sdrec2 = date("d-m-Y",strtotime($datereceived));
			$date_released2 = date("d-m-Y",strtotime($datereleased));
			$tat=round(getWorkingDays($sdrec2,$date_released2,$holidays)) ;
		 }
		 else
		 {
		 	$tat="";
		 }
		
		//..check TAT value
		if ($tat < 0)
		{	$tat ='';
		}
		//$emailsent=EmailSent($ID);
		 
		 if ($nmrlstampno == 0)
		 {	$nmrlstampno ='<small>N/A</small>';
		 	$fcolor		 ='';}
		 else
		 {	$fcolor		 ='#0000FF';
		 }
		 //..get the result color
		 if ($result == 1)//negative
		 {	$rcolor='#009900';
		 }
		 else if ($result == 2)//positive
		 {	$rcolor='#FF0000';
		 }
		 else
		 {	$rcolor='#990000';
		 }
		 //..get the TAT color
		 if ($tat > 21)
		 {	$tcolor='#FF0000';
		 	$underline = '<u>';
			$endunderline = '</u>';
		 }
		 else
		 {
		 	$tcolor='#0000FF';
		 	$underline = '';
			$endunderline = '';
		 }
?>
<tr style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px">
<!--<td ><?php //echo $i;?></td> -->
<td ><strong><font color="<?php echo $fcolor;?>"><?php echo $nmrlstampno;?></font></strong></td>
<td ><?php echo $patient;?></td>
<td ><strong><div align="center"><a href="BatchDetails.php?ID=<?php echo $batchno;?>&view=1&labview=1"><?php echo $batchno;?></a></div></strong></td>
<td ><?php echo $facilityname;?> </td>
<td ><?php echo $provname;?> </td>
<td ><?php echo $date_received;?></td>
<td ><?php echo $date_datetested;?></td>
<td ><?php echo $date_result_updated;?></td>
<td ><font color="<?php echo $rcolor;?>"><strong><?php echo $routcome;?></strong></font></td>
<td ><?php echo $date_released;?></td>
<td ><?php echo $date_dispatched;?> </td>
<td ><div align="center"><font color="<?php echo $tcolor;?>"><?php echo $underline.$tat.$endunderline;?></font></div> </td>
<td ><div align="center"><?php echo $printed;?></div></td>

<?php
if ($showlink == '1')
{
	//get the facility's sms printer imei and pass
	/*$facilityquery=mysql_query("SELECT imei as pi, pass as pp FROM facilitys where ID='$facility' ")or die(mysql_error()); 
	$dd=mysql_fetch_array($facilityquery);
	$pimei=$dd['pi'];
	$ppass=$dd['pp'];
	
	
	$url = "https://192.168.0.7/manage/api/";
	$msg = "?apiver=1&imei=".$pimei."&password=".$ppass."&action=print&print_message=".$currentdate." SMS Results for ";
	sendcallback($url,$msg);
	
	function sendcallback($scripturl,$message)
	{
		$message=urlencode($message);
		// create curl resource
		$ch = curl_init();
		// set url
		curl_setopt($ch, CURLOPT_URL, '.$scripturl.$message.');
		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// $output contains the output string
		$output = curl_exec($ch);
		// close curl resource to free up system resources
		curl_close($ch);
	}*/
	
	//get the facility's sms printer imei and pass
	$sfacilityquery=mysql_query("SELECT imei as pi, pass as pp FROM facilitys where ID='$facility' ")or die(mysql_error()); 
	$sdd=mysql_fetch_array($sfacilityquery);
	$simei=$sdd['pi'];
	$spassword=$sdd['pp'];
	
	if ($simei != '')
	{//..show link
	$smues = '<a href="smues.php?facility=<?php echo $facility;?>&pid=<?php echo $patient;?>&res=<?php echo $result;?>">SMS</a> | ';
	}


?><td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px" ><?php echo $smues;?><a href="sample_detailsprint.php?view=1&ID=<?php echo $labcode; ?>" target='_blank'> Print </a> | <a href="BatchDetails.php?ID=<?php echo $batchno;?>&view=1&labview=1">View Batch</a>



		<!--<a href=individualresults.php?ID=<?php //echo $labcode; ?> target='_blank'> Print Result  </a>  | --> <?php

		/*if ($ContactEmail !=""  || $email !="") //email address exists
		{?>
		<a href=emailresults.php?ID=<?php echo $labcode; ?> > <img src='../img/email.png' title='Click To Email' alt='Email'> </a> 	
		<?php
		}
		else  //no email address
		{?>
		<a href=editfacility.php?ID=<?php echo $facility; ?> > <img src='../img/q.jpg' title='Click To Add Email ' alt='Add Facility Email'> </a> 	
		<?php
		}*/
		?>
</td>
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
	$numrows=GetTotalPrintedSamples($labss); //get total no of batches

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
		   $prev  = " <a href=\"$self?view=1&page=$page\">Prev  |</a> ";
		
		   $first = " <a href=\"$self?view=1&page=1\">First Page | </a> ";
		}
		else
		{
		   $prev  = '&nbsp;'; // we're on page one, don't print previous link
		   $first = '&nbsp;'; // nor the first page link
		}
		
		if ($pageNum < $maxPage)
		{
		   $page = $pageNum + 1;
		   $next = " <a href=\"$self?view=1&page=$page\"> | Next | </a> ";
		
		   $last = " <a href=\"$self?view=1&page=$maxPage\">  Last Page </a> ";
		}
		else
		{
		   $next = '&nbsp;'; // we're on the last page, don't print next link
		   $last = '&nbsp;'; // nor the last page link
		}
		
		// print the navigation link
		echo '<center><font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px">'. $first . "  ". $prev  ." ". $nav . "  ". $next ."  ". $last .'</font></center>';
	
}

else
{
?>
<table   >
  <tr>
    <td style="width:auto" ><div class="notice"><?php 
		
echo  '<strong>'.' <font color="#666600">'.'No Samples Have Been Dispatched'.'</strong>'.' </font>';

?></div></th>
  </tr>
</table><?php
}  
  ?>
	
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>