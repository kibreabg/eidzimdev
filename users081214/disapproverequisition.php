<?php 
session_start();
include('../includes/header.php');
require_once('classes/tc_calendar.php');
$labss=$_SESSION['lab'];
require_once('classes/tc_calendar.php');

//$autocde = $_GET['q'];
$catt=$_GET['catt'];
$db=$_GET['db'];

$disapprove=$_GET['d'];

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
$ereqform = $_GET['ereqform'];
$comments = $_GET['comments'];
$requisitiondate = $_GET['requisitiondate'];
$disapprovecomment = $_GET['disapprovecomment'];

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

if ($_REQUEST['disapprove'])
{
	$db = $_GET['db'];
	$requisitiondate = $_GET['requisitiondate'];
	$disapprovecomment = $_GET['disapprovecomment'];
	
	$facilityname = GetFacility($fcode);
		
	$datemodified = date('Y-m-d');
		
	$disreq = DisapproveRequisition($db,$datemodified,$disapprovecomment);
	
		if ($disreq) //check if all records entered
		{
				$st="Material requisition for ".$facilityname." that was made on ".$requisitiondate." has been disapproved.";
				//header("location:requisitionslist.php?p=$st"); //direct to list view
				echo '<script type="text/javascript">' ;
				echo "window.location.href='requisitionslist.php?d=$st'";
				echo '</script>';

		}
		else
		{
				echo "Failed";
				$st="Disapprove Requisition Failed, please try again.";
		
		}

}
?>
<!--<script language=JavaScript>
function reload(form)
{
var val=form.cat.options[form.cat.options.selectedIndex].value;
self.location='editrequisition.php?catt=' + val ;
}
</script> -->
<?php 
$datecreated=date('d-M-Y',strtotime($datecreated));
$requisitiondate=date('d-M-Y',strtotime($requisitiondate));
?>
		<div>
		<div class="section-title">
		DISAPPROVE 
		<?php 
		$facilityname = GetFacility($catt);
		echo strtoupper($facilityname);?>
		 REQUISITION</div>
		<div class="xtop">
			<?php if ($db !="")
				{
				?> 
				<table>
				  <tr>
					<td ><div class="error"><?php echo '<strong>Are you sure you want to disapprove '.strtoupper($facilityname).' requisition that was made on '.$requisitiondate.'?</strong>';?></div>
					</td>
				  </tr>
				</table>
				<?php 
				} 
				?>
		</div>
		<div>
			<form name="makerequisition" action="" method="get">
			<input name="db"  size="2" value="<?php echo $db;?>" type="hidden"/>
		 	<table width="534" border="0" class="data-table">
            <tr>
              <td width="127" >Facility</td>
              <td colspan="4">
			  
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
			  <td ><small><strong>Date Created</strong>&nbsp;&nbsp;<?php echo $datecreated;?></small></td>
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
              <td>DBS Filter Paper </td>
              <td  width="100" ><strong><?php echo $dbs;?></strong></td>
              <td  width="127" >Ziploc Bags</td>
              <td  width="127" ><strong><?php echo $dessicants;?></strong></td>
            </tr>
            <tr>
              <td>Dessicants</td>
              <td><strong><?php echo $glycline;?></strong></td>
              <td>Drying Racks</td>
              <td><strong><?php echo $lancets;?></strong></td>
            </tr>
            <tr>
              <td>Glycline Envelopes </td>
              <td><strong><?php echo $ziploc;?></strong></td>
              <td>Humidity Indicators</td>
              <td><strong><?php echo $rack;?></strong></td>
            </tr>
            <tr>
              <td>Lancets</td>
              <td ><strong><?php echo $humidity;?></strong></td>
			   <td>Lab Requisition Forms</td>
              <td ><strong><?php echo $reqform;?></strong></td>
            </tr>
            <tr>
              <td>Others / Comments </td>
              <td colspan="3"><strong><?php echo $comments;?></strong></td>
            </tr>
			
			<tr>
              <td>Reason for disapproval</td>
              <td colspan="3"><textarea name="disapprovecomment" rows="2" cols="65"></textarea></td>
            </tr>
			<tr>
            <td colspan="6">
			  	  <input name="disapprove" type="submit" class="button" value="Disapprove <?php echo strtoupper($facilityname)?> Requisition"/>
				   <input name="cancel" type="reset" class="button" value="Cancel" onClick="window.location.href='requisitionslist.php'" />
			</td>
            </tr>
          </table>
		 </form>
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>