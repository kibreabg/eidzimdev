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
	<div class="section-title">SUMMARY OF TESTS DONE IN QUARTER <?PHP echo strtoupper($quarterly)." - ".$quota." ,".$quarteryear; ?></div>
	<div><p><A HREF='labreports.php'><img src='../img/back.gif' alt='Go Back'/></A>&nbsp;|&nbsp;<font style="background-color:#FFFFCC; font-size:10.5px"><strong>The below statistics are number of sample test results inclusive of repeats.</strong></font></p>
	<?php
	$total= totalperiodictests($labss,$quarterly,$quarteryear);
	$positives=periodictestsbyresult($labss,$quarterly,$quarteryear,2);
	if ($positives !=0)
	{
	$pospercentage= round((($positives/$total)*100),1);
	}
	else
	{
	$pospercentage=0;
	}
	$negatives=periodictestsbyresult($labss,$quarterly,$quarteryear,1);
	if ($negatives !=0)
	{
	$negpercentage= round((($negatives/$total)*100),1);
	}
	else
	{
	$negpercentage=0;
	}
	$failed=periodictestsbyresult($labss,$quarterly,$quarteryear,3);
	if ($failed !=0)
	{
	$failpercentage= round((($failed/$total)*100),1);
	}
	else
	{
	$failpercentage=0;
	}
	$totalpercentage=$failpercentage+$negpercentage+$pospercentage;
	?>
	
	<table border="1" style="border-color:#CCCCCC; border-right-color:#F2F2F2">
  <tr>
    <th style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2" colspan="3" width="200">&nbsp;</th>
    <th style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2" colspan="2" width="150"><div align="center">Number of Tests </div></th>
	 <th width="150" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2"><div align="center">Percentage % </div></th>
  </tr>
  <tr>
    <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #FCFCFC" colspan="3">Positives</td>
    <td colspan="2"><div align="center"><?php echo $positives; ?></div></td>
	<td><div align="center"><?php echo $pospercentage; ?></div></td>
  </tr>
  <tr>
    <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #FCFCFC" colspan="3">Negatives</td>
    <td colspan="2"><div align="center"><?php echo $negatives; ?></div></td>
	<td><div align="center"><?php echo $negpercentage; ?></div></td>
  </tr>
  <tr>
    <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #FCFCFC" colspan="3">Failed</td>
    <td colspan="2"><div align="center"><?php echo $failed; ?></div></td>
	<td><div align="center"><?php echo $failpercentage; ?></div></td>
  </tr>
  <tr>
    <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #FCFCFC" colspan="3">Rejected</td>
    <td colspan="2"><div align="center"><?php echo periodicrejectedsamples($labss,$quarterly,$quarteryear);?></div></td>
	<td><div align="center">&nbsp; - &nbsp; </div></td>
  </tr>
  <tr>
    <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #FCFCFC" colspan="3">Total Tests</td>
    <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2" colspan="2"><div align="center"><strong><?php echo $total; ?></strong></div></td>
	<td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2" colspan="2"><div align="center"><strong><?php echo $totalpercentage; ?></strong></div></td>
  </tr>
</table>

	</div>	
</div>

		
 <?php include('../includes/footer.php');?>