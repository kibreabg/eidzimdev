<?php
include('../includes/header.php');
require_once('../connection/config.php');
$userid=$_SESSION['uid'];
$accttype=$_SESSION['accounttype'];
?>
<style type="text/css">
<!--
.style1 {color: #00526C}
-->
</style>

	<!--Navigation-->
	<div>
	<div class="section-title">Navigation</div>

	<!--table div -->
	<div>
	<table>
	<tr>
	<?php if (($accttype == '1') || ($accttype == '4') || ($accttype == '5') ) //data clerk || Lab Tech || Lab Manager
	{ echo '
	<td width="118" align="center"><a href="sampleslist.php">
	  <img src="../img/sample.jpg" alt="Samples" title="Click to View Samples"  class="reflect rheight48 ropacity33" align="middle"/>
	  <p align="center">Samples</p></a></td>';
	 }
	if (($accttype == '4') || ($accttype == '5') ) // Lab Tech || Lab Manager
	{ echo '
	<td width="118" align="center"><a href="worksheetlist.php"><img src="../img/worksheet.png" alt="Worksheets" title="Click to View Worksheets" class="reflect rheight48 ropacity33" align="middle" /><p align="center">Worksheets</p></a></td>';
	 }
	if (($accttype == '1') || ($accttype == '4') || ($accttype == '5') ) //data clerk || Lab Tech || Lab Manager
	{ echo '
	<td width="118" align="center"><a href="labreports.php">
	  <img src="../img/exc.png" alt="Lab Reports" title="Click to View Reports" class="reflect rheight48 ropacity33" align="middle"/>
	  <p align="center">Lab Reports</p>
	</a></td>';
	} 
	if (($accttype == '1') || ($accttype == '2') || ($accttype == '4') || ($accttype == '5') ) //data clerk || Sys Admin || Lab Tech || Lab Manager
	{ echo '
	<td width="118" align="center"><a href="facilitieslist.php">
	  <img src="../img/home.png" alt="Facilities" title="Click to View Facilities" class="reflect rheight48 ropacity33" align="middle" />
	  <p align="center">Facilities </p>
	</a></td>';
	} 
	if (($accttype == '1') || ($accttype == '4') || ($accttype == '5') ) //data clerk || Lab Tech || Lab Manager 
	{ echo '
	<td width="118" align="center"><a href="dispatchedResults.php">
	  <img src="../img/disp.png" alt="Dispatched Results" title="Click to View Dispatched Results" class="reflect rheight48 ropacity33" align="middle" />
	  <p align="center">Dispatched Results </p>
	</a></td>';
	} 
	if (($accttype == '1') || ($accttype == '4') || ($accttype == '5') ) //data clerk || Lab Tech || Lab Manager
	{ echo '
	<td width="118" align="center"><a href="labdashboard.php">
	  <img src="../img/report.png" alt="Lab Dashboard" title="Click to View Lab Dashboard" class="reflect rheight48 ropacity33" align="middle" />
	  <p align="center">Lab Dashboard </p>
	</a></td>';
	} 
	if (($accttype == '1') || ($accttype == '4') || ($accttype == '5') ) //data clerk || Lab Tech || Lab Manager 
	{ echo '
	<td width="118" align="center"><a href="../overall.php">
	  <img src="../img/nat.png" alt="National Dashboard" title="Click to View National Dashboard" class="reflect rheight48 ropacity33" align="middle" />
	  <p align="center">National Dashboard </p>
	</a></td>';
	} ?>
	</tr>
	</table>
	</div>
 	<!--end table div-->
	
	<!--Pending Tasks Div -->
	<div>
	<table >
	<tr bgcolor="#FAFAFA"><td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2; width:820px"><strong>Pending Tasks</strong></td>
	</tr>
	</table>
	<?php include('homependingtasks.php');?>
	</div>
	<!--End Pending Tasks Div -->
				
</div>
<?php include('../includes/footer.php');?>

