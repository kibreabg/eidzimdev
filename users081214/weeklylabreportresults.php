<?php 
require_once('../connection/config.php');
include('../includes/header.php');
$labss=$_SESSION['lab'];
//get the weekly report date filter variables
$weekenddate = $_GET['weekenddate'];
$weekstartdate = $_GET['weekstartdate'];

?>

<div>
	<div class="section-title">LAB REPORT FILTER RESULTS</div>
	<div><A HREF='javascript:history.back(-1)'><img src='../img/back.gif' alt='Go Back'/></A>
	<?php 
	
		//get the filter count for the weekly reports
		$showsample = "
		SELECT s.ID,s.patient,s.facility,s.batchno,s.receivedstatus,s.spots,s.datecollected,s.datereceived,s.datetested,s.datemodified,s.datedispatched,s.result,s.worksheet 
		FROM samples s, facilitys f 
		WHERE s.datereceived BETWEEN '$weekstartdate' AND '$weekenddate' AND s.flag = 1 AND s.facility = f.ID AND f.lab = '$labss' AND s.repeatt=0 ORDER BY s.datereceived DESC";
		$displaysample = @mysql_query($showsample) or die(mysql_error());
		$showcount = mysql_num_rows($displaysample);//get the search count

		$weekstartdatee = date("d-M-Y",strtotime($weekstartdate));
		$weekenddatee = date("d-M-Y",strtotime($weekenddate));
		
		if ($showcount != 0) //display search results if search parameter is NOT NULL
		{
					$samplecount = 0;
					
					$weekstartdatee = date("d-M-Y",strtotime($weekstartdate));
					$weekenddatee = date("d-M-Y",strtotime($weekenddate));
					
					//filter message
					echo "
					<table border=0>
						<tr >
							<td width='600'><div class='notice'><small>Samples Received between </small><strong>$weekstartdatee</strong><small> and </small><strong>$weekenddatee</strong> <small>returned </small><strong>$showcount</strong> results.</div></td>
							
							<td>&nbsp;</td>
							<td width='400'><div align='right'><a href='downloadweeklyreport.php?startdate=$weekstartdate&enddate=$weekenddate' title='Click to Download PDF Report' target='_blank'><img src='../img/pdf.jpeg' alt='Pdf'>&nbsp;<small>PDF</small> </a> &nbsp;&nbsp;  |&nbsp;&nbsp;  <a href='downloadweeklyexcel.php?weekstartdate=$weekstartdate&weekenddate=$weekenddate&labss=$labss' title='Click to Download Excel Report' target='_blank'><img src='../img/excel.gif' alt='Excel'>&nbsp;<small>EXCEL</small></a></div></td>
						</tr>
				
						
					</table>";
					
					
					//display the table and results
					echo "<table border=0 class='data-table'>
			<tr ><th>
			</th><th>Sample Request No</th><th>Facility</th><th>Batch No</th><th>Received Status</th><th>Date Collected</th><th>Date Received</th><th>Date Tested</th><th>Date Modified</th><th>Date Dispatched</th><th>Result</th><th>Worksheet</th><th>Task</th></tr>";
					
						while(list($ID,$patient,$facility,$batchno,$receivedstatus,$spots,$datecollected,$datereceived,$datetested,$datemodified,$datedispatched,$result,$worksheet) = mysql_fetch_array($displaysample))
						{  
							$getfacilityname = GetFacility($facility);
							$samplecount = $samplecount + 1;
							$datecollected=date("d-M-Y",strtotime($datecollected));
							$datereceived=date("d-M-Y",strtotime($datereceived));	
							
							if ($receivedstatus != 1)//..if the received status is NOT ACCEPTED then show RED
								{ $fcolor = '#FF0000';}
							else
								{ $fcolor = '';}
							
							if ($result != 1)//..if the received status is NOT NEGATIVE then show RED
								{ $rfcolor = '#FF0000';}
							else
								{ $rfcolor = '';}
									
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
								
								
								$datereceived4=date("d-m-Y",strtotime($datereceived));
								$showresult = GetResultType($result);//display the result type
								
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
								//..sanitize date dispatched
								if (($datedispatched !="") && ($datedispatched != '0000-00-00'))
								{	$datedispatched=date("d-M-Y",strtotime($datedispatched));		
									$datedispatched4=date("d-m-Y",strtotime($datedispatched));
									$tot = round((strtotime($datedispatched4) - strtotime($datereceived4)) / (60 * 60 * 24));								
								}
								else
								{	$datedispatched='';}
							}
							
							
							if ($worksheet == 0) { $worksheet =''; $wlink ='';}
							else { $wlink ="| <a href='worksheetdetails.php?ID=$worksheet' title='Click to view Worksheets Details' target='_blank'>View Worksheet</a>";}
										 
							echo "<tr class='even'>
									<td ><div align='center'>$samplecount</div></td>
									<td >$patient</td>
									<td >$getfacilityname</td>
									<td ><div align='center'><a href='batchdetails.php?view=1&ID=$batchno' title='Click to view Batch Details' target='_blank'><strong>$batchno</strong></a></div></td>
									<td ><div align='center'><font color=$fcolor>$showstatus</font></div></td>
									<td ><div align='center'>$datecollected</div></td>
									<td ><div align='center'>$datereceived</div></td>
									<td ><div align='center'>$datetested</div></td>
									<td ><div align='center'>$datemodified</div></td>
									<td ><div align='center'>$datedispatched</div></td>
									<td ><div align='center'><font color=$rfcolor>$showresult</font></div></td>
									<td ><div align='center'><a href='worksheetdetails.php?ID=$worksheet' title='Click to view Worksheets Details' target='_blank'><strong>$worksheet</strong></a></td>
									<td><small><a href='batchdetails.php?view=1&ID=$batchno' title='Click to view Batch Details' target='_blank'>View Batch</a> $wlink</small></td>
										
										</tr>";
						}echo "</table>";
		}
		else //show message if the search parameter is null
		{
			echo "<center><strong>There are no samples received between $weekstartdatee and $weekenddatee. <br><a href='labreports.php'>Please try again.</a></strong></center>";
			
		}
			
	?>	
	</div>	
</div>

		
 <?php include('../includes/footer.php');?>