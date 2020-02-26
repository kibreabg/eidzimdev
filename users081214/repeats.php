<?php
session_start();
 require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
include('../includes/header.php');


$labss=$_SESSION['lab'];
if($_REQUEST['Submit'])
{	

$pid= $_POST['pid']; //sample/infant id
$scode= $_POST['scode']; //sample lab id
$batch= $_POST['batch']; //sample batch no
$currentdate=date('Y-m-d'); //get current date
$checkbox=$_POST['checkbox'];

if ($checkbox !="")
{
 foreach($_POST['checkbox'] as $i)
 {
 //update sample record to make available for retest
 $samplerec = mysql_query("UPDATE samples
              SET  	sampleokforretest  = 1 
			  			   WHERE (ID = '$scode[$i]')")or die(mysql_error());

 
 if ( $samplerec )
	{
		$st="The Checked Samples { as listed below }are now available in the worksheet for retest . "."<br>"." The Uncheked one have been marked as failed and ready for dispatch and request for new sample required. ";
 			
	}
	else
	{
		$error='<center>'."Failed to confirm samples for retest, try again ".'</center>';

	}
 }
 //for the unchecked ones....
 //update result as failed..for redrawn
  $unchekedrec = mysql_query("select ID,patient,batchno,parentid from samples where parentid > 0 AND ((sampleokforretest IS NULL ) OR (sampleokforretest =0 ))    AND repeatt =0 AND ((result IS NULL ) OR (result =0 ))")or die(mysql_error());
$uncheckednums=mysql_num_rows($unchekedrec);
if ( $uncheckednums > 0)
{
 while(list($ID,$patient,$batchno,$parentid) = mysql_fetch_array($unchekedrec ))
	{
				//update results to failed
		 			$resultsrec = mysql_query("UPDATE samples
             					 SET  result  	 =  5 ,datemodified = '$currentdate'
			 					WHERE (ID='$ID')")or die(mysql_error());
								
					//set it not to repeat again [complete]
					$repeatresults = mysql_query("UPDATE samples
             			 SET  repeatt  	 =  0 
			 			WHERE (ID='$ID')")or die(mysql_error());
						
						 //update pendign tasks status to be complete
					$pendingtasksupdate = mysql_query("UPDATE pendingtasks
             			 SET  status  	 =  1 
			 			WHERE (sample='$ID' AND task=3)")or die(mysql_error());
						
									//update batch to be complete
					 				$ifcompleterec = mysql_query("UPDATE samples
              						SET  BatchComplete=2
						 		WHERE (ID='$ID')")or die(mysql_error());
							
						
	} //end while && $resultsrec && $repeatresults && $pendingtasksupdate && $ifcompleterec

if ( $resultsrec && $repeatresults && $pendingtasksupdate)
	{
		$st=" The Uncheked Sampless have been marked as failed and ready for dispatch and request for new sample should be made. ";
 			
	}
	else
	{
		$error='<center>'."Failed to confirm samples for retest, try again ".'</center>';

	}
	
}
else
{
//do nothing
}

}
else
{
//check box wasnt checked
}


}

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
		<div class="section-title">REPEAT SAMPLES WAITING TESTING [ <?php   $qury = "SELECT task_id,task,batchno, sample  FROM pendingtasks
					WHERE status=0 AND task=3 ORDER BY batchno ASC ";
			
			$result2 = mysql_query($qury) or die('error');
			$batchesawaitingtest2=mysql_num_rows($result2);
			
			echo $batchesawaitingtest2; ?> ]</div>
		<div class="xtop">
<?php
		if ($st !="")
		{
		?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="success"><?php 
		
echo  '<strong>'.' <font color="#666600">'.$st.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
<?php } ?>
<?php

		if ($error !="")
		{
		?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="error"><?php 
		
echo  '<strong>'.' <font color="#666600">'.$error.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
<?php } ?>
<?php
if ($st !="")
{
	
	$Limit = 10;
// by default we show first page
$pageNum = 1;

// if $_GET['page'] defined, use it as page number
if(isset($_GET['page']))
{
$pageNum = $_GET['page'];
}

// counting the offset
$offset = ($pageNum - 1) * $limit; 


			 $qury = "SELECT task_id,task,batchno, sample  FROM pendingtasks
					WHERE status=0 AND task=3 ORDER BY batchno ASC ";
			
			$result5 = mysql_query($qury) or die('error');
			$batchesawaitingtest=mysql_num_rows($result5);
			
if ($batchesawaitingtest!=0)
{ 
 
echo '<table border="0" class="data-table">
 <tr><td>No</td><td>Lab ID</td><td>Original Lab ID</td><td>Sample / Infant ID</td><td>Batch No</td><td>Facility</td></tr>';
$SUM=0;$samplesrank =0;
 $i = 0; 
while(list($task_id,$task,$batchno,$sample) = mysql_fetch_array($result5))
	{  
	$paroid=getParentID($sample,$labss);//get parent id
	
if ($paroid ==0)
{
$paroid="";
}
else
{
$paroid= $paroid;
}
$sampledetails=getSampleetails($sample);
extract($sampledetails);
$samplecode=patient;
//get patient gender and mother id based on sample code of sample

		//get sample facility name based on facility code
		$facilityname=GetFacility($facility);
		$samplesrank = $samplesrank + 1;
		
	?>
	 
	<tr bgcolor="#F0F3FA">
	<td align="center" style = 'background:#F6F6F6;'><?php echo $samplesrank;?></td>
<td align="center" style = 'background:#F6F6F6;'><?php echo $sample;?></td>
<td align="center" style = 'background:#F6F6F6;'><?php echo $paroid;?></td>
<td align="center" style = 'background:#F6F6F6;'><?php echo $samplecode;?></td>
<td align="center" style = 'background:#F6F6F6;'><?php echo $batchno;?></td>
<td align="center" style = 'background:#F6F6F6;'><?php echo $facilityname;?></td>

	</tr> 
    <p>
      <?php
	}/*<a href="somepage.htm" title="Click to see this page!!!">Somepage</a>*/
?>

<?php
	
		echo '</table>';
	 

	$maxPage = ceil($batchesawaitingtest/$Limit);

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
    <td style="width:auto" ><div class="error"><?php 
		
echo  '<strong>'.' <font color="#666600">'.'No Repeat Samples'.'</strong>'.' </font>';

?></div></th>
  </tr>
</table><?php
}

}
else
{
?>

		<small> <strong>Confirm the samples that are ok for retest. Uncheck any that has less spots or not fit for retest and ask for redrawn sample. <br /> If a sample had alredy been released previously for retest from this page, it wont have a check box next to it.  </strong>  </small> <br />
	<form name="myForm" method="POST" action="" onSubmit="return confirm('Are you sure the below selected samples are available for retest?');"   >
<?php
	$Limit = 10;
// by default we show first page
$pageNum = 1;

// if $_GET['page'] defined, use it as page number
if(isset($_GET['page']))
{
$pageNum = $_GET['page'];
}

// counting the offset
$offset = ($pageNum - 1) * $limit; 


			 $qury = "SELECT task_id,task,batchno, sample  FROM pendingtasks
					WHERE status=0 AND task=3 ORDER BY batchno ASC ";
			
			$resultt = mysql_query($qury) or die('error');
			$batchesawaitingtest=mysql_num_rows($resultt);
			
if ($batchesawaitingtest!=0)
{ 
 
echo '<table border="0" class="data-table">
 <tr><td>No</td><td>Available</td><td>Lab ID</td><td>Original Lab ID</td><td>Sample / Infant ID</td><td>Batch No</td><td>Facility</td></tr>';
$SUM=0;$samplesrank =0;
 $i = 0; 
 $sam=0;
while(list($task_id,$task,$batchno,$sample) = mysql_fetch_array($resultt))
	{  
	
	$dd=mysql_query("select ID from samples where ID='$sample' AND sampleokforretest=1");
	$num=mysql_num_rows($dd);
	$sam=$sam+$num;
	
	
	
	$paroid=getParentID($sample,$labss);//get parent id
	
if ($paroid =='0')
{
$paroid="";
}
else
{
$paroid= $paroid;
}
$sampledetails=getSampleetails($sample);
extract($sampledetails);
$samplecode=patient;//get patient gender and mother id based on sample code of sample
				//get sample facility name based on facility code
		$facilityname=GetFacility($facility);
		$samplesrank = $samplesrank + 1;
		
		
		//echo $sample . " - " .$sampleokforretest;
	?>
	 
	<?php
	if  ($sampleokforretest == 0 )
	{

	?><tr >
	
	<td align="center"><?php echo $samplesrank;?></td>
<td align="center" ><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $i++;?>" checked="checked" /><input type="hidden" name="many" value="Email Facilities" /></td>
<td align="center"><input name="scode[]" type="text" id="lastname" value="<?php echo $sample;?>" readonly="" size="12" style = 'background:#F6F6F6;'></td>
<td align="center"><input name="paroid[]" type="text" id="lastname" value="<?php echo $paroid;?>" readonly="" size="12" style = 'background:#F6F6F6;'></td>
<td align="center"><input name="pid[]" type="text" id="pid[]" value="<?php echo $samplecode;?>" readonly="" style = 'background:#F6F6F6;' /></td>
<td align="center"><input name="batch[]" type="text" id="lastname" value="<?php echo $batchno;?>" readonly="" size="10" style = 'background:#F6F6F6;'></td>
<td align="center"><?php echo $facilityname;?></td>

</tr> 
<?php
}
else
{

?>
<tr class="even">
<td align="center"><?php echo $samplesrank;?></td>
<td align="center" ></td>
<td align="center"><?php echo $sample;?></td>
<td align="center"><?php echo $paroid;?></td>
<td align="center"><?php echo $samplecode;?></td>
<td align="center"><?php echo $batchno;?></td>
<td align="center"><?php echo $facilityname;?></td>

<?php
}
?>
	</tr> 
    <p>
      <?php
	}/*<a href="somepage.htm" title="Click to see this page!!!">Somepage</a>*/
	//echo $batchesawaitingtest ." () ". $sam .'<br/>';
if ($batchesawaitingtest == $sam )
{

}
else
{
?>

<tr >
<td colspan="8" align="center"><input type="submit" name="Submit" value="Release Checked Samples for Retest"  class="button"/></td>
</tr>
<?php
}	
		echo '</table>';
	 

	$maxPage = ceil($batchesawaitingtest/$Limit);

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
    <td style="width:auto" ><div class="error"><?php 
		
echo  '<strong>'.' <font color="#666600">'.'No Repeat Samples'.'</strong>'.' </font>';

?></div></th>
  </tr>
</table><?php
}
?>
	</form>
	
	<?php
	}
	?>
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>