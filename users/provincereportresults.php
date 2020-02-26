<?php 
require_once('../connection/config.php');
include('../includes/header.php');

$labss = $_SESSION['lab'];

//get the province report date filter variables
$provenddate = $_GET['provenddate'];
$provstartdate = $_GET['provstartdate'];
$province =  $_GET['province'];
?>

<div>
	<div class="section-title">LAB REPORT FILTER RESULTS</div>
	<div>
	<?php 
	if ($province != 0)
	{
		//get the filter count for the weekly reports
			$showsample = "SELECT s.ID,s.patient,s.facility,s.batchno,s.receivedstatus,s.spots,s.datecollected,s.datereceived,s.datetested,s.datemodified,s.datedispatched,s.result,s.worksheet FROM samples s, facilitys f,districts d WHERE s.facility = f.ID AND f.district = d.id AND d.province = $province AND s.datereceived BETWEEN '$provstartdate' AND '$provenddate' AND s.flag = 1 AND f.lab = $labss AND s.repeatt=0 ORDER BY s.datereceived ASC";
			
			$displaysample = @mysql_query($showsample) or die(mysql_error());
			$showcount = mysql_num_rows($displaysample);//get the search count
			
			$provincename = GetProvname($province);
								
						$provstartdatee = date("d-M-Y",strtotime($provstartdate));
						$provenddatee = date("d-M-Y",strtotime($provenddate));
						
			if ($showcount != 0) //display search results if search parameter is NOT NULL
			{
						$samplecount = 0;
						
						echo "
						<table border=0>
							<tr >
								<td width='750'><div class='notice'><small>The filter for samples received from</small> <strong>$provincename</strong> <small>between </small><strong>$provstartdatee</strong> <small>and</small> <strong>$provenddatee</strong> <small>returned </small><strong>$showcount</strong> results. <u><a href='labreports.php'><strong>Go back to Lab Report Filter.</strong></a></ul></div></td>
								<td width=150><div align='right'> <a href='downloadprovincialreport.php?startdate=$provstartdate&enddate=$provenddate&province=$province' title='Click to Download PDF Report' target='_blank'><img src='../img/pdf.jpeg' alt='Pdf'>&nbsp;<small>PDF</small> </a> &nbsp;&nbsp;  |&nbsp;&nbsp;  <a href='downloadprovinceexcel.php?startdate=$provstartdate&enddate=$provenddate&province=$province&labss=$labss' title='Click to Download Excel Report' target='_blank'><img src='../img/excel.gif' alt='Excel'>&nbsp;<small>EXCEL</small></a></div></td>
							</tr>
						</table>";
						
				
						echo "<table border=0 class='data-table'>
				<tr ><th>Count</th><th>Request No</th><th>Facility</th><th>Batch No</th><th>Received Status</th><th>Date Collected</th><th>Date Received</th><th>Date Tested</th><th>Date Modified</th><th>Date Dispatched</th><th>Result</th><th>Worksheet</th><th>Task</th></tr>";
						
							while(list($ID,$patient,$facility,$batchno,$receivedstatus,$spots,$datecollected,$datereceived,$datetested,$datemodified,$datedispatched,$result,$worksheet) = mysql_fetch_array($displaysample))
							{  
								///////////////////////////////////////////////////////////////////////////////								
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
								if ($worksheet == '0') { $worksheet =''; $wlink ='';}
							else { $wlink ="| <a href='worksheetdetails.php?ID=$worksheet' title='Click to view Worksheets Details' target='_blank'>View Worksheet</a>";}
							 
									echo "<tr class='even'>
											<td >$samplecount</td>
											<td >$patient</td>
											<td >$getfacilityname</td>
											<td ><a href='batchdetails.php?ID=$batchno' title='Click to view Batch Details'>$batchno</a></td>
											<td >$showstatus</td>
											<td >$datecollected</td>
											<td >$datereceived</td>
											<td >$datetested</td>
											<td >$datemodified</td>
											<td >$datedispatched</td>
											<td >$showresult</td>
											<td ><a href='worksheetdetails.php?ID=$worksheet' title='Click to view Worksheets Details'>$worksheet</a></td>
											<td ><small><a href='batchdetails.php?view=1&ID=$batchno' title='Click to view Batch Details'>View Batch</a> $wlink</small></td>
											
											</tr>";
							}echo "</table>";
			}
			else //show message if the search parameter is null
			{
				echo "<center><strong>There are no samples received from <strong>$provincename</strong> Province between <strong>$provstartdatee</strong> and <strong>$provenddatee.</strong> <br><a href='labreports.php'>Please try again.</a></strong></center>";
				exit();
			}
			exit();
	}
	else if ($province == 0)
	{
		echo "<center><strong>Please select a valid Province to filter.<br/><a href='labreports.php'>Please try again.</a></strong></center>";
		exit();
	}
	?>	
	</div>	
</div>

		
 <?php include('../includes/footer.php');?>