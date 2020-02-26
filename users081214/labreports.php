<?php 
session_start();
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
include('../includes/header.php');

$labss=$_SESSION['lab'];

$currentdate = date('Y'); //show the current year
$lowestdate = GetAnyDateMin(); //get the lowest year from date received
$maximumyear = GetMaxYear();

if ($_REQUEST['programreport'])
{
	//get the weekly report variables
	$enddate = $_GET['enddate'];
	$startdate = $_GET['startdate'];
	$facility =  $_GET['cat'];
	

	
	echo '<script type="text/javascript">' ;
	echo "window.location.href='labreportresults.php?facility=$facility&enddate=$enddate&startdate=$startdate'";
	echo '</script>';
	exit();
	
}
if ($_REQUEST['provincereport'])
{
	//get the weekly report variables
	$provenddate = $_GET['provenddate'];
	$provstartdate = $_GET['provstartdate'];
	$province =  $_GET['province'];
	
	echo '<script type="text/javascript">' ;
	echo "window.location.href='provincereportresults.php?province=$province&provenddate=$provenddate&provstartdate=$provstartdate'";
	echo '</script>';
	exit();
	
}
else if ($_REQUEST['weeklyreport'])
{
	//get the weekly report variables
	$weekenddate = $_GET['weekenddate'];
	$weekstartdate = $_GET['weekstartdate'];
	
	echo '<script type="text/javascript">' ;
	echo "window.location.href='weeklylabreportresults.php?view=1&weekenddate=$weekenddate&weekstartdate=$weekstartdate'";
	echo '</script>';
	exit();
}
else if ($_REQUEST['weeklyreports'])
{
	//get the weekly report variables
	$weekenddate = $_GET['weekenddate'];
	$weekstartdate = $_GET['weekstartdate'];
	
	echo '<script type="text/javascript">' ;
	echo "window.location.href='summaryweeklylabreportresults.php?weekenddate=$weekenddate&weekstartdate=$weekstartdate'";
	echo '</script>';
	exit();
}
else if ($_REQUEST['kitweeklyreport'])
{
	//get the weekly report variables
	$weekenddate = $_GET['weekenddate'];
	$weekstartdate = $_GET['weekstartdate'];
	
	echo '<script type="text/javascript">' ;
	echo "window.location.href='kitweeklylabreportresults.php?weekenddate=$weekenddate&weekstartdate=$weekstartdate'";
	echo '</script>';
	exit();
}
else if ($_REQUEST['periodicreport'])
{
	//get the periodic values
	$quarterly = $_GET['quarterly'];
	$quarteryear = $_GET['quarteryear'];
	
	echo '<script type="text/javascript">' ;
	echo "window.location.href='periodiclabreportresults.php?quarterly=$quarterly&quarteryear=$quarteryear'";
	echo '</script>';
	exit();
}
else if ($_REQUEST['periodicreports'])
{
	//get the periodic values
	$quarterly = $_GET['quarterly'];
	$quarteryear = $_GET['quarteryear'];
	
	echo '<script type="text/javascript">' ;
	echo "window.location.href='summaryperiodiclabreportresults.php?quarterly=$quarterly&quarteryear=$quarteryear'";
	echo '</script>';
	exit();
}
else if ($_REQUEST['kitperiodicreport'])
{
	//get the periodic values
	$quarterly = $_GET['quarterly'];
	$quarteryear = $_GET['quarteryear'];
	
	echo '<script type="text/javascript">' ;
	echo "window.location.href='kitperiodiclabreportresults.php?quarterly=$quarterly&quarteryear=$quarteryear'";
	echo '</script>';
	exit();
}
else if ($_REQUEST['monthlyreport'])
{
	//get the periodic values
	$monthly = $_GET['monthly'];
	$monthyear = $_GET['monthyear'];
	
	echo '<script type="text/javascript">' ;
	echo "window.location.href='monthlylabreportresults.php?monthly=$monthly&monthyear=$monthyear'";
	echo '</script>';
	exit();
}
else if ($_REQUEST['monthlyreports'])
{
	//get the periodic values
	$monthly = $_GET['monthly'];
	$monthyear = $_GET['monthyear'];
	
	echo '<script type="text/javascript">' ;
	echo "window.location.href='summarymonthlylabreportresults.php?monthly=$monthly&monthyear=$monthyear'";
	echo '</script>';
	exit();
}
else if ($_REQUEST['kitmonthlyreport'])
{
	//get the periodic values
	$monthly = $_GET['monthly'];
	$monthyear = $_GET['monthyear'];
	
	echo '<script type="text/javascript">' ;
	echo "window.location.href='kitmonthlylabreportresults.php?monthly=$monthly&monthyear=$monthyear'";
	echo '</script>';
	exit();
}
else if ($_REQUEST['yearreport'])
{
	//get the periodic values
	$yearly = $_GET['yearly'];
	
	echo '<script type="text/javascript">' ;
	echo "window.location.href='yearlabreportresults.php?yearly=$yearly'";
	echo '</script>';
	exit();
}
else if ($_REQUEST['yearreports'])
{
	//get the periodic values
	$yearly = $_GET['yearly'];
	
	echo '<script type="text/javascript">' ;
	echo "window.location.href='summaryyearlabreportresults.php?yearly=$yearly'";
	echo '</script>';
	exit();
}
else if ($_REQUEST['kityearreport'])
{
	//get the periodic values
	$yearly = $_GET['yearly'];
	
	echo '<script type="text/javascript">' ;
	echo "window.location.href='kityearlabreportresults.php?yearly=$yearly'";
	echo '</script>';
	exit();
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
		<!--<div class="section-title">LAB REPORTS</div> -->
		<div class="section-title">&nbsp;</div> 
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
   <form id="customForm" method="get" action="" target="_blank" >
       <table  border="1" style="border-color:#CCCCCC; border-right-color:#FFFFFF"><!--border="1" style="border-color:#CCCCCC" -->
	 
		<tr >
            <td colspan="9"  style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2"><strong>Region Report </strong></td>
         </tr>
		  <tr >
          
            <td width="77" style="font-family:Verdana, Arial, Helvetica, sans-serif ;background-color: #F8F8F8 ">Facility</td>
         	 <td width="205"><select  style="width:200px"  id='cat' name="cat"><script>
    var combo = dhtmlXComboFromSelect("cat");
	combo.enableFilteringMode(true,"02_sql_connector.php",true);
	
</script>	
</select></td>
           
		   <td width="77" style="font-family:Verdana, Arial, Helvetica, sans-serif ;background-color: #F8F8F8"><span class="style11">Start Date </span></td>
            <td   colspan="2" ><?php
	  $myCalendar = new tc_calendar("startdate", true, false);
	  $myCalendar->setIcon("../img/iconCalendar.gif");
	  $myCalendar->setDate(date('d'), date('m'), date('Y'));
	  $myCalendar->setPath("./");
	  $myCalendar->setYearInterval($lowestdate, $currentdate);
	  //$myCalendar->dateAllow('1990-05-13', '2015-03-01');
	  $myCalendar->setDateFormat('j F Y');
	  //$myCalendar->setHeight(350);	  
	  //$myCalendar->autoSubmit(true, "form1");
	  $myCalendar->writeScript();
	  ?></td>
         
            <td  style="font-family:Verdana, Arial, Helvetica, sans-serif ;background-color: #F8F8F8">End Date </td>
            <td ><?php
	  $myCalendar = new tc_calendar("enddate", true, false);
	  $myCalendar->setIcon("../img/iconCalendar.gif");
	  $myCalendar->setDate(date('d'), date('m'), date('Y'));
	  $myCalendar->setPath("./");
	  $myCalendar->setYearInterval($lowestdate, $currentdate);
	  //$myCalendar->dateAllow('1990-05-13', '2015-03-01');
	  $myCalendar->setDateFormat('j F Y');
	  //$myCalendar->setHeight(350);	  
	  //$myCalendar->autoSubmit(true, "form1");
	  $myCalendar->writeScript();
	  ?></td>
	  <td width="123" height="24">
	  <input name="programreport" type="submit" value="Generate" size="2" class="button"/>	  </td>
          </tr>
	<tr>
	<td style="font-family:Verdana, Arial, Helvetica, sans-serif ;background-color: #F8F8F8">Province</td>
	 <td><?php
				   $accountquery = "SELECT Code,name FROM provinces";
						
					$result = mysql_query($accountquery) or die('Error, query failed'); //onchange='submitForm();'
				
				   echo "<select name='province' style='width:200px';>\n";
					echo " <option value='0'> Select One </option>";
					
				  while ($row = mysql_fetch_array($result))
				  {
						 $ID = $row['Code'];
						$name = $row['name'];
					echo "<option value='$ID'> $name</option>\n";
				  }
				  echo "</select>\n";
				  	?></td>
           
		   <td style="font-family:Verdana, Arial, Helvetica, sans-serif ;background-color: #F8F8F8" ><span class="style11">Start Date </span></td>
            <td  colspan="2"><?php
	  $myCalendar = new tc_calendar("provstartdate", true, false);
	  $myCalendar->setIcon("../img/iconCalendar.gif");
	  $myCalendar->setDate(date('d'), date('m'), date('Y'));
	  $myCalendar->setPath("./");
	  $myCalendar->setYearInterval($lowestdate, $currentdate);
	  //$myCalendar->dateAllow('1990-05-13', '2015-03-01');
	  $myCalendar->setDateFormat('j F Y');
	  //$myCalendar->setHeight(350);	  
	  //$myCalendar->autoSubmit(true, "form1");
	  $myCalendar->writeScript();
	  ?></td>
         
            <td style="font-family:Verdana, Arial, Helvetica, sans-serif ;background-color: #F8F8F8" >End Date </td>
            <td ><?php
	  $myCalendar = new tc_calendar("provenddate", true, false);
	  $myCalendar->setIcon("../img/iconCalendar.gif");
	  $myCalendar->setDate(date('d'), date('m'), date('Y'));
	  $myCalendar->setPath("./");
	  $myCalendar->setYearInterval($lowestdate, $currentdate);
	  //$myCalendar->dateAllow('1990-05-13', '2015-03-01');
	  $myCalendar->setDateFormat('j F Y');
	  //$myCalendar->setHeight(350);	  
	  //$myCalendar->autoSubmit(true, "form1");
	  $myCalendar->writeScript();
	  ?></td>
	  <td height="24">
	  <input name="provincereport" type="submit" value="Generate" size="2" class="button"/>	  </td>
	</tr>
	<tr>
	<td colspan="9">&nbsp;</td>
	</tr>
	<tr>
	<td colspan="9" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2"><strong>Weekly Report	</strong></td>
	</tr>
	<tr>
	<td style="font-family:Verdana, Arial, Helvetica, sans-serif ;background-color: #F8F8F8"><span class="style11">Start Date </span></td>
            <td ><?php
	  $myCalendar = new tc_calendar("weekstartdate", true, false);
	  $myCalendar->setIcon("../img/iconCalendar.gif");
	  $myCalendar->setDate(date('d'), date('m'), date('Y'));
	  $myCalendar->setPath("./");
	  $myCalendar->setYearInterval($lowestdate, $currentdate);
	  //$myCalendar->dateAllow('1990-05-13', '2015-03-01');
	  $myCalendar->setDateFormat('j F Y');
	  //$myCalendar->setHeight(350);	  
	  //$myCalendar->autoSubmit(true, "form1");
	  $myCalendar->writeScript();
	  ?></td>
         
            <td style="font-family:Verdana, Arial, Helvetica, sans-serif ;background-color: #F8F8F8">End Date </td>
            <td  colspan="4"><?php
	  $myCalendar = new tc_calendar("weekenddate", true, false);
	  $myCalendar->setIcon("../img/iconCalendar.gif");
	  $myCalendar->setDate(date('d'), date('m'), date('Y'));
	  $myCalendar->setPath("./");
	  $myCalendar->setYearInterval($lowestdate, $currentdate);
	  //$myCalendar->dateAllow('1990-05-13', '2015-03-01');
	  $myCalendar->setDateFormat('j F Y');
	  //$myCalendar->setHeight(350);	  
	  //$myCalendar->autoSubmit(true, "form1");
	  $myCalendar->writeScript();
	  ?></td>
	  <td >
	  <input name="weeklyreports" type="submit" value="Summary" size="2" class="button"/>	   <input name="weeklyreport" type="submit" value="Detailed" size="2" class="button"/>	 <!--|  <input name="kitweeklyreport" type="submit" value="Generate Kit Used Report" size="2" class="button"/>	 -->   </td>
	</tr>
	<tr><td colspan="9">&nbsp;</td></tr>
	<tr>
	<td colspan="9" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2"><strong>Periodic Report</strong> </td>
	</tr>
	<tr>
	<td style="font-family:Verdana, Arial, Helvetica, sans-serif ;background-color: #F8F8F8">Quarterly</td>
            <td >
			<select name="quarterly" style="width:200px">
			<option value="0">Select One</option>
			<option value="1">January - March</option>
			<option value="2">April - June</option>
			<option value="3">July - September</option>
			<option value="4">October - December</option>
			</select>			</td>
     <td style="font-family:Verdana, Arial, Helvetica, sans-serif ;background-color: #F8F8F8">Year</td>
            <td colspan="4" ><?php
			$years = range ($maximumyear, $lowestdate); 
			
			// Make the years pull-down menu.
			echo '<select name="quarteryear" >';
				foreach ($years as $value)
			 	{
					echo "<option value=\"$value\">$value</option>\n";
				}
				
			echo '</select>';
	   
	  ?></td>    
	  <td >
	 <input name="periodicreports" type="submit" value="Summary" size="2" class="button"/>   <input name="periodicreport" type="submit" value="Detailed" size="2" class="button"/>	  <!--|  <input name="kitperiodicreport" type="submit" value="Generate Kit Used  Report" size="2" class="button"/>  --> </td>
	</tr>
	<tr>
	<td style="font-family:Verdana, Arial, Helvetica, sans-serif ;background-color: #F8F8F8">Month</td>
            <td >
			<select name="monthly" class="text" style="width:200px">
			<option value="0">Select One</option>
			<option value="1">January</option>
			<option value="2">February</option>
			<option value="3">March</option>
			<option value="4">April</option>
			<option value="5">May</option>
			<option value="6">June</option>
			<option value="7">July</option>
			<option value="8">August</option>
			<option value="9">September</option>
			<option value="10">October</option>
			<option value="11">November</option>
			<option value="12">December</option>
			</select>			</td>
     <td style="font-family:Verdana, Arial, Helvetica, sans-serif ;background-color: #F8F8F8">Year</td>
            <td colspan="4" ><?php
			$years = range ($maximumyear, $lowestdate); 
			
			// Make the years pull-down menu.
			echo '<select name="monthyear" >';
				foreach ($years as $value)
			 	{
					echo "<option value=\"$value\">$value</option>\n";
				}
				
			echo '</select>';
	   
	  ?></td>    
	  <td >
	<input name="monthlyreports" type="submit" value="Summary" size="2" class="button"/>   <input name="monthlyreport" type="submit" value="Detailed" size="2" class="button"/>	<!-- |  <input name="kitmonthlyreport" type="submit" value="Generate Kit Used Report" size="2" class="button"/>   --></td>
	</tr>
	<tr>
	<td style="font-family:Verdana, Arial, Helvetica, sans-serif ;background-color: #F8F8F8">Yearly</td>
          
            <td colspan="6" ><?php
			$years = range ($maximumyear, $lowestdate); 
			
			// Make the years pull-down menu.
			echo '<select name="yearly" style="width:200px">';
				foreach ($years as $value)
			 	{
					echo "<option value=\"$value\">$value</option>\n";
				}
				
			echo '</select>';
	   
	  ?></td>    
	  <td > <input name="yearreports" type="submit" value="Summary" size="2" class="button"/>  <input name="yearreport" type="submit" value="Detailed" size="2" class="button"/>  <!--|  <input name="kityearreport" type="submit" value="Generate Kit Used Report" size="2" class="button"/> --></td>
	</tr>
	  </table>
	     </form>
		 
		 
	    </div>
		</div>
		
 <?php include('../includes/footer.php');?>