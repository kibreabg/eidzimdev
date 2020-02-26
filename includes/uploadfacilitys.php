<?php
	include("../connection/config.php");
	//include("../authenticate.php");

//'./spreadsheet.xls
 $rec=0;
$handle = fopen ('FinalFacilityList.csv', 'r');
		$count=0;
		while (($data = fgetcsv($handle, 1000, ',', '"')) !== FALSE)
		{
			$rec++;
			if($rec==1)
			{
			continue;
			}
			else
			{

 

		
		$query = "INSERT INTO facilitys(facilitycode,name,district,lab,districtname) VALUES ('$data[0]','$data[1]','$data[2]','$data[3]','$data[4]')";
			$import = mysql_query($query) or die(mysql_error());	
					  
				$count=$count+1;		

			
			
			}
		}
		
		if ($import)
		{
		echo $count. " facilitys Successfully updated ";
		}
		

?>