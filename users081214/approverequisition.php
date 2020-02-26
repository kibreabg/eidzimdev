<?php 
session_start();
include('../includes/header.php');
require_once('classes/tc_calendar.php');
$labss=$_SESSION['lab'];
require_once('classes/tc_calendar.php');

//$autocde = $_GET['q'];
$catt=$_GET['catt'];
$db=$_GET['db'];
$approvesuccess=$_GET['catt'];
$approveerror=$_GET['error'];
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
$comments = $_GET['comments'];
$datecreated = $_GET['datecreated'];
$requisitiondate = $_GET['requisitiondate'];
$reqform = $_GET['reqform'];
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
$reqform = $getreqinfo['reqform'];
$comments = $getreqinfo['comments'];
$datecreated = $getreqinfo['datecreated'];	
$requisitiondate = $getreqinfo['requisitiondate'];
?>
<!--<script language=JavaScript>
function reload(form)
{
var val=form.cat.options[form.cat.options.selectedIndex].value;
self.location='editrequisition.php?catt=' + val ;
}
</script> -->
<?php $datecreated=date('d-M-Y',strtotime($datecreated));?>
		<div>
		<div class="section-title">
		APPROVE 
		<?php 
		$facilityname = GetFacility($catt);
		echo strtoupper($facilityname);?>
		 REQUISITION</div>
		<div class="xtop">
				<?php if ($approvesuccess !="")
				{
				?> 
				<table>
				  <tr>
					<td style="width:auto" ><div class="notice"><?php echo '<strong>Are you sure you want to approve '.strtoupper($facilityname).' requisition that was made on '.$datecreated.'?</strong>';?></div>
					</td>
				  </tr>
				</table>
				<?php 
				} 
				
				if ($approveerror !="")
					{
				?> 
					<table>
					  <tr>
						<td style="width:auto" ><div class="error"><?php echo  '<strong>'.' <font color="#666600">'.$approveerror.'</strong>'.' </font>';?></div>
							
						</td>
					  </tr>
					</table>
				<?php } ?>
		</div>
		<div>
			<form name="makerequisition" action="approve_emailrequisition.php" method="get">
		 	<table width="534" border="0" class="data-table">
            <tr>
              <td width="127" >Facility</td>
              <td colspan="4"><input name="db"  size="2" value="<?php echo $db;?>" type="hidden"/>
			  
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
              <td class="subsection-title" colspan="3">Consumables Requested </td>
			  <td colspan="3" ><small><strong>Date Created</strong>&nbsp;&nbsp;<?php echo $datecreated;?></small><input name="datecreated" type="hidden" value="<?php echo $datecreated;?>" /></td>
            </tr>
			<tr>
				<td colspan="1"> Requisition Date</td>
				<td colspan="5">
				  <?php	
					$requisitiondate =  date('d-M-Y',strtotime($requisitiondate));
					echo "<strong>".$requisitiondate ."</strong>&nbsp;&nbsp;"?>				
					<input name="requisitiondate" type="hidden" value="<?php echo $requisitiondate;?>" />				</td>
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
              <td rowspan=""> Comments </td>
              <td colspan="5"><strong><?php echo $comments;?></strong></td>
            </tr>
            <tr>
			 <td rowspan=""> Approval Comments </td>
			  <td colspan="5"><textarea name="ecomments" rows="3" cols="65"></textarea></td>
            </tr>
			<tr>
            <td colspan="6">
			  	  <input name="approve" type="submit" class="button" value="Approve <?php echo strtoupper($facilityname)?> Requisition"/>
                  <input name="cancel" type="reset" class="button" value="Cancel" onClick=window.location.href="requisitionslist.php" />			</td>
            </tr>
          </table>
		 </form>
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>