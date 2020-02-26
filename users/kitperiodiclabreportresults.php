<?php 
require_once('../connection/config.php');
include('../includes/header.php');

$labss = $_SESSION['lab'];

//get the periodic values
$quarterly = $_GET['quarterly'];
$quarteryear = $_GET['quarteryear'];

if ($quarterly ==1)
{
$quota="JAN-MAR";
}
else if ($quarterly ==2)
{
$quota="APR-JUN";
}
else if ($quarterly ==3)
{
$quota="JUL-SEP";
}
else if ($quarterly ==4)
{
$quota="OCT-DEC";
}

?>

<div>
	<div class="section-title">SUMMARY OF KITS USED IN QUARTER <?PHP echo strtoupper($quarterly)." - ".$quota." ,".$quarteryear; ?></div>
	<div>
	
<?php
	echo "
						<table border=0>
							<tr >
								
								<td><a href=\"downloadperiodickitsummaryreport.php" ."?quarterly=$quarterly&quarteryear=$quarteryear" . "\" title='Click to Download PDF Report' target='_blank'>Download to Pdf </a> &nbsp;&nbsp;  </td>
							</tr>
					
							
						</table>";
	?><table width="549" border="0" class="data-table">
  <tr>
    <th width="133" scope="col">&nbsp;</th>
    <th width="200" scope="col">Kits Usage </th>
	 <th width="202" scope="col">Kits Wasted </th>
  </tr>
  <tr>
    <td>No of Kits </td>
    <td><?php echo periodickitsused($labss,$quarterly,$quarteryear); ?></td>
	 <td><?php echo periodickitswasted($labss,$weekstartdate,$weekenddate); ?></td>
  </tr>
  <tr>
    <td>No of Tests </td>
    <td><?php echo totalperiodictests($labss,$quarterly,$quarteryear); ?></td>
	 <td>-</td>
  </tr>
  <tr>
    <td> KIT </td>
    <td>Worksheet / Template No</td>
	<td> <?php 
  $kitsquery = "SELECT DISTINCT HIQCAPNo  FROM kits_wasted WHERE  lab='$labss' AND MONTH(daterun) BETWEEN '$startmonth' AND '$endmonth' AND YEAR(daterun)=  '$quarteryear' ";
		$kitsresult = mysql_query($kitsquery) or die(mysql_error());

 while(list($HIQCAPNo) = mysql_fetch_array($kitsresult))
  {
 
  ?> <?php echo $HIQCAPNo . " " ; ?><?php }?></td>
  </tr>
  <?php 
  if ($quarterly == 1 ) //january - March
		{
		$startmonth=1;
		$endmonth=3;
		}
		else if ($quarterly == 2 )
		{
		$startmonth=4;
		$endmonth=6;
		}
		else if ($quarterly == 3 )
		{
		$startmonth=7;
		$endmonth=9;
		}
		else if ($quarterly == 4 )
		{
		$startmonth=10;
		$endmonth=12;
		}
  $kitsquery = "SELECT DISTINCT HIQCAPNo  FROM worksheets WHERE  lab='$labss' AND MONTH(daterun) BETWEEN '$startmonth' AND '$endmonth' AND YEAR(daterun)=  '$quarteryear' ";
		$kitsresult = mysql_query($kitsquery) or die(mysql_error());

 while(list($HIQCAPNo) = mysql_fetch_array($kitsresult))
  {
   $worksheetquery = "SELECT ID  FROM worksheets WHERE  lab='$labss' AND HIQCAPNo= '$HIQCAPNo' ";
		$worksheetresult = mysql_query($worksheetquery) or die(mysql_error());
  ?>
  <tr>
    <td><?php echo $HIQCAPNo; ?></td>
    <td><?php
	 while(list($ID) = mysql_fetch_array($worksheetresult))
  {		
  		
  echo " <a href=\"worksheetdetails.php" ."?ID=$ID" . "\" title='Click to view Worksheet Details' target='_blank'>$ID </a>".'&nbsp';
  }
	
	
	
	 ?></td>
	 <td>&nbsp;  </td> 
  </tr>
   <?php 
    }
  ?>
</table>

	
	
<?php
	echo "
						<table border=0>
							
					
							<tr >
								<td><a href='labreports.php'><strong>Go back to Lab Report Filter.</strong></a></td>
							</tr>
						</table>";
	?>	
	
	
	</div>	
</div>

		
 <?php include('../includes/footer.php');?>