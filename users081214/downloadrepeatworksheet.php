<?php
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
include("../includes/functions.php");
$wno=$_GET['ID'];

$worksheet = getWorksheetDetails($wno);
extract($worksheet);			
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
$datecreated=date("d-M-Y",strtotime($datecreated));
?>
<html>
<link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
<style type="text/css">
<!--
.style1 {font-family: "Courier New", Courier, monospace}
.style4 {font-size: 12}
.style5 {font-family: "Courier New", Courier, monospace; font-size: 12; }
-->
</style>
<body onLoad="JavaScript:window.print();">
<div align="center">
<table>
<tr>
<td><strong>HIV	LAB EARLY DIAGNOSIS<br/>

COBAS AMPLIPREP / TAQMAN TEMPLATE [ REPEATS]</strong>
</td>
<td> <a href="home.php">Go Back </a>
</td>
</tr>
</table>
</div>
<table border="0" class="data-table">
		<tr >
		<td class="comment style1 style4">
		Worksheet No		</td>
		<td class="comment">
		  <span class="style5"><?php echo $ID; ?></span></td>
		<td class="comment style1 style4">Lot No </td>
		<td><span class="comment style1 style4"><?php echo $Lotno. 78; ?></span></td>
		<td><span class="style5">Date Cut </span></td>
		<td colspan="2"><?php echo $datecut; ?></td>
		</tr>
		<tr >
		<td class="comment style1 style4">
		Date Created		</td>
		<td class="comment" ><span class="style5"><?php echo  $datecreated ;?></span></td>
				<td class="comment style1 style4">HIQCAP Kit No</td>
		  <td><span class="comment style1 style4"><?php echo $HIQCAPNo; ?></span></td>	
		  <td><span class="style5">Date Run </span></td>
		  <td colspan="2"><?php echo $daterun; ?></td>
		</tr>
<tr >
		<td class="comment style1 style4">
		Created By	    </td>
		<td class="comment"  ><span class="style5">
	    <?php  ?>
		</span></td><td class="comment style1 style4">
		Rack <strong>#</strong></td>
		<td><span class="comment style1 style4"><?php echo $Rackno; ?></span></td>
		<td><span class="style5">Reviewed By </span></td>
		<td colspan="2">N/A</td>
  </tr><tr >
		
		</tr>
		<tr >
		<td><span class="style5">
	  	  Spek Kit No		</span></td>
		<td  colspan="">
		  <span class="style5"><?php echo $Spekkitno; ?></span> </td>
		<td><span class="style5">KIT EXP </span></td>
		<td><span class="style4"><?php echo $kitexpirydate; ?></span></td>
		<td><span class="style5">Date Reviewed </span></td>
		<td colspan="2">N/A</td>
		</tr>
			
<tr >
	 <?php
	 $qury = "SELECT ID,patient
         FROM samples
		WHERE repeatworksheetno='$wno'
			";			
			$result = mysql_query($qury) or die(mysql_error());

	 $i = 0;
	$samplesPerRow = 7;
	while(list($ID,$patient) = mysql_fetch_array($result))
	{  
	
	

     
        if ($i % $samplesPerRow == 0) {
            echo '<tr>';
        }

  
        
      echo "<td> <img src=../html/image.php?code=code128&o=2&dpi=50&t=50&r=1&rot=0&text=$ID&f1=Arial.ttf&f2=8&a1=&a2=B&a3=   />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; </td>
			
";
	
       
   
    
        if ($i % $samplesPerRow == $samplesPerRow - 1) {
            echo '</tr>
			<tr><td colspan=7>&nbsp;</td></tr>
						<tr><td colspan=7>&nbsp;</td></tr>';
        }
        
        $i += 1;

	


	}
	 echo"<td  align=center colspan=3> PC </td><td  align=center colspan=3> NC </td>";
	?>
</tr>
</table>

</body>
</html>