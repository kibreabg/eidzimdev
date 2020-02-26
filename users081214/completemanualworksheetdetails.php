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
if (($daterun !="") && ($daterun !="0000-00-00")&& ($daterun !="1970-01-01"))
{
$daterun=date("d-M-Y",strtotime($daterun));
}
else
{
$daterun="";
}
$samplesPerRow = 3;
//reagent 1

if ($datereviewed !="")
{
$datereviewed=date("d-M-Y",strtotime($datereviewed));
}
else
{
$datereviewed="";
}
$samplesPerRow = 3;
$creator=GetUserFullnames($createdby);
$reviewedby=GetUserFullnames($reviewedby);
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

	<form  method="post" action="downloadcompletemanualworksheet.php?ID=<?php echo $wno; ?>" name="worksheetform" target="_blank" >




<table width="1000" border="1" style="border-right-color:#CCCCCC; border-bottom-color:#CCCCCC; font-family:Courier New, Courier, monospace"><tr >
		
	<td width="120">Serial No </td>
	<td width="179"><span class="style1"><?php echo $ID; ?></span> </td>
	<td width="121">Date Created </td>
	<td width="179"><?php  echo  $datecreated ; //get  date created?></span></td>
	<td width="133">Master LOT NO</td>
	<td width="195"><?php echo $Lotno; ?></td>
	
</tr>
<tr >
	<td>Worksheet No </td>
	<td><span class="style1"><?php echo $worksheetno; ?></span> </td>
	<td>Created By </td>
	<td><?php  echo $creator; ?></td>
	<td >EXP Date</td>
	<td><?php echo $kitexpirydate; ?>
	</td>	

</tr>
		<tr >
		<td width="79"  >Date Run </td>
		<td width="207"><?php echo $daterun; ?> </td>

		<td class="comment style1 style4">Reviewed By  </td>
		<td class="comment" ><?php echo $reviewedby ; ?></td>
				<td >Reviewed Date </td>
		<td>		<?php echo $datereviewed; ?> </td>	
		
		</tr>

</table>

<table width="" class="data-table" border="1" >

<tr><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th></tr>
<tr class="even">
<td><table border="1" class="data-table">
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
ELSEIF ($i  == 3)
{
$count ="<p>&nbsp;</p>CDC NC<br>";
}
ELSEIF ($i  == 4)
{
$count ="<p>&nbsp;</p>CDC PC<br>";

}
		$RE= $rowcount%8;
?>
              <tr><td height="50" > <?php echo $count; ?> </td></tr><?php	


              $count ++;
				$rowcount ++;
			
}


$colcount = 1;
$qury = "SELECT ID,patient,result as 'outcome', nmrlstampno
         FROM samples
		WHERE worksheet='$wno' ORDER BY parentid DESC,nmrlstampno asc,ID ASC";			
			$result = mysql_query($qury) or die(mysql_error());

 while(list($ID,$patient,$outcome,$nmrlstampno) = mysql_fetch_array($result))
		{
		$paroid=getParentID($ID,$labss);//get parent id
	//get sample sample test results
	
		$routcome = GetResultType($outcome);
		
		if ($outcome == 1)//negative
		{
			$beginfont ='<font color="#009933">';
		}
		else if ($outcome == 2)//positive
		{
			$beginfont ='<font color="#FF0000">';
		}
		else
		{
			$beginfont ='<font color="">';
		}
		
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
		 		 <td  height="50" width="145px">  <small>
				 NMRL No &nbsp;&nbsp;&nbsp;: <?php echo  $nmrlstampno; ?>  <br>  
				 Request No : <?php echo  $patient; ?>  <br>  
				 Lab Code &nbsp;&nbsp;&nbsp;: <?php echo   $ID . " ". $paroid ; ?> <br>
				 Result &nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <strong><?php echo $beginfont.$routcome.'</font>'; ?></strong>  </small></td>
		 </tr>
		<?php  $rowcount ++;
		 $d++;
		
             if ($RE==0)
			 {
			?>
			</table>	</td>
			<td>
			<!--<table class="" border="">
				<tr><td><p>&nbsp;</p>IC<br /></td></tr>				
				<tr><td><p>&nbsp;</p>IC<br /></td></tr>
				<tr><td><p>&nbsp;</p>IC<br /></td></tr>
				<tr><td><p>&nbsp;</p>IC<br /></td></tr>
				<tr><td><p>&nbsp;</p>IC<br /></td></tr>
				<tr><td><p>&nbsp;</p>IC<br /></td></tr>
				<tr><td><p>&nbsp;</p>IC<br /></td></tr>				
				<tr><td><p>&nbsp;</p>IC<br /></td></tr>
	  		</table> -->
			</td>
			<td>
			<table class="data-table" border="1">
				
				 <?php
		 }

        }
		 
		 
		  //end while
 
      ?>   
	  </td>
	  </tr>  
	   </table>
	  </td>
	  </tr>

			
            <th  colspan="12" bgcolor="" > <input type="submit" name="SaveWorksheet" value="Print Worksheet" class="button" style="width:400px"   /></th>
      
          </table>
	  
	  
	  
	  </td></tr>
	 
</table>


	
	
	</form>
 
    <?php include('../includes/footer.php');?>
