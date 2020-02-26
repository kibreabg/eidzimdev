<?php
session_start();
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
include("../includes/functions.php");
$sampleid=$_GET['ID'];
$userlab=$_SESSION['lab'];
		$labname=GetLabNames($userlab);//get lab name	
$getresult = "SELECT * FROM  samples WHERE ID='$sampleid'";
$result= mysql_query($getresult) or die('Error, query failed');

?>
<html>
<link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
<style type="text/css">
<!--
.style1 {font-family: "Courier New", Courier, monospace}
.style4 {font-size: 12}
.style5 {font-family: "Courier New", Courier, monospace; font-size: 12; }
.style6 {
	font-size: medium;
	font-weight: bold;
}
-->
</style>
<body onLoad="JavaScript:window.print();">
 <?php
		$row = mysql_fetch_array($result);
	
$facilitycode=$row['facility']; 
	$samplelabid=$row['ID'];
	 $wno=$row['worksheet'];
	 

 
	//get sample facility name based on facility code
		$facilityname=GetFacility($facilitycode);
$patient=$row['patient'];
 $outcome=$row['result'];
 $routcome=GetResultType($outcome);
 	//patietn age
		$pAge=GetPatientAge($patient);
$reason_for_repeat=$row['reason_for_repeat'];
 if  ($reason_for_repeat =="")
 { //1st test
$reason_for_repeat="1st Test";
 }

 
 $DispatchComments=$row['DispatchComments'];

 $labcomment=$row['labcomment'];
$pgender=GetPatientGender($patient);
//patietn age
$pAge=GetPatientAge($patient);
$datetested=$row['datetested'];
if ($datetested !="")
{
$datetested=date("d-M-Y",strtotime($datetested));	
}
else
{
$datetested="Not Tested, not enough spots for retest, New sample needed.";
}
//$datecollected=$row['datecollected'];
$datecollected=date("d-M-Y",strtotime($row['datecollected']));	
$datereceived=date("d-M-Y",strtotime($row['datereceived']));	



//get sample sample test results
$mother=GetMotherID($patient);
		//mother hiv
		$mhiv=GetMotherHIVstatus($mother);
		//mother pmtct intervention
		$mprophylaxis=GetMotherProphylaxis($mother);
		//get mothers feeding type
		$mfeeding=GetMotherFeeding($mother);
		//mother feedin desc
		$mfeedingdesc=GetMotherFeedingDesc($mother);
		//get entry point
		$entry=GetEntryPoint($mother);
//infant prophylaxis
		$pprophylaxis=GetPatientProphylaxis($patient);
	?>
<table  border="0" class="data-table">
	<tr>
<td width="242"  colspan="1"><strong><?php echo  $facilityname ;?></strong></td>
<td colspan="3"><span class="style6"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><img src="../img/naslogo.jpg" alt="NASCOP" align="absmiddle" ></strong> </span><br>
  <span class="style15">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="style1">&nbsp;&nbsp;<span class="style17">MINISTRY OF HEALTH <BR>
  NATIONAL AIDS AND STD CONTROL PROGRAM (NASCOP)<BR>
  EARLY INFANT HIV DIAGNOSIS (DNA-PCR) RESULT FORM</span></span></span></td>
<td colspan="1"><span class="style6 style15">LAB: <?php echo $labname; ?></span></td>
</tr>
<tr>
<td colspan="3" class="comment style1 style4"><strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong></td>
<td colspan="3" class="comment style1 style4"><strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong></td>
</tr>
  <tr>
<td colspan="3" class="comment style1 style4"><strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DNA PCR TEST RESULTS </strong></td>
<td colspan="3" class="comment style1 style4"><strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mother  Information </strong></td>
</tr>
		<tr >
		<td colspan="2" class="style4 style1 comment"><strong>
		Infant ID / Sample Code		</strong></td>
		<td width="226"  class="comment">
		  <span class="style5"><?php echo $patient; ?></span></td>
		  <td width="357" class="style4 style1 comment" ><strong>HIV Status </strong></td>
		  <td width="237" class="comment">
		  <span class="style5"><?php echo  $mhiv ;?></span></td>
		</tr>
		<tr >
		<td colspan="2" class="style4 style1 comment"><strong>Date	Sample Collected </strong></td>
		<td  class="comment">
		  <span class="style5"><?php echo  $datecollected ;?></span></td>
		<td class="style4 style1 comment" ><strong>
		PMTCT Intervention </strong></td>
		<td width="237" class="comment">
		  <span class="style5"><?php echo  $mprophylaxis ;?></span></td>
		</tr>
			<tr >
		<td colspan="2" class="style4 style1 comment"><strong>Date Received </strong></td>
		<td colspan="-3" class="comment" ><span class="style5"></span><span class="style5"><?php echo  $datereceived ;?></span></td>
		<td class="style4 style1 comment" ><strong>
		Infant Prophylaxis		</strong></td>
		<td width="237" class="comment"><span class="style5"><?php echo  $pprophylaxis ;?></span></td>
		</tr>
		<tr >
		<td colspan="2" class="style4 style1 comment"><strong>Date	Test Perfomed </strong></td>
		<td colspan="-3" class="comment" ><span class="style5"><?php echo  $datetested ;?></span></td>
		<td class="style4 style1 comment" ><strong>Infant Feeding </strong></td>
		<td width="237" class="comment">
		  <span class="style5"><?php echo  $mfeedingdesc  ;?></span></td>
		</tr>
		<tr >
		<td colspan="2" class="style4 style1 comment"><strong>Age</strong></td>
		<td colspan="-3"  ><span class="style1 style18"><?php echo $pAge; ?> Months</span></td>
		<td class="style4 style1 comment" ><strong>
		Entry	Point	</strong></td>
		<td width="237" class="comment">
		  <span class="style5"><?php echo  $entry ;?></span></td>
		</tr><tr >
		<td colspan="2" class="style4 style1 comment"><strong>
		Test Result </strong></td>
		<td colspan="5" class="comment style1 style4"  ><strong>
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   <?php echo $routcome ; ?> - <?php echo $reason_for_repeat; ?>
		</strong></td>
  </tr>
		<tr >
		<td colspan="7" class="comment style1 style4">
	  	  <span class="style1"><strong>Notes:</strong></span></td>
		</tr>
				
		
		<tr >
		<td class="comment style1 style4" colspan="2">
		<strong>Comments:</strong></td>
		<td colspan="5" class="comment" ><span class="style5"><?php echo   $DispatchComments ;?> <br> <?php echo   $labcomment ;?> </span></td>
		</tr>
	
</table>
<span class="style1 style11">If you have questions or problems regarding samples, please contact the KEMRI-Nairobi Lab at
eid-nairobi@googlegroups.com</span><br><br>
<br>
<br>

</body>
</html>