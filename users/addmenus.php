<?php 
include('../includes/header.php');
$success=$_GET['p'];
//get the menu information
$menu = $_GET['menu'];
$url = $_GET['url'];
$location = $_GET['location'];
//end get user information


//save the user details
if ($_REQUEST['save'])
{
	$menus = SaveMenu($menu,$url,$location);//call the save user group function
		if ($menu) //check if all records entered
		{
				$st="Menu: ".$menu ." has been added.";
				 //direct to users list view
			  echo '<script type="text/javascript">' ;
				echo "window.location.href='menuslist.php?p=$st'";
				echo '</script>';


		}
		else
		{
				$st="Save User Group Failed, please try again.";
		
		}

}
else if ($_REQUEST['add'])
{
	$menus = SaveMenu($menu,$url,$location);//call the save user group function
		if ($menu) //check if all records entered
		{
				$st="Menu: ".$menu ." has been added.";
				 echo '<script type="text/javascript">' ;
				echo "window.location.href='addmenus.php?p=$st'";
				echo '</script>';
				

		}
		else
		{
				$st="Save Menu Failed, please try again.";
		
		}

}
//end of saving user details




?>


<style type="text/css">
select {
width: 250;}
</style>

<div>
	<div class="section-title">ADD MENUS </div>
		
		
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
              <td colspan="3"><input type="text" name="menu" size="44" class="text" /></td>
            </tr>
            <tr>
              <td width="117"><span class="mandatory">* </span>URL</td>
              <td colspan="3"><input type="text" name="url" size="44" class="text" /></td>
            </tr>
			 <tr>
              <td width="117"><span class="mandatory">* </span>Location</td>
              <td colspan="3"><input name="location" type="radio" value="Top" />
              Top Menu&nbsp;
              <input name="location" type="radio" value="Side" />
              Side Menu</td>
            </tr>
            <tr >
              <td></td>
              <td colspan="3">
		  	    <input name="save" type="submit" class="button" value="Save Menu" />
		  	    <input name="add" type="submit" class="button" value="Save & Add Menu" />
		  	    <input name="reset" type="reset" class="button" value="Reset" /></td>
            </tr>
          </table>
		  </form>
</div>

		
 <?php include('../includes/footer.php');?>