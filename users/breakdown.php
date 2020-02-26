<?php
require_once('../connection/config.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Untitled</title>
</head>

<body>

<table border="1"   class="data-table">
	<tr ><th>Facility </th><th>Province</th><th>District</th><th>Lab</th><th>Jan</th><th>Feb</th><th>Mar</th><th>April</th><th>May</th><th>Jun</th><th>Jul</th><th>Aug</th><th>Sept</th><th>Oct</th><th>Nov</th><th>Dec</th><th>Total</th><th>Positive</th><th>% Positive</th><th>Negative</th><th>% Negative</th></tr>
			
	<!--*********************************************************** -->
	<?php
		
			
		$showfacility = "SELECT facilitys.facilitycode,facilitys.ID,facilitys.lab,provinces.name as 'province',districts.name as 'district' FROM facilitys,districts,provinces WHERE facilitys.flag = 1 AND facilitys.district=districts.ID AND districts.province=provinces.ID ";
		
		$displayfacilities = @mysql_query($showfacility) or die(mysql_error());
		
		//list the variables that you would like to get
		while(list($facilitycode,$ID,$lab,$province,$district) = mysql_fetch_array($displayfacilities))
		{  $year=2010;
			
			//display the facility name
			$facilitytype = GetFacility($ID);
			$labname=GetLab($lab);
			$totalyear=Gettestedperfacilityperyear($ID,$year);
			$pos=Gettestedperfacilityperyearperresult($ID,$year,2);
			$neg=Gettestedperfacilityperyearperresult($ID,$year,1);
			if ($pos > 0)
			{
			$pospercentage=round((($pos/$totalyear)*100),2);
			}
			else
			{
			$pospercentage=0;
			}
			if ($neg > 0)
			{
			$negpercentage=round((($neg/$totalyear)*100),2);
			}
			else
			{
			$negpercentage=0;
			}
			$jan=Gettestedperfacilitypermonth($ID,$year,1);
			$feb=Gettestedperfacilitypermonth($ID,$year,2);
			$mar=Gettestedperfacilitypermonth($ID,$year,3);
			$apr=Gettestedperfacilitypermonth($ID,$year,4);
			$may=Gettestedperfacilitypermonth($ID,$year,5);
			$jun=Gettestedperfacilitypermonth($ID,$year,6);
			$jul=Gettestedperfacilitypermonth($ID,$year,7);
			$aug=Gettestedperfacilitypermonth($ID,$year,8);
			$sep=Gettestedperfacilitypermonth($ID,$year,9);
			$oct=Gettestedperfacilitypermonth($ID,$year,10);
			$nov=Gettestedperfacilitypermonth($ID,$year,11);
			$dec=Gettestedperfacilitypermonth($ID,$year,12);

			//display the facility information as well as the facility name and type
			echo "<tr class='even'>
					<td >$facilitytype</td> 
					<td >$province</td>
					<td >$district</td>
					<td >$labname</td>
					<td >$jan</td>
					<td >$feb</td>
					<td >$mar</td>
					<td >$apr</td>
					<td >$may</td>
					<td >$jun</td>
					<td >$jul</td>
					<td >$aug</td>
					<td >$sep</td>
					<td >$oct</td>
					<td >$nov</td>
					<td >$dec</td>
					<td >$totalyear</td>
					<td >$pos</td>
					<td >$pospercentage %</td>
					<td >$neg</td>
					<td >$negpercentage %</td>
				</tr>";
		}
	?>
		</table>

</body>
</html>
