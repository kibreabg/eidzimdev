<?php 
session_start();
$success=$_GET['z'];
$commentsuccess=$_GET['p'];
$labss=$_SESSION['lab'];

?>
<?php include('../includes/header.php');?>
<style type="text/css">
select {
width: 250;}
</style>	
<script type="text/javascript" language="JavaScript">
function checkscript() {

var boxesTicked = ""

for (i = document.getElementsByName('checkbox[]').length - 1; i >= 0; i--) {

if (document.getElementsByName('checkbox[]')[i].checked) {

boxesTicked = boxesTicked + document.getElementsByName('checkbox[]')[i].value + "\n"

}

}

if (boxesTicked == "") {
alert ("You must select a Batch to continue.")
return false
}
else {
return confirm('Are you sure you want to dispatch the above selected Sample(s)?');
//return true;
}

}

</script>
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
		<div class="section-title">DISPATCH REJECTED SAMPLES </div>
		<div class="xtop">
		<?php if ($success !="")
		{
		?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="success"><?php 
		
echo  '<strong>'.$success.'</strong>';

?></div></th>
  </tr>
</table>
<?php } ?>
<?php if ($commentsuccess !="")
		{
		?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="success"><?php 
		
echo  '<strong>'.$commentsuccess.'</strong>';

?></div></th>
  </tr>
</table>
<?php } ?> 
<tr >
            <td height="24"  colspan="6"><?php echo '<strong>'."Total Rejected Samples Awaiting Dispatch:" .GetRejectedSamplesAwaitingDispatch($labss) .'</strong>'; ?> <br /></td>
		  </tr><tr >
            <td height="24"  colspan="6"><a href="javascript:select(1)">Check all</a> |
<a href="javascript:select(0)">Uncheck all</a></td>
		  </tr>
		
		<form name="myForm" method="post" action="dispatchedrejectedsamples.php" onsubmit="return checkscript()"   >
<?php 
	
   $rowsPerPage = 10; //number of rows to be displayed per page

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
   $qury = "SELECT batchno,sample  FROM pendingtasks
					WHERE status=0 AND task=6 AND lab='$labss'  GROUP BY batchno
			LIMIT $offset, $rowsPerPage";
			
			$rejectedresult = mysql_query($qury) or die(mysql_error()); //for main display
			
	 $rejqury = "SELECT batchno,sample  FROM pendingtasks
					WHERE status=0 AND task=6 AND lab='$labss'  GROUP BY batchno
			";
			
			$rejectednos = mysql_query($rejqury) or die(mysql_error()); //for main display		
		
			//$result2 = mysql_query($qury) or die(mysql_error()); //for calculating samples with results and those without

$no=mysql_num_rows($rejectedresult);

$num_samples=mysql_num_rows($rejectednos);

if ($num_samples !=0)
{ ?>
	
	<?php
// print the districts info in table
echo '<table border="0"   class="data-table">
 <tr ><th>Check</th><th>Lab # </th><th>Request # </th><th>Batch No</th><th>Facility</th><th>Date Received</th><th>Rejected Reason</th></tr>';
	$i = 0; 
	while(list($batchno,$sample) = mysql_fetch_array($rejectedresult ))
		{  
		
			
		//$noofrejected=GetRejectedSamplesPerBatchFromPendingTasks($batchno,$labss);
		 $samplearray=getSampleetails($sample);
		 extract($samplearray);

		//get sample facility name based on facility code
		$facilityname=GetFacility($facility);
		//count no. of samples per batch
		$sdrec=date("d-M-Y",strtotime($datereceived));
		$rejectedreason=GetRejectedReason($rejectedreason);
	

?>
<tr class='even'>
<td ><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $i++;?>" />  </td>
<td ><input name="labno[]" type="text" id="labno[]" value="<?php echo $sample;?>"   style = 'background:#FCFCFC;'></td>
<td ><input name="patient[]" type="text"  id="patient[]" value="<?php echo $patient;?>"   style = 'background:#FCFCFC;'></td>
<td ><input name="BatchNo[]" type="text" id="BatchNo[]" value="<?php echo $batchno;?>"  style = 'background:#FCFCFC;'></td>

<td ><input name="facility[]" type="text" id="facility[]" value="<?php echo $facilityname;?>"  style = 'background:#FCFCFC;'>
<input name="facilitycode[]" type="hidden"  id="facilitycode[]" value="<?php echo $facility;?>"  > </td>
<td ><input name="sampledrec[]" type="text" id="sampledrec[]" value="<?php echo $sdrec;?>" readonly="" size="11" style = 'background:#FCFCFC;'></td>

<td >  <strong><?php	
		
		echo  $rejectedreason ; 
		 

			 ?></strong>	 </td>	</tr>

<?php
	}
	?>
	<tr  bgcolor="#F0F0F0">
<td colspan="12" align="center"><input type="submit" name="Submit" value="Release for Printing" class="button"> </td>
</tr></table>';
	
		<?php
	echo '<br>';
	$numrows=GetRejectedSamplesAwaitingDispatch($labss) ;//get total no of batches

	// how many pages we have when using paging?
	$maxPage = ceil($num_batches/$rowsPerPage);

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
echo '<center>'. ' Page ' .$first . $prev . $nav . $next . $last .'</center>';


}

else
{
?>
<table   >
  <tr>
    <td style="width:auto" ><div class="notice"><?php 
		
echo  '<strong>'.' <font color="#666600">'.'No Rejected Sample Batches Ready for Dispatch'.'</strong>'.' </font>';

?></div></th>
  </tr>
</table><?php
}  
  ?>
	 </form>
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>