<?php 
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
include('../includes/header.php');

$success=$_GET['p'];
$user = $_GET['username'];
$currentdate = date('Y'); //show the current year
$lowestdate = GetAnyDateMin(); //get the lowest year from date received
?>
<script language="javascript" src="calendar.js"></script>
<link type="text/css" href="calendar.css" rel="stylesheet" />	

<div>
	<div class="section-title">USER ACTIVITY LOG</div>
	
		<?php if ($success !="")
		{
		?> 
			<table   >
				<tr>
					<td style="width:auto" ><div class="success"><?php echo  '<strong>'.' <font color="#666600">'.$success.'</strong>'.' </font>';?></div></td>
				</tr>
			</table>
		<?php 
		} ?>		
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
			
		
		$showuser = "SELECT TaskID,dateofentry,user,task,timetaskdone,patient FROM usersactivity  ORDER BY TaskID DESC LIMIT $offset, $rowsPerPage ";
		$displayusers = @mysql_query($showuser) or die(mysql_error());
		$no=mysql_num_rows($displayusers);
		
		//get the record count
		$showtotaluser = "SELECT TaskID,dateofentry,user,task,timetaskdone,patient FROM usersactivity  ORDER BY TaskID DESC";
		$displaytotalusers = @mysql_query($showtotaluser) or die(mysql_error());
		$totalno=mysql_num_rows($displaytotalusers);
		
if ($totalno != 0)
{ 
	?>
	<div>
		<form name="filterform" method="get">
		<table border="0"   >
		<tr>
		
		<td>User</td>
		<td><!--show the lab types -->
		<?php
		   $accountquery = "SELECT ID,surname,oname FROM users ORDER BY surname ASC";
				
			$result = mysql_query($accountquery) or die('Error, query failed'); //onchange='submitForm();'
		
		   echo "<select name='username';>\n";
			echo " <option value=''> Select One </option>";
			
		  while ($row = mysql_fetch_array($result))
		  {
				$ID = $row['ID'];
				$surname = $row['surname'];
				$oname = $row['oname'];
			echo "<option value='$ID'><strong>$surname $oname</strong></option>\n";
		  }
		  echo "</select>\n";
		  
		?>
		<input type="submit" name="useraccessfilter" value="Filter" class="button"/><br/>
		</td>
		<td> | </td>
		<td>Access Date&nbsp;
		<?php
		  $myCalendar = new tc_calendar("logdate", true, false);
		  $myCalendar->setIcon("../img/iconCalendar.gif");
		  $myCalendar->setDate(date('d'), date('m'), date('Y'));
		  $myCalendar->setPath("./");
		  $myCalendar->setYearInterval($lowestdate, $currentdate);
		  //$myCalendar->dateAllow('2008-05-13', '2015-03-01');
		  $myCalendar->setDateFormat('j F Y');
		  //$myCalendar->setHeight(350);	  
		  //$myCalendar->autoSubmit(true, "form1");
		  $myCalendar->writeScript();
		 ?>
		<input type="submit" name="datefilter" value="Filter" class="button"/><br/>
		</td><td> | </td>
		<td><a href="log.php"><strong>Refresh</strong></a></td>
		
		</tr>
		</table>
		</form>
	</div>
		
	<?php
	
	if ($_REQUEST['datefilter'])
	{
		$logdate = $_GET['logdate'];
		$accessdate = date("d-m-Y",strtotime($_GET['logdate']));
		
		//get tehe filter information
		$showuseraccess = "SELECT TaskID,dateofentry,user,task,timetaskdone,patient FROM usersactivity WHERE dateofentry = '$logdate'  ORDER BY TaskID DESC LIMIT $offset, $rowsPerPage ";
		$displayusersaccess = @mysql_query($showuseraccess) or die(mysql_error());
		$accessno = mysql_num_rows($displayusersaccess);
		
		$dateaccess = date("d-M-Y",strtotime($accessdate));
		echo "The access log filter for <strong>$dateaccess</strong> returned <strong>$accessno</strong> results. <a href='log.php'><strong>Click here to refresh page.</strong></a>";
		echo "<table border=0 class='data-table'>
			<tr ><th>User</th><th>Time of Activity</th><th colspan=4>Activity Performed</th></tr>";

		$taskno=0;
		
		while(list($TaskID,$accessdate,$user,$task,$timetaskdone,$patient) = mysql_fetch_array($displayusersaccess))
		{  
			$accessdate=date("d-M-Y",strtotime($accessdate));
				//$taskno = $taskno + 1;
				//$sample =GetActualPatientID($patient);
				$usernames=GetUserFullnames($user);
				//$task=GetTaskName($task);
				$taskquery=mysql_query("SELECT name FROM activitys where ID='$task' ")or die(mysql_error()); 
				$dd=mysql_fetch_array($taskquery);
				$taskname=$dd['name'];
	
				include('activityperformed.php');
				
					echo "<tr class='even'>
							<td ><a href=\"filter_activity.php" ."?ID=$user" . "\" title='Click on User to view acitivity'>$usernames</a> </td>
							<td >$accessdate [$timetaskdone]</td>
							<td ><small>$taskname</small></td><td><small>$recordtype</small></td>
							<td><small>$record</small> </td>
							<td ><a href='$ulink'><small>View Record</small></a></td>
						</tr>";
		}echo "</table><br>";
		
		
		$numrows=GetTotalUserActivity(); //get total no of batches
	
		// how many pages we have when using paging?
		$maxPage = ceil($numrows/$rowsPerPage);
	
		// print the link to access each page
				$self = $_SERVER['PHP_SELF'];
				$nav  = '';
				for($page = 1; $page <= $maxPage; $page++)
				{
				   if ($page == $pageNum)
				   {
					  $nav .= " $page "; // no need to create a link to current page
				   }
				   else
				   {
					  $nav .= " <a href=\"$self?page=$page\">$page</a> ";
				   }
				}
				
				// creating previous and next link
				// plus the link to go straight to
				// the first and last page
				
				if ($pageNum > 1)
				{
				   $page  = $pageNum - 1;
				   $prev  = " <a href=\"$self?page=$page\"> | Prev </a> ";
				
				   $first = " <a href=\"$self?page=1\"> First Page </a> ";
				}
				else
				{
				   $prev  = '&nbsp;'; // we're on page one, don't print previous link
				   $first = '&nbsp;'; // nor the first page link
				}
				
				if ($pageNum < $maxPage)
				{
				   $page = $pageNum + 1;
				   $next = " <a href=\"$self?page=$page\"> | Next |</a> ";
				
				   $last = " <a href=\"$self?page=$maxPage\">Last Page</a> ";
				}
				else
				{
				   $next = '&nbsp;'; // we're on the last page, don't print next link
				   $last = '&nbsp;'; // nor the last page link
				}
				
				// print the navigation link
				echo '<center>'. $first . $prev . $next . $last .'</center>';
		
		/*$numrows = $accessno; //get total no of batches
	
		// how many pages we have when using paging?
		$maxPage = ceil($numrows/$rowsPerPage);
	
		// print the link to access each page
		$self = $_SERVER['PHP_SELF'];
		$nav  = '';
		for($page = 1; $page <= $maxPage; $page ++)
		{
		   if ($page == $pageNum)
		   {
			  $nav .= " $page "; // no need to create a link to current page
		   }
		   /*else
		   {
			  $nav .= " <a href=\"$self?page=$page\">$page</a> ";
		   }*/
		/*}
		
		// creating previous and next link
		// plus the link to go straight to
		// the first and last page
		
		if ($pageNum > 1)
		{
		   $page  = $pageNum - 1;
		   $prev  = " <a href=\"$self?page=$page\">Prev  |</a> ";
		
		   $first = " <a href=\"$self?page=1\">First Page | </a> ";
		}
		else
		{
		   $prev  = '&nbsp;'; // we're on page one, don't print previous link
		   $first = '&nbsp;'; // nor the first page link
		}
		
		if ($pageNum < $maxPage)
		{
		   $page = $pageNum + 1;
		   $next = " <a href=\"$self?page=$page\"> | Next | </a> ";
		
		   $last = " <a href=\"$self?page=$maxPage\">  Last Page </a> ";
		}
		else
		{
		   $next = '&nbsp;'; // we're on the last page, don't print next link
		   $last = '&nbsp;'; // nor the last page link
		}
		
		// print the navigation link
		echo '<center>'. $first . "  ". $prev  ." ". $nav . "  ". $next ."  ". $last .'</center>';
		
		exit();*/
	}
	else if ($_REQUEST['useraccessfilter'])
	{
		$user = $_GET['username'];
		//echo $user;
		
		//get the filter information
		$showuseraccesslog = "SELECT TaskID,dateofentry,user,task,timetaskdone,patient FROM usersactivity WHERE user = '$user' ORDER BY dateofentry DESC LIMIT $offset, $rowsPerPage";
$showuseraccesslog2 = mysql_query("SELECT TaskID,dateofentry,user,task,timetaskdone,patient FROM usersactivity WHERE user = '$user'") or die(mysql_error());
		$displayusersaccesslog = @mysql_query($showuseraccesslog) or die(mysql_error());
		$accessnolog = mysql_num_rows($showuseraccesslog2);
		
		//get the user's names
		$usernames = GetUserFullnames($user);
		
		echo "The access log filter for <strong>$usernames</strong> returned <strong>$accessnolog</strong> results. <a href='log.php'><strong>Click here to refresh page.</strong></a>";
		echo "<table border=0 class='data-table'>
			<tr ><th>User</th><th>Time of Activity</th><th colspan=4>Activity Performed</th></tr>";

		$taskno=0;
		
		while(list($TaskID,$accessdate,$user,$task,$timetaskdone,$patient) = mysql_fetch_array($displayusersaccesslog))
		{  
			$accessdate=date("d-M-Y",strtotime($accessdate));
				//$taskno = $taskno + 1;
				//$sample =GetActualPatientID($patient);
				$usernames=GetUserFullnames($user);
				//$task=GetTaskName($task);
				$taskquery=mysql_query("SELECT name FROM activitys where ID='$task' ")or die(mysql_error()); 
				$dd=mysql_fetch_array($taskquery);
				$taskname=$dd['name'];
	
				include('activityperformed.php');
				
					echo "<tr class='even'>
							<td ><a href=\"filter_activity.php" ."?ID=$user" . "\" title='Click on User to view acitivity'>$usernames</a> </td>
							<td >$accessdate [$timetaskdone]</td>
							<td ><small>$taskname</small></td><td><small>$recordtype</small></td>
							<td><small>$record</small> </td>
							<td ><a href='$ulink'><small>View Record</small></a></td>
						</tr>";
			}echo "</table><br>";
			
		$numrows=GetTotalUserActivity(); //get total no of batches
	
		// how many pages we have when using paging?
		$maxPage = ceil($numrows/$rowsPerPage);
	
		// print the link to access each page
				$self = $_SERVER['PHP_SELF'];
				$nav  = '';
				for($page = 1; $page <= $maxPage; $page++)
				{
				   if ($page == $pageNum)
				   {
					  $nav .= " $page "; // no need to create a link to current page
				   }
				   else
				   {
					  $nav .= " <a href=\"$self?page=$page\">$page</a> ";
				   }
				}
				
				// creating previous and next link
				// plus the link to go straight to
				// the first and last page
				
				if ($pageNum > 1)
				{
				   $page  = $pageNum - 1;
				   $prev  = " <a href=\"$self?page=$page\"> | Prev </a> ";
				
				   $first = " <a href=\"$self?page=1\"> First Page </a> ";
				}
				else
				{
				   $prev  = '&nbsp;'; // we're on page one, don't print previous link
				   $first = '&nbsp;'; // nor the first page link
				}
				
				if ($pageNum < $maxPage)
				{
				   $page = $pageNum + 1;
				   $next = " <a href=\"$self?page=$page\"> | Next |</a> ";
				
				   $last = " <a href=\"$self?page=$maxPage\">Last Page</a> ";
				}
				else
				{
				   $next = '&nbsp;'; // we're on the last page, don't print next link
				   $last = '&nbsp;'; // nor the last page link
				}
				
				// print the navigation link
				echo '<center>'. $first . $prev . $next . $last .'</center>';
		
		/*$numrows = $accessno; //get total no of batches
	
		// how many pages we have when using paging?
			
		// how many pages we have when using paging?
$NumberOfPages = ceil($accessnolog/$rowsPerPage);


$Nav="";
if($pageNum > 1) {
$Nav .= "<A HREF=\"log.php?page=" . ($pageNum-1) . "&username=" .urlencode($user) . "\"><<  Prev  </A>";
}
for($i = 1 ; $i <= $NumberOfPages ; $i++) {
if($i == $pageNum) {
$Nav .= "<B>  $i  </B>";
}else{
$Nav .= "<A HREF=\"log.php?page=" . $i . "&username=" .urlencode($user) . "\">  $i  </A>";
}
}
if($pageNum < $NumberOfPages) {
$Nav .= "<A HREF=\"log.php?page=" . ($pageNum+1) . "&username=" .urlencode($user) . "\">  Next   >></A>";
}
echo '<center>';
echo "<BR> <BR>" . $Nav; 
echo '<center>';
		
		
		
		exit();*/
	}
	
	else //..show the normal view
	{
		echo "<table border=0   class='data-table'>
				<tr ><th>User</th><th>Time of Activity</th><th colspan=4>Activity Performed</th></tr>";
	
			//$taskno=0;
			
			while(list($TaskID,$accessdate,$user,$task,$timetaskdone,$patient) = mysql_fetch_array($displayusers))
			{  
				$accessdate=date("d-M-Y",strtotime($accessdate));
				//$taskno = $taskno + 1;
				//$sample =GetActualPatientID($patient);
				$usernames=GetUserFullnames($user);
				//$task=GetTaskName($task);
				$taskquery=mysql_query("SELECT name FROM activitys where ID='$task' ")or die(mysql_error()); 
				$dd=mysql_fetch_array($taskquery);
				$taskname=$dd['name'];
	
				include('activityperformed.php');
				
					echo "<tr class='even'>
							<td ><a href=\"filter_activity.php" ."?ID=$user" . "\" title='Click on User to view acitivity'>$usernames</a> </td>
							<td >$accessdate [$timetaskdone]</td>
							<td ><small>$taskname</small></td><td><small>$recordtype</small></td>
							<td><small>$record</small> </td>
							<td ><a href='$ulink'><small>View Record</small></a></td>
						</tr>";
			}echo "</table><br>";
			
		$numrows=GetTotalUserActivity(); //get total no of batches
	
		// how many pages we have when using paging?
		$maxPage = ceil($numrows/$rowsPerPage);
	
		// print the link to access each page
				$self = $_SERVER['PHP_SELF'];
				$nav  = '';
				for($page = 1; $page <= $maxPage; $page++)
				{
				   if ($page == $pageNum)
				   {
					  $nav .= " $page "; // no need to create a link to current page
				   }
				   else
				   {
					  $nav .= " <a href=\"$self?page=$page\">$page</a> ";
				   }
				}
				
				// creating previous and next link
				// plus the link to go straight to
				// the first and last page
				
				if ($pageNum > 1)
				{
				   $page  = $pageNum - 1;
				   $prev  = " <a href=\"$self?page=$page\"> | Prev </a> ";
				
				   $first = " <a href=\"$self?page=1\"> First Page </a> ";
				}
				else
				{
				   $prev  = '&nbsp;'; // we're on page one, don't print previous link
				   $first = '&nbsp;'; // nor the first page link
				}
				
				if ($pageNum < $maxPage)
				{
				   $page = $pageNum + 1;
				   $next = " <a href=\"$self?page=$page\"> | Next |</a> ";
				
				   $last = " <a href=\"$self?page=$maxPage\">Last Page</a> ";
				}
				else
				{
				   $next = '&nbsp;'; // we're on the last page, don't print next link
				   $last = '&nbsp;'; // nor the last page link
				}
				
				// print the navigation link
				echo '<center>'. $first . $prev . $next . $last .'</center>';
	}
}
else
{

		echo "<table>
		  <tr>
			<td style='width:auto' ><div class='notice'><strong><font color='#666600'>No User Activity Recorded</strong></font>';</div></td>
		  </tr>
		</table>";
}  
?>  
	<!--***********************************************************	 -->
</div>
	
 <?php include('../includes/footer.php');?>