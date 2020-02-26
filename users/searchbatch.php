<?php 
session_start();
$labss=$_SESSION['lab'];
require_once('../connection/config.php');
include('../includes/header.php');

$searchparameter = ltrim(rtrim($_POST['batch'])); //get the search parameter from the userheader and trim the value
$searchparameterid = ltrim(rtrim($_POST['batchid'])); //get the search parameter from the userheader and trim the value

?>

<div>
	<div class="section-title">SEARCH RESULTS</div>
	<div>
	<?php 
	if (!($searchparameter=='')) //display search results if search parameter is NOT NULL
	{
	?>
		
		<!--start the search*********************************************************** -->
		<?php		
			
			
	  $qury = "SELECT DISTINCT batchno
					FROM samples,facilitys
					WHERE samples.facility=facilitys.ID AND facilitys.lab='$labss' AND samples.batchno='$searchparameterid'
					
					";
					
					$displaysample = mysql_query($qury) or die(mysql_error()); //for main display
			
			$showcount = mysql_num_rows($displaysample);//get the search count
			
						
			if ($showcount!=0) //display table is count is NOT 0
			{
				//show the table
				$samplecount = 0;
				
				echo '<table border="0" >';	
	echo "
<tr class='even'>
<th width='973'>
 The search for Batch<strong>$searchparameter</strong> returned $showcount results
</th>
	 
 <th width='40' >
<input name='back' type='button' class='button' value='Back' onclick='history.go(-1)'/>
  </th>
	</tr>";
		echo '</table>';
		
		
			
				// print the districts info in table
		echo '<table border="0"   class="data-table">
		 <tr ><th>Batch No</th><th>Facility</th><th>Date Received</th><th>No. of Samples</th><th>No. of Rejected Samples</th><th>Samples With Results</th><th>Samples With No Results</th><th>Task</th></tr>';
			while(list($batchno) = mysql_fetch_array($displaysample))
			{  
				//get patient/sample code
				$patient=GetPatient($batchno);
				//get bach received date
				$sdrec=GetDatereceived($batchno);
				//get patient gender and mother id based on sample code of sample
				$mid=GetMotherID($patient);
				//get atient gender
				$pgender=GetPatientGender($patient);
				//get sample facility code based  on mothers id
				$facility=GetFacilityCode($batchno);
				//get sample facility name based on facility code
				$facilityname=GetFacility($facility);
				//count no. of samples per batch
				$num_samples=GetSamplesPerBatch($batchno);
				//count no. of samples per batch that are rejected
				$rej_samples=GetRejectedSamplesPerBatch($batchno);
				//no of saMPLES IN BATCH with results
				$with_result_samples=GetSamplesPerBatchwithResults($batchno);
				////no of saMPLES IN BATCH without results
				$no_result_samples = (($num_samples - $with_result_samples) - $rej_samples);
		
		
			echo "<tr class='even'>
					<td ><a href=\"BatchDetails.php" ."?ID=$batchno" . "\" title='Click to view  Samples in this batch'>$batchno</a></td>
					<td >$facilityname</td>
					<td >$sdrec </td>
					<td > $num_samples</td>
					<td > $rej_samples</td>
					<td > $with_result_samples</td>
					<td >$no_result_samples </td>
					<td ><a href=\"BatchDetails.php" ."?ID=$batchno" . "\" title='Click to view Samples in this batch'>View Samples </a> | <a href=\"batchreport.php" ."?ID=$batchno" . "\" title='Click to view Samples in this batch' target='_blank'>Download Batch </a>  
					</td>
			</tr>";
			}
			echo '</table>';

	
		echo '<br>';
		
									
				
				
				//end show search results
			}
			else //display message of count IS 0
			{
				echo "The search for Batch <strong>".LTRIM(RTRIM($searchparameter))."</strong> returned ".$showcount." results.<br/>";
			}
			
		
			
		?>
			
			
		<!--***********************************************************	 -->
	<?php
	}
	else //show message if the search parameter is null
	{
		echo "<center><strong>Please enter a valid record to search.</strong></center>";
		exit();
	}
	?>	
	</div>	
</div>

		
 <?php include('../includes/footer.php');?>