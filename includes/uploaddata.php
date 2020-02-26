<?php
	include("../connection/config.php");
	//include("../authenticate.php");

//'./spreadsheet.xls
 $rec=0;
$handle = fopen ('B4.csv', 'r');

		while (($data = fgetcsv($handle, 1000, ',', '"')) !== FALSE)
		{
			$rec++;
			if($rec==1)
			{
			continue;
			}
			else
			{

 
/*$import = mysql_query("UPDATE facilitys
              SET lab = 1
			  			   WHERE (facilitycode = '$data[0]')");*/
		
		/*$query = "INSERT INTO mothers(fcode,facility,entry_point,feeding,prophylaxis,status) VALUES ('$data[0]','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]')";
			$import = mysql_query($query) or die(mysql_error());	*/
					  
						/*$query = "INSERT INTO patients(ID,mother,age,dob,gender,prophylaxis) VALUES ('$data[7]','$data[8]','$data[9]','$data[10]','$data[11]','$data[12]')";
			$import = mysql_query($query) or die(mysql_error());
			*/
			/*$datecollected=date("Y-m-d",strtotime($data[20]));
			$datereceived=date("Y-m-d",strtotime($data[21]));*/
			$query = "INSERT INTO samples(batchno,patient,facility,fcode,receivedstatus,spots,datecollected,datereceived,labcomment,parentid,datetested,result,datemodified,datedispatched,batchcomplete) VALUES ('$data[14]','$data[15]','$data[16]','$data[17]','$data[18]','$data[19]','$data[20]','$data[21]','$data[22]','$data[23]','$data[24]','$data[25]','$data[26]','$data[27]','$data[28]')";
			$import= mysql_query($query) or die(mysql_error());

			/*$import = mysql_query("UPDATE samples
              SET datecollected = '$data[1] ',datereceived = '$data[2]'
			  			   WHERE (ID = '$data[0]')");*/
			
			}
		}
		
		if ($import)
		{
		echo" alupe OCT SAMPLES Successfully updated ";
		}
		

?>