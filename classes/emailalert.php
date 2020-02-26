<?php
require('../includes/functions.php');

//get the batch number
$ebatch = $_GET['ebatch'];

$resend = $_GET['res'];

$efacility=GetFacilityCode($ebatch);
$efacilityname=GetFacility($efacility);
$faciup = strtoupper($efacilityname);
//end get the batch number

$d=mysql_query("select patient,datecollected,receivedstatus,rejectedreason from samples where batchno='$ebatch'")or die(mysql_error());
echo "<small>		Hello $faciup, <br><br>

Please find below the samples batch received in the lab for testing. <br>-------------------------------------------------------------------------------------------------------------------
<table width='592' border' 1px solid #CCB'>
	<tr > 
		<td  height='21' colspan='5' class='menubar style3'><small>Batch 
		No: $ebatch</small>
		</td>
		</tr>
		<tr   bgcolor='#F6F6F6'> 
		<td  height='21' colspan='5' class='menubar style3' align='center'><small><strong>Batch 
		Details</strong></small>
		</td>
	</tr>
	<tr  bgcolor='#F6F6F6'>
		<td align='left'><small>Sample / Patient ID</small></td>
		<td align='left'> <small>Date Collected</small></td>
		<td align='left'><small>Received Status</small></td>
		<td align='left'><small>Rejected Reason (if rejected)</small></td>
	</tr></small>";
while(list($patient,$datecollected,$receivedstatus,$rejectedreason)=mysql_fetch_array($d))
{
if ($receivedstatus ==2)
{
$rejectedreason=GetRejectedReason($rejectedreason);;
}
else
{
$rejectedreason="N/A";
}
//get sample recevied
		$srecstatus=GetReceivedStatus($receivedstatus);
		
if ($datecollected !="")
{
$datecollected=date("d-M-Y",strtotime($datecollected));
}
else
{
$$datecollected="";
}
echo"
<small>
<tr bgcolor='#F2F6FA'>
  <td align='left'> $patient</td>
   <td align='left'>  $datecollected </td>
   <td align='left'> $srecstatus</td>
   <td align='left'> $rejectedreason</td>
</tr> </small>";

}
echo  "</table>
-------------------------------------------------------------------------------------------------------------------<br><br>
<br><br>
 
Kindly use the batch number given to make queries with the lab.<br><br>
<br><br>


*** Please note that rejected sample(s) urgently require collection of new sample .<br><br>
<br><br>

Regards,<br><br><br>

--<br><br>
Paediatric Diagnosis for HIV (DNA-PCR)<br>
P3 Lab, Centre for Virus Research (CVR)<br>
Kenya Medical Research Institute (KEMRI) Mbagathi Way, NAIROBI<br>
Email: eid-nairobi@googlegroups.com<br>
";


?>