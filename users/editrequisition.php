<?php 
session_start();
include('../includes/header.php');
require_once('classes/tc_calendar.php');
$labss=$_SESSION['lab'];


//$autocde = $_GET['q'];
$catt=$_GET['catt'];
$db=$_GET['db'];

$provname=$_GET['r'];
$distname=$_GET['z'];
$fcode=  $_GET['cat'];
$province = $_GET['province'];
$district = $_GET['district'];
$editreqdate = $_GET['editreqdate'];
$editrequisitiondate =  isset($_REQUEST["editrequisitiondate"]) ? $_REQUEST["editrequisitiondate"] : "";

//get requisition details
$edbs = $_GET['edbs'];
$eziploc = $_GET['eziploc'];
$edessicants = $_GET['edessicants'];
$erack = $_GET['erack'];
$eglycline = $_GET['eglycline'];
$ehumidity = $_GET['ehumidity'];
$elancets = $_GET['elancets'];
$ereqform = $_GET['ereqform'];
$ecomments = $_GET['ecomments'];

//get today's date
$datemodified = date('Y-m-d');

		if ($_REQUEST['save'])
		{		
		
				$facilityname = GetFacility($fcode);
				
				$updaterequisitions = UpdateRequisition($db,$edbs,$eziploc,$edessicants,$erack,$eglycline,$ehumidity,$elancets,$ereqform,$ecomments,$datemodified);//call the save function
				
					if ($editreqdate != '')
					{
						$editrequisitiondate =  date('Y-m-d',strtotime($editrequisitiondate));
						
						$reqdate = mysql_query("UPDATE requisitions SET requisitiondate = '$editrequisitiondate' WHERE id = '$db'") or die(mysql_error());
					}

				if ($updaterequisitions) //check if all records entered
				{
						$st="Material requisition for <strong>".$facilityname."</strong> has been edited.";
						//header("location:requisitionslist.php?p=$st"); //direct to list view
						
						echo '<script type="text/javascript">' ;
						echo "window.location.href='requisitionslist.php?p=$st'";
						echo '</script>';
						//exit();
				}
				else
				{
						$st="Edit Requisition Failed, please try again.";
				
				}
		}



//get the requisition record from the database
$getreqinfo = GetRequisitionInfo($db);
//assign the records
$dbs = $getreqinfo['dbs'];
$dessicants = $getreqinfo['dessicants'];
$glycline = $getreqinfo['glycline'];
$lancets = $getreqinfo['lancets'];
$ziploc = $getreqinfo['ziploc'];
$rack = $getreqinfo['rack'];
$humidity = $getreqinfo['humidity'];
$comments = $getreqinfo['comments'];
$reqform = $getreqinfo['reqform'];
$datecreated = $getreqinfo['datecreated'];	
$requisitiondate = $getreqinfo['requisitiondate'];

$currentdate = date('Y'); //show the current year
$lowestdate = GetAnyDateMin(); //get the lowest year from date received

?>

<style type="text/css">
select 
{
width: auto;
height: 20;
}
</style>

<script language="javascript" src="calendar.js"></script>

<link type="text/css" href="calendar.css" rel="stylesheet" />	

		<div>
		<div class="section-title">
		EDIT&nbsp;
		<?php 
		$facilityname = GetFacility($catt);
		echo strtoupper($facilityname);?>
		&nbsp;REQUISITION</div>
		<div class="xtop">
				<?php if ($success !="")
				{
				?> 
				<table   >
				  <tr>
					<td style="width:auto" ><div class="success"><?php 
						
					echo  ' <font color="#666600">'.$success.' </font>';
				
				?></div></td>
				  </tr>
				</table>
				<?php } ?>
				<?php if ($st !="")
						{
						?> 
						<table   >
				  <tr>
					<td style="width:auto" ><div class="error"><?php 
						
				echo  '<strong>'.' <font color="#666600">'.$st.'</strong>'.' </font>';
				
				?></div></td>
				  </tr>
				</table>
				<?php } ?>
		</div>
		<div>
			<form name="makerequisition" action="">
		 	<table width="534" border="0" class="data-table">
            <tr>
              <td width="127" >Facility</td>
              <td colspan="4"><input name="db"  size="2" value="<?php echo $db;?>" type="hidden""/>
			  
			 <?php 
			
				if ($catt !="")
				{		
					$facilityname = GetFacility($catt);
					echo "<input name='cat' type='hidden' value='$catt' />";
					echo "<strong>".$facilityname."</strong>";
					
					/////// for second drop down list we will check if category is selected else we will display all the subcategory///// 
					if(isset($catt) and strlen($catt) > 0)
					{
							//get selected district ID
							$districtidquery=mysql_query("SELECT district FROM facilitys WHERE  ID='$catt'"); 
							$noticia = mysql_fetch_array($districtidquery);  
							$distid=$noticia['district'];
							//get select district name and province id	
							$districtnamequery=mysql_query("SELECT province,name FROM districts WHERE  ID='$distid'"); 
							$districtname = mysql_fetch_array($districtnamequery);  
							$provid=$districtname['province'];
							$distname=$districtname['name'];
							//get province name	
							$provincenamequery=mysql_query("SELECT name FROM provinces WHERE  Code='$provid'"); 
							$provincename = mysql_fetch_array($provincenamequery);  
							$provname=$provincename['name'];						
					}
				}
				else
				{						
					if(strlen($catt) > 0 and !is_numeric($catt))
					{ 
						echo "Data Error";
						exit();
					}		
				}
			?>
			
			</td>
            </tr>
            <tr>
              <td>Province</td>
              <td colspan="3"><strong><?php echo $provname;?></strong><input name="province" type="hidden" value="<?php echo $provname;?>" /></td>
            </tr>
            <tr>
              <td>District</td>
              <td colspan="3"><strong><?php echo $distname;?></strong><input name="district" type="hidden" value="<?php echo $distname;?>" />	</td>
            </tr>
        
		 <!--</form> -->
		 <table class="data-table">
            <tr>
              <td class="subsection-title" colspan="4">Consumables Requested </td>
			  <td colspan="2"><small><strong>Date Created</strong>&nbsp;&nbsp;<?php echo $datecreated=date('d-M-Y',strtotime($datecreated));?></small></td>
            </tr>
			
			<tr>
				<td colspan="1"> Requisition Date</td>
				<td colspan="5"><input type="checkbox" name="editreqdate" value="editreqdate"/>
				  <?php	
					$requisitiondate =  date('d-M-Y',strtotime($requisitiondate));
					echo "<strong>".$requisitiondate ."</strong>&nbsp;&nbsp;"?>				
					
					<?php
				  $myCalendar = new tc_calendar("editrequisitiondate", true, false);	  
				  $myCalendar->setIcon("../img/iconCalendar.gif");	  
				  $myCalendar->setDate(date('d'), date('m'), date('Y'));	  
				  $myCalendar->setPath("./");  
				  $myCalendar->setYearInterval($lowestdate, $currentdate);  
				  $myCalendar->dateAllow('1930-01-1', '2015-01-01');	  
				  $myCalendar->setDateFormat('j F Y');	  
				  $myCalendar->writeScript();?></td>
			</tr>
			
			<tr>
			<td colspan="7">&nbsp;</td>
			
			</tr>
			
			<tr>
			<td><strong>Item</strong></td>
			<td><strong>Order</strong></td>
			<td><strong>Supply</strong></td>
			<td><strong>Item</strong></td>
			<td><strong>Order</strong></td>
			<td><strong>Supply</strong></td>
			</tr>
			
            <tr>
              <td width="125">DBS Filter Paper </td>
              <td width="45"><strong><?php echo $dbs;?></strong></td>
			  <td><input name="edbs" class="text" size="2" value="<?php echo $dbs;?>"/></td>
              <td width="135">Ziploc Bags</td>
              <td width="45"><strong><?php echo $ziploc;?></strong></td>
			  <td><input name="eziploc" class="text" size="2" value="<?php echo $ziploc;?>"/></td>
            </tr>
            <tr>
              <td>Dessicants</td>
              <td><strong><?php echo $dessicants;?></strong></td>
			  <td><input name="edessicants" class="text" size="2" value="<?php echo $dessicants;?>"/></td>
              <td>Drying Racks</td>
              <td><strong><?php echo $rack;?></strong></td>
			  <td><input name="erack" class="text" size="2" value="<?php echo $rack;?>"/></td>
            </tr>
            <tr>
              <td>Glycline Envelopes </td>
              <td><strong><?php echo $glycline;?></strong></td>
			  <td><input name="eglycline" class="text" size="2" value="<?php echo $glycline;?>"/></td>
              <td>Humidity Indicators</td>
              <td><strong><?php echo $humidity;?></strong></td>
			  <td><input name="ehumidity" class="text" size="2" value="<?php echo $humidity;?>"/></td>
            </tr>
            <tr>
              <td>Lancets</td>
              <td><strong><?php echo $lancets;?></strong></td>
			  <td ><input name="elancets" class="text" size="2" value="<?php echo $lancets;?>"/></td>
			    <td>Lab Requisition Form</td>
              <td><strong><?php echo $reqform;?></strong></td>
			  <td ><input name="ereqform" class="text" size="2" value="<?php echo $reqform;?>"/></td>
			  
            </tr>
            <tr>
              <td rowspan="2">Others / Comments </td>
              <td colspan="5"><strong><?php echo $comments;?></strong></td>
            </tr>
            <tr>
			<td colspan="5"><textarea name="ecomments" rows="3" cols="65"><?php echo $comments;?></textarea></td>
			</tr>
            <tr >
              <td></td>
              <td colspan="5">
			  	  <input name="save" type="submit" class="button" value="Save Changes" />
                 
                  <input name="cancel" type="reset" class="button" value="Cancel" onClick=window.location.href="requisitionslist.php" /></td>
            </tr>
          </table>
		 </form>
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>