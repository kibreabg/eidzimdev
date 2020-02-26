<?php 
session_start();
$labss=$_SESSION['lab'];
include('../includes/functions.php');

$catt=$_GET['catt'];
$db = $_GET['db'];  ///requisition id for the approved requisiotn
$approvesuccess=$_GET['catt'];
$approveerror=$_GET['error'];
$provname=$_GET['r'];
$distname=$_GET['z'];
$fcode=  $_GET['cat'];
$province = $_GET['province'];
$district = $_GET['district'];


//get the approved requisition detailsGetApprovedRequisitionInfo
$getapprovedreqinfo = GetRequisitionInfo($db);
//assign the records
$parentid = $getapprovedreqinfo['parentid'];
$adbs = $getapprovedreqinfo['dbs'];
$adessicants = $getapprovedreqinfo['dessicants'];
$aglycline = $getapprovedreqinfo['glycline'];
$alancets = $getapprovedreqinfo['lancets'];
$aziploc = $getapprovedreqinfo['ziploc'];
$arack = $getapprovedreqinfo['rack'];
$ahumidity = $getapprovedreqinfo['humidity'];
$acomments = $getapprovedreqinfo['comments'];
//$adatecreated = $getapprovedreqinfo['datecreated'];	
$arequisitiondate = $getapprovedreqinfo['requisitiondate'];
//$adatemodified = $getapprovedreqinfo['datemodified'];
$approvecomments = $getapprovedreqinfo['approvecomments'];


//get the requisition record from the database of original request
$getreqinfo = GetRequisitionInfo($parentid);
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
$datemodified = $getreqinfo['datemodified'];

//orignal request
$datecreated=date('d-M-Y',strtotime($datecreated));
$requisitiondate=date('d-M-Y',strtotime($requisitiondate));
$datemodified=date('d-M-Y',strtotime($datemodified));
//approved request
$adatecreated=date('d-M-Y',strtotime($adatecreated));
$arequisitiondate=date('d-M-Y',strtotime($arequisitiondate));
$adatemodified=date('d-M-Y',strtotime($adatemodified));

$facilityname = GetFacility($catt);
?>
<html>
<link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
<body onLoad="JavaScript:window.print();">
		 
		
			<form name="makerequisition" action="" method="get"/>
		 	<table class="data-table">
			<tr align="center">

			<td colspan="7" class="subsection-title" ><img src="../img/naslogo.jpg" alt="NASCOP"><br/><strong>APPROVED REQUISITION FOR <?php echo strtoupper($facilityname);?></strong>
			</td>
			</tr>
			<tr><td colspan="7">&nbsp;</td></tr>
			<tr>
              <td width="127" >Facility</td>
              <td colspan="4" width="400"><input name="db"  size="2" value="<?php echo $db;?>" type="hidden"/>
			  
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
							$provincenamequery=mysql_query("SELECT name FROM provinces WHERE  ID='$provid'"); 
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
				<td > Requisition Date</td>
				<td colspan="5"><strong><?php echo $requisitiondate;?></td>
			</tr>
			<tr><td colspan="7">&nbsp;</td></tr>
			<tr>
              <td class="subsection-title" colspan="7"><strong>Consumables Requested</strong> </td>
			 
            </tr>
			<tr>
			<td><strong>Item</strong></td>
			<td><strong>Order</strong></td>
			<td width="100"><strong>Supply</strong></td>
			<td><strong>Item</strong></td>
			<td><strong>Order</strong></td>
			<td><strong>Supply</strong></td>
			</tr>
            <tr>
              <td >DBS Filter Paper </td>
              <td width="45"><strong><?php echo $dbs;?></strong></td>
			  <td><?php echo $adbs;?></td>
              <td width="150">Ziploc Bags</td>
              <td width="45"><strong><?php echo $ziploc;?></strong></td>
			  <td><?php echo $aziploc;?></td>
            </tr>
            <tr>
              <td>Dessicants</td>
              <td><strong><?php echo $dessicants;?></strong></td>
			  <td><?php echo $adessicants;?></td>
              <td>Drying Racks</td>
              <td><strong><?php echo $rack;?></strong></td>
			  <td><?php echo $arack;?></td>
            </tr>
            <tr>
              <td>Glycline Envelopes </td>
              <td><strong><?php echo $glycline;?></strong></td>
			  <td><?php echo $aglycline;?></td>
              <td>Humidity Indicators</td>
              <td><strong><?php echo $humidity;?></strong></td>
			  <td><?php echo $ahumidity;?></td>
            </tr>
            <tr>
              <td>Lancets</td>
              <td><strong><?php echo $lancets;?></strong></td>
			  <td colspan="4"><?php echo $alancets;?></td>
            </tr>
			<tr><td colspan="7">&nbsp;</td></tr>
            <tr>
              <td colspan="7"> Comments </td>
			</tr>
			<tr>
              <td colspan="7"><strong><?php echo $comments;?></strong></td>
            </tr>
			<tr><td colspan="7">&nbsp;</td></tr>
            <tr>
			 <td colspan="7"> Approval Comments </td>
			</tr>
			<tr>
			  <td colspan="7"><strong><?php echo $approvecomments; ?></strong></td>
            </tr>
			
          </table>
		 </form>
		
</body>		<?php include('../includes/footer.php');?>
</html>
 