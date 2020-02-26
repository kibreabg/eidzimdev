<?php
session_start();
include('../includes/header.php');
require_once('classes/tc_calendar.php');
//Insert the path where you unpacked log4php
require_once('log/Logger.php');
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
        $grandParentId = getParentID($paroid, $labss); //Get grand parent ID if it's available
        $testresultID = GetIDfromtableandname($outcome[$a], "results"); //th resuls 1-negative 2-positive
        // Repeat samples (The children of the samples to be repeated)
        if ($paroid > 0) {
            //Determine if sample is repeat or not (Get the result of the parent sample)
            $parentresult = getparentsampleresult($paroid, $labss);
            //Get the total no of retests for that sample
            $noofretests = GetNoofRetests($paroid, $labss);
            //Check if this repeated sample has a brother (or a duplicate) and get it's result
            $secondduplicateresult = GetDuplicateSampleResult($paroid, $labcode[$a]);
            //Get the ID of the duplicate brother
            $secondsampeID = GetDuplicateSampleResultID($paroid, $labcode[$a]);
            if ($grandParentId > 0) {
                //Check if this repeated sample has a brother (or a duplicate) and get it's result
                $secondduplicateparentresult = GetDuplicateSampleResult($grandParentId, $paroid);
            }

            // retest done in duplicates and the 2nd duplicate also has a result	
            if (($noofretests == 2) && ($secondduplicateresult > 0)) {

                //Update pending tasks
                $repeatresults = mysql_query("UPDATE pendingtasks
                                              SET  status  	 =  1 
                                              WHERE (sample='$labcode[$a]' AND task=3)") or die(mysql_error());
                ///update status of worksheet
                $updateworksheetrec = mysql_query("UPDATE worksheets
                                                   SET  Flag = 1, reviewedby='$userid',datereviewed='$datereviewed'
			   			   WHERE ( ((worksheetno = '$waksheetno') AND (ID='$serialno')) )") or die(mysql_error());
                //update sample in pending tasks as havin a result
                $withresults = mysql_query("UPDATE pendingtasks
             			 	    SET status = 1 
			 		    WHERE (sample='$labcode[$a]' AND task=1)") or die(mysql_error());

                if (($testresultID == 1) && ($secondduplicateresult == 1)) {
                    //Both Negative is Final Negative 06/03/2017

                    $resultsrec = mysql_query("UPDATE samples
             				       SET result = 1 ,repeatt =  0 , BatchComplete = 2
			 		       WHERE (ID='$labcode[$a]')") or die(mysql_error());

                    //Update the other duplicate as non usable sample
                    $resultsrec2 = mysql_query("UPDATE samples
             					SET repeatt = 1 , BatchComplete = 0
			 			WHERE (ID='$secondsampeID')") or die(mysql_error());
                } else if (($testresultID == 1) && ($secondduplicateresult == 6) || ($testresultID == 6) && ($secondduplicateresult == 1)) {
                    //1 Negative, 1 Failed the failed goes back to be repeated

                    if ($testresultID == 6) {
                        //repeatt = 1 means making the parent sample insignificant, because what matters is the child sample now
                        $repeatresults = mysql_query("UPDATE samples SET repeatt = 1 
			 		      WHERE (ID='$labcode[$a]')") or die(mysql_error());

                        //Get details of the Failed sample to be re saved for re test
                        $repeatsampledetails = getSampleetails($labcode[$a]);
                        extract($repeatsampledetails);
                        //Save the Failed sample detail with new ID with the parent ID of the failed sample
                        $saverepeatsample = GetSavedSamples($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno);

                        //save pending task
                        $task = 3;
                        $status = 0;
                        $lastid = GetLastSampleID($lab);
                        $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $lab);
                        //save complete batch
                        $completeentry = mysql_query("UPDATE samples SET inputcomplete = 1 , approved = 1
			  			  WHERE (ID = '$lastid')") or die(mysql_error());

                        //Update the other duplicate as non usable sample
                        $resultsrec2 = mysql_query("UPDATE samples
             					SET repeatt = 1 , BatchComplete = 0
			 			WHERE (ID='$secondsampeID')") or die(mysql_error());
                    } else if ($secondduplicateresult == 6) {
                        //repeatt = 1 means making the parent sample insignificant, because what matters is the child sample now
                        $repeatresults = mysql_query("UPDATE samples SET repeatt = 1 
			 		      WHERE (ID='$secondsampeID')") or die(mysql_error());

                        //Get details of the Failed sample to be re saved for re test
                        $repeatsampledetails = getSampleetails($secondsampeID);
                        extract($repeatsampledetails);
                        //Save the Failed sample detail with new ID with the parent ID of the failed sample
                        $saverepeatsample = GetSavedSamples($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno);

                        //save pending task
                        $task = 3;
                        $status = 0;
                        $lastid = GetLastSampleID($lab);
                        $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $lab);
                        //save complete batch
                        $completeentry = mysql_query("UPDATE samples SET inputcomplete = 1 , approved = 1
			  			  WHERE (ID = '$lastid')") or die(mysql_error());

                        //Update the other duplicate as non usable sample
                        $resultsrec2 = mysql_query("UPDATE samples
             					SET repeatt = 1 , BatchComplete = 0
			 			WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    }
                } else if (($testresultID == 2) && ($secondduplicateresult == 6) || ($testresultID == 6) && ($secondduplicateresult == 2)) {
                    //1 Positive, 1 Failed the failed goes back to be repeated

                    if ($testresultID == 6) {
                        //repeatt = 1 means making the parent sample insignificant, because what matters is the child sample now
                        $repeatresults = mysql_query("UPDATE samples SET repeatt = 1 
			 		      WHERE (ID='$labcode[$a]')") or die(mysql_error());

                        //Get details of the Failed sample to be re saved for re test
                        $repeatsampledetails = getSampleetails($labcode[$a]);
                        extract($repeatsampledetails);
                        //Save the Failed sample detail with new ID with the parent ID of the failed sample
                        $saverepeatsample = GetSavedSamples($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno);

                        //save pending task
                        $task = 3;
                        $status = 0;
                        $lastid = GetLastSampleID($lab);
                        $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $lab);
                        //save complete batch
                        $completeentry = mysql_query("UPDATE samples SET inputcomplete = 1 , approved = 1
                                                        WHERE (ID = '$lastid')") or die(mysql_error());

                        //Update the other duplicate as non usable sample
                        $resultsrec2 = mysql_query("UPDATE samples
                                                        SET repeatt = 1 , BatchComplete = 0
                                                        WHERE (ID='$secondsampeID')") or die(mysql_error());
                    } else if ($secondduplicateresult == 6) {
                        //repeatt = 1 means making the parent sample insignificant, because what matters is the child sample now
                        $repeatresults = mysql_query("UPDATE samples SET repeatt = 1 
			 		      WHERE (ID='$secondsampeID')") or die(mysql_error());

                        //Get details of the Failed sample to be re saved for re test
                        $repeatsampledetails = getSampleetails($secondsampeID);
                        extract($repeatsampledetails);
                        //Save the Failed sample detail with new ID with the parent ID of the failed sample
                        $saverepeatsample = GetSavedSamples($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno);

                        //save pending task
                        $task = 3;
                        $status = 0;
                        $lastid = GetLastSampleID($lab);
                        $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $lab);
                        //save complete batch
                        $completeentry = mysql_query("UPDATE samples SET inputcomplete = 1 , approved = 1
			  			  WHERE (ID = '$lastid')") or die(mysql_error());

                        //Update the other duplicate as non usable sample
                        $resultsrec2 = mysql_query("UPDATE samples
             					SET repeatt = 1 , BatchComplete = 0
			 			WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    }
                } else if (($testresultID == 2) && ($secondduplicateresult == 2)) {
                    //Both Positive will be final Positive

                    $resultsrec = mysql_query("UPDATE samples
             					SET result = 2 , repeatt = 0 , BatchComplete = 2
			 			WHERE (ID='$labcode[$a]')") or die(mysql_error());

                    ////Update the other duplicate as a complete batch because it's of no use now
                    $resultsrec2 = mysql_query("UPDATE samples
             					SET repeatt = 1 , BatchComplete = 1
			 			WHERE (ID='$secondsampeID')") or die(mysql_error());
                } else if ((($testresultID == 2) && ($secondduplicateresult == 3)) || (($testresultID == 3) && ($secondduplicateresult == 2))) {
                    //1 positive, 1 indeterminate final positive
                    if ($testresultID == 2) {
                        //final one with result and ready for dispatch
                        $resultsrec = mysql_query("UPDATE samples
             					SET  result  	 = '$testresultID' ,repeatt  	 =  0 ,BatchComplete=2
			 					WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    } elseif ($testresultID == 3) {
                        //update the other repeat as final as this one failed
                        $resultsrec = mysql_query("UPDATE samples
             					SET  repeatt  	 =  0 ,BatchComplete=2
			 					WHERE (ID='$secondsampeID')") or die(mysql_error());
                        //mark the failed repeat as incomplete but not for dispatch
                        $resultsrec = mysql_query("UPDATE samples
             					SET  result  	 = '$testresultID' ,repeatt  	 =  1 ,BatchComplete=0
			 					WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    }
                } else if ((($testresultID == 1) && ($secondduplicateresult == 2)) || (($testresultID == 2) && ($secondduplicateresult == 1))) {
                    //1 positive, 1 negative collect new sample:

                    $resultsrec = mysql_query("UPDATE samples
             					 SET  result  	 = 5 ,repeatt  	 =  0 ,BatchComplete=2
			 					WHERE (ID='$labcode[$a]')") or die(mysql_error());

                    //update the atha repeat as final as this one failed
                    $resultsrec = mysql_query("UPDATE samples
             					SET  repeatt  	 =  1 ,BatchComplete=0
			 					WHERE (ID='$secondsampeID')") or die(mysql_error());
                } else if (($testresultID == 3) && ($secondduplicateresult == 3)) {
                    //Both indeterminate (used to be final positive) is now Collect New Sample 
                    $resultsrec = mysql_query("UPDATE samples
             					SET result = 5 ,repeatt = 0 ,BatchComplete = 2
			 		        WHERE (ID='$labcode[$a]')") or die(mysql_error());

                    //Update the other duplicate as a complete batch because it's of no use now
                    $resultsrec = mysql_query("UPDATE samples
             					SET repeatt = 1, BatchComplete = 1
			 			WHERE (ID='$secondsampeID')") or die(mysql_error());
                } else if ((($testresultID == 1) && ($secondduplicateresult == 3)) || (($testresultID == 3) && ($secondduplicateresult == 1))) {
                    //1 negative, 1 indeterminate final negative



                    if ($testresultID == 1) {
                        //final one with result n ready for dispatch
                        $resultsrec = mysql_query("UPDATE samples
             					SET  result  	 = '$testresultID' ,repeatt  	 =  0 ,BatchComplete=2
			 					WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    } elseif ($testresultID == 3) {
                        //update the atha repeat as final as this one failed
                        $resultsrec = mysql_query("UPDATE samples
             					   SET repeatt = 0 ,BatchComplete=2
			 			   WHERE (ID='$secondsampeID')") or die(mysql_error());
                        //mark the failed repeat as incomplete but not for dispatch
                        $resultsrec = mysql_query("UPDATE samples
             					   SET result = '$testresultID' ,repeatt  	 =  1 ,BatchComplete=0
			 			   WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    }
                } else if ((($testresultID == 3) && ($secondduplicateresult == 6)) || (($testresultID == 6) && ($secondduplicateresult == 3))) {
                    //1 Indeterminate, 1 Failed The failed goes for repeat..
                    $logger->debug("One Indeterminate & One Failed. {$labcode[$a]} is Failed ({$testresultID}) and {$secondsampeID} is Indeterminate ({$secondduplicateparentresult})");
                    if ($testresultID == 6) {
                        //repeatt = 1 means making the parent sample insignificant, because what matters is the child sample now
                        $repeatresults = mysql_query("UPDATE samples SET repeatt = 1 
			 		      WHERE (ID='$labcode[$a]')") or die(mysql_error());

                        //Get details of the Failed sample to be re saved for re test
                        $repeatsampledetails = getSampleetails($labcode[$a]);
                        extract($repeatsampledetails);
                        //Save the Failed sample detail with new ID with the parent ID of the failed sample
                        $saverepeatsample = GetSavedSamples($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno);

                        //save pending task
                        $task = 3;
                        $status = 0;
                        $lastid = GetLastSampleID($lab);
                        $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $lab);
                        //save complete batch
                        $completeentry = mysql_query("UPDATE samples SET inputcomplete = 1 , approved = 1
                                                        WHERE (ID = '$lastid')") or die(mysql_error());

                        //Update the other duplicate as non usable sample
                        $resultsrec2 = mysql_query("UPDATE samples
                                                        SET repeatt = 1 , BatchComplete = 0
                                                        WHERE (ID='$secondsampeID')") or die(mysql_error());
                    }
                }
            } else if (($noofretests == 2) && ($secondduplicateresult == 0)) {
                //2nd retest done but 2nd duplicate has no result just yet
                //update results for repeats
                $repeatcode = 1;
                $resultsrec = mysql_query("UPDATE samples
             			           SET result = '$testresultID' ,repeatt =  '$repeatcode' 
			 		   WHERE (ID='$labcode[$a]')") or die(mysql_error());


                //update status of worksheet
                $updateworksheetrec = mysql_query("UPDATE worksheets
                                                   SET  Flag = 1, reviewedby='$userid',datereviewed='$datereviewed'
			   			   WHERE ( ((worksheetno = '$waksheetno') AND (ID='$serialno')) )") or die(mysql_error());
            } else if ($noofretests == 1) {
                //After repeat done only in singles (Like as in failed parents)
                if ($testresultID == 6) {
                    //The repeat is again Failed
                    $repeatresults = mysql_query("UPDATE samples SET repeatt = 1 
			 		      WHERE (ID='$labcode[$a]')") or die(mysql_error());

                    //Get details of the Failed sample to be re saved for re test
                    $repeatsampledetails = getSampleetails($labcode[$a]);
                    extract($repeatsampledetails);
                    //Save the Failed sample detail with new ID with the parent ID of the failed sample
                    $saverepeatsample = GetSavedSamples($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno);

                    //save pending task
                    $task = 3;
                    $status = 0;
                    $lastid = GetLastSampleID($lab);
                    $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $lab);
                    //save complete batch
                    $completeentry = mysql_query("UPDATE samples SET inputcomplete = 1 , approved = 1
			  			  WHERE (ID = '$lastid')") or die(mysql_error());
                } else if ($testresultID == 2) {
                    //The repeat is Positive
                    //Update the Positive samples as ready for repeat
                    $repeatresults = mysql_query("UPDATE samples SET repeatt = 1 
			 		      WHERE (ID='$labcode[$a]')") or die(mysql_error());

                    //Get details of Positive sample to be re saved for re test
                    $repeatsampledetails = getSampleetails($labcode[$a]);
                    extract($repeatsampledetails);
                    //Save sample details with new ID.. save them twice (For Indeterminates and Positives) because repeats should be done in duplicates
                    $z = 0;
                    while ($z != 2) {

                        //save the first samples for the repeat
                        //$lastpatientid = GetLastPatientID();
                        $saverepeatsample = GetSavedSamples($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno);
                        /*
                          $saverepeatsample=GetSavedSamples($batchno,$patient,$facility,$receivedstatus,$spots,$datecollected,$datedispatchedfromfacility, $datereceived,$comments,$labcomment,$labcode[$a],$rejectedreason,$reason_for_repeat,$test_reason) ;
                         */

                        //save pending task
                        $task = 3;
                        $status = 0;
                        $lastid = GetLastSampleID($lab);
                        $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $lab);
                        //save complete batch
                        $completeentry = mysql_query("UPDATE samples SET inputcomplete = 1 , approved = 1
			  			  WHERE (ID = '$lastid')") or die(mysql_error());

                        $z = $z + 1;
                    }
                } else if ($testresultID == 1 && $parentresult == 6 && $secondduplicateparentresult > 0) {
                    if ($secondduplicateparentresult == 1) {
                        //Final Negative but with the parent sample that's failed which is also a duplicate sample from a repeated test
                        //And the parent repeats are 1 Negative and 1 Failed results Negative
                        $resultsrec = mysql_query("UPDATE samples
             					SET result = 1 , repeatt = 0 , BatchComplete = 2
			 			WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    }
                } else if ($testresultID != 1 && $parentresult == 6 && $secondduplicateparentresult > 0) {
                    if ($secondduplicateparentresult == 1) {
                        //Final non-Negative but with the parent sample that's failed which is also a duplicate sample from a repeated test
                        //And the parent repeats are 1 Negative and 1 Failed results Collect New Sample
                        $resultsrec = mysql_query("UPDATE samples
             					SET result = 5 , repeatt = 0 , BatchComplete = 2
			 			WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    }
                } else if ($testresultID == 2 && $parentresult == 6 && $secondduplicateparentresult > 0) {
                    if ($secondduplicateparentresult == 2) {
                        //Final Positive but with the parent sample that's failed which is also a duplicate sample from a repeated test
                        //And the parent repeats are 1 Positive and 1 Failed results Positive
                        $resultsrec = mysql_query("UPDATE samples
             					SET result = 2 , repeatt = 0 , BatchComplete = 2
			 			WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    }
                } else if ($testresultID != 2 && $parentresult == 6 && $secondduplicateparentresult > 0) {
                    if ($secondduplicateparentresult == 2) {
                        //Final non-Positive but with the parent sample that's failed which is also a duplicate sample from a repeated test
                        //And the parent repeats are 1 Positive and 1 Failed results Collect New Sample
                        $resultsrec = mysql_query("UPDATE samples
             					SET result = 5 , repeatt = 0 , BatchComplete = 2
			 			WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    }
                }
            }
        } else if ($paroid == 0) {//ordinary samples
            $noofretests = GetNoofRetests($paroid, $labss); //get the total no of retests for that samples
            //update results for the samples
            $resultsrec = mysql_query("UPDATE samples SET  result = '$testresultID' 
			 	       WHERE (ID='$labcode[$a]')") or die(mysql_error());

            $withresults = mysql_query("UPDATE pendingtasks SET status = 1 
			 		WHERE (sample='$labcode[$a]' AND task=1)") or die(mysql_error());

            //update status of worksheet
            $updateworksheetrec = mysql_query("UPDATE worksheets
                                               SET Flag = 1, reviewedby = '$userid', datereviewed = '$datereviewed'
			   		       WHERE ( ((worksheetno = '$waksheetno') AND (ID='$serialno')) )") or die(mysql_error());
            if ($testresultID == 3 || $testresultID == 2) {
                $logger->debug("Sample with ID {$labcode[$a]} has result {$testresultID}");
                //Update the Indeterminate or Positive samples as ready for repeat
                $repeatresults = mysql_query("UPDATE samples SET repeatt = 1 
			 		      WHERE (ID='$labcode[$a]')") or die(mysql_error());

                //Get details of Indeterminate or Positive samples to be re saved for re test
                $repeatsampledetails = getSampleetails($labcode[$a]);
                extract($repeatsampledetails);
                //Save sample details with new ID.. save them twice (For Indeterminates and Positives) because repeats should be done in duplicates
                $z = 0;
                while ($z != 2) {

                    //save the first samples for the repeat
                    //$lastpatientid = GetLastPatientID();
                    $saverepeatsample = GetSavedSamples($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno);
                    /*
                      $saverepeatsample=GetSavedSamples($batchno,$patient,$facility,$receivedstatus,$spots,$datecollected,$datedispatchedfromfacility, $datereceived,$comments,$labcomment,$labcode[$a],$rejectedreason,$reason_for_repeat,$test_reason) ;
                     */

                    //save pending task
                    $task = 3;
                    $status = 0;
                    $lastid = GetLastSampleID($lab);
                    $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $lab);
                    //save complete batch
                    $completeentry = mysql_query("UPDATE samples SET inputcomplete = 1 , approved = 1
			  			  WHERE (ID = '$lastid')") or die(mysql_error());

                    $z = $z + 1;
                }
            } else if ($testresultID == 6) {
                //Update the Failed sample as ready for repeat
                $repeatresults = mysql_query("UPDATE samples SET repeatt = 1 
			 		      WHERE (ID='$labcode[$a]')") or die(mysql_error());

                //Get details of the Failed sample to be re saved for re test
                $repeatsampledetails = getSampleetails($labcode[$a]);
                extract($repeatsampledetails);
                //Save the Failed sample detail with new ID with the parent ID of the failed or positive sample
                $saverepeatsample = GetSavedSamples($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno);

                //save pending task
                $task = 3;
                $status = 0;
                $lastid = GetLastSampleID($lab);
                $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $lab);
                //save complete batch
                $completeentry = mysql_query("UPDATE samples SET inputcomplete = 1 , approved = 1
			  			  WHERE (ID = '$lastid')") or die(mysql_error());
            } else { //negative samples
                //update sample to be complete and ready for dispatch
                $ifcompleterec = mysql_query("UPDATE samples SET  BatchComplete=2
					      WHERE (ID='$labcode[$a]')") or die(mysql_error());
            }//end if test result
        }//end if for ordinary sample*/
    }//end if for repeat value


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
        echo '<script type="text/javascript">';
        echo "window.location.href='dispatch.php?z=$st'";
        echo '</script>';
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
        $paroid = getParentID($labcode[$a], $labss); //get parent id
        $testresultID = GetIDfromtableandname($outcome[$a], "results"); //th resuls 1-negative 2-positive

        if ($paroid > 0) { // repeat samples
            $parentresult = getparentsampleresult($paroid, $labss);  //determine if sample is repeat or not
            $noofretests = GetNoofRetests($paroid, $labss); //get the total no of retests for that samples
            $secondduplicateresult = GetDuplicateSampleResult($paroid, $labcode[$a]);
            $secondsampeID = GetDuplicateSampleResultID($paroid, $labcode[$a]);

            $resultsrec = true;
            $updateworksheetrec = true;

            //All these tasks should be done on the final approval; hence the comments
            //
            //
            //
            //
            // retest done in duplicates and the 2nd duplicate also has a result	
//            if (($noofretests == 2) && ($secondduplicateresult > 0)) {
//
//                //pdate pendind tasks
//                $repeatresults = mysql_query("UPDATE pendingtasks
//             			 	      SET  status = 1 
//			 		      WHERE (sample='$labcode[$a]' AND task=3)") or die(mysql_error());
//                ///update status of worksheet
//                $updateworksheetrec = mysql_query("UPDATE worksheets
//                                                   SET  Flag = 0, reviewedby='$userid',datereviewed='$datereviewed'
//			   			   WHERE ( ((worksheetno = '$waksheetno') AND (ID='$serialno')) )") or die(mysql_error());
//                //update sample in pending tasks as havin a result
//                $withresults = mysql_query("UPDATE pendingtasks
//             			 	    SET  status =  1 
//			 		    WHERE (sample='$labcode[$a]' AND task=1)") or die(mysql_error());
//
//                if (($testresultID == 1) && ($secondduplicateresult == 1)) {
//                    //both negative final negative
//
//                    $resultsrec = mysql_query("UPDATE samples
//             				       SET result = 1 , repeatt = 0 , BatchComplete = 0
//			                       WHERE (ID='$labcode[$a]')") or die(mysql_error());
//
//                    //update the atha repeat as final as this one failed
//                    $resultsrec2 = mysql_query("UPDATE samples
//             					SET  repeatt = 1 ,BatchComplete=0
//			 			WHERE (ID='$secondsampeID')") or die(mysql_error());
//                } else if (($testresultID == 2) && ($secondduplicateresult == 2)) {
//                    //both positive final positive
//
//                    $resultsrec = mysql_query("UPDATE samples
//             				       SET result = 2 , repeatt = 0 , BatchComplete = 0
//			 		       WHERE (ID='$labcode[$a]')") or die(mysql_error());
//
//                    //update the atha repeat as final as this one failed
//                    $resultsrec2 = mysql_query("UPDATE samples
//             					SET repeatt = 1 ,BatchComplete = 0
//			 	                WHERE (ID='$secondsampeID')") or die(mysql_error());
//                } else if ((($testresultID == 2) && ($secondduplicateresult == 3)) || (($testresultID == 3) && ($secondduplicateresult == 2))) {
//                    //1 positive, 1 indeterminate final positive
//                    if ($testresultID == 2) {
//                        //final one with result n ready fro dispatch
//                        $resultsrec = mysql_query("UPDATE samples
//             					   SET result = '$testresultID' , repeatt = 0 , BatchComplete = 0
//			 			   WHERE (ID='$labcode[$a]')") or die(mysql_error());
//                    } elseif ($testresultID == 3) {
//                        //update the atha repeat as final as this one failed
//                        $resultsrec = mysql_query("UPDATE samples
//             					   SET repeatt = 0 , BatchComplete = 0
//			 			   WHERE (ID='$secondsampeID')") or die(mysql_error());
//                        //mark the failed repeat as incomplete but not for dispatch
//                        $resultsrec = mysql_query("UPDATE samples
//             					   SET result = '$testresultID' , repeatt = 1 , BatchComplete = 0
//			 			   WHERE (ID='$labcode[$a]')") or die(mysql_error());
//                    }
//                } else if ((($testresultID == 1) && ($secondduplicateresult == 2)) || (($testresultID == 2) && ($secondduplicateresult == 1))) {
//                    //1 positive, 1 negative collect new sample:
//
//                    $resultsrec = mysql_query("UPDATE samples
//             					 SET  result  	 = 5 ,repeatt  	 =  0 ,BatchComplete=0
//			 					WHERE (ID='$labcode[$a]')") or die(mysql_error());
//
//                    //update the atha repeat as final as this one failed
//                    $resultsrec = mysql_query("UPDATE samples
//             					SET  repeatt  	 =  1 ,BatchComplete=0
//			 					WHERE (ID='$secondsampeID')") or die(mysql_error());
//                } else if (($testresultID == 3) && ($secondduplicateresult == 3)) {
//                    //both indeterminate final positive
//                    $resultsrec = mysql_query("UPDATE samples
//             					 SET  result  	 = 2 ,repeatt  	 =  0 ,BatchComplete=0
//			 					WHERE (ID='$labcode[$a]')") or die(mysql_error());
//
//                    //update the atha repeat as final as this one failed
//                    $resultsrec = mysql_query("UPDATE samples
//             					SET  repeatt  	 =  1 ,BatchComplete=0
//			 					WHERE (ID='$secondsampeID')") or die(mysql_error());
//                } else if ((($testresultID == 1) && ($secondduplicateresult == 3)) || (($testresultID == 3) && ($secondduplicateresult == 1))) {
//                    //1 negative, 1 indeterminate final negative
//
//
//
//                    if ($testresultID == 1) {
//                        //final one with result n ready fro dispatch
//                        $resultsrec = mysql_query("UPDATE samples
//             					   SET result = '$testresultID' ,repeatt  	 =  0 ,BatchComplete=0
//			 					WHERE (ID='$labcode[$a]')") or die(mysql_error());
//                    } elseif ($testresultID == 3) {
//                        //update the atha repeat as final as this one failed
//                        $resultsrec = mysql_query("UPDATE samples
//             					   SET repeatt = 0 ,BatchComplete=0
//			 			   WHERE (ID='$secondsampeID')") or die(mysql_error());
//                        //mark the failed repeat as incomplete but not for dispatch
//                        $resultsrec = mysql_query("UPDATE samples
//             					   SET result = '$testresultID' ,repeatt =  1 ,BatchComplete=0
//			 			   WHERE (ID='$labcode[$a]')") or die(mysql_error());
//                    }
//                }
//            } else if (($noofretests == 2) && ($secondduplicateresult == 0)) {//2nd retest done but 2nd duplicate has no result just yet
//                //update results for repeats
//                $repeatcode = 1;
//                $resultsrec = mysql_query("UPDATE samples
//             				   SET result = '$testresultID' ,repeatt = '$repeatcode' 
//			 		   WHERE (ID='$labcode[$a]')") or die(mysql_error());
//
//
//                //update status of worksheet
//                $updateworksheetrec = mysql_query("UPDATE worksheets
//                                                   SET  Flag = 0, reviewedby='$userid',datereviewed='$datereviewed'
//			   			   WHERE ( ((worksheetno = '$waksheetno') AND (ID='$serialno')) )") or die(mysql_error());
//            }
        } else if ($paroid == 0) {//ordinary samples
            $noofretests = GetNoofRetests($paroid, $labss); //get the total no of retests for that samples
            //update results for the samples
            $resultsrec = mysql_query("UPDATE samples SET result = '$testresultID' 
			 	       WHERE (ID='$labcode[$a]')") or die(mysql_error());
            $logger->warn("Sample Id = {$labcode[$a]} is assigned Result Id = {$testresultID} and Query returned {$resultsrec}");

            $withresults = mysql_query("UPDATE pendingtasks SET status = 1 
			 		WHERE (sample='$labcode[$a]' AND task=1)") or die(mysql_error());

            //update status of worksheet
            $updateworksheetrec = mysql_query("UPDATE worksheets
                                               SET Flag = 0, reviewedby='$userid',datereviewed='$datereviewed'
			   		       WHERE ( ((worksheetno = '$waksheetno') AND (ID='$serialno')) )") or die(mysql_error());
            $logger->warn("Worksheet Id = {$serialno} with WorksheetNo = {$waksheetno}is updated and Query returned {$updateworksheetrec}");
            //This section is done on the final approval; Hence the comment!
            //
            //
//            if ($testresultID == 3) {
//                //update the indeterminate samples as ready for repeat
//                $repeatresults = mysql_query("UPDATE samples SET repeatt = 1 
//			 		WHERE (ID='$labcode[$a]')") or die(mysql_error());
//
//                //get details of indeterminate samples to be re saved for re test
//                $repeatsampledetails = getSampleetails($labcode[$a]);
//                extract($repeatsampledetails);
//                //save sample details with new ID.. save twice as repeats done in duplicates
//                $z = 0;
//                while ($z != 2) {
//
//                    //save the first samples for th repeat
//                    //$lastpatientid = GetLastPatientID();
//                    $saverepeatsample = GetSavedSamples($patientid, $labss, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno);
//                    /*
//                      $saverepeatsample=GetSavedSamples($batchno,$patient,$facility,$receivedstatus,$spots,$datecollected,$datedispatchedfromfacility, $datereceived,$comments,$labcomment,$labcode[$a],$rejectedreason,$reason_for_repeat,$test_reason) ;
//                     */
//
//                    //save pending task
//                    $task = 3;
//                    $status = 0;
//                    $lastid = GetLastSampleID($labss);
//                    $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $labss);
//                    //save complete batch
//                    $completeentry = mysql_query("UPDATE samples SET inputcomplete = 0 , approved = 0
//			  			   WHERE (ID = '$lastid')") or die(mysql_error());
//
//
//                    $z = $z + 1;
//                }
//            } else { //negative or positive sample
//                //update sample to be complete and ready for dispatch
//                $ifcompleterec = mysql_query("UPDATE samples SET BatchComplete = 0
//					      WHERE (ID='$labcode[$a]')") or die(mysql_error());
//            }//end if test result
//            
//            
        }//end if for ordinary sample*/
    }//end if for repeat value


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


        $st = "First approval completed for worksheet. " . $waksheetno . "<br/>" . " Second approval needed before dispatch.";
        echo '<script type="text/javascript">';
        echo "window.location.href='worksheetlist.php?p=$st&wtype=0'";
        echo '</script>';
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
    function reload(form)
    {
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
<style type="text/css">
    <!--
    .style1 {font-weight: bold}
    -->
</style>
<div  class="section">
    <div class="section-title">WORKSHEET NO <?php echo $worksheetno; ?> RESULTS DETAILS </div>
    <div class="xtop">
        <?php
        if ($success != "") {
            ?> 
            <table>
                <tr>
                    <td style="width:auto">
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
        if ($error != "") {
            ?> 
            <table>
                <tr>
                    <td style="width:auto">
                        <div class="error">
                            <?php
                            echo '<strong>' . ' <font color="#666600">' . $error . '</strong>' . ' </font>';
                            ?>
                        </div>
                    </td>
                </tr>
            </table>
        <?php } ?>
        <form  method="post" action="" name="worksheetform" onSubmit="return confirm('Are you sure you want to approve the below test results as final results?');">
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
                        <strong>
                            Worksheet No		
                        </strong>
                    </td>
                    <td class="comment">
                        <span class="style5">
                            <?php echo $worksheetno; ?>
                            <input type="hidden" name="waksheetno" value="<?php echo $worksheetno; ?>" />
                        </span>
                    </td>
                    <td class="comment style1 style4">Lot No </td>
                    <td>
                        <span class="comment style1 style4">
                            <?php echo $Lotno; ?> 
                        </span>
                    </td>
                    <td>
                        <span class="style5"><strong>Date Run </strong></span>
                    </td>
                    <td colspan="2"><?php echo $daterun; ?></td>
                </tr>
                <tr>
                    <td class="comment style1 style4">
                        Date Created		
                    </td>
                    <td class="comment" ><span class="style5"><?php echo $datecreated; //get current date                                       ?></span></td>
                    <td class="comment style1 style4"><span class="style5"><strong>KIT EXP </strong></span></td>
                    <td><?php echo $kitexpirydate; ?></td>	
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
                    <td colspan="2"><?php echo $datereviewedd; ?></td>
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
                        <td height="50" bgcolor="#dddddd" class="comment style1 style4"> <?php echo $pc; ?> </td>
                        <?php
                        $count++;
                        $colcount++;
                    }
                    $scount = 2;
                    // this is the list you had, but in PHP terms (in an array)
                    $dropDownList = array(
                        "Negative", "Positive",
                        "Indeterminate", "Collect New Sample", "Failed"
                    );
                    // put data into an array
                    //$dataArray = mysql_fetch_array($result);
                    //$dataArray = mysql_fetch_array($dropDownList);
                    $qury = "SELECT 
                                s.ID, s.patient, s.nmrlstampno
                             FROM 
                                samples AS s, labs AS l
                             WHERE
                                l.withresult != 1 AND s.lab = l.id AND worksheet = '$worksheetno'
                             ORDER BY
                                l.priority ASC, s.parentid DESC, s.ID ASC";
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
                        <td  height="50" width="145px">
                           
							NMRL Number: <strong><?php echo $nmrlstampno; ?></strong>
                            <input name='nmrlstampno[]' type='hidden' id='nmrlstampno' value='<?php echo $nmrlstampno; ?>'/>
                            <br/>   
                            Lab Code:<input name='labcode[]' type='hidden' id='labcode[]' value='<?php echo $ID; ?>'/>
                            <?php echo $ID . " " . $paroid; ?>
                            <br/>
                            <?php
					   ?>
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
                    }//end if modulus is 0
                }//end while
                ?>
                <tr bgcolor="#999999">
                    <td style="text-align: center;" colspan="7" bgcolor="#00526C">
                        <?php
                        if ($worksheet['approvalStatus'] == 0) {
                            ?>
                            <input type="submit" name="FirstApproval" value="First Approval" class="button"  />
                            <?php
                        } else if ($worksheet['approvalStatus'] == 1) {
                            ?>
                            <input type="submit" name="SaveWorksheet" value="Confirm & Approve Results" class="button"  />
                            <?php
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </form>
        <?php include('../includes/footer.php'); ?>