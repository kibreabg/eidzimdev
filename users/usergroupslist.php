<?php 
require_once('../connection/config.php');

$success=$_GET['p'];
?>
<?php include('../includes/header.php');?>
<div>
	<div class="section-title">USERS LIST</div>
	
		<?php if ($success !="")
		{
		?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="success"><?php 
		
echo  '<strong>'.' <font color="#666600">'.$success.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
<?php } ?>		
	<!--*********************************************************** -->
	<?php
		
			
		$showuser = "SELECT ID,name FROM usergroups ";
		$displayusers = @mysql_query($showuser) or die(mysql_error());
		$no=mysql_num_rows($displayusers);
if ($no !=0)
{ 
	?>
	<table border="0"   class="data-table">
	<tr ><th>Group ID</th><th>Name</th><th>Task</th></tr>

	<?php	
		
		while(list($ID,$name) = mysql_fetch_array($displayusers))
		{  
			echo "<tr class='even'>
					<td >$ID</td>
					<td >$name</td>
					<td ><a href=\"editusergroup.php" ."?ID=$ID" . "\" title='Click to edit user groups details'>Edit</a> 
</td>
					</tr>";
		}
	?>
		</table>
	<?php	}

else
{
?>
<table   >
  <tr>
    <td style="width:auto" ><div class="notice"><?php 
		
echo  '<strong>'.' <font color="#666600">'.'No User Groups Added'.'</strong>'.' </font>';

?></div></th>
  </tr>
</table><?php
}  
  ?>  
	<!--***********************************************************	 -->
		
		
</div>

		
 <?php include('../includes/footer.php');?>