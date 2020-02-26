<?php include('../includes/header.php');
?>
<?php 
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
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
$samplesPerRow = 3;
//reagent 1
if ($reagent1 !="")
{
$reagent1=date("d-M-Y",strtotime($reagent1));
}
//reagent 1
if ($reagent2 !="")
{
$reagent2=date("d-M-Y",strtotime($reagent2));
}
//reagent 1
if ($reagent3 !="")
{
$reagent3=date("d-M-Y",strtotime($reagent3));
}
//reagent 1
if ($reagent4 !="")
{
$reagent4=date("d-M-Y",strtotime($reagent4));
}
//reagent 1
if ($reagent5 !="")
{
$reagent5=date("d-M-Y",strtotime($reagent5));
}
//reagent 1
if ($reagent6 !="")
{
$reagent6=date("d-M-Y",strtotime($reagent6));
}
//reagent 1
if ($reagent7 !="")
{
$reagent7=date("d-M-Y",strtotime($reagent7));
}
//reagent 1
if ($reagent8 !="")
{
$reagent8=date("d-M-Y",strtotime($reagent8));
}
//reagent 1
if ($reagent9 !="")
{
$reagent9=date("d-M-Y",strtotime($reagent9));
}
//reagent 1
if ($reagent10 !="")
{
$reagent10=date("d-M-Y",strtotime($reagent10));
}
//reagent 1
if ($reagent11 !="")
{
$reagent11=date("d-M-Y",strtotime($reagent11));
}
//reagent 1
if ($reagent12 !="")
{
$reagent12=date("d-M-Y",strtotime($reagent12));
}
//reagent 1
if ($reagent13 !="")
{
$reagent13=date("d-M-Y",strtotime($reagent13));
}
//reagent 1
if ($reagent14 !="")
{
$reagent14=date("d-M-Y",strtotime($reagent14));
}
//reagent 1
if ($reagent15 !="")
{
$reagent15=date("d-M-Y",strtotime($reagent15));
}
$creator=GetUserFullnames($createdby);
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
<script language="JavaScript">
function submitPressed() {
document.worksheetform.SaveWorksheet.disabled = true;
//stuff goes here
document.worksheetform.submit();
}
</script> 
		<div  class="section">
		<div class="section-title"><FONT style="font-family:Verdana, Arial, Helvetica, sans-serif">WORKSHEET NO <FONT color="#990000"><?php echo $wno; ?></FONT></FONT></div>

<form  method="post" action="downloadmanualworksheet.php?ID=<?php echo $wno; ?>" name="worksheetform" target="_blank" >

<table  border="1" style="border-right-color:#CCCCCC; border-bottom-color:#CCCCCC" >
<tr >		
	<td width="120"><strong>Serial No</strong> </td>
	<td width="150"><?php echo $ID; ?></td>
	<td width="121"><strong>Date Created</strong> </td>
	<td width="120"><?php  echo  $datecreated ;?></td>
	<td width="133"><strong>Master LOT NO</strong></td>
	<td width="195"><?php echo $Lotno; ?></td>		
</tr>
<tr >
	<td><strong>Worksheet No</strong> </td>
	<td><?php echo $worksheetno; ?></td>	
	<td><strong>Created By</strong> </td>
	<td><FONT color="#0000FF"><strong><?php  echo $creator; ?></strong></FONT></td>
	<td><strong>EXPIRY Date</strong></td>
	<td><FONT color="#FF0000"><strong><?php echo $kitexpirydate; ?></strong></FONT>	</td>	
</tr>
</table>

<table class="data-table" border="1" style="border-right-color:#CCCCCC; border-bottom-color:#CCCCCC">

<tr><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th></tr>
<tr class="even">
<td>
<table border="" class="">
<?php
$count = 1;

$rowcount=1;
$d=1;?>
     <?php
	//$i=mysql_num_rows($result);
for($i = 1; $i <= 4; $i++) 
{
	if ($i  == 1)
	{
	$count = "<p>&nbsp;</p>NC1<br>";
	}
	if ($i  == 2)
	{
	$count = "<p>&nbsp;</p>PC1<br>";
	}
	else if ($i  == 3)
	{
	$count ="<p>&nbsp;</p>CDC NC<br>";
	}
	else if ($i  == 4)
	{
	$count ="<p>&nbsp;</p>CDC PC<br>";
	}
	$RE= $rowcount%8;
	?>
	<tr><td height="" > <?php echo $count; ?> </td></tr>
	<?php
	$count ++;
	$rowcount ++;			
}


	$colcount = 1;
	$qury = "SELECT ID,patient,nmrlstampno
			 FROM samples
			WHERE worksheet='$wno' ORDER BY parentid DESC,nmrlstampno asc,ID ASC";			
				$result = mysql_query($qury) or die(mysql_error());

while(list($ID,$patient,$nmrlstampno) = mysql_fetch_array($result))
{
		$paroid=getParentID($ID,$labss);//get parent id
	
		if ($paroid ==0)
		{
		$paroid="";
		}
		else
		{
		$paroid=" - ". $paroid;
		}
		$RE= $rowcount%8;
		 ?>
		 <tr>
		 		 <td  height="" width="250px" style="font-size:10px">  
				 NMRL Stamp No : <strong><?php echo $nmrlstampno;?></strong><br>
				 Request No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <strong><?php echo  $patient; ?> </strong> <br> 
				 Lab Code   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <strong><?php echo   $ID . " ". $paroid ; ?></strong>  </td>
		 </tr>
		<?php  
		$rowcount ++;
		$d++;
		
		 if ($RE==0)
		 {?>
			</table>	
			</td>
						
			<td>
				<table class="" border="1">
				<tr><td><p>&nbsp;</p>IC<br /></td></tr>				
				<tr><td><p>&nbsp;</p>IC<br /></td></tr>
				<tr><td><p>&nbsp;</p>IC<br /></td></tr>
				<tr><td><p>&nbsp;</p>IC<br /></td></tr>
				<tr><td><p>&nbsp;</p>IC<br /></td></tr>
				<tr><td><p>&nbsp;</p>IC<br /></td></tr>
				<tr><td><p>&nbsp;</p>IC<br /></td></tr>				
				<tr><td><p>&nbsp;</p>IC<br /></td></tr>
				</table>
			</td>
						
			<td>
			<table class="data-table" border="1">
							
			<?php
		}

}  //end while 
      ?>   
	  </td>
	  </tr>  
	   </table>
</td>
</tr>

  
<th  colspan="12"><input type="submit" name="SaveWorksheet" value="Print Worksheet" class="button" style="width:400px"   /></th>

</table>
	  
	  
	  
	  </td></tr>
	 
</table>


	
	
	</form>
 
    <?php include('../includes/footer.php');?>
