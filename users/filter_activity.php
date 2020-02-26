<?php 
session_start();
require_once('../connection/config.php');
$success=$_GET['p'];
include('../includes/header.php');
$userID=$_GET['ID'];
$usernames=GetUserFullnames($userID);
?>

<div>
	<div class="section-title">USER ACTIVITY LOG FOR <?php echo $usernames; ?></div>
	
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
		$rowsPerPage = 50; //number of rows to be displayed per page

// by default we show first page
$pageNum = 1;

// if $_GET['page'] defined, use it as page number
if(isset($_GET['page']))
{
$pageNum = $_GET['page'];
}

// counting the offset
$offset = ($pageNum - 1) * $rowsPerPage;
			
		$showuser = "SELECT TaskID,dateofentry,user,task,timetaskdone,patient FROM usersactivity WHERE user='$userID' ORDER BY TaskID DESC LIMIT $offset, $rowsPerPage ";
		$displayusers = @mysql_query($showuser) or die(mysql_error());
		$nooftasks =GetTotalSingleUserActivity($userID);
		$no=mysql_num_rows($displayusers);
if ($no !=0)
{ 
	?>
	
	<table border="0"   class="data-table">
	<tr ><th><small>User</small></th><th><small>Time of Activity</small></th><th colspan=4><small>Activity Performed</small></th></tr>

	<?php	
		$taskno=0;
		while(list($TaskID,$dateofentry,$user,$task,$timetaskdone,$patient) = mysql_fetch_array($displayusers))
		{  
			$accessdate=date("d-M-Y",strtotime($dateofentry));
			$usernames=GetUserFullnames($user);
			
			$taskquery=mysql_query("SELECT name FROM activitys where ID='$task' ")or die(mysql_error()); 
			$dd=mysql_fetch_array($taskquery);
			$taskname=$dd['name'];
			
			echo "<tr>
					<td ><small><a href=filter_activity.php?ID=$user title='click to view activity for selected user'>$usernames</a></small></td>
					<td ><small>$accessdate [$timetaskdone]</small></td>
					<td ><small>$taskname</small></td>";
					
					include('activityperformed.php');
			
			echo "
				<td><small>$recordtype</small></td>
				<td><small>$record</small> </td>
				<td ><a href='$ulink'><small>View Record</small></a></td>
		</tr>";
		}
	?>
  </table>
	<?php	
	
	echo '<br>';
	$numrows=GetTotalSingleUserActivity($userID); //get total no of batches

	$NumberOfPages = ceil($numrows/$rowsPerPage);


$Nav="";
if($pageNum > 1) {
$Nav .= "<A HREF=\"filter_activity.php?page=" . ($pageNum-1) . "&ID=" .urlencode($userID) . "\"><<  Prev  </A>";
}
for($i = 1 ; $i <= $NumberOfPages ; $i++) {
if($i == $pageNum) {
$Nav .= "<B>  $i  </B>";
}else{
$Nav .= "<A HREF=\"filter_activity.php?page=" . $i . "&ID=" .urlencode($userID) . "\">  $i  </A>";
}
}
if($pageNum < $NumberOfPages) {
$Nav .= "<A HREF=\"filter_activity.php?page=" . ($pageNum+1) . "&ID=" .urlencode($userID) . "\">  Next   >></A>";
}
echo '<center>';
echo  $Nav; 
echo '<center>';

	
	}

else
{
?>
<table   >
  <tr>
    <td style="width:auto" ><div class="notice"><?php 
		
echo  '<strong>'.' <font color="#666600">'.'No User Activity Recorded'.'</strong>'.' </font>';

?></div></th>
  </tr>
</table><?php
}  
  ?>  
	<!--***********************************************************	 -->
		
		
</div>

		
 <?php include('../includes/footer.php');?>