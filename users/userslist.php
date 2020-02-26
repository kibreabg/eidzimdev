<?php 
session_start();
require_once('../connection/config.php');
$success=$_GET['p'];
$userid = $_SESSION['uid'];
$searchparameter = $_GET['search'];
$deactsuccess=$_GET['deactsuccess'];
$resetsuccess=$_GET['resetsuccess'];
?>
<?php include('../includes/header.php');?>
<div>
	<div class="section-title">USERS LIST</div>
	<?php if ($resetsuccess !="")
		{
		?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="success"><?php 
		
echo  '<strong>'.' <font color="#666600">'.$resetsuccess.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
<?php } ?>	
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
<?php if ($deactsuccess !="")
		{
		?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="success"><?php 
		
echo  '<strong>'.' <font color="#666600">'.$deactsuccess.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
<?php } ?>		
				
				
	<!--*********************************************************** -->
	<?php
	if (empty($searchparameter))
	{
	?>
	<?php
	echo "<strong><small>Total Active Users: </strong>" .GetTotalUsers().'</small>';
	?>
	
	<?php
				$rowsPerPage = 20; //number of rows to be displayed per page
		
		// by default we show first page
		$pageNum = 1;
		
		// if $_GET['page'] defined, use it as page number
		if(isset($_GET['page']))
		{
		$pageNum = $_GET['page'];
		}
		
		// counting the offset
		$offset = ($pageNum - 1) * $rowsPerPage;
					
			
		$showuser = "SELECT ID,surname,oname,telephone,email,account,username,lab FROM users WHERE flag = 1 ORDER BY datecreated DESC LIMIT $offset, $rowsPerPage";
		$displayusers = @mysql_query($showuser) or die(mysql_error());
		$totalusers=mysql_num_rows($displayusers);
		
		if ($totalusers !=0)
		{
		?>
		
				<div>
					<form name="filterform" method="get">
					<table>
					<tr>
					<td><small>Account Type</small></td>
					<td><!--show the lab types -->
					<?php
				   $accountquery = "SELECT ID,name FROM usergroups";
						
					$result = mysql_query($accountquery) or die('Error, query failed'); //onchange='submitForm();'
				
				   echo "<select name='useraccount' style='font-size:10px';>\n";
					echo " <option value=''> Select One </option>";
					
				  while ($row = mysql_fetch_array($result))
				  {
						 $ID = $row['ID'];
						$name = $row['name'];
					echo "<option value='$ID'> $name</option>\n";
				  }
				  echo "</select>\n";
				  ?>
					<input type="submit" name="accountfilter" value="Filter" class="button"/><br/>
					</td>
					<td> | </td>
					<td><small>Active Status</small></td>
					  <td>
					  <select name="accountactive" style='font-size:10px'>
					  <option>Select Option</option>
					  <option value="1">Active Users</option>
					  <option value="0">Inactive Users</option>
					  </select>
					  <input type="submit" name="activityfilter" value="Filter" class="button"/>
					  </td>
					</tr>
					</table>
					</form>
				</div>
				<?php
				if ($_REQUEST['accountfilter'])
				{	$account = $_GET['useraccount'];			
					
					//get the account name
					
					$showuseractive = "SELECT ID,surname,oname,telephone,email,account,username,lab FROM users WHERE account = '$account' AND flag = 1";
					$displayactive = @mysql_query($showuseractive) or die(mysql_error());
					$displayactivecount = mysql_num_rows($displayactive);
					
					$accounttype = GetAccountType($account);
					
		echo "The search for <strong>$accounttype</strong> returned <strong>$displayactivecount</strong> results. <strong><a href='userslist.php'>Click to refresh page.</a></strong>";
						echo "<table border=0 class='data-table'>
					<tr ><th>Count</th><th>Full Names</th><th>Telephone No.</th><th>Email Address</th><th>Account Type</th><th>Lab</th><th>Username</th><th>Action</th></tr>";
						
						//initializa count
						$count=0;
							while(list($ID,$surname,$oname,$telephone,$email,$account,$username,$lab) = mysql_fetch_array($displayactive))
							{  
							//display user account type
							
							$labin=GetLab($lab);
							$count=$count+1;
							$unames = $surname.' '.$oname;
							
								echo "<tr class='even'>
										<td >$count</td>
										<td >$surname $oname</td>
										<td >$telephone</td>
										<td ><a href=mailto:$email title='Click to Send Email To User' >$email</a></td>
										<td >$accounttype</td>
										<td >$labin</td>
										<td >$username</td>
										<td ><a href='edituser.php?ID=$ID' title='Click to edit user details'>Edit </a> | <a href=\"resetpassword.php" ."?ID=$ID&name=$surname&email=$email&userid=$userid&unames=$unames" . "\" title='Click to Reset Password' OnClick=\"return confirm('Are you sure you want to Reset Password for  $surname $oname');\" >Reset Password  </a>  | <a href='deactivateuser.php?ID=$ID&name=$surname' title='Click to Deactivate User' OnClick=\"return confirm('Are you sure you want to deactivate account for  $surname $oname');\" >Deactivate  </a> 
					</td>
										</tr>";
										/*
										<td ><a href='edituser.php?ID=$ID' title='Click to edit user details'>Edit </a> | <a href=\"resetpassword.php" ."?ID=$ID&name=$surname&email=$email&userid=$userid" . "\" title='Click to Reset Password' OnClick=\"return confirm('Are you sure you want to Reset Password for  $surname $oname');\" >Reset Password  </a>  | <a href=\"deactivateuser.php" ."?ID=$ID&name=$surname" . "\" title='Click to Deactivate User' OnClick=\"return confirm('Are you sure you want to deactivate account for  $surname $oname');\" >Deactivate  </a> 
					</td>*/
							}echo "</table>";
						
						$numrows=$displayactivecount; //get total no of batches
			
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
			   $prev  = " <a href=\"$self?page=$page\">[Prev]</a> ";
			
			   $first = " <a href=\"$self?page=1\">[First Page]</a> ";
			}
			else
			{
			   $prev  = '&nbsp;'; // we're on page one, don't print previous link
			   $first = '&nbsp;'; // nor the first page link
			}
			
			if ($pageNum < $maxPage)
			{
			   $page = $pageNum + 1;
			   $next = " <a href=\"$self?page=$page\">[Next]</a> ";
			
			   $last = " <a href=\"$self?page=$maxPage\">[Last Page]</a> ";
			}
			else
			{
			   $next = '&nbsp;'; // we're on the last page, don't print next link
			   $last = '&nbsp;'; // nor the last page link
			}
			
			// print the navigation link
			echo '<center>'. ' Page ' .$first . $prev . $nav . $next . $last .'</center>';
			
				
					exit();
					
				}
				else if ($_REQUEST['activityfilter'])
				{
					$accountactive = $_GET['accountactive'];			
					
					//get the activity name
					if ($accountactive == 1)
					{
						$activityname = "Active Users";
						
							$showactive = "SELECT ID,surname,oname,telephone,email,account,username,lab FROM users WHERE flag = $accountactive";
					$displayuseractive = @mysql_query($showactive) or die(mysql_error());
					$displayusercount = mysql_num_rows($displayuseractive);
					
		echo "The search for <strong>$activityname</strong> returned <strong>$displayusercount</strong> results. <strong><a href='userslist.php'>Click to refresh page.</a></strong>";
						echo "<table border=0 class='data-table'>
					<tr ><th>Count</th><th>Full Names</th><th>Telephone No.</th><th>Email Address</th><th>Account Type</th><th>Lab</th><th>Username</th><th>Action</th></tr>";
						
						//initializa count
						$count=0;
							while(list($ID,$surname,$oname,$telephone,$email,$account,$username,$lab) = mysql_fetch_array($displayuseractive))
							{  
							//display user account type
							$accounttype = GetAccountType($account);
							$labin=GetLab($lab);
							$count=$count+1;
							$unames = $surname.' '.$oname;
							
								echo "<tr class='even'>
										<td >$count</td>
										<td >$surname $oname</td>
										<td >$telephone</td>
										<td ><a href=mailto:$email title='Click to Send Email To User' >$email</a></td>
										
										<td >$accounttype</td>
										<td >$labin</td>
										<td >$username</td>
										<td ><a href='edituser.php?ID=$ID' title='Click to edit user details'>Edit </a> | <a href=\"resetpassword.php" ."?ID=$ID&name=$surname&email=$email&userid=$userid&unames=$unames" . "\" title='Click to Reset Password' OnClick=\"return confirm('Are you sure you want to Reset Password for  $surname $oname');\" >Reset Password  </a>  | <a href='deactivateuser.php?ID=$ID&name=$surname' title='Click to Deactivate User' OnClick=\"return confirm('Are you sure you want to deactivate account for  $surname $oname');\" >Deactivate  </a> 
					</td>
										</tr>";
										/*<td ><a href='edituser.php?ID=$ID' title='Click to edit user details'>Edit </a> | <a href=\"resetpassword.php" ."?ID=$ID&name=$surname&email=$email" . "\" title='Click to Reset Password' OnClick=\"return confirm('Are you sure you want to Reset Password for  $surname $oname');\" >Reset Password  </a>  | <a href=\"deactivateuser.php" ."?ID=$ID&name=$surname" . "\" title='Click to Deactivate User' OnClick=\"return confirm('Are you sure you want to deactivate account for  $surname $oname');\" >Deactivate  </a> 
					</td>*/
							}echo "</table>";
						
						$numrows=$displayusercount; //get total no of batches
			
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
						   $prev  = " <a href=\"$self?page=$page\">[Prev]</a> ";
						
						   $first = " <a href=\"$self?page=1\">[First Page]</a> ";
						}
						else
						{
						   $prev  = '&nbsp;'; // we're on page one, don't print previous link
						   $first = '&nbsp;'; // nor the first page link
						}
						
						if ($pageNum < $maxPage)
						{
						   $page = $pageNum + 1;
						   $next = " <a href=\"$self?page=$page\">[Next]</a> ";
						
						   $last = " <a href=\"$self?page=$maxPage\">[Last Page]</a> ";
						}
						else
						{
						   $next = '&nbsp;'; // we're on the last page, don't print next link
						   $last = '&nbsp;'; // nor the last page link
						}
						
						// print the navigation link
						echo '<center>'. ' Page ' .$first . $prev . $nav . $next . $last .'</center>';
						
							
						exit();
					
					}
					else
					{
						$activityname = "Deactivated Users";
						
							$showactive = "SELECT ID,surname,oname,telephone,email,account,username,lab FROM users WHERE flag = $accountactive";
					$displayuseractive = @mysql_query($showactive) or die(mysql_error());
					$displayusercount = mysql_num_rows($displayuseractive);
					
		echo "The search for <strong>$activityname</strong> returned <strong>$displayusercount</strong> results. <strong><a href='userslist.php'>Click to refresh page.</a></strong>";
						echo "<table border=0 class='data-table'>
					<tr ><th>Count</th><th>Full Names</th><th>Telephone No.</th><th>Email Address</th><th>Account Type</th><th>Lab</th><th>Username</th><th>Action</th></tr>";
						
						//initializa count
						$count=0;
							while(list($ID,$surname,$oname,$telephone,$email,$account,$username,$lab) = mysql_fetch_array($displayuseractive))
							{  
							//display user account type
							$accounttype = GetAccountType($account);
							$labin=GetLab($lab);
							$count=$count+1;
							$unames = $surname.' '.$oname;
							
								echo "<tr class='even'>
										<td >$count</td>
										<td >$surname $oname</td>
										<td >$telephone</td>
										<td ><a href=mailto:$email title='Click to Send Email To User' >$email</a></td>
										
										<td >$accounttype</td>
										<td >$labin</td>
										<td >$username</td>
										<td ><a href=\"activateuser.php" ."?ID=$ID&name=$surname" . "\" title='Click to Activate User' OnClick=\"return confirm('Are you sure you want to activate account for  $surname $oname');\" >Activate</a> 
					</td>
										</tr>";
							}echo "</table>";
						
						$numrows=$displayusercount; //get total no of batches
			
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
					   $prev  = " <a href=\"$self?page=$page\">[Prev]</a> ";
					
					   $first = " <a href=\"$self?page=1\">[First Page]</a> ";
					}
					else
					{
					   $prev  = '&nbsp;'; // we're on page one, don't print previous link
					   $first = '&nbsp;'; // nor the first page link
					}
					
					if ($pageNum < $maxPage)
					{
					   $page = $pageNum + 1;
					   $next = " <a href=\"$self?page=$page\">[Next]</a> ";
					
					   $last = " <a href=\"$self?page=$maxPage\">[Last Page]</a> ";
					}
					else
					{
					   $next = '&nbsp;'; // we're on the last page, don't print next link
					   $last = '&nbsp;'; // nor the last page link
					}
					
					// print the navigation link
					echo '<center>'. ' Page ' .$first . $prev . $nav . $next . $last .'</center>';
					
				
					exit();
					
					}
					
				
				}
				
				//////////////////////NORMAL DISPLAY//////////////////////////////////////////////////
				
					echo "<table border=0   class='data-table'>
					<tr ><th>Count</th><th>Full Names</th><th>Telephone No.</th><th>Email Address</th><th>Account Type</th><th>Lab</th><th>Username</th><th>Action</th></tr>";
						
						//initializa count
						$count=0;
							while(list($ID,$surname,$oname,$telephone,$email,$account,$username,$lab) = mysql_fetch_array($displayusers))
							{  
							//display user account type
							$accounttype = GetAccountType($account);
							$labin=GetLab($lab);
							$count=$count+1;
							$unames = $surname.' '.$oname;
							
								echo "<tr class='even'>
										<td >$count</td>
										<td >$surname $oname</td>
										<td >$telephone</td>
										<td ><a href=mailto:$email title='Click to Send Email To User' >$email</a></td>
										<td >$accounttype</td>
										<td >$labin</td>
										<td >$username</td>
										<td ><a href='edituser.php?ID=$ID' title='Click to edit user details'>Edit </a> | <a href=\"resetpassword.php" ."?ID=$ID&name=$surname&email=$email&userid=$userid&unames=$unames" . "\" title='Click to Reset Password' OnClick=\"return confirm('Are you sure you want to Reset Password for  $surname $oname');\" >Reset Password  </a>  | <a href='deactivateuser.php?ID=$ID&name=$surname' title='Click to Deactivate User' OnClick=\"return confirm('Are you sure you want to deactivate account for  $surname $oname');\" >Deactivate  </a> 
					</td>
										</tr>";
										/*
										<td ><a href='edituser.php?ID=$ID' title='Click to edit user details'>Edit </a> | <a href=\"resetpassword.php" ."?ID=$ID&name=$surname&email=$email" . "\" title='Click to Reset Password' OnClick=\"return confirm('Are you sure you want to Reset Password for  $surname $oname');\" >Reset Password  </a>  | <a href=\"deactivateuser.php" ."?ID=$ID&name=$surname" . "\" title='Click to Deactivate User' OnClick=\"return confirm('Are you sure you want to deactivate account for  $surname $oname');\" >Deactivate  </a> 
					</td>
										*/
							}echo "</table>";
						
						$numrows=GetTotalUsers(); //get total no of batches
			
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
			   $prev  = " <a href=\"$self?page=$page\">[Prev]</a> ";
			
			   $first = " <a href=\"$self?page=1\">[First Page]</a> ";
			}
			else
			{
			   $prev  = '&nbsp;'; // we're on page one, don't print previous link
			   $first = '&nbsp;'; // nor the first page link
			}
			
			if ($pageNum < $maxPage)
			{
			   $page = $pageNum + 1;
			   $next = " <a href=\"$self?page=$page\">[Next]</a> ";
			
			   $last = " <a href=\"$self?page=$maxPage\">[Last Page]</a> ";
			}
			else
			{
			   $next = '&nbsp;'; // we're on the last page, don't print next link
			   $last = '&nbsp;'; // nor the last page link
			}
			
			// print the navigation link
			echo '<center>'. ' Page ' .$first . $prev . $nav . $next . $last .'</center>';
			
				
					
					
		}
		else
		{
		?>
		<table   >
				  <tr>
					<td style="width:auto" >
					<div class="notice">
					<?php echo  '<strong>'.' <font color="#666600">'. 'No Users Added'.'</strong>'.' </font>';?></div>
					</td>
				  </tr>
				</table>
				<?php } ?>
	<!--***********************************************************	 -->
		<?php
	}
	else if (!empty($searchparameter))
	{
		
		$showuser = "SELECT LTRIM(RTRIM(surname)),ID,LTRIM(RTRIM(oname)),telephone,email,account,username,lab FROM users WHERE surname LIKE '%$searchparameter%' OR oname LIKE '%$searchparameter%' AND flag = 1 ORDER BY datecreated DESC";
		$displayusers = @mysql_query($showuser) or die(mysql_error());
		$displaycount = mysql_num_rows($displayusers);
		
		echo "The search for <strong><em>$searchparameter</em></strong> returned <strong>$displaycount</strong> results. <strong><a href='userslist.php'>Click to refresh page.</a></strong>";
		echo "<table border=0   class='data-table'>
		<tr ><th>Count</th><th>Full Names</th><th>Telephone No.</th><th>Email Address</th><th>Account Type</th><th>Lab</th><th>Username</th><th>Action</th></tr>";
				
			
			$userdisplay=0;
			while(list($surname,$ID,$oname,$telephone,$email,$account,$username,$lab) = mysql_fetch_array($displayusers))
			{  
			//display user account type
				$accounttype = GetAccountType($account);
				$labin=GetLab($lab);
				
				$userdisplay=$userdisplay + 1;
				$unames = $surname.' '.$oname;
				
				echo "<tr class='even'>
						<td >$userdisplay</td>
						<td >$surname $oname</td>
						<td >$telephone</td>
						<td >$email</td>
						<td >$accounttype</td>
						<td >$labin</td>
						<td >$username</td>
						<td ><a href=\"" ."?ID=$ID" . "\" title='Click to edit user details'>Edit</a>  | <a href=\"resetpassword.php" ."?ID=$ID&name=$surname&email=$email&userid=$userid&unames=$unames" . "\" title='Click to Reset Password' OnClick=\"return confirm('Are you sure you want to Reset Password for  $surname $oname');\" >Reset Password  </a> | <a href=\"deactivateuser.php" ."?ID=$ID&name=$surname" . "\" title='Click to Deactivate User' OnClick=\"return confirm('Are you sure you want to deactivate account for  $surname $oname');\" >Deactivate  </a> 
</td>
						</tr>";
			}
		?>
		</table>
	<?php 
	}
	?>
		
</div>

		
 <?php include('../includes/footer.php');?>