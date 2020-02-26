<?php
session_start();
$labss=$_SESSION['lab'];
 require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
include('../includes/header.php');

?>
<style type="text/css">
select {
width: 250;}
</style>	<script language="javascript" src="calendar.js"></script>

<link type="text/css" href="calendar.css" rel="stylesheet" />	
<SCRIPT language=JavaScript>
function reload(form)
{
var val=form.cat.options[form.cat.options.selectedIndex].value;
self.location='addsample.php?catt=' + val ;
}
</script>

		<div  class="section">
		<div class="section-title">ALL REJECTED SAMPLES [ <?php echo allrejectedsamples($labss); ?> ]</div>
		<div class="xtop">
		
<?php


	$Limit = 20;
// by default we show first page
$pageNum = 1;

// if $_GET['page'] defined, use it as page number
if(isset($_GET['page']))
{
$pageNum = $_GET['page'];
}

// counting the offset
$offset = ($pageNum - 1) * $limit; 

	 $rejectedsamples=mysql_query("SELECT samples.ID,samples.patient,samples.batchno,samples.datereceived,samples.spots,samples.datecollected,samples.receivedstatus,samples.rejectedreason,samples.facility,facilitys.name,facilitys.district
FROM samples,facilitys WHERE samples.facility=facilitys.ID  AND samples. receivedstatus=2 AND facilitys.lab='$labss'   LIMIT " . ($pageNum-1)*$Limit . ",$Limit")or die(mysql_error());

 $totalrejected= allrejectedsamples($labss);
			
if ( $totalrejected !=0 )
{ 
 
echo '<table border="0" class="data-table">
 <tr  ><td>Count</td><td>Lab ID</td><td>Sample Code</td><td>Batch No</td><td>Facility</td><td>Province</td><td>District</td><td>Date Collected</td><td>Date Received</td><td>Rejected Reason </td><td>Date Dispatched</td><td>Task</font></strong></td></tr>';


   while(list($ID,$patient,$batchno,$datereceived,$spots,$datecollected,$receivedstatus,$rejectedreason,$facility,$name,$district) = mysql_fetch_array($rejectedsamples))
	{ 

		//get select district name and province id	
		$distname=GetDistrictName($district);
		//get province ID
		$provid=GetProvid($district);
			//get province name	
		$provname=GetProvname($provid);
		//date collcted
		$sdoc=date("d-M-Y",strtotime($datecollected));
		//get date received
		$sdrec=date("d-M-Y",strtotime($datereceived));
		$daterec =date("d-m-Y",strtotime($sdrec));
		//get patient gender
	
		//get sample recevied
		$srecstatus=GetReceivedStatus($receivedstatus);
		$daterejdispatched=GetDateDispatchedforRejectedSample($ID);
if ($daterejdispatched != "" )
{
$datedispatched=date("d-M-Y",strtotime($daterejdispatched));
}
else
{
$datedispatched="";
}
$rejectedreason=GetRejectedReason($rejectedreason);
		
		$samplesrank = $samplesrank + 1;
	echo "<tr class='even'>
	<td >	$samplesrank</td>
	<td >$ID </td>
	<td ><a href=\"sample_details.php" ."?ID=$ID" . "\" title='Click to view sample details'>$patient</a></th>
			<td > <a href=\"BatchDetails.php" ."?ID=$batchno" . "\" title='Click to view Batch details'>$batchno</a> </td>
			<td >$name </td>
			<td >$provname</td>
			<td >$distname</td>
			<td >$sdoc</td>
			<td >$sdrec</td>

<td >$rejectedreason</td>			<td >$datedispatched </td>
		<td ><a href=\"sample_details.php" ."?ID=$ID" . "\" title='Click to view sample details'>View Detail</a> | <a href=\"edit_sample.php" ."?ID=$ID" . "\" title='Click to edit sample details' > Edit</a>   
</td>

	
	</tr>";
	}/*<a href="somepage.htm" title="Click to see this page!!!">Somepage</a>*/

	
		echo '</table>';
	 

	$maxPage = ceil($totalrejected/$Limit);

// print the link to access each page
$self = $_SERVER['PHP_SELF'];
$nav  = '';
for($page = 1; $page <= $maxPage; $page++)
{
   if ($page == $pageNum)
   {
      $nav .= " $page "; // no need to create a link to current page
   }
   else
   {
      $nav .= " <a href=\"$self?page=$page\">$page</a> ";
   }
}

// creating previous and next link
// plus the link to go straight to
// the first and last page

if ($pageNum > 1)
{
   $page  = $pageNum - 1;
   $prev  = " <a href=\"$self?page=$page\">[Prev]</a> ";

   $first = " <a href=\"$self?page=1\">[First Page]</a> ";
}
else
{
   $prev  = '&nbsp;'; // we're on page one, don't print previous link
   $first = '&nbsp;'; // nor the first page link
}

if ($pageNum < $maxPage)
{
   $page = $pageNum + 1;
   $next = " <a href=\"$self?page=$page\">[Next]</a> ";

   $last = " <a href=\"$self?page=$maxPage\">[Last Page]</a> ";
}
else
{
   $next = '&nbsp;'; // we're on the last page, don't print next link
   $last = '&nbsp;'; // nor the last page link
}

// print the navigation link
echo '<center>'.$first . $prev . $nav . $next . $last .'</center>';

}
else
{
?>
<table   >
  <tr>
    <td style="width:auto" ><div class="notice"><?php 
		
echo  '<strong>'.' <font color="#666600">'.'No Rejected Samples'.'</strong>'.' </font>';

?></div></th>
  </tr>
</table><?php
}
?>
	
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>