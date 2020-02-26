<?php 
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
$success=$_GET['p'];
$facilitycode= $_POST['facilitycode'];
$BatchNo= $_POST['BatchNo'];
$checkbox=$_POST['checkbox'] ;
$patient= $_POST['patient'];
$labcode= $_POST['labcode'];
$facility= $_POST['facility'];
$fcontname= $_POST['fcontname'];
$femail= $_POST['femail'];
$facilitycode= $_POST['facilitycode'];
$sampleresult= $_POST['sampleresult'];
$msg= $_POST['msg'];
$facilityemail= $_POST['facilityemail'];
$delay= $_POST['delay'];
$dateupdated= $_POST['dateupdated'];
$dateoftest= $_POST['dateoftest'];
$sampledrec= $_POST['sampledrec'];

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
		<div class="section-title">
		<?php
		if ($accttype==4)//..lab tech
		{ $menuname = 'CONFIRM RESULTS RELEASE '; $waiting = 'Release';
		}
		else if ($accttype==1)//..data clerk
		{ $menuname = 'CONFIRM DISPATCH RESULTS '; $waiting = 'Dispatch';
		} 
		echo $menuname; 
		?>
		</div>
		<table>
		<tr >
            <td height="24"  colspan="6"><div class="notice"><strong>Please confirm the batches for <?php echo $waiting;?> below.</strong></div></td>
		  </tr><tr >
            <td height="24"  colspan="6"><a href="javascript:select(1)">Check all</a> |
<a href="javascript:select(0)">Uncheck all</a></td>
		  </tr>
		  </table>
		<div class="xtop">
  <form action="dispatchedResults.php" onSubmit="return confirm('Are you sure you want to <?php echo $waiting;?> the above selected Sample(s)?');" method="post"  class="searchform" name="myForm">
  <?php

echo '<table border="0"   class="data-table">
 <tr ><th>Check</th><th>Sample ID</th><th>Batch No</th><th>Facility</th><th>Date Received</th><th>Date Tested </th><th>Date Updated </th><th> Result</th><th>Delay {days}</th><th>Add Dispatch Comments</th></tr>';
$i=0;
 foreach($_POST['checkbox'] as $i)
 {
 echo "
        <tr  class='even'>

<td ><div align='center'><input name='checkbox[]' type='checkbox' id='checkbox[]' value='$i++' /></div>  </td>		
<td><strong>$patient[$i]</strong><input name='patient[]' type='hidden' id='patient[]' value='$patient[$i]' readonly='' size='2' >
 <input name='labcode[]' type='hidden' id='labcode[]' value='$labcode[$i]' readonly='' size='2' ></td>
<td ><div align='center'>$BatchNo[$i]</div><input name='BatchNo[]' type='hidden' id='BatchNo[]' value='$BatchNo[$i]' readonly='' size='2' ></td>
<td >$facility[$i]<input name='facility[]' type='hidden' id='facility[]' value='$facility[$i]' readonly='' > </td>
<td >$sampledrec[$i]<input name='sampledrec[]' type='hidden' id='sampledrec[]' value='$sampledrec[$i]' readonly=''size='11'></td>
<td >$dateoftest[$i]<input name='dateoftest[]' type='hidden' id='dateoftest[]' value='$dateoftest[$i]' readonly='' size='11'></td>
<td >$dateupdated[$i]<input name='dateupdated[]' type='hidden'  id='dateupdated[]'value='$dateupdated[$i]' readonly='' size='11' > </td>
<td >$sampleresult[$i]<input name='sampleresult[]' type='hidden'  id='sampleresult[]'value='$sampleresult[$i]' readonly='' size='2' > </td>
<td ><div align='center'>$delay[$i]</div><input name='delay[]' type='hidden'  id='delay[]'value='$delay[$i]' readonly='' size='2' > </td>
<td ><textarea rows='2' cols='80' name='msg[]'   id='msg[]' maxlength='255'  > $msg[$i]</textarea> </td>


        </tr>
";

}

 echo '<tr  >
<th colspan=14><input name="button" type="button" onclick="history.go(-1)" value="Go Back" class="button"/>&nbsp;&nbsp;
<input type="submit" name="Submit" class="button" value="Confirm '.$waiting.'" class="button">&nbsp;&nbsp;
<input name="btnCancel" type="button" id="btnCancel" value="Cancel '.$waiting.'" onClick=window.location.href="dispatch.php" class="button">  </th>


</tr>';
 echo '</table>';
	echo '<br>';
	
?>  <input name="dispatch" type="hidden"  id="dispatch" value="<?php echo "dispatch";?>"  >
      
		  		</form>
   
	
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>