<?php
session_start();
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment;Filename=NMRL_QUARTERLY_REPORT.xls");

	include("../connection/config.php");
	include('../includes/functions.php');


    $quarterly=$_GET['quarter'];
	$quarteryear=$_GET['quarteryear'];
	$labss = $_SESSION['lab'];
	
		if ($quarterly ==1)
		{
		$quotaname="Jan - Mar";
		}
		else if ($quarterly ==2)
		{
		$quotaname="Apr - June";
		}
		else if ($quarterly ==3)
		{
		$quotaname=" July- Sept";
		}
		else 
		{
		$quotaname="Oct - Dec";
		}
		
		if ($quarterly == 1 ) //january - March
		{
		$pstartdate = 1;
		$penddate = 3;		
		/*
			$qury = "SELECT s.ID,s.patient,f.name,s.batchno,s.receivedstatus,s.datecollected,s.datereceived,s.datetested,s.datemodified,s.datedispatched,s.result,s.worksheet FROM samples s, facilitys f  WHERE MONTH(s.datereceived) BETWEEN 1 AND 3 AND YEAR(s.datereceived) = '$quarteryear' AND s.flag = 1 AND s.facility = f.ID AND f.lab = '$labss' AND s.repeatt=0  ORDER BY s.datereceived DESC ";*/
		}
		else if ($quarterly == 2) //april - june
		{
		$pstartdate = 4;
		$penddate = 6;		
		/*$qury = "SELECT s.ID,s.patient,f.name,s.batchno,s.receivedstatus,s.datecollected,s.datereceived,s.datetested,s.datemodified,s.datedispatched,s.result,s.worksheet FROM samples s, facilitys f  WHERE MONTH(s.datereceived) BETWEEN 4 AND 6 AND YEAR(s.datereceived) = '$quarteryear' AND s.flag = 1 AND s.facility = f.ID AND f.lab = '$labss' AND s.repeatt=0  ORDER BY s.datereceived DESC ";*/
		}
		else if ($quarterly == 3) //july - september
		{
		$pstartdate = 7;
		$penddate = 9;		
		/*$qury = "SELECT s.ID,s.patient,f.name,s.batchno,s.receivedstatus,s.datecollected,s.datereceived,s.datetested,s.datemodified,s.datedispatched,s.result,s.worksheet FROM samples s, facilitys f  WHERE MONTH(s.datereceived) BETWEEN 7 AND 9 AND YEAR(s.datereceived) = '$quarteryear' AND s.flag = 1 AND s.facility = f.ID AND f.lab = '$labss' AND s.repeatt=0  ORDER BY s.datereceived DESC";*/
		}
		else if ($quarterly == 4) //october - december
		{
		$pstartdate = 10;
		$penddate = 12;
		/*$qury = "SELECT s.ID,s.patient,f.name,s.batchno,s.receivedstatus,s.datecollected,s.datereceived,s.datetested,s.datemodified,s.datedispatched,s.result,s.worksheet FROM samples s, facilitys f  WHERE MONTH(s.datereceived) BETWEEN 10 AND 12 AND YEAR(s.datereceived) = '$quarteryear' AND s.flag = 1 AND s.facility = f.ID AND f.lab = '$labss' AND s.repeatt=0  ORDER BY s.datereceived DESC";*/
		}
		else //show message if the search parameter is null
		{
		$pstartdate = 0;
		$penddate = 0;
		}
		
		//...execute query
		$qury = "SELECT s.ID,s.patient,s.facility,s.batchno,s.receivedstatus,s.spots,s.datecollected,s.datereceived,s.datetested,s.datemodified,s.datedispatched,s.result,s.worksheet FROM samples s, facilitys f WHERE MONTH(s.datereceived) BETWEEN $pstartdate AND $penddate AND YEAR(s.datereceived) = $quarteryear AND s.flag = 1 AND s.flag = 1 AND s.facility = f.ID AND f.lab = $labss AND s.repeatt=0 ORDER BY s.datereceived DESC";	
	
		$displaysample = @mysql_query($qury) or die(mysql_error());
		$showcount = mysql_num_rows($displaysample);//get the search count
		
		if ($showcount != 0) //display search results if search parameter is NOT NULL
		{
			echo "
				<table border=0>
					<tr >
						<td colspan=11><small>The filter for samples received for the quarter </small> <strong>$quotaname $quarteryear</strong> <small>returned </small><strong>$showcount</strong> results. </td>
					</tr>
				</table>";
				
			echo "<table border=0 class='data-table'>
		<tr ><th>Count</th><th>Sample Request No</th><th>Facility</th><th>Batch No</th><th>Received Status</th><th>Date Collected</th><th>Date Received</th><th>Date Tested</th><th>Date Modified</th><th>Date Dispatched</th><th>Result</th><th>Worksheet</th></tr>";
		
		//..show table
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
		else
		{
		echo 'There are no records to display for the quarter '.$quotaname.' '.$quarteryear;
		}
	
	?>