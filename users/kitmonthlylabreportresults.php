<?php 
session_start();
require_once('../connection/config.php');
include('../includes/header.php');

$labss = $_SESSION['lab'];

//get the periodic values
$monthly = $_GET['monthly'];
$monthyear = $_GET['monthyear'];
//translate the month values to text
			 if ($monthly ==1) {$month = "January";}
		else if ($monthly ==2) {$month = "February";}
		else if ($monthly ==3) {$month = "March";}
		else if ($monthly ==4) {$month = "April";}
		else if ($monthly ==5) {$month = "May";}
		else if ($monthly ==6) {$month = "June";}
		else if ($monthly ==7) {$month = "July";}
		else if ($monthly ==8) {$month = "August";}
		else if ($monthly ==9) {$month = "September";}
		else if ($monthly ==10) {$month = "October";}
		else if ($monthly ==11) {$month = "November";}
		else if ($monthly ==12) {$month = "December";}
	

?>

<div>
	<div class="section-title">SUMMARY OF KITS USED IN <?PHP echo strtoupper($month).",".$monthyear; ?>
	<div>
	<div>
	<br>
    <?php
	echo "
						<table border=0>
							<tr >
								
								<td><a href=\"downloadmonthlykitsummaryreport.php" ."?monthly=$monthly&monthyear=$monthyear" . "\" title='Click to Download PDF Report' target='_blank'>Download to Pdf </a> &nbsp;&nbsp;  </td>
							</tr>
					
							
						</table>";
	?>
    <table width="634" border="0" class="data-table">
      <tr>
        <th width="164" scope="col">&nbsp;</th>
        <th width="191" scope="col">Kits Usage </th>
        <th width="265" scope="col">Kits Wasted </th>
      </tr>
      <tr>
        <td>No of Kits </td>
        <td><?php echo monthlykitsused($labss,$monthly,$monthyear); ?></td>
        <td><?php echo monthlykitswasted($labss,$weekstartdate,$weekenddate); ?></td>
      </tr>
      <tr>
        <td>No of Tests </td>
        <td><?php  echo totalmonthlytests($labss,$monthly,$monthyear); ?></td>
		 <td>-</td>
      </tr>
      <tr>
        <td> KIT </td>
        <td>Worksheet / Template No</td>
		<td> <?php 
  $kitsquery = "SELECT DISTINCT HIQCAPNo  FROM kits_wasted WHERE  lab='$labss' AND MONTH(daterun)= '$monthly'  AND YEAR(daterun)= '$monthyear' ";
		$kitsresult = mysql_query($kitsquery) or die(mysql_error());

 while(list($HIQCAPNo) = mysql_fetch_array($kitsresult))
  {
 
  ?> <?php echo $HIQCAPNo . " " ; ?><?php }?></td>
      </tr>
      <?php 
  $kitsquery = "SELECT DISTINCT HIQCAPNo  FROM worksheets WHERE  lab='$labss' AND MONTH(daterun)= '$monthly'  AND YEAR(daterun)= '$monthyear' ";
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