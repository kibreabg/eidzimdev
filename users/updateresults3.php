<?php
session_start();
$labss=$_SESSION['lab'];
require_once('../connection/config.php');
include('../includes/header.php');
	$userid=$_SESSION['uid'] ; //id of user who is updatin th record
$wno=$_GET['ID'];

$worksheet = getWorksheetDetails($wno);
extract($worksheet);			
$datecreated=date("d-M-Y",strtotime($datecreated));
if ($kitexpirydate !="")
{
$kitexpirydate=date("d-M-Y",strtotime($kitexpirydate));
}
if ($datecut != "")
{
$datecut=date("d-M-Y",strtotime($datecut));
}
else
{
$datecut="";
}
if ($daterun !="")
{
$daterun=date("d-M-Y",strtotime($daterun));
}
else
{
$daterun="";
}


$creator=GetUserFullnames($createdby);


if(isset($_POST['submit']))
{ 
	$work= $_POST['work'];
	$serialno= $_POST['serialno'];
	$labcode= $_POST['labcode'];
	$outcome= $_POST['testresult'];
	$daterun = $_POST['daterun'];
	if ($daterun !="")
	{
	$daterun =date("Y-m-d",strtotime($daterun)); //convert to yy-mm-dd
	}
	else
	{
	$daterun ="";
	}
	$dateresultsupdated =date('Y-m-d');
	$datereviewed=date("Y-m-d");
	foreach($labcode as $a => $b)
	{
	
	  $import = mysql_query("UPDATE samples
              SET result = '$outcome[$a]' ,datemodified = '$dateresultsupdated', datetested='$daterun'
			  			  WHERE (ID = '$labcode[$a]')");
						// echo  "wsheet" . $work   ."date run " . $daterun ."<br/>";
						//  echo  "Lab ID ". $labcode[$a] . " Result" .$outcome[$a] . "<br/>";
	}
	
	 $updateworksheetrec = mysql_query("UPDATE worksheets
             SET Updatedby='$userid' ,daterun='$daterun' , Flag=1 , reviewedby='$userid',datereviewed='$datereviewed'
			   			   WHERE ( ((worksheetno = '$work') AND (ID='$serialno')) )")or die(mysql_error());
						   
						   
						   if($import && $updateworksheetrec )
		{
			//save user activity
			$tasktime= date("h:i:s a");
			$todaysdate=date("Y-m-d");
			$utask = 7; //user task = update worksheet
			
			$activity = SaveUserActivity($userid,$utask,$tasktime,$worksheetno,$todaysdate);
		
				
			$st = "Import done, Results Updated successfully, Please Confirm and approve the updated results below";
			echo '<script type="text/javascript">' ;
			echo "window.location.href='confirmresults.php?p=$st&q=$work'";
			echo '</script>';
		}			

}
?>
<html>
<link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
<style type="text/css">
<!--
.style1 {font-family: "Courier New", Courier, monospace}
.style4 {font-size: 12}
.style5 {font-family: "Courier New", Courier, monospace; font-size: 12; }
.style7 {
	font-size: small;
	font-weight: bold;
}
.style11 {font-family: "Courier New", Courier, monospace; font-size: 12px; }
-->
</style>
<style type="text/css">
select {
width: 250;}
</style>	
<script type="text/javascript" src="../includes/validationresults.js"></script>
<link rel="stylesheet" href="../includes/validation.css" type="text/css" media="screen" />

<script language="javascript" src="calendar.js"></script>
<link type="text/css" href="calendar.css" rel="stylesheet" />	
		<link href="base/jquery-ui.css" rel="stylesheet" type="text/css"/>
 
  <script src="jquery-ui.min.js"></script>
  <link rel="stylesheet" href="demos.css">
  <script>
  $(document).ready(function() {
   // $("#dob").datepicker();
	$( "#daterun" ).datepicker({ minDate: "-7D", maxDate: "+0D" });
	});


//  });
  </script>
<div  class="section">
		<div class="section-title">UPDATE TEST RESULTS  FOR WORKSHEET NO <?php echo $worksheetno; ?> </div>
		<div class="xtop">

</div>
<form action="" method="post" id="customForm">
<table width="1000" class="data-table" border="0" bordercolor="#CCCCCC">
<tr class="even">
		
		<td width="120"  >Serial No </td>
		    <td width="179"><span class="style7"><span class="style1"><?php echo $ID; ?></span><input name='serialno' type='hidden' id='serialno' value='<?php echo $ID; ?>'  style='width:174px' readonly=''  /> </td>
			<td width="121"  class="comment style1 style4" >Date Created </td>
		<td width="22" class="comment">
		  <span class="style5">
		  <?php  echo  $datecreated ; //get  date created?></span></td>
				<td width="133">Master LOT NO</td>
		<td width="195"  colspan="2"><?php echo $Lotno; ?></td>
		
</tr>
		<tr class="even">
		<td width="79"  >Worksheet No </td>
		<td width="207"><span class="style7"><span class="style1"><?php echo $worksheetno; ?></span> <input name='work' type='hidden' id='work' value='<?php echo $worksheetno; ?>'  style='width:174px' readonly=''  /></td>

		<td class="comment style1 style4">Created By </td>
		<td class="comment" ><?php  echo $creator; ?></td>
				<td >EXP Date</td>
		<td><?php echo $kitexpirydate; ?>
		</td>	
		
		</tr>
		</tr>
		<tr class="even" >
		<td width="79"  >Date Run </td>
		<td width="207"><div>
			 <input id="daterun" type="text" name="daterun" class="text"  size="26" ><span id="daterunInfo"></span></div></p>

<div type="text" id="daterun"></div></td>

		<td class="comment style1 style4">Reviewed By  </td>
		<td class="comment" > N/A </td>
				<td >Reviewed Date </td>
		<td>		N/A </td>	
		
		</tr>

<tr >
			<th colspan="6" ><small> <strong><?php echo $no; ?> WORKSHEET SAMPLES [2 Controls]</strong></small>		</th>
			</tr>
<tr  >
<?php
	 $count = 1;
$colcount=1;
for($i = 1; $i <= 2; $i++) 
{
		if ($count==1)
		{
		$pc="<div align='center'>Negative Control<br><strong>NC</strong></div>";
		}
		elseif ($count==2)
		{
		$pc="<div align='center'>Positive Control<br><strong>PC</strong></div>";
		}
		
				$RE= $colcount%6;
?>
             <td height="50" > <?php echo $pc; ?> </td><?php	 
	

       $count ++;         
	$colcount ++;
			
}
$scount = 2;
$qury = "SELECT ID,patient
         FROM samples
		WHERE worksheet='$wno' ORDER BY parentid DESC,ID ASC";			
			$result = mysql_query($qury) or die(mysql_error());

	
	while(list($ID,$patient) = mysql_fetch_array($result))
	{  
		$scount = $scount + 1;
		$paroid=getParentID($ID,$labss);//get parent id
	
		if ($paroid ==0)
		{
		$paroid="";
		}
		else
		{
		$paroid=" - ". $paroid;
		}
     
	 $RE= $colcount%6;
	 
        

  ?>
     

	
	 <td  height="50" width="145px"> Request No: <strong><?php echo $patient; ?></strong> <input name='patient[]' type='hidden' id='patient' value='<?php echo $patient; ?>' size='14' readonly=''>  <br>   Lab Code   <input name='labcode[]' type='hidden' id='labcode[]' value='<?php echo $ID; ?>' size='5' readonly=''><?php echo   $ID . " ". $paroid ; ?>  <br> <?php
					$requery = "SELECT ID,Name FROM results where ID !=4  ";//and ID !=5
				
					$rreesult = mysql_query($requery) or die('Error, query failed'); //onchange='submitForm();'
		
					echo "<select name='testresult[]' id='testresult[]'  ;>\n";
					echo " <option value=''> Select One </option>";
					  while ($rerow = mysql_fetch_array($rreesult))
					  {
							 $reID = $rerow['ID'];
							$rename = $rerow['Name'];
						echo "<option value='$reID'> $rename</option>\n";
					  }
		  echo "</select>\n";
		  ?> </td>
       
   <?php
    
        $colcount ++;
		 
		
             if ($RE==0)
			 { 
			?>
	
       
   
     </tr>
<?php
		 }//end if modulus is 0
	 }//end while?>
</tr>
	  
	 <tr class='even'>
		<td colspan='13'>
		<input type='submit' name='submit' value='Update Results' class='button'></td>
     		
		</tr>	
</table>
</form>
 <?php include('../includes/footer.php');?>
