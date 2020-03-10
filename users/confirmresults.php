<?php
session_start();
include '../includes/header.php';
require_once 'classes/tc_calendar.php';
//Insert the path where you unpacked log4php
require_once 'log/Logger.php';
//Tell log4php to use our configuration file.
Logger::configure('configureLog4PHP.xml');
$logger = Logger::getLogger("main");

$worksheetno = $_GET['q'];
$success = $_GET['p'];
$worksheet = getWorksheetDetails($worksheetno);
extract($worksheet);
$datecreated = date("d-M-Y", strtotime($datecreated));
$datereviewed = date("Y-m-d");
$datereviewedd = date("d-M-Y", strtotime($datereviewed));
$samplesPerRow = 3;
$creator = GetUserFullnames($createdby);
$userid = $_SESSION['uid']; //id of user who is updating the record
$reviewedby = GetUserFullnames($userid);
$labss = $_SESSION['lab'];

if ($kitexpirydate != "") {
    $kitexpirydate = date("d-M-Y", strtotime($kitexpirydate));
}
if ($datecut != "") {
    $datecut = date("d-M-Y", strtotime($datecut));
} else {
    $datecut = "";
}
if ($daterun != "") {
    $daterun = date("d-M-Y", strtotime($daterun));
} else {
    $daterun = "";
}
//confirm results and final save
if ($_REQUEST['SaveWorksheet']) {
    $waksheetno = $_POST['waksheetno'];
    $serialno = $_POST['serialno'];
    $labcode = $_POST['labcode'];
    $outcome = $_POST['testresult'];
    $dateresultsupdated = date('Y-m-d');

    foreach ($labcode as $a => $b) {
        $paroid = getParentID($labcode[$a], $labss); //get parent id
        $testresultID = GetIDfromtableandname($outcome[$a], "results");
        // Repeat samples (The children of the samples to be repeated)
        if ($paroid > 0) {
            //Update status of worksheet
            $updateworksheetrec = mysql_query("UPDATE worksheets SET Flag = 1, reviewedby='$userid', datereviewed='$datereviewed'
                        WHERE ( ((worksheetno = '$waksheetno') AND (ID='$serialno')) )") or die(mysql_error());
            //Determine if sample is repeat or not (Get the result of the parent sample)
            $parentresult = getparentsampleresult($paroid, $labss);
            //Determine if this sample has a brother with a same parent ID
            $noofretests = GetNoofRetests($paroid, $labss);
            //Check if this repeated sample has a brother (or a duplicate) and get it's result
            $secondduplicateresult = GetDuplicateSampleResult($paroid, $labcode[$a]);
            //Get the ID of the duplicate brother
            $secondsampeID = GetDuplicateSampleResultID($paroid, $labcode[$a]);
            //Get the blood spot available for test (Max spots are 5)
            $bloodspot = GetBloodSpot($labcode[$a]);

            // retest done in duplicates and the 2nd duplicate also has a result
            if (($noofretests == 2) && ($secondduplicateresult > 0)) {

                //Update pending tasks
                $repeatresults = mysql_query("UPDATE pendingtasks SET status = 1 WHERE sample='$labcode[$a]' AND (task = 3 OR task = 1)") or die(mysql_error());

                if (($testresultID == 1) && ($secondduplicateresult == 1)) {
                    //Both Negative is Final Negative
                    $resultsrec = mysql_query("UPDATE samples SET result = 1, repeatt =  0, BatchComplete = 2 WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    //Update the other duplicate as non usable sample
                    $resultsrec = mysql_query("UPDATE samples SET repeatt = 1, BatchComplete = 0 WHERE (ID='$secondsampeID')") or die(mysql_error());
                } else if ((($testresultID == 1) && ($secondduplicateresult == 6)) || (($testresultID == 6) && ($secondduplicateresult == 1))
                    || (($testresultID == 2) && ($secondduplicateresult == 6)) || (($testresultID == 6) && ($secondduplicateresult == 2))
                    || (($testresultID == 3) && ($secondduplicateresult == 6)) || (($testresultID == 6) && ($secondduplicateresult == 3))) {
                    //1 Negative, 1 Failed OR 1 Positive, 1 Failed OR 1 Indeterminate, 1 Failed the failed goes back to be repeated

                    if ($testresultID == 6) {
                        //Make the Failed sample Interim, (Non Reportable, Printable)
                        $resultsrec = mysql_query("UPDATE samples SET repeatt = 1, BatchComplete = 0 WHERE (ID='$labcode[$a]')") or die(mysql_error());
                        //Update the other duplicate as non usable sample as well
                        $resultsrec = mysql_query("UPDATE samples SET repeatt = 1, BatchComplete = 0 WHERE (ID='$secondsampeID')") or die(mysql_error());

                        //Get details of the Failed sample to be re saved for re test
                        $repeatsampledetails = getSampleetails($labcode[$a]);
                        extract($repeatsampledetails);
                        $failedBrotherID = $secondsampeID;
                        $bloodspot = 4;
                        //Save the Failed sample detail with new ID with the parent ID of the failed sample
                        $saverepeatsample = SaveSampleWithResult($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, '', $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno, $bloodspot, $failedBrotherID);

                        //save pending task
                        $task = 3;
                        $status = 0;
                        $lastid = GetLastSampleID($lab);
                        $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $lab);
                        //save complete batch
                        $resultsrec = mysql_query("UPDATE samples SET inputcomplete = 1 , approved = 1 WHERE (ID = '$lastid')") or die(mysql_error());
                    } else if ($secondduplicateresult == 6) {
                        //Make the Failed sample Interim, (Non Reportable, Printable)
                        $resultsrec = mysql_query("UPDATE samples SET repeatt = 1, BatchComplete = 0 WHERE (ID='$secondsampeID')") or die(mysql_error());
                        //Update the other duplicate as non usable sample
                        $resultsrec = mysql_query("UPDATE samples SET repeatt = 1, BatchComplete = 0 WHERE (ID='$labcode[$a]')") or die(mysql_error());
                        //Get details of the Failed sample to be re saved for re test
                        //Get details of the Failed sample to be re saved for re test
                        $repeatsampledetails = getSampleetails($secondsampeID);
                        extract($repeatsampledetails);
                        $failedBrotherID = $labcode[$a];
                        $bloodspot = 4;
                        //Save the Failed sample detail with new ID with the parent ID of the failed sample
                        $saverepeatsample = SaveSampleWithResult($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $secondsampeID, $rejectedreason, $reason_for_repeat, '', $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno, $bloodspot, $failedBrotherID);

                        //Save pending task
                        $task = 3;
                        $status = 0;
                        $lastid = GetLastSampleID($lab);
                        $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $lab);
                        //save complete batch
                        $resultsrec = mysql_query("UPDATE samples SET inputcomplete = 1 , approved = 1 WHERE (ID = '$lastid')") or die(mysql_error());

                    }
                } else if (($testresultID == 2) && ($secondduplicateresult == 2)) {
                    //Both Positive will be final Positive
                    $resultsrec = mysql_query("UPDATE samples SET result = 2, repeatt = 0, BatchComplete = 2 WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    //Update the other duplicate as a complete batch because it's of no use now
                    $resultsrec = mysql_query("UPDATE samples SET repeatt = 1, BatchComplete = 0 WHERE (ID='$secondsampeID')") or die(mysql_error());
                } else if ((($testresultID == 2) && ($secondduplicateresult == 3)) || (($testresultID == 3) && ($secondduplicateresult == 2))) {
                    //1 Positive, 1 Indeterminate Final Positive
                    if ($testresultID == 2) {
                        //Final one with positive result gets to be ready for dispatch
                        $resultsrec = mysql_query("UPDATE samples SET result = '$testresultID', repeatt = 0, BatchComplete = 2 WHERE (ID='$labcode[$a]')") or die(mysql_error());
                        //Update the other duplicate as non usable sample
                        $resultsrec = mysql_query("UPDATE samples SET result = '$secondduplicateresult', repeatt = 1, BatchComplete = 0 WHERE (ID='$secondsampeID')") or die(mysql_error());

                    } elseif ($testresultID == 3) {
                        //Final one with positive result gets to be ready for dispatch
                        $resultsrec = mysql_query("UPDATE samples SET result = '$secondduplicateresult', repeatt = 0, BatchComplete = 2 WHERE (ID='$secondsampeID')") or die(mysql_error());
                        //Update the other duplicate as non usable sample
                        $resultsrec = mysql_query("UPDATE samples SET result = '$testresultID', repeatt = 1, BatchComplete = 0 WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    }
                } else if ((($testresultID == 1) && ($secondduplicateresult == 2)) || (($testresultID == 2) && ($secondduplicateresult == 1))) {
                    //1 Positive, 1 Negative Collect New Sample: (Make both Interim and create a new one and make it Reportable Collect New Sample)
                    $resultsrec = mysql_query("UPDATE samples SET repeatt = 1 , BatchComplete = 0 WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    $resultsrec = mysql_query("UPDATE samples SET repeatt = 1 , BatchComplete = 0 WHERE (ID='$secondsampeID')") or die(mysql_error());

                    //Get details of one of the samples to be re saved for re test
                    $repeatsampledetails = getSampleetails($labcode[$a]);
                    extract($repeatsampledetails);
                    //Create a mirror sample for one of the samples by giving it a parent ID of this sample and give it a result of 5 (Collect New Sample)
                    $result = 5;
                    $bloodspot = GetMaxBloodSpot($patient) + 1;
                    
                    if ($bloodspot < 5) {
                        $saverepeatsample = SaveSampleWithResult($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $result, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno, $bloodspot, $failedBrotherID);
                        $lastid = GetLastSampleID($lab);
                        //Save complete batch
                        $resultsrec = mysql_query("UPDATE samples SET repeatt = 0, BatchComplete = 2, inputcomplete = 1, approved = 1 WHERE (ID = '$lastid')") or die(mysql_error());
                    }
                } else if (($testresultID == 3) && ($secondduplicateresult == 3)) {
                    //Both Indeterminate Collect New Sample: (Make both Interim and create a new one and make it Reportable Collect New Sample)
                    $resultsrec = mysql_query("UPDATE samples SET repeatt = 1 , BatchComplete = 0 WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    $resultsrec = mysql_query("UPDATE samples SET repeatt = 1 , BatchComplete = 0 WHERE (ID='$secondsampeID')") or die(mysql_error());

                    //Get details of one of the samples to be re saved for re test
                    $repeatsampledetails = getSampleetails($labcode[$a]);
                    extract($repeatsampledetails);
                    //Create a mirror sample for one of the samples by giving it a parent ID of this sample and give it a result of 5 (Collect New Sample)
                    $result = 5;
                    $bloodspot = GetMaxBloodSpot($patient) + 1;

                    if ($bloodspot < 5) {
                        $saverepeatsample = SaveSampleWithResult($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $result, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno, $bloodspot, $failedBrotherID);
                        $lastid = GetLastSampleID($lab);
                        //Make the new sample Printable & Reportable
                        $resultsrec = mysql_query("UPDATE samples SET repeatt = 0, BatchComplete = 2, inputcomplete = 1, approved = 1 WHERE (ID = '$lastid')") or die(mysql_error());
                    }
                } else if ((($testresultID == 1) && ($secondduplicateresult == 3)) || (($testresultID == 3) && ($secondduplicateresult == 1))) {
                    //1 Negative and 1 Indeterminate is Collect New Sample: (Make both Interim and create a new one and make it Reportable Collect New Sample)
                    $resultsrec = mysql_query("UPDATE samples SET repeatt = 1 , BatchComplete = 0 WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    $resultsrec = mysql_query("UPDATE samples SET repeatt = 1 , BatchComplete = 0 WHERE (ID='$secondsampeID')") or die(mysql_error());

                    //Get details of one of the samples to be re saved for re test
                    $repeatsampledetails = getSampleetails($labcode[$a]);
                    extract($repeatsampledetails);
                    //Create a mirror sample for one of the samples by giving it a parent ID of this sample and give it a result of 5 (Collect New Sample)
                    $result = 5;
                    $bloodspot = GetMaxBloodSpot($patient) + 1;

                    if ($bloodspot < 5) {
                        $saverepeatsample = SaveSampleWithResult($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $result, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno, $bloodspot, $failedBrotherID);
                        $lastid = GetLastSampleID($lab);
                        //Save complete batch
                        $resultsrec = mysql_query("UPDATE samples SET repeatt = 0, BatchComplete = 2, inputcomplete = 1, approved = 1 WHERE (ID = '$lastid')") or die(mysql_error());
                    }
                }
            } else if (($noofretests == 2) && ($secondduplicateresult == 0)) {
                //The sample has a brother but the brother doesn't have a result yet
                //Make this result an Interim & Not Reportable
                $resultsrec = mysql_query("UPDATE samples SET result = '$testresultID', repeatt = 1 WHERE (ID='$labcode[$a]')") or die(mysql_error());

            } else if ($noofretests == 1) {
                //After repeat is done only in singles (Like as in failed or positive parents)
                if ($testresultID == 6 && $bloodspot < 5) {
                    //The repeat is again Failed
                    $resultsrec = mysql_query("UPDATE samples SET result = '$testresultID', repeatt = 1 WHERE (ID='$labcode[$a]')") or die(mysql_error());

                    //Get details of the Failed sample to be re saved for re test
                    $repeatsampledetails = getSampleetails($labcode[$a]);
                    extract($repeatsampledetails);
                    //Get the parent ID of the current failed sample
                    $parID = getParentID($labcode[$a], $lab);
                    $failedBrotherID = GetFailedBrotherID($parID);
                    $result = '';
                    SaveSampleWithResult($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $result, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno, $bloodspot, $failedBrotherID);

                    //Save pending task
                    $task = 3;
                    $status = 0;
                    $lastid = GetLastSampleID($lab);
                    $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $lab);
                    //save complete batch
                    $resultsrec = mysql_query("UPDATE samples SET inputcomplete = 1 , approved = 1 WHERE (ID = '$lastid')") or die(mysql_error());
                } else if ($testresultID == 6 && $bloodspot == 5) {
                    //The repeat is again Failed but we've run out of blood spots (MAX = 5)
                    $resultsrec = mysql_query("UPDATE samples SET result = '$testresultID', repeatt = 1 WHERE (ID='$labcode[$a]')") or die(mysql_error());

                    //Get details of the Failed sample to be re saved for re test
                    $repeatsampledetails = getSampleetails($labcode[$a]);
                    extract($repeatsampledetails);
                    //Get the parent ID of the current failed sample
                    $parID = getParentID($labcode[$a], $lab);
                    $failedBrotherID = GetFailedBrotherID($parID);
                    //The New sample created has a result of 5 (Collect New Sample)
                    SaveSampleWithResult($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, 5, $rejectedreason, $reason_for_repeat, $result, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno, $bloodspot, $failedBrotherID);

                    //Save pending task
                    $task = 3;
                    $status = 0;
                    $lastid = GetLastSampleID($lab);
                    $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $lab);
                    //save complete batch
                    $resultsrec = mysql_query("UPDATE samples SET inputcomplete = 1 , approved = 1 WHERE (ID = '$lastid')") or die(mysql_error());

                } else if ($testresultID == 2) {
                    //Get the failedBrotherID column value for this sample
                    $failedBrotherID = GetFailedBrotherID($labcode[$a]);

                    //Check if this sample is the result of repeats that came from double duplicates or single duplicates
                    //If it came from single duplicate it wouldn't have failedBrotherID
                    //If it came from double duplicate it will have failedBrotherId and the result is determined by comparing it with that result
                    if ($failedBrotherID == 0) {
                        //If the sample is either weak positive or strong positive, final result is Positive (Reportable & Printable)
                        $resultsrec = mysql_query("UPDATE samples SET result = '$testresultID', repeatt =  0, BatchComplete = 2, inputcomplete = 1, approved = 1 WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    } else if ($failedBrotherID > 0) {
                        $failedBrotherResult = GetSampleResult($failedBrotherID);
                        if ($failedBrotherResult == 'Positive') {
                            //Both Positive is Final Positive
                            $resultsrec = mysql_query("UPDATE samples SET result = 2, repeatt =  0, BatchComplete = 2, inputcomplete = 1, approved = 1 WHERE (ID='$labcode[$a]')") or die(mysql_error());
                        } else if ($failedBrotherResult == 'Negative') {
                            //1 Positive, 1 Negative Collect New Sample: (Make the Negative Interim and create a new one and make it Reportable Collect New Sample)
                            $resultsrec = mysql_query("UPDATE samples SET repeatt = 1, BatchComplete = 0 WHERE (ID='$labcode[$a]')") or die(mysql_error());

                            //Get details of one of the samples to be re saved for re test
                            $repeatsampledetails = getSampleetails($labcode[$a]);
                            extract($repeatsampledetails);
                            //Create a mirror sample for one of the samples by giving it a parent ID of this sample and give it a result of 5 (Collect New Sample)
                            $result = 5;
                            $saverepeatsample = SaveSampleWithResult($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $result, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno, $bloodspot, $failedBrotherID);
                            $lastid = GetLastSampleID($lab);
                            //Save complete batch
                            $resultsrec = mysql_query("UPDATE samples SET repeatt = 0, BatchComplete = 2, inputcomplete = 1, approved = 1 WHERE (ID = '$lastid')") or die(mysql_error());

                        } else if ($failedBrotherResult == 'Indeterminate') {
                            //1 Positive, 1 Indeterminate Final Positive
                            $resultsrec = mysql_query("UPDATE samples SET result = 2, repeatt =  0, BatchComplete = 2, inputcomplete = 1, approved = 1 WHERE (ID='$labcode[$a]')") or die(mysql_error());
                        }
                    }

                } else if ($testresultID == 1) {
                    //Get the failedBrotherID column value for this sample
                    $failedBrotherID = GetFailedBrotherID($labcode[$a]);

                    //Check if this sample is the result of repeats that came from double duplicates or single duplicates
                    //If it came from single duplicate it wouldn't have failedBrotherID
                    //If it came from double duplicate it will have failedBrotherId and the result is determined by comparing it with that result
                    if ($failedBrotherID == 0) {
                        //The repeat is now Negative, therefore we need to Collect New Sample. Make this sample Interim (Not Reportable)
                        $resultsrec = mysql_query("UPDATE samples SET result = '$testresultID', repeatt = 1 WHERE (ID='$labcode[$a]')") or die(mysql_error());

                        //Get details of the Negative sample to be re saved for re test
                        $repeatsampledetails = getSampleetails($labcode[$a]);
                        extract($repeatsampledetails);
                        $result = 5;
                        //The New sample created has a result of 5 (Collect New Sample)
                        SaveSampleWithResult($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $result, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno, $bloodspot, $failedBrotherID);
                        $lastid = GetLastSampleID($lab);
                        //Make the new sample Printable & Reportable
                        $resultsrec = mysql_query("UPDATE samples SET repeatt = 0, BatchComplete = 2, inputcomplete = 1, approved = 1 WHERE (ID = '$lastid')") or die(mysql_error());
                    } else if ($failedBrotherID > 0) {
                        $failedBrotherResult = GetSampleResult($failedBrotherID);
                        if ($failedBrotherResult == 'Negative') {
                            //Both Negative is Final Negative
                            $resultsrec = mysql_query("UPDATE samples SET result = 1, repeatt =  0, BatchComplete = 2, inputcomplete = 1, approved = 1 WHERE (ID='$labcode[$a]')") or die(mysql_error());
                        } else if ($failedBrotherResult == 'Positive') {
                            //1 Positive, 1 Negative Collect New Sample: (Make the Negative Interim and create a new one and make it Reportable Collect New Sample)
                            $resultsrec = mysql_query("UPDATE samples SET repeatt = 1, BatchComplete = 0 WHERE (ID='$labcode[$a]')") or die(mysql_error());

                            //Get details of one of the samples to be re saved for re test
                            $repeatsampledetails = getSampleetails($labcode[$a]);
                            extract($repeatsampledetails);
                            //Create a mirror sample for one of the samples by giving it a parent ID of this sample and give it a result of 5 (Collect New Sample)
                            $result = 5;
                            $saverepeatsample = SaveSampleWithResult($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $result, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno, $bloodspot, $failedBrotherID);
                            $lastid = GetLastSampleID($lab);
                            //Save complete batch
                            $resultsrec = mysql_query("UPDATE samples SET repeatt = 0, BatchComplete = 2, inputcomplete = 1, approved = 1 WHERE (ID = '$lastid')") or die(mysql_error());

                        } else if ($failedBrotherResult == 'Indeterminate') {
                            //1 Negative, 1 Indeterminate Collect New Sample: (Make the Negative Interim and create a new one and make it Reportable Collect New Sample)
                            $resultsrec = mysql_query("UPDATE samples SET repeatt = 1, BatchComplete = 0 WHERE (ID='$labcode[$a]')") or die(mysql_error());

                            //Get details of one of the samples to be re saved for re test
                            $repeatsampledetails = getSampleetails($labcode[$a]);
                            extract($repeatsampledetails);
                            //Create a mirror sample for one of the samples by giving it a parent ID of this sample and give it a result of 5 (Collect New Sample)
                            $result = 5;
                            $saverepeatsample = SaveSampleWithResult($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $result, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno, $bloodspot, $failedBrotherID);
                            $lastid = GetLastSampleID($lab);
                            //Save complete batch
                            $resultsrec = mysql_query("UPDATE samples SET repeatt = 0, BatchComplete = 2, inputcomplete = 1, approved = 1 WHERE (ID = '$lastid')") or die(mysql_error());
                        }
                    }
                } else if ($testresultID == 3) {
                    //Get the failedBrotherID column value for this sample
                    $failedBrotherID = GetFailedBrotherID($labcode[$a]);

                    //If this sample came from double duplicate it will have failedBrotherId and the result is determined by comparing it with that result
                    if ($failedBrotherID > 0) {
                        $failedBrotherResult = GetSampleResult($failedBrotherID);
                        if ($failedBrotherResult == 'Negative') {
                            //1 Indeterminate, 1 Negative is Collect New Sample: (Make the Indeterminate Interim and create a new one and make it Reportable Collect New Sample)
                            $resultsrec = mysql_query("UPDATE samples SET repeatt = 1, BatchComplete = 0 WHERE (ID='$labcode[$a]')") or die(mysql_error());

                            //Get details of one of the samples to be re saved for re test
                            $repeatsampledetails = getSampleetails($labcode[$a]);
                            extract($repeatsampledetails);
                            //Create a mirror sample for one of the samples by giving it a parent ID of this sample and give it a result of 5 (Collect New Sample)
                            $result = 5;
                            $saverepeatsample = SaveSampleWithResult($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $result, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno, $bloodspot, $failedBrotherID);
                            $lastid = GetLastSampleID($lab);
                            //Save complete batch
                            $resultsrec = mysql_query("UPDATE samples SET repeatt = 0, BatchComplete = 2, inputcomplete = 1, approved = 1 WHERE (ID = '$lastid')") or die(mysql_error());
                        } else if ($failedBrotherResult == 'Indeterminate') {
                            //Both Indeterminate is Collect New Sample: (Make the Negative Interim and create a new one and make it Reportable Collect New Sample)
                            $resultsrec = mysql_query("UPDATE samples SET repeatt = 1, BatchComplete = 0 WHERE (ID='$labcode[$a]')") or die(mysql_error());

                            //Get details of one of the samples to be re saved for re test
                            $repeatsampledetails = getSampleetails($labcode[$a]);
                            extract($repeatsampledetails);
                            //Create a mirror sample for one of the samples by giving it a parent ID of this sample and give it a result of 5 (Collect New Sample)
                            $result = 5;
                            $saverepeatsample = SaveSampleWithResult($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $result, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno, $bloodspot, $failedBrotherID);
                            $lastid = GetLastSampleID($lab);
                            //Save complete batch
                            $resultsrec = mysql_query("UPDATE samples SET repeatt = 0, BatchComplete = 2, inputcomplete = 1, approved = 1 WHERE (ID = '$lastid')") or die(mysql_error());

                        } else if ($failedBrotherResult == 'Positive') {
                            //1 Indeterminate, 1 Positive Final Positive
                            $resultsrec = mysql_query("UPDATE samples SET result = 2, repeatt =  0, BatchComplete = 2, inputcomplete = 1, approved = 1 WHERE (ID='$labcode[$a]')") or die(mysql_error());
                        }
                    }
                }
            }
        } else if ($paroid == 0) { //Ordinary samples
            //update results for the samples
            $resultsrec = mysql_query("UPDATE samples SET result = '$testresultID', bloodspot = 1 WHERE (ID='$labcode[$a]')") or die(mysql_error());
            $withresults = mysql_query("UPDATE pendingtasks SET status = 1 WHERE (sample='$labcode[$a]' AND task=1)") or die(mysql_error());
            //update status of worksheet
            $updateworksheetrec = mysql_query("UPDATE worksheets SET Flag = 1, reviewedby = '$userid', datereviewed = '$datereviewed'
                          WHERE ( ((worksheetno = '$waksheetno') AND (ID='$serialno')) )") or die(mysql_error());

            if ($testresultID == 3) {
                $logger->debug("Sample with ID {$labcode[$a]} has result {$testresultID}");
                //Update the Indeterminate sample as ready for repeat (repeatt = 1 means Interim sample not Reportable or Printable)
                $resultsrec = mysql_query("UPDATE samples SET repeatt = 1 WHERE (ID='$labcode[$a]')") or die(mysql_error());

                //Get details of the Indeterminate sample to be re saved for re test
                $repeatsampledetails = getSampleetails($labcode[$a]);
                extract($repeatsampledetails);
                //Save sample details with new ID.. save them twice (For Indeterminates) because repeats should be done in duplicates
                $count = 0;
                while ($count != 2) {
                    //Save the new samples by mirroring the current Indeterminate sample
                    $bloodspot = $bloodspot + 1;
                    $saverepeatsample = SaveSampleWithResult($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, '', $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno, $bloodspot, $failedBrotherID);
                    //Save pending task
                    $task = 3;
                    $status = 0;
                    $lastid = GetLastSampleID($lab);
                    $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $lab);
                    //Save complete batch
                    $resultsrec = mysql_query("UPDATE samples SET inputcomplete = 1 , approved = 1 WHERE (ID = '$lastid')") or die(mysql_error());

                    $count = $count + 1;
                }
            } else if ($testresultID == 6 || $testresultID == 2) {
                //Update the Failed or Positive sample as ready for repeat (repeatt = 1 means this is now an Interim result Not Reportable or Printable)
                $resultsrec = mysql_query("UPDATE samples SET result = '$testresultID', repeatt = 1 WHERE (ID='$labcode[$a]')") or die(mysql_error());

                //Get details of the Failed sample to be re saved for re test
                $repeatsampledetails = getSampleetails($labcode[$a]);
                extract($repeatsampledetails);
                $bloodspot = $bloodspot + 1;
                //Save the Failed or Positive sample detail with new ID with the parent ID of the failed or positive sample
                $saverepeatsample = SaveSampleWithResult($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, '', $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno, $bloodspot, $failedBrotherID);

                //save pending task
                $task = 3;
                $status = 0;
                $lastid = GetLastSampleID($lab);
                $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $lab);
                //save complete batch
                $resultsrec = mysql_query("UPDATE samples SET inputcomplete = 1 , approved = 1 WHERE (ID = '$lastid')") or die(mysql_error());
            } else { //Negative samples
                //Update sample to be complete and ready for dispatch
                $resultsrec = mysql_query("UPDATE samples SET result = '$testresultID', repeatt =  0, BatchComplete = 2 WHERE (ID='$labcode[$a]')") or die(mysql_error());
            } //end if test result
        } //end if for ordinary sample
    } //end if for repeat value
    if ($resultsrec && $updateworksheetrec) {
        //save user activity
        $tasktime = date("h:i:s a");
        $todaysdate = date("Y-m-d");
        $utask = 8; //user task = 1st review

        $activity = SaveUserActivity($userid, $utask, $tasktime, $waksheetno, $todaysdate);

        $query = "UPDATE
                    worksheets
                  SET
                    approvalstatus = 2, Flag = 1, secondApprovalBy = '$userid', secondApprovalDate = '$todaysdate'
                  WHERE
                    (((worksheetno = '$waksheetno') AND (ID = '$serialno')))";
        $updateWorksheet = mysql_query($query);

        $st = "Test Results for Worksheet No. " . $waksheetno . "  have been successfully approved. " . "<br>" . " They may now be dispatched " . "<br>" . " All of the indeterminates and failed samples have been set aside for retest. ";
        echo "<script type='text/javascript'>";
        echo "window.location.href='dispatch.php?z=$st'";
        echo "</script>";
    } else {
        $error = '<center>' . "Failed to Update test results, try again " . '</center>';
    }
} else if ($_REQUEST['FirstApproval']) {
    $waksheetno = $_POST['waksheetno'];
    $serialno = $_POST['serialno'];
    $labcode = $_POST['labcode'];
    $outcome = $_POST['testresult'];
    $dateresultsupdated = date('Y-m-d');

    foreach ($labcode as $a => $b) {
        $testresultID = GetIDfromtableandname($outcome[$a], "results");

        //update results for the samples
        $resultsrec = mysql_query("UPDATE samples SET result = '$testresultID' WHERE (ID='$labcode[$a]')") or die(mysql_error());
        $logger->warn("Sample Id = {$labcode[$a]} is assigned Result Id = {$testresultID} and Query returned {$resultsrec}");

        $withresults = mysql_query("UPDATE pendingtasks SET status = 1 WHERE (sample='$labcode[$a]' AND task=1)") or die(mysql_error());

        //update status of worksheet
        $updateworksheetrec = mysql_query("UPDATE worksheets SET Flag = 0, reviewedby='$userid', datereviewed='$datereviewed'
			   		       WHERE ( ((worksheetno = '$waksheetno') AND (ID='$serialno')) )") or die(mysql_error());
        $logger->warn("Worksheet Id = {$serialno} with WorksheetNo = {$waksheetno} is updated and Query returned {$updateworksheetrec}");

    }

    if ($resultsrec && $updateworksheetrec) {
        //save user activity
        $tasktime = date("h:i:s a");
        $todaysdate = date("Y-m-d");
        $utask = 8; //user task = 1st review

        $activity = SaveUserActivity($userid, $utask, $tasktime, $waksheetno, $todaysdate);
        $query = "UPDATE
                    worksheets
                  SET
                    approvalstatus = 1, firstapprovalby = '$userid', firstapprovaldate = '$todaysdate'
                  WHERE
                    (((worksheetno = '$waksheetno') AND (ID = '$serialno')))";
        $updateWorksheet = mysql_query($query);

        $st = "First approval completed for worksheet. " . $waksheetno . "<br/> Second approval needed before dispatch.";
        echo "<script type='text/javascript'>";
        echo "window.location.href='worksheetlist.php?p=$st&wtype=0'";
        echo "</script>";
    } else {
        $error = '<center>' . "Failed to Update test results, try again " . '</center>';
    }
}
?>

    <style type="text/css">
        select {
            width: 250;
        }
    </style>
    <script language="javascript" src="calendar.js"></script>
    <link type="text/css" href="calendar.css" rel="stylesheet" />
    <script type="text/javascript">
        function reload(form) {
            var val = form.cat.options[form.cat.options.selectedIndex].value;
            self.location = 'addsample.php?catt=' + val;
        }
    </script>
    <script language="JavaScript">
        function submitPressed() {
            document.worksheetform.SaveWorksheet.disabled = true;
            //stuff goes here
            document.worksheetform.submit();
        }
    </script>
    <div class="section">
        <div class="section-title">WORKSHEET NO <?php echo $worksheetno; ?> RESULTS DETAILS </div>
        <div class="xtop">
            <?php if ($success != "") {?>
            <table>
                <tr>
                    <td style="width:auto">
                        <div class="success">
                            <strong>
                            <font color="#666600"><?php echo $success; ?></font>
                        </strong>
                        </div>
                    </td>
                </tr>
            </table>
            <?php
}
if ($error != "") {
    ?>
            <table>
                <tr>
                    <td style="width:auto">
                        <div class="error">
                            <strong>
                            <font color="#666600"><?php echo $error; ?></font>
                        </strong>
                        </div>
                    </td>
                </tr>
            </table>
            <?php }?>
            <form method="post" action="" name="worksheetform" onSubmit="return confirm('Are you sure you want to approve the below test results as final results?');">
                <table border="0" class="data-table">
                    <tr>
                        <td class="comment style1 style4">Serial No </td>
                        <td>
                            <span class="comment style1 style4"><?php echo $ID; ?>
                            <span class="style5">
                                <input type="hidden" name="serialno" value="<?php echo $ID; ?>" />
                            </span>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="style4 comment">
                            <strong>Worksheet No</strong>
                        </td>
                        <td class="comment">
                            <span class="style5"><?php echo $worksheetno; ?><input type="hidden" name="waksheetno" value="<?php echo $worksheetno; ?>" />
                        </span>
                        </td>
                        <td class="comment style1 style4">Lot No </td>
                        <td><span class="comment style1 style4"><?php echo $Lotno; ?></span></td>
                        <td>
                            <span class="style5"><strong>Date Run </strong></span>
                        </td>
                        <td colspan="2"><?php echo $daterun; ?></td>
                    </tr>
                    <tr>
                        <td class="comment style1 style4">
                            Date Created
                        </td>
                        <td class="comment"><span class="style5"><?php echo $datecreated; //get current date                                                                         ?></span>
                        </td>
                        <td class="comment style1 style4"><span class="style5"><strong>KIT EXP </strong></span></td>
                        <td>
                            <?php echo $kitexpirydate; ?>
                        </td>
                        <td><span class="style5"><strong>Reviewed By </strong></span></td>
                        <td colspan="2"><?php echo $reviewedby; ?></td>
                    </tr>
                    <tr>
                        <td class="comment style1 style4">
                            Created By
                        </td>
                        <td class="comment">
                            <span class="style5">
                            <?php echo $creator; ?>
                        </span>
                        </td>
                        <td class="comment style1 style4">
                            <span class="style5"><strong>Date Cut </strong></span></td>
                        <td>
                            <?php echo $datecut; ?>
                        </td>
                        <td><span class="style5"><strong>Date Reviewed </strong></span></td>
                        <td colspan="2">
                            <?php echo $datereviewedd; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7">&nbsp;</td>
                    </tr>
                    <tr>
                        <?php
$count = 1;
$colcount = 1;
for ($i = 1; $i <= 2; $i++) {
    if ($count == 1) {
        $pc = "<div align='right'></div><div align='center'>Negative Control<br><strong>NC</strong></div>";
    } elseif ($count == 2) {
        $pc = "<div align='center'>Positive Control<br><strong>PC</strong></div>";
    }

    $RE = $colcount % 6;
    ?>
                            <td height="50" bgcolor="#dddddd" class="comment style1 style4">
                                <?php echo $pc; ?> </td>
                            <?php
$count++;
    $colcount++;
}
$scount = 2;
// this is the list you had, but in PHP terms (in an array)
$dropDownList = array("Negative", "Positive", "Indeterminate", "Collect New Sample", "Failed");
//put data into an array
//$dataArray = mysql_fetch_array($result);
//$dataArray = mysql_fetch_array($dropDownList);
$qury = "SELECT
                                s.ID, s.patient, s.nmrlstampno
                             FROM
                                samples AS s, labs AS l
                             WHERE
                                l.withresult != 1 AND s.lab = l.id AND worksheet = '$worksheetno'
                             ORDER BY
                                l.priority ASC, s.parentid DESC, s.ID, s.nmrlstampno ASC";
$result = mysql_query($qury) or die(mysql_error());

$i = 0;
$samplesPerRow = 7;

while (list($ID, $patient, $nmrlstampno) = mysql_fetch_array($result)) {
    $scount = $scount + 1;
    $paroid = getParentID($ID, $labss); //get parent id

    if ($paroid == 0) {
        $paroid = "";
    } else {
        $paroid = " - " . $paroid;
    }
    //get sample sample test results
    $routcome = GetSampleResult($ID);

    // grab data from database
    $result2 = mysql_query("SELECT results.name FROM samples,results WHERE samples.ID = '$ID' AND samples.result = results.ID");
    // put data into an array
    $dataArray = mysql_fetch_array($result2);

    $RE = $colcount % 6;
    ?>
                                <td height="50" width="145px">
                                    NMRL Number: <strong><?php echo $nmrlstampno; ?></strong>
                                    <input name='nmrlstampno[]' type='hidden' id='nmrlstampno' value='<?php echo $nmrlstampno; ?>' />
                                    <br /> Lab Code:<input name='labcode[]' type='hidden' id='labcode[]' value='<?php echo $ID; ?>' />
                                    <?php echo $ID . " " . $paroid; ?>
                                    <br />
                                    <?php
echo "<select name=\"testresult[]\">\n";

    foreach ($dropDownList as $listItem) {
        // output <option> for this item in the list
        // if this item matches DB value, then we put in SELECTED.
        if ($dataArray[0] == $listItem) {
            echo "<option value=\"{$listItem}\" SELECTED>{$listItem}</option>\n";
        }
        // otherwise, just output it normally
        else {
            echo "<option value=\"{$listItem}\">{$listItem}</option>\n";
        }
    }
    echo "</select>";
    ?>
                                        <?php
echo "  </td>";
    // now output
    $colcount++;
    if ($RE == 0) {
        ?>
                    </tr>
                    <?php
} //end if modulus is 0
} //end while
?>
                        <tr bgcolor="#999999">
                            <td style="text-align: center;" colspan="7" bgcolor="#00526C">
                                <?php
if ($worksheet['approvalStatus'] == 0) {
    ?>
                                    <input type="submit" name="FirstApproval" value="First Approval" class="button" />
                                    <?php
} else if ($worksheet['approvalStatus'] == 1) {
    ?>
                                        <input type="submit" name="SaveWorksheet" value="Confirm & Approve Results" class="button" />
                                        <?php
}
?>
                            </td>
                        </tr>
                </table>
            </form>
            <?php include '../includes/footer.php';?>