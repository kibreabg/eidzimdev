<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment;Filename=NMRL_WEEKLY_REPORT.xls");

require_once('../connection/config.php');
require_once('../includes/functions.php');
//get the filter count for the weekly reports
	
$labss=$_GET['labss'];
$weekenddate = $_GET['weekenddate'];
$weekstartdate = $_GET['weekstartdate'];

//show the records in excel	
					$weekstartdatee = date("d-M-Y",strtotime($weekstartdate));
					$weekenddatee = date("d-M-Y",strtotime($weekenddate));
					
					$showsample = "
						SELECT samples.ID,samples.patient,samples.facility,samples.batchno,samples.receivedstatus,samples.spots,samples.datecollected,samples.datereceived,samples.datetested,samples.datemodified,samples.datedispatched,samples.result,samples.worksheet 
						FROM samples, facilitys
						WHERE samples.datereceived BETWEEN '$weekstartdate' AND '$weekenddate' AND samples.flag = 1 AND samples.facility = facilitys.ID AND facilitys.lab ='$labss' ORDER BY samples.datereceived DESC";
					$displaysample = @mysql_query($showsample) or die(mysql_error());
					$showcount = mysql_num_rows($displaysample);//get the search count
					
					//display the table and results
					echo "<table>
					<tr><td>Start $weekstartdatee</td><td>End $weekenddatee</td><td>Counts $showcount</td></tr>
			<tr ><th>Patient</th><th>Facility</th><th>Batch No</th><th>Received Status</th><th>Date Collected</th><th>Date Received</th><th>Date Tested</th><th>Date Modified</th><th>Date Dispatched</th><th>Result</th><th>Worksheet</th></tr>";
					
					
		
						while(list($ID,$patient,$facility,$batchno,$receivedstatus,$spots,$datecollected,$datereceived,$datetested,$datemodified,$datedispatched,$result,$worksheet) = mysql_fetch_array($displaysample))
						{  
						
							$getfacilityname = GetFacility($facility);
							$datecollected=date("d-M-Y",strtotime($datecollected));
							$datereceived=date("d-M-Y",strtotime($datereceived));	
							
									
							if (($receivedstatus == 2) || (($result < 0 ) || ($result =="")))//..if the status was accepted OR result is blank
							{
								$datetested=""; $datereleased="";
								$datemodified="";
								$datedispatched="";
								$showresult ="";
								$showstatus = GetReceivedStatus($receivedstatus);//display received status
							} 
							else
							{
								$showstatus = GetReceivedStatus($receivedstatus);//display received status	
								
								//..check if the date tested is blank
								if ($datetested != '0000-00-00')
								{
									$datetested=date("d-M-Y",strtotime($datetested));
									$datemodified=date("d-M-Y",strtotime($datemodified));
								}
								else
								{
									$datetested="";
									$datemodified="";
								}
								
								$datereceived4=date("d-m-Y",strtotime($datereceived));
								$showresult = GetResultType($result);//display the result type
															
								//..check if the datedispatched is blank
								if ($datedispatched != '0000-00-00')
								{
									$datedispatched=date("d-M-Y",strtotime($datedispatched));	
									$datedispatched4=date("d-m-Y",strtotime($datedispatched));
									$tot = round((strtotime($datedispatched4) - strtotime($datereceived4)) / (60 * 60 * 24));
									
								}
								else
								{	$datedispatched='';}
							}
								 
							echo "<tr>
									<td >$patient</td>
									<td >$getfacilityname</td>
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
						}
						echo "</table>";
?>
