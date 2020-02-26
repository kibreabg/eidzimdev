<?php 
$sessionuserid = $_SESSION['uid']; //get the user id for this session		

require_once('../connection/config.php');
require_once('../includes/header.php');

	//get the user info
	$user = $_GET['ID'];

	$userinfo = GetUserInfo($user);
		$surname = $userinfo['surname'];
		$oname = $userinfo['oname'];
		$email = $userinfo['email'];
		$enumber = $userinfo['enumber'];
		$username = $userinfo['username'];
		$useraccount = $userinfo['account'];
		$datecreated = $userinfo['datecreated'];
		$datemodified = $userinfo['datemodified'];
		
		$useraccountname = UserAccountName($useraccount); //get the user's account type

if ($_REQUEST['update'])
{
	//get the variables
	$datemodified = date('Y-m-d');
	$user = $_GET['userid'];
	$surname = $_GET['surname'];
	$oname= $_GET['oname'];
	$enumber= $_GET['enumber'];
	$email= $_GET['email'];
	$username= $_GET['username'];
	
	//check whether the checkbox is checked and update value
	$checkaccount = $_GET['checkaccount'];
		
	if ($checkaccount != '')
 	{
		$edituseraccount = $_GET['edituseraccount'];
		
		$editedaccountrecord = mysql_query("UPDATE users SET useraccount = '$edituseraccount' WHERE (id = '$user')")or die(mysql_error());	
	}
	
	$updateuser = UpdateUser($surname,$oname,$enumber,$email,$username,$datemodified,$user);
		
		if (($updateuser) || ($editedaccountrecord))
		{
				$description = $surname.", ".$oname; //save the name 
				$activity = 17; //activity in the activitys table
				$particular = $user; //save the user id
				
				$useractivity = SaveUserActivity($sessionuserid,$activity,$particular,$description,$sessionid); //save the user activity in the users_activity table
							
				echo '<script type="text/javascript">' ;
				echo "window.location.href='userslist.php?user=$user'"; //direct to patient list view
				echo '</script>';
				
			  	exit();

		}
		else
		{
				$st="Update User Failed, please try again.";
		}
	
}
?>

<style type="text/css">
select 
{
width: 250;

}
</style>	
	

		<div  class="section">
		<div class="section-title">EDIT USER DETAILS </div>
		<div class="xtop">
		<A HREF="javascript:history.back(-1)"><img src="../img/back.gif" alt="Go Back"/></A>
		
		<form autocomplete="off"  method="get" action=""  name="userform">
		 	<table>
				<tr>
					<td colspan="4" align="right"><small><strong>Date Last Modified</strong>&nbsp;<?php echo $datemodified=date('d-M-Y',strtotime($datemodified))?></small></td>
				</tr>
			</table>
		 	<table class="data-table">
				
				<tr >
                  <th colspan="4" >Personal Information </th>
                </tr>
				<tr class="even">
                  <td ><span class="mandatory">*</span> Surname</td>
                  <td ><input type="hidden" name="userid" size="10" class="text" value="<?php echo $user?>"/>
                    <input type="text" name="surname" size="20" class="text" value="<?php echo $surname?>"/>
                  </td>
				  <td><span class="mandatory">*</span> Other Names </td>
                  <td><input type="text" name="oname" size="30" class="text" value="<?php echo $oname?>"/></td>
				</tr>
              
				 <tr class="even">
                 <td><span class="mandatory">*</span> Email Address</td>
                  <td colspan=""><input type="text" name="email" size="30" class="text" value="<?php echo $email?>"/>                    </strong></td>
				 </tr>
				<tr class="even">
                  <td colspan="7">&nbsp;</td>
				</tr>
				
				<tr >
                  <th colspan="4">Account Information </th>
                </tr>
				
				 <tr class="even">
                  <td><span class="mandatory">*</span> UserName</td>
                  <td colspan="4"><input type="text" name="username" size="20" class="text" value="<?php echo $username?>"/></td>
				 </tr>
				 
				 <tr class="even">
                  <td>Account Type  </td>
				 <td colspan="4"><input type="checkbox" name="checkaccount" value="checkaccount"/>
				   <?php echo $useraccountname?>&nbsp;<?php
				  	$accountname = "SELECT id,name FROM usergroups";
						
					$result = mysql_query($accountname) or die('Error, query failed'); //onchange='submitForm();'
				
				  	echo "<select name='edituseraccount' ;>\n";
					echo " <option value=''> Select One </option>";
					
					  while ($row = mysql_fetch_array($result))
					  {
							 $ID = $row['id'];
							$name = $row['name'];
						echo "<option value='$ID'> $name</option>\n";
					  }
					  
					echo "</select>\n";
				  ?></td>
				</tr>
				
				 <tr>
                   <th colspan="4">
				   <input name="update" type="submit" value="Update Changes" style="border-style:ridge" onclick="" class="button"/>
				   </th>
				 </tr>
          </table>
		 
		</form>
		
		</div>
		</div>
<script language="JavaScript" type="text/javascript">
//You should create the validator only after the definition of the HTML form
  var frmvalidator  = new Validator("userform");
 
  
   frmvalidator.addValidation("surname","req","Please enter the Surname");
  frmvalidator.addValidation("surname","maxlen=20","The maximum length for the surname is 20");
  
  frmvalidator.addValidation("username","req","Please enter the UserName");
  frmvalidator.addValidation("username","maxlen=20",	"Max length for UserName is 20");
  
  frmvalidator.addValidation("oname","req","Please enter the user's Other Names");
  frmvalidator.addValidation("oname","maxlen=20",	"Max length for other names is 20");

  frmvalidator.addValidation("enumber","req","Please enter the Employee Number");
  frmvalidator.addValidation("enumber","maxlen=20",	"Max length for the Employee Number is 20");
  
</script>	
<?php 
include('../includes/footer.php');
?>
