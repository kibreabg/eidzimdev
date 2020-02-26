<?php 
require_once('../connection/config.php');
include('../includes/header.php');

$labss = $_SESSION['lab'];

//get the periodic values
$yearly = $_GET['yearly'];
?>



<div>
	<div class="section-title">SUMMARY OF KITS USED IN <?PHP echo strtoupper($yearly); ?>  </div>
	<div>
	
<?php
	echo "
						<table border=0>
							<tr >
								
								<td><a href=\"downloadyearlykitsummaryreport.php" ."?yearly=$yearly" . "\" title='Click to Download PDF Report' target='_blank'>Download to Pdf </a> &nbsp;&nbsp;  </td>
							</tr>
					
							
						</table>";
	?><table width="524" border="0" class="data-table">
  <tr>
    <th width="119" scope="col">&nbsp;</th>
    <th width="203" scope="col">Kits Usage </th>
	 <th width="188" scope="col">Kits Wasted </th>
  </tr>
  <tr>
    <td>No of Kits </td>
    <td><?php echo yearlykitsused($labss,$yearly); ?></td>
	 <td><?php echo yearlykitswasted($labss,$weekstartdate,$weekenddate); ?></td>
  </tr>
  <tr>
    <td>No of Tests </td>
    <td><?php echo totalyearlytests($labss,$yearly); ?></td>
	 <td>-</td>
  </tr>
  <tr>
    <td> KIT </td>
    <td>Worksheet / Template No</td>
	<td> <?php 
  $kitsquery = "SELECT DISTINCT HIQCAPNo  FROM kits_wasted WHERE  lab='$labss' AND YEAR(daterun)= '$yearly' ";
		$kitsresult = mysql_query($kitsquery) or die(mysql_error());

 while(list($HIQCAPNo) = mysql_fetch_array($kitsresult))
  {
 
  ?> <?php echo $HIQCAPNo . " " ; ?><?php }?></td>
  </tr>
  <?php 
  $kitsquery = "SELECT DISTINCT HIQCAPNo  FROM worksheets WHERE  lab='$labss' AND YEAR(daterun)= '$yearly'  ";
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