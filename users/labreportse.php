<?php 
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
?>
<?php include('../includes/header.php');?>
<?php
$fcode=  $_GET['cat'];
if ($_REQUEST['programreport'])
{
$startdate= isset($_REQUEST["startdate"]) ? $_REQUEST["startdate"] : "";  //start testing date
$enddate= isset($_REQUEST["enddate"]) ? $_REQUEST["enddate"] : "";  //end testing date

$dislaystartdate=date("d-M-Y",strtotime($startdate));  //formatted date in DAY-MONTH-YEAR FOR DISPLAY
$dislayenddate=date("d-M-Y",strtotime($enddate));
$fcode=  $_GET['cat'];
	//get sample facility name based on facility code
		$facilityname=GetFacility($fcode);
$qury = "SELECT *
        FROM samples WHERE datetested BETWEEN '$startdate' AND '$enddate' AND samples.Flag = 1 AND facility='$fcode' 	
		ORDER BY samples.datetested ASC";
$a = mysql_query($qury) or die(mysql_error());
$noofsamples=mysql_num_rows($a);
$reason= " Infant Diagnosis Results Report for  ".$facilityname. " between". $dislaystartdate .  " and ". $dislayenddate ;
}
?>
<style type="text/css">
select {
width: 250;}
</style>	
<script>
		window.dhx_globalImgPath="../img/";
	</script>
<script type="text/javascript" src="../includes/validation.js"></script>
<script language="javascript" src="calendar.js"></script>
<script src="dhtmlxcombo_extra.js"></script>
 <link rel="STYLESHEET" type="text/css" href="dhtmlxcombo.css">
  <script src="dhtmlxcommon.js"></script>
  <script src="dhtmlxcombo.js"></script>

<link rel="stylesheet" href="../includes/validation.css" type="text/css" media="screen" />

<link type="text/css" href="calendar.css" rel="stylesheet" />	
<link href="base/jquery-ui.css" rel="stylesheet" type="text/css"/>
 
  <script src="jquery-ui.min.js"></script>
  <link rel="stylesheet" href="demos.css">



		<div  class="section">
		<div class="section-title">LAB REPORTS</div>
		<div class="xtop">
		<?php if ($success !="")
				{
				?> 
				<table>
				  <tr>
					<td style="width:auto" >
					<div class="success">
					<?php echo  '<strong>'.' <font color="#666600">'.$success.'</strong>'.' </font>';?>
					</div>
					</td>
				  </tr>
				</table>
				<?php } ?>
   <form id="customForm" method="get" action="" >
       <table border="0" class="data-table">
	 
		<tr bgcolor="#CDCCCA">
            <td width="240" height="24" colspan="7" bgcolor="#00526C"><span class="style3 style10"><strong>Reports :: Infant Diagnosis Results Report </strong></span></td>
          </tr>
		  <tr bgcolor="#CDCCCA">
          
            <td bgcolor="#DBE2F2" colspan="2"><strong>Facility</strong></td>
         
            <td height="24" bgcolor="#DBE2F2" colspan="5" align="center"><span style="font-weight: bold">Test Period </span></td>
          </tr>
		  <tr bgcolor="#CDCCCA">
           
            <td bgcolor="#DBE2F2" colspan="2">
			
			 <select  style="width:262px"  id='cat' name="cat">
    
  </select>
  <script>
    var combo = dhtmlXComboFromSelect("cat");
	combo.enableFilteringMode(true,"02_sql_connector.php",true);
	
		

	

</script></td>
			<td height="24" bgcolor="#DBE2F2"><span class="style11">Start Date </span></td>
            <td bgcolor="#DBE2F2"><?php
	  $myCalendar = new tc_calendar("startdate", true, false);
	  $myCalendar->setIcon("../img/iconCalendar.gif");
	  $myCalendar->setDate(date('d'), date('m'), date('Y'));
	  $myCalendar->setPath("./");
	  $myCalendar->setYearInterval(1990, 2015);
	  $myCalendar->dateAllow('1990-05-13', '2015-03-01');
	  $myCalendar->setDateFormat('j F Y');
	  //$myCalendar->setHeight(350);	  
	  //$myCalendar->autoSubmit(true, "form1");
	  $myCalendar->writeScript();
	  ?></td>
         
            <td height="24" bgcolor="#DBE2F2">End Date </td>
            <td bgcolor="#DBE2F2"><?php
	  $myCalendar = new tc_calendar("enddate", true, false);
	  $myCalendar->setIcon("../img/iconCalendar.gif");
	  $myCalendar->setDate(date('d'), date('m'), date('Y'));
	  $myCalendar->setPath("./");
	  $myCalendar->setYearInterval(1990, 2015);
	  $myCalendar->dateAllow('1990-05-13', '2015-03-01');
	  $myCalendar->setDateFormat('j F Y');
	  //$myCalendar->setHeight(350);	  
	  //$myCalendar->autoSubmit(true, "form1");
	  $myCalendar->writeScript();
	  ?></td>
	  <td height="24" bgcolor="#DBE2F2">
	  <input name="programreport" type="submit" value="Generate" size="2" class="button"/>	  </td>
          </tr>
		  </table>
	     </form>
		 <table>
		  <tr bgcolor="#CDCCCA">
            <td height="24" bgcolor="#FFFFFF" colspan="7"></td>
          </tr>
		   <tr bgcolor="#CDCCCA">
            <td height="24" bgcolor="#FFFFFF" colspan="7"><?php 
			 if ($_REQUEST['programreport'])
	{
		if ($noofsamples !=0)
		{ 

?>
<table border="0" >	
<tr>
<td colspan="4">
<strong> <?php echo $reason; ?></strong>
</td>	
	</tr>
	<tr>
<td colspan="6">
<strong>Number of Samples: <?php  echo $noofsamples;  ?></strong>
</td>
	<td align='right'>

		  <?php echo "
<a href=\"downloadfilterreport.php" ."?startdate=$startdate&enddate=$enddate&fcode=$fcode" . "\" title='Click to Download Report' target='_blank'>Download Report </a> | <a href=\"emailtestresults.php" ."?fcode=$fcode" . "\" title='Click to Email Report' target='_blank'>Email Report </a>  ";
?>
  </td>


	
	</tr>
	</table>
	<?php
	echo "<table border='0'   class='data-table'>
				
	 <tr ><th>Infant ID/Sample Code</th><th>Age</th><th>Gender</th><th>Date Collected</th><th>Date Received</th><th>Date Tested</th><th> Test Result</th><th>Date Dispatched</th><th>Turn Around Time</th></tr>";
		while($row = mysql_fetch_array($a))
		{   
				$scode =$row['ID'] ;
				$sdoc=$row['datecollected'];
				$sdoc=date("d-M-Y",strtotime($sdoc));
				$sdrec=$row['datereceived'];
				$sdrec=date("d-M-Y",strtotime($sdrec));
				$testresult=$row['result'];
				$routcome=GetResultType($testresult);
				$patient=$row['patient'];
				$date_of_test=$row['datetested'];
				$date_dispatched=$row['datedispatched'];
				$date_modified=$row['datemodified'];
				$pgender=GetPatientGender($patient);
					//patietn age
				$pAge=GetPatientAge($patient);	
				
				$date_of_test=date("d-M-Y",strtotime($date_of_test));
				$date_dispatched=date("d-M-Y",strtotime($date_dispatched));
				$datereceived4=date("d-m-Y",strtotime($sdrec));
				$datedispatched4=date("d-m-Y",strtotime($date_dispatched));
				$tot = (strtotime($datedispatched4) - strtotime($datereceived4)) / (60 * 60 * 24);	
	echo "<tr class='even'>
								<td ><a href=\"sampledetails.php" ."?ID=$scode" . "\" title='Click to view  Sample Details'>$patient</a></td>
								<td >$pAge</td>
								<td >$pgender </td>
								<td >$sdoc</td>
								<td > $sdrec</td>
								<td > $date_of_test</td>
								<td >$routcome</td>
								<td >$date_dispatched
								</td>
								<td >$tot
								</td>
						</tr>";
					} 
					echo '</table>';

}
else
{
?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="notice"><?php 
		
echo  '<strong>'. 'No Samples Matching your criteria found'.'</strong>';

?></div></th>
  </tr>
</table>
<?php }
	}?>
	 </td>
          </tr>
		  </table>
		 
	    </div>
		</div>
		
 <?php include('../includes/footer.php');?>