<?php 
session_start();
include("../includes/functions.php");
$labss=$_SESSION['lab'];
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

<html>
<link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
<style type="text/css">
<!--
.style1 {font-family: "Courier New", Courier, monospace}
.style4 {font-size: 12}
.style5 {font-family: "Courier New", Courier, monospace; font-size: 12; }
.style7 {font-size: x-small}
-->
</style>
<body onLoad="JavaScript:window.print();" >


<table width="1000" class="" border="1" style="border-bottom-color:#CCCCCC; border-right-color:#999999">
<tr>
<td colspan="7"><div align="center"><strong><span class="style5">HIV-1 DNA PCR WORKSHEET</span> </strong></div>
</td>
</tr>
<tr >
		
		<td width="120"  ><span class="style5">Serial No</span> </td>
		<td width="179"><span class="style5"><?php echo $ID; ?></span> </td>		
		<td width="133"><span class="style5">Master LOT NO</span></td>
		<td width="195"><?php echo $Lotno; ?></td>
		<td><span class="style5">EXPIRY Date</span></td>
		<td colspan="2"><span class="style5"><font color="#FF0000"><strong><?php echo $kitexpirydate; ?></strong></font></span>		</td>			
</tr>
<tr >
		<td><span class="style5">Worksheet No</span> </td>
		<td><span class="style5"><?php echo $worksheetno; ?></span> </td>
		<td width="121"  class="comment style1 style4" >Date Created </td>
		<td width="179" class="comment"> <span class="style5"><?php  echo  $datecreated ; //get  date created?></span></td>
		<td colspan="2"><span class="style5">Created By </span></td>
		<td><span class="style5"><?php  echo $creator; ?></span></td>
				
</tr>
<tr >
		<td><span class="style5">Date Run</span> </td>
		<td><span class="style5"><?php echo $daterun; ?></span> </td>
		<td><span class="style5">Reviewed Date</span> </td>
		<td><span class="style5"><?php echo $datereviewed; ?> </span></td>
		<td><span class="style5">Reviewed By </span> </td>
		<td colspan="2"><span class="style5"><?php echo $reviewedby ; ?></span></td>
			
		
		</tr>

</table>

<table width="" class="data-table" border="1" >

<tr><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th></tr>
<tr>
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
if ($paroid ==0)
{
$paroid="";
}
else
{
$paroid=" - ". $paroid;
}
		$RE= $rowcount%8;
		
		
		if ($outcome==1)//negative
	   {	$beginfont = '<font color="#009900">';
	   }
	   else if ($outcome == 2)//positive
	   {	$beginfont = '<font color="#FF0000">';
	   }
	   else
	   { 	$beginfont = '<font color="">';
	   }
		 ?>
		 <tr>
		 		 <td  height="50" width="200px"> <small> 
				 NMRL No &nbsp;&nbsp;&nbsp;: <?php echo  $nmrlstampno; ?>  <br>  
				 Request No : <?php echo  $patient; ?>  <br>  
				 Lab Code &nbsp;&nbsp; : <?php echo   $ID . " ". $paroid ; ?> <br> 
				 Result &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <strong><?php echo $beginfont.$routcome.'</font>'; ?> </strong></small> </td>
		 </tr>
		<?php  $rowcount ++;
		 $d++;
		
             if ($RE==0)
			 {
			?>
			</table>	</td>
			<td>
			<!--<table class="data-table" border="1">
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

			 
           <!-- <th  colspan="12" bgcolor="" ><input type="submit" name="SaveWorksheet" value="Print Worksheet" class="button" style="width:400px"   /></th> -->
       
          </table>
	  
	  
	  
	  </td></tr>
	 
</table>


	
	
	</body>
</html>