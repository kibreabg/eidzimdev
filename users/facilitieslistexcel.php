<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment;Filename=ExcelFile.xls");

require_once('../connection/config.php');
require_once('../includes/functions.php');

//get the search variable
$searchparameter = $_GET['search'];
$provid = $_GET['provid'];
$fcode = $_GET['fcode'];
$fname = $_GET['fname'];
$district = $_GET['district'];

$currentdate = date('d-M-Y');
	
		$query = " SELECT f.ID,f.facilitycode,f.name,f.district,f.ftype,f.telephone,f.telephone2,f.ContactEmail,f.contactperson, f.imei, f.pass FROM facilitys f, districts d WHERE 1 = Case When '$provid' = '' Then 1  When '$provid' = d.province Then 1  END and 1 = Case When '$fcode' = '' Then 1  When '$fcode' = f.ID Then 1  END and 1 = Case When '$district' = '' Then 1  When '$district' = f.district Then 1  END and f.district = d.ID AND f.flag = 1 ORDER BY f.name ASC ";

$displayfacilities = @mysql_query($query) or die(mysql_error());
			
		
if (empty($searchparameter))
{		

		



		if ($provid != '')
		{				
			
		$provname=GetProvname($provid);
		}
		else if (($fcode != '') && ($fname != ''))
		{
		}
		else if ($district != '')
		{
			$distname=GetDistrictName($district);
		}
		else
		{
		}
		
		//display normal view
		echo "<table border=0>
		<tr><td colspan='12'><center><h2>FACILITIES LIST <small>as at $currentdate</small></h2></center></td></tr>
		<tr><td colspan='12'>&nbsp;</td></tr>
		<tr ><th>Facility Code</th><th>Facility Name</th><th>District</th><th>Province</th><th>Land Line</th><th>Mobile No</th><th>Address</th><th>Email Address</th><th>Contact Person</th><th>SMS PRINTER</th><th>IMEI</th><th>PASSWORD</th></tr>";			
					
		//list the variables that you would like to get
		while(list($ID,$facilitycode,$name,$district,$ftype,$telephone,$telephone2,$email,$contactperson,$PostalAddress,$imei,$pass ) = mysql_fetch_array($displayfacilities))
			{   
				$distname=GetDistrictName($district);
				$provid=GetProvid($district);
				$provname=GetProvname($provid);
				
				if ($imei == 0)
				{	$printer = 'N';
				}
				else if ($imei != 0)
				{	$printer = 'Y';
				}
				
				echo "<tr class='even'>
						<td >$facilitycode</td>
						<td >$name</td> 
						<td >$distname</td> 
						<td >$provname</td>
						<td >$telephone</td>
						<td >$telephone2</td>
						<td >$PostalAddress </td>
						<td ><a href='mailto:$email'>$email</a></td>
						<td >$contactperson</td>
						<td >$printer</td>
						<td >$imei</td>
						<td >$pass</td>
					</tr>";
			}echo "</table>";
		
		
}
	
else if (!empty($searchparameter))
{


}

	?>
