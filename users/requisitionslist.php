<?php 
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
include('../includes/header.php');

//get the search variable
$success=$_GET['p'];
$approvesuccess=$_GET['approvesuccess'];
$disapprove=$_GET['d'];
$deletesuccess=$_GET['catt'];
//$datefilter = $_GET['datefil'];



$db = $_GET['db'];



$currentdate = date('Y'); //show the current year
$lowestdate = GetReqMin(); //get the lowest year from date received


?>
<script type="text/javascript" src="../includes/jquery.min.js"></script>
<script type="text/javascript" src="../includes/jquery.js"></script>
<script type='text/javascript' src='../includes/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="../includes/jquery.autocomplete.css" />
<script type="text/javascript">
$().ready(function() 
{
	
	$("#searchfacility").autocomplete("get_facility.php", {
		width: "auto",
		matchContains: true,
		mustMatch: true,
		selectFirst: false
	});
	
	$("#searchfacility").result(function(event, data, formatted) {
		$("#facilitycode").val(data[1]);
	});
});
</script>
<script language="javascript" src="calendar.js"></script>

<link type="text/css" href="calendar.css" rel="stylesheet" />	
<div>
	<div class="section-title">REQUISITIONS LIST</div>
<!--	success display -->
		<div>
				<?php 
				if ($success !="")
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
				<?php } 
				if ($deletesuccess != "")
					{
						$deleted = GetFacility($deletesuccess);
				?> 
					<table>
					  <tr>
						<td style="width:auto" >
						<div class="notice">
						<?php echo  '<strong>'.$deleted.' Requisition has been successfully deleted.</strong> <a href="requisitionslist.php">Click to Refresh page</a>';?>
						</div>
						</td>
					  </tr>
					</table>
				<?php } 
				 if ($st !="")
					{
				?> 
					<table>
					  <tr>
						<td style="width:auto" ><div class="error"><?php echo  '<strong>'.$st.'</strong>';?></div>
							
						</td>
					  </tr>
					</table>
				<?php }
				 if ($approvesuccess !="")
					{
				?> 
					<table>
					  <tr>
						<td style="width:auto" ><div class="success"><?php echo  '<strong>'.$approvesuccess.'</strong>';?></div>
							
						</td>
					  </tr>
					</table>
				<?php }  
				 if ($disapprove !="")
					{
				?> 
					<table>
					  <tr>
						<td style="width:auto" ><div class="error"><?php echo  '<strong>'.$disapprove.'</strong>';?></div>
							
						</td>
					  </tr>
					</table>
				<?php }  ?>
		</div>
<!--	end success display -->
	
	
<!--	begin date filter -->
<?php
//check if there are records in the database
$requisitionquery = "SELECT * FROM requisitions WHERE flag = 1 AND parentid < 1 ORDER BY id DESC ";
$result = mysql_query($requisitionquery) or die(mysql_error()); //for main display		
$no = mysql_num_rows($result);
	
if ($no != 0)
{
?>
		<div>
			<form name="dateform" action="" autocomplete="off">
			<table>
			<tr>
				<td>Approval Status
					<select name="approved">
					<option>Select One</option>
					<option value="1">Not Yet Approved</option>
					<option value="2">Approved</option>
					<option value="3">Disapproved</option>
					</select>
					&nbsp;
					<input type="submit" name="approvefilter" value="Filter" class="button"/><br/>			  </td>
					
					 <td> | </td>
			  
				 <td>From&nbsp;
			<?php
			  $myCalendar = new tc_calendar("fromdate", true, false);
			  $myCalendar->setIcon("../img/iconCalendar.gif");
			  $myCalendar->setDate(date('d'), date('m'), date('Y'));
			  $myCalendar->setPath("./");
			  $myCalendar->setYearInterval($lowestdate,$currentdate);
			  //$myCalendar->dateAllow('2008-05-13', '2015-03-01');
			  $myCalendar->setDateFormat('j F Y');
			  //$myCalendar->setHeight(350);	  
			  //$myCalendar->autoSubmit(true, "form1");
			  $myCalendar->writeScript();
			  ?>
			  &nbsp;To&nbsp;
			<?php
			  $myCalendar = new tc_calendar("todate", true, false);
			  $myCalendar->setIcon("../img/iconCalendar.gif");
			  $myCalendar->setDate(date('d'), date('m'), date('Y'));
			  $myCalendar->setPath("./");
			  $myCalendar->setYearInterval($lowestdate, $currentdate);
			  //$myCalendar->dateAllow('2008-05-13', '2015-03-01');
			  $myCalendar->setDateFormat('j F Y');
			  //$myCalendar->setHeight(350);	  
			  //$myCalendar->autoSubmit(true, "form1");
			  $myCalendar->writeScript();
			  ?>&nbsp;
			<input type="submit" name="datefilter" value="Filter" class="button"/>			&nbsp;			  </td>
			  
			  <td> | </td>
			 
			   <td> Province &nbsp;&nbsp;
			     <?php
				   $accountquery = "SELECT Code,name FROM provinces";
						
					$result = mysql_query($accountquery) or die('Error, query failed'); //onchange='submitForm();'
				
				   echo "<select name='prov';>\n";
					echo " <option value=''> Select One </option>";
					
				  while ($row = mysql_fetch_array($result))
				  {
						 $ID = $row['Code'];
						$name = $row['name'];
					echo "<option value='$ID'> $name</option>\n";
				  }
				  echo "</select>\n";
				  	?>
			     <input type="submit" name="provincefilter" value="Filter" class="button"/>
		      <br/>			  </td>
			 
			  <td> | </td>
			 
			   <td> Facility&nbsp;
			    <input type="text" name="searchfacility" id="searchfacility" class="text"/> <input type="hidden" name="facilitycode" id="facilitycode" />&nbsp;<input type="submit" name="ffilter" value="Filter" class="button"/><br/>			  </td>
			 
			  </tr>
			</table>
			</form>
<!--	end date filter -->
		</div>
			<?php
		
		if ($_REQUEST['ffilter'])
		{			
					//$datefilter = date("Y-m-d",strtotime($datefilter));
					$searchfacility = $_GET['facilitycode'];
					
					$showfacilityrequisition = "SELECT facility,ID,dbs,dessicants,glycline,lancets,reqform,ziploc,rack,humidity,datecreated,requisitiondate FROM requisitions WHERE facility = $searchfacility AND status = 1 AND parentid > 0 AND flag = 1 ORDER BY id DESC ";
					
					$displayfacilityrequisition = @mysql_query($showfacilityrequisition) or die(mysql_error());
					
					$showfacilityrequisition = mysql_num_rows($displayfacilityrequisition);//get the search count
					
					////GET FACILITY NAME///////////////////////////////////
					$getfacilityname = "SELECT name FROM facilitys WHERE ID = '$searchfacility'";
					$getfacility = mysql_query($getfacilityname) or die(mysql_error());
					$facility = mysql_fetch_array($getfacility);
					$showfacility = $facility['name'];
					/////END GET FACILITY NAME////////////////////////////////////////////////	
					
					if ($showfacilityrequisition!=0) //display table is count is NOT 0
					{
						
						echo "The <strong>Approved Requisitions</strong> filter for <strong>$showfacility</strong> returned <strong>".$showfacilityrequisition." </strong>results.&nbsp;&nbsp;&nbsp;";
						echo '<strong><a href="requisitionslist.php">Click to Refresh Page</a></strong>';
						
						/*	<!--	***************************************************************** -->*/
						echo '<table border="0"   class="data-table">
						<tr ><th>Count</th><th>Facility</th><th>DBS Paper</th><th>Dessicants</th><th>Glycline</th><th>Lancets</th><th>Req. Forms</th><th>Ziploc Bags</th><th>Drying Bags</th><th>Humidity</th><th>Requisition Date</th><th>Date Created</th><th>Task</th></tr>';
						/*<!--*********************************************************** -->*/
						
						
						while(list($facility,$dbcount,$dbs,$dessicants,$glycline,$lancets,$reqform,$ziploc,$rack,$humidity,$datecreated,$requisitiondate) = mysql_fetch_array($displayfacilityrequisition))
						{  
								/////////////////////////////////////////////////////////////////////
								$getfname = "SELECT name FROM facilitys WHERE ID = '$facility'";
								$getf = mysql_query($getfname) or die(mysql_error());
								$f = mysql_fetch_array($getf);
								$showf = $f['name'];
								/////////////////////////////////////////////////////////////////////
								$getfacilitytype = "SELECT ftype FROM facilitys WHERE ID = '$facility'";
								$getfacilityno = mysql_query($getfacilitytype) or die(mysql_error());
								$facilityno = mysql_fetch_array($getfacilityno);
								$showfacilityno = $facilityno['ftype'];
								/////////////////////////////////////////////////////////////////////
								$getfacilitytypename = "SELECT name FROM facilitytype WHERE ID = '$showfacilityno'";
								$getfacilitytype = mysql_query($getfacilitytypename) or die(mysql_error());
								$facilitytype = mysql_fetch_array($getfacilitytype);
								$showfacilitytype = $facilitytype['name'];
								/////////////////////////////////////////////////////////////////////
								
								$requisitionscount = $requisitionscount + 1;
								$requisitiondate=date('d-M-Y',strtotime($requisitiondate));
								
								echo "<tr >
										<td >$requisitionscount</td>
										<td >$showf $showfacilitytype</td>
										<td >$dbs</td>
										<td >$dessicants</td>
										<td >$glycline</td>
										<td >$lancets</td>
										<td >$reqform</td>
										<td >$ziploc</td>
										<td >$rack</td>
										<td >$humidity</td>
										<td >$requisitiondate</td>
										<td >$datecreated</td>
										<td ><a href=\"approvedrequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to View Approved Requisition Details'>View Approved Details</a> | <a href=\"downloadrequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to Download Requisition Details'>Download</a> </td>
										
										</tr>
										</table>";
						} 
						
						//end show search results
					}
					else //display message of count IS 0
					{	
						echo "The Requisitions filter for <strong>".$showfacility."</strong> returned <strong> 0 </strong>results.";
						
						echo '<strong><a href="requisitionslist.php">Click to Refresh Page</a></strong>';
					
						exit();
					}
			exit();
		}
		else if ($_REQUEST['datefilter'])
		{
				$fromdate = $_GET['fromdate'];
				$todate = $_GET['todate'];
				
				//sql statement for filter
					$showdate = "SELECT facility,ID,dbs,dessicants,glycline,lancets,reqform,ziploc,rack,humidity,datecreated,requisitiondate FROM requisitions WHERE datecreated BETWEEN '$fromdate' AND '$todate' AND status = 1 AND parentid > 0 ORDER BY id DESC ";
					
					$displaydate = @mysql_query($showdate) or die(mysql_error());
					
					$showrequisitiondate = mysql_num_rows($displaydate);//get the search count
					
					//change the date format
						$fromdate =  date('d-M-Y',strtotime($fromdate));
						$todate = date('d-M-Y',strtotime($todate));
						
					if ($showrequisitiondate!=0) //display table is count is NOT 0
					{
						
						echo "The <strong>Approved Requisitions</strong> filter for requisitions made between <strong>$fromdate </strong>to <strong>$todate</strong> returned <strong>".$showrequisitiondate." </strong>results.&nbsp;&nbsp;&nbsp;";
						echo '<strong><a href="requisitionslist.php">Click to Refresh Page</a></strong>';
						
						/*	<!--	***************************************************************** -->*/
								echo '<table border="0"   class="data-table">
								<tr ><th>Count</th><th>Facility</th><th>DBS Paper</th><th>Dessicants</th><th>Glycline</th><th>Lancets</th><th>Req. Forms </th><th>Ziploc Bags</th><th>Drying Bags</th><th>Humidity</th><th>Requisition Date</th><th>Date Created</th><th>Task</th></tr>';
								/*<!--*********************************************************** -->*/
						
						
						while(list($facility,$dbcount,$dbs,$dessicants,$glycline,$lancets,$reqform,$ziploc,$rack,$humidity,$datecreated,$requisitiondate) = mysql_fetch_array($displaydate))
						{  
								/////////////////////////////////////////////////////////////////////
								$getfname = "SELECT name FROM facilitys WHERE ID = '$facility'";
								$getf = mysql_query($getfname) or die(mysql_error());
								$f = mysql_fetch_array($getf);
								$showf = $f['name'];
								/////////////////////////////////////////////////////////////////////
								$getfacilitytype = "SELECT ftype FROM facilitys WHERE ID = '$facility'";
								$getfacilityno = mysql_query($getfacilitytype) or die(mysql_error());
								$facilityno = mysql_fetch_array($getfacilityno);
								$showfacilityno = $facilityno['ftype'];
								/////////////////////////////////////////////////////////////////////
								$getfacilitytypename = "SELECT name FROM facilitytype WHERE ID = '$showfacilityno'";
								$getfacilitytype = mysql_query($getfacilitytypename) or die(mysql_error());
								$facilitytype = mysql_fetch_array($getfacilitytype);
								$showfacilitytype = $facilitytype['name'];
								/////////////////////////////////////////////////////////////////////
								
								$requisitionscount = $requisitionscount + 1;
								$requisitiondate=date('d-M-Y',strtotime($requisitiondate));
								
								echo "<tr >
										<td >$requisitionscount</td>
										<td >$showf $showfacilitytype</td>
										<td >$dbs</td>
										<td >$dessicants</td>
										<td >$glycline</td>
										<td >$lancets</td>
										<td >$reqform</td>
										<td >$ziploc</td>
										<td >$rack</td>
										<td >$humidity</td>
										<td >$requisitiondate</td>
										<td >$datecreated</td>
										<td ><a href=\"approvedrequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to View Approved Requisition Details'>View Approved Details</a> </td>
										
										</tr>
										";
						} echo "</table>";
						
						//end show search results
					}
					else //display message of count IS 0
					{	
						echo "There are <strong>0 </strong>results for requisitions entered between <strong>$fromdate</strong> and <strong>$todate</strong>.";
						
						echo '<strong><a href="requisitionslist.php">Click to Refresh Page</a></strong>';
					
					}
			
				exit();
		}
		else if ($_REQUEST['approvefilter'])
		{
			$approved = $_GET['approved'];
			
			//get the filter type
			if ($approved==1)
			{ 	$approved = "Not Yet Approved";
				$value = 0;
				
				
				//sql statement for filter
					$showapproved = "SELECT facility,dbs,dessicants,glycline,lancets,reqform,ziploc,rack,humidity,datecreated,requisitiondate FROM requisitions WHERE status = '$value' AND flag = 1  AND parentid < 1 ORDER BY id DESC ";
					
					$displayapproved = @mysql_query($showapproved) or die(mysql_error());
					
					$showapproved = mysql_num_rows($displayapproved);//get the search count
					
								
					if ($showapproved!=0) //display table is count is NOT 0
					{
						
						echo "The Requisitions filter for <strong>$approved</strong> returned <strong>".$showapproved." </strong>results.&nbsp;&nbsp;&nbsp;";
						echo '<strong><a href="requisitionslist.php">Click to Refresh Page</a></strong>';
						
						
						
						/*	<!--	***************************************************************** -->*/
								echo '<table border="0"   class="data-table">
								<tr ><th>Count</th><th>Facility</th><th>DBS Paper</th><th>Dessicants</th><th>Glycline</th><th>Lancets</th><th>Req. Forms</th><th>Ziploc Bags</th><th>Drying Bags</th><th>Humidity</th><th>Requisition Date</th><th>Date Created</th><th>Task</th></tr>';
								/*<!--*********************************************************** -->*/
						
						
						while(list($facility,$dbs,$dessicants,$glycline,$lancets,$reqform,$ziploc,$rack,$humidity,$datecreated,$requisitiondate) = mysql_fetch_array($displayapproved))
						{  
								/////////////////////////////////////////////////////////////////////
								$getfname = "SELECT name FROM facilitys WHERE ID = '$facility'";
								$getf = mysql_query($getfname) or die(mysql_error());
								$f = mysql_fetch_array($getf);
								$showf = $f['name'];
								/////////////////////////////////////////////////////////////////////
								$getfacilitytype = "SELECT ftype FROM facilitys WHERE ID = '$facility'";
								$getfacilityno = mysql_query($getfacilitytype) or die(mysql_error());
								$facilityno = mysql_fetch_array($getfacilityno);
								$showfacilityno = $facilityno['ftype'];
								/////////////////////////////////////////////////////////////////////
								$getfacilitytypename = "SELECT name FROM facilitytype WHERE ID = '$showfacilityno'";
								$getfacilitytype = mysql_query($getfacilitytypename) or die(mysql_error());
								$facilitytype = mysql_fetch_array($getfacilitytype);
								$showfacilitytype = $facilitytype['name'];
								/////////////////////////////////////////////////////////////////////
								
								$requisitionscount = $requisitionscount + 1;
								$requisitiondate=date('d-M-Y',strtotime($requisitiondate));
								
								echo "<tr >
										<td >$requisitionscount</td>
										<td >$showf $showfacilitytype</td>
										<td >$dbs</td>
										<td >$dessicants</td>
										<td >$glycline</td>
										<td >$lancets</td>
										<td >$reqform</td>
										<td >$ziploc</td>
										<td >$rack</td>
										<td >$humidity</td>
										<td >$requisitiondate</td>
										<td >$datecreated</td>
										<td ><a href=\"viewrequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to View Requisition Details'>Details</a> | <a href=\"editrequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to Edit Requisition Details'>Edit  </a> | 
							<a href=\"approverequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to Approve Requisition Details'>Approve</a> | 
							<a href=\"disapproverequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to Disapprove Requisition Details'>Disapprove</a> | 
							<a href=\"deleterequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to Delete Requisition Details'>Delete</a> 
							</td>
										
										</tr>
										";
						} echo "</table>";
						
						//end show search results
					}
					else //display message of count IS 0
					{	
						echo "There are <strong>0 </strong>results for <strong>$approved</strong> requisitions.";
						
						echo '<strong><a href="requisitionslist.php">Click to Refresh Page</a></strong>';
					
					}
			
				exit();
			}
			else if ($approved==2)
			{ 	$approved = "Approved";
				$value = 1;
				
				//sql statement for filter
					$showapproved = "SELECT facility,ID,dbs,dessicants,glycline,lancets,reqform,ziploc,rack,humidity,datecreated,requisitiondate FROM requisitions WHERE parentid > 0 ORDER BY id DESC ";
					
					
					$displayapproved = @mysql_query($showapproved) or die(mysql_error());
					
					$showapproved = mysql_num_rows($displayapproved);//get the search count
					
								echo $dbcount;
					if ($showapproved!=0) //display table is count is NOT 0
					{
						
						echo "The Requisitions filter for <strong>$approved</strong> returned <strong>".$showapproved." </strong>results.&nbsp;&nbsp;&nbsp;";
						echo '<strong><a href="requisitionslist.php">Click to Refresh Page</a></strong>';
						
						
						
						/*	<!--	***************************************************************** -->*/
								echo '<table border="0"   class="data-table">
								<tr ><th>Count</th><th>Facility</th><th>DBS Paper</th><th>Dessicants</th><th>Glycline</th><th>Lancets</th><th>Req. Forms</th><th>Ziploc Bags</th><th>Drying Bags</th><th>Humidity</th><th>Requisition Date</th><th>Date Created</th><th>Task</th></tr>';
								/*<!--*********************************************************** -->*/
						
						
						while(list($facility,$dbcount,$dbs,$dessicants,$glycline,$lancets,$reqform,$ziploc,$rack,$humidity,$datecreated,$requisitiondate) = mysql_fetch_array($displayapproved))
						{  
								/////////////////////////////////////////////////////////////////////
								$getfname = "SELECT name FROM facilitys WHERE ID = '$facility'";
								$getf = mysql_query($getfname) or die(mysql_error());
								$f = mysql_fetch_array($getf);
								$showf = $f['name'];
								/////////////////////////////////////////////////////////////////////
								$getfacilitytype = "SELECT ftype FROM facilitys WHERE ID = '$facility'";
								$getfacilityno = mysql_query($getfacilitytype) or die(mysql_error());
								$facilityno = mysql_fetch_array($getfacilityno);
								$showfacilityno = $facilityno['ftype'];
								/////////////////////////////////////////////////////////////////////
								$getfacilitytypename = "SELECT name FROM facilitytype WHERE ID = '$showfacilityno'";
								$getfacilitytype = mysql_query($getfacilitytypename) or die(mysql_error());
								$facilitytype = mysql_fetch_array($getfacilitytype);
								$showfacilitytype = $facilitytype['name'];
								/////////////////////////////////////////////////////////////////////
								
								$requisitionscount = $requisitionscount + 1;
								$requisitiondate=date('d-M-Y',strtotime($requisitiondate));
								
								echo "<tr >
										<td >$requisitionscount</td>
										<td >$showf $showfacilitytype</td>
										<td >$dbs</td>
										<td >$dessicants</td>
										<td >$glycline</td>
										<td >$lancets</td>
										<td >$reqform</td>
										<td >$ziploc</td>
										<td >$rack</td>
										<td >$humidity</td>
										<td >$requisitiondate</td>
										<td >$datecreated</td>
										<td ><a href=\"approvedrequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to View Approved Requisition Details'>View Approved Details</a> | <a href=\"downloadrequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to Download Requisition Details'>Download</a> </td>
										
										</tr>
										";
						} echo "</table>";
						
						//end show search results
					}
					else //display message of count IS 0
					{	
						echo "There are <strong>0 </strong>results for <strong>$approved</strong> requisitions.";
						
						echo '<strong><a href="requisitionslist.php">Click to Refresh Page</a></strong>';
					
					}
			
				exit();
			}
			else if ($approved==3)
			{ 	$approved = "Disapproved";
				$value = 2;
				//sql statement for filter
					$showapproved = "SELECT facility,ID,dbs,dessicants,glycline,lancets,reqform,ziploc,rack,humidity,datecreated,requisitiondate FROM requisitions WHERE status = '$value' ORDER BY id DESC ";
					
					
					$displayapproved = @mysql_query($showapproved) or die(mysql_error());
					
					$showapproved = mysql_num_rows($displayapproved);//get the search count
					
								echo $dbcount;
					if ($showapproved!=0) //display table is count is NOT 0
					{
						
						echo "The Requisitions filter for <strong>$approved</strong> returned <strong>".$showapproved." </strong>results.&nbsp;&nbsp;&nbsp;";
						echo '<strong><a href="requisitionslist.php">Click to Refresh Page</a></strong>';
						
						
						
						/*	<!--	***************************************************************** -->*/
								echo '<table border="0"   class="data-table">
								<tr ><th>Count</th><th>Facility</th><th>DBS Paper</th><th>Dessicants</th><th>Glycline</th><th>Lancets</th><th>Req. Forms</th><th>Ziploc Bags</th><th>Drying Bags</th><th>Humidity</th><th>Requisition Date</th><th>Date Created</th><th>Task</th></tr>';
								/*<!--*********************************************************** -->*/
						
						
						while(list($facility,$dbcount,$dbs,$dessicants,$glycline,$lancets,$reqform,$ziploc,$rack,$humidity,$datecreated,$requisitiondate) = mysql_fetch_array($displayapproved))
						{  
								/////////////////////////////////////////////////////////////////////
								$getfname = "SELECT name FROM facilitys WHERE ID = '$facility'";
								$getf = mysql_query($getfname) or die(mysql_error());
								$f = mysql_fetch_array($getf);
								$showf = $f['name'];
								/////////////////////////////////////////////////////////////////////
								$getfacilitytype = "SELECT ftype FROM facilitys WHERE ID = '$facility'";
								$getfacilityno = mysql_query($getfacilitytype) or die(mysql_error());
								$facilityno = mysql_fetch_array($getfacilityno);
								$showfacilityno = $facilityno['ftype'];
								/////////////////////////////////////////////////////////////////////
								$getfacilitytypename = "SELECT name FROM facilitytype WHERE ID = '$showfacilityno'";
								$getfacilitytype = mysql_query($getfacilitytypename) or die(mysql_error());
								$facilitytype = mysql_fetch_array($getfacilitytype);
								$showfacilitytype = $facilitytype['name'];
								/////////////////////////////////////////////////////////////////////
								
								$requisitionscount = $requisitionscount + 1;
								$requisitiondate=date('d-M-Y',strtotime($requisitiondate));
								
								echo "<tr >
										<td >$requisitionscount</td>
										<td >$showf $showfacilitytype</td>
										<td >$dbs</td>
										<td >$dessicants</td>
										<td >$glycline</td>
										<td >$lancets</td>
										<td >$reqform</td>
										<td >$ziploc</td>
										<td >$rack</td>
										<td >$humidity</td>
										<td >$requisitiondate</td>
										<td >$datecreated</td>
										<td ><a href=\"disapprovedrequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to View Disapproved Requisition Details'>View Disapproved Details</a> | <a href=\"downloaddisapprovedrequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to View Download Disapproved Requisition Details'>Download</a> </td>
										
										</tr>
										";
						} echo "</table>";
						
						//end show search results
					}
					else //display message of count IS 0
					{	
						echo "There are <strong>0 </strong>results for <strong>$approved</strong> requisitions.";
						
						echo '<strong><a href="requisitionslist.php">Click to Refresh Page</a></strong>';
					
					}
			
				exit();
			}
			else
			{
				echo "<strong><center>Please select the Approval Status to filter by.</center></strong>";
				exit();
			}
			exit();
		}
		else if ($_REQUEST['provincefilter'])
		{
				$province = $_GET['prov'];
				
				//sql statement for filter
					$showprovince = "SELECT r.facility,r.ID,r.dbs,r.dessicants,r.glycline,r.lancets,r.reqform,r.ziploc,r.rack,r.humidity,r.datecreated,r.requisitiondate from requisitions r,facilitys f, districts d where r.facility = f.id AND f.district = d.id AND d.province = $province AND r.status = 1 AND r.parentid > 0 ORDER BY id DESC";
					
					$displayprovince = @mysql_query($showprovince) or die(mysql_error());
					
					$showprovinceno = mysql_num_rows($displayprovince);//get the search count
					
						//get the province name
						$showprovincename = "SELECT name FROM provinces WHERE Code = '$province'";
						$displayprovincename = @mysql_query($showprovincename) or die(mysql_error());
						$provincename = mysql_fetch_array($displayprovincename);
						$showname = $provincename['name'];
						/////end get province name//////
						
					if ($showprovinceno!=0) //display table is count is NOT 0
					{
						echo "The Requisitions received from <strong>$showname</strong> returned <strong>".$showprovinceno." </strong>results.&nbsp;&nbsp;&nbsp;";
						echo '<strong><a href="requisitionslist.php">Click to Refresh Page</a></strong>';
						
						
						
						/*	<!--	***************************************************************** -->*/
								echo '<table border="0"   class="data-table">
								<tr ><th>Count</th><th>Facility</th><th>DBS Paper</th><th>Dessicants</th><th>Glycline</th><th>Lancets</th><th>Req. Forms</th><th>Ziploc Bags</th><th>Drying Bags</th><th>Humidity</th><th>Requisition Date</th><th>Date Created</th><th>Task</th></tr>';
								/*<!--*********************************************************** -->*/
						
						
						while(list($facility,$dbcount,$dbs,$dessicants,$glycline,$lancets,$reqform,$ziploc,$rack,$humidity,$datecreated,$requisitiondate) = mysql_fetch_array($displayprovince))
						{  
								/////////////////////////////////////////////////////////////////////
								$getfname = "SELECT name FROM facilitys WHERE ID = '$facility'";
								$getf = mysql_query($getfname) or die(mysql_error());
								$f = mysql_fetch_array($getf);
								$showf = $f['name'];
								/////////////////////////////////////////////////////////////////////
								$getfacilitytype = "SELECT ftype FROM facilitys WHERE ID = '$facility'";
								$getfacilityno = mysql_query($getfacilitytype) or die(mysql_error());
								$facilityno = mysql_fetch_array($getfacilityno);
								$showfacilityno = $facilityno['ftype'];
								/////////////////////////////////////////////////////////////////////
								$getfacilitytypename = "SELECT name FROM facilitytype WHERE ID = '$showfacilityno'";
								$getfacilitytype = mysql_query($getfacilitytypename) or die(mysql_error());
								$facilitytype = mysql_fetch_array($getfacilitytype);
								$showfacilitytype = $facilitytype['name'];
								/////////////////////////////////////////////////////////////////////
								
								$requisitionscount = $requisitionscount + 1;
								$requisitiondate=date('d-M-Y',strtotime($requisitiondate));
								
								echo "<tr >
										<td >$requisitionscount</td>
										<td >$showf $showfacilitytype</td>
										<td >$dbs</td>
										<td >$dessicants</td>
										<td >$glycline</td>
										<td >$lancets</td>
										<td >$reqform</td>
										<td >$ziploc</td>
										<td >$rack</td>
										<td >$humidity</td>
										<td >$requisitiondate</td>
										<td >$datecreated</td>
										<td ><a href=\"approvedrequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to View Approved Requisition Details'>View Approved Details</a> | <a href=\"downloadrequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to Download Requisition Details'>Download</a> </td>
										
										</tr>
										";
						} echo "</table>";
						
						//end show search results
					}
					else //display message of count IS 0
					{	
						echo "The Requisitions received from <strong>$showname</strong> returned <strong>".$showprovinceno." </strong>results.&nbsp;&nbsp;&nbsp;";
						echo '<strong><a href="requisitionslist.php">Click to Refresh Page</a></strong>';
						exit();
					
					}
			
				exit();
		
		}
		else
		{		
				//query database for all requisitions
	
			
					
				$showrequisition = "SELECT facility,ID,dbs,dessicants,glycline,lancets,reqform,ziploc,rack,humidity,datecreated,parentid,status,requisitiondate FROM requisitions WHERE status = 0 AND flag = 1 AND parentid < 1 ORDER BY id DESC";
				$displayrequisitions = mysql_query($showrequisition) or die(mysql_error());
				$reqcount = mysql_num_rows($displayrequisitions);
				
				$requisitionscount = 0;
				
				if ($reqcount!=0)
				{
					echo "
					<table><tr><td>
					The Requisitions shown are <strong>Not Yet Approved</strong>
					</td></tr></table>";
								/*	<!--	***************************************************************** -->*/
					echo '<table border="0" class="data-table">
					<tr ><th>Count</th><th>Facility</th><th>DBS Paper</th><th>Dessicants</th><th>Glycline</th><th>Lancets</th><th>Req. Forms</th><th>Ziploc Bags</th><th>Drying Bags</th><th>Humidity</th><th>Requisition Date</th><th>Date Created</th><th>Task</th></tr>';
					/*<!--*********************************************************** -->*/
					
						while(list($facility,$dbcount,$dbs,$dessicants,$glycline,$lancets,$reqform,$ziploc,$rack,$humidity,$datecreated,$parentid,$status,$requisitiondate) = mysql_fetch_array($displayrequisitions))
						{  
							/////////////////////////////////////////////////////////////////////
							$getfname = "SELECT name FROM facilitys WHERE ID = '$facility'";
							$getf = mysql_query($getfname) or die(mysql_error());
							$f = mysql_fetch_array($getf);
							$showf = $f['name'];
							/////////////////////////////////////////////////////////////////////
							$getfacilitytype = "SELECT ftype FROM facilitys WHERE ID = '$facility'";
							$getfacilityno = mysql_query($getfacilitytype) or die(mysql_error());
							$facilityno = mysql_fetch_array($getfacilityno);
							$showfacilityno = $facilityno['ftype'];
							/////////////////////////////////////////////////////////////////////
							$getfacilitytypename = "SELECT name FROM facilitytype WHERE ID = '$showfacilityno'";
							$getfacilitytype = mysql_query($getfacilitytypename) or die(mysql_error());
							$facilitytype = mysql_fetch_array($getfacilitytype);
							$showfacilitytype = $facilitytype['name'];
							/////////////////////////////////////////////////////////////////////
							
							
							$requisitionscount = $requisitionscount + 1;
							$datecreated=date('d-M-Y',strtotime($datecreated));
							$requisitiondate=date('d-M-Y',strtotime($requisitiondate));
							
							echo "<tr >
									<td >$requisitionscount</td>
									
									<td >$showf $showfacilitytype</td>
									<td >$dbs</td>
									<td >$dessicants</td>
									<td >$glycline</td>
									<td >$lancets</td>
									<td >$reqform</td>
									<td >$ziploc</td>
									<td >$rack</td>
									<td >$humidity</td>
									<td >$requisitiondate</td>
									<td >$datecreated</td>
									<td ><a href=\"viewrequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to View Requisition Details'>Details</a> | <a href=\"editrequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to Edit Requisition Details'>Edit  </a> | 
									<a href=\"approverequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to Approve Requisition Details'>Approve</a> | 
									<a href=\"disapproverequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to Disapprove Requisition Details'>Disapprove</a> | 
									<a href=\"deleterequisition.php" ."?catt=$facility&db=$dbcount" . "\" title='Click to Delete Requisition Details'>Delete</a> </td>
									
									</tr>";
						}
						echo "</table>";
				/*<!--***********************************************************	 -->*/
						exit();
					
				}
				else
				{
					
					
					
					echo "There are <strong>$reqcount</strong> results for <strong>Not Yet Approved</strong> requisitions.";
					exit();
				}
				
				
				exit();
		}
}
else
		{
		?>
		<table   >
				  <tr>
					<td style="width:auto" >
					<div class="notice">
					<?php echo '<center><strong>No Requisitions have been Added</strong></center>';		?></div>
					</td>
				  </tr>
				</table>
				<?php } ?>

<!--	end requisition details -->
</div>
		
 <?php include('../includes/footer.php');?>