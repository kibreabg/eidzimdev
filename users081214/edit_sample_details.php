<?php
include('../includes/header.php');
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');

//..get the saved status and show success or otherwise
$savedstatus = $_GET['saved'];
if ($savedstatus != '') {
    echo '<table><tr><th><div class="';
    if ($savedstatus == 1) {//..saved successfully
        echo 'success">The details have been <u>SUCCESSFULLY UPDATED</u>. KINDLY READ THROUGH TO CONFIRM THE DETAILS EDITED.';
    } else if ($savedstatus == 2) {//..NOT saved
        echo 'error">The details have been <u>NOT BEEN UPDATED</u>. PLEASE TRY AGAIN.';
    }
    echo '</div></th></tr></table>';
}

if ($_GET['samplecode'] == '') {
    $samplecode = $_GET['ID'];
} else {
    $samplecode = $_GET['samplecode'];
}
$approve = $_GET['approve'];
$userid = $_SESSION['uid'];
$notselected = $_GET['notselected'];
$labview = $_GET['labview'];

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
//**********************************************************

if ($_REQUEST['updateinfo']) {
    $nmrlstampno = $_POST['nmrlstampno'];
    $mother_name = $_POST['mother_name'];
    $anc_no = $_POST['anc_no'];
    $mohivstatus = $_POST['mohivstatus'];
    $infant = $_POST['infant'];
    $sdob = $_POST['sdob'];
    $sdoc = $_POST['sdoc'];
    $delivery = $_POST['delivery'];
    $infantfeeding = $_POST['infeeding'];
    $mentpoint = $_POST['mentpoint'];
    $testreason = $_POST['testreason'];

    $requestno_year = $_POST['requestno_year'];
    $requestno_no = $_POST['requestno_no'];

    $patientrecordid = $_POST['patientrecordid'];
    $motherrecordid = $_POST['motherrecordid'];
    $samplerecordid = $_POST['samplerecordid'];

    $patientrequestno = $requestno_year . $requestno_no;

    //get the patient's age
    if (($sdob != "") && ($sdoc != "")) {
        $dob = date("d-m-Y", strtotime($sdob));
        $doc = date("d-m-Y", strtotime($sdoc));
        $agedays = round((strtotime($doc) - strtotime($dob)) / (60 * 60 * 24));
        $agemonths = round(($agedays / 30), 1);
    } else {
        $agemonths = 0;
    }

    //check if the feeding options selected to answer the 1st question as to whether ...  Infant breastfed in the last 6 weeks
    if (($infantfeeding == 2) or ($infantfeeding == 3) or ($infantfeeding == 4)) {//..then answer = Yes (1)
        $bf = 1; //..Yes
    } else if ($infantfeeding == 6) {//..Never breastfed then answer = No (2)
        $bf = 2; //..No
    } else if ($infantfeeding == 7) {//..Unk then answer = unk (3)
        $bf = 3; //..Unk
    }

    //..update mother
    $updatemother = mysql_query("UPDATE mothers SET name = '$mother_name' , anc ='$anc_no', entry_point = '$mentpoint', breastfeeding ='$bf', feeding = '$infantfeeding', status ='$mohivstatus', delivery ='$delivery' WHERE (ID = '$motherrecordid')") or die(mysql_error());


    //..update patient
    $updatepatient = mysql_query("UPDATE patients SET ID = '$patientrequestno', name = '$infant', age ='$agemonths', dob='$sdob',  requestno_year = '$requestno_year', requestno_no = '$requestno_no' WHERE (AutoID = '$patientrecordid')") or die(mysql_error());


    //..update sample
    $updatesample = mysql_query("UPDATE samples SET nmrlstampno ='$nmrlstampno', patient ='$patientrequestno', datecollected ='$sdoc', test_reason ='$testreason' WHERE (ID = '$samplerecordid')") or die(mysql_error());

    if (($updatemother) and ($updatepatient) and ($updatesample)) {
        $saved = 1;
    } else {
        $saved = 0;
    }

    //..refer back to the previous page
    echo '<script type="text/javascript">';
    echo "window.location.href='edit_sample_details.php?ID=$samplerecordid&view=1&labview=1&saved=$saved'";
    echo '</script>';
} else if ($_REQUEST['cancel']) {
    //..refer back to the previous page
    echo '<script type="text/javascript">';
    echo "window.location.href='javascript:history.back(-1)'";
    echo '</script>';
} else {//..if the update button was not clicked
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



    $patient = $patientid; //..$patientid is autoincrement and is picked from the samples table
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
}//**********************************************************
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
        <?php
//get all patient infor for use
        $pinfo = GetPatientInfo($patient);
        extract($pinfo);

        if ($savedstatus == '') {
            ?>
        <div class="section-title"><font style="font-family:Verdana, Arial, Helvetica, sans-serif">EDIT <?php echo ' SAMPLE ' . $requestno_year . $requestno_no . ' DETAILS'; ?> </font></div>
    <?php } ?>
    <div class="xtop">
        <?php
        if (($result == 0) or ($result == '')) {//..check if the sample has a result...and if it does then do not allow edit..refer back to previous page.
            ?>
            <table>
                <tr>
                    <td><A HREF='javascript:history.back(-1)'><img src='../img/back.gif' alt='Go Back'/></A></td>
                    <td> | </td>
                    <td style="background:#FFFFCC">Please note that the Facility Name & Date Sample received <strong><u>CANNOT</u></strong> be edited since it would affect the <strong>BATCHING SYSTEM</strong>.</td>
                </tr>
            </table>

            <form name="approvals" method="post">
                <table class="">

         <!--<th colspan="4">FACILITY INFORMATION</th> -->
                    <tr>
                        <th>
                    <div class="notice">NMRL STAMP NUMBER</div>
                    </th>
                    <th colspan="3"><input type="text" name="nmrlstampno" class="text" size="45" value="<?php echo $nmrlstampno; ?>" />
                        <input type="hidden" name="patientrecordid" class="text" size="45" value="<?php echo $patientid; ?>" />   
                        <input type="hidden" name="motherrecordid" class="text" size="45" value="<?php echo $mother; ?>" />
                        <input type="hidden" name="samplerecordid" class="text" size="45" value="<?php echo $samplecode; ?>" />
                    </th>
                    </tr>
                    <th colspan="8" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">&nbsp;<!--FACILITY INFORMATION --></th>
                    <tr >
                        <td ><strong>Referring Clinic / Hospital Name</strong></td>
                        <td colspan="3" >
                            <?php
                            echo '<strong><font color="#FF0000" style="font-size:14px">' . $facilityname . ' </font> </strong>';
                            /* $fdeliveryquery = "SELECT ID,name FROM facilitys where flag = 1 and ID != '$facility'";

                              $fdresult = mysql_query($fdeliveryquery) or die('Error, query failed'); //onchange='submitForm();'

                              echo "<select name='cat' id='cat' style='width:300px';>\n";
                              echo " <option value='$facility'> $facilityname </option>";

                              while ($fdrow = mysql_fetch_array($fdresult))
                              {
                              $frID = $fdrow['ID'];
                              $frname = $fdrow['name'];
                              echo "<option value='$frID'> $frname</option>\n";
                              }
                              echo "</select>\n"; */
                            ?></td>
                        <td height="24" ><span style="font-weight: bold">Request No </span></td>		
                        <td colspan="3"><?php echo '<strong>Year &nbsp;</strong><input type="text" name="requestno_year" id="requestno_year" size="5" class="text" id="" value="' . $requestno_year . '"/>&nbsp;&nbsp;<strong>No &nbsp;</strong><input type="text" name="requestno_no" id="requestno_no" size="10" class="text" id="" value="' . $requestno_no . '"/> '; ?></td>
                    <input name="sampleid" type="hidden" class="text" value="<?php echo $requestno_year . $requestno_no; ?>" />
                    <input name="samplecode" type="hidden" class="text" value="<?php echo $samplecode; ?>" />
                    <input name="batchno" type="hidden" class="text" value="<?php echo $batchno; ?>" />
                    </tr>
                          <!--<tr ><td colspan="7">&nbsp;</td></tr> -->

                    <?php
                    //get all mother infor for use
                    $minfo = GetMotherInfo($mother);
                    extract($minfo);

                    if ($testedbefore == 1) {
                        $testedbefore = "Yes";
                    } else if ($testedbefore == 2) {
                        $testedbefore = "No";
                    } else if ($testedbefore == 3) {
                        $testedbefore = "Unk";
                    } else {
                        $testedbefore = "";
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

                    /* if ($breastfeeding == 1) 	 {$breastfeeding = "Yes";}
                      else if ($breastfeeding == 2) {$breastfeeding = "No";}
                      else if ($breastfeeding == 3) {$breastfeeding = "Unk";}
                      else  {$breastfeeding = "";} */

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
                        $ndelivery = "Caesarean";
                    } else if ($delivery == 2) {
                        $ndelivery = "Vaginal";
                    } else if ($delivery == 3) {
                        $ndelivery = "Unknown";
                    } else {
                        $delivery = "";
                    }
                    ?>	
                    <th colspan="8" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">&nbsp;<!--MOTHER INFORMATION --></th>

                    <tr >
                        <td><span style="font-weight: bold">Name of mother </span></td>
                        <td><input type="text" name="mother_name" class="text" size="32" value="<?php echo $mname; ?>" /></td>
                                <!-- <td><span style="font-weight: bold">Was Mother Tested for HIV Before? </span></td>
                        <td><?php //echo $testedbefore;  ?>&nbsp;</td> -->
                        <td><span style="font-weight: bold">Mother's HIV Result Status </span></td>
                        <td>
                            <?php
                            $sresultquery = "SELECT ID,name FROM results where ID != '$status'";

                            $sshowresult = mysql_query($sresultquery) or die('Error, query failed'); //onchange='submitForm();'

                            echo "<select name='mohivstatus' id='mohivstatus' style='width:110px';>\n";
                            echo " <option value='$status'> $mhiv </option>";

                            while ($ssrow = mysql_fetch_array($sshowresult)) {
                                $sID = $ssrow['ID'];
                                $sname = $ssrow['name'];
                                echo "<option value='$sID'> $sname</option>\n";
                            }
                            echo "</select>\n";
                            //echo $mhiv; 
                            ?>&nbsp;</td>
                    </tr>
                    <tr>
                    <tr >
                        <td><span style="font-weight: bold">Mother ANC #</span></td>
                        <td><input type="text" name="anc_no" class="text" size="32" value="<?php echo $anc; ?>" /></td>
                    </tr>

                <!--<tr >
                <td colspan="7" >&nbsp;</td>
                </tr> -->

                    <th colspan="8" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">&nbsp;<!--INFANT INFORMATION --></th>

                    <tr >

                        <td ><span style="font-weight: bold">Infant's Name</span> </td>
                        <td ><input type="text" name="infant" class="text" size="32" value="<?php echo $infantname; ?>" />  </td>
                        <td ><span style="font-weight: bold">Date of Birth  </span></td>
                        <td ><?php
                        //..get tbe date of birth in d m Y
                        $bd = "SELECT day(dob) as daybd, month(dob) as dbmon, year(dob) as dby from patients WHERE AutoID='$patient'";
                        $bdgot = mysql_query($bd) or die(mysql_error());
                        $bdarec = mysql_fetch_array($bdgot);
                        $bsd = $bdarec['daybd'];
                        $bsm = $bdarec['dbmon'];
                        $bsy = $bdarec['dby'];


                        $myCalendar = new tc_calendar("sdob", true, false);
                        $myCalendar->setIcon("../img/iconCalendar.gif");
                        $myCalendar->setDate($bsd, $bsm, $bsy);
                        $myCalendar->setPath("./");
                        $myCalendar->setYearInterval($lowestdate, $currentdate);
                        //$myCalendar->dateAllow('2008-05-13', '2015-03-01');
                        $myCalendar->setDateFormat('j F Y');
                        //$myCalendar->setHeight(350);	  
                        //$myCalendar->autoSubmit(true, "form1");
                        $myCalendar->writeScript();
                            ?>                <?php //echo $pdob;  ?></td>
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
                    <tr >

                    </tr>
                    <tr >
                        <td><span style="font-weight: bold">Date of taking DBS </span></td>
                        <td><?php
                //..get tbe date of birth in d m Y
                $d = "SELECT day(datecollected) as dayd, month(datecollected) as dmon, year(datecollected) as dy from samples WHERE patientid='$patient'";
                $dgot = mysql_query($d) or die(mysql_error());
                $darec = mysql_fetch_array($dgot);
                $sd = $darec['dayd'];
                $sm = $darec['dmon'];
                $sy = $darec['dy'];


                /* if ($sm == 1) { $sm = 'January';} if ($sm == 2) {$sm = 'February';} if ($sm == 3) {$sm = 'March';} if ($sm == 4) {$sm = 'April';}if ($sm == 5) {$sm = 'May';}if ($sm == 6) {$sm = 'June';}if ($sm == 7) {$sm = 'July';}if ($sm == 8) {$sm = 'August';}if ($sm == 9) {$sm = 'September';}if ($sm == 10) {$sm = 'October';}if ($sm == 11) {$sm = 'November';}if ($sm == 12) {$sm = 'December';} */

                $currentdate = date('Y'); //show the current year
                $lowestdate = GetAnyDateMin(); //get the lowest year from date received

                $myCalendar = new tc_calendar("sdoc", true, false);
                $myCalendar->setIcon("../img/iconCalendar.gif");
                $myCalendar->setDate($sd, $sm, $sy);
                $myCalendar->setPath("./");
                $myCalendar->setYearInterval($lowestdate, $currentdate);
                //$myCalendar->dateAllow('2008-05-13', '2015-03-01');
                $myCalendar->setDateFormat('j F Y');
                //$myCalendar->setHeight(350);	  
                //$myCalendar->autoSubmit(true, "form1");
                $myCalendar->writeScript();
                            ?>                     <?php /* echo $datecollected; */ ?></td>
                        <td><span style="font-weight: bold">Mode of Delivery </span></td>
                        <td><?php
                        $deliveryquery = "SELECT ID,name FROM deliverymode where ID != '$delivery'";

                        $dresult = mysql_query($deliveryquery) or die('Error, query failed'); //onchange='submitForm();'

                        echo "<select name='delivery' id='delivery' style='width:188px';>\n";
                        echo " <option value='$delivery'> $ndelivery </option>";

                        while ($drow = mysql_fetch_array($dresult)) {
                            $ID = $drow['ID'];
                            $name = $drow['name'];
                            echo "<option value='$ID'> $name</option>\n";
                        }
                        echo "</select>\n";
                            ?>                      <?php //echo $delivery; ?></td>
                        <!--
            <td ><span style="font-weight: bold">Infant Prophylaxis </span></td>
                        <td colspan="3" ><?php //echo $pprophylaxis;  ?></td> -->
                    </tr>  

                    <tr >
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
                    <tr >
                        <td><strong>ARV Prophylaxis given to Infant</strong></td>
                        <td style="border-right:inset; border-right-color:#CCCCCC"><?php echo $infantarv; ?></td>
                        <td><strong>Is Mother on ART</strong></td>
                        <td style="border-right:inset; border-right-color:#CCCCCC"><?php echo $onart; ?></td>
                        <td rowspan="3" ><span style="font-weight: bold">Was the infant tested for HIV before? </span></td>
                        <td rowspan="3" style="border-right:inset; border-right-color:#CCCCCC"><?php echo $testedbefore; ?></td>
                        <td ><span style="font-weight: bold">If yes, what was the result?</span></td>
                        <td ><?php
                $infanthivstatus = GetResultName($infanthivstatus);
                echo $infanthivstatus;
                    ?></td>
                    </tr>


                    <tr >
                        <td><strong> If Yes, what did the infant receive? </strong></td>
                        <td style="border-right:inset; border-right-color:#CCCCCC"><?php echo $prophylaxis; ?></td>
                        <td><strong> If No, did the mother receive ARV Prophylaxis?  </strong></td>
                        <td style="border-right:inset; border-right-color:#CCCCCC"><?php echo $receivearv; ?></td>
                        <td ><span style="font-weight: bold">If yes, what type of test was it? </span></td>
                        <td ><?php echo $testtype; ?></td>
                    </tr>
                    <tr >
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

                    <tr >
                        <td colspan="7" >&nbsp;</td>
                    </tr>

                    <th colspan="8" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">INFANT FEEDING </th>



                    <tr >
                                 <!--<td height="24" ><strong>Infant breastfed in the last 6 weeks  </strong> </td>
                       <td ><?php //echo $breastfeeding;   ?></td>	 -->
                        <td height="24" ><strong>Type of Feeding</strong> </td>
                        <td colspan="3" ><?php
                    $d06 = "SELECT ID,name, description FROM feedings where ID != '$breastfeeding'";
                    $d06result = mysql_query($d06) or die('Error, query failed'); //onchange='submitForm();'

                    echo "<select name='infeeding' style='width:188px';>\n";
                    echo " <option value='$breastfeeding'> $mfeeding </option>";

                    while ($d06row = mysql_fetch_array($d06result)) {
                        $d6ID = $d06row['ID'];
                        $d6name = $d06row['name'];
                        $d6d = $d06row['description'];
                        echo "<option value='$d6ID'> $d6name [ $d6d ]</option>\n";
                    }
                    echo "</select>\n";

                    //echo $mfeeding; 
                        ?></td>	
                    </tr>

                    <tr >
                        <td colspan="7" >&nbsp;</td>
                    </tr>

                    <th style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">ENTRY POINT</th><td><?php
                        $d19 = "SELECT ID,name FROM entry_points where ID != '$entry_point'";
                        $d19result = mysql_query($d19) or die('Error, query failed'); //onchange='submitForm();'

                        echo "<select name='mentpoint' id='mentpoint' style='width:188px';>\n";
                        echo " <option value='$entry_point'> $entry </option>";

                        while ($d19row = mysql_fetch_array($d19result)) {
                            $d19ID = $d19row['ID'];
                            $d19name = $d19row['name'];
                            echo "<option value='$d19ID'> $d19name</option>\n";
                        }
                        echo "</select>\n";

                        // echo $entry; 
                        ?></td>
                    <th style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">Reasons for DNA / PCR Test</th><td colspan="3">

                        <?php
                        $d11 = "SELECT id,name FROM testreason where ID != '$test_reason'";
                        $d11result = mysql_query($d11) or die('Error, query failed'); //onchange='submitForm();'


                        $test_reason_name = GetTestReason($test_reason);

                        echo "<select name='testreason' id='testreason' style='width:188px';>\n";
                        echo " <option value='$test_reason'> $test_reason_name </option>";

                        while ($d11row = mysql_fetch_array($d11result)) {
                            $d11ID = $d11row['id'];
                            $d11name = $d11row['name'];
                            echo "<option value='$d11ID'> $d11name</option>\n";
                        }
                        echo "</select>\n";

                        //$test_reason = GetTestReason($test_reason);
                        //echo $test_reason; 
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

                    <tr>
                        <th colspan="6">
                    <div align="center">
                        <input type="submit" name="updateinfo" value="Save Changes" style="width:450px" class="button">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="submit" name="cancel" value="Cancel Edit" style="width:450px" onClick="javascript:history.back(-1)" class="button"></div></th>
                    </tr>

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
                    ?>


                </table>
            </form>


            <?php
        } else { //..refer back to previous page since the sample already has a result and information cannot continue being edited because it is going back to the facilities.
            ?>
            <table>
                <tr>
                    <td><A HREF='javascript:history.back(-1)'><img src='../img/back.gif' alt='Go Back'/></A></td>
                    <td> | </td>
                    <td><?php
        echo '<div class="notice"><strong>The sample information <u>CANNOT</u> be edited since the sample has a result.</strong></div>';
            ?></td>
                </tr>
            </table>
            <table class="data-table">
                <th colspan="4">SAMPLE DETAILS</th>
                <tr class="even">
                    <td width="300px"><strong>NMRL Stamp Number</strong></td><td width="300px"><?php
                    if ($nmrlstampno == 0) {
                        $nmrlstampno = '';
                    } echo $nmrlstampno;
                    ?></td></tr>
                <tr class="even">
                    <td width="300px"><strong>Sample Request Number</strong></td><td width="300px"><?php echo '<strong>Year</strong> ' . $requestno_year . ' &nbsp;&nbsp;&nbsp;&nbsp;<strong>No</strong> ' . $requestno_no; ?></td></tr>
                <tr class="even">
                    <td width="300px"><strong>Date Collected</strong></td><td width="300px"><?php echo $datecollected; ?></td></tr>
                <tr class="even">
                    <td width="300px"><strong>Date Received</strong></td><td width="300px"><?php echo $datereceived; ?></td></tr>
                <tr class="even">
                    <td width="300px"><strong>Received Status</strong></td><td width="300px"><?php
                    if ($receivedstatus == 2) {
                        $fcolor = '#FF0000';
                    } else if ($receivedstatus == 1) {
                        $fcolor = '#009933';
                    } echo '<font color="' . $fcolor . '">' . $srecstatus . '</font>';
            ?></td></tr>
                <?php
                if ($receivedstatus == 2) { //..show new row indicating the rejected status
                    $rejectedreason = GetRejectedReason($rejectedreason);
                    echo '<tr class="even">
	<td width="300px"><strong>Reason for Rejection</strong></td><td width="300px">$rejectedreason</td></tr>';
                }
                ?>
                <tr class="even">
                    <td width="300px"><strong>Date Tested</strong></td><td width="300px"><?php echo $datetested; ?></td></tr>
                <tr class="even">
                    <td width="300px"><strong>Date Result Updated</strong></td><td width="300px"><?php echo $datemodified; ?></td></tr>
                <tr class="even">
                    <td width="300px"><strong>Date Result Dispatched to Facility</strong></td><td width="300px"><?php echo $datedispatched; ?></td></tr>
                <tr class="even">
                    <td width="300px"><font color="#CC3333"><strong>Result</strong></td><td width="300px">
                        <?php
                        if ($result == 1) { //negative
                            $rcolor = '#009933';
                        } else if ($result == 2) { //positive
                            $rcolor = '#FF0000';
                        } else {
                            $rcolor = '#FF6666';
                        }

                        echo '<strong><font color="' . $rcolor . '">' . $result = GetResultName($result) . '</font></strong>';
                        ?></td></td></tr>
            </table>
            <?php
        }


        include('../includes/footer.php');
        ?>