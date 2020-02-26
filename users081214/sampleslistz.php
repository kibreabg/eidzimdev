<?php 
session_start();
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
include('../includes/header.php');
//$labss=$_SESSION['lab'];
$labss ='2';//ZVITAMBO

$success=$_GET['p'];
$datefilter = $_GET['datefilter'];
$fromfilter = $_GET['fromfilter'];
$tofilter = $_GET['tofilter'];

$currentdate = date('Y'); //show the current year
$lowestdate = GetAnyDateMin(); //get the lowest year from date received
?>
<style type="text/css">
select {
width: 250;}
</style>	<script language="javascript" src="calendar.js"></script>

<link type="text/css" href="calendar.css" rel="stylesheet" />	
<SCRIPT language=JavaScript>
function reload(form)
{
var val=form.cat.options[form.cat.options.selectedIndex].value;
self.location='addsample.php?catt=' + val ;
}
</script>

		<div  class="section">
		<div class="section-title"><small><a href="sampleslist.php" style="color:#00CC33">NMRL BATCHES LIST</a> | <a href="sampleslistz.php" style="color:#0000CC">ZVITAMBO BATCHES LIST</a></small> </div>
		<div class="xtop">
		<?php if ($success !="")
		{
		?> 
			<table   >
			<tr>
			<td style="width:auto" ><div class="success"><?php echo  '<strong>'.' <font color="#666600">'.$success.'</strong>'.' </font>'; ?></div></th>
			</tr>
			</table>
		<?php 
		} 


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
		   $qury = "SELECT DISTINCT batchno
					FROM samples
					WHERE samples.lab='$labss' AND samples.Flag=1
					ORDER BY datereceived DESC, batchno desc
					LIMIT $offset, $rowsPerPage";
					
					$result = mysql_query($qury) or die(mysql_error()); //for main display
					$result2 = mysql_query($qury) or die(mysql_error()); //for calculating samples with results and those without
		
					$no=mysql_num_rows($result);
					
if ($no > 0)
{ 
	
?>
		

<!--show the filter table------------------------------------------------------------------------------------------------------------------------------->
	<div>
		<form name="filterform" action="">
		<table border="0"   >
		<tr>
		
	
		<td>Select Date</td>
		<td>
		<?php
		  $myCalendar = new tc_calendar("datefilter", true, false);
		  $myCalendar->setIcon("../img/iconCalendar.gif");
		  $myCalendar->setDate(date('d'), date('m'), date('Y'));
		  $myCalendar->setPath("./");
		  $myCalendar->setYearInterval($lowestdate, $currentdate);
		  //$myCalendar->dateAllow('2008-05-13', '2015-03-01');
		  $myCalendar->setDateFormat('j F Y');
		  //$myCalendar->setHeight(350);	  
		  //$myCalendar->autoSubmit(true, "form1");
		  $myCalendar->writeScript();
		  ?>
		<input type="submit" name="submitdate" value="Filter" class="button"/><br/>
		</td>
		<td colspan="2"> | </td>
		<td>Select Date Range: From</td>
		<td><?php
		  $myCalendar = new tc_calendar("fromfilter", true, false);
		  $myCalendar->setIcon("../img/iconCalendar.gif");
		  $myCalendar->setDate(date('d'), date('m'), date('Y'));
		  $myCalendar->setPath("./");
		  $myCalendar->setYearInterval($lowestdate,$currentdate);
		  //$myCalendar->dateAllow('2008-05-13', '2015-03-01');
		  $myCalendar->setDateFormat('j F Y');
		  //$myCalendar->setHeight(350);	  
		  //$myCalendar->autoSubmit(true, "form1");
		  $myCalendar->writeScript();
		  ?></td>
		<td>To</td>
		<td><?php
		  $myCalendar = new tc_calendar("tofilter", true, false);
		  $myCalendar->setIcon("../img/iconCalendar.gif");
		  $myCalendar->setDate(date('d'), date('m'), date('Y'));
		  $myCalendar->setPath("./");
		  $myCalendar->setYearInterval($lowestdate, $currentdate);
		  //$myCalendar->dateAllow('2008-05-13', '2015-03-01');
		  $myCalendar->setDateFormat('j F Y');
		  //$myCalendar->setHeight(350);	  
		  //$myCalendar->autoSubmit(true, "form1");
		  $myCalendar->writeScript();
		  ?></td>
		  <td>
		  <input type="submit" name="submitfrom" value="Filter" class="button"/><br/>
		  </td>
		</tr>
		</table>
		</form>
	</div>
<!-------------------------------------------------------------------------------------------------------------------------------------------------- -->
	<?php
	
	if ($_REQUEST['submitdate'])
	{			
		$Limit = 100; //Number of results per page
		$page=$_GET["page"]; //Get the page number to show
		If($page == "") $page=1; //If no page number is set, the default page is 1
		$datefilter=date("Y-m-d",strtotime($datefilter));
		
		$showsample = "SELECT DISTINCT(samples.batchno) FROM samples WHERE samples.datereceived = '$datefilter' AND samples.Flag = 1 AND samples.lab='$labss' LIMIT " . ($page-1)*$Limit . ",$Limit";
			//for pagination
		$showsamplefornaviagtion = "SELECT DISTINCT(samples.batchno) FROM samples WHERE samples.datereceived = '$datefilter' AND samples.flag = 1 AND samples.lab='$labss' ";
								
				$displaysample = @mysql_query($showsample) or die(mysql_error());
				$displaysample2 = @mysql_query($showsamplefornaviagtion) or die(mysql_error());
				$showcount = mysql_num_rows($displaysample2);//get the search count
				
				if ($showcount!=0) //display table is count is NOT 0
				{
					echo "The Batch filter for <strong>".date("d-M-Y",strtotime($datefilter))."</strong> returned <strong>".$showcount." </strong>results.&nbsp;&nbsp;&nbsp;";
					echo '<strong><a href="sampleslistz.php">Click to Refresh Page</a></strong>';
				
					echo "<table border='0'   class='data-table'>
					
					 <tr ><th>Batch No</th><th>Referring Clinic / Hospital Name</th><th>Date Received</th>
							 <th><small><font color='#0000FF'>No. of Samples</font></small></th>
							 <th><small><font color='#336600'>Samples With Results</font></small></th>
							 <th>Samples With No Results</th>
							 <th>Task</th></tr>";
					
					
					while(list($batchno) = mysql_fetch_array($displaysample))
					{  
							//get patient/sample code
							$patient=GetPatient($batchno,$labss);
							//get bach received date
							$sdrec=GetDatereceived($batchno,$labss);
							//get patient gender and mother id based on sample code of sample
							$mid=GetMotherID($patient);
							//get atient gender
							$pgender=GetPatientGender($patient);
							//get sample facility code based  on mothers id
							$facility=GetFacilityCode($batchno,$labss);
							//get sample facility name based on facility code
							$facilityname=GetFacility($facility);
							//count no. of samples per batch
							$num_samples=GetSamplesPerBatch($batchno,$labss);
							//count no. of samples per batch that are rejected
							$rej_samples=GetRejectedSamplesPerBatch($batchno,$labss);
							//no of saMPLES IN BATCH with results
							$with_result_samples=GetSamplesPerBatchwithResults($batchno,$labss);
							//count no. of samples per batch that are not received
							$notrec_samples=GetNotReceivedSamplesPerBatch($batchno,$labss);
							////no of saMPLES IN BATCH without results
							$no_result_samples = (($num_samples - $with_result_samples) - ($rej_samples + $notrec_samples));
					
					
						echo "<tr class='even'>
								<td ><div align='center'>$batchno</div></td>
								<td >$facilityname</td>
								<td ><div align='center'>$sdrec</div> </td>
								<td ><div align='center'> $num_samples</div></td>
								<td ><div align='center'> $with_result_samples</div></td>
								<td ><div align='center'>$no_result_samples</div> </td>
								<td ><a href='BatchDetails.php?ID=$batchno&labview=$labss' title='Click to view Samples in this batch'>View Samples </a> 
								</td>
						</tr>";
					} 
					echo '</table>';
					//get total no of batches
	
					// how many pages we have when using paging?
								$NumberOfPages = ceil($showcount/$rowsPerPage);
								
								
								$Nav="";
								if($pageNum > 1) {
								$Nav .= "<A HREF=\"sampleslistz.php?page=" . ($pageNum-1) . "&datefilter=" .urlencode($datefilter) . "\"><<  Prev  </A>";
								}
								for($i = 1 ; $i <= $NumberOfPages ; $i++) {
								if($i == $pageNum) {
								$Nav .= "<B>  $i  </B>";
								}else{
								$Nav .= "<A HREF=\"sampleslistz.php?page=" . $i . "&datefilter=" .urlencode($datefilter) . "\">  $i  </A>";
								}
								}
								if($pageNum < $NumberOfPages) {
								$Nav .= "<A HREF=\"sampleslistz.php?page=" . ($pageNum+1) . "&datefilter=" .urlencode($datefilter) . "\">  Next   >></A>";
								}
								echo '<center>';
								echo "<BR> <BR>" . $Nav; 
								echo '<center>';
											
								exit();	
					
					//end show search results
				}
				else //display message of count IS 0
				{	
					echo "The Batch filter for <strong>".date("d-M-Y",strtotime($datefilter))."</strong> returned <strong> 0 </strong>results.";
					
					echo '<strong><a href="sampleslistz.php">Click to Refresh Page</a></strong>';
				
					exit();
				}
		
	}
	else if ($_REQUEST['submitfrom'])
	{
		$Limit = 100; //Number of results per page
		$page=$_GET["page"]; //Get the page number to show
		If($page == "") $page=1; //If no page number is set, the default page is 1$datefilter=date("Y-m-d",strtotime($datefilter));
		
				//change the date format
				$fromfilter=date("Y-m-d",strtotime($fromfilter));
				$tofilter=date("Y-m-d",strtotime($tofilter));
				
				$showsample = "SELECT DISTINCT(samples.batchno) FROM samples WHERE samples.datereceived BETWEEN '$fromfilter' AND '$tofilter' AND samples.Flag = 1 AND samples.lab='$labss' LIMIT " . ($page-1)*$Limit . ",$Limit";
				$showsamplefornavigation = "SELECT DISTINCT(samples.batchno) FROM samples WHERE samples.datereceived BETWEEN '$fromfilter' AND '$tofilter' AND samples.flag = 1 AND samples.lab='$labss' ";
					
					
				$displaysample = @mysql_query($showsample) or die(mysql_error());
				$displaysample2 = @mysql_query($showsamplefornavigation) or die(mysql_error());
				$showcount = mysql_num_rows($displaysample2);//get the search count
				
				if ($showcount!=0) //display table is count is NOT 0
				{	
					echo "The Batch filter between <strong>".date("d-M-Y",strtotime($fromfilter))."</strong> and <strong>".date("d-M-Y",strtotime($tofilter))."</strong> returned 	<strong>".$showcount." </strong>results.&nbsp;&nbsp;&nbsp;";
					echo '<strong><a href="sampleslistz.php">Click to Refresh Page</a></strong>';
					
					echo "<table border='0'   class='data-table'>
									
					
	 		<tr ><th>Batch No</th><th>Referring Clinic / Hospital Name</th><th>Date Received</th>
		 	 <th><small><font color='#0000FF'>No. of Samples</font></small></th>
			 <th><small><font color='#336600'>Samples With Results</font></small></th>
			 <th>Samples With No Results</th>
			 <th>Task</th></tr>";
					
					
					while(list($batchno) = mysql_fetch_array($displaysample))
					{  
							//get patient/sample code
							$patient=GetPatient($batchno,$labss);
							//get bach received date
							$sdrec=GetDatereceived($batchno,$labss);
							//get patient gender and mother id based on sample code of sample
							$mid=GetMotherID($patient);
							//get atient gender
							$pgender=GetPatientGender($patient);
							//get sample facility code based  on mothers id
							$facility=GetFacilityCode($batchno,$labss);
							//get sample facility name based on facility code
							$facilityname=GetFacility($facility);
							//count no. of samples per batch
							$num_samples=GetSamplesPerBatch($batchno,$labss);
							//count no. of samples per batch that are rejected
							$rej_samples=GetRejectedSamplesPerBatch($batchno,$labss);
							//no of saMPLES IN BATCH with results
							$with_result_samples=GetSamplesPerBatchwithResults($batchno,$labss);
							//count no. of samples per batch that are not received
							$notrec_samples=GetNotReceivedSamplesPerBatch($batchno,$labss);
							////no of saMPLES IN BATCH without results
							$no_result_samples = (($num_samples - $with_result_samples) - ($rej_samples + $notrec_samples));
					
					
						echo "<tr class='even'>
								<td >$batchno</div></td>
								<td >$facilityname</td>
								<td ><div align='center'>$sdrec</div> </td>
								<td ><div align='center'> $num_samples</div></td>
								<td ><div align='center'> $with_result_samples</div></td>
								<td ><div align='center'>$no_result_samples</div> </td>
								<td ><a href='BatchDetails.php?ID=$batchno&labview=$labss' title='Click to view Samples in this batch'>View Samples </a> 
								</td>
						</tr>";
					} 
					echo '</table>';
					echo '<br>';
					 //get total no of batches
				
					// how many pages we have when using paging?
					
					// how many pages we have when using paging?
					$NumberOfPages = ceil($showcount/$rowsPerPage);
					
					
					$Nav="";
					if($pageNum > 1) {
					$Nav .= "<A HREF=\"sampleslistz.php?page=" . ($pageNum-1) . "&fromfilter=" .urlencode($fromfilter). "&tofilter=" .urlencode($tofilter) . "\"><<  Prev  </A>";
					}
					for($i = 1 ; $i <= $NumberOfPages ; $i++) {
					if($i == $pageNum) {
					$Nav .= "<B>  $i  </B>";
					}else{
					$Nav .= "<A HREF=\"sampleslistz.php?page=" . $i . "&fromfilter=" .urlencode($fromfilter). "&tofilter=" .urlencode($tofilter) . "\">  $i  </A>";
					}
					}
					if($pageNum < $NumberOfPages) {
					$Nav .= "<A HREF=\"sampleslistz.php?page=" . ($pageNum+1) . "&fromfilter=" .urlencode($fromfilter). "&tofilter=" .urlencode($tofilter) . "\">  Next   >></A>";
					}
					echo '<center>';
					echo "<BR> <BR>" . $Nav; 
					echo '<center>';
					
					exit();
					
					//end show search results
				}
				else //display message of count IS 0
				{	
						echo "The Batch filter between <strong>".date("d-M-Y",strtotime($fromfilter))."</strong> and </strong>".date("d-M-Y",strtotime($fromfilter))."</strong> returned <strong> 0 </strong>results.&nbsp;&nbsp;&nbsp;";
					
					echo '<strong><a href="sampleslistz.php">Click to Refresh Page</a></strong>';
				
					exit();
				}
		
	}
	?>
<!------------------------------------------------------------------------------------------------------------------------------------------------->
<?php
/*
echo '<strong>'."<a href=\"allretests.php". "\" title='Click to view all  Positive Samples that were retested'> All Positive Repeats  List </a>  ". " | "."<a href=\"allrepeatforrejectiontests.php". "\" title='Click to view all  Samples that were repeated based on Rejection in first test'> All Repeats for Rejection List </a>  ". " | "."<a href=\"allconfirmatorytests.php". "\" title='Click to view all  confirmatory tests at 9 months'> All Confirmatory Test at 9 months  List </a>  "." | "."<a href=\"rejectedsamples.php". "\" title='Click to view all  Rejected Samples'> All Rejected Samples </a>  ".    '</strong>';
echo '<br>';
*/
		echo "<p>
		<strong><small><font color='#0000CC'>ZVITAMBO </font>Total Samples : " .Gettotalsamples($labss).'</small></strong></p>';
	// print the districts info in table
		echo '<table border="0"   class="data-table">
		 <tr ><th>Batch No</th><th>Referring Clinic / Hospital Name</th><th>Date Received</th>
		 	 <th><small><font color="#0000FF">No. of Samples</font></small></th>
			 <th><small><font color="#336600">Samples With Results</font></small></th>
			 <th>Samples With No Results</th>
			 <th>Task</th></tr>';
			while(list($batchno) = mysql_fetch_array($result))
			{  
				//get patient/sample code
				$patient=GetPatient($batchno,$labss);
				//get bach received date
				$sdrec=GetDatereceived($batchno,$labss);
				//get patient gender and mother id based on sample code of sample
				$mid=GetMotherID($patient);
				//get atient gender
				$pgender=GetPatientGender($patient);
				//get sample facility code based  on mothers id
				$facility=GetFacilityCode($batchno,$labss);
				//get sample facility name based on facility code
				$facilityname=GetFacility($facility);
				//count no. of samples per batch
				$num_samples=GetSamplesPerBatch($batchno,$labss);
				//count no. of samples per batch that are rejected
				$rej_samples=GetRejectedSamplesPerBatch($batchno,$labss);
				//no of saMPLES IN BATCH with results
				$with_result_samples=GetSamplesPerBatchwithResults($batchno,$labss);
				//count no. of samples per batch that are not received
				$notrec_samples=GetNotReceivedSamplesPerBatch($batchno,$labss);
				////no of saMPLES IN BATCH without results
				$no_result_samples = (($num_samples - $with_result_samples) - ($rej_samples + $notrec_samples));
		
		
			echo "<tr class='even'>
					<td ><div align='center'>$batchno</div></td>
					<td >$facilityname</td>
					<td ><div align='center'>$sdrec</div> </td>
					<td ><div align='center'> $num_samples</div></td>
					<td ><div align='center'> $with_result_samples</div></td>
					<td ><div align='center'>$no_result_samples</div> </td>
					<td ><a href='BatchDetails.php.?ID=$batchno&view=1&labview=$labss' title='Click to view Samples in this batch'>View Samples </a> 
					</td>
			</tr>";
			}
			echo '</table>';

	
		echo '<br>';
		$numrows=GetTotalNoBatches($labss); //get total no of batches
	
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

else if ($no == 0)
{?>
	<table   >
	<tr>
	<td style="width:auto" > <div class="notice"> <?php echo  '<strong>'.' <font color="#666600">'. 'No Samples Added'.'</strong>'.' </font>';?></div> </td>
	</tr>
	</table>
<?php 
} ?>
	
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>