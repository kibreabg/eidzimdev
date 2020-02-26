<?php 
session_start();
require_once('../connection/config.php');
include('../includes/header.php');

$userid=$_SESSION['uid'] ; //id of user who is updatin th record
//get the facility information
$code=$_GET['code'];
$name=$_GET['name'];
$type=$_GET['type'];
$district=$_GET['district'];
$lab=$_GET['lab'];
$postal=$_GET['postal'];

$telephone=$_GET['telephone'];
$otelephone=$_GET['otelephone'];
$fax=$_GET['fax'];
$email=$_GET['email'];
$fullname=$_GET['fullname'];
$contacttelephone=$_GET['contacttelephone'];
$ocontacttelephone=$_GET['ocontacttelephone'];
$success=$_GET['p'];
$contactemail=$_GET['contactemail'];
$imei = $_GET['imei'];
$pass = $_GET['pass'];
$iseid=$_GET['iseid'];
//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;



//save the facility details
if ($_REQUEST['save'])
{

/*check for duplicate facility code
$qry = "SELECT * FROM facilitys WHERE facilitycode='$code'";
		$result = mysql_query($qry);
		if($result)
		 {
			if(mysql_num_rows($result) > 0)
			 {
				$errmsg_arr[]= 'Facility Code already in use, enter another one';
				$errflag = true;
			}
			@mysql_free_result($result);
		}
		else 
		{
			die("failed");
		}
		*/
		//If there are input validations, redirect back to the registration form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		
	}
	else
	{
		$provid=GetProvid($district);
		$code=$provid.$district;
		$facilities = SaveFacility($code,$name,$district,$lab,$postal,$telephone,$otelephone,$fax,$email,$fullname,$contacttelephone,$ocontacttelephone,$contactemail,$imei,$pass,$iseid);//call the save facility function
		if ($facilities) //check if all records entered
		{
				//save user activity
				$tasktime= date("h:i:s a");
				$todaysdate=date("Y-m-d");
				$utask = 16; //user task = add facility
				$lastfacility = GetLastFacility();
				
				$activity = SaveUserActivity($userid,$utask,$tasktime,$lastfacility,$todaysdate);
				
				$st="Facility: ".$name. " has been added.";
				 //direct to users list view
			  	echo '<script type="text/javascript">' ;
				echo "window.location.href='facilitieslist.php?p=$st&view=1'";
				echo '</script>';

		}
		else
		{
				$st="Save User Failed, please try again.";
		
		}
	}

}
else if ($_REQUEST['add'])
{

/*check for duplicate facility code
$qry = "SELECT * FROM facilitys WHERE facilitycode='$code'";
		$result = mysql_query($qry);
		if($result)
		 {
			if(mysql_num_rows($result) > 0)
			 {
				$errmsg_arr[]= 'Facility Code already in use, enter another one';
				$errflag = true;
			}
			@mysql_free_result($result);
		}
		else 
		{
			die("failed");
		}*/
		//If there are input validations, redirect back to the registration form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		
	}
	else
	{
		$provid=GetProvid($district);
		$code=$provid.$district;
	$facilities = Savefacility($code,$name,$district,$lab,$postal,$telephone,$otelephone,$fax,$email,$fullname,$contacttelephone,$ocontacttelephone,$contactemail,$imei,$pass,$iseid);//call the save facility function
		if ($facilities) //check if all records entered
		{
				//save user activity
				$tasktime= date("h:i:s a");
				$todaysdate=date("Y-m-d");
				$utask = 16; //user task = add facility
				$lastfacility = GetLastFacility();
				
				$activity = SaveUserActivity($userid,$utask,$tasktime,$lastfacility,$todaysdate);
				
				$st="Facility: ".$name. " has been added.";
				 //direct to users list view
				echo '<script type="text/javascript">' ;
				echo "window.location.href='addfacility.php?p=$st'";
				echo '</script>';

		}
		else
		{
				$st="Save User Failed, please try again.";
		
		}
}

}


?>
<style type="text/css">
select {
width: 250;}
</style>
<script type="text/javascript" src="../includes/validatefacility.js"></script>
<link rel="stylesheet" href="../includes/validation.css" type="text/css" media="screen" />

		<link href="../style.css" rel="stylesheet" type="text/css" />
<div>
		<div class="section-title">ADD FACILITY </div>
  <div>
		<table>
			<tr>
              <td colspan="6" width="750">The fields indicated asterisk (<span class="mandatory">*</span>) are mandatory.</td>
            </tr>
			
		</table>
		
	<!--		****************************************************************************** -->
	<div class="xtop">
	<?php  //validation errors
	if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {
		echo '<table>
				  <tr>
					<td style="width:auto" ><div class="error">';
		foreach($_SESSION['ERRMSG_ARR'] as $msg) {
			echo '<strong>'.$msg . '</strong>'; 
		}
		echo '</div></td>
				  </tr>
				</table>';
		unset($_SESSION['ERRMSG_ARR']);
	}
?>
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
	
	<!--		****************************************************************************** -->
		<form id="customForm"   method="get" action=""  >
        <table border="0" class="data-table">
          <tr>
            <th colspan="5" >Facility Information </th>
          </tr>
          <!--<tr class="even">
            <td ><span class="mandatory">*</span> Code</td>
            <td colspan="3"><div><input type="text" name="code" id="code" size="44" class="text" /><span id='codeInfo'></span></div></td>
          </tr> -->
          <tr class="even">
            <td><span class="mandatory">*</span> Hospital Name</td>
            <td colspan="3"><div>
              <input type="text" name="name" id="name" size="44" class="text" />
              <span id='nameInfo'></span></div></td>
            
          </tr>
          <tr class="even">
            <td ><span class="mandatory">*</span> District</td>
            <td colspan="3"><div><?php
		   		$groupquery = "SELECT ID,name FROM districts ORDER BY name ASC";
				
				$result = mysql_query($groupquery) or die('Error, query failed'); 
		
				   echo "<select name='district' id='district' style='width:260px';>\n";
					echo " <option value=''> Select District </option>";
				  //Now fill the table with data
				  while ($row = mysql_fetch_array($result))
				  {
						 $ID = $row['ID'];
						$name = $row['name'];
					echo "<option value='$ID'> $name</option>\n";
				  }
				  echo "</select>\n";
		  	?><span id='districtInfo'></span></div></td>
          </tr>
          <tr class="even">
            <td><span class="mandatory">*</span> Laboratory</td>
            <td colspan="3"><div><?php
		   		$groupquery = "SELECT ID,name FROM labs";
				
				$result = mysql_query($groupquery) or die('Error, query failed'); 
		
				   echo "<select name='lab' id='lab' style='width:260px';>\n";
					echo " <option value=''> Select Laboratory </option>";
				  //Now fill the table with data
				  while ($row = mysql_fetch_array($result))
				  {
						 $ID = $row['ID'];
						$name = $row['name'];
					echo "<option value='$ID'> $name</option>\n";
				  }
				  echo "</select>\n";
		  	?><span id='labInfo'></span></div></td>
          </tr>
          <tr class="even">
            <td>Gives EID Service</td>
            <td colspan="3"><input type="checkbox" name="iseid" value="Yes" /></td>
          </tr>
          <tr>
            <th colspan="2" >Facility Contact Details </th>
			<th colspan="4" ><font color="#0000FF">SMS PRINTER DETAILS</font> </th>
          </tr>
          <tr class="even">
            <td width="200">Postal Address </td>
            <td colspan=""><textarea name="postal" rows="2" cols="40"></textarea></td>
			<td width="150"><div align="right"><font color="#0000FF">IMEI Number </font></div></td>
			<td ><input type="text" name="imei" size="44" class="text" /></td>
          </tr>
          <tr class="even">
            <td><span class="mandatory">*</span> Telephone No</td>
            <td ><div><input type="text" name="telephone" id="telephone" size="44" class="text" /><span id='telephonInfo'></span></div></td>
			<td width=""><div align="right"><font color="#0000FF">Printer Password</font></div></td>
			<td ><input type="text" name="pass" size="44" class="text" /></td>
			<!--<td>Telephone No. 2 </td>
            <td ><input type="hidden" name="otelephone" size="44" class="text" /></td> -->
          </tr>
          <tr class="even">
            <!--<td>Fax Line No. </td>
            <td ><input type="hidden" name="fax" size="44" class="text" /></td> -->
			<td>Email Address </td>
            <td colspan="3"><input type="text" name="email" size="44" class="text" /></td>
          </tr>
         
          <tr>
            <th colspan="4" >Facility Contact Person </th>
          </tr>
          <tr class="even">
            <td width="117"><span class="mandatory">*</span> Full Name(s) </td>
            <td ><div>
            <input type="text" name="fullname"  id="fullname" size="44" class="text" /><span id='fullnamInfo'></span></div></td>
		
		  	<td>Contact Email Address </td>
            <td width="117"><input type="text" name="contactemail" id="contactemail" size="44" class="text" /></td>
            <!--<td><span class="mandatory">*</span> Contact Tel. No. 1 </td>
            <td ><div><input type="text" name="contacttelephone" id="contacttelephone" size="44" class="text" /><span id='contacttelephonInfo'></span></div></td> -->
			<!--<td>Contact Tel. No. 2 </td>
            <td width="117"><input type="text" name="ocontacttelephone" size="44" class="text" /></td> -->
          </tr>
          <tr >
            <th colspan="4"><input name="save" type="submit" class="button" value="Save Facility" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="add" type="submit" class="button" value="Save & Add Facility" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="reset" type="submit" class="button" value="Reset" /></th>
          </tr>
        </table>
	</form>
	<!--		****************************************************************************** -->
  </div>
</div>
		
 <?php include('../includes/footer.php');?>