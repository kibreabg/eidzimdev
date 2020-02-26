<?php 
require_once('../connection/config.php');
include('../includes/header.php');?>
<link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
<style type="text/css">
<!--
.style2 {color: #F0F0F0}
-->
</style>			  
						
<div>
	<div class="section-title">Navigation</div>
	<!--table div -->
	<div >
	<table >
	<tr>
	<td><a href="userslist.php"><img src="../img/users.png" alt="Users" title="Click to View Users" longdesc="#" align="middle"/></a>	  <p align="center"><a href="userslist.php">Users</a></p></td>
	<td><a href="facilitieslist.php"><img src="../img/Home.png"  alt="Facilities" title="Click to View Facilities" longdesc="#" align="middle"/>
	  <p align="center">Facilities</p>
	  </a></td>
	<td><a href="districtslist.php"><img src="../img/dist.png" alt="Districts" title="Click to View Districts" longdesc="#" align="middle" /><p align="center">Districts</p></a></td>
	<td><a href="log.php">
	  <img src="../img/access.png" alt="Access Logs" title="Click to View Access Logs" longdesc="#" align="middle" />
	  <p align="center">Access Log</p></a></td>
	
	
	</tr>
	</table>
	</div>
 	<!--end table div-->
	<!--Access Log Div -->
	<div>
	<table width="50%">
	<tr bgcolor="#FAFAFA">
	  <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2; width:820px"><strong>Access Log</strong></td>
	</tr>
	</table>
	<?php 

//query database for all districts
   $qury = "SELECT TaskID,user,task,patient,dateofentry,timetaskdone
            FROM usersactivity
			ORDER BY TaskID DESC
			LIMIT 5";
			
			$result = mysql_query($qury) or die('Error, query failed');
$no=mysql_num_rows($result);
if ($no !=0)
{
// print the districts info in table
echo '<table border="0" class="data-table">
 <tr ><th><small>User</small></th><th><small>Time of Activity</small></th><th colspan=4><small>Activity Performed</small></th></tr>';
	while(list($TaskID,$user,$task,$patient,$dateofentry,$timetaskdone) = mysql_fetch_array($result))
	{  
	
		//get access date
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
				/*//check which activity it is
			if (($task == 1) || ($task == 3) || ($task == 4)) // concerns add, delete, approve sample then get sample accession no
			{ 	//get sample that has the accession no
				$staskquery=mysql_query("SELECT ID FROM samples where ID='$patient' ")or die(mysql_error()); 
				$sdd=mysql_fetch_array($staskquery);
				$patientid=$sdd['ID'];

				$ulink= 'sample_details.php?ID='.$patientid;

				$recordtype = 'Request No';
				$record = $patient;
			}
			
			else if ($task == 2) //edit sample show which record
			{
				//get sample that has the accession no
				$astaskquery=mysql_query("SELECT patient FROM samples where ID=$patient")or die(mysql_error()); 
				$asdd=mysql_fetch_array($astaskquery);
				$sampleid=$asdd['patient'];

				$ulink= 'sample_details.php?ID='.$patient;
				$recordtype = 'Request No';
				$record = $sampleid;
			}
			
			else if (($task == 5) || ($task == 6) || ($task == 7) || ($task == 8)) // create, flag, update, review worksheet then show worksheet no
			{ 	$ulink= 'worksheetdetails.php?ID='.$patient;
				$recordtype = 'Worksheet No';
				$record = $patient;
			}
			
			else if (($task == 9) || ($task == 23)) //dispatch, release samples then get the batch no
			{ 	$ulink= 'BatchDetails.php?ID='.$patient;
				$recordtype = 'Batch No';
				$record = $patient;
			}
			
			else if (($task == 16) || ($task == 17) || ($task == 18)) //add,edit,delete facility
			{ 	$ulink= 'facilitydetails.php?ID='.$patient;
				$recordtype = 'Health Unit';
				$record = GetFacilityName($patient);
			}
			
			else if (($task == 19) || ($task == 20) || ($task == 21)) //add,edit,delete district
			{ 	$ulink= '#';
				$recordtype = 'District';
				$record = GetDistrictName($patient);
			}

			else if (($task == 12) || ($task == 13) || ($task == 14) || ($task == 15)) //add,edit,deactivate,activate user
			{ 	$ulink= '#';
				$recordtype = 'User';
				$userinfo = GetUserInfo($patient);
				$record = $userinfo['surname'].' '.$userinfo['oname'];
			}*/
		echo "
				<td><small>$recordtype</small></td>
				<td><small>$record</small> </td>
				<td ><a href='$ulink'><small>View Record</small></a></td>
		</tr>";
		/*
		<td ><small><a href=sample_details.php?ID=$patient title='click to view samples '>$patient</a></small></td>*/
	}
	echo "
	<tr>
		<th colspan=6><div align='right'><a href='log.php'><small>More Listings...</small></div></a></th>
	</tr>";
	echo '</table>';
	echo '<br>';
// print the navigation link

}

else
{
 echo '<center>' . 'No Access Logs' .'</center>';
	echo '<br>';
 //echo "<a href=\"createacct2.php"  . "\">Click here to set up an account</a>";

 }  
 
  ?>
	</div>
	<!--End Access Log Div -->
</div>
<?php include('../includes/footer.php');?>

