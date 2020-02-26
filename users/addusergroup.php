<?php 
require_once('../connection/config.php');
include('../includes/header.php');
$success=$_GET['p'];
//get the user information
$groupname = $_GET['groupname'];
//end get user information


//save the user details
if ($_REQUEST['save'])
{
	$usergrup = SaveUserGroup($groupname);//call the save user group function
		if ($usergrup) //check if all records entered
		{
				$st="User Group: ".$groupname ." has been added.";
				 //direct to users list view
			  	echo '<script type="text/javascript">' ;
				echo "window.location.href='usergroupslist.php?p=$st'";
				echo '</script>';


		}
		else
		{
				$st="Save User Group Failed, please try again.";
		
		}

}
else if ($_REQUEST['add'])
{
	$usergrup = SaveUserGroup($groupname);//call the save user group function
		if ($usergrup) //check if all records entered
		{
				$st="User Group: ".$groupname ." has been added.";
				header("location:addusergroup.php?p=$st"); //direct to users list view
			  	exit();

		}
		else
		{
				$st="Save User Group Failed, please try again.";
		
		}

}
//end of saving user details




?>


<style type="text/css">
select {
width: 250;}
</style>

<div>
	<div class="section-title">ADD USER GROUPS </div>
		
		
		<!--*********************************************************************** -->
		<div class="xtop">
		<!--display the save message -->
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
				<?php if ($st !="")
						{
						?> 
						<table   >
				  <tr>
					<td style="width:auto" >
					<div class="error">
					<?php echo  '<strong>'.' <font color="#666600">'.$st.'</strong>'.' </font>';?></div>
					</td>
				  </tr>
				</table>
				<?php } ?>
		<!-- end display the save message -->
		</div>
		<!--*********************************************************************** -->
		
		  <form autocomplete="off"  method="get" action=""  name="userform">
		  <table>
			<tr>
              <td colspan="4" width="414"><em>The field indicated asterix (<span class="mandatory">*</span>) are mandatory.</em></td>
            </tr>
		  </table>
		  <table border="0" class="data-table">
          
            <tr>
              <td width="117"><span class="mandatory">* </span>Name</td>
              <td colspan="3"><input type="text" name="groupname" size="44" class="text" /></td>
            </tr>
           
            <tr >
              <td></td>
              <td colspan="3">
		  	    <input name="save" type="submit" class="button" value="Save Group" />
		  	    <input name="add" type="submit" class="button" value="Save & Add Group" />
		  	    <input name="reset" type="reset" class="button" value="Reset" /></td>
            </tr>
          </table>
		  </form>
</div>

		
 <?php include('../includes/footer.php');?>