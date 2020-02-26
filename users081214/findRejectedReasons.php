
<?php
#### Roshan's Ajax dropdown code with php
#### Copyright reserved to Roshan Bhattarai - nepaliboy007@yahoo.com
#### if you have any problem contact me at http://roshanbh.com.np
#### fell free to visit my blog http://roshanbh.com.np
?>

<?php 
require('../connection/config.php');
$rejid=intval($_GET['rejid']);
if ($rejid == 2)  
{
?>
		<?php
		$query="SELECT ID,Name FROM rejectedreasons";
		$result=mysql_query($query)or die('error');
		
		?><div><font color="#FF0000">
			<select name="rejectedreason" id="rejectedreason" style='width:188px; color:#FF0000'>
				<option value="">Select Rejected Reason</option>
				<?php while($row=mysql_fetch_array($result)) { ?>
				<option value=<?php echo $row['ID'];?>><?php echo $row['Name'];?></option>
				<?php } ?>
			</select></font>
			<span id="rejectedreasonInfo"></span></div>
	
	<?php
}
else if ($rejid == 3) 
{?><font color="#0000CC">
		<select name="repeatreason" id="repeatreason" style='width:188px; color:#0000FF'>
			<option value="">Select a Reason for Repeat</option>
			<option  value="Repeat For Rejection">Repeat For Rejection </option>
			<option  value="Confirmatory PCR at 9 Mths">Confirmatory PCR at 9Mths</option>
		
		</select>
		<span id="repeatreasonInfo"></span>
	</font>
<?php
}
else
{
}
?>

