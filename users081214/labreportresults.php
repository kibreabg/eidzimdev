<?php 
require_once('../connection/config.php');
include('../includes/header.php');

$labss = $_SESSION['lab'];

//get the program report date filter variables
$enddate = $_GET['enddate'];
$startdate = $_GET['startdate'];

//get the facility code
$facility =  $_GET['facility'];
?>

<div>
	<div class="section-title">LAB REPORT FILTER RESULTS</div>
	<div><A HREF='labreports.php'><img src='../img/back.gif' alt='Go Back'/></A>&nbsp;|&nbsp;
	<?php 
	
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
			<tr > 
			<a href='downloadfilterreport.php?startdate=$startdate&enddate=$enddate&fcode=$facility' title='Click to Download PDF Report' target='_blank'><img src='../img/pdf.jpeg' alt='PDF'> <small>PDF</small> </a> &nbsp;&nbsp;  |&nbsp;&nbsp;  <a href='downloadfilterexcel.php?startdate=$startdate&enddate=$enddate&fcode=$facility&labss=$labss' title='Click to Download Excel Report' target='_blank'> <img src='../img/excel.gif' alt='EXCEL'> <small>EXCEL</small> </a>&nbsp;&nbsp; |&nbsp;&nbsp; <a href=\"emailtestresults.php" ."?fcode=$facility" . "\" title='Click to Email Report' target='_blank'><img src='../img/email.png' alt='EMAIL'> <small>EMAIL</small></a>   </tr>
			<tr><td colspan=12>
			<div class='notice'>The filter for samples received from <strong>$getfacilityname</strong> between <strong>$startdatee</strong> and <strong>$enddatee</strong> returned <strong>$showcount</strong> results. <u><a href='labreports.php'><strong>Go back to Lab Report Filter.</strong></a></u></div>
			</td></tr></table>";
					
					
					echo "<table border=0 class='data-table'>
			<tr ><th>Count</th><th>Request No</th><th>Facility</th><th>Batch No</th><th>Received Status</th><th>Date Collected</th><th>Date Received</th><th>Date Tested</th><th>Date Modified</th><th>Date Dispatched</th><th>Result</th><th>Worksheet</th><th>Task</th></tr>";
					
						while(list($ID,$patient,$facility,$batchno,$receivedstatus,$spots,$datecollected,$datereceived,$datetested,$datemodified,$datedispatched,$result,$worksheet) = mysql_fetch_array($displaysample))
						{  
							$showstatus = GetReceivedStatus($receivedstatus);//display received status
							$showresult = GetResultType($result);//display the result type
							///////////////////////////////////////////////////////////////////////////////
							
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
							 
							 if ($worksheet == '0') { $worksheet =''; $wlink ='';}
							else { $wlink ="| <a href='worksheetdetails.php?ID=$worksheet' title='Click to view Worksheets Details' target='_blank'>View Worksheet</a>";}
								
								echo "<tr class='even'>
										<td ><div align='center'>$samplecount</div></td>
										<td >$patient</td>
										<td >$getfacilityname</td>
										<td ><div align='center'><a href='batchdetails.php?ID=$batchno' title='Click to view Batch Details'>$batchno</a></div></td>
										<td ><div align='center'>$showstatus</div></td>
										<td ><div align='center'>$datecollected</div></td>
										<td ><div align='center'>$datereceived</div></td>
										<td ><div align='center'>$datetested</div></td>
										<td ><div align='center'>$datemodified</div></td>
										<td ><div align='center'>$datedispatched</div></td>
										<td ><div align='center'>$showresult</div></td>
										<td ><div align='center'><a href='worksheetdetails.php?ID=$worksheet' title='Click to view Worksheets Details'>$worksheet</a></div></td>
										<td><small><a href='batchdetails.php?ID=$batchno' title='Click to view Batch Details'>View Batch</a> $wlink</small></td>
										</tr>";
						}echo "</table>";
		}
		else //show message if the search parameter is null
		{
			echo "<center><strong>There are no samples received from <strong>$getfacilityname</strong> between <strong>$startdatee</strong> and <strong>$enddatee.</strong> <br><a href='labreports.php'>Please try again.</a></strong></center>";
			exit();
		}
		exit();
	
	?>	
	</div>	
</div>

		
 <?php include('../includes/footer.php');?>