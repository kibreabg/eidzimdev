<?php
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
include('../includes/header.php');

//$labview = $_GET['labview'];
//
//if ($labview == 1)//nmrl
//{
//	$labss = 1;
//}
//else if ($labview == 2)//zvitambo
//{
//	$labss = 2;
//}
//else //any other lab
//{
//	$labss = 0;
//}

$deletesuccess = $_GET['p'];

$accttype = $_SESSION['accounttype'];

//delete the sample from pending tasks
if ($deletesuccess != '') {
    $batchno = $_GET['batchno'];
    $samplerec2 = mysql_query("DELETE from pendingtasks
                     WHERE (sample = '$ID' AND task=1 AND batchno='$batchno')");
}

$batchno = $_GET['ID'];

//get patient/sample code
$patient = GetPatientv1($batchno);
//get bach received date
$sdrec = GetDatereceivedv1($batchno);
//get patient gender and mother id based on sample code of sample
$mid = GetMotherID($patient);
//get patient gender
//get sample facility code based  on mothers id
$facility = GetFacilityCodev1($batchno);
//get sample facility name based on facility code
$facilityname = GetFacility($facility);
//get district and province
//get selected district ID
$distid = GetDistrictID($facility);
//get select district name and province id	
$distname = GetDistrictName($distid);
//get province ID
$provid = GetProvid($distid);
//get province name	
$provname = GetProvname($provid);
?>

<style type="text/css">
    select {
        width: 250;}
    </style>	<script language="javascript" src="calendar.js"></script>

    <link type="text/css" href="calendar.css" rel="stylesheet" />	
    <SCRIPT language=JavaScript>
        function reload(form)
        {
            var val=form.cat.options[form.cat.options.selectedIndex].value;
            self.location='addsample.php?catt=' + val ;
        }
    </script>

    <div  class="section">
    <div class="section-title">BATCH NO. <?php echo $batchno; ?> </div>
    <div class="xtop">

        <table>
            <tr>
                <td>
                    <A HREF='javascript:history.back(-1)'><img src='../img/back.gif' alt='Go Back'/></A>
                </td>
            </tr>
        </table>

<?php if ($deletesuccess != "") {
    ?> 
            <table>
                <tr>
                    <td style="width:auto" ><div class="success"><?php echo '<strong>' . ' <font color="#666600">' . $deletesuccess . '</strong>' . ' </font>'; ?></div></td>
                </tr>
            </table><?php }
?>	



<?php
$batchcompleted = isbatchcompletev1($batchno);
if ($batchcompleted == 0) { //all samples in batch complete

    //query database for all districts
    $qury = "SELECT ID,patientid,patient,datereceived,spots,datecollected,receivedstatus,approved
            FROM samples
			WHERE batchno='$batchno' AND Flag=1 and repeatt=0 and result > 0  ORDER BY parentid ASC";

    $result = mysql_query($qury) or die(mysql_error());
    $no = mysql_num_rows($result);

    if ($no != 0) {

        echo "
	<table class='data-table'>
	<tr class='even'>
		<th width='600'>
			Referring Clinic / Hospital Name :  $facilityname | Province :  $provname | District :  $distname
		</th>
		
		<th width='330' >
			Date Received: $sdrec 
		</th>
	</tr>
	</table>";
        /* <th width='30' >
          <form action='batchreport.php' target ='_blank'  method='get' name='download batch'>
          <input name='ID' type='hidden' value='$batchno'>
          <input type='image' img src='../img/print.png'>
          </form>
          </th> */

        // print the districts info in table
        echo '<table border="0" class="data-table">
	<tr ><th colspan="16">Sample Log</th></tr>
	<tr><th colspan="7">Patient Information</th><th colspan="2">Sample Information</th><th colspan="4">Mother Information</th><th colspan="1"></th><th colspan="1"></th></th></tr>
	<tr><th>No</th><th>Patient ID</th><th>Patient Name</th><th>Sex</th><th>Date of Birth</th><th>Age (mths)</th><th>Infant Prophylaxis</th><th>Date Collected</th><th>Received Status</th><th>HIV Status</th><th>PMTCT Intervention</th><th>Feeding Type</th><th>Entry Point</th><th>Sample Result</th><th>Task</th></tr>';

        $No = 0;
        while (list($ID, $patientid, $patient, $datereceived, $spots, $datecollected, $receivedstatus, $approved) = mysql_fetch_array($result)) {

            $sampleAutoID = $ID;
            //date collcted
            $sdoc = date("d-M-Y", strtotime($datecollected));
            //infant prophylaxis
            $pprophylaxis = GetPatientProphylaxis($patientid);
            //get sample sample test results
            $routcome = GetSampleResult($ID);
            //get sample recevied
            $srecstatus = GetReceivedStatus($receivedstatus);
            //get mother id from patient 
            $mother = GetMotherID($patientid);
            //mother hiv
            $mhiv = GetMotherHIVstatus($mother);
            //mother pmtct intervention
            $mprophylaxis = GetMotherProphylaxis($mother);
            //get mothers feeding type
            $mfeeding = GetMotherFeeding($mother);
            //get entry point
            $entry = GetEntryPoint($mother);

            //get all patient infor for use
            $pinfo = GetPatientInfo($patientid);
            extract($pinfo);
            //$dob=date("d-M-Y",strtotime($dob));
            if (($dob != "") && ($dob != "0000-00-00") && ($dob != "1970-01-01")) {
                $sdob = date("d-M-Y", strtotime($dob));
            } else {
                $sdob = "";
            }


            if ($testedbefore == 1) {
                $testedbefore = "Yes";
            } else if ($testedbefore == 2) {
                $testedbefore = "No";
            } else if ($testedbefore == 3) {
                $testedbefore = "Unk";
            }

            $No = $No + 1;

            if ($approved == 0) { //..not yet approved
                if ($accttype == 4) {//..check that the user is the lab tech then allow approve
                    $astatus = "<a href=sample_details.php?ID=$sampleAutoID&approve=1&view=1 style='color:#0000CC'><strong>Approve</strong></a></font> | <a href=\"sample_details.php" . "?ID=$sampleAutoID&view=1" . "\" title='Click to view sample details'>View</a> | <a href=\"deletesample.php" . "?ID=$sampleAutoID&batch=$batchno" . "\" title='Click to Delete Sample' OnClick=\"return confirm('Are you sure you want to delete Sample $patient');\" >Delete   </a>";
                    //| <a href='edit_sample.php?view=1&ID=$ID' title='Click to edit sample details' > Edit</a>
                } else {//..do not allow approve
                    $astatus = "<font color='#FF0000'><small>Not Approved Yet</small></font> | <a href=\"sample_details.php" . "?ID=$sampleAutoID&view=1" . "\" title='Click to view sample details'>View</a>  | <a href='edit_sample_details.php?ID=$sampleAutoID&view=1'>Edit</a> |  <a href=\"deletesample.php" . "?ID=$sampleAutoID&batch=$batchno" . "\" title='Click to Delete Sample' OnClick=\"return confirm('Are you sure you want to delete Sample $patient');\" >Delete   </a>";
                    //| <a href='edit_sample.php?view=1&ID=$ID' title='Click to edit sample details' > Edit</a> 
                }
            } else if ($approved == 1) { //..approved
                $astatus = " <font color='#00CC00'><strong>Approved</strong></font> | <a href=\"sample_details.php" . "?ID=$sampleAutoID&view=1" . "\" title='Click to view sample details'>View</a> | <a href='edit_sample_details.php?ID=$sampleAutoID&view=1'>Edit</a>";
            } else if ($approved == 2) { //..rejected
                $astatus = " <font color='#FF0000'><strong>Rejected</strong></font> | <a href=\"sample_details.php" . "?ID=$sampleAutoID&view=1" . "\" title='Click to view sample details'>View</a> ";
            }

            //..color code the received status only for the rejected or not received
            if (($receivedstatus == 2) or ($receivedstatus == 4)) {
                $reccolor = '#FF0000';
            } else {
                $reccolor = '';
            }

            echo "<tr class='even'>
			<td >$No</td>
			<td >$patient</td>
			<td >$name</td>
			<td ><div align='center'>$gender</div> </td>
			<td ><div align='center'>$sdob </div></td>
			<td ><div align='center'>$age</div></td>
			<td >$pprophylaxis</td>
			<td ><div align='center'>$sdoc</div></td>
			<td >$srecstatus</td>
			<td >$mhiv</td>
			<td >$mprophylaxis</td>
			<td >$mfeeding</td>
			<td >$entry</td>
			<td >$routcome </td>
			<td >$astatus</td>

	
	</tr>";
        }/* <a href="somepage.htm" title="Click to see this page!!!">Somepage</a> */
        echo '</table>';
    } else {
        ?>
                <table>
                    <tr>
                        <td style="width:auto" ><div class="notice"><?php echo '<strong><font color="#666600"><br>Please <font color="#FF0000">confirm</font> that:<br><br> a) <u>The samples in that batch were not rejected .</u> If they were, <a href="dispatchedrejectedsamples.php" style="color:#0033CC" target="_blank">click on this link to confirm</a> from the Rejected Samples List.<br><br> OR <br><br> b) They have all been approved.</strong></font>'; ?></div></td>			
                    </tr>
                </table><?php
    }
    ?>  

            <?php
        } elseif ($batchcompleted > 0) { //some samples till under testing
            ?>
            <table>
                <tr>
                    <td style="width:auto" ><div class="error"><?php echo '<strong>' . ' <font color="#666600">' . 'Samples in the selected Batch are still under processing' . '</strong>' . ' </font>'; ?></div></td>
                </tr>
            </table>
            <?php
            //query database for all districts
            $qury = "SELECT ID,patientid,patient,datereceived,spots,datecollected,receivedstatus,approved,parentid,result,worksheet
            FROM samples
			WHERE batchno='$batchno' AND Flag=1  ORDER BY parentid ASC";

            $quryresult = mysql_query($qury) or die(mysql_error());
            $no = mysql_num_rows($quryresult);

            if ($no != 0) {

                echo "
	<table class='data-table'>
	<tr class='even'>
		<th width='600'>
			Referring Clinic / Hospital Name :  $facilityname | Province :  $provname | District :  $distname
		</th>
		
		<th width='330' >
			Date Received: $sdrec 
		</th>
	</tr>
	</table>";
                /* <th width='30' >
                  <form action='batchreport.php' target ='_blank'  method='get' name='download batch'>
                  <input name='ID' type='hidden' value='$batchno'>
                  <input type='image' img src='../img/print.png'>
                  </form>
                  </th> */

                // print the districts info in table
                echo '<table border="0" class="data-table">
	<tr ><th colspan="16">Sample Log</th></tr>
	<tr><th colspan="7">Patient Information</th><th colspan="2">Sample Information</th><th colspan="4">Mother Information</th><th colspan="1"></th><th colspan="2"></th></th></tr>
	<tr><th>No</th><th>Patient ID</th><th>Patient Name</th><th>Sex</th><th>Date of Birth</th><th>Age (mths)</th><th>Infant Prophylaxis</th><th>Date Collected</th><th>Received Status</th><th>HIV Status</th><th>PMTCT Intervention</th><th>Feeding Type</th><th>Entry Point</th><th>Sample Result</th><th>Run</th><th>Task</th></tr>';

                $No = 0;
                while (list($ID, $patientid, $patient, $datereceived, $spots, $datecollected, $receivedstatus, $approved, $parentid, $result, $worksheet) = mysql_fetch_array($quryresult)) {

                    $run = getsamplerunnumber($ID, $parentid, $result, $worksheet);

                    $sampleAutoID = $ID;
                    //date collcted
                    $sdoc = date("d-M-Y", strtotime($datecollected));
                    //infant prophylaxis
                    $pprophylaxis = GetPatientProphylaxis($patientid);
                    //get sample sample test results
                    $routcome = GetSampleResult($ID);
                    //get sample recevied
                    $srecstatus = GetReceivedStatus($receivedstatus);
                    //get mother id from patient 
                    $mother = GetMotherID($patientid);
                    //mother hiv
                    $mhiv = GetMotherHIVstatus($mother);
                    //mother pmtct intervention
                    $mprophylaxis = GetMotherProphylaxis($mother);
                    //get mothers feeding type
                    $mfeeding = GetMotherFeeding($mother);
                    //get entry point
                    $entry = GetEntryPoint($mother);

                    //get all patient infor for use
                    $pinfo = GetPatientInfo($patientid);
                    extract($pinfo);
                    //$dob=date("d-M-Y",strtotime($dob));
                    if (($dob != "") && ($dob != "0000-00-00") && ($dob != "1970-01-01")) {
                        $sdob = date("d-M-Y", strtotime($dob));
                    } else {
                        $sdob = "";
                    }


                    if ($testedbefore == 1) {
                        $testedbefore = "Yes";
                    } else if ($testedbefore == 2) {
                        $testedbefore = "No";
                    } else if ($testedbefore == 3) {
                        $testedbefore = "Unk";
                    }

                    $No = $No + 1;

                    if ($approved == 0) { //..not yet approved
                        if ($accttype == 4) {//..check that the user is the lab tech then allow approve
                            $astatus = "<a href=sample_details.php?ID=$sampleAutoID&approve=1&view=1 style='color:#0000CC'><strong>Approve</strong></a></font> | <a href=\"sample_details.php" . "?ID=$sampleAutoID&view=1" . "\" title='Click to view sample details'>View</a> | <a href=\"deletesample.php" . "?ID=$sampleAutoID&batch=$batchno" . "\" title='Click to Delete Sample' OnClick=\"return confirm('Are you sure you want to delete Sample $patient');\" >Delete   </a>";
                            //| <a href='edit_sample.php?view=1&ID=$ID' title='Click to edit sample details' > Edit</a>
                        } else {//..do not allow approve
                            $astatus = "<font color='#FF0000'><small>Not Approved Yet</small></font> | <a href=\"sample_details.php" . "?ID=$sampleAutoID&view=1" . "\" title='Click to view sample details'>View</a>  | <a href='edit_sample_details.php?ID=$sampleAutoID&view=1'>Edit</a> |  <a href=\"deletesample.php" . "?ID=$sampleAutoID&batch=$batchno" . "\" title='Click to Delete Sample' OnClick=\"return confirm('Are you sure you want to delete Sample $patient');\" >Delete   </a>";
                            //| <a href='edit_sample.php?view=1&ID=$ID' title='Click to edit sample details' > Edit</a> 
                        }
                    } else if ($approved == 1) { //..approved
                        $astatus = " <font color='#00CC00'><strong>Approved</strong></font> | <a href=\"sample_details.php" . "?ID=$sampleAutoID&view=1" . "\" title='Click to view sample details'>View</a> | <a href='edit_sample_details.php?ID=$sampleAutoID&view=1'>Edit</a>";
                    } else if ($approved == 2) { //..rejected
                        $astatus = " <font color='#FF0000'><strong>Rejected</strong></font> | <a href=\"sample_details.php" . "?ID=$sampleAutoID&view=1" . "\" title='Click to view sample details'>View</a> ";
                    }


                    //..color code the received status only for the rejected or not received
                    if (($receivedstatus == 2) or ($receivedstatus == 4)) {
                        $reccolor = '#FF0000';
                    } else {
                        $reccolor = '';
                    }

                    if ($run != 1) {

                        echo "<tr class='even2' >
				<td >$No</td>
				<td >$patient</td>
				<td >$name</td>
				<td ><div align='center'>$gender</div> </td>
				<td ><div align='center'>$sdob </div></td>
				<td ><div align='center'>$age</div></td>
				<td >$pprophylaxis</td>
				<td ><div align='center'>$sdoc</div></td>
				<td ><font color='$reccolor'>$srecstatus</FONT></td>
				<td >$mhiv</td>
				<td >$mprophylaxis</td>
				<td >$mfeeding</td>
				<td >$entry</td>
				<td >$routcome </td>
				<td >$run </td>
				<td >$astatus</td>

	
	</tr>";
                    } else {

                        echo "<tr class='even'>
				<td >$No</td>
				<td >$patient</td>
				<td >$name</td>
				<td ><div align='center'>$gender</div> </td>
				<td ><div align='center'>$sdob </div></td>
				<td ><div align='center'>$age</div></td>
				<td >$pprophylaxis</td>
				<td ><div align='center'>$sdoc</div></td>
				<td ><font color='$reccolor'>$srecstatus</FONT></td>
				<td >$mhiv</td>
				<td >$mprophylaxis</td>
				<td >$mfeeding</td>
				<td >$entry</td>
				<td >$routcome </td>
				<td >$run </td>
				<td >$astatus</td>	
		</tr>";
                    }
                }/* <a href="somepage.htm" title="Click to see this page!!!">Somepage</a> */
                echo '</table>';
            } else {
                ?>
                <table>
                    <tr>
                        <td style="width:auto" ><div class="notice"><?php echo '<strong><font color="#666600">No Samples in that Batch. <br><br>Please <u>confirm that the samples were not rejected.</u> If they were, <a href="dispatchedrejectedsamples.php" style="color:#0033CC" target="_blank">click on this link to confirm</a> from the Rejected Samples List.</strong></font>'; ?></div></td>			
                    </tr>
                </table><?php
    }
            ?> 

            <?php
        }
        ?>
    </div>
</div>

        <?php include('../includes/footer.php'); ?>