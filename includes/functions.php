<?php

//ZIMBABWE FUNCTIONS
require '../connection/config.php';
?>
<?php

/* * ..................................
ADD samples functions ..........
.................
...............* */

function getGUID()
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,
        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

//get facility name
function GetFacility($autocode)
{
    $facilityquery = mysql_query("SELECT name FROM facilitys where ID='$autocode' ") or die(mysql_error());
    $dd = mysql_fetch_array($facilityquery);
    $fname = $dd['name'];
    return $fname;
}

//get users name
function GetUserFullnames($userid)
{
    $usersquery = mysql_query("SELECT surname,oname FROM users where ID='$userid' ") or die(mysql_error());
    $dd = mysql_fetch_array($usersquery);
    $sname = $dd['surname'];
    $onames = $dd['oname'];
    $names = $sname . ", " . $onames;
    return $names;
}

//get sample/patinet ID from lab ID
function GetActualPatientID($labid)
{
    $samplequery = mysql_query("SELECT patient FROM samples where ID='$labid' ") or die(mysql_error());
    $dd = mysql_fetch_array($samplequery);
    $patient = $dd['patient'];

    return $patient;
}

//get lab email
function GetLabEmail($lab)
{
    $samplequery = mysql_query("SELECT email FROM labs where ID='$lab' ") or die(mysql_error());
    $dd = mysql_fetch_array($samplequery);
    $email = $dd['email'];

    return $email;
}

//save district details
function Savedistrict($name, $province, $comment)
{
    $savedistrict = "INSERT INTO
districts(name,province,comment,flag)VALUES('$name','$province','$comment',1)";
    $districts = @mysql_query($savedistrict) or die(mysql_error());
    return $districts;
}

//save lab details
function Savelab($name, $initial, $email, $withresult, $priority, $description)
{
    $savelab = "INSERT INTO labs(name,initials,email,withresult,priority,description)VALUES('$name','$initial','$email','$withresult','$priority','$description')";
    $labs = @mysql_query($savelab) or die(mysql_error());
    return $labs;
}

//Update lab details
function Updatelab($id, $name, $initial, $email, $withresult, $priority, $description)
{
    $updatelab = "UPDATE labs SET name = '$name', initials = '$initial', email = '$email', withresult = '$withresult', priority = '$priority', description = '$description' WHERE ID = '$id'";
    $labs = @mysql_query($updatelab) or die(mysql_error());
    return $labs;
}

function SaveWorkSheetType($name, $maxlimit, $status)
{
    $saveWorkSheetType = "INSERT INTO worksheettype(name,maxlimit,status)VALUES('{$name}',$maxlimit,$status)";
    $WorkSheetType = mysql_query($saveWorkSheetType) or die(mysql_error());
    return $WorkSheetType;
}

//Update worksheet type details
function UpdateWorksheetType($id, $name, $maxlimit, $status)
{
    $updateworksheettype = "UPDATE worksheettype SET name = '$name', maxlimit = '$maxlimit', status = '$status' WHERE ID = '$id'";
    $worksheettype = @mysql_query($updateworksheettype) or die(mysql_error());
    return $worksheettype;
}

//get task name from task id
function GetTaskName($taskid)
{
    $taskquery = mysql_query("SELECT name FROM tasks where ID='$taskid' ") or die(mysql_error());
    $dd = mysql_fetch_array($taskquery);
    $name = $dd['name'];

    return $name;
}

//get lab in wich facility belongs to
function GetLab($lab)
{
    $labquery = mysql_query("SELECT name FROM labs where ID='$lab' ") or die(mysql_error());
    $dd = mysql_fetch_array($labquery);
    $labname = $dd['name'];
    return $labname;
}

//get lab initials
function GetLabInitials($lab)
{
    $labquery = mysql_query("SELECT initials FROM labs where ID='$lab' ") or die(mysql_error());
    $dd = mysql_fetch_array($labquery);
    $labname = $dd['initials'];
    return $labname;
}

//get facility code
function GetActualFacilityCode($fid)
{
    $facilityquery = mysql_query("SELECT * FROM facilitys where ID='$fid' ") or die(mysql_error());
    $row = mysql_fetch_assoc($facilityquery);
    return $row;
}

//get samples pper facility per month
function Gettestedperfacilitypermonth($facility, $year, $month)
{

    $strQuery = mysql_query("SELECT COUNT(ID) as 'monthlytestedsamples' FROM samples WHERE result > 0 AND facility='$facility' AND  YEAR(datetested)='$year' AND MONTH(datetested)='$month'") or die(mysql_error());
    $resultarray = mysql_fetch_array($strQuery);
    $monthlytestedsamples = $resultarray['monthlytestedsamples'];
    return $monthlytestedsamples;
}

//get samples pper facility per year
function Gettestedperfacilityperyear($facility, $year)
{

    $strQuery = mysql_query("SELECT COUNT(ID) as 'yearlytestedsamples' FROM samples WHERE result > 0 AND facility='$facility' AND  YEAR(datetested)='$year' ") or die(mysql_error());
    $resultarray = mysql_fetch_array($strQuery);
    $yearlytestedsamples = $resultarray['yearlytestedsamples'];
    return $yearlytestedsamples;
}

//get samples pper facility per year per result type
function Gettestedperfacilityperyearperresult($facility, $year, $resulttype)
{

    $strQuery = mysql_query("SELECT COUNT(ID) as 'testedsamples' FROM samples WHERE result ='$resulttype' AND facility='$facility' AND  YEAR(datetested)='$year' ") or die(mysql_error());
    $resultarray = mysql_fetch_array($strQuery);
    $testedsamples = $resultarray['testedsamples'];
    return $testedsamples;
}

//determine if batch exists
function GetBatchNoifExists($datereceived, $facility, $lab)
{
    $strQuery = mysql_query("SELECT samples.batchno FROM samples,facilitys WHERE samples.datereceived='$datereceived' AND samples.facility='$facility' AND samples.lab='$lab' ORDER by batchno DESC LIMIT 1") or die(mysql_error());
    $numrows = mysql_num_rows($strQuery);
    return $numrows;
}

//determine EXISITNG BATCH NO
function GetExistingBatchNo($datereceived, $facility, $lab)
{

    $strQuery = mysql_query("SELECT samples.batchno FROM samples WHERE samples.datereceived='$datereceived' AND samples.facility='$facility' AND samples.lab='$lab' ") or die(mysql_error());
    $dd = mysql_fetch_array($strQuery);
    $batch = $dd['batchno'];
    return $batch;
}

//generate new  batch no
function GetNewBatchNo($lab)
{

    $RES = mysql_query("SELECT MAX(samples.batchno) as 'Max' FROM samples WHERE samples.lab='$lab' and samples.Flag=1");

    if (mysql_num_rows($RES) == 1) {
        $ROW = mysql_fetch_assoc($RES);
        $BatchNo = $ROW['Max'] + 1;
    }
    return $BatchNo;
}

//save mother details
function GetSavedMother($mhivstatus, $mentpoint, $breastfeeding, $mbfeeding, $mdrug, $fcode, $delivery, $anc_no, $mother_name, $testedbefore, $otherentry, $onart, $receivearv)
{
    $motherrec = "INSERT INTO
				mothers
					(facility,entry_point,breastfeeding,feeding,art,status,delivery,anc,name,testedbefore,otherentry,onart,receivearv)
				VALUES
					('$fcode','$mentpoint','$breastfeeding','$mbfeeding','$mdrug','$mhivstatus','$delivery','$anc_no','$mother_name','$testedbefore','$otherentry','$onart','$receivearv')";
    $mother = @mysql_query($motherrec) or die(mysql_error());
    return $mother;
}

function SaveRepeatSamplesTask($task, $batchno, $status, $labid, $labss)
{
    //query pending tasks table for id of mother just saved
    $repeatrec = "INSERT INTO pendingtasks(task,batchno,status,sample,lab) VALUES ('$task','$batchno','$status','$labid','$labss')";
    $repeat = @mysql_query($repeatrec) or die(mysql_error());
    return $repeat;
}

function GetLastMotherID($labss)
{
    $getmotherid = "SELECT mothers.ID
                    FROM mothers,facilitys
                    WHERE  mothers.facility = facilitys.ID
                    ORDER by ID DESC LIMIT 1 ";
    $getmum = mysql_query($getmotherid);
    $mumrec = mysql_fetch_array($getmum);
    $mid = $mumrec['ID'];
    return $mid;
}

function GetLastPatientID()
{
    $getpatientid = "SELECT patients.AutoID
            FROM patients,mothers
				WHERE  patients.mother=mothers.ID
            ORDER by patients.AutoID DESC LIMIT 1 ";
    $getpmum = mysql_query($getpatientid);
    $pmumrec = mysql_fetch_array($getpmum);
    $pmid = $pmumrec['AutoID'];
    return $pmid;
}

//save patient details
function GetSavedPatient($pid, $motherid, $agemonths, $pgender, $infantarv, $infantprophylaxis, $onctx, $testedbefore, $infanthivstatus, $testtype, $requestno_year, $requestno_no, $originalrequestno_year, $originalrequestno_no, $sdob, $infant)
{
    $child = "INSERT INTO
patients
(ID,mother,age,gender,infantarv,prophylaxis,onctx,testedbefore,infanthivstatus,testtype,requestno_year,requestno_no,originalrequestno_year,originalrequestno_no,dob,name)
VALUES
('$pid','$motherid','$agemonths','$pgender','$infantarv','$infantprophylaxis','$onctx','$testedbefore','$infanthivstatus','$testtype','$requestno_year','$requestno_no','$originalrequestno_year','$originalrequestno_no','$sdob','$infant')";
    $patient = @mysql_query($child) or die(mysql_error());
    return $patient;
}

//save sampels details
function GetSavedSamples($lastpatientid, $labss, $BatchNo, $pid, $fcode, $srecstatus, $sspot, $sdoc, $datedispatchedd, $sdrec, $scomments, $labcomment, $parentid, $rejectedreason, $repeatreason, $testreason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno, $rej, $priority)
{
    if ($parentid == "") {
        $parentid = 0;
    }
    $blank = "";
    $child = "INSERT INTO samples
    (patientid,lab,batchno, patient, facility, receivedstatus, spots, datecollected, datedispatchedfromfacility, datereceived, comments, labcomment,parentid,rejectedreason,reason_for_repeat,datetested,worksheet,result,datemodified,datedispatched,fcode,Inworksheet,BatchComplete,DispatchComments,Flag,repeatt,sampleokforretest,test_reason,othertest,dateenteredindb,loggedinby,nmrlstampno,approved,priority)
    VALUES('$lastpatientid','$labss','$BatchNo', '$pid', '$fcode', '$srecstatus', '$sspot', '$sdoc', '$datedispatchedd', '$sdrec', '$scomments', '$labcomment', '$parentid', '$rejectedreason', '$repeatreason', '$blank', '$blank', '$blank', '$blank', '$blank', '$blank', '$blank','$blank','$blank',1,'$blank','$blank','$testreason','$othertest','$dateenteredindb','$loggedinby','$nmrlstampno','$rej','$priority')"; //
    $success = mysql_query($child) or die(mysql_error());
    return $success;
}

//save sampels details
function GetZSavedSamples($lastpatientid, $labss, $BatchNo, $pid, $fcode, $srecstatus, $sspot, $sdoc, $datedispatchedd, $sdrec, $scomments, $labcomment, $parentid, $rejectedreason, $repeatreason, $testreason, $othertest, $infantresult, $inputcomplete, $approved, $dateenteredindb, $loggedinby, $priority)
{
    if ($parentid == "") {
        $parentid = 0;
    }
    $blank = "";
    $child = "INSERT INTO samples(patientid,lab,batchno, patient, facility, receivedstatus, spots, datecollected, datedispatchedfromfacility, datereceived, comments, labcomment,parentid,rejectedreason,reason_for_repeat,result,Flag,test_reason,othertest,inputcomplete,approved,BatchComplete,sampleokforretest,dateenteredindb,loggedinby,priority)
    VALUES('$lastpatientid','$labss','$BatchNo', '$pid', '$fcode', 1 , '$sspot', '$sdoc', '$datedispatchedd', '$sdrec', '$scomments', '$labcomment', '$parentid', '$rejectedreason', '$repeatreason', '$infantresult',1,'$testreason','$othertest',1,1,1,0,'$dateenteredindb','$loggedinby','$priority')";
    $success = mysql_query($child) or die(mysql_error());
    return $success;
}

function SaveSampleWithResult($lastpatientid, $labss, $BatchNo, $pid, $fcode, $srecstatus, $sspot, $sdoc, $datedispatchedd, $sdrec, $scomments, $labcomment, $parentid, $rejectedreason, $repeatreason, $result, $testreason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno, $rej, $priority, $bloodspot, $failedBrotherID) {
    if ($parentid == "") {
        $parentid = 0;
    }
    $blank = "";
    $child = "INSERT INTO samples
    (patientid,lab,batchno, patient, facility, receivedstatus, spots, datecollected, datedispatchedfromfacility, datereceived, comments, labcomment,parentid,rejectedreason,reason_for_repeat,datetested,worksheet,result,datemodified,datedispatched,fcode,Inworksheet,BatchComplete,DispatchComments,Flag,repeatt,sampleokforretest,test_reason,othertest,dateenteredindb,loggedinby,nmrlstampno,approved,priority,bloodspot,failedBrotherId)
    VALUES('$lastpatientid','$labss','$BatchNo', '$pid', '$fcode', '$srecstatus', '$sspot', '$sdoc', '$datedispatchedd', '$sdrec', '$scomments', '$labcomment', '$parentid', '$rejectedreason', '$repeatreason', '$blank', '$blank', '$result', '$blank', '$blank', '$blank', '$blank','$blank','$blank',1,'$blank','$blank','$testreason','$othertest','$dateenteredindb','$loggedinby','$nmrlstampno','$rej','$priority','$bloodspot','$failedBrotherID')"; //
    $success = mysql_query($child) or die(mysql_error());
    return $success;
}

/* * ..................................
samples listings, view functions ..........
.................
...............* */

//get patient id
function GetPatient($batchno, $labss)
{
    $getbatch = "SELECT patientid from samples WHERE batchno='$batchno' and lab='$labss'";
    $gotbatch = mysql_query($getbatch) or die(mysql_error());
    $batchrec = mysql_fetch_array($gotbatch);
    $patient = $batchrec['patient'];
    return $patient;
}

//get patient id
function GetPatientv1($batchno)
{
    $getbatch = "SELECT patientid from samples WHERE batchno='$batchno'";
    $gotbatch = mysql_query($getbatch) or die(mysql_error());
    $batchrec = mysql_fetch_array($gotbatch);
    $patient = $batchrec['patient'];
    return $patient;
}

//get date received for a batch
function GetDatereceived($batchno, $labss)
{
    $getdate = "SELECT datereceived from samples WHERE batchno='$batchno' and lab='$labss'";
    $gotdate = mysql_query($getdate) or die(mysql_error());
    $daterec = mysql_fetch_array($gotdate);
    $sdrec = $daterec['datereceived'];

    if (($sdrec != "") && ($sdrec != "0000-00-00") && ($sdrec != "1970-01-01")) {
        $sdreceieved = date("d-M-Y", strtotime($sdrec));
    } else {
        $sdreceieved = "";
    }
    return $sdreceieved;
}

//get date received for a batch
function GetDatereceivedv1($batchno)
{
    $getdate = "SELECT datereceived from samples WHERE batchno='$batchno'";
    $gotdate = mysql_query($getdate) or die(mysql_error());
    $daterec = mysql_fetch_array($gotdate);
    $sdrec = $daterec['datereceived'];

    if (($sdrec != "") && ($sdrec != "0000-00-00") && ($sdrec != "1970-01-01")) {
        $sdreceieved = date("d-M-Y", strtotime($sdrec));
    } else {
        $sdreceieved = "";
    }
    return $sdreceieved;
}

//get miother id for patient
function GetMotherID($patient)
{
    $getpatient = "SELECT mother FROM patients WHERE AutoID='$patient' ORDER BY AutoID DESC limit 0,1";
    $gotpatient = mysql_query($getpatient) or die(mysql_error());
    $patientrec = mysql_fetch_array($gotpatient);
    $mid = $patientrec['mother'];
    return $mid;
}

//get miother anc for patient
function GetMotherANC($patient)
{
    $getpatient = "SELECT mother FROM patients WHERE AutoID='$patient'";
    $gotpatient = mysql_query($getpatient) or die(mysql_error());
    $patientrec = mysql_fetch_array($gotpatient);
    $mid = $patientrec['mother'];

    $getm = "SELECT * FROM mothers WHERE ID='$mid'";
    $gotm = mysql_query($getm) or die(mysql_error());
    $mrec = mysql_fetch_array($gotm);
    return $mrec;
}

//get patient gender
function GetPatientGender($patient)
{
    $getpatient = "SELECT gender FROM patients WHERE AutoID='$patient'";
    $gotpatient = mysql_query($getpatient) or die(mysql_error());
    $patientrec = mysql_fetch_array($gotpatient);
    $pgender = $patientrec['gender'];

    return $pgender;
}

//get patient age
function GetPatientAge($patient)
{
    $getpatient = "SELECT age FROM patients WHERE AutoID='$patient'";
    $gotpatient = mysql_query($getpatient) or die(mysql_error());
    $patientrec = mysql_fetch_array($gotpatient);
    $age = $patientrec['age'];

    return $age;
}

//get patient date of birth
function GetPatientDOB($patient)
{
    $getpatient = "SELECT dob FROM patients WHERE AutoID='$patient'";
    $gotpatient = mysql_query($getpatient) or die(mysql_error());
    $patientrec = mysql_fetch_array($gotpatient);
    $dob = $patientrec['dob'];
    if (($dob != "") && ($dob != "0000-00-00") && ($dob != "1970-01-01")) {
        $sdob = date("d-M-Y", strtotime($dob));
    } else {
        $sdob = "";
    }
    return $sdob;
}

//get patient prophylaxis
function GetPatientProphylaxis($patient)
{
    $getpatient = "SELECT patients.prophylaxis,prophylaxis.name as 'infantprophylaxis' FROM patients,prophylaxis WHERE patients.AutoID='$patient' AND patients.prophylaxis=prophylaxis.ID";
    $gotpatient = mysql_query($getpatient) or die(mysql_error());
    $patientrec = mysql_fetch_array($gotpatient);
    $infantprophylaxis = $patientrec['infantprophylaxis'];

    return $infantprophylaxis;
}

//get facility code from batch
function GetFacilityCode($batchno, $labss)
{
    $getfcode = "SELECT facility FROM samples WHERE batchno='$batchno' and lab='$labss' and flag = 1 limit 0,1";
    $gotfcode = mysql_query($getfcode) or die(mysql_error());
    $fcoderec = mysql_fetch_array($gotfcode);
    $facility = $fcoderec['facility'];

    return $facility;
}

function GetFacilityCodeByID($batchno, $sampleID)
{
    $getfcode = "SELECT facility FROM samples WHERE batchno='$batchno' and ID='$sampleID' and flag = 1 limit 0,1";
    $gotfcode = mysql_query($getfcode) or die(mysql_error());
    $fcoderec = mysql_fetch_array($gotfcode);
    $facility = $fcoderec['facility'];

    return $facility;
}

//get facility code from batch
function GetFacilityCodev1($batchno)
{
    $getfcode = "SELECT facility FROM samples WHERE batchno='$batchno' and flag = 1 limit 0,1";
    $gotfcode = mysql_query($getfcode) or die(mysql_error());
    $fcoderec = mysql_fetch_array($gotfcode);
    $facility = $fcoderec['facility'];

    return $facility;
}

/* get facility code from batch
function GetFacilityCode($batchno,$labss)
{
$getfcode = "SELECT facility FROM samples WHERE batchno='$batchno' and lab='$labss' and flag = 1 limit 0,1";
$gotfcode = mysql_query($getfcode) or die(mysql_error());
$fcoderec = mysql_fetch_array($gotfcode);
$facility = $fcoderec['facility'];

return $facility;
} */

//get facility code from batch
function GetFacilityID($sample)
{
    $getfcode = "SELECT facility FROM samples WHERE ID='$sample' ";
    $gotfcode = mysql_query($getfcode) or die(mysql_error());
    $fcoderec = mysql_fetch_array($gotfcode);
    $facility = $fcoderec['facility'];

    return $facility;
}

//get mothers hivstatus
function GetMotherHIVstatus($mid)
{
    $getmother = "SELECT mothers.status,results.Name as 'HIV' FROM mothers,results WHERE mothers.ID='$mid' AND mothers.status=results.ID";
    $gotmother = mysql_query($getmother) or die(mysql_error());
    $motherrec = mysql_fetch_array($gotmother);
    $HIV = $motherrec['HIV'];
    return $HIV;
}

//get mothers pmtct intervention
function GetMotherProphylaxis($mid)
{
    $getmother = "SELECT mothers.art,prophylaxis.name as 'motherprophylaxis' FROM mothers,prophylaxis WHERE mothers.ID='$mid' AND mothers.art=prophylaxis.ID";
    $gotmother = mysql_query($getmother) or die(mysql_error());
    $motherrec = mysql_fetch_array($gotmother);
    $motherprophylaxis = $motherrec['motherprophylaxis'];

    return $motherprophylaxis;
}

//get mothers feeding types
function GetMotherFeeding($mid)
{
    $getmother = "SELECT mothers.feeding,feedings.name as 'motherfeeding',feedings.description as 'fdescr' FROM mothers,feedings WHERE mothers.ID='$mid' AND mothers.feeding=feedings.ID";
    $gotmother = mysql_query($getmother) or die(mysql_error());
    $motherrec = mysql_fetch_array($gotmother);
    $motherfeeding = $motherrec['motherfeeding'] . ' [ ' . $motherrec['fdescr'] . ' ]';
    return $motherfeeding;
}

//get mothers feeding types
function GetMotherDelivery($mid)
{
    $getmother = "SELECT mothers.delivery,deliverymode.name as 'motherdelivery' FROM mothers,deliverymode WHERE mothers.ID='$mid' AND mothers.delivery=deliverymode.ID";
    $gotmother = mysql_query($getmother) or die(mysql_error());
    $motherrec = mysql_fetch_array($gotmother);
    $motherdelivery = $motherrec['motherdelivery'];
    return $motherdelivery;
}

//get mothers feeding types description
function GetMotherFeedingDesc($mid)
{
    $getmother = "SELECT mothers.feeding,feedings.description as 'feedingdesc' FROM mothers,feedings WHERE mothers.ID='$mid' AND mothers.feeding=feedings.ID";
    $gotmother = mysql_query($getmother) or die(mysql_error());
    $motherrec = mysql_fetch_array($gotmother);
    $feedingdesc = $motherrec['feedingdesc'];
    return $feedingdesc;
}

//get mothers entry point
function GetEntryPoint($mid)
{
    $getmother = "SELECT mothers.entry_point,entry_points.name as 'entrypoint' FROM mothers,entry_points WHERE mothers.ID='$mid' AND mothers.entry_point=entry_points.ID";
    $gotmother = mysql_query($getmother) or die(mysql_error());
    $motherrec = mysql_fetch_array($gotmother);
    $entrypoint = $motherrec['entrypoint'];
    return $entrypoint;
}

//get total no of sampels per batch
function GetSamplesPerBatch($batchno, $labss)
{
    $samplequery = "SELECT COUNT(ID) as num_samples FROM samples WHERE batchno='$batchno' and lab = '$labss' AND ((parentid=0) OR (parentid IS NULL)) AND Flag=1
		 ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

//get total no of sampels per batch
function GetSamplesPerBatchv1($batchno)
{
    $samplequery = "SELECT COUNT(ID) as num_samples FROM samples WHERE batchno='$batchno' AND ((parentid=0) OR (parentid IS NULL)) AND Flag=1
		 ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

//get no of rejected samples per batch
function GetRejectedSamplesPerBatch($batchno, $labss)
{
    $rejquery = "SELECT COUNT(ID) as rej_samples FROM samples WHERE batchno='$batchno' AND lab='$labss' and receivedstatus=2
 AND Flag=1";
    $rejresult = mysql_query($rejquery) or die('mysql_error()');
    $rejrow = mysql_fetch_array($rejresult, MYSQL_ASSOC);
    $rej_samples = $rejrow['rej_samples'];

    return $rej_samples;
}

//get no of rejected samples per batch
function GetRejectedSamplesPerBatchv1($batchno)
{
    $rejquery = "SELECT COUNT(ID) as rej_samples FROM samples WHERE batchno='$batchno' and receivedstatus=2
 AND Flag=1";
    $rejresult = mysql_query($rejquery) or die('mysql_error()');
    $rejrow = mysql_fetch_array($rejresult, MYSQL_ASSOC);
    $rej_samples = $rejrow['rej_samples'];

    return $rej_samples;
}

//get no of not received samples per batch
function GetNotReceivedSamplesPerBatch($batchno, $labss)
{
    $rejquery = mysql_query("SELECT COUNT(ID) as n_samples FROM samples WHERE batchno='$batchno' AND lab='$labss' and receivedstatus=4
 AND Flag=1") or die(mysql_error());
    $rejrow = mysql_fetch_array($rejquery);
    $rej_samples = $rejrow['n_samples'];

    return $rej_samples;
}

//get no of not received samples per batch
function GetNotReceivedSamplesPerBatchv1($batchno)
{
    $rejquery = mysql_query("SELECT COUNT(ID) as n_samples FROM samples WHERE batchno='$batchno' and receivedstatus=4
 AND Flag=1") or die(mysql_error());
    $rejrow = mysql_fetch_array($rejquery);
    $rej_samples = $rejrow['n_samples'];

    return $rej_samples;
}

//get the pending no of samples in the batch
function gettotalpendingsamplesinbatches($batchno, $labss)
{

//total no of samples in particular batch awaiting approval by data clerk 2
    $batchesforapproval = mysql_query("SELECT ID
            FROM samples
			WHERE approved='0' AND batchno='$batchno' AND flag=1 and repeatt=0 and lab='$labss'

			") or die(mysql_error());
    $noofbatches = mysql_num_rows($batchesforapproval);

    return $noofbatches;
}

//get no of rejected samples per batch in pedning tasks table
function GetRejectedSamplesPerBatchFromPendingTasks($batchno, $lab)
{
    $rejquery = "SELECT COUNT(sample) as rej_samples FROM pendingtasks WHERE batchno='$batchno' AND task=6 AND status=0 AND lab='$lab'
 ";
    $rejresult = mysql_query($rejquery) or die(mysql_error());
    $rejrow = mysql_fetch_array($rejresult, MYSQL_ASSOC);
    $rej_samples = $rejrow['rej_samples'];

    return $rej_samples;
}

//get no of rejected samples per batch in pedning tasks table that are complete
function GetTotalCompleteRejectedSamplesBatches($batchno, $lab)
{

    $rejquery = "SELECT COUNT(sample) as rej_samples FROM pendingtasks WHERE batchno='$batchno' AND task=6 AND status=1 AND lab='$lab'
 ";
    $rejresult = mysql_query($rejquery) or die('mysql_error()');
    $rejrow = mysql_fetch_array($rejresult, MYSQL_ASSOC);
    $rej_samples = $rejrow['rej_samples'];

    return $rej_samples;
}

//get no of  samples per batch with results
function GetSamplesPerBatchwithResults($batchno, $labss)
{
    $noresultsamplee = "SELECT COUNT(ID) as with_result_samples FROM samples WHERE  batchno='$batchno' AND lab='$labss' and result > 0 AND repeatt =0 AND sampleokforretest=0 AND Flag=1";
    $noresultsampleresultt = mysql_query($noresultsamplee) or die(mysql_error());
    $noresultsampleresultroww = mysql_fetch_array($noresultsampleresultt, MYSQL_ASSOC);
    $with_result_samples = $noresultsampleresultroww['with_result_samples'];
    return $with_result_samples;
}

//get no of  samples per batch with results
function GetSamplesPerBatchwithResultsv1($batchno)
{
    $noresultsamplee = "SELECT COUNT(ID) as with_result_samples FROM samples WHERE  batchno='$batchno' and result > 0 AND repeatt =0 AND sampleokforretest=0 AND Flag=1";
    $noresultsampleresultt = mysql_query($noresultsamplee) or die(mysql_error());
    $noresultsampleresultroww = mysql_fetch_array($noresultsampleresultt, MYSQL_ASSOC);
    $with_result_samples = $noresultsampleresultroww['with_result_samples'];
    return $with_result_samples;
}

function getifcomplete($batchno, $labss)
{
    $numsamples = GetSamplesPerBatch($batchno, $labss);
    $rejected = GetRejectedSamplesPerBatch($batchno, $labss);
    $negatives = GetSamplesPerBatchbyresultype($batchno, 1, $labss);
    $indeter = GetSamplesPerBatchbyresultype($batchno, 3, $labss);
    $pos = ($numsamples - ($negatives + $indeter));
    $posi = 0;

    for ($counter = 1; $counter <= $pos; $counter += 1) {
        $posres = mysql_query("select COUNT(ID) as pos_with_result_samples from samples where parent='labcode' AND  lab = '$labss' and result >0 ") or die(mysql_error());
        $posresrow = mysql_fetch_array($posres, MYSQL_ASSOC);
        $pos_with_result_samples = $posresrow['pos_with_result_samples'];

        if ($pos_with_result_samples == 3) {
            $posi = posi + 1;
        }
    }

    $withresult = ($posi + $indeter + $negatives);
    $no_result_samples = (($numsamples - $withresult) - $rejected);

    return $no_result_samples;
}

//get no of  samples per batch with particular result type
function GetSamplesPerBatchbyresultype($batchno, $resultype)
{
    $noresultsamplee = "SELECT COUNT(ID) as with_result_samples FROM samples WHERE  batchno='$batchno' AND result = '$resultype'  AND parentid=0 AND Flag=1";
    $noresultsampleresultt = mysql_query($noresultsamplee) or die(mysql_error());
    $noresultsampleresultroww = mysql_fetch_array($noresultsampleresultt, MYSQL_ASSOC);
    $with_result_samples = $noresultsampleresultroww['with_result_samples'];

    return $with_result_samples;
}

//get no of  samples per batch with particular final result type  for positives and failed
function GetSamplesPerBatchbyFinalresultype($batchno, $resultype, $dispatchtype)
{

    if ($dispatchtype == 0) { //samples bein readied for  dispatch
        $noresultsamplee = "SELECT COUNT(ID) as with_result_samples FROM samples WHERE  batchno='$batchno' AND result = '$resultype'  AND parentid >0 AND `BatchComplete` =2 AND Flag=1";
        $noresultsampleresultt = mysql_query($noresultsamplee) or die(mysql_error());
        $noresultsampleresultroww = mysql_fetch_array($noresultsampleresultt, MYSQL_ASSOC);
        $with_result_samples = $noresultsampleresultroww['with_result_samples'];

        return $with_result_samples;
    } else { //samples alredy dispatched
        $noresultsamplee = "SELECT COUNT(ID) as with_result_samples FROM samples WHERE  batchno='$batchno' AND result = '$resultype'  AND
 parentid > 0 AND `BatchComplete` =1 AND Flag=1";
        $noresultsampleresultt = mysql_query($noresultsamplee) or die(mysql_error());
        $noresultsampleresultroww = mysql_fetch_array($noresultsampleresultt, MYSQL_ASSOC);
        $with_result_samples = $noresultsampleresultroww['with_result_samples'];

        return $with_result_samples;
    }
}

//get max date of test per batch
function GetMaxdateoftestperbatch($batchno)
{
    $noresultsamplee = "SELECT MAX(datetested) as 'maxdate' FROM samples WHERE  batchno='$batchno'   ";
    $noresultsampleresultt = mysql_query($noresultsamplee) or die(mysql_error());
    $noresultsampleresultroww = mysql_fetch_array($noresultsampleresultt, MYSQL_ASSOC);
    $maxdate = $noresultsampleresultroww['maxdate'];
    $maxdate = date("d-M-Y", strtotime($maxdate));
    return $maxdate;
}

//get max date of result updated afetr test per batch
function GetMaxdateupdatedperbatch($batchno)
{
    $noresultsamplee = "SELECT MAX(datemodified) as 'updateddate' FROM samples WHERE  batchno='$batchno'  ";
    $noresultsampleresultt = mysql_query($noresultsamplee) or die(mysql_error());
    $noresultsampleresultroww = mysql_fetch_array($noresultsampleresultt, MYSQL_ASSOC);
    $updateddate = $noresultsampleresultroww['updateddate'];
    if ($updateddate != "0000-00-00") {
        $updateddate = date("d-M-Y", strtotime($updateddate));
    } else {
        $updateddate = "";
    }
    return $updateddate;
}

//get max ID of sample with restets
function GetMaxLabIDforRetest($parent)
{
    $noresultsamplee = "SELECT MAX(ID) as 'maxid' FROM samples WHERE  parentid='$parent'   ";
    $noresultsampleresultt = mysql_query($noresultsamplee) or die(mysql_error());
    $noresultsampleresultroww = mysql_fetch_array($noresultsampleresultt, MYSQL_ASSOC);
    $maxid = $noresultsampleresultroww['maxid'];
    return $maxid;
}

//get date batch was dispatched
function Getbatchdateofdispatch($batchno, $labss)
{
    $noresultsamplee = "SELECT MAX(datedispatched) as 'dateofdispatch' FROM samples WHERE  lab='$labss' and batchno='$batchno'  ";
    $noresultsampleresultt = mysql_query($noresultsamplee) or die(mysql_error());
    $noresultsampleresultroww = mysql_fetch_array($noresultsampleresultt, MYSQL_ASSOC);
    $dateofdispatch = $noresultsampleresultroww['dateofdispatch'];
    $dateofdispatch = date("d-M-Y", strtotime($dateofdispatch));
    return $dateofdispatch;
}

//determine total number of batches
function GetTotalNoBatches($labss)
{
    $query = "SELECT DISTINCT samples.batchno   FROM samples
					WHERE samples.lab='$labss' and flag =1";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

//determine total number of batches
function GetTotalNoBatchesv1()
{
    $query = "SELECT DISTINCT samples.batchno   FROM samples
					WHERE flag =1";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

//determine total number of batches wit complete results
function GetTotalCompleteBatches($state, $labss)
{
    $query = "SELECT ID FROM samples
					WHERE samples.lab='$labss' AND samples.BatchComplete='$state' and flag=1";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

function EmailSent($ID)
{
    $emailquery = mysql_query("SELECT SentEmail FROM samples where ID='$ID' ") or die('he he');
    $dd = mysql_fetch_array($emailquery);
    $emailsent = $dd['SentEmail'];
    if ($emailsent != 1) {
        $sent = 'N';
    } else {
        $sent = 'Y';
    }
    return $sent;
}

//determine total number of batches wit complete results
function GetRejectedSamplesAwaitingDispatch($labss)
{
    $query = "SELECT task_id,task, batchno,sample  FROM pendingtasks
					WHERE status=0 AND task=6 AND lab='$labss'";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

//get results based on sample code
function GetSampleResult($ID)
{
    $getdate = "SELECT results.Name as 'outcome' from samples,results WHERE samples.result = results.ID AND samples.ID='$ID'";
    $gotdate = mysql_query($getdate) or die(mysql_error());
    $daterec = mysql_fetch_array($gotdate);
    $outcome = $daterec['outcome'];
    return $outcome;
}

//get results based on sample code
function GetResultName($infanthivstatus)
{
    $getdate = "SELECT Name as 'outcome' from results WHERE ID= '$infanthivstatus'";
    $gotdate = mysql_query($getdate) or die(mysql_error());
    $daterec = mysql_fetch_array($gotdate);
    $outcome = $daterec['outcome'];
    return $outcome;
}

//get test reason
function GetTestReason($test_reason)
{
    $getdate = "SELECT name as 'outcome' from testreason WHERE id= '$test_reason'";
    $gotdate = mysql_query($getdate) or die(mysql_error());
    $daterec = mysql_fetch_array($gotdate);
    $outcome = $daterec['outcome'];
    return $outcome;
}

//get received status based on ID
function GetReceivedStatus($receivedstatus)
{
    $getdate = "SELECT Name as 'status' from receivedstatus WHERE ID ='$receivedstatus'";
    $gotdate = mysql_query($getdate) or die(mysql_error());
    $daterec = mysql_fetch_array($gotdate);
    $status = $daterec['status'];
    return $status;
}

//determine total number of repeated samples (all parent samples that turned positive n needed retest)
function GetTotalRepeatParentSamples($labss)
{
    $query = "SELECT samples.ID,samples.patient,samples.datereceived,samples.spots,samples.datecollected,samples.receivedstatus,samples.facility
					FROM samples,facilitys
					WHERE ((samples.repeatt=1) AND ((samples.parentid=0)OR(samples.parentid IS NULL))) AND samples.facility=facilitys.ID AND facilitys.lab='$labss'
					ORDER BY samples.ID DESC";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

//determine total number of confirmatoy tests
function GetTotalConfirmatoryTests($labss)
{
    $reason = "Confirmatory PCR at 9 Mths";
    $query = "SELECT *
					FROM samples,facilitys
					WHERE samples.receivedstatus=3 AND samples.reason_for_repeat LIKE '%Confirmatory PCR at 9 Mths%'  AND samples.facility=facilitys.ID AND facilitys.lab='$labss'
					";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

//determine total number of confirmatoy tests
function GetTotalRepeatforRejection($labss)
{
    $query = "SELECT *
					FROM samples,facilitys
					WHERE samples.receivedstatus=3 AND samples.reason_for_repeat LIKE '%Repeat For Rejection%'  AND samples.facility=facilitys.ID AND facilitys.lab='$labss'
					";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

/* //get received status based on ID
function GetReceivedStatus($receivedstatus)
{
$getdate = "SELECT samples.receivedstatus,receivedstatus.Name as 'status' from samples,receivedstatus WHERE samples.receivedstatus AND receivedstatus.ID AND samples.receivedstatus='$receivedstatus'";
$gotdate = mysql_query($getdate) or die(mysql_error());
$daterec = mysql_fetch_array($gotdate);
$status = $daterec['status'];
return $status;
}
 *///get dsitrct ID

function GetDistrictID($facility)
{
    $districtidquery = mysql_query("SELECT district
            FROM facilitys
            WHERE  ID='$facility'");
    $noticia = mysql_fetch_array($districtidquery);

    $distid = $noticia['district'];
    return $distid;
}

//get distrcit name
function GetDistrictName($distid)
{
    $districtnamequery = mysql_query("SELECT name
            FROM districts
            WHERE  ID='$distid'");
    $districtname = mysql_fetch_array($districtnamequery);
    $distname = $districtname['name'];
    return $distname;
}

//get province id
function GetProvid($distid)
{
    $districtnamequery = mysql_query("SELECT province
            FROM districts
            WHERE  ID='$distid'");
    $districtname = mysql_fetch_array($districtnamequery);
    $provid = $districtname['province'];
    return $provid;
}

//get province id
function GetProvinceActualID($provid)
{
    $districtnamequery = mysql_query("SELECT ID
            FROM provinces
            WHERE  Code='$provid'");
    $districtname = mysql_fetch_array($districtnamequery);
    $provid = $districtname['ID'];
    return $provid;
}

//get province name
function GetProvname($provid)
{
    $provincenamequery = mysql_query("SELECT name
            FROM provinces
            WHERE  Code='$provid'");
    $provincename = mysql_fetch_array($provincenamequery);
    $provname = $provincename['name'];
    return $provname;
}

//determine if sample is a repeat or normal samples
function getRepeatValue($paroid, $lab)
{
    $repeatquery = mysql_query("SELECT samples.repeatt as 'repeatt'
            FROM samples,facilitys
            WHERE samples.facility=facilitys.ID AND facilitys.lab='$lab' AND samples.ID='$paroid'");
    $repeatname = mysql_fetch_array($repeatquery);
    $repeatvalue = $repeatname['repeatt'];
    return $repeatvalue;
}

//get the labcode parent id
function getParentID($labid, $lab)
{
    $parentquery = mysql_query("SELECT samples.parentid  as 'parentid'
            FROM samples,facilitys
            WHERE samples.facility=facilitys.ID AND facilitys.lab='$lab' AND  samples.ID='$labid'");
    $parentname = mysql_fetch_array($parentquery);
    $parentvalue = $parentname['parentid'];
    return $parentvalue;
}

//get no of repeats for that parent id
function GetNoofRetests($parentid, $lab)
{
    $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'noofrepeats' FROM samples WHERE  samples.lab='$lab' AND samples.parentid='$parentid' AND flag=1") or die(mysql_error());
    $resultarray = mysql_fetch_array($strQuery);
    $testedsamples = $resultarray['noofrepeats'];
    return $testedsamples;
}

function GetDuplicateSampleResult($paroid, $labcode)
{
    $parentquery = mysql_query("SELECT result FROM samples WHERE parentid='$paroid' AND ID !='$labcode' and flag=1");
    $parentname = mysql_fetch_array($parentquery);
    $parentvalue = $parentname['result'];
    return $parentvalue;
}

function GetDuplicateSampleResultID($paroid, $labcode)
{
    $parentquery = mysql_query("SELECT ID FROM samples WHERE parentid='$paroid' AND ID !='$labcode' and flag=1");
    $parentname = mysql_fetch_array($parentquery);
    $parentvalue = $parentname['ID'];
    return $parentvalue;
}

function GetFailedBrotherID($parentID){
    $query = "SELECT failedBrotherID FROM samples WHERE ID = '$parentID'";
    $runQuery = mysql_query($query);
    $resultSet = mysql_fetch_array($runQuery);
    $failedBrotherID = $resultSet['failedBrotherID'];
    return $failedBrotherID;
}

//generate new  worksheet no
function GetNewWorksheetNo($type, $lab)
{

    if (isset($lab)) {
        $labinitials = GetLabInitials($lab);
        $RES = mysql_query("SELECT MAX(ID) as 'Max' FROM worksheets");

        if (mysql_num_rows($RES) == 1) {
            $ROW = mysql_fetch_assoc($RES);
            $nextno = $ROW['Max'] + 1;
        }
        if ($type == 0) {
            $worksheetno = $labinitials . " - TW" . $nextno;
        } elseif ($type == 1) {
            $worksheetno = $labinitials . " - MW" . $nextno;
        }
    } else {
        if ($type == 0) {
            $worksheetno = "TW" . $nextno;
        } elseif ($type == 1) {
            $worksheetno = "MW" . $nextno;
        }
    }

    return array($nextno, $worksheetno);
}

//get total number of samples
function Gettotalsamples($labss)
{
    $provincenamequery = mysql_query("SELECT COUNT(samples.ID) as 'totalsamples'
           FROM samples
					WHERE samples.lab='$labss' and flag = 1

            ");
    $provincename = mysql_fetch_array($provincenamequery);
    $totalsamples = $provincename['totalsamples'];
    return $totalsamples;
}

//get total number of worksheets
function Gettotalworksheets()
{
    $provincenamequery = mysql_query("SELECT COUNT(ID) as 'worksheets' FROM worksheets");
    $provincename = mysql_fetch_array($provincenamequery);
    $worksheets = $provincename['worksheets'];
    return $worksheets;
}

//get total number of complete worksheets
function Gettotalcompleteworksheets()
{
    $completeworksheetquery = mysql_query("SELECT COUNT(ID) as 'worksheets' FROM worksheets where flag=1");
    $completeworksheetresult = mysql_fetch_array($completeworksheetquery);
    $worksheets = $completeworksheetresult['worksheets'];
    return $worksheets;
}

//get total number of repeat worksheets
function GettotalRepeatworksheets()
{
    $provincenamequery = mysql_query("SELECT COUNT(ID) as 'worksheets'
            FROM worksheets where type=1
            ");
    $provincename = mysql_fetch_array($provincenamequery);
    $worksheets = $provincename['worksheets'];
    return $worksheets;
}

//get total number of pending  worksheets

function GettotalPendingworksheets()
{
    $pendingquery = mysql_query("SELECT COUNT(ID) as 'worksheets' FROM worksheets WHERE (approvalstatus = 0 or approvalstatus = 1) AND Flag = 0");
    $pendingresult = mysql_fetch_array($pendingquery);
    $worksheets = $pendingresult['worksheets'];
    return $worksheets;
}

//get total no of sampels per worksheet
function GetSamplesPerworksheet($worksheet)
{
    $samplequery = "SELECT COUNT(ID) as num_samples FROM samples WHERE worksheet='$worksheet'
		 ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

//get total no of sampels per worksheet for repeat
function GetRepeatSamplesPerworksheet($worksheet)
{
    $samplequery = "SELECT COUNT(ID) as num_samples FROM samples WHERE worksheet='$worksheet' AND 	inrepeatworksheet  = 1
		 ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

//get worksheet details
function getWorksheetDetails($wno)
{
    $qury = "SELECT *
            FROM worksheets
            WHERE worksheetno= '$wno'";
    $reslt = mysql_query($qury) or die(mysql_error());
    $row = mysql_fetch_assoc($reslt);
    return $row;
}

//get sample details
function getSampleetails($sample)
{
    $qury = "SELECT *
            FROM samples
            WHERE ID= '$sample' ";
    $reslt = mysql_query($qury) or die(mysql_error());
    $row = mysql_fetch_assoc($reslt);
    return $row;
}

//get sample details
function GetPatientInfo($patient)
{
    $qury = "SELECT *
            FROM patients
            WHERE AutoID= '$patient'";
    $reslt = mysql_query($qury) or die(mysql_error());
    $row = mysql_fetch_assoc($reslt);
    return $row;
}

//get mother details
function GetMotherInfo($mother)
{
    $qury = "SELECT *
            FROM mothers
            WHERE ID= '$mother'";
    $reslt = mysql_query($qury) or die(mysql_error());
    $row = mysql_fetch_assoc($reslt);
    return $row;
}

//get facility details
function getFacilityDetails($fcode)
{
    $qury = "SELECT *
            FROM facilitys
            WHERE ID= '$fcode'";
    $reslt = mysql_query($qury) or die(mysql_error());
    $row = mysql_fetch_assoc($reslt);
    return $row;
}

//get samples in batch
function getBatchDetails($batchno)
{
    $qury = "SELECT *
            FROM samples
            WHERE batchno= '$batchno'";
    $reslt = mysql_query($qury) or die(mysql_error());
    $row = mysql_fetch_assoc($reslt);
    return $row;
}

//get requisition details
function getRequisitionDetails($rno)
{
    $qury = "SELECT *
            FROM requisitions
            WHERE id= '$rno'";
    $reslt = mysql_query($qury) or die(mysql_error());
    $row = mysql_fetch_assoc($reslt);
    return $row;
}

//save user details
function SaveUser($surname, $oname, $telephone, $postal, $email, $account, $username, $password, $lab, $datecreated)
{

    $saved = "INSERT INTO
users(surname,oname,telephone,postal,email,account,lab,username,password,flag,datecreated)VALUES('$surname','$oname','$telephone','$postal','$email','$account','$lab','$username','$password',1,'$datecreated')";
    $users = @mysql_query($saved) or die(mysql_error());
    return $users;
}

//save user groups
function SaveUserGroup($groupname)
{
    $saved = "INSERT INTO
usergroups(name)VALUES('$groupname')";
    $users = @mysql_query($saved) or die(mysql_error());
    return $users;
}

//save menus
function SaveMenu($menu, $url, $location)
{
    $saved = "INSERT INTO
menus(name,url,location)VALUES('$menu','$url','$location')";
    $users = @mysql_query($saved) or die(mysql_error());
    return $menus;
}

function GetTotalMenus()
{
    $query = "SELECT ID  FROM menus";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

function GetTotalUserActivity()
{
    $query = "SELECT TaskID  FROM usersactivity";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

function GetTotalSingleUserActivity($user)
{
    $query = "SELECT TaskID  FROM usersactivity where user=$user";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

//get total users
function GetTotalUsers()
{
    $query = "SELECT ID  FROM users where flag=1";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

//get rejected reason
function GetRejectedReason($ID)
{
    $query = "SELECT Name  FROM rejectedreasons where ID='$ID'";
    $result = mysql_query($query) or die(mysql_error());
    $dd = mysql_fetch_array($result);
    $rejreason = $dd['Name'];
    return $rejreason;
}

//get delivery
function GetDelivery($delivery)
{
    $query = "SELECT name as dname  FROM deliverymode where id='$delivery'";
    $result = mysql_query($query) or die(mysql_error());
    $dd = mysql_fetch_array($result);
    $rejreason = $dd['dname'];
    return $rejreason;
}

//get total distrcts
function GetTotalDistricts()
{
    $query = "SELECT ID  FROM districts where flag=1";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

//get total facilities
function GetTotalFacilities()
{
    $query = "SELECT ID  FROM facilitys where Flag=1";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

//get total samples for repeat
function GetTotalRepeatSamples()
{
    $query = "select * from samples where parentid IS NOT NULL AND inrepeatworksheet =0";
    $result = mysql_query($query) or die('Error, query failed');
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    $numrows = $row['numrows'];
    return $numrows;
}

//get total samples for repeat
function GetLastFacility()
{
    $userquery = mysql_query("select max(ID) as mid from facilitys") or die(mysql_error());
    $dd = mysql_fetch_array($userquery);
    $grupname = $dd['mid'];
    return $grupname;
}

//save facility details
function Savefacility($code, $name, $district, $lab, $postal, $telephone, $otelephone, $fax, $email, $fullname, $contacttelephone, $ocontacttelephone, $contactemail, $imei, $pass, $iseid)
{
    $saved = "INSERT INTO
facilitys(facilitycode,name,district,lab,physicaladdress,telephone,telephone2,fax,email,contactperson,contacttelephone,contacttelephone2,flag,ContactEmail,imei,pass,iseid)VALUES('$code','$name','$district','$lab','$postal','$telephone','$otelephone','$fax','$email','$fullname','$contacttelephone','$ocontacttelephone',1,'$contactemail','$imei','$pass','$iseid')";
    $users = @mysql_query($saved) or die(mysql_error());
    return $users;
}

//save user activity
function SaveUserActivity($userid, $utask, $tasktime, $patient, $todaysdate)
{
    $activity = "INSERT INTO
usersactivity(user,task,timetaskdone,patient,dateofentry)VALUES('$userid','$utask','$tasktime','$patient','$todaysdate')";
    $useractivity = @mysql_query($activity) or die('error');
    return $useractivity;
}

/* old save user activity
function SaveUserActivity($userid,$leo,$task,$tasktime,$patient)
{
$activity = "INSERT INTO
usersactivity(user,accessdate,task,timetaskdone,patient)VALUES('$userid','$leo','$task','$tasktime','$patient')";
$useractivity = @mysql_query($activity) or die('error');
return $useractivity;
} */

//get account type name
function GetAccountType($account)
{
    $userquery = mysql_query("SELECT name FROM usergroups where ID='$account' ") or die(mysql_error());
    $dd = mysql_fetch_array($userquery);
    $grupname = $dd['name'];
    return $grupname;
}

//get sample date of testting based on id
function GetSampleDateofTest($parentid)
{
    $datequery = mysql_query("SELECT datetested FROM samples where ID='$parentid' ") or die(mysql_error());
    $dd = mysql_fetch_array($datequery);
    $datetested = $dd['datetested'];
    $datetested = date("d-M-Y", strtotime($datetested));
    return $datetested;
}

//get sample date of testting based on id
function GetSampleResultbasedonparentid($parentid)
{
    $resultquery = mysql_query("SELECT result FROM samples where ID='$parentid' ") or die(mysql_error());
    $dd = mysql_fetch_array($resultquery);
    $outcome = $dd['result'];
    return $outcome;
}

//get menu name
function GetMenuName($menu)
{
    $menuquery = mysql_query("SELECT name FROM menus where ID='$menu' ") or die(mysql_error());
    $dd = mysql_fetch_array($menuquery);
    $menuname = $dd['name'];
    return $menuname;
}

function GetMenuUrl($menu)
{
    $menuquery = mysql_query("SELECT url FROM menus where ID='$menu' ") or die(mysql_error());
    $dd = mysql_fetch_array($menuquery);
    $menuurl = $dd['url'];
    return $menuurl;
}

//get sample result
function GetResultType($result)
{
    $result = "SELECT name FROM results WHERE ID = '$result'";
    $getresult = mysql_query($result) or die(mysql_error());
    $resulttype = mysql_fetch_array($getresult);
    $showresult = $resulttype['name'];

    return $showresult;
}

//get ID baased on name and table
function GetIDfromtableandname($names, $tabl)
{
    $menuquery = mysql_query("SELECT ID FROM $tabl where name='$names' ") or die(mysql_error());
    $dd = mysql_fetch_array($menuquery);
    $ID = $dd['ID'];
    return $ID;
}

function gettotalpendingtasks()
{

//total no of samples awaitin dispatch
    $batchesfordispatch = mysql_query("SELECT task_id,task,sample
            FROM pendingtasks
			WHERE status=1 AND task=2
			ORDER BY sample ASC
			") or die(mysql_error());

    $noofbatches = mysql_num_rows($batchesfordispatch);

    //total no of rejected samples awaiting dispatch
    $rejectedsamplesfordispatch = mysql_query("SELECT task_id,task,sample
            FROM pendingtasks
			WHERE status=0 AND task=6
			ORDER BY sample ASC
			") or die(mysql_error());
    $noofrejsamples = mysql_num_rows($rejectedsamplesfordispatch);

//total sampels for repeat
    $samplesforrepeat = mysql_query("SELECT task_id,task,batchno,sample
            FROM pendingtasks
			WHERE status=0 AND task=3
			ORDER BY sample ASC
			") or die(mysql_error());

    $noofsamples = mysql_num_rows($samplesforrepeat);
//total number of samples awaiting testing
    $samplesfortest = mysql_query("SELECT task_id,task, sample FROM pendingtasks
					WHERE status=0 AND task=1 ") or die(mysql_error());

    $samplesawaitingtest = mysql_num_rows($samplesfortest);

    $totaltasks = ($noofbatches + $noofsamples + $samplesawaitingtest + $noofrejsamples);

    return $totaltasks;
}

function samplesawaitingtests()
{
    $samplesfortest = mysql_query("SELECT task_id,task, sample FROM pendingtasks
					WHERE status=0 AND task=1 ") or die(mysql_error());

    $samplesawaitingtest = mysql_num_rows($samplesfortest);
    return $samplesawaitingtest;
}

function getsamplesdonebyfacility($facility)
{
    $samples = mysql_query("SELECT ID from samples where facility = '$facility' and flag = 1
					 ") or die(mysql_error());
    $samplestest = mysql_num_rows($samples);

    return $samplestest;
}

//get sample Lab ID of  last saved sample record
function GetLastSampleID($lab)
{
    $getsampleid = "SELECT samples.ID
                    FROM samples
                    WHERE  samples.lab='$lab'
                    ORDER by ID DESC LIMIT 0,1 ";
    $getsample = mysql_query($getsampleid);
    $samplerec = mysql_fetch_array($getsample);
    $sid = $samplerec['ID'];
    return $sid;
}

//get requsition no of last saved requisition record
function GetLastRequisitionID()
{
    $getreqid = "SELECT id
            FROM requisitions
            ORDER by id DESC LIMIT 1 ";
    $getreq = mysql_query($getreqid);
    $reqrec = mysql_fetch_array($getreq);
    $rid = $reqrec['ID'];
    return $rid;
}

//save requisition
function SaveRequisition($fcode, $dbs, $ziploc, $dessicants, $rack, $glycline, $humidity, $lancets, $reqform, $comments, $datecreated, $parentid, $disapprovecomments, $ecomments, $requisitiondate, $datemodified)
{
    if ($parentid == "" && $disapprovecomments == "" && $approvecomments == "") {
        $parentid = "";
        $disapprovecomments = "";
        $approvecomments = "";
    }
    $saved = "INSERT INTO
requisitions(facility,dbs,ziploc,dessicants,rack,glycline,humidity,lancets,reqform,comments,datecreated,flag,parentid,approvecomments,disapprovecomments,requisitiondate,datemodified)VALUES('$fcode','$dbs','$ziploc','$dessicants','$rack','$glycline','$humidity','$lancets','$reqform','$comments','$datecreated',1,'$parentid','$ecomments','$disapprovecomments','$requisitiondate','$datemodified')";
    $requisitions = mysql_query($saved) or die(mysql_error());
    return $requisitions;
}

//get any date
function GetAnyDateMin()
{
    $getanydate = "SELECT YEAR(MIN(datereceived)) AS lowdate FROM samples WHERE flag=1 AND datereceived > 0";
    $anydate = mysql_query($getanydate) or die(mysql_error());
    $dateresult = mysql_fetch_array($anydate);
    $showdate = $dateresult['lowdate'];

    return $showdate;
}

function GetReqMin()
{
    $getanydate = "SELECT YEAR(MIN(datecreated)) AS lowdate FROM requisitions WHERE flag=1 AND datecreated > 0";
    $anydate = mysql_query($getanydate) or die(mysql_error());
    $dateresult = mysql_fetch_array($anydate);
    $showdate = $dateresult['lowdate'];

    return $showdate;
}

//get requisition details
function GetRequisitionInfo($db)
{
    $req = "SELECT dbs,dessicants,glycline,lancets,reqform,ziploc,rack,humidity,comments,datecreated,requisitiondate,datemodified,parentid,approvecomments FROM requisitions WHERE ID = '$db'";
    $getreq = mysql_query($req) or die(mysql_error());
    $requisition = mysql_fetch_array($getreq);

    return $requisition;
}

//update requisition details
function UpdateRequisition($db, $edbs, $eziploc, $edessicants, $erack, $eglycline, $ehumidity, $elancets, $ereqform, $ecomments, $datemodified)
{
    $req = "UPDATE requisitions SET dbs='$edbs',ziploc='$eziploc',dessicants='$edessicants',rack='$erack',glycline='$eglycline',humidity='$ehumidity',lancets='$elancets',reqform='$ereqform',comments='$ecomments',datemodified='$datemodified' WHERE id = '$db'";
    $getreq = mysql_query($req) or die(mysql_error());

    return $getreq;
}

//delete requisition details
function DeleteRequisition($db, $datemodified)
{
    $delreq = "UPDATE
requisitions SET flag=0, datemodified='$datemodified' WHERE id = '$db'";
    $deletedreq = mysql_query($delreq) or die(mysql_error());

    return $deletedreq;
}

//disapprove requisition
function DisapproveRequisition($db, $datemodified, $disapprovecomment)
{
    $delreq = "UPDATE
requisitions SET status=2, datemodified='$datemodified', disapprovecomments='$disapprovecomment' WHERE id = '$db'";
    $deletedreq = mysql_query($delreq) or die(mysql_error());

    return $deletedreq;
}

//get date dispatched for rejected sample
function GetDateDispatchedforRejectedSample($samplecode)
{
    $noresultsamplee = "SELECT dateupdated FROM pendingtasks WHERE  sample='$samplecode' AND task=6  ";
    $noresultsampleresultt = mysql_query($noresultsamplee) or die(mysql_error());
    $noresultsampleresultroww = mysql_fetch_array($noresultsampleresultt, MYSQL_ASSOC);
    $dateupdated = $noresultsampleresultroww['dateupdated'];
    return $dateupdated;
}

//get lab name
function GetLabNames($lab)
{

    $facilityquery = mysql_query("SELECT name FROM labs where ID='$lab' ") or die(mysql_error());
    $dd = mysql_fetch_array($facilityquery);
    $labname = $dd['name'];
    return $labname;
}

//get approved requisition details
function GetApprovedRequisitionInfo($db)
{
    $approvedreq = "SELECT dbs,dessicants,glycline,lancets,ziploc,rack,humidity,comments,datecreated,requisitiondate,datemodified FROM requisitions WHERE parentid = '$db'";
    $appreq = mysql_query($approvedreq) or die(mysql_error());
    $apprequisition = mysql_fetch_array($appreq);

    return $apprequisition;
}

//get sample id from auto lab code
function GetInfantID($labcode)
{
    $query = "select patient from samples where ID= '$labcode'";
    $result = mysql_query($query) or die('Error, query failed');
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    $numrows = $row['patient'];
    return $numrows;
}

//get sample id from auto lab code
function GetNoofspots($labcode)
{
    $query = "select spots from samples where ID= '$labcode'";
    $result = mysql_query($query) or die('Error, query failed');
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    $numrows = $row['spots'];
    return $numrows;
}

//get maximum year
function GetMaxYear()
{
    $getmaxyear = "SELECT MAX( YEAR( datereceived ) ) AS maximumyear FROM samples WHERE flag =1 ";
    $maxyear = mysql_query($getmaxyear) or die(mysql_error());
    $year = mysql_fetch_array($maxyear);
    $showyear = $year['maximumyear'];

    return $showyear;
}

function getfacilitylab($facilitycode)
{
    $query = "select lab from data2010 where facilitycode= '$facilitycode' limit 0,1";
    $result = mysql_query($query) or die('Error, query failed');
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    $lab = $row['lab'];

    if ($lab == 1) {
        $labname = "KEMRI Nairobi";
    } else if ($lab == 2) {
        $labname = "CDC Kisumu";
    } else if ($lab == 3) {
        $labname = "Alupe Busia";
    } else if ($lab == 4) {
        $labname = "Walter Reed Kericho";
    } else {
        $labname = "";
    }
    return $labname;
}

function getmonthlytests($facilitycode, $month, $year)
{

    $samplequery = "SELECT COUNT(sample) as num_samples FROM data2010 WHERE facilitycode='$facilitycode' AND MONTH(datetested)='$month'	AND YEAR(datetested)='$year' ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

function getyearlytotals($facilitycode, $year)
{
    $samplequery = "SELECT COUNT(sample) as num_samples FROM data2010 WHERE facilitycode='$facilitycode' AND YEAR(datetested)='$year' ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

function getyearlyrejected($facilitycode, $year)
{
    $samplequery = "SELECT COUNT(sample) as num_samples FROM data2010 WHERE facilitycode='$facilitycode' AND YEAR(datereceived)='$year' AND receivedstatus=2 ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

function gettotaltestsperresult($facilitycode, $resultype, $year)
{
    $samplequery = "SELECT COUNT(sample) as num_samples FROM data2010 WHERE facilitycode='$facilitycode' AND YEAR(datetested)='$year' AND result='$resultype' ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

function gettotaltestsperresultpermonth($facilitycode, $resultype, $month, $year)
{

    $samplequery = "SELECT COUNT(sample) as num_samples FROM data2010 WHERE facilitycode='$facilitycode' AND YEAR(datetested)='$year' AND MONTH(datetested)='$month' AND  result='$resultype' ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

function allrejectedsamples($lab)
{
    $samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID  AND samples. receivedstatus=2 AND facilitys.lab='$lab'";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

function getparentsampleresult($paroid, $lab)
{
    $strQuery = mysql_query("SELECT samples.result as 'outcome' FROM samples,facilitys WHERE samples.facility=facilitys.ID AND facilitys.lab='$lab'  AND samples.ID='$paroid' ") or die(mysql_error());
    $resultarray = mysql_fetch_array($strQuery);
    $parentresult = $resultarray['outcome'];
    return $parentresult;
}

// lab reports functions
/*
 *
 *
 *
 */
// weekly tests by result type
function weeklytestsbyresult($lab, $startdate, $enddate, $resultype)
{
    $samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab' AND samples. datetested BETWEEN '$startdate' AND '$enddate' AND samples.result='$resultype'";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

//total tests weekly
function totalweeklytests($lab, $startdate, $enddate)
{
    $samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab' AND samples. datetested BETWEEN '$startdate' AND '$enddate' ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

// weekly tests rejected
function weeklyrejectedsamples($lab, $startdate, $enddate)
{
    $samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID AND facilitys.lab='$lab' AND samples.datereceived BETWEEN '$startdate' AND '$enddate' AND samples.receivedstatus=2";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

// weekly kits used
function weeklykitsused($lab, $startdate, $enddate)
{
    $samplequery = "SELECT COUNT( DISTINCT HIQCAPNo) as num_kits FROM worksheets WHERE  lab='$lab' AND daterun BETWEEN '$startdate' AND '$enddate' ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_kits = $samplerow['num_kits'];

    return $num_kits;
}

// weekly kits wasted
function weeklykitswasted($lab, $startdate, $enddate)
{
    $samplequery = "SELECT COUNT( DISTINCT HIQCAPNo) as num_kits FROM kits_wasted WHERE  lab='$lab' AND daterun BETWEEN '$startdate' AND '$enddate' ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_kits = $samplerow['num_kits'];

    return $num_kits;
}

// monthly tests by result type
function monthlytestsbyresult($lab, $month, $resultype, $year)
{
    $samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab' AND MONTH(samples.datetested)=  '$month' AND YEAR(samples.datetested)=  '$year'  AND samples.result='$resultype'";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

//total tests monthly
function totalmonthlytests($lab, $month, $year)
{
    $samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab' AND MONTH(samples.datetested)=  '$month' AND YEAR(samples.datetested)=  '$year' ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

// monthly tests rejected
function monthlyrejectedsamples($lab, $month, $year)
{
    $samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab' AND MONTH(samples.datereceived)=  '$month' AND YEAR(samples.datereceived)= '$year'  AND samples.receivedstatus=2";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

// monthly kits used
function monthlykitsused($lab, $month, $year)
{
    $samplequery = "SELECT COUNT( DISTINCT HIQCAPNo) as num_kits FROM worksheets WHERE  lab='$lab' AND MONTH(daterun)= '$month'  AND YEAR(daterun)= '$year' ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_kits = $samplerow['num_kits'];

    return $num_kits;
}

// monthly kits wasted
function monthlykitswasted($lab, $month, $year)
{
    $samplequery = "SELECT COUNT( DISTINCT HIQCAPNo) as num_kits FROM kits_wasted WHERE  lab='$lab' AND MONTH(daterun)= '$month'  AND YEAR(daterun)= '$year' ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_kits = $samplerow['num_kits'];

    return $num_kits;
}

// yearly tests by result type
function yearlytestsbyresult($lab, $year, $resultype)
{
    $samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab' AND YEAR(samples.datetested)=  '$year'  AND samples.result='$resultype'";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

//total tests yearly
function totalyearlytests($lab, $year)
{
    $samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab'  AND YEAR(samples.datetested)=  '$year'  ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

// yearly tests rejected
function yearlyrejectedsamples($lab, $year)
{
    $samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab' AND YEAR(samples.datetested)=  '$year'  AND samples.receivedstatus=2";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

// yearly kits used
function yearlykitsused($lab, $year)
{
    $samplequery = "SELECT COUNT( DISTINCT HIQCAPNo) as num_kits FROM worksheets WHERE  lab='$lab'  AND YEAR(daterun)= '$year' ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_kits = $samplerow['num_kits'];

    return $num_kits;
}

// yearly kits wasted
function yearlykitswasted($lab, $year)
{
    $samplequery = "SELECT COUNT( DISTINCT HIQCAPNo) as num_kits FROM kits_wasted WHERE  lab='$lab'  AND YEAR(daterun)= '$year' ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_kits = $samplerow['num_kits'];

    return $num_kits;
}

// periodic tests by result type
function periodictestsbyresult($lab, $quarterly, $year, $resultype)
{
    if ($quarterly == 1) { //january - March
        $startmonth = 1;
        $endmonth = 3;
    } else if ($quarterly == 2) {
        $startmonth = 4;
        $endmonth = 6;
    } else if ($quarterly == 3) {
        $startmonth = 7;
        $endmonth = 9;
    } else if ($quarterly == 4) {
        $startmonth = 10;
        $endmonth = 12;
    }
    $samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab' AND MONTH(samples.datetested) BETWEEN '$startmonth' AND '$endmonth' AND YEAR(samples.datetested)=  '$year'  AND samples.result='$resultype'";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

//total tests yearly
function totalperiodictests($lab, $quarterly, $year)
{
    if ($quarterly == 1) { //january - March
        $startmonth = 1;
        $endmonth = 3;
    } else if ($quarterly == 2) {
        $startmonth = 4;
        $endmonth = 6;
    } else if ($quarterly == 3) {
        $startmonth = 7;
        $endmonth = 9;
    } else if ($quarterly == 4) {
        $startmonth = 10;
        $endmonth = 12;
    }
    $samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab' AND MONTH(samples.datetested) BETWEEN '$startmonth' AND '$endmonth' AND YEAR(samples.datetested)=  '$year'  ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

// periodic tests by result type
function periodicrejectedsamples($lab, $quarterly, $year)
{
    if ($quarterly == 1) { //january - March
        $startmonth = 1;
        $endmonth = 3;
    } else if ($quarterly == 2) {
        $startmonth = 4;
        $endmonth = 6;
    } else if ($quarterly == 3) {
        $startmonth = 7;
        $endmonth = 9;
    } else if ($quarterly == 4) {
        $startmonth = 10;
        $endmonth = 12;
    }
    $samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab' AND MONTH(samples.datetested) BETWEEN '$startmonth' AND '$endmonth' AND YEAR(samples.datetested)=  '$year'  AND samples.receivedstatus=2";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

// periodic kits used
function periodickitsused($lab, $quarterly, $year)
{

    if ($quarterly == 1) { //january - March
        $startmonth = 1;
        $endmonth = 3;
    } else if ($quarterly == 2) {
        $startmonth = 4;
        $endmonth = 6;
    } else if ($quarterly == 3) {
        $startmonth = 7;
        $endmonth = 9;
    } else if ($quarterly == 4) {
        $startmonth = 10;
        $endmonth = 12;
    }
    $samplequery = "SELECT COUNT( DISTINCT HIQCAPNo) as num_kits FROM worksheets WHERE  lab='$lab'  AND MONTH(daterun) BETWEEN '$startmonth' AND '$endmonth' AND YEAR(daterun)=  '$year' ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_kits = $samplerow['num_kits'];

    return $num_kits;
}

// periodic kits wasted
function periodickitswasted($lab, $quarterly, $year)
{

    if ($quarterly == 1) { //january - March
        $startmonth = 1;
        $endmonth = 3;
    } else if ($quarterly == 2) {
        $startmonth = 4;
        $endmonth = 6;
    } else if ($quarterly == 3) {
        $startmonth = 7;
        $endmonth = 9;
    } else if ($quarterly == 4) {
        $startmonth = 10;
        $endmonth = 12;
    }
    $samplequery = "SELECT COUNT( DISTINCT HIQCAPNo) as num_kits FROM kits_wasted WHERE  lab='$lab'  AND MONTH(daterun) BETWEEN '$startmonth' AND '$endmonth' AND YEAR(daterun)=  '$year' ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_kits = $samplerow['num_kits'];

    return $num_kits;
}

function getWorkingDays($startDate, $endDate, $holidays)
{

    //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
    //We add one to inlude both dates in the interval.
    $days = (strtotime($endDate) - strtotime($startDate)) / 86400 + 1;

    $no_full_weeks = floor($days / 7);

    $no_remaining_days = fmod($days, 7);

    //It will return 1 if it's Monday,.. ,7 for Sunday
    $the_first_day_of_week = date("N", strtotime($startDate));

    $the_last_day_of_week = date("N", strtotime($endDate));
    // echo              $the_last_day_of_week;
    //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
    //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
    if ($the_first_day_of_week <= $the_last_day_of_week) {
        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) {
            $no_remaining_days--;
        }

        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) {
            $no_remaining_days--;
        }

    } else {
        if ($the_first_day_of_week <= 6) {
            //In the case when the interval falls in two weeks, there will be a Sunday for sure
            $no_remaining_days--;
        }
    }

    //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
    //---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
    $workingDays = $no_full_weeks * 5;
    if ($no_remaining_days > 0) {
        $workingDays += $no_remaining_days;
    }

    //We subtract the holidays
    /*    foreach($holidays as $holiday){
    $time_stamp=strtotime($holiday);
    //If the holiday doesn't fall in weekend
    if (strtotime($startDate) <= $time_stamp && $time_stamp <= strtotime($endDate) && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
    $workingDays--;
    } */

    return $workingDays;
}

function GetTotalPrintedSamples($lab, $f, $p, $sfd, $dit)
{
    $query = "SELECT ID FROM samples WHERE 1=Case When '$dit'= '' Then 1 When '$dit'= (select district From facilitys Where facilitys.ID=samples.facility LIMIT 1) Then 1 End and 1 = Case When '$sfd' = '' Then 1  When '$sfd' = samples.datedispatched Then 1  END and 1 = Case When '$p' = '' Then 1  When '$p' = samples.printed Then 1  END and 1 = Case When '$f' = '' Then 1  When '$f' = samples.facility Then 1  END and samples.lab='$lab' AND samples.resultprinted=1 and flag=1";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

// the user information
function GetUserInfo($success)
{
    $user = "SELECT * FROM users WHERE id = '$success'";
    $user = mysql_query($user) or die(mysql_error());
    $userrec = mysql_fetch_array($user);
    return $userrec;
}

// the province information
function GetProvInfo($success)
{
    $user = "SELECT * FROM provinces WHERE Code = '$success'";
    $user = mysql_query($user) or die(mysql_error());
    $userrec = mysql_fetch_array($user);
    return $userrec;
}

// the district information
function GetDistInfo($success)
{
    $user = "SELECT * FROM districts WHERE id = '$success'";
    $user = mysql_query($user) or die(mysql_error());
    $userrec = mysql_fetch_array($user);
    return $userrec;
}

// the user account name
function UserAccountName($useraccount)
{
    $accountid = "SELECT name AS accountname FROM usergroups WHERE ID ='$useraccount'";
    $accountname = mysql_query($accountid);
    $aname = mysql_fetch_array($accountname);
    $showname = $aname['accountname'];
    return $showname;
}

function isbatchcomplete($batchno, $labss)
{
    $samplesperbatch = GetSamplesPerBatch($batchno, $labss);

    $rejectedasmples = GetRejectedSamplesPerBatch($batchno, $labss);

    $sampleswithresults = GetSamplesPerBatchwithResults($batchno, $labss);

    $noresult = $samplesperbatch - $rejectedasmples - $sampleswithresults;

    return $noresult;
}

function isbatchcompletev1($batchno)
{
    $samplesperbatch = GetSamplesPerBatchv1($batchno);

    $rejectedasmples = GetRejectedSamplesPerBatchv1($batchno);

    $sampleswithresults = GetSamplesPerBatchwithResultsv1($batchno);

    $noresult = $samplesperbatch - $rejectedasmples - $sampleswithresults;

    return $noresult;
}

function getsamplerunnumber($ID, $parentid, $result, $worksheet)
{
    if (($parentid == 0) && ($result > 0)) {
        $run = 1;
    } elseif (($parentid > 0) && ($result == 0) && ($worksheet > 0)) {
        $run = 2;
    } else {

    }

    return $run;
}

//get total number of worksheetsby type i.e manual or taqman
function Gettotalworksheetsbytype($type)
{
    $worksheettypequery = mysql_query("SELECT COUNT(ID) as 'worksheets' FROM worksheets where type='$type'");
    $worksheettyperesult = mysql_fetch_array($worksheettypequery);
    $worksheets = $worksheettyperesult['worksheets'];
    return $worksheets;
}

function rejectedsamplesforprinting($labss)
{
    $qury = "SELECT ID,batchno,patient,facility,datereceived,datetested,datemodified,result,datereleased,rejectedreason FROM samples
			WHERE samples.lab='$labss' AND  samples.receivedstatus=2 AND  samples.BatchComplete=1 AND  samples.resultprinted=0 and  samples.Flag=1 order by facility , datereleased ASC
			";

    $quryresult = mysql_query($qury) or die(mysql_error());
    $noofsamples = mysql_num_rows($quryresult);
    return $noofsamples;
}

//update the user record
function UpdateUser($surname, $oname, $email, $username, $user)
{
    $user = "UPDATE users SET surname='$surname',oname='$oname',email='$email',username='$username' WHERE (ID = '$user')";
    $usersaved = mysql_query($user) or die(mysql_error());
    return $usersaved;
}
