<?php
session_start();
 include('../includes/header.php');
?>
<?php 
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
$samplecode=$_GET['ID'];
$success=$_GET['success'];

if  ($samplecode != "")
{
	$samples = getSampleetails($samplecode);
	extract($samples);	

	//get sample facility name based on facility code
	$facilityname=GetFacility($facility);	
	if ($datecollected !="")
	{
	$datecollected=date("d-M-Y",strtotime($datecollected));
	}	
	if ($datereceived !="")
	{
	$datereceived=date("d-M-Y",strtotime($datereceived));
	}
	if ($datetested !="")
	{
	$datetested=date("d-M-Y",strtotime($datetested));
	}
	if ($datemodified !="")
	{
	$datemodified=date("d-M-Y",strtotime($datemodified));
	}
	if ($datedispatched !="")
	{
	$datedispatched=date("d-M-Y",strtotime($datedispatched));
	}
	if ($result  != 0)
	{
	$routcome = GetSampleResult($result);
	}
	
		$pgender=GetPatientGender($patient);
		//patietn age
		$pAge=GetPatientAge($patient);
		//patient dob
		$pdob=GetPatientDOB($patient);
		//infant prophylaxis
		$pprophylaxis=GetPatientProphylaxis($patient);
		//get sample sample test results
		$routcome = GetSampleResult($ID);
		//get sample recevied
		$srecstatus=GetReceivedStatus($receivedstatus);
		//get mother id from patient 
		$mother=GetMotherID($patient);	
		//mother hiv
		//$mhiv=GetMotherHIVstatus($mother);
		//mother pmtct intervention
		//$mprophylaxis=GetMotherProphylaxis($mother);
		//get mothers feeding type
		//$mfeeding=GetMotherFeeding($mother);
		//get entry point
		//$entry=GetEntryPoint($mother);
}	
		
if($_REQUEST['editsample'])
{
	//get mothers details from the add_sample page
	$patientid = $_GET['patientid']; //auto sample id	
	$batch= $_GET['batchno']; 
	$mid= $_GET['mid'];
	$mentpoint = $_GET['entrypoint'];
	//$mentpoint=GetIDfromtableandname($mentpoint,"entry_points");
	$mhivstatus = $_GET['status'];
	//$mhivstatus=GetIDfromtableandname($mhivstatus,"results");
	$mdrug = $_GET['arv'];
	//$mdrug=GetIDfromtableandname($mdrug,"prophylaxis");
	$pdrug = $_GET['ips'];
	//$pdrug=GetIDfromtableandname($pdrug,"prophylaxis");
	$mbfeeding = $_GET['feeding'];
	//$mbfeeding=GetIDfromtableandname($mbfeeding,"feedings");
	//get patient/child details from the add_sample page
	$patient = $_GET['patient'];
	$oldpatientid = $_GET['oldpatientid'];
	$pgender = $_GET['pgender'];
	$age =$_GET['age'];
	$agetype= $_GET['agetype'];
	$labcomment = ' ';//$_GET['labcomment'];	
	$srecstatus = $_GET['receivedstatus'];
	$mothername = $_GET['mothername'];
	$anc = $_GET['anc'];
	$onctx = $_GET['onctx'];
	$delivery = $_GET['delivery'];
	$testreason = $_GET['testreason'];
	$receivedstatus = $_GET['receivedstatus'];
	$infant = $_GET['infant'];
	
	
	//get sample details from the add_sample page
	$sdoccheck = $_GET['sdoccheck'];
	$sdreccheck = $_GET['sdreccheck'];
	$pdobcheck = $_GET['pdobcheck']; //get the patient date of birth check box
	$sdoc = isset($_REQUEST["sdoc"]) ? $_REQUEST["sdoc"] : "";
	$sdrec = isset($_REQUEST["sdrec"]) ? $_REQUEST["sdrec"] : "";
	$pdob = isset($_REQUEST["pdob"]) ? $_REQUEST["pdob"] : "";//get the patient date of birth
	$sspot = $_GET['sspot'];
	$srecstatus = $_GET['srecstatus'];
	$comments = $_GET['comments'];
	$facilitycheck= $_GET['facilitycheck'];
	$ddcheck= $_GET['ddcheck'];
	$dd = isset($_REQUEST["dd"]) ? $_REQUEST["dd"] : "";
	//facility
	$fcode=  $_GET['cat'];
	
	//get the age in months
	if (($pdob != "") && ($sdoc !=""))
	{
			$dob=date("d-m-Y",strtotime($pdob));
			$doc=date("d-m-Y",strtotime($sdoc));
			$agedays = round((strtotime($doc) - strtotime($dob)) / (60 * 60 * 24));
			$ageinmonths=round(($agedays/30),1);
	}
	else
	{
		$ageinmonths = 0;
	}
	//end get the age in months
	
	/*if  ($agetype==1) //in weeks
	{
	$ageinmonths=round(($age/4),2);
	}
	else if ($agetype==2) 
	{
	$ageinmonths=$age ;
	}	*/
	
	//update mothers record
	$motherrec = mysql_query("UPDATE mothers
              					SET entry_point = '$mentpoint' ,prophylaxis = '$mdrug' ,status = '$mhivstatus', feeding = '$mbfeeding', anc='$anc',name='$mothername',delivery='$delivery'
			  			   			WHERE (ID = '$mid')")or die(mysql_error());

	//update patient record
	$patientrec = mysql_query("UPDATE patients
              					SET ID = '$patient' ,gender = '$pgender',prophylaxis='$pdrug' ,age='$ageinmonths',onctx ='$onctx',name='$infant'
			  			  			WHERE (ID = '$oldpatientid')")or die(mysql_error());
	//update sample record
	$samplerec = mysql_query("UPDATE samples
								SET patient = '$patient' , spots = '$sspot' , receivedstatus = '$receivedstatus' , comments = '$comments',labcomment='$labcomment',test_reason='$testreason'
									WHERE (ID = '$patientid')")or die(mysql_error());
						   
						  
	//update sample date of collection if it has been chnaged
	if ($sdoccheck != '')
	 {
	 $docrec = mysql_query("UPDATE samples
							  SET datecollected= '$sdoc' 
									WHERE (ID = '$patientid')")or die(mysql_error());	
	 }
	 
	 //update patient dob if changed
	if ($pdobcheck != '')
	 {
	 $pdobrec = mysql_query("UPDATE patients
							  SET dob= '$pdob' 
									WHERE (AutoID = '$patientid')")or die(mysql_error());	
	 }
	 
	//update sample date of receiving if it has been chnaged
	if ($sdreccheck != '')
	 {
	 $drec = mysql_query("UPDATE samples
				  SET datereceived = '$sdrec' 
								 WHERE (ID = '$patientid')")or die(mysql_error());
	
	 }
 
   //update facility name if it has been chnaged
	if ($facilitycheck != '')
	 {
		 $docrec = mysql_query("UPDATE samples
					  SET facility= '$fcode' 
								   WHERE (ID = '$patientid')")or die(mysql_error());
								   
		 $docrec1 = mysql_query("UPDATE samples
					  SET facility= '$fcode' 
								   WHERE (batchno = '$batch')")or die(mysql_error());
								   
		 $docrec2 = mysql_query("UPDATE mothers
				  SET facility= '$fcode' 
							   WHERE (ID = '$mid')")or die(mysql_error());
	 }
	 
	//update sample date of receiving if it has been chnaged
	
	if ($ddcheck != '')
	 {
	 $drec = mysql_query("UPDATE samples
				  SET datedispatched = '$dd' 
								 WHERE (ID = '$patientid')")or die(mysql_error());
	
	 }
 
	//save the user activity
	$tasktime= date("h:i:s a");
	$leo=date("d-m-Y");
	$task=5;
	$activity = SaveUserActivity($userid,$leo,$task,$tasktime,$patientid);
	
	if ($motherrec && $patientrec && $samplerec  && $activity)
	{
		$success="Sample details successfully edited and saved ";
					 echo '<script type="text/javascript">' ;
					echo "window.location.href='edit_sample.php?ID=$patientid&success=$success'";
					echo '</script>';
	}
	else
	{
	$era='<center>'."Failed to update sample record, try again ".'</center>';
	
	}
}
?>

<style type="text/css">
select {
width: 250;}
</style>
	
<script language="javascript" src="calendar.js"></script>
<script>
window.dhx_globalImgPath="../img/";
</script>
<script type="text/javascript" src="../includes/validation.js"></script>
<script src="dhtmlxcombo_extra.js"></script>
<link rel="STYLESHEET" type="text/css" href="dhtmlxcombo.css">
<script src="dhtmlxcommon.js"></script>
<script src="dhtmlxcombo.js"></script>

<link rel="stylesheet" href="../includes/validation.css" type="text/css" media="screen" />

<link type="text/css" href="calendar.css" rel="stylesheet" />	
<link href="base/jquery-ui.css" rel="stylesheet" type="text/css"/>
 
<script src="jquery-ui.min.js"></script>
<link rel="stylesheet" href="demos.css">
<script>

<link type="text/css" href="calendar.css" rel="stylesheet" />	
		<SCRIPT language=JavaScript>
function reload(form)
{
var val=form.cat.options[form.cat.options.selectedIndex].value;
self.location='addsample.php?catt=' + val ;
}
</script>
<script language="JavaScript">
function submitPressed() {
document.worksheetform.SaveWorksheet.disabled = true;
//stuff goes here
document.worksheetform.submit();
}
</script> 
 <script language="javascript" type="text/javascript">
// Roshan's Ajax dropdown code with php
// This notice must stay intact for legal use
// Copyright reserved to Roshan Bhattarai - nepaliboy007@yahoo.com
// If you have any problem contact me at http://roshanbh.com.np
function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
    }
	
	function getRejectedreasons(srecstatus) {		
		
		var strURL="findRejectedReasons.php?rejid="+srecstatus;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('statediv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
	
</script>

<div  class="section">
<div class="section-title">EDIT SAMPLE <?php echo $patient; ?> DETAILS </div>
<div class="xtop">
<A HREF="javascript:history.back(-1)"><img src="../img/back.gif" alt="Go Back"/></A>
	<!--Messages -->
	<?php if ($success !="")
	{?> 		
		<table   >
			<tr>
				<td style="width:auto" ><div class="success"><?php echo  '<strong>'.' <font color="#666600">'.$success.'</strong>'.' </font>';?></div></td>
			</tr>
		</table><?php 
	} 
	if ($era !="")
		{
		?> 
		<table   >
			<tr>
				<td style="width:auto" ><div class="error"><?php echo  '<strong>'.' <font color="#666600">'.$era.'</strong>'.' </font>'; ?></div></td>
			</tr>
		</table><?php
	} ?>
   <!--End Messages -->
   
<form action="" method="get" name="adminform" >
<!--begin the sample form Table -->			
<table class="data-table">
	  <th colspan="4">FACILITY INFORMATION </th>
	  
	  <tr class="even">
		<td ><strong>Referring Clinic / Hospital Name</strong></td>
		<td colspan="3"><input name="facilitycheck" type="checkbox" id="facilitycheck"/> &nbsp;<?php echo $facilityname; ?>
			<select  style="width:260px"  id='cat' name="cat"></select>
		    <script>
				var combo = dhtmlXComboFromSelect("cat");
				combo.enableFilteringMode(true,"02_sql_connector.php",true);				
			</script>
			
			<input name="mid" type="hidden" value="<?php echo $mother;?>" />
			
			<input name="patientid" type="hidden" value="<?php echo $ID;?>" /><input name="batchno" type="hidden" value="<?php echo $batchno;?>" /></td>
      	</tr>
		 
		<tr class="even">
		  <td colspan="7">&nbsp;</td>
		</tr>
				
     <th colspan="4"><span class="style3">MOTHER INFORMATION </span></th>
		<?php
		 //get the mother details
		$motherinfo = GetMotherInfo($mother);
		extract($motherinfo);		
		?>
		<tr class='even'>	
			<td><strong>Name of Mother</strong></td>
			<td>
			  <input name="mothername" type="text" id="mothername" value="<?php echo $name; ?>"  style="width:174px" class="text"/>
			  <input name="oldpatientid" type="hidden" value="<?php echo $patient;?>" /> 
			 </td>
			<td><strong>Mother's ANC #</strong></td>
			<td><input name="anc" type="text" id="anc" value="<?php echo $anc; ?>"  style="width:174px" class="text"/>
			</td>
		</tr>	
		<tr class='even'>	
		<td><strong>HIV Status </strong></td>
		<td><?php
		
		$statustype = GetResultName($status);
		
		$motherstatus = "SELECT ID,Name FROM results where ID != '$status' and ID != '1' and ID != '3'";
				
		$msresult = mysql_query($motherstatus) or die('Error, query failed'); //onchange='submitForm();'
			
			   echo "<select name='status' id='status' style='width:188px';>\n";
			   echo " <option value='$status'> $statustype </option>";
				
				  while ($msrow = mysql_fetch_array($msresult))
				  {
						 $ID = $msrow['ID'];
						$name = $msrow['Name'];
					echo "<option value='$ID'> $name</option>\n";
				  }
			  echo "</select>\n";
					 
		?></td>
		<td><strong>Entry Point</strong></td>
		<td><?php
		//get the entry type name
			$entryp = "SELECT name as entr FROM entry_points where ID = '$entry_point'";
			$entrypresult = mysql_query($entryp) or die(mysql_error());
			$entryprow = mysql_fetch_array($entrypresult, MYSQL_ASSOC);
			$entrytype = $entryprow['entr'];
		
		//display the control				
			$enstatus = "SELECT ID,Name FROM entry_points where ID != '$entry_point'";
					
			$enresult = mysql_query($enstatus) or die('Error, query failed'); //onchange='submitForm();'
				
				   echo "<select name='entrypoint' id='entrypoint' style='width:188px';>\n";
				   echo " <option value='$entry_point'> $entrytype </option>";
					
					  while ($enrow = mysql_fetch_array($enresult))
					  {
							 $enID = $enrow['ID'];
							$enname = $enrow['Name'];
						echo "<option value='$enID'> $enname</option>\n";
					  }
				  echo "</select>\n";
		?></td>
		</tr>	
		<tr class="even">
		<td><strong>ARV Received</strong></td>
		<td><?php
		//get the arv name
			$arvn = "SELECT name as arvtp FROM prophylaxis where ID = '$prophylaxis' and ptype = '1'";
			$arvnresult = mysql_query($arvn) or die(mysql_error());
			$arvnrow = mysql_fetch_array($arvnresult, MYSQL_ASSOC);
			$arvtype = $arvnrow['arvtp'];
		
		//display the control				
			$arvstatus = "SELECT ID,Name FROM prophylaxis where ID  != '$prophylaxis' and ptype = '1'";
					
			$arvresult = mysql_query($arvstatus) or die('Error, query failed');
				
				   echo "<select name='arv' id='arv' style='width:188px';>\n";
				   echo " <option value='$prophylaxis'> $arvtype </option>";
					
					  while ($arvrow = mysql_fetch_array($arvresult))
					  {
							 $arvID = $arvrow['ID'];
							$arvname = $arvrow['Name'];
						echo "<option value='$arvID'> $arvname</option>\n";
					  }
				  echo "</select>\n";

		?></td>
		<td ><strong>Infant Feeding </strong></td>
        <td ><?php
		//get the infant name
			$fd = "SELECT description as bf FROM feedings where ID = '$feeding'";
			$fdresult = mysql_query($fd) or die(mysql_error());
			$fdrow = mysql_fetch_array($fdresult, MYSQL_ASSOC);
			$feedingtype = $fdrow['bf'];
		
		//display the control				
			$fdstatus = "SELECT ID,description FROM feedings where ID  != '$feeding'";
					
			$fdresult = mysql_query($fdstatus) or die('Error, query failed'); //onchange='submitForm();'
				
				   echo "<select name='feeding' id='feeding' style='width:188px';>\n";
				   echo " <option value='$feeding'> $feedingtype </option>";
					
					  while ($fdrow = mysql_fetch_array($fdresult))
					  {
							 $fdID = $fdrow['ID'];
							$fdname = $fdrow['description'];
						echo "<option value='$fdID'> $fdname</option>\n";
					  }
				  echo "</select>\n";

		?></td>
	</tr>
	
      <tr  class="even">
	  
        <td colspan="7">&nbsp;</td>
		    </tr>
                    <th colspan="7"><span class="style3">INFANT INFORMATION </span></th>
           <?php
		   	//get the patient details
			$patientinfo = GetPatientInfo($patient);
			extract($patientinfo);
			
			//$infantp = $patientinfo['prophylaxis'];
		   ?>
			<tr  class="even">
        
			</tr>
			
			<tr class="even">
        <td ><strong>Request No.</strong></td>
              <td ><input name="patient" type="text" id="pid" value="<?php echo $patient; ?>"  style="width:174px" class="text"/>
                    <input name="oldpatientid" type="hidden" value="<?php echo $patient;?>" /></td>
			<td>
			  <strong>Infant's Name</strong></td>
			<td><input name="infant" type="text" id="infant" value="<?php echo $name; ?>"  style="width:174px" class="text"/></td>
			</tr>
			<tr class="even">
			<td><strong>Date of Birth</strong></td>
			<td><input name="pdobcheck" type="checkbox" id="pdobcheck"/>
				<?php echo $dob = date(("d-M-Y"),strtotime($dob));
				echo '&nbsp;';
				
				$maxyr = date('Y');
				
				  $myCalendar = new tc_calendar("pdob", true, false);
				  $myCalendar->setIcon("../img/iconCalendar.gif");
				  $myCalendar->setDate(date('d'), date('m'), date('Y'));
				  $myCalendar->setPath("./");
				  $myCalendar->setYearInterval(1987, $maxyr);
				  $myCalendar->dateAllow('2008-05-13', '2015-03-01');
				  $myCalendar->setDateFormat('j F Y');
				  //$myCalendar->setHeight(350);	  
				  //$myCalendar->autoSubmit(true, "form1");
				  $myCalendar->writeScript();
	  			?>
			
			</td>
			
      		<td ><strong>Gender</strong></td>
                    <td >
					  <input name="pgender" type="radio" value="M" <?php  if (isset($gender) && $gender == 'M' ) { echo 'checked="checked"';}?> />
					  Male&nbsp;
					  <input name="pgender" type="radio" value="F" <?php  if (isset($gender) && $gender == 'F' ) { echo 'checked="checked"';}?>  />
					  Female 
			  		</td>
        </tr>			
			
		<tr class="even">
        		<td ><strong>Infant Prophylaxis </strong></td>
                    <td><?php
				//get the arv name
					$ip = "SELECT name as itp FROM prophylaxis where ID = '$prophylaxis' and ptype = '2'";
					$iresult = mysql_query($ip) or die(mysql_error());
					$irow = mysql_fetch_array($iresult, MYSQL_ASSOC);
					$itype = $irow['itp'];
				
				//display the control				
					$istatus = "SELECT ID,Name FROM prophylaxis where ID  != '$prophylaxis' and ptype = '2'";
							
					$ipresult = mysql_query($istatus) or die('Error, query failed');
						
						   echo "<select name='ips' id='ips' style='width:188px';>\n";
						   echo " <option value='$prophylaxis'> $itype </option>";
							
							  while ($iprow = mysql_fetch_array($ipresult))
							  {
									 $ipID = $iprow['ID'];
									$ipname = $iprow['Name'];
								echo "<option value='$ipID'> $ipname</option>\n";
							  }
						  echo "</select>\n";

				?></td>
				<td><strong>Infant Already on CTX Prophylaxis?</strong></td>
				<td>
					  <input name="onctx" type="radio" value="Y" <?php  if (isset($onctx) && $onctx == 'Y' ) { echo 'checked="checked"';}?> />
					  Yes&nbsp;
					  <input name="onctx" type="radio" value="N" <?php  if (isset($onctx) && $onctx == 'N' ) { echo 'checked="checked"';}?>/>
					  No	&nbsp;
					  <input name="onctx" type="radio" value="U" <?php  if (isset($onctx) && $onctx == 'U' ) { echo 'checked="checked"';}?> />
					  Unknown	  
				</td>
	  </tr>
			
			<tr class="even">
				<td><strong>Mode of Delivery</strong></td>
				<td colspan="7">
					<?php
					$deliveryname = GetDelivery($delivery);
					   $deliveryquery = "SELECT ID,name FROM deliverymode where id != $delivery";
							
							$dresult = mysql_query($deliveryquery) or die('Error, query failed'); //onchange='submitForm();'
						
						   echo "<select name='delivery' id='delivery' style='width:188px';>\n";
						   echo " <option value='$delivery'> $deliveryname </option>";
							
							  while ($drow = mysql_fetch_array($dresult))
							  {
									 $ID = $drow['ID'];
									$name = $drow['name'];
								echo "<option value='$ID'> $name</option>\n";
							  }
						  echo "</select>\n";
					  ?>
				</td>
			</tr>
			
			 <tr class="even">
			 <td colspan="7">&nbsp;</td>
			 </tr>
		  
                      <th colspan="7"><span class="style3">INFANT TESTING (<em>Check Child Health Card</em>) </span></th>
					
			
           <tr class="even">
        <td ><strong>Date of taking DBS</strong></td>
                    <td >
					
					<input name="sdoccheck" type="checkbox" id="sdoccheck"/>
					<?php echo $datecollected; ?>
                    <input name="bankname" type="hidden" id="othernames" value="<?php echo $datecollected; ?>" size="10"/><?php
		  $myCalendar = new tc_calendar("sdoc", true, false);
		  $myCalendar->setIcon("../img/iconCalendar.gif");
		  $myCalendar->setDate(date('d'), date('m'), date('Y'));
		  $myCalendar->setPath("./");
		  $myCalendar->setYearInterval(2000, 2015);
		  $myCalendar->dateAllow('2008-05-13', '2015-03-01');
		  $myCalendar->setDateFormat('j F Y');
		  //$myCalendar->setHeight(350);	  
		  //$myCalendar->autoSubmit(true, "form1");
		  $myCalendar->writeScript();
		  ?></td>
		 
        <td ><strong>Date Received  </strong></td>
        <td ><input name="sdreccheck" type="checkbox" id="sdreccheck"/>
		<?php echo $datereceived; ?>
		<input name="bankname" type="hidden" id="othernames" value="<?php echo $datereceived; ?>"/><?php
			  $myCalendar = new tc_calendar("sdrec", true, false);
			  $myCalendar->setIcon("../img/iconCalendar.gif");
			  $myCalendar->setDate(date('d'), date('m'), date('Y'));
			  $myCalendar->setPath("./");
			  $myCalendar->setYearInterval(2000, 2015);
			  $myCalendar->dateAllow('2008-05-13', '2015-03-01');
			  $myCalendar->setDateFormat('j F Y');
			  //$myCalendar->setHeight(350);	  
			  //$myCalendar->autoSubmit(true, "form1");
			  $myCalendar->writeScript();
			  ?></td>
		    </tr>
			<tr class="even">
			<td ><strong>Reason for DNA/PCR Test </strong></td>
			<td colspan=""><?php
				//get the test name
				$terp = "SELECT Name as ter FROM testreason where ID = '$test_reason'";
				$terresult = mysql_query($terp) or die(mysql_error());
				$terprow = mysql_fetch_array($terresult, MYSQL_ASSOC);
				$tertype = $terprow['ter'];
					
	  		 	$ttestquery = "SELECT ID,Name FROM testreason where ID != '$test_reason'";
			
				$ttestresult = mysql_query($ttestquery) or die('Error, query failed'); //onchange='submitForm();'
	
	   			echo "<select name='testreason' id='testreason' style='width:188px';>\n";
	    		echo " <option value='$test_reason'> $tertype </option>";
				  while ($ttestrow = mysql_fetch_array($ttestresult))
				  {
						 $tID = $ttestrow['ID'];
						$tname = $ttestrow['Name'];
					echo "<option value='$tID'> $tname</option>\n";
				  }
		  echo "</select>\n";
		  ?></td>
			
        <td ><strong>Received Status </strong></td>
        <td ><?php
		
				//get the test name
				$rsp = "SELECT Name as rame FROM receivedstatus where ID = '$receivedstatus'";
				$rsresult = mysql_query($rsp) or die('Error, query failed');
				$rsrow = mysql_fetch_array($rsresult, MYSQL_ASSOC);
				$rstype = $rsrow['rame'];
				
	  		 	$rsquery = "SELECT ID,Name FROM receivedstatus where ID!='$receivedstatus'";
			
				$rssresult = mysql_query($rsquery) or die('Error, query failed'); //onchange='submitForm();'
	
	   			echo "<select name='receivedstatus' id='receivedstatus' style='width:188px' ;>\n";
	    		echo " <option value='$receivedstatus'> $rstype </option>";
				  while ($rsrow = mysql_fetch_array($rssresult))
				  {
						 $rsID = $rsrow['ID'];
						$rsname = $rsrow['Name'];
					echo "<option value='$rsID'> $rsname</option>\n";
				  }
      		echo "</select>\n";
	  ?></td>
		  
        
			</tr>
			
			
      <tr >
        <td colspan="7"  > <center>
          <input name="editsample" type="submit" value="Save Changes" style="border-style:ridge" class="button"/> &nbsp;
          <input name="reset" type="submit" value="Reset" style="border-style:ridge" class="button" />
          </center>				</td>
            </tr>     
      </table>  
          </form>

		    
		
		
	
		
 <?php include('../includes/footer.php');?>