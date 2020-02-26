<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment;Filename=NMRL_FACILITY_REPORT.xls");

require_once('../connection/config.php');
require_once('../includes/functions.php');
//get the filter count for the weekly reports
	
$labss=$_GET['labss'];
$enddate = $_GET['enddate'];
$startdate = $_GET['startdate'];
$facility = $_GET['fcode'];

//show the records in excel	
//get the filter count for the weekly reports
		$showsample = "SELECT s.ID,s.patient,s.facility,s.batchno,s.receivedstatus,s.spots,s.datecollected,s.datereceived,s.datetested,s.datemodified,s.datedispatched,s.result,s.worksheet FROM samples s, facilitys f WHERE s.facility = '$facility' AND s.datereceived BETWEEN '$startdate' AND '$enddate' AND s.flag = 1 AND s.facility = f.ID AND f.lab = $labss  AND s.repeatt = 0 ORDER BY s.datereceived ASC";
		$displaysample = @mysql_query($showsample) or die(mysql_error());
		$showcount = mysql_num_rows($displaysample);//get the search count
	
		$getfacilityname = GetFacility($facility);
		
		if ($showcount != 0) //display search results if search parameter is NOT NULL
		{
					$samplecount = 0;
					
					$startdatee = date("d-M-Y",strtotime($startdate));
					$enddatee = date("d-M-Y",strtotime($enddate));
					
					echo "<table >
			<tr><td colspan=11>
			The filter for samples received from <strong>$getfacilityname</strong> between <strong>$startdatee</strong> and <strong>$enddatee</strong> returned <strong>$showcount</strong> results.
			</td></tr></table>";
					
					
					echo "<table border=0>
			<tr ><th>Count</th><th>Request No</th><th>Facility</th><th>Batch No</th><th>Received Status</th><th>Date Collected</th><th>Date Received</th><th>Date Tested</th><th>Date Modified</th><th>Date Dispatched</th><th>Result</th><th>Worksheet</th></tr>";
					
						while(list($ID,$patient,$facility,$batchno,$receivedstatus,$spots,$datecollected,$datereceived,$datetested,$datemodified,$datedispatched,$result,$worksheet) = mysql_fetch_array($displaysample))
						{  
							$showstatus = GetReceivedStatus($receivedstatus);//display received status
							$showresult = GetResultType($result);//display the result type
							///////////////////////////////////////////////////////////////////////////////
							
								///////////////////////////////////////////////////////////////////////////////
							$samplecount = $samplecount + 1;
							 
							$facilityname = GetFacility($facility);
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
										<td >$facilityname</td>
										<td >$batchno</td>
										<td >$showstatus</td>
										<td >$datecollected</td>
										<td >$datereceived</td>
										<td >$datetested</td>
										<td >$datemodified</td>
										<td >$datedispatched</td>
										<td >$showresult</td>
										<td >$worksheet</td>
										</tr>";
						}echo "</table>";
		}
	?>
