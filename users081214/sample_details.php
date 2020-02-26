<?php include('../includes/header.php'); ?>
<?php
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
if ($_GET['samplecode'] == '') {
    $samplecode = $_GET['ID'];
} else {
    $samplecode = $_GET['samplecode'];
}
//echo $samplecode;
$approve = $_GET['approve'];
//$labss = $_SESSION['lab'];
$userid = $_SESSION['uid'];
$notselected = $_GET['notselected'];

//$labview = $_GET['labview'];
//
//if ($labview != '')
//{
//	if ($labview == 1)//nmrl
//	{
//		$labss = 1;
//	}
//	else if ($labview == 2)//zvitambo
//	{
//		$labss = 2;
//	}
//	else //any other lab
//	{
//		$labss = 0;
//	}
//}
//else if ($labview == '')
//{
//	$labss = $_SESSION['lab'];
//}
//echo $labss;

if ($_REQUEST['addonly']) {
    $batchno = $_GET['batchno'];
    $sampleid = $_GET['sampleid'];
    $receivedstatus = $_GET['receivedstatus'];
    $rejectedreason = addslashes($_GET['rejectedreason']);
    $repeatreason = addslashes($_GET['repeatreason']);
    $samplecode = $_GET['samplecode'];

    if ($receivedstatus != '') {
        if ($receivedstatus == 1) {//..accepted
            $approved = 1; //..approved
            $task = 1; //..sample awaiting testing
        } else if ($receivedstatus == 2) {//..rejected
            $approved = 2; //..not approved
            $task = 6; //..rejected sample awaiting dispatch
        } else if ($receivedstatus == 3) {//..repeat
            $approved = 1; //..approved
            $task = 1; //..sample awaiting testing
        } else if ($receivedstatus == 4) {//..not received
            $approved = 3; //,,not received
            $task = 8; //..sample not received
        }

        $dateupdated = date('Y-m-d');

        /* $completeentry = mysql_query("UPDATE samples SET  approved = '$approved', rejectedreason='$rejectedreason', reason_for_repeat='$repeatreason', receivedstatus='$receivedstatus' WHERE batchno = '$batchno' AND patient='$sampleid'")or die(mysql_error()); */
        $completeentry = mysql_query("UPDATE samples SET  approved = '$approved', rejectedreason='$rejectedreason', reason_for_repeat='$repeatreason', receivedstatus='$receivedstatus' WHERE batchno = '$batchno' AND ID='$samplecode'") or die(mysql_error());
        //save the pending tasks
        $savepending = mysql_query("INSERT INTO 		
	pendingtasks(task,batchno,status,sample,dateupdated) values ('$task','$batchno',0,'$samplecode','$dateupdated')") or die(mysql_error()); //save sample complete status

        /* $savepending = mysql_query("INSERT INTO 		
          pendingtasks(task,batchno,status,sample,lab,dateupdated) values ('$task','$batchno',0,'$sampleid','$labss','$dateupdated')")or die(mysql_error());//save sample complete status
         */
        if ($completeentry) {
            //save user activity
            $tasktime = date("h:i:s a");
            $todaysdate = date("Y-m-d");
            $utask = 4; //user task = approve sample

            $activity = SaveUserActivity($userid, $utask, $tasktime, $samplecode, $todaysdate);

            echo '<script type="text/javascript">';
            echo "window.location.href='verifybatcheslist.php?approvesuccess=1&sampleid=$sampleid'";
            echo '</script>';
        } else {
            echo '<script type="text/javascript">';
            echo "window.location.href='verifybatcheslist.php?approvesuccess=0&sampleid=$sampleid'";
            echo '</script>';
        }
    } else if ($receivedstatus == '') {
        $batchno = $_GET['batchno'];
        $samplecode = $_GET['samplecode'];

        echo '<script type="text/javascript">';
        echo "window.location.href='sample_details.php?samplecode=$samplecode&batchno=$batchno&notselected=1&view=1'";
        echo '</script>';
    }
} else {//..if the save button was not clicked
    $samples = getSampleetails($samplecode);
    extract($samples);
//get sample facility name based on facility code
    $facilityname = GetFacility($facility);
    if (($datecollected != "") && ($datecollected != "0000-00-00") && ($datecollected != "1970-01-01")) {
        $datecollected = date("d-M-Y", strtotime($datecollected));
    } else {
        $datecollected = "";
    }

    if (($datereceived != "") && ($datereceived != "0000-00-00") && ($datereceived != "1970-01-01")) {
        $datereceived = date("d-M-Y", strtotime($datereceived));
    } else {
        $datereceived = "";
    }

    if (($datetested != "") && ($datetested != "0000-00-00") && ($datetested != "1970-01-01")) {
        $datetested = date("d-M-Y", strtotime($datetested));
    } else {
        $datetested = "";
    }

    if (($datemodified != "") && ($datemodified != "0000-00-00") && ($datemodified != "1970-01-01")) {
        $datemodified = date("d-M-Y", strtotime($datemodified));
    } else {
        $datemodified = "";
    }

    if (($datedispatched != "") && ($datedispatched != "0000-00-00") && ($datedispatched != "1970-01-01")) {
        $datedispatched = date("d-M-Y", strtotime($datedispatched));
    } else {
        $datedispatched = "";
    }
    /* if ($result  != 0)
      {
      $routcome = GetSampleResult($result);
      } */

    $patient = $patientid;
    $patientinfo = GetPatientInfo($patient);
    $infantname = $patientinfo['name'];
    $testedbefore = $patientinfo['testedbefore'];
    $infanthivstatus = $patientinfo['infanthivstatus'];
    $testtype = $patientinfo['testtype'];
    $originalpatientno = $patientinfo['originalrequestno_year'] . $patientinfo['originalrequestno_no'];

    $pgender = GetPatientGender($patient);
    //patietn age
    $pAge = GetPatientAge($patient);
    //patient dob
    $pdob = GetPatientDOB($patient);
    //infant prophylaxis
    $pprophylaxis = GetPatientProphylaxis($patient);
    //get sample sample test results
    $routcome = GetSampleResult($ID);
    //get sample recevied
    $srecstatus = GetReceivedStatus($receivedstatus);
    //get mother id from patient 
    $mother = GetMotherID($patient);
    $motherinf = GetMotherANC($patient);
    $anc = $motherinf['anc'];
    $mname = $motherinf['name'];
    //mother hiv
    $mhiv = GetMotherHIVstatus($mother);
    //mother pmtct intervention
    $mprophylaxis = GetMotherProphylaxis($mother);
    //get mothers feeding type
    $mfeeding = GetMotherFeeding($mother);
    //get entry point
    $entry = GetEntryPoint($mother);
}
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
	
        function getRejectedreasons(receivedstatus) {		
		
            var strURL="findRejectedReasons.php?rejid="+receivedstatus;
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
    <div class="section-title">
        <?php
        if (($approve == '') && ($notselected == '')) {
            echo '[Batch No ' . $batchno . ']';
        } else {
            echo 'Approve';
        }
        echo ' Sample ' . $patient . ' Details';
        ?> 
    </div>
    <div class="xtop">
        <?php
        if ($result == 0) {
            ?>
            <table>
                <tr>
                    <td><A HREF='javascript:history.back(-1)'><img src='../img/back.gif' alt='Go Back'/></A></td>
                    <td> | </td>
                    <td><?php
        if (($approve == '') || ($notselected == '')) {
            echo '';
            //echo '<strong>'." <a href=\"edit_sample.php" ."?ID=$ID" . "\" > Edit Sample Details</a>".'<strong>';
        } else if ($approve != '') {
            echo '<div class="notice"><strong>You may enter the Sample Received Status at the bottom of the page.</strong></div>';
        } else if ($notselected == 1) {
            echo '<div class="error"><strong>Please enter the Sample Received Status at the bottom of the page.</strong></div>';
        }
            ?></td>
                </tr>
            </table>
            <?php
        }
        ?>
        <form name="approvals" method="get">
            <table class="">

 <!--<th colspan="4">FACILITY INFORMATION</th> -->

                <tr>
                    <td ><strong>Referring Clinic / Hospital Name</strong></td>
                    <td colspan="3" ><?php echo $facilityname; ?>&nbsp;</td>
                    <td height="24" ><span style="font-weight: bold">Request No </span></td>
                    <?php
                    //get all patient infor for use
                    $pinfo = GetPatientInfo($patient);
                    extract($pinfo);
                    ?>
                    <td ><?php echo '<strong>Year &nbsp;</strong>' . $requestno_year . '&nbsp;&nbsp;<strong>No &nbsp;</strong>' . $requestno_no; ?></td>
                <input name="sampleid" type="hidden" class="text" value="<?php echo $requestno_year . $requestno_no; ?>" />
                <input name="samplecode" type="hidden" class="text" value="<?php echo $samplecode; ?>" />
                <input name="batchno" type="hidden" class="text" value="<?php echo $batchno; ?>" />
                </tr>
                <!--<tr ><td colspan="7">&nbsp;</td></tr> -->

                <?php
                //get all mother infor for use
                $minfo = GetMotherInfo($mother);
                extract($minfo);
                
                $mthrTestedBefore = $minfo['testedbefore'];

                if ($mthrTestedBefore == 1) {
                    $mthrTestedBefore = "Yes";
                } else if ($mthrTestedBefore == 2) {
                    $mthrTestedBefore = "No";
                } else if ($mthrTestedBefore == 3) {
                    $mthrTestedBefore = "Unk";
                } else {
                    $mthrTestedBefore = "";
                }

                if ($onart == 1) {
                    $onart = "Yes";
                } else if ($onart == 2) {
                    $onart = "No";
                } else if ($onart == 3) {
                    $onart = "Unk";
                } else {
                    $onart = "";
                }

                if ($breastfeeding == 1) {
                    $breastfeeding = "Yes";
                } else if ($breastfeeding == 2) {
                    $breastfeeding = "No";
                } else if ($breastfeeding == 3) {
                    $breastfeeding = "Unk";
                } else {
                    $breastfeeding = "";
                }

                if ($receivearv == 1) {
                    $receivearv = "Yes";
                } else if ($receivearv == 2) {
                    $receivearv = "No";
                } else if ($receivearv == 3) {
                    $receivearv = "Unk";
                } else {
                    $receivearv = "";
                }

                //mother pmtct intervention
                $art = GetMotherProphylaxis($ID);

                if ($delivery == 1) {
                    $delivery = "Caesarean";
                } else if ($delivery == 2) {
                    $delivery = "Vaginal";
                } else if ($delivery == 3) {
                    $delivery = "Unknown";
                } else {
                    $delivery = "";
                }
                ?>	
                <th colspan="8" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">&nbsp;<!--MOTHER INFORMATION --></th>

                <tr >
                    <td><span style="font-weight: bold">Name of mother </span></td>
                    <td><?php echo $mname; ?>&nbsp;</td>
                    <td><span style="font-weight: bold">Was Mother Tested for HIV Before? </span></td>
                    <td><?php echo $mthrTestedBefore; ?>&nbsp;</td>
                    <td><span style="font-weight: bold">HIV Result </span></td>
                    <td><?php echo $mhiv; ?>&nbsp;</td>
                </tr>
                <tr>
                <tr >
                    <td><span style="font-weight: bold">Mother ANC #</span></td>
                    <td><?php echo $anc; ?>&nbsp;</td>
                </tr>

                <th colspan="8" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">&nbsp;<!--INFANT INFORMATION --></th>

                <tr>

                    <td ><span style="font-weight: bold">Infant's Name</span> </td>
                    <td ><?php echo $infantname; ?></td>
                    <td ><span style="font-weight: bold">Date of Birth
                        </span></td>
                    <td ><?php echo $pdob; ?></td>
                    <td ><span style="font-weight: bold">Sex of Baby</span> </td>
                    <td colspan="3"><?php echo $pgender; ?></td>
                </tr>
                    <!--<tr class="even">
   
             <td ><span style="font-weight: bold">Age At Testing
      </span></td>
          <td bgcolor="#F0F3FA"><?php /*
                  if ($pAge < 12)
                  { $agetype = " Months";}
                  else if ($pAge >= 12)
                  { $agetype = " Years";}
                  echo $pAge.$agetype; */ ?></td>
    </tr> -->
                <tr>
                </tr>
                <tr >
                    <td><span style="font-weight: bold">Date of taking DBS </span></td>
                    <td><?php echo $datecollected; ?></td>
                    <td><span style="font-weight: bold">Mode of Delivery </span></td>
                    <td><?php echo $delivery; ?></td><!--
        <td ><span style="font-weight: bold">Infant Prophylaxis </span></td>
                    <td colspan="3" ><?php //echo $pprophylaxis;   ?></td> -->
                </tr>  

                <tr>
                    <td colspan="7" >&nbsp;</td>
                </tr>

                <th colspan="2" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2; border-right:inset; border-right-color:#CCCCCC">INFANT PROPHYLAXIS</th><th colspan="2" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2; border-right:inset; border-right-color:#CCCCCC">MOTHER PMTCT Prophylaxis</th><th colspan="5" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">Infant Testing (Check Child Health Card)</th>
                <?php
//get all patient infor for use
                $pinfo = GetPatientInfo($patient);
                extract($pinfo);

                if ($infantarv == 1) {
                    $infantarv = "Yes";
                } else if ($infantarv == 2) {
                    $infantarv = "No";
                } else if ($infantarv == 3) {
                    $infantarv = "Unk";
                } else {
                    $infantarv = "";
                }

                if ($testedbefore == 1) {
                    $testedbefore = "Yes";
                } else if ($testedbefore == 2) {
                    $testedbefore = "No";
                } else if ($testedbefore == 3) {
                    $testedbefore = "Unk";
                } else {
                    $testedbefore = "";
                }
//infant prophylaxis
                $prophylaxis = GetPatientProphylaxis($patientid);
                ?>
                <tr>
                    <td><strong>ARV Prophylaxis given to Infant</strong></td>
                    <td style="border-right:inset; border-right-color:#CCCCCC"><?php echo $infantarv; ?></td>
                    <td><strong>Is Mother on ART</strong></td>
                    <td style="border-right:inset; border-right-color:#CCCCCC"><?php echo $onart; ?></td>
                    <td rowspan="3" ><span style="font-weight: bold">Was the infant tested for HIV before? </span></td>
                    <td rowspan="3" style="border-right:inset; border-right-color:#CCCCCC"><?php echo $testedbefore; ?></td>
                    <td ><span style="font-weight: bold">If yes, what was the result?</span></td>
                    <td>
                        <?php
                        $infanthivstatus = GetResultName($infanthivstatus);
                        echo $infanthivstatus;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><strong> If Yes, what did the infant receive? </strong></td>
                    <td style="border-right:inset; border-right-color:#CCCCCC"><?php echo $prophylaxis; ?></td>
                    <td><strong> If No, did the mother receive ARV Prophylaxis?  </strong></td>
                    <td style="border-right:inset; border-right-color:#CCCCCC"><?php echo $receivearv; ?></td>
                    <td><span style="font-weight: bold">If yes, what type of test was it? </span></td>
                    <td><?php echo $testtype; ?></td>
                </tr>
                <tr>
                    <td><strong> Infant already on CTX prophylaxis? </strong></td>
                    <td style="border-right:inset; border-right-color:#CCCCCC"><?php echo $onctx; ?></td>
                    <td><strong> If Yes, what did the mother receive?  </strong></td>
                    <td style="border-right:inset; border-right-color:#CCCCCC"><?php echo $art; ?></td>
                    <?php
                    if ($testedbefore != '1') {
                        if ($testtype == 'DNA PCR') {
                            ?>
                            <td ><span style="font-weight: bold">If DNA PCR, give original patient Lab Request No </span></td>
                            <td ><?php echo $originalpatientno; ?></td>
                            <?php
                        }
                    }
                    ?>
                </tr>
                <tr>
                    <td colspan="7">&nbsp;</td>
                </tr>
                <th colspan="8" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">INFANT FEEDING </th>

                <tr>
                    <td height="24" ><strong>Infant breastfed in the last 6 weeks  </strong> </td>
                    <td><?php echo $breastfeeding; ?></td>	
                    <td height="24"><strong>Type of Feeding</strong> </td>
                    <td colspan="3"><?php echo $mfeeding; ?></td>	
                </tr>

                <tr>
                    <td colspan="7" >&nbsp;</td>
                </tr>

                <th style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">ENTRY POINT</th><td><?php echo $entry; ?></td><th style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">Reasons for DNA / PCR Test</th><td colspan="3"><?php
                    $test_reason = GetTestReason($test_reason);
                    echo $test_reason;
                    ?></td>

                <tr>
                    <td colspan="7" >&nbsp;</td>
                </tr>

                <th colspan="8" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">SAMPLE INFORMATION </th>
                <tr >



                    <td ><span style="font-weight: bold">Date Received  </span></td>
                    <td ><?php echo $datereceived; ?></td>
                    <td><span style="font-weight: bold"><div class="notice">Received Status </div></span></td>
                    <td ><?php
                    if ($receivedstatus == 0) {
                        $receivedstatus = '[No Status Selected]';
                    } else {
                        $receivedstatus = GetReceivedStatus($receivedstatus);
                    }

                    if (($approve != '') || ($notselected == 1)) {
                        $rquery = "SELECT ID,Name FROM receivedstatus ";

                        $rresult = mysql_query($rquery) or die('Error, query failed'); //onchange='submitForm();'

                        echo "<select name='receivedstatus' id='receivedstatus' style='width:188px' onChange='getRejectedreasons(this.value)';>\n";
                        echo " <option value=''> Select One </option>";
                        while ($rrow = mysql_fetch_array($rresult)) {
                            $rID = $rrow['ID'];
                            $rname = $rrow['Name'];

                            if ($rID == 2) { //rejected
                                $fcolor = '#FF0000';
                            } else if ($rID == 3) { //repeat
                                $fcolor = '#0000FF';
                            } else if ($rID == 4) { //not received
                                $fcolor = '';
                            } else { //accepted
                                $fcolor = '#00CC00';
                            }
                            echo "<option value='$rID' style='color:" . $fcolor . "'> $rname</option>\n";
                        }
                        echo "</select>\n
							</td>
							<td width=150><div id='statediv'></div></td>";
                    } else {
                        echo '<td>' . $receivedstatus;
                    }
                    ?></td>
                </tr>
                    <!--<tr >
    
                    
    <td><span style="font-weight: bold">No of Spots </span></td>
                <td colspan="7"><?php //echo $spots;   ?></td>
                    </tr> -->


                <?php
                if (($approve == '') && ($notselected == '')) {
                    
                } else {
                    echo '
				<tr>
				<td colspan=6 >
					<div align="right">
					<input name="addonly" type="submit" class="button" value="Save Sample Received Status" />
					</div>			</td>
			  </tr>';
                }

                if ($result != 0) {
                    ?>
                    <tr >
                        <td colspan="7" >&nbsp;</td>
                    </tr>
                    <th colspan="8" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">
                        SAMPLE TESTING DETAILS
                    </th>
                    <tr >
                        <td ><span style="font-weight: bold">Date Test Performed </span></td>
                        <td ><?php echo $datetested; ?></td>				
                        <td ><span style="font-weight: bold">Date Result Updated </span></td>
                        <td ><?php echo $datemodified; ?></td>
                        <td><span style="font-weight: bold">Date Result Dispatched </span></td>
                        <td ><?php echo $datedispatched; ?></td>
                    </tr>
                    <tr >
                        <td><span style="font-weight: bold">Test Results </span></td>
                        <td>
                            <?php
                            $result = GetResultType($result);
                            echo $result;
                            ?></td>
                    </tr>
                    <tr >
                        <td ><span style="font-weight: bold">Dispatch Comments</span></td>
                        <td colspan="7" ><?php echo $DispatchComments; ?></td>
                    </tr>
                <?php }
                ?>


            </table>
        </form>





        <?php include('../includes/footer.php'); ?>