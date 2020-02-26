<?php 
session_start();
require_once('../connection/config.php');
include('../includes/header.php');

$fcode=$_GET['ID'];

$ok=$_GET['success'];

if  ($fcode != "")
{
	$facilityde = getFacilityDetails($fcode);
	extract($facilityde);	
	$iseid=$iseid;
	$facilityname=$name;
	$fcontacttelephone=$contacttelephone;
	$femail=$email;
	$ftelephone=$telephone;
	$ffax=$fax;
	$fphysicaladdress=$physicaladdress;
	$imeinum = $imei;
	$passnum=$pass;
//get select district name and province id	
		$distname=GetDistrictName($district);
		//get province ID
		$provid=GetProvid($district);
			//get province name	
		$provname=GetProvname($provid);
		$labname=GetLabNames($lab);//get lab name
		
		$labid=$lab;
		$distid = $district;
}




//save the facility details
if ($_REQUEST['save'])
{
	//get the facility information
	$autocode=$_GET['autocode'];
	$code = $_GET['code'];
	$userid=$_SESSION['uid'] ; //id of user who is updatin th record$code=$_GET['code'];
	$fname=$_GET['name'];
	$type=$_GET['type'];
	$district=$_GET['district'];
	//$cdistrict=$_GET['cdistrict'];
	$lab=$_GET['lab'];
	//$clab=$_GET['clab'];
	$postal=$_GET['postal'];
	$physicaladdress=$_GET['physicaladdress'];
	$telephone=$_GET['telephone'];
	$otelephone=$_GET['otelephone'];
	$fax=$_GET['fax'];
	$email=$_GET['email'];
	$fullname=$_GET['fullname'];
	$contacttelephone=$_GET['contacttelephone'];
	$ocontacttelephone=$_GET['ocontacttelephone'];
	$contactemail=$_GET['contactemail'];
	$im = $_GET['imei'];
	$pas = $_GET['pass'];
	$iseid=$_GET['iseid'];
	$provid=GetProvid($district);
		//$code=$provid.$district;
	//update facility record
	$facilityrec = mysql_query("UPDATE facilitys
				  SET facilitycode = '$code' , name = '$fname' , PostalAddress = '$postal' , telephone = '$telephone'   ,telephone2='$otelephone',fax='$fax',email='$email',physicaladdress='$physicaladdress',contactperson='$fullname',contacttelephone='$contacttelephone',contacttelephone2='$ocontacttelephone',ContactEmail='$contactemail',district= '$district' ,lab = '$lab' ,imei='$im',pass='$pas',iseid='$iseid' 
			  			   WHERE ID = '$autocode'")or die(mysql_error());


		
		if ($facilityrec ) //check if all records entered
		{
			//save user activity
			$tasktime= date("h:i:s a");
			$todaysdate=date("Y-m-d");
			$utask = 17; //user task = edit facility
			
			$activity = SaveUserActivity($userid,$utask,$tasktime,$autocode,$todaysdate);
				
			$success="Facility details successfully edited and saved ";
			$autocode=$autocode;
			echo '<script type="text/javascript">' ;
			echo "window.location.href='editfacility.php?ID=$autocode&success=$success'";
			echo '</script>';

		}
		else
		{
				$era="Edit Failed, please try again.";
		
		}
	

}





?>
<style type="text/css">
select {
width: 250;}
</style>

		<link href="../style.css" rel="stylesheet" type="text/css" />
<div>
		<div class="section-title"><font style="font-family:Verdana, Arial, Helvetica, sans-serif">EDIT FACILITY</font> </div>
  <div>
		<p>The fields indicated asterisk (<span class="mandatory">*</span>) are mandatory.</p>
		
	<!--		****************************************************************************** -->
	<div class="xtop">
	<?php if ($ok !="")
		{
		?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="success"><?php 
		
echo  '<strong>'.' <font color="#666600">'.$ok.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
<?php } ?>
<?php if ($era !="")
		{
		?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="error"><?php 
		
echo  '<strong>'.' <font color="#666600">'.$era.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
<?php } ?>
		<!-- end display the save message -->
		</div>
	
	<!--		****************************************************************************** -->
		<form id="customForm"   method="get" action=""  >
        <table border="0" class="data-table">
          <tr>
            <th colspan="5">Facility Information </th>
          </tr>
          <tr class="even">            
         <!-- </tr>
          <tr class="even"> -->
            <td> Hospital Name</td>
            <td colspan=""><div>
              <input type="text" name="name" id="name" size="44" class="text" value="<?php echo $facilityname; ?>" />
              <span id='nameInfo'></span></div></td>
			  <td >Hospital Code</td>
            <td colspan=""><div>
           <input type="text" name="code" id="code" size="44" class="text" value="<?php echo $facilitycode; ?>"/>
            <input type="hidden" name="autocode" id="autocode" size="44" class="text" value="<?php echo $ID; ?>"/><span id='codeInfo'></span></div></td>
          </tr>
          <tr class="even">
            <td > District</td>
            <td colspan="">
			<!--<input name="cdistrict" type="checkbox" id="district"/> &nbsp;<?php //echo $distname; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
			<?php
		   		$groupquery = "SELECT ID,name FROM districts where ID != '$distid'";
				
				$result = mysql_query($groupquery) or die('Error, query failed'); 
		
				   echo "<select name='district' id='district' style='width:265px' ;>\n";
					echo " <option value='$distid'> $distname </option>";
				  //Now fill the table with data
				  while ($row = mysql_fetch_array($result))
				  {
						 $ID = $row['ID'];
						$name = $row['name'];
					echo "<option value='$ID'> $name</option>\n";
				  }
				  echo "</select>\n";
		  	?>
			</td>
         <!-- </tr>
          <tr class="even"> -->
            <td> Laboratory</td>
            <td colspan="3">
			<!--<input name="clab" type="checkbox" id="clab"/> &nbsp;<?php echo $labname; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
			<?php
		   		$groupquery = "SELECT ID,name FROM labs where ID != '$labid'";
				
				$result = mysql_query($groupquery) or die('Error, query failed'); 
		
				   echo "<select name='lab' id='lab' style='width:265px' ;>\n";
					echo " <option value='$labid'> $labname </option>";
				  //Now fill the table with data
				  while ($row = mysql_fetch_array($result))
				  {
						 $ID = $row['ID'];
						$name = $row['name'];
					echo "<option value='$ID'> $name</option>\n";
				  }
				  echo "</select>\n";
		  	?>
			</td>
          </tr>
          <tr class="even">
            <td >Gives EID Service</td>
            <td colspan="">
            <?php 
			if($iseid=='Yes')
			{?>
				<input type="checkbox" name="iseid" checked="checked"  value="Yes" />
			<?php }
			else
			{ ?>
				<input type="checkbox" name="iseid" value="Yes" />
			<?php }
			?>
            </td>
            <td>&nbsp;</td>
            <td colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <th colspan="2">Facility Contact Details </th>
			<th colspan="4" ><font color="#0000FF">SMS PRINTER DETAILS</font> </th>
          </tr>
          <tr class="even">
            <td >Postal Address </td>
            <td ><textarea name="postal" rows="2" cols="40"><?php echo $PostalAddress; ?></textarea></td>
			<td width="150"><div align="right"><font color="#0000FF">IMEI Number </font></div></td>
			<td ><input type="text" name="imei" size="44" class="text" value="<?php echo $imeinum; ?>" /></td>
          </tr>
		    <!--<tr class="even">
            <td >Physical Address </td>
            <td colspan="4"><textarea name="physicaladdress" rows="2" cols="115"><?php //echo $fphysicaladdress; ?></textarea></td>
          </tr> -->
          <tr class="even">
            <td> Telephone No</td>
            <td ><div><input type="text" name="telephone" id="telephone" size="44" class="text" value="<?php echo $ftelephone; ?>" /><span id='telephonInfo'></span></div></td>
			<td width=""><div align="right"><font color="#0000FF">Printer Password</font></div></td>
			<td ><input type="text" name="pass" size="44" class="text" value="<?php echo $passnum; ?>" /></td>
			<!--<td>Telephone No. 2 </td>
            <td ><input type="text" name="otelephone" size="44" class="text" value="<?php //echo $telephone2; ?>" /></td> -->
          </tr>
          <tr class="even">
           <!-- <td>Fax </td>
            <td ><input type="text" name="fax" size="44" class="text"  value="<?php //echo $ffax; ?>"/></td> -->
			<td>Email Address </td>
            <td colspan="3"><input type="text" name="email" size="44" class="text" value="<?php echo $femail; ?>"/></td>
          </tr>
          <tr>          </tr>
          <tr>
            <th colspan="4" >Facility Contact Person </th>
          </tr>
          <tr class="even">
            <td width="117"> Full Name(s) </td>
            <td ><div>
            <input type="text" name="fullname"  id="fullname" size="44" class="text" value="<?php echo $contactperson; ?>" /><span id='fullnamInfo'></span></div></td>
			<td>Contact Email Address </td>
            <td width="117"><input type="text" name="contactemail" id="contactemail" size="44" class="text" value="<?php echo $ContactEmail; ?>" /></td>
          </tr>
         <!-- <tr class="even">
		  <td>Contact Tel. No. 1 </td>
            <td ><div><input type="text" name="contacttelephone" id="contacttelephone" size="44" class="text" value="<?php //echo $fcontacttelephone; ?>"  /><span id='contacttelephonInfo'></span></div></td>
			<td>Contact Tel. No. 2 </td>
            <td width="117"><input type="text" name="ocontacttelephone" size="44" class="text" value="<?php //echo $contacttelephone2; ?>"  /></td>
          </tr> -->
          <tr >
            <th colspan="4"><input name="save" type="submit" class="button" value="Save Changes" style="width:200px" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              
              <input name="btnCancel" type="button" id="btnCancel" value="Cancel Changes" onClick=window.location.href="facilitieslist.php?view=1" class="button" style="width:200px"></th>
          </tr>
        </table>
	</form>
	<!--		****************************************************************************** -->
  </div>
</div>
		
 <?php include('../includes/footer.php');?>