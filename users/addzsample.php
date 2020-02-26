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
//$labss=$_SESSION['lab'];
$labss = '2'; //zvitambo
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

$fcode = $_GET['cat']; //facility
$mentpoint = $_GET['mentpoint'];
$mhivstatus = $_GET['mhivstatus'];
$mdrug = $_GET['mdrug'];
$mbfeeding = $_GET['mbfeeding'];
$breastfeeding = $_GET['breastfeeding']; //yes, no, unk
$mother_name = $_GET['mother_name'];
$testedbefore = $_GET['testedbefore'];
$otherentry = strtoupper($_GET['otherentry']);
$receivearv = $_GET['receivearv'];
$onart = $_GET['onart'];
$anc_no = $_GET['anc_no'];
$delivery = $_GET['delivery'];
//get patient/child details from the add_sample page
$infant = $_GET['infant'];
$sdob = $_GET['sdob'];
$sdob = date("Y-m-d", strtotime($sdob)); //convert to yy-mm-dd
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
$infantresult = $_GET['infantresult'];

//get sample details from the add_sample page
$sdoc = $_GET['sdoc'];
$sdoc = date("Y-m-d", strtotime($sdoc)); //convert to yy-mm-dd
$sdrec = $_GET['sdrec'];
$testreason = $_GET['testreason'];
$sdrec = date("Y-m-d", strtotime($sdrec)); //convert to yy-mm-dd

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
$rejectedreason = addslashes($_GET['rejectedreason']);
$scomments = $_GET['scomments'];
$labcomment = $_GET['labcomment'];
$province = $_GET['province'];
$district = $_GET['district'];
$agetype = $_GET['agetype'];
$repeatreason = addslashes($_GET['repeatreason']);
$dateenteredindb = date('Y-m-d');
$loggedinby = $_SESSION['uid'];

$task = 4; //add sample found in tasks table ....to be commented later on

if ($fcode == "") {
    $errmsg_arr[] = 'Select Facility';
    $errflag = true;
}


if ($_REQUEST['addonly']) {
    if ($errflag) {
        $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    } else {
        $d = GetBatchNoifExists($sdrec, $fcode, $labss); //check if batch no exists where samples were received on the same date from the same facility

        if ($d == 0) {
            $BatchNo = GetNewBatchNo($labss); //capture new batchno
            $mother = GetSavedMother($mhivstatus, $mentpoint, $breastfeeding, $mbfeeding, $mdrug, $fcode, $delivery, $anc_no, $mother_name, $testedbefore, $otherentry, $onart, $receivearv); //capture if mother saved
            $motherid = GetLastMotherID($labss); //get last entred mother record		
            $patient = GetSavedPatient($pid, $motherid, $agemonths, $pgender, $infantarv, $infantprophylaxis, $onctx, $testedbefore, $infanthivstatus, $testtype, $requestno_year, $requestno_no, $originalrequestno_year, $originalrequestno_no, $sdob, $infant); //save patients
            $lastpatientid = GetLastPatientID();
            $sample = GetZSavedSamples($lastpatientid, $labss, $BatchNo, $pid, $fcode, $srecstatus, $sspot, $sdoc, $datedispatchedd, $sdrec, $scomments, $labcomment, $parentid, $rejectedreason, $repeatreason, $testreason, $othertest, $infantresult, $inputcomplete, $approved, $dateenteredindb, $loggedinby); //save sample
            /* if ($srecstatus == 2)
              {
              $task=6;
              $status=0;
              $lastid=GetLastSampleID($labss);
              $rejectednotice = SaveRepeatSamplesTask($task,$BatchNo,$status,$lastid,$labss); //save the notice as rejected samples awaitin dispatch
              } */

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
                echo "window.location.href='sampleslistz.php?p=$st'";
                echo '</script>';
            } else {
                $st = "Sample Save Failed, try again ";
            }
        } else if ($d != 0) {
            $BatchNo = GetExistingBatchNo($sdrec, $fcode, $labss); //get alredy exisitin batch no;
            $mother = GetSavedMother($mhivstatus, $mentpoint, $breastfeeding, $mbfeeding, $mdrug, $fcode, $delivery, $anc_no, $mother_name, $testedbefore, $otherentry, $onart, $receivearv); //capture if mother saved
            $motherid = GetLastMotherID($labss); //get last entred mother record
            $patient = GetSavedPatient($pid, $motherid, $agemonths, $pgender, $infantarv, $infantprophylaxis, $onctx, $testedbefore, $infanthivstatus, $testtype, $requestno_year, $requestno_no, $originalrequestno_year, $originalrequestno_no, $sdob, $infant); //save patients
            $lastpatientid = GetLastPatientID();
            $sample = GetZSavedSamples($lastpatientid, $labss, $BatchNo, $pid, $fcode, $srecstatus, $sspot, $sdoc, $datedispatchedd, $sdrec, $scomments, $labcomment, $parentid, $rejectedreason, $repeatreason, $testreason, $othertest, $infantresult, $inputcomplete, $approved, $dateenteredindb, $loggedinby); //save sample
            /* if ($srecstatus==2)
              {
              $task=6;
              $status=0;
              $lastid=GetLastSampleID($labss);
              $rejectednotice = SaveRepeatSamplesTask($task,$BatchNo,$status,$lastid,$labss); //save the notice as rejected samples awaitin dispatch
              } */

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
                echo "window.location.href='sampleslistz.php?p=$st'";
                echo '</script>';
            } else {
                $st = "Sample Save Failed, try again ";
            }
        }
    }
} else if ($_REQUEST['saveadd']) {

    if ($errflag) {
        $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    } else {
        $d = GetBatchNoifExists($sdrec, $fcode, $labss); //check if batch no exists where samples were received on the same date from the same facility

        if ($d == 0) {
            //generate new batch no
            $BatchNo = GetNewBatchNo($labss); //capture new batchno
            $mother = GetSavedMother($mhivstatus, $mentpoint, $breastfeeding, $mbfeeding, $mdrug, $fcode, $delivery, $anc_no, $mother_name, $testedbefore, $otherentry, $onart, $receivearv); //capture if mother saved
            $motherid = GetLastMotherID($labss); //get last entred mother record
            $patient = GetSavedPatient($pid, $motherid, $agemonths, $pgender, $infantarv, $infantprophylaxis, $onctx, $testedbefore, $infanthivstatus, $testtype, $requestno_year, $requestno_no, $originalrequestno_year, $originalrequestno_no, $sdob, $infant); //save patients
            $lastpatientid = GetLastPatientID();
            $sample = GetZSavedSamples($lastpatientid, $labss, $BatchNo, $pid, $fcode, $srecstatus, $sspot, $sdoc, $datedispatchedd, $sdrec, $scomments, $labcomment, $parentid, $rejectedreason, $repeatreason, $testreason, $othertest, $infantresult, $inputcomplete, $approved, $dateenteredindb, $loggedinby); //save sample

            /* if ($srecstatus==2)
              {
              $task=6;
              $status=0;
              $lastid=GetLastSampleID($labss);
              $rejectednotice = SaveRepeatSamplesTask($task,$BatchNo,$status,$lastid,$labss); //save the notice as rejected samples awaitin dispatch
              } */

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
                echo "window.location.href='addzsample.php?p=$st&q=$fcode&r=$province&z=$district&view=1'";
                echo '</script>';
            } else {
                $st = "Sample Save Failed, try again ";
            }
        } else {
            $BatchNo = GetExistingBatchNo($sdrec, $fcode, $labss); //get alredy exisitin batch no;
            $mother = GetSavedMother($mhivstatus, $mentpoint, $breastfeeding, $mbfeeding, $mdrug, $fcode, $delivery, $anc_no, $mother_name, $testedbefore, $otherentry, $onart, $receivearv); //capture if mother saved
            $motherid = GetLastMotherID($labss); //get last entred mother record
            $patient = GetSavedPatient($pid, $motherid, $agemonths, $pgender, $infantarv, $infantprophylaxis, $onctx, $testedbefore, $infanthivstatus, $testtype, $requestno_year, $requestno_no, $originalrequestno_year, $originalrequestno_no, $sdob, $infant); //save patients
            $lastpatientid = GetLastPatientID();
            $sample = GetZSavedSamples($lastpatientid, $labss, $BatchNo, $pid, $fcode, $srecstatus, $sspot, $sdoc, $datedispatchedd, $sdrec, $scomments, $labcomment, $parentid, $rejectedreason, $repeatreason, $testreason, $othertest, $infantresult, $inputcomplete, $approved, $dateenteredindb, $loggedinby); //save sample
            /* if ($srecstatus==2)
              {
              $task=6;
              $status=0;
              $lastid=GetLastSampleID($labss);
              $rejectednotice = SaveRepeatSamplesTask($task,$BatchNo,$status,$lastid,$labss); //save the notice as rejected samples awaitin dispatch
              } */

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
                echo "window.location.href='addzsample.php?p=$st&q=$fcode&r=$province&z=$district&view=1'";
                echo '</script>';
            } else {
                $st = "Sample Save Failed, try again ";
            }
        }
    }
}
?>


<style type="text/css">
    select {
        width: 250;}
    </style>	
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
   <!-- <script>
    $(document).ready(function() {
     // $("#dob").datepicker();
          $( "#dob" ).datepicker({ minDate: "-18M", maxDate: "-28D" });
          });
  
  
  //  });
    </script> -->
    <script>
        $(document).ready(function() {
            // $("#datecollected").datepicker();
            $( "#datecollected" ).datepicker({ minDate: "-18M", maxDate: "-1D" });

        });
    </script>
    <script>
        $(document).ready(function() {
            // $("#datedispatched").datepicker();
            $( "#datereceived" ).datepicker({ minDate: "-70D", maxDate: "+0D" });
        });
    </script>
    <script>
        $(document).ready(function() {
            // $("#datedispatched").datepicker();
            $( "#dateofbirth" ).datepicker({ minDate: "-18M", maxDate: "-14D" });

        });
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
	
        function getFeeding(breastfeeding) {		
		
            var strURL="findARV.php?feeding="+breastfeeding;
            var req = getXMLHTTP();
		
            if (req) {
			
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        // only if "OK"
                        if (req.status == 200) {						
                            document.getElementById('feedstateediv').innerHTML=req.responseText;						
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
		
            var strURL="findARV.php?reason="+testreason;
            var req = getXMLHTTP();
		
            if (req) {
			
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        // only if "OK"
                        if (req.status == 200) {						
                            document.getElementById('teststateediv').innerHTML=req.responseText;						
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
		
            var strURL="findARV.php?entry="+mentpoint;
            var req = getXMLHTTP();
		
            if (req) {
			
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        // only if "OK"
                        if (req.status == 200) {						
                            document.getElementById('estateediv').innerHTML=req.responseText;						
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
		
            var strURL="findARV.php?tested="+testedbefore;
            var req = getXMLHTTP();
		
            if (req) {
			
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        // only if "OK"
                        if (req.status == 200) {						
                            document.getElementById('tstateediv').innerHTML=req.responseText;						
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
		
            var strURL="findARV.php?itested="+itestedbefore;
            var req = getXMLHTTP();
		
            if (req) {
			
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        // only if "OK"
                        if (req.status == 200) {						
                            document.getElementById('hstateediv').innerHTML=req.responseText;						
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
		
            var strURL="findARV.php?arv="+infantarv;
            var req = getXMLHTTP();
		
            if (req) {
			
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        // only if "OK"
                        if (req.status == 200) {						
                            document.getElementById('stateediv').innerHTML=req.responseText;						
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
		
            var strURL="findARV.php?mart="+mart;
            var req = getXMLHTTP();
		
            if (req) {
			
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        // only if "OK"
                        if (req.status == 200) {						
                            document.getElementById('mstateediv').innerHTML=req.responseText;						
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
		
            var strURL="findARV.php?receivearv="+receivearv;
            var req = getXMLHTTP();
		
            if (req) {
			
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        // only if "OK"
                        if (req.status == 200) {						
                            document.getElementById('mprophstateediv').innerHTML=req.responseText;						
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
    <div class="section-title">ADD ZVITAMBO SAMPLE</div>
    <div class="xtop">
        <table>
            <tr><td><div class="notice"><strong>THIS PAGE IS FOR <u>ZVITAMBO SAMPLE ENTRY ONLY.</u></strong></div></td>
            </tr>
        </table>
        <A HREF="javascript:history.back(-1)"><img src="../img/back.gif" alt="Go Back"/></A> | The fields indicated asterisk (<span class="mandatory">*</span>) are mandatory.
<?php
if (isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) > 0) {
    echo '<table>
				  <tr>
					<td style="width:auto" ><div class="error">';
    foreach ($_SESSION['ERRMSG_ARR'] as $msg) {
        echo $msg;
    }
    echo '</div></td>
				  </tr>
				</table>';
    unset($_SESSION['ERRMSG_ARR']);
}
?>
<?php
if ($success != "") {
    ?> 
            <table   >
                <tr>
                    <td style="width:auto" ><div class="success"><?php
    echo '<strong>' . ' <font color="#666600">' . $success . '</strong>' . ' </font>';
    ?></div></th>
                </tr>
            </table>
<?php } ?>
<?php
if ($st != "") {
    ?> 
            <table   >
                <tr>
                    <td style="width:auto" ><div class="error"><?php
    echo '<strong>' . ' <font color="#666600">' . $st . '</strong>' . ' </font>';
    ?></div></th>
                </tr>
            </table>
<?php } ?>

        <form id="customForm" method="get" action="" >

            <table border="1" style="border-color:#CCCCCC" width="100%">
                <tr>
                    <td colspan="2">
                        <table>
                            <tr>
                                <td width=""><span class="mandatory">*</span> Referring Clinic / Hospital Name </td>
                                <td colspan=""><div>  	<?php
if ($autocode != "") {
    $facilityname = GetFacility($autocode);
    ?>
                                            <div class="notice">
    <?php
    echo '<strong>' . $facilityname . '</strong>';
    echo"<input name='cat' type='hidden' value='$autocode' />";
    ?>
                                            </div>
            <?php
        } else {
            ?>	
                                            <select  style="width:262px"  id='cat' name="cat">
                                            </select>
                                            <script>
                                                var combo = dhtmlXComboFromSelect("cat");
                                                combo.enableFilteringMode(true,"02_sql_connector.php",true);
                                            </script>
            <?php }
        ?>
                                        <br>	<span id='codeInfo'></span></div></td>
                            </tr>
                        </table>			  </td>

                    <td colspan="2">
                        <table>
                            <tr>				
                                <td><span class="mandatory">*</span> Request No</td>
                                <td>
                                    <strong>Year</strong>&nbsp;<input type="text" name="requestno_year" size="5" class="text" id="" value=""/>&nbsp;
                                    <strong>No</strong>&nbsp;<input type="text" name="requestno_no" size="10" class="text" id="" value=""/></td>
                            </tr>
                        </table>			  </td>
                    <td colspan="2">
                        <table>
                            <tr>	
                                <td colspan="" width="200"><div align="center"><img src="../img/lab_logo.png" alt="" /></div></td>
                            </tr>
                        </table>			</td>
                </tr>

                <tr>
                    <td colspan="6" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2"><strong>Mother Information</strong></td>
                </tr>
                <tr>
                    <td colspan="6">
                        <table>
                            <tr>
                                <td width="96">Name of Mother</td>
                                <td width="150px"><input type="text" name="mother_name" class="text" size="32" /></td>
                                <td><em>Was the mother tested for HIV before?</em></td>
                                <td> 
                                    <input name="testedbefore" type="radio" value="1" onChange='getTested(this.value)' />
                                    Yes&nbsp;
                                    <input name="testedbefore" type="radio" value="2" onChange='getTested(this.value)' />
                                    No	&nbsp;
                                    <input name="testedbefore" type="radio" value="3" onChange='getTested(this.value)' />
                                    Unk	&nbsp;</td>

                                <td width="350" colspan="3">
                                    <div id="tstateediv"></div>
                                </td>

                            </tr>

                            <tr>
                                <td>Mother's ANC #</td>
                                <td><input type="text" name="anc_no" class="text" size="32" /></td>
                            </tr>
                        </table>	  	</td>
                </tr>

                <tr>
                    <td colspan="6" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2"><strong>Infant Information</strong></td>
                </tr>

                <tr>
                    <td colspan="6">
                        <table>
                            <tr>
                                <td>Infant's Name </td>
                                <td><input type="text" name="infant" class="text" size="32" /></td>
                                <td><span class="mandatory">*</span> Date of Birth of Infant </td>
                                <td><div><input id="dateofbirth" type="text" name="sdob" class="text"  size="31" ><span id=""></span></div><div type="text" id="dateofbirth"></div></td>
                                <td> Sex of baby </td>
                                <td colspan=""><input name="pgender" type="radio" value="M" />
                                    Male&nbsp;
                                    <input name="pgender" type="radio" value="F" />
                                    Female &nbsp;
                                    <input name="pgender" type="radio" value="U" />
                                    Unknown</td>
                            </tr>

                            <tr>
                                <td><span class="mandatory">*</span> Date of taking DBS </td>
                                <td><!--calendar--><div>
                                        <p> <input id="datecollected" type="text" name="sdoc" class="text"  size="31" ><span id="sdocInfo"></span></div></p>
                                    <div type="text" id="datecollected"></div>			 </td>
                                <td><span class="mandatory">*</span> Mode of Delivery </td>
                                <td colspan=""><?php
        $deliveryquery = "SELECT ID,name FROM deliverymode";

        $dresult = mysql_query($deliveryquery) or die('Error, query failed'); //onchange='submitForm();'

        echo "<select name='delivery' id='delivery' style='width:188px';>\n";
        echo " <option value=''> Select One </option>";

        while ($drow = mysql_fetch_array($dresult)) {
            $ID = $drow['ID'];
            $name = $drow['name'];
            echo "<option value='$ID'> $name</option>\n";
        }
        echo "</select>\n";
        ?></td>
                            </tr>
                        </table>	  </td>
                </tr>

                <tr>
                    <td colspan="" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2"><strong><h5>INFANT PROPHYLAXIS</h5></strong></td>
                    <td colspan="" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2"><strong><h5>MOTHER PMTCT Prophylaxis</h5></strong></td>
                    <td colspan="4" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2"><strong><h5>Infant Testing <em>(Check Child Health Card)</em></h5></strong></td>
                </tr>

                <tr>
                    <td width="300" > <!--INFANT PROPHYLAXIS -->
                        <table>
                            <tr>
                                <td > ARV Prophylaxis given to Infant</td>
                                <td colspan=""><input name="infantarv" id="infantarv" type="radio" value="1" onChange='getARV(this.value)' />
                                    Yes&nbsp;
                                    <input name="infantarv" id="infantarv" type="radio" value="2" onChange='getARV(this.value)' />
                                    No 
                                    &nbsp;
                                    <input name="infantarv" id="infantarv" type="radio" value="3" onChange='getARV(this.value)' />
                                    Unk</td>
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
                                    Unk	</td>
                            </tr>
                        </table>		</td><!--END INFANT PROPHYLAXIS -->

                    <td colspan="" width="280"> <!--MOTHER PMTCT Prophylaxis -->
                        <table>
                            <tr>
                                <td> Is Mother on ART </td>
                                <td colspan="">
                                    <input name="onart" type="radio" value="1" onChange='getMotherARV(this.value)'/>
                                    Yes&nbsp;
                                    <input name="onart" type="radio" value="2" onChange='getMotherARV(this.value)'/>
                                    No &nbsp;
                                    <input name="onart" type="radio" value="3" onChange='getMotherARV(this.value)'/>
                                    Unk</td>
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
                        </table></td>
                    <!--END MOTHER PMTCT Prophylaxis -->

                    <td width="" colspan="4"><!--Infant Testing -->
                        <table>
                            <tr>				
                                <td width="">Was Infant Tested <br />for HIV before?</td>
                                <td width="130">
                                    <input name="itestedbefore" type="radio" value="1" onChange='getInfantTested(this.value)' />
                                    Yes&nbsp;
                                    <input name="itestedbefore" type="radio" value="2" onchange='getInfantTested(this.value)' />					
                                    No	&nbsp;
                                    <input name="itestedbefore" type="radio" value="3" onChange='getInfantTested(this.value)' />
                                    Unk  </td>

                                <td width="450" colspan="4"> 	
                                    <div><span id="hstateediv"></span></div>					</td> 
                            </tr>
                        </table>		</td><!--END Infant Testing -->
                </tr>	  

                <tr>
                    <td colspan="6" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2"><strong><h5><span class="mandatory">*</span> Infant Feeding</h5></strong></td></tr>

                <tr>	
                    <td colspan="6">
                        <table>
                            <td>
                                Infant breastfed in the last 6 weeks &nbsp;
                                <select name="breastfeeding" onChange='getFeeding(this.value)'>
                                    <option value="0">Select</option>
                                    <option value="1" >Yes</option>
                                    <option value="2" >No</option>
                                    <option value="3" >Unk</option>
                                </select>
                            </td>
                            <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2"><strong>
                                    Types of Feeding	</strong>				
                            </td>
                            <td>
                                <div><span id="feedstateediv"></span></div>
                            </td>
                </tr>
            </table>		</td>
            </tr>        

            <tr>
                <td colspan="2" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2"><strong><h5><span class="mandatory">*</span> Entry Point</h5></strong></td>
                <td colspan="4" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2"><strong><h5><span class="mandatory">*</span> Reasons for DNA / PCR Test</h5></strong></td>
            </tr>
            <tr>
                <td colspan="2"><table>
                        <tr>
                            <td colspan="" width="700"><?php
        $d9 = mysql_query("SELECT ID,name FROM entry_points") or die(mysql_error());
        while (list($mepID, $mepname) = mysql_fetch_array($d9)) {
            ?>
                                    <input type="radio" name="mentpoint" id="mentpoint" value="<?php echo $mepID; ?>" onChange='getEntry(this.value)' />
    <?php echo $mepname; ?>&nbsp;
    <?php
}
?>
                                <p>
                                <div><span id="estateediv"></span></div>
                                </p></td>
                        </tr>
                    </table>		</td>
                <td colspan="4">
                    <table>
                        <tr>
                            <td >
<?php
$d11 = mysql_query("SELECT ID,Name FROM testreason") or die(mysql_error());
while (list($tpID, $tpname) = mysql_fetch_array($d11)) {
    ?>
                                 <!-- <input type="radio" name="testreason[]" id="testreason[]" value="<?php echo $tpID; ?>" onChange='getTestReason(this.value)'/> -->
                                    <input type="radio" name="testreason" id="testreason" value="<?php echo $tpID; ?>" onChange='getTestReason(this.value)'/>
                                    &nbsp;<?php echo $tpname; ?> &nbsp;
    <?php
}
?>
                                <p>
                                <div><span id="teststateediv"></span></div>
                                </p></td>
                        </tr>
                    </table>		</td>
            </tr>

            <tr>
                <td colspan="6" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2"><strong>Sample Information</strong></td>
            </tr>

            <tr>
                <td colspan="6">
                    <table border="1" style="border-color:#CCCCCC">
                        <tr>    
                            <td width="150"><strong><span class="mandatory">*</span> Date Received</strong> </td>

                            <td><div>
                                    <p><input id="datereceived" type="text" name="sdrec" class="text"  size="31" >
                                        <span id="sdrecInfo"></span></p>
                                </div>
                                <div type="text" id="datereceived"></div>

                            </td>  
                            <td width="150"><strong><span class="mandatory">*</span> Result</strong> </td>

                            <td>
<?php
$resultquery = "SELECT ID,name FROM results";

$showresult = mysql_query($resultquery) or die('Error, query failed'); //onchange='submitForm();'

echo "<select name='infantresult' id='infantresult' style='width:110px';>\n";
echo " <option value=''> Select Result </option>";

while ($srow = mysql_fetch_array($showresult)) {
    $ID = $srow['ID'];
    $name = $srow['name'];
    echo "<option value='$ID'> $name</option>\n";
}
echo "</select>\n";
?> 
                            </td>  
                            <!--<td><td width="150"><strong><span class="mandatory">*</span> Received Status	</strong></td>
                    
<?php /*
  $rquery = "SELECT ID,Name FROM receivedstatus ";

  $rresult = mysql_query($rquery) or die('Error, query failed'); //onchange='submitForm();'

  echo "<select name='receivedstatus' id='receivedstatus' style='width:188px' onChange='getRejectedreasons(this.value)';>\n";
  echo " <option value=''> Select One </option>";
  while ($rrow = mysql_fetch_array($rresult))
  {
  $rID = $rrow['ID'];
  $rname = $rrow['Name'];

  if ($rID == 2)
  {
  $fcolor = '#FF0000';
  }
  else if ($rID == 3)
  {
  $fcolor = '#0000FF';
  }
  echo "<option value='$rID' style='color:".$fcolor."'> $rname</option>\n";
  }
  echo "</select>\n"; */
?></td>
                      
                      <td width="150">
                      <div id="statediv"></div>		  </td> -->
                      <!--<td width="150"><strong>Reason for rejection</strong></td>
                    
                            <td><?php
/* $requery = "SELECT ID,Name FROM rejectedreasons ";

  $rreesult = mysql_query($requery) or die('Error, query failed'); //onchange='submitForm();'

  echo "<select name='rejectedreason' id='rejectedreason' style='width:188px' ;>\n";
  echo " <option value=''> Select One </option>";
  while ($rerow = mysql_fetch_array($rreesult))
  {
  $reID = $rerow['ID'];
  $rename = $rerow['Name'];
  echo "<option value='$reID'> $rename</option>\n";
  }
  echo "</select>\n"; */
?></td> -->
                        </tr>
                    </table>
                </td>
            </tr>


            <tr>
                <td colspan="6" >
                    <div align="center">
                        <input name="addonly" type="submit" class="button" value="Save & Release Sample" />
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="saveadd" type="submit" class="button" value="Save & Add Sample [Same Batch from Facility]" />
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="reset" type="reset" class="button" value="Reset" />
                    </div>			</td>
            </tr>
            </table>
        </form>

    </div>
</div>

                            <?php include('../includes/footer.php'); ?>