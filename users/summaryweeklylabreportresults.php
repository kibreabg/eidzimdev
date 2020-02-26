<?php 
session_start();
require_once('../connection/config.php');
include('../includes/header.php');
$labss=$_SESSION['lab'];
//get the weekly report date filter variables
$weekenddate = $_GET['weekenddate'];
$weekstartdate = $_GET['weekstartdate'];
	$weekstartdatee = date("d-M-Y",strtotime($weekstartdate));
					$weekenddatee = date("d-M-Y",strtotime($weekenddate));
					
?>

<div>
	<div class="section-title">SUMMARY OF TESTS DONE BETWEEN <?PHP echo strtoupper($weekstartdatee); ?> AND <?PHP echo strtoupper($weekenddatee); ?>  </div>
	<div><p><A HREF='labreports.php'><img src='../img/back.gif' alt='Go Back'/></A>&nbsp;|&nbsp;<font style="background-color:#FFFFCC; font-size:10.5px"><strong>The below statistics are number of sample test results inclusive of repeats.</strong></font></p>
	<?php
	$total= totalweeklytests($labss,$weekstartdate,$weekenddate);
	$positives=weeklytestsbyresult($labss,$weekstartdate,$weekenddate,2);
	if ($positives !=0)
	{
	$pospercentage= round((($positives/$total)*100),1);
	}
	else
	{
	$pospercentage=0;
	}
	$negatives=weeklytestsbyresult($labss,$weekstartdate,$weekenddate,1);
	if ($negatives !=0)
	{
	$negpercentage= round((($negatives/$total)*100),1);
	}
	else
	{
	$negpercentage=0;
	}
	$failed=weeklytestsbyresult($labss,$weekstartdate,$weekenddate,3);
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
    <td colspan="2"><div align="center"><?php echo weeklytestsbyresult($labss,$weekstartdate,$weekenddate,2); ?></div></td>
	  <td><div align="center"><?php echo $pospercentage; ?></div></td>
  </tr>
  <tr>
    <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #FCFCFC" colspan="3">Negatives</td>
    <td colspan="2"><div align="center"><?php echo weeklytestsbyresult($labss,$weekstartdate,$weekenddate,1); ?></div></td>
	  <td><div align="center"><?php echo $negpercentage; ?></div></td>
  </tr>
  <tr>
    <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #FCFCFC" colspan="3">Failed</td>
    <td colspan="2"><div align="center"><?php echo weeklytestsbyresult($labss,$weekstartdate,$weekenddate,3); ?></div></td>
	  <td><div align="center"><?php echo $failpercentage; ?></div></td>
  </tr>
  <tr>
    <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #FCFCFC" colspan="3">Rejected</td>
    <td colspan="2"><div align="center"><?php echo weeklyrejectedsamples($labss,$weekstartdate,$weekenddate);?></div></td>
	  <td><div align="center">&nbsp; - &nbsp; </div></td>
  </tr>
  <tr>
    <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2" colspan="3"><strong>Total Tests</strong></td>
    <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2" colspan="2"><div align="center"><strong><?php echo totalweeklytests($labss,$weekstartdate,$weekenddate); ?></strong></div></td>
	  <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2" colspan="2"><div align="center"><strong><?php echo $totalpercentage; ?></strong></div></td>
  </tr>
</table></th>
    <th scope="col" colspan="4">&nbsp;</th>
    
    <th scope="col">&nbsp;</th>
  </tr>
</table>

	
	
<?php
	/*echo "
						<table border=0>
							
					
							<tr >
								<td><a href='labreports.php'><strong>Go back to Lab Report Filter.</strong></a></td>
							</tr>
						</table>";*/
	?>	
	
	
	</div>	
</div>

		
 <?php include('../includes/footer.php');?>