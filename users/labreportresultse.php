<?php 
require_once('../connection/config.php');
include('../includes/header.php');

//get the weekly report date filter variables
$weekenddate = $_GET['weekenddate'];
$weekstartdate = $_GET['weekstartdate'];



//get the filter count for the weekly reports
$showsample = "SELECT ID,patient,facility,batchno,receivedstatus,spots,datecollected,datereceived,datetested,datemodified,datedispatched,result,worksheet FROM samples WHERE datereceived BETWEEN '$weekstartdate' AND '$weekenddate' AND flag = 1 ORDER BY datereceived DESC";
$displaysample = @mysql_query($showsample) or die(mysql_error());
$showcount = mysql_num_rows($displaysample);//get the search count
?>

<div>
	<div class="section-title">LAB REPORT FILTER RESULTS</div>
	<div>
	<?php 
	if ($showcount != 0) //display search results if search parameter is NOT NULL
	{
				$samplecount = 0;
				
				$weekstartdate = date("d-M-Y",strtotime($weekstartdate));
				$weekenddate = date("d-M-Y",strtotime($weekenddate));
				
				echo "<table border=0 class='data-table'>
		<tr ><a>Download to Pdf</a> | <a>Download to Excel</a></tr></table>";
				
				echo "The filter for samples received between <strong>$weekstartdate</strong> and <strong>$weekenddate</strong> returned <strong>$showcount</strong> results.<br/> <a href='labreports.php'><strong>Go back to Lab Report Filter.</strong></a>";
				
				
				echo "<table border=0 class='data-table'>
		<tr ><th>Count</th><th>Patient</th><th>Facility</th><th>Batch No</th><th>Received Status</th><th>Spots</th><th>Date Collected</th><th>Date Received</th><th>Date Tested</th><th>Date Modified</th><th>Date Dispatched</th><th>Result</th><th>Worksheet</th></tr>";
				
					while(list($ID,$patient,$facility,$batchno,$receivedstatus,$spots,$datecollected,$datereceived,$datetested,$datemodified,$datedispatched,$result,$worksheet) = mysql_fetch_array($displaysample))
					{  
						$showstatus = GetReceivedStatus($receivedstatus);//display received status
						$showresult = GetResultType($result);//display the result type
						///////////////////////////////////////////////////////////////////////////////
						$getfacilityname = GetFacility($facility);
							///////////////////////////////////////////////////////////////////////////////
						$samplecount = $samplecount + 1;
						 
						 //format the dates to be user friendly
						 $datecollected = date("d-M-Y",strtotime($datecollected));
						 $datereceived = date("d-M-Y",strtotime($datereceived));
						 $datedatecollected = date("d-M-Y",strtotime($datedatecollected));
						 $datetested = date("d-M-Y",strtotime($datetested));
						 $datedispatched = date("d-M-Y",strtotime($datedispatched));
						 $datemodified = date("d-M-Y",strtotime($datemodified));
						 //end date format
						 
							echo "<tr class='even'>
									<td >$samplecount</td>
									<td >$patient</td>
									<td >$getfacilityname</td>
									<td ><a href=\"batchdetails.php" ."?ID=$batchno" . "\" title='Click to view Batch Details'>$batchno</a></td>
									<td >$showstatus</td>
									<td >$spots</td>
									<td >$datecollected</td>
									<td >$datereceived</td>
									<td >$datetested</td>
									<td >$datemodified</td>
									<td >$datedispatched</td>
									<td >$showresult</td>
									<td ><a href=\"worksheetdetails.php" ."?ID=$worksheet" . "\" title='Click to view Worksheets Details'>$worksheet</a></td>
									
									</tr>";
					}echo "</table>";
	}
	else //show message if the search parameter is null
	{
		echo "<center><strong>There are no samples received between $weekstartdate and $weekenddate. <br><a href='labreports.php'>Please try again.</a></strong></center>";
		exit();
	}
	?>	
	</div>	
</div>

		
 <?php include('../includes/footer.php');?>