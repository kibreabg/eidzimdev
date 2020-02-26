<?php 
require_once('../connection/config.php');
include('../includes/header.php');

$labss = $_SESSION['lab'];

//get the periodic values
$yearly = $_GET['yearly'];
?>

<div>
	<div class="section-title">SUMMARY OF TESTS DONE IN <?PHP echo strtoupper($yearly); ?>  </div>
	<div>
	<?php
	$total= totalyearlytests($labss,$yearly);
	$positives=yearlytestsbyresult($labss,$yearly,2);
	if ($positives !=0)
	{
	$pospercentage= round((($positives/$total)*100),1);
	}
	else
	{
	$pospercentage=0;
	}
	$negatives=yearlytestsbyresult($labss,$yearly,1);
	if ($negatives !=0)
	{
	$negpercentage= round((($negatives/$total)*100),1);
	}
	else
	{
	$negpercentage=0;
	}
	$failed=yearlytestsbyresult($labss,$yearly,3);
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
	<?php
	echo "
						<table border=0>
							<tr >
								
								<td><a href=\"downloadyearlysummaryreport.php" ."?year=$yearly" . "\" title='Click to Download PDF Report' target='_blank'>Download to Pdf </a> &nbsp;&nbsp;  </td>
							</tr>
					
							
						</table>";
	?>
	<table width="325" border="0" class="data-table">
  <tr>
    <th scope="col" colspan="3">&nbsp;</th>
    <th scope="col" colspan="2">Number of Tests </th>
	 <th width="46" scope="col">% </th>
  </tr>
  <tr>
    <td colspan="3">Positives</td>
    <td colspan="2"><?php echo $positives; ?></td>
	  <td><?php echo $pospercentage; ?></td>
  </tr>
  <tr>
    <td colspan="3">Negatives</td>
    <td colspan="2"><?php echo $negatives; ?></td>
	  <td><?php echo $negpercentage; ?></td>
  </tr>
  <tr>
    <td colspan="3">Failed</td>
    <td colspan="2"><?php echo $failed; ?></td>
	  <td><?php echo $failpercentage; ?></td>
  </tr>
  <tr>
    <td colspan="3">Rejected</td>
    <td colspan="2"><?php echo yearlyrejectedsamples($labss,$yearly);?></td>
	  <td>&nbsp; - &nbsp; </td>
  </tr>
  <tr>
    <td colspan="3">Total Tests</td>
    <td colspan="2"><?php echo $total; ?></td>
	  <td><?php echo $totalpercentage; ?></td>
  </tr>
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