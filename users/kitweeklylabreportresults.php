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
	<div class="section-title">SUMMARY OF KITS USED BETWEEN <?PHP echo strtoupper($weekstartdatee); ?> AND <?PHP echo strtoupper($weekenddatee); ?>  </div>
	<div>

<?php
	echo "
						<table border=0>
							<tr >
								
								<td><a href=\"downloadweeklykitsummaryreport.php" ."?weekstartdate=$weekstartdate&weekenddate=$weekenddate" . "\" title='Click to Download PDF Report' target='_blank'>Download to Pdf </a> &nbsp;&nbsp;  </td>
							</tr>
					
							
						</table>";
	?><table width="488" border="0" class="data-table">
  <tr>
    <th width="73" scope="col">&nbsp;</th>
    <th width="229" scope="col">Kits Used </th>
	 <th width="172" scope="col">Kits Wasted </th>
  </tr>
  <tr>
    <td>No of Kits </td>
    <td><?php echo weeklykitsused($labss,$weekstartdate,$weekenddate); ?></td>
	  <td><?php echo weeklykitswasted($labss,$weekstartdate,$weekenddate); ?></td>
  </tr>
  <tr>
    <td>No of Tests </td>
    <td><?php echo totalweeklytests($labss,$weekstartdate,$weekenddate); ?></td>
	  <td>-</td>
  </tr>
  <tr>
    <td> KIT </td>
    <td>Worksheet / Template No</td>
	 <td> <?php 
  $kitsquery = "SELECT DISTINCT HIQCAPNo  FROM kits_wasted WHERE  lab='$labss' AND daterun BETWEEN '$weekstartdate' AND '$weekenddate' ";
		$kitsresult = mysql_query($kitsquery) or die(mysql_error());

 while(list($HIQCAPNo) = mysql_fetch_array($kitsresult))
  {
 
  ?> <?php echo $HIQCAPNo . " " ; ?><?php }?></td>
  </tr>
  <?php 
  $kitsquery = "SELECT DISTINCT HIQCAPNo  FROM worksheets WHERE  lab='$labss' AND daterun BETWEEN '$weekstartdate' AND '$weekenddate' ";
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
	 }
	
	
	 ?></td>
	<td> &nbsp; </td> 
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