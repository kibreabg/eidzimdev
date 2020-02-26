<?php 
require_once('../connection/config.php');
include('../includes/header.php');

$labss = $_SESSION['lab'];

//get the periodic values
$monthly = $_GET['monthly'];
$monthyear = $_GET['monthyear'];

?>

<div>
	<div class="section-title">LAB REPORT FILTER RESULTS</div>
	<div>
	<?php 
	
		//show depending on whether the quarterly has been provided
	if ($monthly != 0)
	{
		//check which month the filter is in
		$showsample = "SELECT s.ID,s.patient,s.facility,s.batchno,s.receivedstatus,s.spots,s.datecollected,s.datereceived,s.datetested,s.datemodified,s.datedispatched,s.result,s.worksheet FROM samples s, facilitys f WHERE MONTH(s.datereceived) = $monthly AND YEAR(s.datereceived) = $monthyear AND s.flag = 1 AND s.facility = f.ID AND f.lab = $labss AND s.repeatt=0 ORDER BY s.datereceived DESC";
		$displaysample = @mysql_query($showsample) or die(mysql_error());
		$showcount = mysql_num_rows($displaysample);//get the search count
			
		//translate the month values to text
			 if ($monthly ==1) {$month = "January";}
		else if ($monthly ==2) {$month = "February";}
		else if ($monthly ==3) {$month = "March";}
		else if ($monthly ==4) {$month = "April";}
		else if ($monthly ==5) {$month = "May";}
		else if ($monthly ==6) {$month = "June";}
		else if ($monthly ==7) {$month = "July";}
		else if ($monthly ==8) {$month = "August";}
		else if ($monthly ==9) {$month = "September";}
		else if ($monthly ==10) {$month = "October";}
		else if ($monthly ==11) {$month = "November";}
		else if ($monthly ==12) {$month = "December";}
		
			if ($showcount != 0) //display search results if search parameter is NOT NULL
			{
						$samplecount = 0;
						
						//filter message
						echo "
						<table border=0>
							<tr >
								<td colspan=8>Samples Received for the month <strong>$month</strong>, <strong>$monthyear</strong> returned <strong>$showcount</strong> results.</td>
								<td>Positives () | Negatives () | Rejected () | Failed ()</td>
								<td>&nbsp;</td>
								<td><a href=\"downloadmonthlyreport.php" ."?month=$monthly&year=$monthyear" . "\" title='Click to Download PDF Report' target='_blank'>Download to Pdf </a> &nbsp;&nbsp;  |&nbsp;&nbsp;  <a href=\"downloadmonthlyexcelreport.php" ."?month=$monthly&year=$monthyear" . "\" title='Click to Download Excel Report' target='_blank'>Download to Excel </a></td>
							</tr>
					
							<tr >
								<td><a href='labreports.php'><strong>Go back to Lab Report Filter.</strong></a></td>
							</tr>
						</table>";
						
						
						
						//display the table and results
						echo "<table border=0 class='data-table'>
				<tr ><th>Count</th><th>Patient</th><th>Facility</th><th>Batch No</th><th>Received Status</th><th>Spots</th><th>Date Collected</th><th>Date Received</th><th>Date Tested</th><th>Date Modified</th><th>Date Dispatched</th><th>Result</th><th>Worksheet</th></tr>";
						
							while(list($ID,$patient,$facility,$batchno,$receivedstatus,$spots,$datecollected,$datereceived,$datetested,$datemodified,$datedispatched,$result,$worksheet) = mysql_fetch_array($displaysample))
							{  
								
								///////////////////////////////////////////////////////////////////////////////
								$getfacilityname = GetFacility($facility);
									///////////////////////////////////////////////////////////////////////////////
								$samplecount = $samplecount + 1;
								 
								 //format the dates to be user friendly
								$datecollected=date("d-M-Y",strtotime($datecollected));
$datereceived=date("d-M-Y",strtotime($datereceived));	
if (($receivedstatus ==2) || (($result < 0 )||($result =="")))
							{
		$datetested="";
		$datemodified="";
		$datedispatched="";
		  $showresult ="";
		  	$showstatus = GetReceivedStatus($receivedstatus);//display received status
							} 
							else
							{
							
							
							$showstatus = GetReceivedStatus($receivedstatus);//display received status	
					   $datetested=date("d-M-Y",strtotime($datetested));
					   $datemodified=date("d-M-Y",strtotime($datemodified));
					 
					   $datereceived4=date("d-m-Y",strtotime($datereceived));
					    $showresult = GetResultType($result);//display the result type
if ($datedispatched !="")
					   {
  $datedispatched=date("d-M-Y",strtotime($datedispatched));	//get sample sample test results
$datedispatched4=date("d-m-Y",strtotime($datedispatched));
$tot = round((strtotime($datedispatched4) - strtotime($datereceived4)) / (60 * 60 * 24));	

 }
else
{
}
}
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
				echo "<center><strong>There are no samples received for the month of <strong>$month</strong>, <strong>$monthyear</strong>.<br><a href='labreports.php'>Please try again.</a></strong></center>";
				exit();
			}
			exit();
		
		
	}
	else if ($monthly == 0)
	{
		echo "<strong><center>Please enter a valid month to filter.</center></strong>";
		exit();
	}
	?>	
	</div>	
</div>

		
 <?php include('../includes/footer.php');?>