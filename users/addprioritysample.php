<!DOCTYPE html>
<?php
session_start();
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
include('../includes/header.php');

@$catt = $_GET['catt']; // Use this line or below line if register_global is off
$autocode = $_GET['q'];
$provname = $_GET['r'];
$distname = $_GET['z'];
$success = $_GET['p'];
$userid = $_SESSION['uid'];
$labss = $_GET['labid'];

//Array to store validation errors
$errmsg_arr = array();

//Validation error flag
$errflag = false;

//Function to sanitize values received from the form. Prevents SQL injection
function clean($str) {
    $str = @trim($str);
    if (get_magic_quotes_gpc()) {
        $str = stripslashes($str);
    }
    return mysql_real_escape_string($str);
}

function getmysqldate($date) {
    list($d, $m, $y) = preg_split('/\//', $date);
    $date = sprintf('%4d%02d%02d', $y, $m, $d);
    return date("Y-m-d", strtotime($date));
}

//The form elements
$fcode = $_GET['facility']; //The refering Facility/Hospital/Clinic
$labid = $_GET['labid'];
$mentpoint = $_GET['mentpoint'];
$mhivstatus = $_GET['mhivstatus'];
$mdrug = $_GET['mdrug'];
$mbfeeding = $_GET['mbfeeding'];
$breastfeeding = $_GET['breastfeeding']; //yes, no, unk
$mother_name = mysql_real_escape_string(ucwords($_GET['mother_name']));
$otherentry = strtoupper($_GET['otherentry']);
$receivearv = $_GET['receivearv'];
$onart = $_GET['onart'];
$anc_no = $_GET['anc_no'];
$delivery = $_GET['delivery'];
$samplePriority = $_GET['slctSamplePriority'];
//get patient/child details from the add_sample page
$infant = mysql_real_escape_string(ucwords($_GET['infant']));
$sdob = $_GET['sdob'];
$sdob1 = $_GET['sdob'];
if ($sdob != "") {
    $sdob = getmysqldate($sdob); //date("Y-m-d",strtotime($sdob)); //convert to yy-mm-dd
} else {
    $sdob = "";
}

$infantTestedBefore = $_GET['itestedbefore'];
$infantprophylaxis = $_GET['infantprophylaxis'];
$pgender = $_GET['pgender'];
$requestno_year = $_GET['requestno_year'];
$requestno_no = $_GET['requestno_no'];
$pid = $requestno_year . $requestno_no;
$testedbefore = $_GET['testedbefore'];
$infanthivstatus = $_GET['infanthivstatus'];
$testtype = $_GET['testtype'];
$onctx = $_GET['onctx'];
$originalrequestno_year = $_GET['originalrequestno_year'];
$originalrequestno_no = $_GET['originalrequestno_no'];
$infantarv = $_GET['infantarv'];
$othertest = $_GET['othertest'];

//get sample details from the add_sample page
$sdoc1 = $_GET['sdoc'];
$sdoc = getmysqldate($_GET['sdoc']); //date("Y-m-d",strtotime($sdoc)); //convert to yy-mm-dd
$sdrec1 = $_GET['sdrec'];
$testreason = $_GET['testreason'];
$sdrec = getmysqldate($_GET['sdrec']); //date("Y-m-d",strtotime($sdrec)); //convert to yy-mm-dd
$rej = 1;
$rejectedreason = '';

if (($sdob != "") && ($sdoc != "")) {
    $dob = date("d-m-Y", strtotime($sdob));
    $doc = date("d-m-Y", strtotime($sdoc));
    $agedays = round((strtotime($doc) - strtotime($dob)) / (60 * 60 * 24));
    $agemonths = round(($agedays / 30), 1);
} else {
    $agemonths = 0;
}

$sspot = $_GET['sspot'];
$srecstatus = $_GET['receivedstatus'];
$scomments = $_GET['scomments'];
$labcomment = $_GET['labcomment'];
$province = $_GET['province'];
$district = $_GET['district'];
$agetype = $_GET['agetype'];
$repeatreason = addslashes($_GET['repeatreason']);
$nmrlstampno = $_GET['nmrlstampno'];

//date entered in database
$dateenteredindb = date('Y-m-d');

$task = 4; //add sample found in tasks table ....to be commented later on

function validateDate($value) {
    if (ereg("^(([0-9])|([0-2][0-9])|(3[0-1]))\/(([1-9])|(0[1-9])|(1[0-2]))\/(([0-9][0-9])|([1-2][0,9][0-9][0-9]))$", $value, $regs)) {
        return true;
    } else {
        return false;
    }
}

function greaterDate($start_date, $end_date) {
    $start = $start_date;
    $end = $end_date;

    list($d, $m, $y) = preg_split('/\//', $start);
    $start = sprintf('%4d%02d%02d', $y, $m, $d);

    list($d, $m, $y) = preg_split('/\//', $end);
    $end = sprintf('%4d%02d%02d', $y, $m, $d);


    $s = date("Y-m-d", strtotime($start));

    $e = date("Y-m-d", strtotime($end));

    $sd = strtotime($s);
    $ed = strtotime($e);

    if ($sd - $ed > 0)
        return true;
    else
        return false;
}

function greaterOrEqualDate($start_date, $end_date) {
    $start = $start_date;
    $end = $end_date;

    list($d, $m, $y) = preg_split('/\//', $start);
    $start = sprintf('%4d%02d%02d', $y, $m, $d);

    list($d, $m, $y) = preg_split('/\//', $end);
    $end = sprintf('%4d%02d%02d', $y, $m, $d);


    $s = date("Y-m-d", strtotime($start));

    $e = date("Y-m-d", strtotime($end));

    $sd = strtotime($s);
    $ed = strtotime($e);

    if ($sd - $ed >= 0)
        return true;
    else
        return false;
}

if ($_GET['addOnlyInd'] === "true") {
    if ($rej != 2) {
        $rej = 1;
        $srecstatus = 1;
    }

    if ($rej == 2) {
        $srecstatus = 2;
    }

    $validFacility = getFacilityDetails($fcode);
    if ($fcode == "" || $validFacility == false) {
        $errmsg_arr[] = 'Select available facility';
        $errflag = true;
    }

    if (!greaterOrEqualDate($sdoc1, $sdob1)) {
        $errmsg_arr[] = 'Date collected should be greater or equal to DOB.';
        $errflag = true;
    }

    if (!greaterOrEqualDate($sdrec1, $sdob1)) {
        $errmsg_arr[] = 'Date received should be greater or equal to DOB.';
        $errflag = true;
    }
    if (!greaterOrEqualDate($sdrec1, $sdoc1)) {

        $errmsg_arr[] = 'Date received should be greater or equal to DBS collection date.';
        $errflag = true;
    }

    if (!validateDate($sdoc1)) {
        $errmsg_arr[] = 'Enter Valid Date Collected.';
        $errflag = true;
    }
    if (!validateDate($sdrec1)) {
        $errmsg_arr[] = 'Enter Valid Date Received.';
        $errflag = true;
    }

    //check duplicate req#
    $qry = "SELECT * FROM samples WHERE patient='$pid'";
    $result = mysql_query($qry);
    if ($result) {
        if (mysql_num_rows($result) > 0) {
            $errmsg_arr[] = 'Request No. already in use, enter another one';
            $errflag = true;
        } @mysql_free_result($result);
    } else {
        die("failed");
    }

    //check duplicate nmrl#
    $qry = "SELECT * FROM samples WHERE nmrlstampno='$nmrlstampno'";
    $result = mysql_query($qry);
    if ($result) {
        if (mysql_num_rows($result) > 0) {
            $errmsg_arr[] = 'NMRL stamp No. already in use, enter another one';
            $errflag = true;
        } @mysql_free_result($result);
    } else {
        die("failed");
    }


    if ($errflag) {
        $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    } else {
        $d = GetBatchNoifExists($sdrec, $fcode, $labss); //check if batch no exists where samples were received on the same date from the same facility

        if ($d == 0) {
            $BatchNo = GetNewBatchNo($labss); //capture new batchno
            $mother = GetSavedMother($mhivstatus, $mentpoint, $breastfeeding, $mbfeeding, $mdrug, $fcode, $delivery, $anc_no, $mother_name, $testedbefore, $otherentry, $onart, $receivearv); //capture if mother saved
            $motherid = GetLastMotherID($labss); //get last entred mother record		
            $patient = GetSavedPatient($pid, $motherid, $agemonths, $pgender, $infantarv, $infantprophylaxis, $onctx, $infantTestedBefore, $infanthivstatus, $testtype, $requestno_year, $requestno_no, $originalrequestno_year, $originalrequestno_no, $sdob, $infant); //save patients
            $lastpatientid = GetLastPatientID();


            $sample = GetSavedSamples($lastpatientid, $labss, $BatchNo, $pid, $fcode, $srecstatus, $sspot, $sdoc, $datedispatchedd, $sdrec, $scomments, $labcomment, $parentid, $rejectedreason, $repeatreason, $testreason, $othertest, $dateenteredindb, $userid, $nmrlstampno, $rej, $samplePriority); //save sample
            $completeentry = mysql_query("UPDATE samples SET inputcomplete = 1 WHERE (batchno = '$BatchNo')") or die(mysql_error()); //save complete batch
            $lastSampleid = GetLastSampleID($labss);
            if ($rej == 2)
                $rejectedBatch = mysql_query("UPDATE samples SET BatchComplete = 1, rejectedby = {$userid} WHERE ID = {$lastSampleid}");

            if ($mother && $patient && $sample) { //check if all records entered
                //save user activity
                $tasktime = date("h:i:s a");
                $todaysdate = date("Y-m-d");
                $lastid = GetLastSampleID($labss); //get the patient ID then one can retreave the info saved even though it has been edited
                $utask = 1; //user task = add sample
                $activity = SaveUserActivity($userid, $utask, $tasktime, $lastid, $todaysdate);

                $st = "Sample: " . $pid . " Successfully Added, in Batch " . $BatchNo;
                echo '<script type="text/javascript">';
                echo "window.location.href='sampleslist.php?p=$st'";
                echo '</script>';
            } else {
                $st = "Sample Save Failed, try again ";
            }
        } else if ($d != 0) {
            $BatchNo = GetExistingBatchNo($sdrec, $fcode, $labss); //get alredy exisitin batch no;
            $mother = GetSavedMother($mhivstatus, $mentpoint, $breastfeeding, $mbfeeding, $mdrug, $fcode, $delivery, $anc_no, $mother_name, $testedbefore, $otherentry, $onart, $receivearv); //capture if mother saved
            $motherid = GetLastMotherID($labss); //get last entred mother record
            $patient = GetSavedPatient($pid, $motherid, $agemonths, $pgender, $infantarv, $infantprophylaxis, $onctx, $infantTestedBefore, $infanthivstatus, $testtype, $requestno_year, $requestno_no, $originalrequestno_year, $originalrequestno_no, $sdob, $infant); //save patients
            $lastpatientid = GetLastPatientID();
            $sample = GetSavedSamples($lastpatientid, $labss, $BatchNo, $pid, $fcode, $srecstatus, $sspot, $sdoc, $datedispatchedd, $sdrec, $scomments, $labcomment, $parentid, $rejectedreason, $repeatreason, $testreason, $othertest, $dateenteredindb, $userid, $nmrlstampno, $rej, $samplePriority); //save sample
            $completeentry = mysql_query("UPDATE samples SET inputcomplete = 1 WHERE (batchno = '$BatchNo')") or die(mysql_error()); //save complete batch
            $lastSampleid = GetLastSampleID($labss);
            if ($rej == 2)
                $rejectedBatch = mysql_query("UPDATE samples SET BatchComplete = 1, rejectedby = {$userid} WHERE ID = {$lastSampleid}");

            if ($srecstatus == 2) {
                $task = 6;
                $status = 0;
                $lastid = GetLastSampleID($labss);
                $rejectednotice = SaveRepeatSamplesTask($task, $BatchNo, $status, $lastid, $labss); //save the notice as rejected samples awaitin dispatch
            }

            if ($mother && $patient && $sample) { //check if all records entered
                //save user activity
                $tasktime = date("h:i:s a");
                $todaysdate = date("Y-m-d");
                $lastid = GetLastSampleID($labss); //get the patient ID then one can retreave the info saved even though it has been edited
                $utask = 1; //user task = add sample
                //$activity = SaveUserActivity($userid,$leo,$task,$tasktime,$lastid);
                $activity = SaveUserActivity($userid, $utask, $tasktime, $lastid, $todaysdate);

                $st = "Sample: " . $pid . " Successfully Added, in Batch " . $BatchNo;
                echo '<script type="text/javascript">';
                echo "window.location.href='sampleslist.php?p=$st'";
                echo '</script>';
            } else {
                $st = "Sample Save Failed, try again ";
            }
        }
    }
} else if ($_GET['addSaveInd'] === "true") {
    if ($rej != 2) {
        $rej = 1;
        $srecstatus = 1;
    }
    if ($rej == 2) {
        $srecstatus = 2;
    }
    
    $validFacility = getFacilityDetails($fcode);
    if ($fcode == "" || $validFacility == false) {
        $errmsg_arr[] = 'Select available facility';
        $errflag = true;
    }

    if (!greaterOrEqualDate($sdoc1, $sdob1)) {
        $errmsg_arr[] = 'Date collected should be greater or equal to DOB.';
        $errflag = true;
    }

    if (!greaterOrEqualDate($sdrec1, $sdob1)) {
        $errmsg_arr[] = 'Date received should be greater or equal to DOB.';
        $errflag = true;
    }
    if (!greaterOrEqualDate($sdrec1, $sdoc1)) {
        $errmsg_arr[] = 'Date received should be greater or equal to DBS collection date.';
        $errflag = true;
    }

    if (!validateDate($sdoc1)) {
        $errmsg_arr[] = 'Enter Valid Date Collected.';
        $errflag = true;
    }
    if (!validateDate($sdrec1)) {
        $errmsg_arr[] = 'Enter Valid Date Received.';
        $errflag = true;
    }

    //check duplicate req#
    $qry = "SELECT * FROM samples WHERE patient='$pid'";
    $result = mysql_query($qry);
    if ($result) {
        if (mysql_num_rows($result) > 0) {
            $errmsg_arr[] = 'Request No. already in use, enter another one';
            $errflag = true;
        } @mysql_free_result($result);
    } else {
        die("failed");
    }

    //check duplicate nmrl#
    $qry = "SELECT * FROM samples WHERE nmrlstampno='$nmrlstampno'";
    $result = mysql_query($qry);
    if ($result) {
        if (mysql_num_rows($result) > 0) {
            $errmsg_arr[] = 'NMRL stamp No. already in use, enter another one';
            $errflag = true;
        } @mysql_free_result($result);
    } else {
        die("failed");
    }

    if ($errflag) {
        $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    } else {
        $d = GetBatchNoifExists($sdrec, $fcode, $labss); //check if batch no exists where samples were received on the same date from the same facility

        if ($d == 0) {
            //generate new batch no
            $BatchNo = GetNewBatchNo($labss); //capture new batchno
            $mother = GetSavedMother($mhivstatus, $mentpoint, $breastfeeding, $mbfeeding, $mdrug, $fcode, $delivery, $anc_no, $mother_name, $testedbefore, $otherentry, $onart, $receivearv); //capture if mother saved
            $motherid = GetLastMotherID($labss); //get last entred mother record
            $patient = GetSavedPatient($pid, $motherid, $agemonths, $pgender, $infantarv, $infantprophylaxis, $onctx, $infantTestedBefore, $infanthivstatus, $testtype, $requestno_year, $requestno_no, $originalrequestno_year, $originalrequestno_no, $sdob, $infant); //save patients
            $lastpatientid = GetLastPatientID();
            $sample = GetSavedSamples($lastpatientid, $labss, $BatchNo, $pid, $fcode, $srecstatus, $sspot, $sdoc, $datedispatchedd, $sdrec, $scomments, $labcomment, $parentid, $rejectedreason, $repeatreason, $testreason, $othertest, $dateenteredindb, $userid, $nmrlstampno, $rej, $samplePriority); //save sample
            $completeentry = mysql_query("UPDATE samples SET inputcomplete = 1 WHERE (batchno = '$BatchNo')") or die(mysql_error()); //save sample complete status
            $lastSampleid = GetLastSampleID($labss);
            if ($rej == 2)
                $rejectedBatch = mysql_query("UPDATE samples SET BatchComplete = 1, rejectedby = {$userid} WHERE ID = {$lastSampleid}");

            if ($mother && $patient && $sample) { //check if all records entered
                //save user activity
                $tasktime = date("h:i:s a");
                $todaysdate = date("Y-m-d");
                $lastid = GetLastSampleID($labss); //get the patient ID then one can retreave the info saved even though it has been edited
                $utask = 1; //user task = add sample
                //$activity = SaveUserActivity($userid,$leo,$task,$tasktime,$lastid);
                $activity = SaveUserActivity($userid, $utask, $tasktime, $lastid, $todaysdate);

                $st = "Sample " . $pid . " Successfully Added, in Batch " . $BatchNo;
                echo '<script type="text/javascript">';
                echo "window.location.href='addprioritysample.php?p=$st&q=$fcode&r=$province&z=$district&view=1&labid={$_GET['labid']}'";
                echo '</script>';
            } else {
                $st = "Sample Save Failed, try again ";
            }
        } else {
            $BatchNo = GetExistingBatchNo($sdrec, $fcode, $labss); //get alredy exisitin batch no;
            $mother = GetSavedMother($mhivstatus, $mentpoint, $breastfeeding, $mbfeeding, $mdrug, $fcode, $delivery, $anc_no, $mother_name, $testedbefore, $otherentry, $onart, $receivearv); //capture if mother saved
            $motherid = GetLastMotherID($labss); //get last entred mother record
            $patient = GetSavedPatient($pid, $motherid, $agemonths, $pgender, $infantarv, $infantprophylaxis, $onctx, $infantTestedBefore, $infanthivstatus, $testtype, $requestno_year, $requestno_no, $originalrequestno_year, $originalrequestno_no, $sdob, $infant); //save patients
            $lastpatientid = GetLastPatientID();
            $sample = GetSavedSamples($lastpatientid, $labss, $BatchNo, $pid, $fcode, $srecstatus, $sspot, $sdoc, $datedispatchedd, $sdrec, $scomments, $labcomment, $parentid, $rejectedreason, $repeatreason, $testreason, $othertest, $dateenteredindb, $userid, $nmrlstampno, $rej, $samplePriority); //save sample
            $completeentry = mysql_query("UPDATE samples SET inputcomplete = 1 WHERE (batchno = '$BatchNo')") or die(mysql_error()); //save sample complete status
            $lastSampleid = GetLastSampleID($labss);
            if ($rej == 2)
                $rejectedBatch = mysql_query("UPDATE samples SET BatchComplete = 1, rejectedby = {$userid} WHERE ID = {$lastSampleid}");

            if ($mother && $patient && $sample) { //check if all records entered
                //save user activity
                $tasktime = date("h:i:s a");
                $todaysdate = date("Y-m-d");
                $lastid = GetLastSampleID($labss); //get the patient ID then one can retreave the info saved even though it has been edited
                $utask = 1; //user task = add sample
                //$activity = SaveUserActivity($userid,$leo,$task,$tasktime,$lastid);
                $activity = SaveUserActivity($userid, $utask, $tasktime, $lastid, $todaysdate);

                $st = "Sample: " . $pid . " Successfully Added, in Batch " . $BatchNo;
                echo '<script type="text/javascript">';
                echo "window.location.href='addprioritysample.php?p=$st&q=$fcode&r=$province&z=$district&view=1&labid={$_GET['labid']}'";
                echo '</script>';
            } else {
                $st = "Sample Save Failed, try again ";
            }
        }
    }
}
?>
<!--Styles and Scripts Section-->
<style type="text/css">
    select {
        width: 250;
    }
</style>	
<script>
    window.dhx_globalImgPath = "../img/dhtmlx/imgs/";
</script>
<script type="text/javascript" src="../includes/validatesample.js"></script>
<script src="dhtmlx/dhtmlx.js" type="text/javascript" charset="utf-8"></script>
<script src="dhtmlx/connector.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="dhtmlx/dhtmlx.css" type="text/css"/>
<link rel="stylesheet" href="../includes/validation.css" type="text/css" media="screen" />
<link type="text/css" href="calendar.css" rel="stylesheet" />	
<script src="jquery-ui.min.js"></script>
<link href="base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="demos.css">

<script>
    $(document).ready(function () {
        $("#datecollected").datepicker({dateFormat: 'dd/mm/yy', minDate: "-18M", maxDate: "-1D"});
        $("#datereceived").datepicker({dateFormat: 'dd/mm/yy', minDate: "-28D", maxDate: "+0D"});
        $("#dateofbirth").datepicker({dateFormat: 'dd/mm/yy', minDate: "-50Y", maxDate: "-14D"});

        $("#rejectReason").hide();
        $("#reject").change(function () {
            if ($(this).is(':checked')) {
                $("#rejectReason").show();
            } else
            {
                $("#rejectReason").hide();
            }
        });

        //Filter Provinces and Districts
        $("#province").change(function () {
            $("#provinceDistrictSlct").load('provinceDistrict.php?pID=' + $(this).val(), function () {
                $('select#dist', this).change(function () {
                    $("#districtFacilitySlct").load('districtFacility.php?dID=' + $(this).val(), function () {
                        var facility = $('input.dhx_combo_input', this);
                        var facilityInfo = $('span#facilityInfo', this);
                        //var addonly = $("#addonly");
                        //var saveadd = $("#saveadd");

                        var myarr = <?php
$qy = mysql_query("SELECT name FROM facilitys") or die(mysql_error()); //save sample complete status

while ($row = mysql_fetch_assoc($qy)) {
    $fs[] = $row['name'];
}

echo json_encode($fs);
//echo implode(',', $fs);                                         
?>;
                        facility.blur(validateFacility);
                        facility.keyup(validateFacility);
                        //addonly.click(validateFacility);
                        //saveadd.click(validateFacility);

                        //Facility validation
                        function validateFacility() {
                            //if it's NOT valid
                            if (facility.val().length < 1) {
                                facility.addClass("error");
                                facilityInfo.text("Please enter referring facility!");
                                facilityInfo.addClass("error");
                                return false;
                            } else if (myarr.indexOf(facility.val()) < 0)
                            {
                                facility.addClass("error");
                                facilityInfo.text("The facility name isn't valid!");
                                facilityInfo.addClass("error");
                                return false;
                            }
                            //if it's valid
                            else {
                                facility.removeClass("error");
                                facilityInfo.text("");
                                facilityInfo.removeClass("error");
                                return true;
                            }
                        }
                    });
                });
            });
        });

    });
</script>

<script language="javascript" type="text/javascript">
    // Roshan's Ajax dropdown code with php
    // This notice must stay intact for legal use
    // Copyright reserved to Roshan Bhattarai - nepaliboy007@yahoo.com
    // If you have any problem contact me at http://roshanbh.com.np
    function getXMLHTTP() { //fuction to return the xml http object
        var xmlhttp = false;
        try {
            xmlhttp = new XMLHttpRequest();
        } catch (e) {
            try {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                try {
                    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e1) {
                    xmlhttp = false;
                }
            }
        }

        return xmlhttp;
    }

    function getFeeding(breastfeeding) {

        var strURL = "findARV.php?feeding=" + breastfeeding;
        var req = getXMLHTTP();

        if (req) {

            req.onreadystatechange = function () {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                        document.getElementById('feedstateediv').innerHTML = req.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
    }

    function getTestReason(testreason) {

        var strURL = "findARV.php?reason=" + testreason;
        var req = getXMLHTTP();

        if (req) {

            req.onreadystatechange = function () {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                        document.getElementById('testreasonInfo').innerHTML = req.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
    }

    function getEntry(mentpoint) {

        var strURL = "findARV.php?entry=" + mentpoint;
        var req = getXMLHTTP();

        if (req) {

            req.onreadystatechange = function () {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                        document.getElementById('entrypointInfo').innerHTML = req.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
    }

    function getTested(testedbefore) {

        var strURL = "findARV.php?tested=" + testedbefore;
        var req = getXMLHTTP();

        if (req) {

            req.onreadystatechange = function () {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                        document.getElementById('tstateediv').innerHTML = req.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
    }

    function getInfantTested(itestedbefore) {

        var strURL = "findARV.php?itested=" + itestedbefore;
        var req = getXMLHTTP();

        if (req) {

            req.onreadystatechange = function () {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                        document.getElementById('hstateediv').innerHTML = req.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
    }

    function getARV(infantarv) {

        var strURL = "findARV.php?arv=" + infantarv;
        var req = getXMLHTTP();

        if (req) {

            req.onreadystatechange = function () {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                        document.getElementById('stateediv').innerHTML = req.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
    }

    function getMotherARV(mart) {

        var strURL = "findARV.php?mart=" + mart;
        var req = getXMLHTTP();

        if (req) {

            req.onreadystatechange = function () {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                        document.getElementById('mstateediv').innerHTML = req.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
    }

    function getMotherProph(receivearv) {

        var strURL = "findARV.php?receivearv=" + receivearv;
        var req = getXMLHTTP();

        if (req) {

            req.onreadystatechange = function () {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                        document.getElementById('mprophstateediv').innerHTML = req.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
    }

    function getRejectedreasons(receivedstatus) {

        var strURL = "findRejectedReasons.php?rejid=" + receivedstatus;
        var req = getXMLHTTP();

        if (req) {

            req.onreadystatechange = function () {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                        document.getElementById('statediv').innerHTML = req.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
    }

    function getLabs(labinfo) {

        var separator = labinfo.indexOf("_");
        var labid = labinfo.slice(0, separator);
        var labtype = labinfo.slice(separator + 1, labinfo.length);

        if (labtype == '0') {
            window.location.href = "addsampleswithoutresult.php?view=1&labid=" + labid;
        } else if (labtype == '1') {
            window.location.href = "addsampleswithresult.php?view=1&labid=" + labid;
		} else if (labtype == '4') {
            window.location.href = "addprioritysample.php?view=1&labid=" + labid;
        } else {
            window.location.href = "addsamplesrejected.php?view=1&labid=" + labid;
        }
    }

</script>

<!--Styles and Scripts sections end here-->

<?php
if (isset($_GET['labid'])) {
    $labid = $_GET['labid'];
    $query = "SELECT * FROM labs WHERE ID = {$labid} LIMIT 1";
    $result = mysql_query($query);
    $lab = mysql_fetch_array($result);
}
?>
<div style="width: 100%; text-align: center;">    
    <span>Lab Type:</span>&nbsp;&nbsp;
    <select name="labs" id="labs" onchange="getLabs(this.value)">
        <option value="" >--Select Lab--</option>
        <?php
        $qry = "SELECT * FROM labs";
        $result = mysql_query($qry);
        if ($result) {
            while ($row = mysql_fetch_array($result)) {
                echo "<option value='{$row['ID']}_{$row['withresult']}' ";
                if (isset($_GET['labid'])) {
                    if ($_GET['labid'] == $row['ID'])
                        echo "selected='selected'";
                }
                echo ">{$row['name']}</option>";
            }
        } else {
            die("failed");
        }
        ?>
    </select>
</div>

<!--The Add Sample Section-->
<div class="section">
    <?php
    if (isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) > 0) {
        echo "<table>
                    <tr>
			<td style='width:auto'>
                            <div class='error'>";
        foreach ($_SESSION['ERRMSG_ARR'] as $msg) {
            echo $msg;
            echo "<br/>";
        }
        echo "</div></td></tr></table>";
        unset($_SESSION['ERRMSG_ARR']);
    }
    ?>

    <div class="section-title">ADD SAMPLE</div>
    <div class="xtop">
        <font color="#FF0000"><strong>PLEASE <u>DO NOT ENTER</u> SAMPLES THAT DO NOT HAVE FACILITY NAMES!!!!!!!!!!!!!!!!!!!!</strong></font><div class="error"><strong>The fields indicated asterisk (<span class="mandatory">*</span>) are mandatory. Please do not enter ( <font color='#FF0000'> , . " ; : </font> ) in the infant & Mother names</strong></div>
        <?php
        if ($success != "") {
            ?> 
            <table>
                <tr>
                    <td style="width:auto" >
                        <div class="success">
                            <?php
                            echo '<strong>' . ' <font color="#666600">' . $success . '</strong>' . ' </font>';
                            ?>
                        </div>
                    </td>
                </tr>
            </table>
        <?php } ?>
        <?php
        if ($st != "") {
            ?> 
            <table>
                <tr>
                    <td style="width:auto">
                        <div class="error">
                            <?php
                            echo '<strong>' . ' <font color="#666600">' . $st . '</strong>' . ' </font>';
                            ?>
                        </div>
                    </td>
                </tr>
            </table>
        <?php } ?>

        <form id="customForm" method="get" action="addprioritysample.php">
            <input type="hidden" name="view" value="1"/>
            <input type="hidden" name="labid" value="<?php echo $lab['ID']; ?>"/>
            <table style="border-color:#CCCCCC" width="100%">                	
                <tr>
                    <td colspan="2">
                        <table style="width: 100%;">
                            <tr>
                                <td>
                                    Province&nbsp;&nbsp;
                                    <?php
                                    $query = "SELECT Code,name FROM provinces";

                                    $result = mysql_query($query) or die('Error, query failed');

                                    echo "<select id='province' name='province'>\n";
                                    echo "<option value=''> Select One </option>";

                                    while ($row = mysql_fetch_array($result)) {
                                        $ID = $row['Code'];
                                        $name = $row['name'];
                                        echo "<option value='{$ID}'>{$name}</option>\n";
                                    }
                                    echo "</select>\n";
                                    ?>
                                </td>                                    
                                <td>
                                    <div id="provinceDistrictSlct"></div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="width: 100%;" id="districtFacilitySlct"></div>
                                </td>
                                <td>

                                </td>
                            </tr>
                        </table>			  
                    </td>
                    <td colspan="2">
                        <table>
                            <tr>				
                                <td><span class="mandatory">*</span>Request No</td>
                                <td>
                                    <strong>Year</strong>&nbsp;
                                    <input type="text" name="requestno_year" id="requestno_year" size="5" class="text" value="<?php if (isset($_GET['requestno_year'])) echo $_GET['requestno_year']; ?>"/>&nbsp;
                                    <strong>No</strong>&nbsp;
                                    <input type="text" name="requestno_no" id="requestno_no" size="10" class="text" value="<?php if (isset($_GET['requestno_no'])) echo $_GET['requestno_no']; ?>"/>  
                                    <span id="pidInfo"></span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">
                        <strong>Mother Information</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <table>
                            <tr>
                                <td width="96">Name of Mother</td>
                                <td width="150px">
                                    <input type="text" name="mother_name" id="mother_name" value="<?php if (isset($_GET['mother_name'])) echo $_GET['mother_name']; ?>" class="text" size="32" />
                                    <span id="mother_nameInfo"></span>
                                </td>
                                <td><em>Was the mother tested for HIV before?</em></td>
                                <td> 
                                    <input name="testedbefore" type="radio" value="1" onChange='getTested(this.value)' />
                                    Yes &nbsp;
                                    <input name="testedbefore" type="radio" value="2" onChange='getTested(this.value)' />
                                    No &nbsp;
                                    <input name="testedbefore" type="radio" value="3" onChange='getTested(this.value)' />
                                    No Data &nbsp;
                                </td>
                                <td style="width: 350px;" colspan="3">
                                    <div id="tstateediv"></div>
                                </td>
                            </tr>
                            <tr>
                                <td>Mother's ANC #</td>
                                <td><input type="text" name="anc_no" value="<?php if (isset($_GET['anc_no'])) echo $_GET['anc_no']; ?>" class="text" size="32" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">
                        <strong>Infant Information</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <table>
                            <tr>
                                <td>Infant's Name</td>
                                <td>
                                    <input type="text" name="infant" id="infantName" value="<?php if (isset($_GET['infant'])) echo $_GET['infant']; ?>" class="text" size="32" />
                                    <span id="infantNameInfo"></span>
                                </td>
                                <td>Date of Birth of Infant </td>
                                <td>
                                    <div>
                                        <input id="dateofbirth" type="text" name="sdob" value="<?php if (isset($_GET['sdob'])) echo $_GET['sdob']; ?>" class="text" size="31"/>
                                    </div>
                                    <span id="ageInfo"></span>
                                    <div id="dateofbirth"></div>
                                </td>
                                <td> Sex of baby </td>
                                <td>
                                    <input name="pgender" type="radio" value="M" />
                                    Male&nbsp;
                                    <input name="pgender" type="radio" value="F" />
                                    Female &nbsp;
                                    <input name="pgender" type="radio" value="U" />
                                    No Data</td>
                            </tr>
                            <tr>
                                <td><span class="mandatory">*</span>  Date of taking DBS </td>
                                <td><!--calendar-->
                                    <div>
                                        <input id="datecollected" type="text" name="sdoc" value="<?php if (isset($_GET['sdoc'])) echo $_GET['sdoc']; ?>" class="text"  size="31" />
                                    </div>
                                    <span id="sdocInfo"></span>
                                    <div id="datecollected"></div>			 
                                </td>
                                <td><span class="mandatory">*</span> Mode of Delivery </td>
                                <td>
                                    <div style="height: 41px;">
                                        <?php
                                        $deliveryquery = "SELECT ID,name FROM deliverymode";

                                        $dresult = mysql_query($deliveryquery) or die('Error, query failed'); //onchange='submitForm();'

                                        echo "<select name='delivery' id='delivery' style='width:188px';>\n";
                                        echo " <option value=''>--Select One--</option>";

                                        while ($drow = mysql_fetch_array($dresult)) {
                                            $ID = $drow['ID'];
                                            $name = $drow['name'];
                                            echo "<option value='{$ID}'";
                                            if (isset($_GET['delivery'])) {
                                                if ($_GET['delivery'] == $ID) {
                                                    echo "selected='selected'";
                                                }
                                            }
                                            echo ">{$name}</option>";
                                        }
                                        echo "</select>";
                                        ?>
                                    </div>
                                    <span id="deliveryInfo"></span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">
                        <h5><strong>INFANT PROPHYLAXIS</strong></h5>
                    </td>
                    <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">
                        <h5><strong>MOTHER PMTCT Prophylaxis</strong></h5>
                    </td>
                    <td colspan="4" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">
                        <h5><strong>Infant Testing <em>(Check Child Health Card)</em></strong></h5>
                    </td>
                </tr>
                <tr>
                    <td width="330"><!--INFANT PROPHYLAXIS -->
                        <table>
                            <tr>
                                <td> ARV Prophylaxis given to Infant</td>
                                <td><input name="infantarv" id="infantarv" type="radio" value="1" onChange='getARV(this.value)' />
                                    Yes &nbsp;
                                    <input name="infantarv" id="infantarv" type="radio" value="2" onChange='getARV(this.value)' />
                                    No &nbsp;
                                    <input name="infantarv" id="infantarv" type="radio" value="3" onChange='getARV(this.value)' />
                                    No Data &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td width="150" colspan="2">	
                                    <div><span id="stateediv"></span></div>
                                </td>
                            </tr>
                            <tr>
                                <td>Infant already on <br />CTX prophylaxis? </td>
                                <td>
                                    <input name="onctx" type="radio" value="Y" />
                                    Yes&nbsp;
                                    <input name="onctx" type="radio" value="N" />
                                    No	&nbsp;
                                    <input name="onctx" type="radio" value="U" />
                                    No Data &nbsp;	
                                </td>
                            </tr>
                        </table>
                    </td><!--END INFANT PROPHYLAXIS -->
                    <td style="width: 280px;"> <!--MOTHER PMTCT Prophylaxis -->
                        <table>
                            <tr>
                                <td> Is Mother on ART </td>
                                <td>
                                    <input name="onart" type="radio" value="1" onChange='getMotherARV(this.value)'/>
                                    Yes&nbsp;
                                    <input name="onart" type="radio" value="2" onChange='getMotherARV(this.value)'/>
                                    No &nbsp;
                                    <input name="onart" type="radio" value="3" onChange='getMotherARV(this.value)'/>
                                    No Data &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td width="150" colspan="2">	
                                    <div><span id="mstateediv"></span></div>
                                </td>
                            </tr>
                            <tr>
                                <td width="150" colspan="2">	
                                    <div><span id="mprophstateediv"></span></div>
                                </td>
                            </tr>
                            <tr>
                                <td align="right" colspan="2"><div align="center"><em>(at onset of labor)</em></div></td>
                            </tr>
                        </table>
                    </td>
                    <!--END MOTHER PMTCT Prophylaxis -->
                    <td width="" colspan="4"><!--Infant Testing -->
                        <table>
                            <tr>				
                                <td width="">Was Infant Tested <br />for HIV before?</td>
                                <td width="150">
                                    <input name="itestedbefore" type="radio" value="1" onChange='getInfantTested(this.value)' />
                                    Yes &nbsp;
                                    <input name="itestedbefore" type="radio" value="2" onchange='getInfantTested(this.value)' />					
                                    No	&nbsp;
                                    <input name="itestedbefore" type="radio" value="3" onChange='getInfantTested(this.value)' />
                                    No Data  &nbsp;                                    
                                </td>
                                <td width="450" colspan="4"> 	
                                    <div><span id="hstateediv"></span></div>					
                                </td> 
                            </tr>
                        </table>
                    </td><!--END Infant Testing -->
                </tr>
                <tr>
                    <td colspan="6" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">
                        <h5><strong><span class="mandatory">*</span> Infant Feeding</strong></h5>
                    </td>
                </tr>
                <tr>	
                    <td colspan="6">
                        <table>
                            <tr>
                                <td>
                                    <div>
                                        Infant feeding? &nbsp;
                                        <select name="breastfeeding"  id="breastfeeding" onChange="getFeeding(this.value)">
                                            <option value="">Select</option>
                                            <option value="1">Yes (In the last 6 weeks)</option>
                                            <option value="2">No (In the last 6 weeks)</option>
                                            <option value="4">Never breastfed</option>
                                            <option value="3">No Data</option>
                                        </select>
                                    </div>
                                    <br/>
                                    <span id="mbfeedingInfo"></span>
                                </td>
                                <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">
                                    <strong>Types of Feeding</strong>				
                                </td>
                                <td>
                                    <div><span id="feedstateediv"></span></div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">
                        <h5><strong><span class="mandatory">*</span> Entry Point</strong></h5>
                    </td>
                    <td colspan="4" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">
                        <h5><strong><span class="mandatory">*</span> Reasons for DNA / PCR Test</strong></h5>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table style="height: 100%;">
                            <tr>
                                <td width="700">
                                    <div id="enrtypointradios">
                                        <?php
                                        $d9 = mysql_query("SELECT ID,name FROM entry_points") or die(mysql_error());
                                        $i = 0;
                                        while (list($mepID, $mepname) = mysql_fetch_array($d9)) {
                                            ?>
                                            <input type="radio" name="mentpoint" class="mentpoint" value="<?php echo $mepID; ?>" onChange='getEntry(this.value)' />
                                            <?php echo $mepname; ?>&nbsp;
                                            <?php
                                            $i++;
                                        }
                                        ?>
                                    </div>
                                    <br/>
                                    <span id="entrypointInfo"></span>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td colspan="4">
                        <table>
                            <tr>
                                <td>
                                    <div id="testreasonradios">
                                        <?php
                                        $d11 = mysql_query("SELECT ID,Name FROM testreason") or die(mysql_error());
                                        while (list($tpID, $tpname) = mysql_fetch_array($d11)) {
                                            ?>
                                            <input type="radio" name="testreason" id="testreason" value="<?php echo $tpID; ?>" onChange='getTestReason(this.value)'/>
                                            &nbsp;<?php echo $tpname; ?> &nbsp;
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <br/>
                                    <span id="testreasonInfo"></span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2;">
                        <strong>Sample Information</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table border="1" style="border-color:#CCCCCC">
                            <tr>    
                                <td width="150">
                                    <strong><span class="mandatory">*</span> Date Received</strong>
                                </td>  
                                <td>
                                    <div>
                                        <input id="datereceived" type="text" name="sdrec" value="<?php if (isset($_GET['sdrec'])) echo $_GET['sdrec']; ?>" class="text" size="31"/>
                                        <span id="sdrecInfo"></span>
                                    </div>
                                    <div id="datereceived"></div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    
                </tr>
                <tr>
                    <td colspan="2">
                        <table>
                            <tr>				
                                <td><span class="mandatory">*</span>Request No</td>
                                <td>
                                    <strong>Year</strong>&nbsp;
                                    <input type="text" name="requestno_year2" id="requestno_year2" size="5" class="text" value="<?php if (isset($_GET['requestno_year'])) echo $_GET['requestno_year']; ?>"/>&nbsp;
                                    <strong>No</strong>&nbsp;
                                    <input type="text" name="requestno_no2" id="requestno_no2" size="10" class="text" value="<?php if (isset($_GET['requestno_no'])) echo $_GET['requestno_no']; ?>"/>  
                                    <span id="confirmReqNo"></span>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td colspan="2">
                        <table>
                            <tr>
                                <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">
                                    <div class="notice"><strong><?php echo $lab['name']; ?> STAMP NUMBER</strong></div>
                                </td>
                                <td colspan="6" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">
                                    <div>
                                        <strong><input type="text" name="nmrlstampno" id="nmrlstampno" class="text" size="45" value="<?php if (isset($_GET['nmrlstampno'])) echo $_GET['nmrlstampno']; ?>" /></strong>
                                        <span id="nmrlstampnoInfo"></span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>

                </tr>
                <tr>
                    <td colspan="7">
                        <div class="notice" style="font-weight: bold;">
                            <font color="#FF0000">*******</font> PLEASE CONFIRM ALL THE DETAILS ENTERED INTO THE SYSTEM <u><font color="#FF0000">BEFORE SAVNG</font></u><span>!!!!!!!!</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <div style="font-weight: bold;" align="center">
                            <input name="addonly" id="addonly" type="button" class="button" value="Save & Release Sample" style="width:400px; height:30px" />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="hidden" id="addOnlyInd" name="addOnlyInd" value="false"/>
                            <input name="saveadd" id="saveadd" type="submit" class="button" value="Save & Add Sample [Same Batch from Facility]" style="width:400px; height:30px" />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="hidden" id="addSaveInd" name="addSaveInd" value="false"/>
                            <input name="reset" id="reset" type="reset" class="button" value="Reset" style="width:400px; height:30px" />
                            <!-- -->	
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<!--The Add Sample Section ends here-->
<?php include('../includes/footer.php'); ?>