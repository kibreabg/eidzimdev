<?php

require_once('../connection/config.php');


/* * ..................................
  ADD samples functions ..........
  .................
  ...............* */

//get facility name
function GetFacility($autocode) {
    $facilityquery = mysql_query("SELECT name FROM facilitys where ID='$autocode' ") or die(mysql_error());
    $dd = mysql_fetch_array($facilityquery);
    $fname = $dd['name'];
    return $fname;
}

//get users name
function GetUserFullnames($userid) {
    $usersquery = mysql_query("SELECT surname,oname FROM users where ID='$userid' ") or die(mysql_error());
    $dd = mysql_fetch_array($usersquery);
    $sname = $dd['surname'];
    $onames = $dd['oname'];
    $names = $sname . ", " . $onames;
    return $names;
}

//get sample/patinet ID from lab ID
function GetActualPatientID($labid) {
    $samplequery = mysql_query("SELECT patient FROM samples where ID='$labid' ") or die(mysql_error());
    $dd = mysql_fetch_array($samplequery);
    $patient = $dd['patient'];

    return $patient;
}

//get lab email
function GetLabEmail($lab) {
    $samplequery = mysql_query("SELECT email FROM labs where ID='$lab' ") or die(mysql_error());
    $dd = mysql_fetch_array($samplequery);
    $email = $dd['email'];

    return $email;
}

//save district details
function Savedistrict($name, $province, $comment) {
    $savedistrict = "INSERT INTO districts(name,province,comment,flag)VALUES('$name','$province','$comment',1)";
    $districts = @mysql_query($savedistrict) or die(mysql_error());
    return $districts;
}

//save lab details
function Savelab($name, $initial, $email, $priority, $description) {
    $savelab = "INSERT INTO labs(name,initials,email,withresult,priority,description)VALUES('$name','$initial','$email','0','$priority','$description')";
    $labs = @mysql_query($savelab) or die(mysql_error());
    return $labs;
}

//get task name from task id
function GetTaskName($taskid) {
    $taskquery = mysql_query("SELECT name FROM tasks where ID='$taskid' ") or die(mysql_error());
    $dd = mysql_fetch_array($taskquery);
    $name = $dd['name'];

    return $name;
}

//get lab in wich facility belongs to
function GetLab($lab) {
    $labquery = mysql_query("SELECT name FROM labs where ID='$lab' ") or die(mysql_error());
    $dd = mysql_fetch_array($labquery);
    $labname = $dd['name'];
    return $labname;
}

//get facility code
function GetActualFacilityCode($fid) {
    $facilityquery = mysql_query("SELECT * FROM facilitys where ID='$fid' ") or die(mysql_error());
    $row = mysql_fetch_assoc($facilityquery);
    return $row;
}

//get samples pper facility per month
function Gettestedperfacilitypermonth($facility, $year, $month) {

    $strQuery = mysql_query("SELECT COUNT(ID) as 'monthlytestedsamples' FROM samples WHERE result > 0 AND facility='$facility' AND  YEAR(datetested)='$year' AND MONTH(datetested)='$month'") or die(mysql_error());
    $resultarray = mysql_fetch_array($strQuery);
    $monthlytestedsamples = $resultarray['monthlytestedsamples'];
    return $monthlytestedsamples;
}

//get samples pper facility per year
function Gettestedperfacilityperyear($facility, $year) {

    $strQuery = mysql_query("SELECT COUNT(ID) as 'yearlytestedsamples' FROM samples WHERE result > 0 AND facility='$facility' AND  YEAR(datetested)='$year' ") or die(mysql_error());
    $resultarray = mysql_fetch_array($strQuery);
    $yearlytestedsamples = $resultarray['yearlytestedsamples'];
    return $yearlytestedsamples;
}

//get samples pper facility per year per result type
function Gettestedperfacilityperyearperresult($facility, $year, $resulttype) {

    $strQuery = mysql_query("SELECT COUNT(ID) as 'testedsamples' FROM samples WHERE result ='$resulttype' AND facility='$facility' AND  YEAR(datetested)='$year' ") or die(mysql_error());
    $resultarray = mysql_fetch_array($strQuery);
    $testedsamples = $resultarray['testedsamples'];
    return $testedsamples;
}

//determine if batch exists
function GetBatchNoifExists($datereceived, $facility, $lab) {
    $strQuery = mysql_query("SELECT samples.batchno FROM samples,facilitys WHERE samples.datereceived='$datereceived' AND samples.facility='$facility' AND samples.facility=facilitys.ID AND facilitys.lab='$lab' ORDER by batchno DESC LIMIT 1") or die(mysql_error());
    $numrows = mysql_num_rows($strQuery);
    return $numrows;
}

//determine if batch exists
function GetExistingBatchNo($datereceived, $facility, $lab) {

    $strQuery = mysql_query("SELECT samples.batchno FROM samples,facilitys WHERE samples.datereceived='$datereceived' AND samples.facility='$facility' AND samples.facility=facilitys.ID AND facilitys.lab='$lab' ") or die(mysql_error());
    $dd = mysql_fetch_array($strQuery);
    $batch = $dd['batchno'];
    return $batch;
}

//generate new  batch no
function GetNewBatchNo($lab) {

    $RES = mysql_query("SELECT MAX(samples.batchno) as 'Max' FROM samples,facilitys 	WHERE  samples.facility=facilitys.ID AND facilitys.lab='$lab'");

    if (mysql_num_rows($RES) == 1) {
        $ROW = mysql_fetch_assoc($RES);
        $BatchNo = $ROW['Max'] + 1;
    }
    return $BatchNo;
}

//save mother details
function GetSavedMother($mhivstatus, $mentpoint, $mbfeeding, $mdrug, $fcode) {
    $motherrec = "INSERT INTO 		
mothers(facility,entry_point,feeding,prophylaxis,status)VALUES
('$fcode','$mentpoint','$mbfeeding','$mdrug','$mhivstatus')";
    $mother = @mysql_query($motherrec) or die(mysql_error());
    return $mother;
}

function SaveRepeatSamplesTask($task, $batchno, $status, $labid, $labss) {
//query pendin tasks table for id of mother just saved
    $repeatrec = "INSERT INTO 		
pendingtasks(task,batchno,status,sample,lab)VALUES
('$task','$batchno','$status','$labid','$labss')";
    $repeat = @mysql_query($repeatrec) or die(mysql_error());
    return $repeat;
}

function GetLastMotherID($lab) {
    $getmotherid = "SELECT mothers.ID
            FROM mothers,facilitys
				WHERE  mothers.facility=facilitys.ID AND facilitys.lab='$lab'
            ORDER by ID DESC LIMIT 1 ";
    $getmum = mysql_query($getmotherid);
    $mumrec = mysql_fetch_array($getmum);
    $mid = $mumrec['ID'];
    return $mid;
}

//save patient details
function GetSavedPatient($pid, $motherid, $age, $pdob, $pgender, $infantprophylaxis) {
    $child = "INSERT INTO 		
patients(ID,mother,age,dob,gender,prophylaxis)VALUES('$pid','$motherid','$age','$pdob','$pgender','$infantprophylaxis')";
    $patient = @mysql_query($child) or die(mysql_error());
    return $patient;
}

//save sampels details
function GetSavedSamples($BatchNo, $pid, $fcode, $srecstatus, $rejectedreason, $sspot, $sdoc, $sdrec, $scomments, $labcomment, $parentid, $repeatforrejection, $confirmatorypcr) {
    if ($parentid == "") {
        $parentid = 0;
    }
    $child = "INSERT INTO 		
samples(batchno,patient,facility,receivedstatus,rejectedreason,spots,datecollected,datereceived,comments,labcomment,parentid,repeatforrejection,confirmatorypcr)VALUES('$BatchNo','$pid','$fcode','$srecstatus','$rejectedreason','$sspot','$sdoc','$sdrec','$scomments','$labcomment','$parentid','$repeatforrejection','$confirmatorypcr')";
    $patient = @mysql_query($child) or die(mysql_error());
    return $patient;
}

/* * ..................................
  samples listings, view functions ..........
  .................
  ...............* */

//get patient id
function GetPatient($batchno) {
    $getbatch = "SELECT patient from samples WHERE batchno='$batchno'";
    $gotbatch = mysql_query($getbatch) or die(mysql_error());
    $batchrec = mysql_fetch_array($gotbatch);
    $patient = $batchrec['patient'];
    return $patient;
}

//get date received for a batch
function GetDatereceived($batchno) {
    $getdate = "SELECT datereceived from samples WHERE batchno='$batchno'";
    $gotdate = mysql_query($getdate) or die(mysql_error());
    $daterec = mysql_fetch_array($gotdate);
    $sdrec = $daterec['datereceived'];

    $sdrec = date("d-M-Y", strtotime($sdrec));
    return $sdrec;
}

//get miother id for patient
function GetMotherID($patient) {
    $getpatient = "SELECT mother FROM patients WHERE ID='$patient'";
    $gotpatient = mysql_query($getpatient) or die(mysql_error());
    $patientrec = mysql_fetch_array($gotpatient);
    $mid = $patientrec['mother'];
    return $mid;
}

//get patient gender
function GetPatientGender($patient) {
    $getpatient = "SELECT gender FROM patients WHERE ID='$patient'";
    $gotpatient = mysql_query($getpatient) or die(mysql_error());
    $patientrec = mysql_fetch_array($gotpatient);
    $pgender = $patientrec['gender'];

    return $pgender;
}

//get patient age
function GetPatientAge($patient) {
    $getpatient = "SELECT age FROM patients WHERE ID='$patient'";
    $gotpatient = mysql_query($getpatient) or die(mysql_error());
    $patientrec = mysql_fetch_array($gotpatient);
    $age = $patientrec['age'];

    return $age;
}

//get patient date of birth
function GetPatientDOB($patient) {
    $getpatient = "SELECT dob FROM patients WHERE ID='$patient'";
    $gotpatient = mysql_query($getpatient) or die(mysql_error());
    $patientrec = mysql_fetch_array($gotpatient);
    $dob = $patientrec['dob'];
    if ($dob != "") {
        $dob = date("d-M-Y", strtotime($dob));
    } else {
        $dob = "None";
    }
    return $dob;
}

//get patient prophylaxis
function GetPatientProphylaxis($patient) {
    $getpatient = "SELECT patients.prophylaxis,prophylaxis.name as 'infantprophylaxis' FROM patients,prophylaxis WHERE patients.ID='$patient' AND patients.prophylaxis=prophylaxis.ID";
    $gotpatient = mysql_query($getpatient) or die(mysql_error());
    $patientrec = mysql_fetch_array($gotpatient);
    $infantprophylaxis = $patientrec['infantprophylaxis'];

    return $infantprophylaxis;
}

//get facility code from mothers 
function GetFacilityCode($mid) {
    $getfcode = "SELECT facility FROM mothers WHERE ID='$mid'";
    $gotfcode = mysql_query($getfcode) or die(mysql_error());
    $fcoderec = mysql_fetch_array($gotfcode);
    $facility = $fcoderec['facility'];

    return $facility;
}

//get mothers hivstatus
function GetMotherHIVstatus($mid) {
    $getmother = "SELECT mothers.status,results.Name as 'HIV' FROM mothers,results WHERE mothers.ID='$mid' AND mothers.status=results.ID";
    $gotmother = mysql_query($getmother) or die(mysql_error());
    $motherrec = mysql_fetch_array($gotmother);
    $HIV = $motherrec['HIV'];

    return $HIV;
}

//get mothers pmtct intervention
function GetMotherProphylaxis($mid) {
    $getmother = "SELECT mothers.prophylaxis,prophylaxis.name as 'motherprophylaxis' FROM mothers,prophylaxis WHERE mothers.ID='$mid' AND mothers.prophylaxis=prophylaxis.ID";
    $gotmother = mysql_query($getmother) or die(mysql_error());
    $motherrec = mysql_fetch_array($gotmother);
    $motherprophylaxis = $motherrec['motherprophylaxis'];

    return $motherprophylaxis;
}

//get mothers feeding types
function GetMotherFeeding($mid) {
    $getmother = "SELECT mothers.feeding,feedings.name as 'motherfeeding' FROM mothers,feedings WHERE mothers.ID='$mid' AND mothers.feeding=feedings.ID";
    $gotmother = mysql_query($getmother) or die(mysql_error());
    $motherrec = mysql_fetch_array($gotmother);
    $motherfeeding = $motherrec['motherfeeding'];
    return $motherfeeding;
}

//get mothers feeding types description
function GetMotherFeedingDesc($mid) {
    $getmother = "SELECT mothers.feeding,feedings.description as 'feedingdesc' FROM mothers,feedings WHERE mothers.ID='$mid' AND mothers.feeding=feedings.ID";
    $gotmother = mysql_query($getmother) or die(mysql_error());
    $motherrec = mysql_fetch_array($gotmother);
    $feedingdesc = $motherrec['feedingdesc'];
    return $feedingdesc;
}

//get mothers entry point
function GetEntryPoint($mid) {
    $getmother = "SELECT mothers.entry_point,entry_points.name as 'entrypoint' FROM mothers,entry_points WHERE mothers.ID='$mid' AND mothers.entry_point=entry_points.ID";
    $gotmother = mysql_query($getmother) or die(mysql_error());
    $motherrec = mysql_fetch_array($gotmother);
    $entrypoint = $motherrec['entrypoint'];
    return $entrypoint;
}

//get total no of sampels per batch
//get total no of sampels per batch
function GetSamplesPerBatch($batchno) {
    $samplequery = "SELECT COUNT(ID) as num_samples FROM samples WHERE batchno='$batchno' AND parentid=0
		 ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];


    return $num_samples;
}

//get no of rejected samples per batch
function GetRejectedSamplesPerBatch($batchno) {
    $rejquery = "SELECT COUNT(ID) as rej_samples FROM samples WHERE batchno='$batchno' AND receivedstatus=2
 ";
    $rejresult = mysql_query($rejquery) or die('mysql_error()');
    $rejrow = mysql_fetch_array($rejresult, MYSQL_ASSOC);
    $rej_samples = $rejrow['rej_samples'];


    return $rej_samples;
}

//get no of rejected samples per batch in pedning tasks table
function GetRejectedSamplesPerBatchFromPendingTasks($batchno, $lab) {
    $rejquery = "SELECT COUNT(sample) as rej_samples FROM pendingtasks WHERE batchno='$batchno' AND task=6 AND status=0 AND lab='$lab'
 ";
    $rejresult = mysql_query($rejquery) or die('mysql_error()');
    $rejrow = mysql_fetch_array($rejresult, MYSQL_ASSOC);
    $rej_samples = $rejrow['rej_samples'];


    return $rej_samples;
}

//get no of rejected samples per batch in pedning tasks table that are complete
function GetTotalCompleteRejectedSamplesBatches($batchno, $lab) {

    $rejquery = "SELECT COUNT(sample) as rej_samples FROM pendingtasks WHERE batchno='$batchno' AND task=6 AND status=1 AND lab='$lab'
 ";
    $rejresult = mysql_query($rejquery) or die('mysql_error()');
    $rejrow = mysql_fetch_array($rejresult, MYSQL_ASSOC);
    $rej_samples = $rejrow['rej_samples'];


    return $rej_samples;
}

//get no of  samples per batch with results
function GetSamplesPerBatchwithResults($batchno) {
    $noresultsamplee = "SELECT COUNT(ID) as with_result_samples FROM samples WHERE  batchno='$batchno' AND result > 0 ";
    $noresultsampleresultt = mysql_query($noresultsamplee) or die(mysql_error());
    $noresultsampleresultroww = mysql_fetch_array($noresultsampleresultt, MYSQL_ASSOC);
    $with_result_samples = $noresultsampleresultroww['with_result_samples'];
    return $with_result_samples;
}

function getifcomplete($batchno) {
    $numsamples = GetSamplesPerBatch($batchno);
    $rejected = GetRejectedSamplesPerBatch($batchno);
    $negatives = GetSamplesPerBatchbyresultype($batchno, 1);
    $indeter = GetSamplesPerBatchbyresultype($batchno, 3);
    $pos = ($numsamples - ($negatives + $indeter));
    $posi = 0;

    for ($counter = 1; $counter <= $pos; $counter += 1) {
        $posres = mysql_query("select COUNT(ID) as pos_with_result_samples from samples where parent='labcode' AND  result >0 ") or die(mysql_error());
        $posresrow = mysql_fetch_array($posres, MYSQL_ASSOC);
        $pos_with_result_samples = $posresrow['pos_with_result_samples'];

        if ($pos_with_result_samples == 3) {
            $posi = posi + 1;
        }
    }

    $withresult = ($posi + $indeter + $negatives);
    $no_result_samples = (($numsamples - $withresult ) - $rejected);

    return $no_result_samples;
}

//get no of  samples per batch with particular result type
function GetSamplesPerBatchbyresultype($batchno, $resultype) {
    $noresultsamplee = "SELECT COUNT(ID) as with_result_samples FROM samples WHERE  batchno='$batchno' AND result = '$resultype' AND parentid=0";
    $noresultsampleresultt = mysql_query($noresultsamplee) or die(mysql_error());
    $noresultsampleresultroww = mysql_fetch_array($noresultsampleresultt, MYSQL_ASSOC);
    $with_result_samples = $noresultsampleresultroww['with_result_samples'];

    return $with_result_samples;
}

//get max date of test per batch
function GetMaxdateoftestperbatch($batchno) {
    $noresultsamplee = "SELECT MAX(datetested) as 'maxdate' FROM samples WHERE  batchno='$batchno'   ";
    $noresultsampleresultt = mysql_query($noresultsamplee) or die(mysql_error());
    $noresultsampleresultroww = mysql_fetch_array($noresultsampleresultt, MYSQL_ASSOC);
    $maxdate = $noresultsampleresultroww['maxdate'];
    $maxdate = date("d-M-Y", strtotime($maxdate));
    return $maxdate;
}

//get max date of result updated afetr test per batch
function GetMaxdateupdatedperbatch($batchno) {
    $noresultsamplee = "SELECT MAX(datemodified) as 'updateddate' FROM samples WHERE  batchno='$batchno'  ";
    $noresultsampleresultt = mysql_query($noresultsamplee) or die(mysql_error());
    $noresultsampleresultroww = mysql_fetch_array($noresultsampleresultt, MYSQL_ASSOC);
    $updateddate = $noresultsampleresultroww['updateddate'];
    $updateddate = date("d-M-Y", strtotime($updateddate));
    return $updateddate;
}

//get max ID of sample with restets
function GetMaxLabIDforRetest($parent) {
    $noresultsamplee = "SELECT MAX(ID) as 'maxid' FROM samples WHERE  parentid='$parent'   ";
    $noresultsampleresultt = mysql_query($noresultsamplee) or die(mysql_error());
    $noresultsampleresultroww = mysql_fetch_array($noresultsampleresultt, MYSQL_ASSOC);
    $maxid = $noresultsampleresultroww['maxid'];
    return $maxid;
}

//get date batch was dispatched
function Getbatchdateofdispatch($batchno) {
    $noresultsamplee = "SELECT MAX(datedispatched) as 'dateofdispatch' FROM samples WHERE  batchno='$batchno'  ";
    $noresultsampleresultt = mysql_query($noresultsamplee) or die(mysql_error());
    $noresultsampleresultroww = mysql_fetch_array($noresultsampleresultt, MYSQL_ASSOC);
    $dateofdispatch = $noresultsampleresultroww['dateofdispatch'];
    $dateofdispatch = date("d-M-Y", strtotime($dateofdispatch));
    return $dateofdispatch;
}

//determine total number of batches
function GetTotalNoBatches($labss) {
    $query = "SELECT DISTINCT samples.batchno   FROM samples,facilitys
					WHERE samples.facility=facilitys.ID AND facilitys.lab='$labss'";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

//determine total number of batches wit complete results 
function GetTotalCompleteBatches($state, $labss) {
    $query = "SELECT DISTINCT batchno FROM samples,facilitys
					WHERE samples.facility=facilitys.ID AND facilitys.lab='$labss' AND samples.BatchComplete='$state'";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

//determine total number of batches wit complete results 
function GetRejectedSamplesAwaitingDispatch($labss) {
    $query = "SELECT task_id,task, batchno,sample  FROM pendingtasks
					WHERE status=0 AND task=6 AND lab='$labss' GROUP BY batchno";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

//get results based on sample code
function GetSampleResult($ID) {
    $getdate = "SELECT results.Name as 'outcome' from samples,results WHERE samples.result = results.ID AND samples.ID='$ID'";
    $gotdate = mysql_query($getdate) or die(mysql_error());
    $daterec = mysql_fetch_array($gotdate);
    $outcome = $daterec['outcome'];
    return $outcome;
}

//get received status based on ID
function GetReceivedStatus($receivedstatus) {
    $getdate = "SELECT Name as 'status' from receivedstatus WHERE ID ='$receivedstatus'";
    $gotdate = mysql_query($getdate) or die(mysql_error());
    $daterec = mysql_fetch_array($gotdate);
    $status = $daterec['status'];
    return $status;
}

//determine total number of repeated samples (all parent samples that turned positive n needed retest)
function GetTotalRepeatParentSamples($labss) {
    $query = "SELECT samples.ID,samples.patient,samples.datereceived,samples.spots,samples.datecollected,samples.receivedstatus,samples.facility
					FROM samples,facilitys
					WHERE ((samples.repeatt=1) AND ((samples.parentid=0)OR(samples.parentid IS NULL))) AND samples.facility=facilitys.ID AND facilitys.lab='$labss'
					ORDER BY samples.ID DESC";
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

function GetDistrictID($facility) {
    $districtidquery = mysql_query("SELECT district
            FROM facilitys
            WHERE  ID='$facility'");
    $noticia = mysql_fetch_array($districtidquery);

    $distid = $noticia['district'];
    return $distid;
}

//get distrcit name
function GetDistrictName($distid) {
    $districtnamequery = mysql_query("SELECT name 
            FROM districts
            WHERE  ID='$distid'");
    $districtname = mysql_fetch_array($districtnamequery);
    $distname = $districtname['name'];
    return $distname;
}

//get province id
function GetProvid($distid) {
    $districtnamequery = mysql_query("SELECT province
            FROM districts
            WHERE  ID='$distid'");
    $districtname = mysql_fetch_array($districtnamequery);
    $provid = $districtname['province'];
    return $provid;
}

//get province name
function GetProvname($provid) {
    $provincenamequery = mysql_query("SELECT name 
            FROM provinces
            WHERE  ID='$provid'");
    $provincename = mysql_fetch_array($provincenamequery);
    $provname = $provincename['name'];
    return $provname;
}

//determine if sample is a repeat or normal samples
function getRepeatValue($paroid, $lab) {
    $repeatquery = mysql_query("SELECT samples.repeatt as 'repeatt'
            FROM samples,facilitys
            WHERE samples.facility=facilitys.ID AND facilitys.lab='$lab' AND samples.ID='$paroid'");
    $repeatname = mysql_fetch_array($repeatquery);
    $repeatvalue = $repeatname['repeatt'];
    return $repeatvalue;
}

//get the labcode parent id		
function getParentID($labid, $lab) {
    $parentquery = mysql_query("SELECT samples.parentid  as 'parentid'
            FROM samples,facilitys
            WHERE samples.facility=facilitys.ID AND facilitys.lab='$lab' AND  samples.ID='$labid'");
    $parentname = mysql_fetch_array($parentquery);
    $parentvalue = $parentname['parentid'];
    return $parentvalue;
}

//get no of repeats for that parent id
function GetNoofRetests($parentid, $lab) {

    $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'noofrepeats' FROM samples,facilitys WHERE samples.facility=facilitys.ID AND facilitys.lab='$lab'  AND samples.parentid='$parentid' ") or die(mysql_error());
    $resultarray = mysql_fetch_array($strQuery);
    $testedsamples = $resultarray['noofrepeats'];
    return $testedsamples;
}

//generate new  worksheet no
function GetNewWorksheetNo() {

    $RES = mysql_query("SELECT MAX(ID) as 'Max' FROM worksheets");

    if (mysql_num_rows($RES) == 1) {
        $ROW = mysql_fetch_assoc($RES);
        $worksheetno = $ROW['Max'] + 1;
    }
    return $worksheetno;
}

//get total number of samples
function Gettotalsamples($labss) {
    $provincenamequery = mysql_query("SELECT COUNT(samples.ID) as 'totalsamples'
           FROM samples,facilitys
					WHERE samples.facility=facilitys.ID AND facilitys.lab='$labss'
				
            ");
    $provincename = mysql_fetch_array($provincenamequery);
    $totalsamples = $provincename['totalsamples'];
    return $totalsamples;
}

//get total number of worksheets
function Gettotalworksheets() {
    $provincenamequery = mysql_query("SELECT COUNT(ID) as 'worksheets'
            FROM worksheets
            ");
    $provincename = mysql_fetch_array($provincenamequery);
    $worksheets = $provincename['worksheets'];
    return $worksheets;
}

//get total number of complete worksheets
function Gettotalcompleteworksheets() {
    $provincenamequery = mysql_query("SELECT COUNT(ID) as 'worksheets'
            FROM worksheets where flag=2
            ");
    $provincename = mysql_fetch_array($provincenamequery);
    $worksheets = $provincename['worksheets'];
    return $worksheets;
}

//get total number of repeat worksheets
function GettotalRepeatworksheets() {
    $provincenamequery = mysql_query("SELECT COUNT(ID) as 'worksheets'
            FROM worksheets where type=1
            ");
    $provincename = mysql_fetch_array($provincenamequery);
    $worksheets = $provincename['worksheets'];
    return $worksheets;
}

//get total number of pending  worksheets

function GettotalPendingworksheets() {
    $provincenamequery = mysql_query("SELECT COUNT(ID) as 'worksheets'
            FROM worksheets where flag=0
            ");
    $provincename = mysql_fetch_array($provincenamequery);
    $worksheets = $provincename['worksheets'];
    return $worksheets;
}

//get total no of sampels per worksheet
function GetSamplesPerworksheet($worksheet) {
    $samplequery = "SELECT COUNT(ID) as num_samples FROM samples WHERE worksheet='$worksheet'
		 ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

//get total no of sampels per worksheet for repeat
function GetRepeatSamplesPerworksheet($worksheet) {
    $samplequery = "SELECT COUNT(ID) as num_samples FROM samples WHERE worksheet='$worksheet' AND 	inrepeatworksheet  = 1
		 ";
    $sampleresult = mysql_query($samplequery) or die(mysql_error());
    $samplerow = mysql_fetch_array($sampleresult, MYSQL_ASSOC);
    $num_samples = $samplerow['num_samples'];

    return $num_samples;
}

//get worksheet details
function getWorksheetDetails($wno) {
    $qury = "SELECT *
            FROM worksheets
            WHERE ID= '$wno'";
    $reslt = mysql_query($qury) or die(mysql_error());
    $row = mysql_fetch_assoc($reslt);
    return $row;
}

//get sample details
function getSampleetails($sample) {
    $qury = "SELECT *
            FROM samples
            WHERE ID= '$sample'";
    $reslt = mysql_query($qury) or die(mysql_error());
    $row = mysql_fetch_assoc($reslt);
    return $row;
}

//get facility details
function getFacilityDetails($fcode) {
    $qury = "SELECT *
            FROM facilitys
            WHERE ID= '$fcode'";
    $reslt = mysql_query($qury) or die(mysql_error());
    $row = mysql_fetch_assoc($reslt);
    return $row;
}

//get samples in batch
function getBatchDetails($batchno) {
    $qury = "SELECT *
            FROM samples
            WHERE batchno= '$batchno'";
    $reslt = mysql_query($qury) or die(mysql_error());
    $row = mysql_fetch_assoc($reslt);
    return $row;
}

//get requisition details
function getRequisitionDetails($rno) {
    $qury = "SELECT *
            FROM requisitions
            WHERE id= '$rno'";
    $reslt = mysql_query($qury) or die(mysql_error());
    $row = mysql_fetch_assoc($reslt);
    return $row;
}

//save user details
function SaveUser($surname, $oname, $telephone, $postal, $email, $account, $username, $password, $lab, $datecreated) {

    $saved = "INSERT INTO 		
users(surname,oname,telephone,postal,email,account,lab,username,password,flag,datecreated)VALUES('$surname','$oname','$telephone','$postal','$email','$account','$lab','$username','$password',1,'$datecreated')";
    $users = @mysql_query($saved) or die(mysql_error());
    return $users;
}

//save user groups
function SaveUserGroup($groupname) {
    $saved = "INSERT INTO 		
usergroups(name)VALUES('$groupname')";
    $users = @mysql_query($saved) or die(mysql_error());
    return $users;
}

//save menus
function SaveMenu($menu, $url, $location) {
    $saved = "INSERT INTO 		
menus(name,url,location)VALUES('$menu','$url','$location')";
    $users = @mysql_query($saved) or die(mysql_error());
    return $menus;
}

function GetTotalMenus() {
    $query = "SELECT ID  FROM menus";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

function GetTotalUserActivity() {
    $query = "SELECT TaskID  FROM usersactivity";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

function GetTotalSingleUserActivity($user) {
    $query = "SELECT TaskID  FROM usersactivity where user=$user";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

//get total users
function GetTotalUsers() {
    $query = "SELECT ID  FROM users where flag=1";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

//get rejected reason
function GetRejectedReason($ID) {
    $query = "SELECT Name  FROM rejectedreasons where ID='$ID'";
    $result = mysql_query($query) or die(mysql_error());
    $dd = mysql_fetch_array($result);
    $rejreason = $dd['Name'];
    return $rejreason;
}

//get total distrcts
function GetTotalDistricts() {
    $query = "SELECT ID  FROM districts where flag=1";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

//get total facilities
function GetTotalFacilities() {
    $query = "SELECT ID  FROM facilitys where Flag=1";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    return $numrows;
}

//get total samples for repeat
function GetTotalRepeatSamples() {
    $query = "select * from samples where parentid IS NOT NULL AND inrepeatworksheet =0";
    $result = mysql_query($query) or die('Error, query failed');
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    $numrows = $row['numrows'];
    return $numrows;
}

//save facility details
function Savefacility($code, $name, $district, $lab, $postal, $telephone, $otelephone, $fax, $email, $fullname, $contacttelephone, $ocontacttelephone, $contactemail) {
    $saved = "INSERT INTO 		
facilitys(facilitycode,name,district,lab,physicaladdress,telephone,telephone2,fax,email,contactperson,contacttelephone,contacttelephone2,flag,ContactEmail)VALUES('$code','$name','$district','$lab','$postal','$telephone','$otelephone','$fax','$email','$fullname','$contacttelephone','$ocontacttelephone',1,'$contactemail')";
    $users = @mysql_query($saved) or die(mysql_error());
    return $users;
}

//save user activity
function SaveUserActivity($userid, $leo, $task, $tasktime, $patient) {
    $activity = "INSERT INTO 		
usersactivity(user,accessdate,task,timetaskdone,patient)VALUES('$userid','$leo','$task','$tasktime','$patient')";
    $useractivity = @mysql_query($activity) or die(mysql_error());
    return $useractivity;
}

//get account type name
function GetAccountType($account) {
    $userquery = mysql_query("SELECT name FROM usergroups where ID='$account' ") or die(mysql_error());
    $dd = mysql_fetch_array($userquery);
    $grupname = $dd['name'];
    return $grupname;
}

//get sample date of testting based on id
function GetSampleDateofTest($parentid) {
    $datequery = mysql_query("SELECT datetested FROM samples where ID='$parentid' ") or die(mysql_error());
    $dd = mysql_fetch_array($datequery);
    $datetested = $dd['datetested'];
    $datetested = date("d-M-Y", strtotime($datetested));
    return $datetested;
}

//get sample date of testting based on id
function GetSampleResultbasedonparentid($parentid) {
    $resultquery = mysql_query("SELECT result FROM samples where ID='$parentid' ") or die(mysql_error());
    $dd = mysql_fetch_array($resultquery);
    $outcome = $dd['result'];
    return $outcome;
}

//get menu name
function GetMenuName($menu) {
    $menuquery = mysql_query("SELECT name FROM menus where ID='$menu' ") or die(mysql_error());
    $dd = mysql_fetch_array($menuquery);
    $menuname = $dd['name'];
    return $menuname;
}

function GetMenuUrl($menu) {
    $menuquery = mysql_query("SELECT url FROM menus where ID='$menu' ") or die(mysql_error());
    $dd = mysql_fetch_array($menuquery);
    $menuurl = $dd['url'];
    return $menuurl;
}

//get sample result
function GetResultType($result) {
    $result = "SELECT name FROM results WHERE ID = '$result'";
    $getresult = mysql_query($result) or die(mysql_error());
    $resulttype = mysql_fetch_array($getresult);
    $showresult = $resulttype['name'];

    return $showresult;
}

//get ID baased on name and table
function GetIDfromtableandname($names, $tabl) {
    $menuquery = mysql_query("SELECT ID FROM $tabl where name='$names' ") or die(mysql_error());
    $dd = mysql_fetch_array($menuquery);
    $ID = $dd['ID'];
    return $ID;
}

function gettotalpendingtasks() {

//total no of batches awaiting testing
    $batchesfordispatch = mysql_query("SELECT task_id,task,batchno
            FROM pendingtasks
			WHERE status=0 AND task=2
			ORDER BY batchno ASC
			") or die(mysql_error());

    $noofbatches = mysql_num_rows($batchesfordispatch);

    //total no of rejected samples awaiting dispatch
    $rejectedsamplesfordispatch = mysql_query("SELECT task_id,task,batchno
            FROM pendingtasks
			WHERE status=0 AND task=6
			ORDER BY batchno ASC
			") or die(mysql_error());
    $noofrejsamples = mysql_num_rows($rejectedsamplesfordispatch);


//total sampels for repeat
    $samplesforrepeat = mysql_query("SELECT task_id,task,batchno,sample
            FROM pendingtasks
			WHERE status=0 AND task=3
			ORDER BY batchno ASC
			") or die(mysql_error());


    $noofsamples = mysql_num_rows($samplesforrepeat);
//total number of samples awaiting testing
    $samplesfortest = mysql_query("SELECT task_id,task, batchno FROM pendingtasks
					WHERE status=0 AND task=1 ") or die(mysql_error());
    $samplesawaitingtest = 0;
    while (list($task_id, $task, $batchno) = mysql_fetch_array($samplesfortest)) {
        $samplescount = mysql_query("SELECT ID
FROM   samples
WHERE batchno='$batchno' AND  result IS NULL") or die(mysql_error());
        $totalsamples = mysql_num_rows($samplescount);
        $samplesawaitingtest = $samplesawaitingtest + $totalsamples;
    }
    $totaltasks = ($noofbatches + $noofsamples + $samplesawaitingtest + $noofrejsamples);

    return $totaltasks;
}

function samplesawaitingtests() {
    $samplesfortest = mysql_query("SELECT task_id,task, batchno FROM pendingtasks
					WHERE status=0 AND task=1 ") or die(mysql_error());
    $samplesawaitingtest = 0;
    while (list($task_id, $task, $batchno) = mysql_fetch_array($samplesfortest)) {
        $samplescount = mysql_query("SELECT ID
FROM   samples
WHERE batchno='$batchno' AND  result IS NULL") or die(mysql_error());
        $totalsamples = mysql_num_rows($samplescount);
        $samplesawaitingtest = $samplesawaitingtest + $totalsamples;
    }


    return $samplesawaitingtest;
}

//get sample Lab ID of  last saved sample record
function GetLastSampleID($lab) {
    $getsampleid = "SELECT samples.ID
            FROM samples,facilitys
			WHERE  samples.facility=facilitys.ID AND facilitys.lab='$lab'
			ORDER by ID DESC LIMIT 1 ";
    $getsample = mysql_query($getsampleid);
    $samplerec = mysql_fetch_array($getsample);
    $sid = $samplerec['ID'];
    return $sid;
}

//get requsition no of last saved requisition record
function GetLastRequisitionID() {
    $getreqid = "SELECT id
            FROM requisitions
            ORDER by id DESC LIMIT 1 ";
    $getreq = mysql_query($getreqid);
    $reqrec = mysql_fetch_array($getreq);
    $rid = $reqrec['ID'];
    return $rid;
}

//save requisition
function SaveRequisition($fcode, $dbs, $ziploc, $dessicants, $rack, $glycline, $humidity, $lancets, $comments, $datecreated, $parentid, $disapprovecomments, $ecomments, $requisitiondate, $datemodified) {
    if ($parentid == "" && $disapprovecomments == "" && $approvecomments == "") {
        $parentid = "";
        $disapprovecomments = "";
        $approvecomments = "";
    }
    $saved = "INSERT INTO 		
requisitions(facility,dbs,ziploc,dessicants,rack,glycline,humidity,lancets,comments,datecreated,flag,parentid,approvecomments,disapprovecomments,requisitiondate,datemodified)VALUES('$fcode','$dbs','$ziploc','$dessicants','$rack','$glycline','$humidity','$lancets','$comments','$datecreated',1,'$parentid','$ecomments','$disapprovecomments','$requisitiondate','$datemodified')";
    $requisitions = mysql_query($saved) or die(mysql_error());
    return $requisitions;
}

//get any date
function GetAnyDateMin() {
    $getanydate = "SELECT YEAR(MIN(datereceived)) AS lowdate FROM samples WHERE flag=1 AND datereceived > 0";
    $anydate = mysql_query($getanydate) or die(mysql_error());
    $dateresult = mysql_fetch_array($anydate);
    $showdate = $dateresult['lowdate'];

    return $showdate;
}

//get maximum year
function GetMaxYear() {
    $getmaxyear = "SELECT MAX( YEAR( datereceived ) ) AS maximumyear FROM samples WHERE flag =1 ";
    $maxyear = mysql_query($getmaxyear) or die(mysql_error());
    $year = mysql_fetch_array($maxyear);
    $showyear = $year['maximumyear'];

    return $showyear;
}

function GetReqMin() {
    $getanydate = "SELECT YEAR(MIN(datecreated)) AS lowdate FROM requisitions WHERE flag=1 AND datecreated > 0";
    $anydate = mysql_query($getanydate) or die(mysql_error());
    $dateresult = mysql_fetch_array($anydate);
    $showdate = $dateresult['lowdate'];

    return $showdate;
}

//get requisition details
function GetRequisitionInfo($db) {
    $req = "SELECT dbs,dessicants,glycline,lancets,ziploc,rack,humidity,comments,datecreated,requisitiondate,datemodified,parentid,approvecomments FROM requisitions WHERE ID = '$db'";
    $getreq = mysql_query($req) or die(mysql_error());
    $requisition = mysql_fetch_array($getreq);

    return $requisition;
}

//update requisition details
function UpdateRequisition($db, $edbs, $eziploc, $edessicants, $erack, $eglycline, $ehumidity, $elancets, $ecomments, $datemodified) {
    $req = "UPDATE requisitions SET dbs='$edbs',ziploc='$eziploc',dessicants='$edessicants',rack='$erack',glycline='$eglycline',humidity='$ehumidity',lancets='$elancets',comments='$ecomments',datemodified='$datemodified' WHERE id = '$db'";
    $getreq = mysql_query($req) or die(mysql_error());

    return $getreq;
}

//delete requisition details
function DeleteRequisition($db, $datemodified) {
    $delreq = "UPDATE 		
requisitions SET flag=0, datemodified='$datemodified' WHERE id = '$db'";
    $deletedreq = mysql_query($delreq) or die(mysql_error());

    return $deletedreq;
}

//get date dispatched for rejected sample
function GetDateDispatchedforRejectedSample($samplecode) {
    $noresultsamplee = "SELECT dateupdated FROM pendingtasks WHERE  sample='$samplecode' AND task=6  ";
    $noresultsampleresultt = mysql_query($noresultsamplee) or die(mysql_error());
    $noresultsampleresultroww = mysql_fetch_array($noresultsampleresultt, MYSQL_ASSOC);
    $dateupdated = $noresultsampleresultroww['dateupdated'];
    return $dateupdated;
}

//get lab name
function GetLabNames($lab) {

    $facilityquery = mysql_query("SELECT name FROM labs where ID='$lab' ") or die(mysql_error());
    $dd = mysql_fetch_array($facilityquery);
    $labname = $dd['name'];
    return $labname;
}

//get approved requisition details
function GetApprovedRequisitionInfo($db) {
    $approvedreq = "SELECT dbs,dessicants,glycline,lancets,ziploc,rack,humidity,comments,datecreated,requisitiondate,datemodified FROM requisitions WHERE parentid = '$db'";
    $appreq = mysql_query($approvedreq) or die(mysql_error());
    $apprequisition = mysql_fetch_array($appreq);

    return $apprequisition;
}

?>