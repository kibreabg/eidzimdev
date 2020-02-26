<?php 
require_once('../connection/config.php');
include('../includes/header.php');

$labss = $_SESSION['lab'];

//get the periodic values
$quarterly = $_GET['quarterly'];
$quarteryear = $_GET['quarteryear'];

?>

<div>
	<div class="section-title">LAB REPORT FILTER RESULTS</div>
	<div>
	<?php 
	
		//show depending on whether the quarterly has been provided
	if ($quarterly != 0)
	{
		//check which quarter the filter is in
		if ($quarterly == 1 ) //january - March
		{
			$showsample = "SELECT s.ID,s.patient,s.facility,s.batchno,s.receivedstatus,s.spots,s.datecollected,s.datereceived,s.datetested,s.datemodified,s.datedispatched,s.result,s.worksheet FROM samples s, facilitys f WHERE MONTH(s.datereceived) BETWEEN 1 AND 3 AND YEAR(s.datereceived) = $quarteryear AND s.flag = 1 AND s.flag = 1 AND s.facility = f.ID AND f.lab = $labss AND s.repeatt=0 ORDER BY s.datereceived DESC ";
			$displaysample = @mysql_query($showsample) or die(mysql_error());
			$showcount = mysql_num_rows($displaysample);//get the search count
			
			if ($showcount != 0) //display search results if search parameter is NOT NULL
			{
						$samplecount = 0;
						
					/*
						<td><a href='downloadperiodicreport.php?quarter=$quarterly&quarteryear=$quarteryear' title='Click to Download PDF Report' target='_blank'>Download to Pdf </a> &nbsp;&nbsp;  |&nbsp;&nbsp;  <a href='downloadperiodicexcelreport.php?quarter=$quarterly&quarteryear=$quarteryear' title='Click to Download Excel Report' target='_blank'>Download to Excel </a></td>*/
						//filter message
						echo "
						<table border=0>
							<tr >
								<td colspan=8><div class='notice'>Samples Received for the quarter <strong> Jan - Mar  </strong> ,<strong> $quarteryear </strong> returned <strong>$showcount</strong> results.</div></td>
								
								<td>&nbsp;</td>
								
								<td width='150px'><div align='right'> <a href='downloadperiodicreport.php?quarter=$quarterly&quarteryear=$quarteryear' title='Click to Download PDF Report' target='_blank'><img src='../img/pdf.jpeg' alt='Pdf'>&nbsp;<small>PDF</small> </a> &nbsp;&nbsp;  |&nbsp;&nbsp;  <a href='downloadperiodicexcelreport.php?quarter=$quarterly&quarteryear=$quarteryear' title='Click to Download Excel Report' target='_blank'><img src='../img/excel.gif' alt='Excel'>&nbsp;<small>EXCEL</small></a></div></td>
							</tr>
						</table>";
						
						
						
						//display the table and results
						echo "<table border=0 class='data-table'>
				<tr ><th></th><th>Sample Request No</th><th>Facility</th><th>Batch No</th><th>Received Status</th><th>Spots</th><th>Date Collected</th><th>Date Received</th><th>Date Tested</th><th>Date Modified</th><th>Date Dispatched</th><th>Result</th><th>Worksheet</th></tr>";
						
							while(list($ID,$patient,$facility,$batchno,$receivedstatus,$spots,$datecollected,$datereceived,$datetested,$datemodified,$datedispatched,$result,$worksheet) = mysql_fetch_array($displaysample))
							{  
							
								$getfacilityname = GetFacility($facility);
								$samplecount = $samplecount + 1;
								 
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
				echo "<center><strong>There are no samples received for the quarter <strong>January - March</strong>, <strong>$quarteryear</strong>.<br><a href='labreports.php'>Please try again.</a></strong></center>";
				exit();
			}
			exit();
			
		}
		else if ($quarterly == 2) //april - june
		{
			$showsample = "SELECT s.ID,s.patient,s.facility,s.batchno,s.receivedstatus,s.spots,s.datecollected,s.datereceived,s.datetested,s.datemodified,s.datedispatched,s.result,s.worksheet FROM samples s, facilitys f WHERE MONTH(s.datereceived) BETWEEN 4 AND 6 AND YEAR(s.datereceived) = $quarteryear AND s.flag = 1 AND s.facility = f.ID AND f.lab = $labss AND s.repeatt=0 ORDER BY s.datereceived DESC";
			$displaysample = @mysql_query($showsample) or die(mysql_error());
			$showcount = mysql_num_rows($displaysample);//get the search count
			
			if ($showcount != 0) //display search results if search parameter is NOT NULL
			{
						$samplecount = 0;
						
						$weekstartdate = date("d-M-Y",strtotime($weekstartdate));
						$weekenddate = date("d-M-Y",strtotime($weekenddate));
						
						//filter message
						echo "
						<table border=0>
							<tr >
								<td colspan=8>Samples Received for the quarter <strong>April - June</strong>, <strong>$quarteryear</strong> returned <strong>$showcount</strong> results.</td>
								
								<td>&nbsp;</td>
								<td width='150px'><div align='right'> <a href='downloadperiodicreport.php?quarter=$quarterly&quarteryear=$quarteryear' title='Click to Download PDF Report' target='_blank'><img src='../img/pdf.jpeg' alt='Pdf'>&nbsp;<small>PDF</small> </a> &nbsp;&nbsp;  |&nbsp;&nbsp;  <a href='downloadperiodicexcelreport.php?quarter=$quarterly&quarteryear=$quarteryear' title='Click to Download Excel Report' target='_blank'><img src='../img/excel.gif' alt='Excel'>&nbsp;<small>EXCEL</small></a></div></td>
							</tr>
						</table>";
						
						
						
						//display the table and results
						echo "<table border=0 class='data-table'>
				<tr ><th></th><th>Sample Request No</th><th>Facility</th><th>Batch No</th><th>Received Status</th><th>Spots</th><th>Date Collected</th><th>Date Received</th><th>Date Tested</th><th>Date Modified</th><th>Date Dispatched</th><th>Result</th><th>Worksheet</th></tr>";
						
							while(list($ID,$patient,$facility,$batchno,$receivedstatus,$spots,$datecollected,$datereceived,$datetested,$datemodified,$datedispatched,$result,$worksheet) = mysql_fetch_array($displaysample))
							{  
								$getfacilityname = GetFacility($facility);
								$samplecount = $samplecount + 1;
								 
								 //format the dates to be user friendly
								 $datecollected = date("d-M-Y",strtotime($datecollected));
								 $datereceived = date("d-M-Y",strtotime($datereceived));
								 $datedatecollected = date("d-M-Y",strtotime($datedatecollected));								
								 //end date format
								 
								 if (($receivedstatus ==2) || (($result < 0 )||($result =="")))
								{
									$datetested="";
									$datemodified="";
									$datedispatched="";
									$showresult="";
									$showstatus = GetReceivedStatus($receivedstatus);//display received status
								} 
								else
								{
									$showstatus = GetReceivedStatus($receivedstatus);//display received status
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
				echo "<center><strong>There are no samples received for the quarter <strong>April - June</strong> , <strong>$quarteryear</strong><br><a href='labreports.php'>Please try again.</a></strong></center>";
				exit();
			}
			exit();
		}
		else if ($quarterly == 3) //july - september
		{
			$showsample = "SELECT s.ID,s.patient,s.facility,s.batchno,s.receivedstatus,s.spots,s.datecollected,s.datereceived,s.datetested,s.datemodified,s.datedispatched,s.result,s.worksheet FROM samples s, facilitys f WHERE MONTH(s.datereceived) BETWEEN 7 AND 9 AND YEAR(s.datereceived) = $quarteryear AND s.flag = 1 AND s.facility = f.ID AND f.lab = $labss AND s.repeatt=0 ORDER BY s.datereceived DESC";
			$displaysample = @mysql_query($showsample) or die(mysql_error());
			$showcount = mysql_num_rows($displaysample);//get the search count
			
			if ($showcount != 0) //display search results if search parameter is NOT NULL
			{
						$samplecount = 0;
						
						$weekstartdate = date("d-M-Y",strtotime($weekstartdate));
						$weekenddate = date("d-M-Y",strtotime($weekenddate));
						
						//filter message
						echo "
						<table border=0>
							<tr >
								<td colspan=8>Samples Received for the quarter <strong>July - September</strong> , <strong>$quarteryear</strong> returned <strong>$showcount</strong> results.</td>
								
								<td>&nbsp;</td>
								<td width='150px'><div align='right'> <a href='downloadperiodicreport.php?quarter=$quarterly&quarteryear=$quarteryear' title='Click to Download PDF Report' target='_blank'><img src='../img/pdf.jpeg' alt='Pdf'>&nbsp;<small>PDF</small> </a> &nbsp;&nbsp;  |&nbsp;&nbsp;  <a href='downloadperiodicexcelreport.php?quarter=$quarterly&quarteryear=$quarteryear' title='Click to Download Excel Report' target='_blank'><img src='../img/excel.gif' alt='Excel'>&nbsp;<small>EXCEL</small></a></div></td>
							</tr>
					
						</table>";
						
						
						
						//display the table and results
						echo "<table border=0 class='data-table'>
				<tr ><th></th><th>Sample Request No</th><th>Facility</th><th>Batch No</th><th>Received Status</th><th>Spots</th><th>Date Collected</th><th>Date Received</th><th>Date Tested</th><th>Date Modified</th><th>Date Dispatched</th><th>Result</th><th>Worksheet</th></tr>";
						
							while(list($ID,$patient,$facility,$batchno,$receivedstatus,$spots,$datecollected,$datereceived,$datetested,$datemodified,$datedispatched,$result,$worksheet) = mysql_fetch_array($displaysample))
							{  
								$getfacilityname = GetFacility($facility);
								$samplecount = $samplecount + 1;
								 
								 //format the dates to be user friendly
								 $datecollected = date("d-M-Y",strtotime($datecollected));
								 $datereceived = date("d-M-Y",strtotime($datereceived));
								 $datedatecollected = date("d-M-Y",strtotime($datedatecollected));
								//end date format
								 
								 if (($receivedstatus ==2) || (($result < 0 )||($result =="")))
								{
									$datetested="";
									$datemodified="";
									$datedispatched="";
									$showresult="";
									$showstatus = GetReceivedStatus($receivedstatus);//display received status
								} 
								else
								{ 
									$showstatus = GetReceivedStatus($receivedstatus);//display received status
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
				echo "<center><strong>There are no samples received for the quarter <strong>July - September</strong> , <strong>$quarteryear</strong><br><a href='labreports.php'>Please try again.</a></strong></center>";
				exit();
			}
			exit();
		}
		else if ($quarterly == 4) //october - december
		{
				$showsample = "SELECT s.ID,s.patient,s.facility,s.batchno,s.receivedstatus,s.spots,s.datecollected,s.datereceived,s.datetested,s.datemodified,s.datedispatched,s.result,s.worksheet FROM samples s, facilitys f WHERE MONTH(s.datereceived) BETWEEN 10 AND 12 AND YEAR(s.datereceived) = $quarteryear AND s.flag = 1 AND s.facility = f.ID AND f.lab = $labss AND s.repeatt=0 ORDER BY s.datereceived DESC";
			$displaysample = @mysql_query($showsample) or die(mysql_error());
			$showcount = mysql_num_rows($displaysample);//get the search count
			
			if ($showcount != 0) //display search results if search parameter is NOT NULL
			{
						$samplecount = 0;
						
						$weekstartdate = date("d-M-Y",strtotime($weekstartdate));
						$weekenddate = date("d-M-Y",strtotime($weekenddate));
						
						//filter message
						echo "
						<table border=0>
							<tr >
								<td colspan=8>Samples Received for the quarter <strong>October - December</strong> , <strong>$quarteryear</strong> returned <strong>$showcount</strong> results.</td>
								
								<td>&nbsp;</td>
								<td width='150px'><div align='right'> <a href='downloadperiodicreport.php?quarter=$quarterly&quarteryear=$quarteryear' title='Click to Download PDF Report' target='_blank'><img src='../img/pdf.jpeg' alt='Pdf'>&nbsp;<small>PDF</small> </a> &nbsp;&nbsp;  |&nbsp;&nbsp;  <a href='downloadperiodicexcelreport.php?quarter=$quarterly&quarteryear=$quarteryear' title='Click to Download Excel Report' target='_blank'><img src='../img/excel.gif' alt='Excel'>&nbsp;<small>EXCEL</small></a></div></td>
							</tr>
						</table>";
						
						
						
						//display the table and results
						echo "<table border=0 class='data-table'>
				<tr ><th></th><th>Sample Request No</th><th>Facility</th><th>Batch No</th><th>Received Status</th><th>Spots</th><th>Date Collected</th><th>Date Received</th><th>Date Tested</th><th>Date Modified</th><th>Date Dispatched</th><th>Result</th><th>Worksheet</th></tr>";
						
							while(list($ID,$patient,$facility,$batchno,$receivedstatus,$spots,$datecollected,$datereceived,$datetested,$datemodified,$datedispatched,$result,$worksheet) = mysql_fetch_array($displaysample))
							{  
								$getfacilityname = GetFacility($facility);
								$samplecount = $samplecount + 1;
								
								$datecollected=date("d-M-Y",strtotime($datecollected));
								$datereceived=date("d-M-Y",strtotime($datereceived));	

								if (($receivedstatus ==2) || (($result < 0 )||($result =="")))
								{
									$datetested="";
									$datemodified="";
									$datedispatched="";
									$showresult="";
									$showstatus = GetReceivedStatus($receivedstatus);//display received status
								} 
								else
								{
								  	$showstatus = GetReceivedStatus($receivedstatus);//display received status
									$showresult = GetResultType($result);//display the result type
								   
								   $datereceived4=date("d-m-Y",strtotime($datereceived));
					   
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
				echo "<center><strong>There are no samples received for the quarter <strong>October - December</strong> , <strong>$quarteryear</strong><br><a href='labreports.php'>Please try again.</a></strong></center>";
				exit();
			}
			exit();
		}
		
	}
	else if ($quarterly == 0)
	{
		echo "<strong><center>Please select a valid Period to filter.</center></strong>";
		exit();
	}
			
	
	?>	
	</div>	
</div>

		
 <?php include('../includes/footer.php');?>