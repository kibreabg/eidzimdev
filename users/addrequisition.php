<?php 
session_start();
include('../includes/header.php');
require_once('classes/tc_calendar.php');
$labss=$_SESSION['lab'];

$success=$_GET['p'];

$autocde = $_GET['q'];
$catt=$_GET['catt'];
$provname=$_GET['r'];
$distname=$_GET['z'];
$fcode=  $_GET['cat'];
$province = $_GET['province'];
$district = $_GET['district'];

//get requisition details
$dbs = $_GET['dbs'];
$ziploc = $_GET['ziploc'];
$dessicants = $_GET['dessicants'];
$rack = $_GET['rack'];
$glycline = $_GET['glycline'];
$humidity = $_GET['humidity'];
$lancets = $_GET['lancets'];
$reqform= $_GET['reqform'];
$comments = $_GET['comments'];
$requisitiondate = $_GET['requisitiondate'];

$currentdate = date('Y'); //show the current year
$lowestdate = GetAnyDateMin(); //get the lowest year from date received



if ($_REQUEST['save'])
{		
		
		$facilityname = GetFacility($fcode);
		
		$datecreated = date('Y-m-d');
		$datemodified = $datecreated;
		
		$requisitions = SaveRequisition($fcode,$dbs,$ziploc,$dessicants,$rack,$glycline,$humidity,$lancets,$reqform,$comments,$datecreated,$parentid,$disapprovecomments,$approvecomments,$requisitiondate,$datemodified);//call the save function
		if ($requisitions) //check if all records entered
		{
				$st="Material requisition for ".$facilityname." has been added.";
				//header("location:requisitionslist.php?p=$st"); //direct to list view
				echo '<script type="text/javascript">' ;
				echo "window.location.href='requisitionslist.php?p=$st'";
				echo '</script>';
			  	///exit();
		}
		else
		{
				$st="Save Requisition Failed, please try again.";
		
		}
}


?>
<script language=JavaScript>
function reload(form)
{
var val=form.cat.options[form.cat.options.selectedIndex].value;
self.location='addrequisition.php?catt=' + val ;
}
</script>

<script language="javascript" src="calendar.js"></script>

<link type="text/css" href="calendar.css" rel="stylesheet" />	
	
		<div>
		<div class="section-title">MAKE REQUISITION</div>
		<div class="xtop">
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
				<?php if ($st !="")
						{
						?> 
						<table   >
				  <tr>
					<td style="width:auto" ><div class="error"><?php 
						
				echo  '<strong>'.' <font color="#666600">'.$st.'</strong>'.' </font>';
				
				?></div></th>
				  </tr>
				</table>
				<?php } ?>
		</div>
		<div>
			<form name="makerequisition" action="" autocomplete="Off">
		 	<table width="534" border="0" class="data-table">
            <tr>
              <td width="127" >Facility</td>
              <td colspan="4">
			  
			 <?php 
			
				if ($autocode !="")
				{		
					$facilityname = GetFacility($autocode);
					echo "<input name='catname' type='text' id='catname'  class='textbox' value='$facilityname' size=45 />";
					echo "<input name='cat' type='hidden' value='$autocode' />";
				}
				else
				{						
					if(strlen($catt) > 0 and !is_numeric($catt))
					{ 
						echo "Data Error";
						exit();
					}
				
				
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
						}?>
		  <form method="get" name="f1" action="">
						<select name="cat" onchange="reload(this.form)">
						<option value=""> Select Facility </option>
						<?php
						$facilityquery=mysql_query("SELECT ID,name,district,ftype FROM facilitys WHERE lab='$labss' ") or die(mysql_error()); 

						while($noticia2 = mysql_fetch_array($facilityquery))
				 		{ 
							$fid=$noticia2['ID'];
							$tid = $noticia2['ftype'];
							$sql5 = "select name from facilitytype where ID='$tid'";
							$rsd5 = mysql_query($sql5);
							$farray = mysql_fetch_array($rsd5);
							$tname=$farray['name'];
							$cname = $noticia2['name'] . " " . $tname ;
							
							if($fid==@$catt)
							{
								echo "<option selected value='$fid'>$cname</option>"."<BR>";
							}
							else
							{
								echo  "<option value='$noticia2[ID]'>$cname</option>";
							}
						}
						echo "</select>";
				
				}
			?>
			  </select>
			  </td>
			  
            </tr>
            <tr>
              <td>Province</td>
              <td colspan="3"><?php echo $provname;?>
			  <input name="province" type="hidden" value="<?php echo $provname;?>" /></td>
            </tr>
            <tr>
              <td>District</td>
              <td colspan="3"><?php echo $distname;?>
			  <input name="district" type="hidden" value="<?php echo $distname;?>" />	</td>
            </tr>
		 </form>
		 <table class="data-table">
            <tr>
              <td class="subsection-title" colspan="4">Consumables Requested </td>
            </tr>
			<tr>
				<td colspan="1"> Requisition Date</td>
				<td colspan="3"><?php	
				
				  $myCalendar = new tc_calendar("requisitiondate", true, false);	  
				  $myCalendar->setIcon("../img/iconCalendar.gif");	  
				  $myCalendar->setDate(date('d'), date('m'), date('Y'));	  
				  $myCalendar->setPath("./");  
				  $myCalendar->setYearInterval($lowestdate, $currentdate);  
				  //$myCalendar->dateAllow('1930-01-1', '2015-01-01');	  
				  $myCalendar->setDateFormat('j F Y');	  
				  $myCalendar->writeScript();?></td>
			</tr>
            <tr>
              <td>DBS Filter Paper </td>
              <td width="117"><input type="text" name="dbs" size="5" class="text" value="0"/></td>
              <td width="117">Ziploc Bags</td>
              <td width="117"><input type="text" name="ziploc" size="5" class="text" value="0"/></td>
            </tr>
            <tr>
              <td>Dessicants</td>
              <td><input type="text" name="dessicants" size="5" class="text" value="0"/></td>
              <td>Drying Racks</td>
              <td><input type="text" name="rack" size="5" class="text" value="0"/></td>
            </tr>
            <tr>
              <td>Glycline Envelopes </td>
              <td><input type="text" name="glycline" size="5" class="text" value="0"/></td>
              <td>Humidity Indicators</td>
              <td><input type="text" name="humidity" size="5" class="text" value="0"/></td>
            </tr>
            <tr>
              <td>Lancets</td>
              <td colspan=""><input type="text" name="lancets" size="5" class="text" value="0"/></td>
			 <td>Lab Requisition Forms</td>
              <td colspan=""><input type="text" name="reqform" size="5" class="text" value="0"/></td>
            </tr>
            <tr>
              <td>Others / Comments </td>
              <td colspan="3"><textarea name="comments" rows="3" cols="70"></textarea></td>
            </tr>
            
            <tr >
              <td></td>
              <td colspan="3">
			  	  <input name="save" type="submit" class="button" value="Save Requisition" />
                  <!--<input name="add" type="submit" class="button" value="Save & Add Requisition" /> -->
                  <input name="reset" type="submit" class="button" value="Reset" /></td>
            </tr>
          </table>
		 </form>
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>