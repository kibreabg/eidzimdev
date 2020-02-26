<?php
session_start();
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment;Filename=Annual_Excel_Report.xls");
//readfile('Annual_Excel_Report.xls');
?>
<?php
include("nationaldashboardfunctions.php");
 	
	$province = $_GET['province'];
	$facility = $_GET['facility'];
	$yearly = $_GET['yearly'];
	$partner=$_SESSION['partner'];

 			
					if (($province==0) &&  ($facility==0) ) //all
					{
				
						$title= $yearly ;
						$showfacility = "SELECT samples.ID,samples.patientid,samples.patient,samples.facility,samples.datecollected,samples.datereceived,samples.receivedstatus,samples.spots,samples.datetested,samples.result,samples.datemodified,samples.datedispatched,samples.lab as 'labtestedin' FROM facilitys,samples WHERE facilitys.ID=samples.facility  AND YEAR(samples.datetested)='$yearly'  AND samples.repeatt=0 AND  samples.flag=1 order by facilitys.name ASC";
		
					}
					elseif (($province !=0) &&  ($facility==0) ) //provincial
					{
						$provname=GetProvname($province);
						$title= $provname . " Province ,  " . $yearly;
						$showfacility = "SELECT samples.ID,samples.patientid,samples.patient,samples.facility,samples.datecollected,samples.datereceived,samples.receivedstatus,samples.spots,samples.datetested,samples.result,samples.datemodified,samples.datedispatched,samples.lab as 'labtestedin' FROM facilitys,samples,districts WHERE facilitys.ID=samples.facility AND facilitys.district=districts.ID  AND districts.province='$province' AND YEAR(samples.datetested)='$yearly'  AND samples.repeatt=0 AND  samples.flag=1 order by facilitys.district ASC";
			
					}
					/*else if ($radio=="radio3") //district
					{
						$distname=GetDistrictName($district);
						$title= $distname . " District ,  " . $yearly;
						$showfacility = "SELECT samples.ID,samples.patient,samples.facility,samples.datecollected,samples.datereceived,samples.receivedstatus,samples.spots,samples.datetested,samples.result,samples.datemodified,samples.datedispatched,samples.lab as 'labtestedin' FROM facilitys,samples WHERE facilitys.ID=samples.facility AND facilitys.district='$district' AND YEAR(samples.datetested)='$yearly'  AND samples.repeatt=0 AND  samples.flag=1 order by facilitys.name ASC";
			
		
					}*/
					elseif (($province ==0) &&  ($facility !=0) )//facility

					{
						$facilityname=GetFacilityName($facility);
						$title= $facilityname . " ,  ". $yearly;
						
						$showfacility = "SELECT samples.ID,samples.patientid,samples.patient,samples.facility,samples.datecollected,samples.datereceived,samples.receivedstatus,samples.spots,samples.datetested,samples.result,samples.datemodified,samples.datedispatched,samples.lab as 'labtestedin' FROM facilitys,samples WHERE facilitys.ID=samples.facility AND samples.facility='$facility' AND  YEAR(samples.datetested)='$yearly'  AND samples.repeatt=0 AND  samples.flag=1 ";
		
					}
					
		
					$objQuery = @mysql_query($showfacility) or die(mysql_error());
						$reportitle= strtoupper($partnername) ." TEST OUTCOME REPORT  FOR ". strtoupper($title) ;
						
						
					if($objQuery)
		{			
				?>
			<table border="1"   class="data-table">
				
			<tr>
			<td colspan='19' align='center'>
			<b> <?php echo $reportitle; ?></b>
			</td>
			</tr>	
	<tr >
	  <th>Unique No </th> <th>Lab # </th><th>Accession # </th><th>Testing Lab</th>
	  <th>Province </th><th>District </th><th>Facility Name </th>
	  <th>Sex</th><th>Age</th><th>DOB</th>
	  <th>Date of Collection </th><th> HIV Status of Mother</th><th>PMTCT Intervention</th><th>Breast Feeding </th><th>Entry Point</th><th>Date of Receiving </th><th>Date of Testing</th><th>Date of Dispatch </th><th>Test Result</th></tr>

				<?php
				//***********//
			
				$intRows = 4;
			$count=0;	while(list($ID,$patientid,$patient,$facility,$datecollected,$datereceived,$receivedstatus,$spots,$datetested,$result,$datemodified,$datedispatched,$labtestedin) = mysql_fetch_array($objQuery))
				{
				
		$count=$count+1;
				
					
		
		
		//get all patient infor for use
		$pinfo = GetPatientInfo($patientid);
		extract($pinfo);
		//$dob=date("d-M-Y",strtotime($dob));
		if (($dob !="") && ($dob !="0000-00-00") && ($dob !="1970-01-01"))
		{
		$pdob=date("d-M-Y",strtotime($dob));
		}
		else
		{
		$pdob="";
		}
		
		if ($age <=0)
		{
		$pAge='';
		}
		else
		{
		$pAge=$age;
		}
	// echo $age;
	//get patient gender
		$pgender=$gender;
		//get sample facility name based on facility code
		$facilityname=GetFacilityName($facility);
		//get selected district ID
		$district=GetDistrictID($facility);	
		//get select district name and province id	
		$distname=GetDistrictName($district);
		//get province ID
		$provid=GetProvid($district);
			//get province name	
		$provname=GetProvname($provid);
		
		//lab of facility
		$lab= GetLab($labtestedin);
		//get sample recevied
		$srecstatus=GetReceivedStatus($receivedstatus);
		
		//get mother id from patient 
		$mother=GetMotherID($patientid);
		//mother hiv
		$mhiv=GetMotherHIVstatus($mother);
		//mother pmtct intervention
		$mprophylaxis=GetMotherProphylaxis($mother);
		//get mothers feeding type
		$mfeeding=GetMotherFeeding($mother);
		//get entry point
		$entry=GetEntryPoint($mother);
		$testresult=GetResultType($result);
				
				?>
				
				<tr class='even'>
					<td > <?php echo  $count ;?></td>
					<td > <?php echo  $ID ;?></td>
					<td > <?php echo  $patient ;?></td>
					<td > <?php echo  $lab ;?></td>
					<td > <?php echo  $provname ;?></td>
					<td > <?php echo  $distname ;?></td>
					<td > <?php echo  $facilityname ;?></td>
					<td > <?php echo  $pgender ;?></td>
					<td > <?php echo  $pAge ;?></td>
					<td > <?php echo  $pdob;?></td>
					<td > <?php echo  $datecollected ;?></td>
					<td > <?php echo  $mhiv ;?></td>
					<td > <?php echo   $mprophylaxis;?></td>
					<td > <?php echo   $mfeeding;?></td>
					<td > <?php echo   $entry ;?></td>
					<td > <?php echo   $datereceived ;?></td>
					<td > <?php echo   $datetested;?></td>
					<td > <?php echo $datedispatched;?></td>
					<td > <?php echo  $testresult ;?></td>
			
				
				</tr>
					
			
<?php
		}
		
		}

	
?>
	
	






