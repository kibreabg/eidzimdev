<?php 
session_start();
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
$labss=$_SESSION['lab'];
$samplecode=$_GET['ID']; //get lab code of sample wich went for retest

?>
<?php include('../includes/header.php');
?>
<style type="text/css">
select {
width: 250;}
</style>	
<script>
function select(a) {
    var theForm = document.myForm;
    for (i=0; i<theForm.elements.length; i++) {
        if (theForm.elements[i].name=='checkbox[]')
            theForm.elements[i].checked = a;
    }
}
</script>
		<div  class="section">
		<div class="section-title">REPEAT FOR REJECTION HISTORY FOR SAMPLE <strong> <?php echo $samplecode; ?></strong></div>
		
		
		<div class="xtop">

   <?php
   
		//query database for all districts
		   $qury = "SELECT samples.ID,samples.patient,samples.datereceived,samples.spots,samples.datecollected,samples.receivedstatus,samples.facility,samples.datetested
					FROM samples,facilitys
					WHERE samples.patient='$samplecode' AND samples.receivedstatus=2 AND samples.facility=facilitys.ID AND facilitys.lab='$labss'
					ORDER BY samples.ID ASC
					";
					
					$result = mysql_query($qury) or die(mysql_error()); //for main display
							
					$no=mysql_num_rows($result);
					
if ($no !=0)
{ 
	
   
   
   	// print the districts info in table
		echo '<table border="0"   class="data-table">
		</tr>
<tr><th>No</th><th>Patient ID</th><th>Facility</th><th>Sex</th><th>Age (mths)</th><th>Date of Birth</th><th>Infant Prophylaxis</th><th>Date Collected</th><th>Date Received</th><th>Status</th><th>Spots</th><th>HIV Status</th><th>PMTCT Intervention</th><th>Feeding Type</th><th>Entry Point</th><th>Date Tested</th><th>Result</th></tr>';
			while(list($ID,$patient,$datereceived,$spots,$datecollected,$receivedstatus,$facility,$datetested) = mysql_fetch_array($result))
			{  
				//get sample facility name based on facility code
				$facilityname=GetFacility($facility);
				//date collcted
		$sdoc=date("d-M-Y",strtotime($datecollected));
		//date tested
		if  ($datetested != "")
		{
		$datetested=date("d-M-Y",strtotime($datetested));
		}
		else
		{
		$datetested="Not Tested";
		}
		//date tested
		$datereceived=date("d-M-Y",strtotime($datereceived));
		//get patient gender
		$pgender=GetPatientGender($patient);
		//patietn age
		$pAge=GetPatientAge($patient);
		//patient dob
		$pdob=GetPatientDOB($patient);
		//infant prophylaxis
		$pprophylaxis=GetPatientProphylaxis($patient);
		//get sample sample test results
		$routcome = GetSampleResult($ID);
		//get sample recevied
		$srecstatus=GetReceivedStatus($receivedstatus);
		//get mother id from patient 
		$mother=GetMotherID($patient);
		//mother hiv
		$mhiv=GetMotherHIVstatus($mother);
		//mother pmtct intervention
		$mprophylaxis=GetMotherProphylaxis($mother);
		//get mothers feeding type
		$mfeeding=GetMotherFeeding($mother);
		//get entry point
		$entry=GetEntryPoint($mother);
		$No=$No+1;
	
		
		
			echo "<tr class='even'>
	<td >$No</td>
	<td ><a href=\"sample_details.php" ."?ID=$ID" . "\" title='Click to view sample details'>$patient</a></td>
			<td >$facilityname </td>
			<td >$pgender </td>
			<td >$pAge</td>
			<td >$pdob</td>
			<td >$pprophylaxis</td>
			<td >$sdoc</td>
			<td >$datereceived</td>
			<td > $srecstatus</td>
			<td >$spots </td>
			<td >$mhiv</td>
			<td >$mprophylaxis</td>
			<td >$mfeeding</td>
			<td >$entry</td>
			<td >$datetested</td>
			<td >$routcome </td>

	

	
	</tr>";
			}
				echo '</table>';
					echo '<table border="0"   >
	';
			echo"
			<tr>
			<th width='30' >
<input name='back' type='button' class='button' value='Back' onclick='history.go(-1)'/>
  </th></tr>";
			echo '</table>';

	
		
	}
	else
	{
	
	?>
<table   >
  <tr>
    <td style="width:auto" ><div class="notice"><?php 
		
echo  '<strong>'.' <font color="#666600">'.'No Historical Records Found'.'</strong>'.' </font>';

?></div></th>
  </tr>
</table><?php
}
?>
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>