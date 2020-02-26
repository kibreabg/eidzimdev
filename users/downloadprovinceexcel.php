<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment;Filename=NMRL_PROVINCE_REPORT.xls");

require_once('../connection/config.php');
require_once('../includes/functions.php');
//get the filter count for the weekly reports
	
$labss=$_SESSION['labss'];
$provenddate = $_GET['enddate'];
$provstartdate = $_GET['startdate'];
$province = $_GET['province'];
//show the records in excel	
//get the filter count for the weekly reports
			$showsample = "SELECT samples.ID,samples.patient,samples.facility,samples.batchno,samples.receivedstatus,samples.spots,samples.datecollected,samples.datereceived,samples.datetested,samples.datemodified,samples.datedispatched,samples.result,samples.worksheet 
			FROM samples, facilitys,districts 
			WHERE samples.facility = facilitys.ID AND facilitys.district = districts.id AND districts.province = '$province' AND samples.datereceived BETWEEN '$provstartdate' AND '$provenddate' AND samples.flag = 1 AND facilitys.lab = '$labss' AND samples.repeatt=0 ORDER BY samples.datereceived ASC";
			
			$displaysample = @mysql_query($showsample) or die(mysql_error());
			$showcount = mysql_num_rows($displaysample);//get the search count
			
			$provincename = GetProvname($province);
								
						$provstartdatee = date("d-M-Y",strtotime($provstartdate));
						$provenddatee = date("d-M-Y",strtotime($provenddate));
						
			if ($showcount != 0) //display search results if search parameter is NOT NULL
			{
					echo "
						<table border=0>
							<tr >
								<td colspan=11><small>The filter for samples received from</small> <strong>$provincename</strong> <small>between </small><strong>$provstartdatee</strong> <small>and</small> <strong>$provenddatee</strong> <small>returned </small><strong>$showcount</strong> results. </td>
							</tr>
						</table>";
						
				
					echo "<table border=0 class='data-table'>
				<tr ><th>Count</th><th>Sample Request No</th><th>Facility</th><th>Batch No</th><th>Received Status</th><th>Date Collected</th><th>Date Received</th><th>Date Tested</th><th>Date Modified</th><th>Date Dispatched</th><th>Result</th><th>Worksheet</th></tr>";
						
				while(list($ID,$patient,$facility,$batchno,$receivedstatus,$spots,$datecollected,$datereceived,$datetested,$datemodified,$datedispatched,$result,$worksheet) = mysql_fetch_array($displaysample))
				{  
					$samplecount = $samplecount + 1;
					$getfacilityname = GetFacility($facility);
					
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
							
							$datereceived4=date("d-m-Y",strtotime($datereceived));
							$showresult = GetResultType($result);//display the result type
		
							if ($datedispatched !="")
							{
								$datedispatched=date("d-M-Y",strtotime($datedispatched));	//get sample sample test results
								$datedispatched4=date("d-m-Y",strtotime($datedispatched));
								$tot = round((strtotime($datedispatched4) - strtotime($datereceived4)) / (60 * 60 * 24));	
							
							}
							else
							{}
							//..sanitize date tested
							if (($datetested !="") && ($datetested != '0000-00-00'))
							{	$datetested=date("d-M-Y",strtotime($datetested));										
							}
							else
							{	$datetested='';}
							//..sanitize date modified
							if (($datemodified !="") && ($datemodified != '0000-00-00'))
							{	$datemodified=date("d-M-Y",strtotime($datemodified));										
							}
							else
							{	$datemodified='';}
						}
					 //end date format
					
						echo "<tr>
								<td >$samplecount</td>
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
				}echo "</table>";
			}
			?>
