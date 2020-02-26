<?php
session_start();
require_once('nationaldashboardfunctions.php');
require_once('classes/tc_calendar.php');
$lowestdate = GetAnyDateMin(); //get the lowest year from date received
$maximumyear = GetMaxYear();
$currentdate = date('Y'); //show the current year

$period=intval($_GET['reportperiod']);


if ($period == '1')
{?>
	<br />
	<table>
		<tr>
			<td colspan="6" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2" ><strong><em>Please select the weekly date range.</em></strong></td>
		</tr>
		
		<tr>
			<td  >Start Date </td>
            <td   colspan="2" ><?php
			  $myCalendar = new tc_calendar("startdate", true, false);
			  $myCalendar->setIcon("img/iconCalendar.gif");
			  $myCalendar->setDate(date('d'), date('m'), date('Y'));
			  $myCalendar->setPath("./");
			  $myCalendar->setYearInterval($lowestdate, $currentdate);
			  $myCalendar->setDateFormat('j F Y');
			  $myCalendar->writeScript();
			  ?></td>
         
			<td   >End Date </td>
			<td  colspan="2" ><?php
			  $myCalendar = new tc_calendar("enddate", true, false);
			  $myCalendar->setIcon("img/iconCalendar.gif");
			  $myCalendar->setDate(date('d'), date('m'), date('Y'));
			  $myCalendar->setPath("./");
			  $myCalendar->setYearInterval($lowestdate, $currentdate);
			  $myCalendar->setDateFormat('j F Y');
			  $myCalendar->writeScript();
			  ?></td>
		</tr>
	</table>	
		
<?php
}
else if ($period == '2')
{?>
	<br />
	<table>
		<tr>
			<td colspan="" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2" ><strong><em>Please select the month</em></strong></td>
		<td height="10">
	<select name="monthly" style="width:200px">
		<option value="0">Select Month</option>
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
	</select></td>
	 <td ><?php
			$years = range ($maximumyear, $lowestdate); 
			
			// Make the years pull-down menu.
			echo '<select name="monthyear" >';
				foreach ($years as $value)
			 	{
					echo "<option value=\"$value\">$value</option>\n";
				}
				
			echo '</select>';
	   
	  ?></td>
</tr></table>
<?php	
}
else if ($period == '3')
{?>
	<br />
	<table>
		<tr>
			<td colspan="" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2" ><strong><em>Please select the quarter</em></strong></td>
		<td height="10">
	<select name="quarterly" style="width:200px">
		<option value="0">Select Quarter</option>
		<option value="1">1) January - March</option>
		<option value="2">2) April - June</option>
		<option value="3">3) July - September</option>
		<option value="4">4) October - December</option>
	</select></td>  <td ><?php
			$years = range ($maximumyear, $lowestdate); 
			
			// Make the years pull-down menu.
			echo '<select name="quarteryear" >';
				foreach ($years as $value)
			 	{
					echo "<option value=\"$value\">$value</option>\n";
				}
				
			echo '</select>';
	   
	  ?></td></tr></table>
<?php
}
else if ($period == '4')
{?>
	<br />
	<table>
		<tr>
			<td colspan="" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2" ><strong><em>Please select the bi-Annual</em></strong></td>
		<td height="10">
	<select name="biannual" style="width:200px">
		<option value="0">Select Bi-Annual</option>
		<option value="1">1) January - June</option>
		<option value="2">2) July - December</option>
	</select></td> <td ><?php
			$years = range ($maximumyear, $lowestdate); 
			
			// Make the years pull-down menu.
			echo '<select name="biannualyear" >';
				foreach ($years as $value)
			 	{
					echo "<option value=\"$value\">$value</option>\n";
				}
				
			echo '</select>';
	   
	  ?></td></tr></table>
<?php
}
else if ($period == '5')
{?>
	<br />
	<table>
		<tr>
			<td colspan="" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2" ><strong><em>Please select the Year</em></strong></td>
		<td colspan="3" ><?php
			$years = range ($maximumyear, $lowestdate); 
			
			// Make the years pull-down menu.
			echo '<select name="yearly" style="width:200px">';
				foreach ($years as $value)
			 	{
					echo "<option value=\"$value\">$value</option>\n";
				}
				
			echo '</select>';
	   
	  ?></td></tr></table>
<?php
}
/*else if ($period == '' || $period == '0')
{
	echo "";
}*/
?>
<!--style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2 

<font color="#333333" style="background-color:#999999"-->

