<?php 
require('nationaldashboardfunctions.php');
$mwaka=$_GET['year'];
$mwezi=$_GET['mwezi'];
$lowestdate = GetAnyDateMin(); //get the lowest year from date received
$maximumyear = GetMaxYear();
$currentdate = date('Y'); //show the current year
$rejid=intval($_GET['rejid']);
if ($rejid ==1)   //monthly
{
?>
<table>
<tr>
	
   
	  
      <td ><select name="monthly" class="text" style="width:200px">
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
        </select>      </td>
      <td height="24" >Year</td>
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

</tr>
</table>
<?php
}
else if ($rejid ==2) 
{?>
<table>
<tr>
    
      
      <td ><select name="quarterly" style="width:200px">
          <option value="0">Select Quarter</option>
          <option value="1">January - March</option>
          <option value="2">April - June</option>
          <option value="3">July - September</option>
          <option value="4">October - December</option>
        </select>      </td>
      <td height="24" >Year</td>
      <td ><?php
			$years = range ($maximumyear, $lowestdate); 
			
			// Make the years pull-down menu.
			echo '<select name="quarteryear" >';
				foreach ($years as $value)
			 	{
					echo "<option value=\"$value\">$value</option>\n";
				}
				
			echo '</select>';
	   
	  ?></td>

</tr>
</table>
<?php
}
else if ($rejid ==3) 
{?>
<table>
<tr>
     
	    
      <td ><select name="biannual" style="width:200px">
          <option value="0">Select </option>
          <option value="1">January - June</option>
          <option value="2">July - December</option>
          </select>      </td>
      <td height="24" >Year</td>
      <td ><?php
			$years = range ($maximumyear, $lowestdate); 
			
			// Make the years pull-down menu.
			echo '<select name="biannualyear" >';
				foreach ($years as $value)
			 	{
					echo "<option value=\"$value\">$value</option>\n";
				}
				
			echo '</select>';
	   
	  ?></td>

</tr>
</table>
<?php
}
else if ($rejid ==4) 
{?>
<table>
<tr>
     
      <td colspan="3" ><?php
			$years = range ($maximumyear, $lowestdate); 
			
			// Make the years pull-down menu.
			echo '<select name="yearly" style="width:200px">';
				foreach ($years as $value)
			 	{
					echo "<option value=\"$value\">$value</option>\n";
				}
				
			echo '</select>';
	   
	  ?></td>

</tr>
</table>
<?php
}
else
{
}
?>

