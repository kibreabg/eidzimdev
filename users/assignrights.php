<?php 
require_once('../connection/config.php');
include('../includes/header.php');

$success=$_GET['p'];
//get the menu information
$menu = $_GET['menu'];
$url = $_GET['url'];
//end get user  grup information
$usergrup= $_POST['usergrup'];
$usergrupname=GetAccountType($usergrup);

$roles= $_POST['roles'];
$roleid= $_POST['roleid'];
$checkbox= $_POST['checkbox'];


//save the user details
if ($_REQUEST['save'])
{


 foreach($_POST['checkbox'] as $j)
 {
 
//echo "Type ".$roles[$j] . " ID ". $roleid[$j]."<br>";
		//save worksheet details
$grupmenus ="INSERT INTO 		
groupmenus(usergroup,menu)VALUES('$usergrup','$roleid[$j]')";
			$grupmenusrec = @mysql_query($grupmenus) or die(mysql_error());
		
}
			if ($grupmenusrec) //check if all records entered
		{
				$st="User Group: ".$usergrupname ." rights been assigned.";
				echo '<script type="text/javascript">' ;
echo "window.location.href='assignrights.php?p=$st'";
echo '</script>';
				
			

		}
		else
		{
				$st="Assigning rights has failed, please try again.";
		
		}

}

//end of saving user details




?>

<script>
function select(a) {
    var theForm = document.myForm;
    for (i=0; i<theForm.elements.length; i++) {
        if (theForm.elements[i].name=='checkbox[]')
            theForm.elements[i].checked = a;
    }
}
</script>
<style type="text/css">
select {
width: 250;}
</style>

<div>
	<div class="section-title">ASSIGN RIGHTS </div>
		
		
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
		
		  <form name="myForm" method="post" action="">
		 		  <table border="0" class="data-table">
          	<tr>
              <td colspan="11" ><em>Select A User Group, then Check the Rights</em></td>
            </tr>
            <tr>
              <td colspan="3"><span class="mandatory">* </span>User Group </td>
              <td colspan="8"><?php
	   $pmtctquery = "SELECT ID,name FROM usergroups ";
			
			$result = mysql_query($pmtctquery) or die('Error, query failed'); //onchange='submitForm();'
	
	   echo "<select name='usergrup' ;>\n";
	    echo " <option value=''> Select One </option>";

     // echo "<option>-- Select Group --</option>\n";
   // echo "<option>--  --</option>\n";
      //Now fill the table with data
      while ($row = mysql_fetch_array($result))
      {
             $ID = $row['ID'];
			$name = $row['name'];
        echo "<option value='$ID'> $name</option>\n";
      }
      echo "</select>\n";
	  ?></td>
            </tr>
          			<tr >
            <td height="24"  colspan="10"><a href="javascript:select(1)">Check all</a> |
<a href="javascript:select(0)">Uncheck all</a></td>
		  </tr>
			
             <?php 
 //ORDER BY name ASC

  $qury = "SELECT ID,name
            FROM menus
			";			
			$result = mysql_query($qury) or die('Error, query failed');
			$no=mysql_num_rows($result);
if ($no !=0)
{
// print the districts info in table

 	 $k = 0;
	 $i = 0;
	$samplesPerRow = 3; 
	while(list($ID,$name) = mysql_fetch_array($result))
	{  
	if ($k % $samplesPerRow == 0) {
            echo '<tr >';
        }
	
	    ?> 

<td align="center" ><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $i++;?>" /></td>
<td align="center"><input name="roles[]" type="text" id="roles" value="<?php echo $name;?>" readonly="" size="20"> <input type="hidden" name="roleid[]" value="<?php echo $ID;?>" /></td>
	 
    
	  
<?php
	
	  if ($k % $samplesPerRow == $samplesPerRow - 1) {
            echo '</tr>';
        }
        
        $k += 1;
	}
	
	}
	?>
            <tr >
              <td></td>
              <td colspan="11" >
		  	    <input name="save" type="submit" class="button" value="Save Rights" />
		  	  	    <input name="reset" type="reset" class="button" value="Reset" /></td>
            </tr>
          </table>
		  </form>
</div>

		
 <?php include('../includes/footer.php');?>