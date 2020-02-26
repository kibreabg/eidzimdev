<?php
require_once('../connection/config.php');
include('../includes/header.php');
//get the search variable
$searchparameter = $_GET['search'];
$success = $_GET['p'];

?>
<script type="text/javascript" src="../includes/jquery.min.js"></script>
<script type="text/javascript" src="../includes/jquery.js"></script>
<script type='text/javascript' src='../includes/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="../includes/jquery.autocomplete.css" />
<script type="text/javascript">
$().ready(function() {
	
	$("#facility").autocomplete("get_facility.php", {
		width: 260,
		matchContains: true,
		mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
	
	$("#facility").result(function(event, data, formatted) {
		$("#fcode").val(data[1]);
	});
});
</script>
<div>
	<?php 
	$showfacility = "SELECT ID,facilitycode,name,district,ftype,telephone,telephone2,email,contactperson,PostalAddress FROM facilitys WHERE Flag = 1 ORDER BY name ASC";
			
			$displayfacilities = @mysql_query($showfacility) or die(mysql_error());
			$facilitycount = mysql_num_rows($displayfacilities);
	?>
	<div class="section-title" style="font-family:Verdana, Arial, Helvetica, sans-serif">FACILITITES LIST </div>
	<?php if ($success !="")
		{
		?> 
		<table>
  			<tr>
    			<td style="width:auto" ><div class="success"><?php echo  '<strong>'.' <font color="#666600">'.$success.'</strong>'.' </font>';?></div></td>
  			</tr>
		</table>
	<?php 
		} 
	
			
		
if ($facilitycount != 0)
{		
	$rowsPerPage = 150; //number of rows to be displayed per page

		// by default we show first page
		$pageNum = 1;
		
		// if $_GET['page'] defined, use it as page number
		if(isset($_GET['page']))
		{
		$pageNum = $_GET['page'];
		}
		
		// counting the offset
		$offset = ($pageNum - 1) * $rowsPerPage;
		
if (empty($searchparameter))
{
	
		?>
			<div>
				<form name="filterform" method="get" action="facilitieslist.php?view=1">
				<table border="0"   >
				<tr>
				<td>Province</td>
				<td><!--show the lab types -->
				<?php
			   $accountquery = "SELECT Code,name FROM provinces ORDER BY name ASC";
					
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
				<!--<input type="submit" name="provincefilter" value="Filter" class="button"/>--><br/>
				</td>
				<td> | </td>
				<td>District</td>
				  <td>
				  <?php
			   $accountquery = "SELECT ID,name FROM districts ORDER BY name ASC";
					
				$result = mysql_query($accountquery) or die('Error, query failed'); //onchange='submitForm();'
			
			   echo "<select name='district';>\n";
				echo " <option value=''> Select One </option>";
				
			  while ($row = mysql_fetch_array($result))
			  {
					 $ID = $row['ID'];
					$name = $row['name'];
				echo "<option value='$ID'> $name</option>\n";
			  }
			  echo "</select>\n";
			  ?><!--<input type="submit" name="districtfilter" value="Filter" class="button"/>--><br/>
				  </td>
				  <td> | </td>
				<td>Facility</td>
				  <td>
				
				  <input name="facility" id="facility" type="text" class="text" size="25" />
					<input type="hidden" name="fcode" id="fcode" />&nbsp; 
						  <input type="submit" name="filterform" value="Search" class="button"/>
				<br/>
				  </td>
				</tr>
				</table>
				</form>
			</div>
	
		<?php
		
		if ($_REQUEST['filterform'])
		{	$provid = $_GET['prov'];
		}
		if ($_REQUEST['filterform'])
		{	$fcode = $_GET['fcode'];
			$fname = $_GET['facility'];
		}
		if ($_REQUEST['filterform'])
		{	$district = $_GET['district'];
		}
		
		$querycount = " SELECT f.ID,f.facilitycode,f.name,f.district,f.ftype,f.telephone,f.telephone2,f.ContactEmail,f.contactperson, f.imei, f.pass FROM facilitys f, districts d WHERE 1 = Case When '$provid' = '' Then 1  When '$provid' = d.province Then 1  END and 1 = Case When '$fcode' = '' Then 1  When '$fcode' = f.ID Then 1  END and 1 = Case When '$district' = '' Then 1  When '$district' = f.district Then 1  END and f.district = d.ID AND f.flag = 1 ORDER BY f.name ASC ";
		
		$resultcount = @mysql_query($querycount) or die(mysql_error());
			
	    $totalresult = mysql_num_rows($resultcount);	
		
		$query = " SELECT f.ID,f.facilitycode,f.name,f.district,f.ftype,f.telephone,f.telephone2,f.ContactEmail,f.contactperson, f.imei, f.pass FROM facilitys f, districts d WHERE 1 = Case When '$provid' = '' Then 1  When '$provid' = d.province Then 1  END and 1 = Case When '$fcode' = '' Then 1  When '$fcode' = f.ID Then 1  END and 1 = Case When '$district' = '' Then 1  When '$district' = f.district Then 1  END and f.district = d.ID AND f.flag = 1 ORDER BY f.name ASC LIMIT $offset, $rowsPerPage";

$displayfacilities = @mysql_query($query) or die(mysql_error());
		
		if ($provid != '')
		{

			
			
			
			

			$provname=GetProvname($provid);
				
			
			//$displayprovincefacilities = @mysql_query($showprovincefacility) or die(mysql_error());
			
			//$displayprovincefacilitiescount = mysql_num_rows($displayprovincefacilities);				
	
			//$message = "The filter for <strong>$provname</strong> returned <strong>$displayprovincefacilitiescount</strong> results. <a href='facilitieslist.php?view=1'><strong>Click here to refresh page.</strong></a>";
			
			/////get the facilities
	
		}
		else if (($fcode != '') or ($fname!=''))
		{
					
			

		}
		else if ($district !='')
		{
			$distname=GetDistrictName($district);
		
		
		}
		else
		{	
			
		
			$message = '<small>Total Facilities : <strong>'.GetTotalFacilities().'</strong> </small>';
		}
		
		//display normal view
		echo '';
		
		echo "
		<table>
		
				<tr class='4'>
					<td>
					<small><div class='notice'><font style='font-family:Verdana, Arial, Helvetica, sans-serif' color='#330000'>$message</font></DIV></small>
					</td>
					<td><a href='facilitieslistexcel.php?view=1&provid=$provid&fcode=$fcode&fname=$fname&district=$district' title='Download Excel'><img src='../img/excel.gif' alt='Download Excel'>&nbsp;<small><font style='font-family:Verdana, Arial, Helvetica, sans-serif'>EXCEL</font></small></a>  |  
					
					<a href='eidfacilitieslistexcel.php?view=1&provid=$provid&fcode=$fcode&fname=$fname&district=$district' title='Download Excel'><img src='../img/excel.gif' alt='Download Excel'>&nbsp;<small><font style='font-family:Verdana, Arial, Helvetica, sans-serif'>EID EXCEL</font></small></a>
					</td>
					
				</tr>
		</table>
		<table border=0  class='data-table'>
		<tr ><th>Facility Code</th><th>Facility Name</th><th>District</th><th>Province</th><th>Land Line</th><th>Mobile No</th><th>Email Address</th><th>Contact Person</th><th><small><font color='#0000FF'>SMS Printer?</font></small></th><th>IMEI No.</th><th>Password</th><th><small>WITH DATA?</small></th><th>Task</th></tr>";			
		
		
		//list the variables that you would like to get
		while(list($ID,$facilitycode,$name,$district,$ftype,$telephone,$telephone2,$email,$contactperson,$imei,$pass ) = mysql_fetch_array($displayfacilities))
		{  
			$distname=GetDistrictName($district);
			$provid=GetProvid($district);
			$provname=GetProvname($provid);
			
			//check if the facility has an sms printer
			if (($imei == '') )
			{
				$printer ='<img src="../img/item_chk0_dis.gif">';
			}
			else if (($imei != '') )
			{
				$printer ='<img src="../img/item_chk1.gif">';
			}
			
					
			$numsamplesever = getsamplesdonebyfacility($ID);
			
			if ($numsamplesever > 0) // do not allow delete link
			{	$deletesample = "";
				$withdata ='<font color="#FF0000">Y</font>';				
			}
			else if ($numsamplesever == 0)//  allow delete link
			{	$deletesample = "|  <a href='deletefacility.php?ID=$ID&fname=$name' title='Click to Delete Facility' OnClick=\"return confirm('Are you sure you want to delete Worksheet $name');\" >Delete   </a>";
				$withdata ='N';
			}
			//<a href=\"facilitydetails.php" ."?ID=$ID" . "\" title='Click to view Facility Details'>View </a>
			
			echo "<tr class='even'>
					<td ><strong>$facilitycode</strong></td>
					<td >$name</td> 
					<td ><small>$distname</small></td> 
					<td ><small>$provname</small></td>
					<td >$telephone</td>
					<td >$telephone2</td>
					<td ><a href='mailto:$email'>$email</a></td>
					<td >$contactperson</td>
					<td ><div align='center'>$printer</div></td>
					<td >$imei</td>
					<td >$pass</td>	
					<td ><div align='center'><small>$withdata</small></font></td>					
					<td ><small><a href='editfacility.php?ID=$ID' title='Click to view Facility Details'>Edit</a>  $deletesample</small>
			</td>
				</tr>";
		}echo "</table>";
	
	//	if ($displayprovincefacilitiescount != 0) 
//		{
//			$numrows = $displayprovincefacilitiescount;
//		}
//		else if ($displayfprovincefacilitiescount != 0)
//		{
//			$numrows = $displayfprovincefacilitiescount;
//		}
//		else if ($displaydistrictfacilitiescount != 0)
//		{
//			$numrows = $displaydistrictfacilitiescount;
//		}
//		else 
//		{
			$numrows=$totalresult;//GetTotalFacilities(); //get total no of batches
		//}
		
			// how many pages we have when using paging?
			$maxPage = ceil($numrows/$rowsPerPage);
		
		// print the link to access each page
		$self = $_SERVER['PHP_SELF'];
		$nav  = '';
		for($page = 1; $page <= $maxPage; $page++)
		{
		   if ($page == $pageNum)
		   {
			  $nav .= " $page "; // no need to create a link to current page
		   }
		   else
		   {
			  $nav .= " <a href=\"$self?page=$page\">$page</a> ";
		   }
		}
		
		// creating previous and next link
		// plus the link to go straight to
		// the first and last page
		
		if ($pageNum > 1)
		{
		   $page  = $pageNum - 1;
		   $prev  = " <a href=\"$self?view=1&page=$page\"> | Prev </a> ";
		
		   $first = " <a href=\"$self?view=1&page=1\"> First Page </a> ";
		}
		else
		{
		   $prev  = '&nbsp;'; // we're on page one, don't print previous link
		   $first = '&nbsp;'; // nor the first page link
		}
		
		if ($pageNum < $maxPage)
		{
		   $page = $pageNum + 1;
		   $next = " <a href=\"$self?view=1&page=$page\"> | Next |</a> ";
		
		   $last = " <a href=\"$self?view=1&page=$maxPage\">Last Page</a> ";
		}
		else
		{
		   $next = '&nbsp;'; // we're on the last page, don't print next link
		   $last = '&nbsp;'; // nor the last page link
		}
		
		// print the navigation link
		echo '<center><font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px">'. $first . $prev . $next . $last .'</font></center>';
		}
			
		else if (!empty($searchparameter))
		{
			$showfacility = "SELECT ID, facilitycode,LTRIM(RTRIM(name)),district,telephone,telephone2,email,contactperson,PostalAddress  FROM facilitys WHERE name LIKE '%$searchparameter%' AND flag = 1";
			
			$displayfacilities = @mysql_query($showfacility) or die(mysql_error());
			
			$showfacilitycount = mysql_num_rows($displayfacilities);
			
			echo "The search for <strong>$searchparameter</strong> returned <strong>$showfacilitycount</strong> results.<a href='facilitieslist.php'><strong>Click here to refresh page.</strong></a>";
			
			//display search
			echo "<table border=0 class='data-table'>
		<tr ><th>Facility Code</th><th>Facility Name</th><th>District</th><th>Province</th><th>Land Line</th><th>Mobile No</th><th>Address</th><th>Email Address</th><th>Contact Person</th><th>Task</th></tr>";
						
			//list the variables that you would like to get
			while(list($ID,$facilitycode,$name,$district,$telephone,$telephone2,$email,$contactperson,$PostalAddress ) = mysql_fetch_array($displayfacilities))
			{   //get select district name and province id	
				$distname=GetDistrictName($district);
				//get province ID
				$provid=GetProvid($district);
					//get province name	
				$provname=GetProvname($provid);
					//display the facility name
					//$facilityname = GetFacility($ID);
					//display the facility information as well as the facility name and type
					echo "<tr class='even'>
							<td >$facilitycode</td>
							<td >$name</td> 
							<td >$distname</td> 
							<td >$provname</td>
							<td >$telephone</td>
							<td >$telephone2</td>
							<td>$PostalAddress </td>
							<td ><a href='mailto:$email'>$email</a></td>
							<td >$contactperson</td>
							<td ><a href=\"facilitydetails.php" ."?ID=$ID" . "\" title='Click to view Facility Details'>View </a> | <a href=\"editfacility.php" ."?facilitycode=$facilitycode" . "\" title='Click to view Facility Details'>Edit</a> |  <a href=\"deletefacility.php" ."?ID=$ID&fname=$name" . "\" title='Click to Delete Facility' OnClick=\"return confirm('Are you sure you want to delete Worksheet $name');\" >Delete   </a>
					</td>
						</tr>";
			}echo "</table>";
			
			$numrows=$showfacilitycount; //get total no of batches
					
						// how many pages we have when using paging?
						$maxPage = ceil($numrows/$rowsPerPage);
					
					// print the link to access each page
					$self = $_SERVER['PHP_SELF'];
					$nav  = '';
					for($page = 1; $page <= $maxPage; $page++)
					{
					   if ($page == $pageNum)
					   {
						  $nav .= " $page "; // no need to create a link to current page
					   }
					   else
					   {
						  $nav .= " <a href=\"$self?page=$page\">$page</a> ";
					   }
					}
					
					// creating previous and next link
					// plus the link to go straight to
					// the first and last page
					
					if ($pageNum > 1)
					{
					   $page  = $pageNum - 1;
					   $prev  = " <a href=\"$self?page=$page\"> | Prev </a> ";
					
					   $first = " <a href=\"$self?page=1\"> First Page </a> ";
					}
					else
					{
					   $prev  = '&nbsp;'; // we're on page one, don't print previous link
					   $first = '&nbsp;'; // nor the first page link
					}
					
					if ($pageNum < $maxPage)
					{
					   $page = $pageNum + 1;
					   $next = " <a href=\"$self?page=$page\"> | Next |</a> ";
					
					   $last = " <a href=\"$self?page=$maxPage\">Last Page</a> ";
					}
					else
					{
					   $next = '&nbsp;'; // we're on the last page, don't print next link
					   $last = '&nbsp;'; // nor the last page link
					}
					
					// print the navigation link
					echo '<center>'. $first . $prev . $next . $last .'</center>';
			exit();
		
		}
}
else if ($facilitycount == 0)
{
	echo "</strong><center>There are no facility records.</center></strong>";
}
	?>

</div>

		
 <?php include('../includes/footer.php');?>