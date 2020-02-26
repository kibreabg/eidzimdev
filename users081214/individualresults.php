<?php
session_start();
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
include("../includes/functions.php");
$labcode=$_GET['ID'];
$userlab=$_SESSION['lab'];
$labname=GetLabNames($userlab);//get lab name	
 $query = "SELECT ID,batchno,patient,facility,datecollected,datereceived,receivedstatus,datetested,datemodified,result,datedispatched,DispatchComments,labcomment,test_reason
   			 FROM samples
			where samples.ID='$labcode' and samples.lab='$userlab' AND  samples.BatchComplete=1 and samples.Flag=1";
$resultt = mysql_query($query) or die(mysql_error());	
$ss=mysql_fetch_array($resultt);
$patient=$ss['patient'];
$facility=$ss['facility'];
$datereceived=$ss['datereceived'];
$receivedstatus=$ss['receivedstatus'];
$datecollected=$ss['datecollected'];
$datetested=$ss['datetested'];
$datemodified=$ss['datemodified'];
$result=$ss['result'];
$datedispatched=$ss['datedispatched'];
$labcomment =$ss['labcomment'];
$DispatchComments=$ss['DispatchComments'];
$test_reason=$ss['test_reason'];
		if ($datecollected !="")
		{
			$date_collected =date("d-M-Y",strtotime($datecollected));
		}
		$date_received =date("d-M-Y",strtotime($datereceived));
		$date_datetested =date("d-M-Y",strtotime($datetested));
		$date_result_updated =date("d-M-Y",strtotime($datemodified));
		$date_dispatched =date("d-M-Y",strtotime($datedispatched));
	

		$facilityname=GetFacility($facility);
		//resut in words
		$routcome=GetResultType($result);
		
		$facilitydetails= getFacilityDetails($facility);
		extract($facilitydetails);
		//get selected district ID
		$distid=GetDistrictID($facility);	
		//get select district name and province id	
		$distname=GetDistrictName($distid);
			//get province ID
		$provid=GetProvid($distid);
			//get province name	
		$provname=GetProvname($provid);	
		$provinceID=GetProvinceActualID($provid);	
		//get patient gender
		$pgender=GetPatientGender($patient);
		//patietn age
		$pAge=GetPatientAge($patient);
		//patient dob
		$pdob=GetPatientDOB($patient);
		//infant prophylaxis
		$pprophylaxis=GetPatientProphylaxis($patient);

		//get sample recevied
		$srecstatus=GetReceivedStatus($receivedstatus);
		//get mother id from patient 
		$mother=GetMotherID($patient);
		//mother hiv
		$mhiv=GetMotherHIVstatus($mother);
		//mother pmtct intervention
		$mprophylaxis=GetMotherProphylaxis($mother);
		//get mothers feeding type
		$mfeeding=GetMotherFeeding($mother);
		//get entry point
		$entry=GetEntryPoint($mother);
		
		$motherdetails= GetMotherInfo($mother);
		extract($motherdetails);
		$mothername=$name;
		$mdelivery=GetMotherDelivery($mother);
		
		
		$patientdetails=GetPatientInfo($patient);
		extract($patientdetails);
		$patientname=$name;
		
		$reasonfortest=GetTestReason($test_reason);
	
		
?>
<html>
<link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
<style type="text/css">
<!--
.style1 {font-family: "Courier New", Courier, monospace}
.style4 {font-size: 12}
.style5 {font-family: "Courier New", Courier, monospace; font-size: 12; }
-->

	
</style>
<STYLE TYPE="text/css">
     P.breakhere {page-break-before: always}
.style6 {
	font-size: medium;
	font-weight: bold;
}
.style11 {font-size: 10px}

.style15 {font-size: smaller}
</STYLE> 
<style type="text/css">
BODY {margin-top: -10px}
.style17 {font-size: xx-small}
.style18 {font-size: small}
</style>
<body >
<?php
$ob_file = fopen('summary2.html','w');

ob_start('ob_file_callback');

function ob_file_callback($buffer)
{
  global $ob_file;
  fwrite($ob_file,$buffer);
  return $buffer;
}

?>
   <table class="data-table" align="center">
  
		  
          <tr style='background:#F6F6F6;'>
            <td > Referring Clinic / Hospital Name </td>
            <td ><?php  echo $facilityname;?></td>
			<td > Clinic / Hospital Code </td>
            <td ><?php  echo $facilitycode;?></td>
				
          </tr>
		   <tr style='background:#F6F6F6;'>
            <td> Province</td>
            <td ><?php echo $provname;
				?></td>
			<td> Province Code</td>
            <td ><?php echo $provinceID;
				?></td>
				
          </tr>
		   <tr style='background:#F6F6F6;'>
            <td >District</td>
            <td ><?php  echo $distname;
				?></td>
				  <td >District Code</td>
            <td ><?php  echo $distid;
				?></td>
				
          </tr>
         
     	  <tr >
          
            <td colspan="4">  &nbsp; </td>
				
          </tr>
		   <tr style='background:#F6F6F6;'>
            <td class="subsection-title" colspan="4" align="center"> <strong>Mother Information  </strong></td>
          </tr>
		  <tr style='background:#F6F6F6;' >
		  <td>Name of Mother</td>
		  <td><?php 
				echo $mothername;?></td>
		  <td>Mother's ANC #</td>
		  <td><?php 
				echo $anc;?></td>
		  </tr>
          <tr style='background:#F6F6F6;'>
		  	 <td> HIV Status </td>
            <td><?php echo $mhiv;
	  
	  ?>   <span id="mhivstatusInfo"></span></div>      </td>
            
	  <td> Entry Point</td>
            <td><?php echo $entry;
	   
	  ?></td>
          </tr>
		  <tr style='background:#F6F6F6;'>	
		  <td>Mother ARV</td>
		<td>
		<?php
			echo $mprophylaxis;
		?></td>	  	  
	  <td> Infant Feeding</td>
	      <td><?php
		 echo $mfeeding;
	  	  ?></td>
		  </tr>        
	  <tr >
          
            <td colspan="4">  &nbsp; </td>
				
          </tr>	  
		   <tr style='background:#F6F6F6;'>
            <td class="subsection-title" colspan="4" align="center"> <strong>Infant Information </strong> </td>
          </tr>
          <tr style='background:#F6F6F6;'>
		  	
            <td> Request No</td>
            <td>
			<strong>Year</strong>&nbsp;<?php 
			
			$year=substr($patient, 0, 4);  // bcd
			echo $year;
				?>&nbsp;
			<strong>No</strong>&nbsp;<?php 
			$no=substr($patient, 4);  // bcd
			
			echo $no;
			//echo $patient
				?>		</td>
         	<td>Infant's Name </td>
		  	<td><?php 
				echo $patientname; ?></td>
          </tr>
          <tr style='background:#F6F6F6;'>
		  	<td> Date of Birth </td>
			<td>  <?php 
			echo $pdob;
			
				?></td>
			 <td> Sex of baby </td>
             <td colspan=""><?php
			 
			 echo  $pgender;
				?>
             </td>
          </tr>
		  <tr style='background:#F6F6F6;'>
		  
			  <td> Infant Prophylaxis </td>
            <td colspan=""><?php
			 echo $pprophylaxis;
	   	  ?></td>
	  <td>On CTX prophylaxis? </td>
	  <td>
	 <?php 
			echo $onctx;	?>	  </td>
	  </tr>
	  <tr style='background:#F6F6F6;'>
	   <td> Mode of Delivery </td>
            <td colspan="3"><?php
echo $mdelivery;
		  ?></td>
	  </tr>
	    <tr >
          
            <td colspan="4">  &nbsp; </td>
				
          </tr>
	  <tr style='background:#F6F6F6;'>
            <td class="subsection-title" colspan="4" align="center"> <strong>Infant Testing  </strong></td>
          </tr>
	  <tr style='background:#F6F6F6;'>
	  <td> Infant Tested  before?</td>
	  <td>
	 <?php 
				echo $testedbefore; ?>  </td>
	  <td></td>
	  <td>
	  <div><?php
	  
	  ?> </div>	  </td>
		  </tr>
		  <tr style='background:#F6F6F6;'>
		 <?php  if ($testedbefore=='Y')
		 {
		 ?>
		   <td>Test Type </td>
	       <td><?php 
				echo $testtype;?>
	        </td>
			<?php
			}
			?>
	  <?php
	  if ($testtype=='DNA PCR')
	  {?>
	   <td> Original Lab Request No </td>
	   <td><strong>Year</strong>&nbsp;
	    <?php 
		echo $requestno_year;
				?>
	     &nbsp; <strong>No</strong>&nbsp;
	   <?php 
	   echo $requestno_no;
				?></td>
				<?php 
				}
				?>
		  </tr>
		   <tr >
          
            <td colspan="4">  &nbsp; </td>
				
          </tr>
          <tr style='background:#F6F6F6;'>
            <td class="subsection-title" colspan="4" align="center"> <strong>Sample Information </strong></td>
          </tr>
          <tr style='background:#F6F6F6;'>
            <td> Date of taking DBS </td>
            <td><?php 
				echo $date_collected;
				?>	 </td>
				 <td> Reason for DNA/PCR Test </td>
			 <td ><?php
	  		 	  	echo $reasonfortest;
	  ?></td>
          </tr>
		  <tr >
          
            <td colspan="4">  &nbsp; </td>
				
          </tr>
		  
		    <tr style='background:#F6F6F6;'>
            <td class="subsection-title" colspan="4" align="center"> <strong>Laboratory Report </strong></td>
          </tr>
          <tr style='background:#F6F6F6;'>
            <td> Date DBS Received </td>
            <td><?php 
			echo $date_received;
				?>	 </td>
			 <td> DNA PCR Result  </td>
            <td><strong><?php 
			echo $routcome;
				?></strong>
	  		<!--end calendar-->	  		</td>
          </tr>
		   <tr style='background:#F6F6F6;'>
                   
			 <td> Date of Result </td>
             <td ><?php echo $date_datetested;
	  		 	  	
	  ?></td>
	  <td> Lab Ref # </td>
             <td ><?php echo $ID
	  		 	  	
	  ?></td>
          </tr>
		   <tr style='background:#F6F6F6;'>
                    
            
			 <td >Date Dispatched </td>
             <td colspan="3"><?php
	  		 	  	echo $date_dispatched;
	  ?></td>

          </tr>
		  <tr style='background:#F6F6F6;'>
                    
            
			 <td colspan="1"> Comments </td>
             <td colspan="3"><?php
	  		 	  	echo $labcomment . '<br>'. $DispatchComments;
	  ?></td>

          </tr>
          
	</table>
	<?php 
$d= ob_end_flush();
 
 if ($d)
 {
 	echo '<script type="text/javascript">';
    echo "window.location.href='Results_pdf.php'";
    echo '</script>';;
 }
 ?>
</body>
</html>