<?php 
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
include('../includes/header.php');


//$autocde = $_GET['q'];
$catt=$_GET['catt'];
$db=$_GET['db'];

$provname=$_GET['r'];
$distname=$_GET['z'];
$fcode=  $_GET['cat'];
$province = $_GET['province'];
$district = $_GET['district'];




/*get requisition details
$edbs = $_GET['edbs'];
$eziploc = $_GET['eziploc'];
$edessicants = $_GET['edessicants'];
$erack = $_GET['erack'];
$eglycline = $_GET['eglycline'];
$ehumidity = $_GET['ehumidity'];
$elancets = $_GET['elancets'];
$ecomments = $_GET['ecomments'];*/

//get today's date
$datemodified = date('Y-m-d');
	
		if ($_REQUEST['delete'])
		{		
		    $db = $_POST['db'];
			
				$facilityname = GetFacility($catt);
				
				$delrequisitions = DeleteRequisition($db,$datemodified);//call the save function
				
				if ($delrequisitions) //check if all records entered
				{
						$st="Material requisition for <strong>".$facilityname."</strong> has been deleted.";
						//header("location:requisitionslist.php?catt=$fcode"); //direct to list view
						echo '<script type="text/javascript">' ;
						echo "window.location.href='requisitionslist.php?catt=$catt'";
						echo '</script>';//do nothing
						exit();
				}
				else
				{
						$error="Delete Requisition Failed, please try again.";
				
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
$datecreated = $getreqinfo['datecreated'];	
$requisitiondate = $getreqinfo['requisitiondate'];
?>

<style type="text/css">
select 
{
width: auto;
height: 20;
}
</style>
		<div>
		<div class="section-title">
		DELETE&nbsp;
		<?php 
		$facilityname = GetFacility($catt);
		echo strtoupper($facilityname);?>
		&nbsp;REQUISITION NO [<?php echo $db; ?>]</div>
		<div class="xtop">
		
				<?php $datecreated=date('d-M-Y',strtotime($datecreated));
				if ($db !="")
				{?> 
				<table>
				  <tr>
					<td style="width:auto" ><div class="error"><?php echo '<strong>Are you sure you want to delete '.strtoupper($facilityname).' requisition that was made on '.$datecreated.'?</strong>';?></div></td>
				  </tr>
				</table>
				<?php
				}
				if ($error !="")
				{?> 
				<table>
				  <tr>
					<td style="width:auto" ><div class="error"> <?php echo $error;?></div></td>
				  </tr>
				</table>
				<?php
				}?>
		</div>
		<div>
			<form name="makerequisition" action="" method="post">
		 	<table width="534" border="0" class="data-table">
            <tr>
              <td width="127" >Facility</td>
              <td colspan="2"><input name="db"  size="2" value="<?php echo $db;?>" type="hidden"/>
			  
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
              <td colspan="2"><strong><?php echo $provname;?></strong><input name="province" type="hidden" value="<?php echo $provname;?>" /></td>
            </tr>
            <tr>
              <td>District</td>
              <td colspan="2"><strong><?php echo $distname;?></strong><input name="district" type="hidden" value="<?php echo $distname;?>" />	</td>
            </tr>
        
		 <!--</form> -->
		 <table class="data-table">
            <tr>
              <td class="subsection-title" colspan="3">Consumables Requested </td>
			  <td width="150"><small><strong>Date Created</strong>&nbsp;&nbsp;<?php echo $datecreated; ?></small></td>
            </tr>
			
			<tr>
				<td colspan="1"> Requisition Date</td>
				<td colspan="3"><strong>
				<?php 
				$requisitiondate =  date('d-M-Y',strtotime($requisitiondate));
				echo $requisitiondate;?></strong>
				<input name="requisitiondate" type="hidden" value="<?php echo $requisitiondate;?>" />
				</td>
			</tr>
				
			<tr>
			<td colspan="7">&nbsp;</td>
			
			</tr>
			
			<tr>
			<td><strong>Item</strong></td>
			<td><strong>Order</strong></td>
			
			<td><strong>Item</strong></td>
			<td><strong>Order</strong></td>
			
			</tr>
			
            <tr>
              <td width="130">DBS Filter Paper </td>
              <td width="85"><strong><?php echo $dbs;?></strong></td>
              <td width="125">Ziploc Bags</td>
              <td width="75"><strong><?php echo $ziploc;?></strong></td>
            </tr>
            <tr>
              <td>Dessicants</td>
              <td><strong><?php echo $dessicants;?></strong></td>
              <td>Drying Racks</td>
              <td><strong><?php echo $rack;?></strong></td>
            </tr>
            <tr>
              <td>Glycline Envelopes </td>
              <td><strong><?php echo $glycline;?></strong></td>
              <td>Humidity Indicators</td>
              <td><strong><?php echo $humidity;?></strong></td>
            </tr>
            <tr>
              <td>Lancets</td>
              <td colspan="4"><strong><?php echo $lancets;?></strong></td>
            </tr>
            <tr>
              <td>Others / Comments </td>
              <td colspan="5"><strong><?php echo $comments;?></strong></td>
            </tr>
            <tr >
              
              <td colspan="6">
			  	  <input name="delete" type="submit" class="button" value="Delete <?php echo strtoupper($facilityname)?> Requisition" />
                 
                  <input name="cancel" type="reset" class="button" value="No" onClick=window.location.href="requisitionslist.php" /></td>
            </tr>
          </table>
		 </form>
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>