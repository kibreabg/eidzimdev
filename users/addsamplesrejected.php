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
$labss = 1;

//Array to store validation errors
$errmsg_arr = array();

//Validation error flag
$errflag = false;

//The form elements
$fcode = $_GET['facility']; //The refering Facility/Hospital/Clinic
$labid = $_GET['labid'];
$requestno_year = $_GET['requestno_year'];
$requestno_no = $_GET['requestno_no'];
$pid = $requestno_year . $requestno_no;
$originalrequestno_year = $_GET['originalrequestno_year'];
$originalrequestno_no = $_GET['originalrequestno_no'];
$mother_name = mysql_real_escape_string(ucwords($_GET['mother_name']));
$infant = mysql_real_escape_string(ucwords($_GET['infant']));
$province = $_GET['province'];
$district = $_GET['district'];
$nmrlstampno = $_GET['nmrlstampno'];
$sdrec = getmysqldate($_GET['sdrec']); //date("Y-m-d",strtotime($sdrec)); //convert to yy-mm-dd
$rej = 2; //This is an already rejected sample
$rejectedreason = $_GET['slctRejectedReason'];
//date entered in database
$dispatchDate = date('Y-m-d');
$dateenteredindb = date('Y-m-d');

$task = 4; //add sample found in tasks table ....to be commented later on
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

if ($fcode == "") {
    $errmsg_arr[] = 'Select Facility';
    $errflag = true;
}

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

if (isset($_GET['saveadd'])) {
    if ($rej != 2) {
        $rej = 1;
        $srecstatus = 1;
    }
    if ($rej == 2) {
        $srecstatus = 2;
    }

    /* if (!validateDate($sdrec)) {
      $errmsg_arr[] = 'Enter Valid Date Received.';
      $errflag = true;
      } */

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
            if ($rej == 2) {
                $rejectedBatch = mysql_query("UPDATE samples SET BatchComplete = 1, resultprinted = 0, datedispatched = '{$dispatchDate}', rejectedby = {$userid} WHERE ID = {$lastSampleid}");
            }

            if ($sample) { //check if all records entered
                //save user activity
                $tasktime = date("h:i:s a");
                $todaysdate = date("Y-m-d");
                $lastid = GetLastSampleID($labss); //get the patient ID then one can retreave the info saved even though it has been edited
                $utask = 6; //user task = add sample
                //$activity = SaveUserActivity($userid,$leo,$task,$tasktime,$lastid);
                $activity = SaveUserActivity($userid, $utask, $tasktime, $lastid, $todaysdate);

                $st = "Sample " . $pid . " Successfully Added, in Batch " . $BatchNo;
                echo '<script type="text/javascript">';
                echo "window.location.href='addsamplesrejected.php?p=$st&q=$fcode&r=$province&z=$district&view=1&labid={$_GET['labid']}'";
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
            if ($rej == 2) {
                $rejectedBatch = mysql_query("UPDATE samples SET BatchComplete = 1, resultprinted = 0, datedispatched = '{$dispatchDate}', rejectedby = {$userid} WHERE ID = {$lastSampleid}");
            }
            if ($sample) { //check if all records entered
                //save user activity
                $tasktime = date("h:i:s a");
                $todaysdate = date("Y-m-d");
                $lastid = GetLastSampleID($labss); //get the patient ID then one can retreave the info saved even though it has been edited
                $utask = 6; //user task = add sample
                //$activity = SaveUserActivity($userid,$leo,$task,$tasktime,$lastid);
                $activity = SaveUserActivity($userid, $utask, $tasktime, $lastid, $todaysdate);

                $st = "Sample: " . $pid . " Successfully Added, in Batch " . $BatchNo;
                echo '<script type="text/javascript">';
                echo "window.location.href='addsamplesrejected.php?p=$st&q=$fcode&r=$province&z=$district&view=1&labid={$_GET['labid']}'";
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
<script type="text/javascript" src="../includes/validaterejsample.js"></script>
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

        //Filter Provinces and Districts
        $("#province").change(function () {
            $("#provinceDistrictSlct").load('provinceDistrict.php?pID=' + $(this).val(), function () {
                $('select#dist', this).change(function () {
                    $("#districtFacilitySlct").load('districtFacility.php?dID=' + $(this).val(), function () {
                        var facility = $('input.dhx_combo_input', this);
                        var facilityInfo = $('span#facilityInfo', this);
                        //var addonly = $("#addonly");

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

    function getLabs(labinfo) {

        var separator = labinfo.indexOf("_");
        var labid = labinfo.slice(0, separator);
        var labtype = labinfo.slice(separator + 1, labinfo.length);

        if (labtype == '0') {
            window.location.href = "addsampleswithoutresult.php?view=1&labid=" + labid;
        } else if (labtype == '1') {
            window.location.href = "addsampleswithresult.php?view=1&labid=" + labid;
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

        <form id="customForm" method="get" action="addsamplesrejected.php">
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
                        <strong>Mother & Infant Information</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <table>
                            <tr>
                                <td>Name of Mother</td>
                                <td>
                                    <input type="text" name="mother_name" value="<?php if (isset($_GET['mother_name'])) echo $_GET['mother_name']; ?>" class="text" size="32" />
                                </td>
                                <td>Infant's Name</td>
                                <td>
                                    <input type="text" name="infant" value="<?php if (isset($_GET['infant'])) echo $_GET['infant']; ?>" class="text" size="32" />
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
                                <td>
                                    <div>
                                        <strong><span style="color: Red;">*</span> Date Received</strong>
                                        <input id="datereceived" type="text" name="sdrec" value="<?php if (isset($_GET['sdrec'])) echo $_GET['sdrec']; ?>" class="text"/>
                                        <span id="sdrecInfo"></span>
                                    </div>
                                    <div id="datereceived"></div>
                                </td>
                                <td>
                                    <div id="rejectReason">
                                        <strong><span style="color: Red;">*</span>Rejected Reason</strong>
                                        <select id="slctRejectedReason" name="slctRejectedReason">
                                            <?php
                                            $query = "SELECT * FROM rejectedreasons";
                                            $rejectedReasons = mysql_query($query) or die('Error, query failed');

                                            echo " <option value=''>--Select One--</option>";

                                            while ($rejectedReason = mysql_fetch_array($rejectedReasons)) {
                                                $ID = $rejectedReason['ID'];
                                                $reason = $rejectedReason['Name'];
                                                echo "<option value='{$ID}'";
                                                if (isset($_GET['rejectedReason'])) {
                                                    if ($_GET['rejectedReason'] == $ID) {
                                                        echo "selected='selected'";
                                                    }
                                                }
                                                echo ">{$reason}</option>";
                                            }
                                            ?>
                                        </select>    
                                        <span id="rejectedreasonInfo"></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
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
                            <input name="saveadd" id="saveadd" type="submit" class="button" value="Save & Add Sample [Same Batch from Facility]" style="width:400px; height:30px" />
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