<?php
session_start();
$labss=$_SESSION['lab'];
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
include("../includes/functions.php");
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
<!--<body onLoad="JavaScript:window.print();"> -->
<body onLoad="JavaScript:window.print();">
<!--<div align="center">
<table>

</table>
</div> -->

<table width="1000" border="1" style="border-right-color:#CCCCCC; border-bottom-color:#CCCCCC; font-family:Courier New, Courier, monospace">
<tr>
<th colspan="8"> <div align="center"><FONT style="font-family:Verdana, Arial, Helvetica, sans-serif">
HIV-1 DNA PCR WORKSHEET</FONT></div>
</th>
</tr>
<tr>
		
	<td width="120"><strong>Serial No</strong> </td>
	<td width="150"><?php echo $ID; ?></td>
	<td width="121"><strong>Date Created</strong> </td>
	<td width="120"><?php  echo  $datecreated ;?></td>
	<td width="133"><strong>Master LOT NO</strong></td>
	<td width="195"><?php echo $Lotno; ?></td>
		
</tr>
<tr >
	<td><strong>Worksheet No </strong></td>
	<td><?php echo $worksheetno; ?></td>
	<td><strong>Created By </strong></td>
	<td><?php  echo $creator; ?></td>
	<td><strong>EXPIRY Date</strong></td>
	<td><?php echo $kitexpirydate; ?></td>	

</tr>

</table>

<table width="" class="" border="1" style="border-right-color:#999999; border-bottom-color:#999999">

<tr style="background-color:#DFDFDF"><th><div align="center">1</div></th><th><div align="center">2</div></th><th><div align="center">3</div></th><th><div align="center">4</div></th><th><div align="center">5</div></th><th><div align="center">6</div></th><th><div align="center">7</div></th><th><div align="center">8</div></th><th><div align="center">9</div></th><th><div align="center">10</div></th><th><div align="center">11</div></th><th><div align="center">12</div></th></tr>
<tr>
<td><table border="1" class="" style="border-right-color:#999999; border-bottom-color:#999999">
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

$qury = "SELECT ID,patient, nmrlstampno
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
		 		 <td  height="50" width="145px">  <small>
				 NMRL No : <strong><?php echo  $nmrlstampno; ?></strong>  <br>   
				 Request No  : <strong><?php echo  $patient; ?></strong>  <br>  
				 Lab Code    : <strong><?php echo   $ID . " ". $paroid ; ?></strong>  </small></td>
		 </tr>
		<?php  $rowcount ++;
		 $d++;
		
             if ($RE==0)
			 {
			?>
			</table>	</td><td>
			<table class="" border="0">
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
			<table class="" border="1">
				
				 <?php
		 }

        }
		 
		 
		  //end while
 
      ?>   
	  </td></tr>  
	  </table>
	  </td>
	  </tr>
	  
	 
</table>
<br>

</body>
</html>