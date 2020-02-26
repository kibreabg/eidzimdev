<?php 
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');?>
<?php include('../includes/header.php');?>
<?php
$batchno=$_GET['ID'];
		//get patient/sample code
		$patient=GetPatient($batchno);
		//get bach received date
		$sdrec=GetDatereceived($batchno);
		//get patient gender and mother id based on sample code of sample
		$mid=GetMotherID($patient);
		//get patient gender
		$pgender=GetPatientGender($patient);
		//get sample facility code based  on mothers id
		$facility=GetFacilityCode($mid);
		//get sample facility name based on facility code
		$facilityname=GetFacility($facility);
		//get district and province
		//get selected district ID
		$distid=GetDistrictID($facility);	
		//get select district name and province id	
		$distname=GetDistrictName($distid);
		//get province ID
		$provid=GetProvid($distid);
			//get province name	
		$provname=GetProvname($provid);


if($_SERVER['REQUEST_METHOD']=="POST")
{
$labcode=$_POST['labcode'];
$msg=$_POST['msg'];

foreach($labcode as $a => $b)
 	{	//update worksheet items with date dispac
			
					$samplerec = mysql_query("UPDATE samples
              SET   DispatchComments = '$msg[$a]'
			  			   WHERE (ID = '$labcode[$a]')")or die(mysql_error());
						  // echo $labcode[$a] . "- " .$msg[$a] . "<br>";
 	}
	
	if ($samplerec)
	{  $st="Comments for samples in Batch No". $batchno ." have been added, kindly proceed with the dispatch.";
		echo '<script type="text/javascript">' ;
				echo "window.location.href='dispatch.php?p=$st'";
				echo '</script>';
				 	exit();
	}
}
?>

<style type="text/css">
select {
width: 250;}
</style>	<script language="javascript" src="calendar.js"></script>
<script language="javascript" type="text/javascript">
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}
</script>
<link type="text/css" href="calendar.css" rel="stylesheet" />	
		<SCRIPT language=JavaScript>
function reload(form)
{
var val=form.cat.options[form.cat.options.selectedIndex].value;
self.location='addsample.php?catt=' + val ;
}
</script>

		<div  class="section">
		<div class="section-title">BATCH NO. <?php echo $batchno; ?> SAMPLES DISPATCH COMMENTS  </div>
		<div class="xtop">
	

			
		 		<form name="myForm" method="post" action="" >

       
	<?php 
   $rowsPerPage = 15; //number of rows to be displayed per page

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
   $qury = "SELECT ID,patient,datereceived,spots,datecollected,receivedstatus,DispatchComments,labcomment,result,repeatt,parentid
            FROM samples
			WHERE batchno='$batchno' 
			
			LIMIT $offset, $rowsPerPage";
			
			$result4 = mysql_query($qury) or die(mysql_error());
$no=mysql_num_rows($result4);
if ($no !=0)
{ 
echo '<table border="0" class="data-table">';	

echo "
<tr class='even'>
<th width='558'>
 Facility:  $facilityname | Province:  $provname | District:  $distname
</th>
	<th width='440' >
   	Date Received: $sdrec
 
  </th>
  <th width='30' >
<input name='back' type='button' class='button' value='Back' onclick='history.go(-1)'/>
  </th>
	</tr>";
		echo '</table>';
			
// print the districts info in table
echo '<table border="0" class="data-table">
<tr ><th colspan="15">Sample Log</th></tr>
<tr><th colspan="5">Patient Information</th><th colspan="4">Mother Information</th><th colspan="4">Sample</th><th colspan="1">Dispatch Comments</th></tr>
<tr><th>Lab ID</th><th>Patient ID</th><th>Sex</th><th>Age (mths)</th><th>Infant Prophylaxis</th><th>HIV Status</th><th>PMTCT Intervention</th><th>Feeding Type</th><th>Entry Point</th><th>Status</th><th>Spots</th><th>Result</th><th>Lab Comments</th><th>Add Comments</th></tr>';
	
	
	
	while(list($ID,$patient,$datereceived,$spots,$datecollected,$receivedstatus,$DispatchComments,$labcomment,$result,$repeatt,$parentid) = mysql_fetch_array($result4))
	{  
	
	
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
		$routcome =  GetResultType($result);
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
		
		
		 if ($repeatt ==0 ) 
		  {
     		 $rep="
				  
		 	 <textarea rows='4' cols='50' name='msg[]' maxlength='10'  > $DispatchComments	</textarea>";
		}
		  else
		  { 
		  		if ($parentid ==0)
				{
				$maxrepeatid=GetMaxLabIDforRetest($ID);
				
				}
				else
				{
				$maxrepeatid=GetMaxLabIDforRetest($parentid);
				}
				
				$rep="  
				 <textarea rows='4' cols='50' name='msg[]' maxlength='10' readonly='readonly' > Repeat Test of Sample ".$patient .",see the final retest below with Lab ID ".$maxrepeatid ." to add comment	</textarea> 
		 	";
		}
		   
		 
		
		
		 echo '<tr >';
       
		 echo "
		 <td ><input name='labcode[]' type='text' id='labcode[]' value='$ID' size='5' readonly=''></td>
		 	 <td >$patient</td>
			<td >$pgender </td>
			<td >$pAge</td>
			<td >$pprophylaxis</td>
				<td >$mhiv</td>
			<td >$mprophylaxis</td>
			<td >$mfeeding</td>
			<td >$entry</td>
			<td > $srecstatus</td>
			<td >$spots </td>
		
			<td >$routcome </td>
		  <td >$labcomment </td>
		 
				 <td >   
		 	 $rep</td>";
		  
   
 
         echo '</tr>';
		
      

	}
	
	?>
	<tr  bgcolor="#F0F0F0">
<td colspan="14" align="center"><input type="submit" name="Submit" value="Save Comments " class="button"></td>
</tr><?php
	echo '</table>';
	
 //echo "<a href=\"createacct2.php"  . "\">Click to Open another account</a>";

$numrows =  GetSamplesPerBatch($batchno);

// how many pages we have when using paging?
$NumberOfPages = ceil($numrows/$rowsPerPage);


$Nav="";
if($pageNum > 1) {
$Nav .= "<A HREF=\"BatchDetails.php?page=" . ($pageNum-1) . "&ID=" .urlencode($batchno) . "\"><<  Prev  </A>";
}
for($i = 1 ; $i <= $NumberOfPages ; $i++) {
if($i == $pageNum) {
$Nav .= "<B>  $i  </B>";
}else{
$Nav .= "<A HREF=\"BatchDetails.php?page=" . $i . "&ID=" .urlencode($batchno) . "\">  $i  </A>";
}
}
if($pageNum < $NumberOfPages) {
$Nav .= "<A HREF=\"BatchDetails.php?page=" . ($pageNum+1) . "&ID=" .urlencode($batchno) . "\">  Next   >></A>";
}
echo '<center>';
echo "<BR> <BR>" . $Nav; 
echo '<center>';
}

else
{
?>
<table   >
  <tr>
    <td style="width:auto" ><div class="notice">
      <input type="submit" name="Submit" value="Save Comments " class="button" />
      <?php 
		
echo  '<strong>'.' <font color="#666600">'.'No Samples in that Batch'.'</strong>'.' </font>';

?></div></th>  </tr>
</table>
<?php
}  
  ?>  
	 </form>	
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>