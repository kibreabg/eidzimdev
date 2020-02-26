<?php 
session_start();
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
$labss=$_SESSION['lab'];

?>
<?php include('../includes/header.php');?>
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
		<div class="section-title">ALL CONFIRMATORY SAMPLES  AT 9 MONTHS </div>
		
		<tr >
            <td height="24"  colspan="6">Click on Sample to view its  History, <br /></td>
		  </tr>
		<div class="xtop">

   <?php
   echo " Total Samples.".'<strong>'. GetTotalConfirmatoryTests($labss).'</strong>'; 
   	$rowsPerPage = 20; //number of rows to be displayed per page
		
		// by default we show first page
		$pageNum = 1;
		
		// if $_GET['page'] defined, use it as page number
		if(isset($_GET['page']))
		{
		$pageNum = $_GET['page'];
		}
		
		// counting the offset
		$offset = ($pageNum - 1) * $rowsPerPage;
		//query database for all districts
		   $qury = "SELECT samples.ID,samples.patient,samples.datereceived,samples.spots,samples.datecollected,samples.receivedstatus,samples.facility
					FROM samples,facilitys
					WHERE samples.receivedstatus=3 AND samples.reason_for_repeat LIKE '%Confirmatory PCR at 9 Mths%'  AND samples.facility=facilitys.ID AND facilitys.lab='$labss'
					ORDER BY samples.ID DESC
					LIMIT $offset, $rowsPerPage";
					
					$result = mysql_query($qury) or die(mysql_error()); //for main display
							
					$no=mysql_num_rows($result);
					
if ($no !=0)
{ 
	
   
   
   	// print the districts info in table
		echo '<table border="0"   class="data-table">
		</tr>
<tr><th>No</th><th>Patient ID</th><th>Facility</th><th>Sex</th><th>Age (mths)</th><th>Infant Prophylaxis</th><th>Date Collected</th><th>Spots</th><th>HIV Status</th><th>PMTCT Intervention</th><th>Feeding Type</th><th>Entry Point</th><th>Result</th><th>Task</th></tr>';
			while(list($ID,$patient,$datereceived,$spots,$datecollected,$receivedstatus,$facility) = mysql_fetch_array($result))
			{  
				//get sample facility name based on facility code
				$facilityname=GetFacility($facility);
				//date collcted
		$sdoc=date("d-M-Y",strtotime($datecollected));
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
			<td >$pprophylaxis</td>
			<td >$sdoc</td>
			
			<td >$spots </td>
			<td >$mhiv</td>
			<td >$mprophylaxis</td>
			<td >$mfeeding</td>
			<td >$entry</td>
			<td >$routcome </td>

	<td ><a href=\"confirmatory_details.php" ."?ID=$patient" . "\" title='Click to view  history of this sample'>View  History</a> 
</td>

	
	</tr>";
			}
			echo '</table>';

	
		echo '<br>';
		$numrows=GetTotalRepeatParentSamples($labss); //get total no of batches
	
		// how many pages we have when using paging?
		$maxPage = ceil($numrows/$rowsPerPage);
		
		// print the link to access each page
		$self = $_SERVER['PHP_SELF'];
		$nav  = '';

		for($page = 1; $page <= $maxPage; $page ++)
		{
		   if ($page == $pageNum)
		   {
			  $nav .= " $page "; // no need to create a link to current page
		   }
		   /*else
		   {
			  $nav .= " <a href=\"$self?page=$page\">$page</a> ";
		   }*/
		}
		
		// creating previous and next link
		// plus the link to go straight to
		// the first and last page
		
		if ($pageNum > 1)
		{
		   $page  = $pageNum - 1;
		   $prev  = " <a href=\"$self?page=$page\">Prev  |</a> ";
		
		   $first = " <a href=\"$self?page=1\">First Page | </a> ";
		}
		else
		{
		   $prev  = '&nbsp;'; // we're on page one, don't print previous link
		   $first = '&nbsp;'; // nor the first page link
		}
		
		if ($pageNum < $maxPage)
		{
		   $page = $pageNum + 1;
		   $next = " <a href=\"$self?page=$page\"> | Next | </a> ";
		
		   $last = " <a href=\"$self?page=$maxPage\">  Last Page </a> ";
		}
		else
		{
		   $next = '&nbsp;'; // we're on the last page, don't print next link
		   $last = '&nbsp;'; // nor the last page link
		}
		
		// print the navigation link
		echo '<center>'. $first . "  ". $prev  ." ". $nav . "  ". $next ."  ". $last .'</center>';
	}
	else
	{
	
	?>
<table   >
  <tr>
    <td style="width:auto" ><div class="notice"><?php 
		
echo  '<strong>'.' <font color="#666600">'.'No Sample(s) for Confirmatory at 9 months'.'</strong>'.' </font>';

?></div></th>
  </tr>
</table><?php
}
?>
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>