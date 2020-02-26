<?php
session_start();
include('../includes/header.php');
require_once('classes/tc_calendar.php');

$worksheetno = $_GET['q'];
$success = $_GET['p'];
$worksheet = getWorksheetDetails($worksheetno);
extract($worksheet);
$datecreated = date("d-M-Y", strtotime($datecreated));
$datereviewed = date("Y-m-d");
$datereviewedd = date("d-M-Y", strtotime($datereviewed));
$samplesPerRow = 3;
$creator = GetUserFullnames($createdby);
$userid = $_SESSION['uid']; //id of user who is updatin th record
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
        $testresultID = GetIDfromtableandname($outcome[$a], "results"); //th resuls 1-negative 2-positive

        if ($paroid > 0) { // repeat samples
            $parentresult = getparentsampleresult($paroid, $labss);  //determine if sample is repeat or not
            $noofretests = GetNoofRetests($paroid, $labss); //get the total no of retests for that samples
            $secondduplicateresult = GetDuplicateSampleResult($paroid, $labcode[$a]);
            $secondsampeID = GetDuplicateSampleResultID($paroid, $labcode[$a]);

            // retest done in duplicates and the 2nd duplicate also has a result	
            if (($noofretests == 2) && ($secondduplicateresult > 0)) {

                //pdate pendind tasks
                $repeatresults = mysql_query("UPDATE pendingtasks
                                              SET  status = 1 
                                              WHERE (sample='$labcode[$a]' AND task=3)") or die(mysql_error());
                ///update status of worksheet
                $updateworksheetrec = mysql_query("UPDATE worksheets
                                                   SET  Flag = 1, reviewedby='$userid',datereviewed='$datereviewed'
			   			   WHERE ( ((worksheetno = '$waksheetno') AND (ID='$serialno')) )") or die(mysql_error());
                //update sample in pending tasks as havin a result
                $withresults = mysql_query("UPDATE pendingtasks
                                            SET  status =  1 
                                            WHERE (sample='$labcode[$a]' AND task=1)") or die(mysql_error());

                if (($testresultID == 1) && ($secondduplicateresult == 1)) {
                    //both negative final negative

                    $resultsrec = mysql_query("UPDATE samples
                                               SET  result =  1 ,repeatt = 0 ,BatchComplete=2
                                               WHERE (ID='$labcode[$a]')") or die(mysql_error());

                    //update the atha repeat as final as this one failed
                    $resultsrec2 = mysql_query("UPDATE samples
             					SET  repeatt =  1 ,BatchComplete=0
			 			WHERE (ID='$secondsampeID')") or die(mysql_error());
                } else if (($testresultID == 2) && ($secondduplicateresult == 2)) {

                    //both positive final positive
                    $resultsrec = mysql_query("UPDATE samples
                                               SET  result = 2 ,repeatt =  0 ,BatchComplete=2
			 		       WHERE (ID='$labcode[$a]')") or die(mysql_error());

                    //update the atha repeat as final as this one failed
                    $resultsrec2 = mysql_query("UPDATE samples
             					SET  repeatt  	 =  1 ,BatchComplete=0
			 					WHERE (ID='$secondsampeID')") or die(mysql_error());
                } else if ((($testresultID == 2) && ($secondduplicateresult == 3)) || (($testresultID == 3) && ($secondduplicateresult == 2))) {
                    //1 positive, 1 indeterminate final positive
                    if ($testresultID == 2) {
                        //final one with result n ready fro dispatch
                        $resultsrec = mysql_query("UPDATE samples
             					SET  result  	 = '$testresultID' ,repeatt  	 =  0 ,BatchComplete=2
			 					WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    } elseif ($testresultID == 3) {
                        //update the atha repeat as final as this one failed
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
                    //both indeterminate final positive
                    $resultsrec = mysql_query("UPDATE samples
             					 SET  result  	 = 2 ,repeatt  	 =  0 ,BatchComplete=2
			 					WHERE (ID='$labcode[$a]')") or die(mysql_error());

                    //update the atha repeat as final as this one failed
                    $resultsrec = mysql_query("UPDATE samples
             					SET  repeatt  	 =  1 ,BatchComplete=0
			 					WHERE (ID='$secondsampeID')") or die(mysql_error());
                } else if ((($testresultID == 1) && ($secondduplicateresult == 3)) || (($testresultID == 3) && ($secondduplicateresult == 1))) {
                    //1 negative, 1 indeterminate final negative



                    if ($testresultID == 1) {
                        //final one with result n ready fro dispatch
                        $resultsrec = mysql_query("UPDATE samples
             					SET  result  	 = '$testresultID' ,repeatt  	 =  0 ,BatchComplete=2
			 					WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    } elseif ($testresultID == 3) {
                        //update the atha repeat as final as this one failed
                        $resultsrec = mysql_query("UPDATE samples
             					SET  repeatt  	 =  0 ,BatchComplete=2
			 					WHERE (ID='$secondsampeID')") or die(mysql_error());
                        //mark the failed repeat as incomplete but not for dispatch
                        $resultsrec = mysql_query("UPDATE samples
             					SET  result  	 = '$testresultID' ,repeatt  	 =  1 ,BatchComplete=0
			 					WHERE (ID='$labcode[$a]')") or die(mysql_error());
                    }
                }
            } else if (($noofretests == 2) && ($secondduplicateresult == 0)) {//2nd retest done but 2nd duplicate has no result just yet
                //update results for repeats
                $repeatcode = 1;
                $resultsrec = mysql_query("UPDATE samples
             					 SET  result  	 =  '$testresultID' ,repeatt  	 =  '$repeatcode' 
			 					WHERE (ID='$labcode[$a]')") or die(mysql_error());


                //update status of worksheet
                $updateworksheetrec = mysql_query("UPDATE worksheets
              SET  Flag = 1, reviewedby='$userid',datereviewed='$datereviewed'
			   			    WHERE ( ((worksheetno = '$waksheetno') AND (ID='$serialno')) )") or die(mysql_error());
            }
        } else if ($paroid == 0) {//ordinary samples
            $noofretests = GetNoofRetests($paroid, $labss); //get the total no of retests for that samples
            //update results for the samples
            $resultsrec = mysql_query("UPDATE samples
                                       SET result = '$testresultID' 
                                       WHERE (ID='$labcode[$a]')") or die(mysql_error());

            $withresults = mysql_query("UPDATE pendingtasks
             			 	SET status = 1 
			 		WHERE (sample='$labcode[$a]' AND task=1)") or die(mysql_error());

            //update status of worksheet
            $updateworksheetrec = mysql_query("UPDATE worksheets
                                               SET  Flag = 1, reviewedby='$userid',datereviewed='$datereviewed'
                                               WHERE ( ((worksheetno = '$waksheetno') AND (ID='$serialno')) )") or die(mysql_error());
            if ($testresultID == 3) {
                //update the indeterminate samples as ready for repeat
                $repeatresults = mysql_query("UPDATE samples
                                              SET repeatt = 1 
			 		      WHERE (ID='$labcode[$a]')") or die(mysql_error());

                //get details of indeterminate samples to be re saved for re test
                $repeatsampledetails = getSampleetails($labcode[$a]);
                extract($repeatsampledetails);
                //save sample details with new ID.. save twice as repeats done in duplicates
                $z = 0;
                while ($z != 2) {

                    //save the first samples for th repeat
                    //$lastpatientid = GetLastPatientID();
                    $saverepeatsample = GetSavedSamples($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $test_reason);
                    /*
                      $saverepeatsample=GetSavedSamples($batchno,$patient,$facility,$receivedstatus,$spots,$datecollected,$datedispatchedfromfacility, $datereceived,$comments,$labcomment,$labcode[$a],$rejectedreason,$reason_for_repeat,$test_reason) ;
                     */
                    //save pendin task
                    $task = 3;
                    $status = 0;
                    $lastid = GetLastSampleID($lab);
                    $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $lab);
                    //save complete batch
                    $completeentry = mysql_query("UPDATE samples
                                                  SET inputcomplete = 1 , approved=1
			  			  WHERE (ID = '$lastid')") or die(mysql_error());
                    $z = $z + 1;
                }
            } else if ($testresultID == 6) {
                //update the failed sample as ready for repeat
                $repeatresults = mysql_query("UPDATE samples SET repeatt = 1 
			 		      WHERE (ID='$labcode[$a]')") or die(mysql_error());

                //get details of failed sample to be re saved for re test
                $repeatsampledetails = getSampleetails($labcode[$a]);
                extract($repeatsampledetails);
                //save the failed sample detail with new ID
                $saverepeatsample = GetSavedSamples($patientid, $lab, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $test_reason, $othertest, $dateenteredindb, $loggedinby, $nmrlstampno);

                //save pending task
                $task = 3;
                $status = 0;
                $lastid = GetLastSampleID($lab);
                $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $lab);
                //save complete batch
                $completeentry = mysql_query("UPDATE samples SET inputcomplete = 1 , approved = 1
			  			  WHERE (ID = '$lastid')") or die(mysql_error());
            } else { //negative or positive sample
                //update sample to be complete and ready for dispatch
                $ifcompleterec = mysql_query("UPDATE samples
                                              SET  BatchComplete = 2
					      WHERE (ID='$labcode[$a]')") or die(mysql_error());
            }//end if test result
        }//end if for ordubary sample*/
    }//end if for repeat value


    if ($resultsrec && $updateworksheetrec) {
        //save user activity
        $tasktime = date("h:i:s a");
        $todaysdate = date("Y-m-d");
        $utask = 8; //user task = 1st review

        $activity = SaveUserActivity($userid, $utask, $tasktime, $waksheetno, $todaysdate);

        $st = "Test Results for Worksheet No. " . $waksheetno . "  have been successfully approved. " . "<br>" . " They may now be dispatched " . "<br>" . " All the indeterminates have been set aside for retest. ";
        echo '<script type="text/javascript">';
        echo "window.location.href='dispatch.php?z=$st'";
        echo '</script>';
    } else {
        $error = '<center>' . "Failed to Update test results, try again " . '</center>';
    }
}//end if request
else if ($_REQUEST['FirstApproval']) {
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
//                                              SET  status = 1 
//                                              WHERE (sample='$labcode[$a]' AND task=3)") or die(mysql_error());
//                ///update status of worksheet
//                $updateworksheetrec = mysql_query("UPDATE worksheets
//                                                   SET  Flag = 0, reviewedby='$userid',datereviewed='$datereviewed'
//			   			   WHERE ( ((worksheetno = '$waksheetno') AND (ID='$serialno')) )") or die(mysql_error());
//                //update sample in pending tasks as havin a result
//                $withresults = mysql_query("UPDATE pendingtasks
//                                            SET  status =  1 
//                                            WHERE (sample='$labcode[$a]' AND task=1)") or die(mysql_error());
//
//                if (($testresultID == 1) && ($secondduplicateresult == 1)) {
//                    //both negative final negative
//
//                    $resultsrec = mysql_query("UPDATE samples
//                                               SET  result =  1 ,repeatt = 0 ,BatchComplete=0
//                                               WHERE (ID='$labcode[$a]')") or die(mysql_error());
//
//                    //update the atha repeat as final as this one failed
//                    $resultsrec2 = mysql_query("UPDATE samples
//             					SET  repeatt =  1 ,BatchComplete=0
//			 			WHERE (ID='$secondsampeID')") or die(mysql_error());
//                } else if (($testresultID == 2) && ($secondduplicateresult == 2)) {
//
//                    //both positive final positive
//                    $resultsrec = mysql_query("UPDATE samples
//                                               SET  result = 2 ,repeatt =  0 ,BatchComplete=0
//			 		       WHERE (ID='$labcode[$a]')") or die(mysql_error());
//
//                    //update the atha repeat as final as this one failed
//                    $resultsrec2 = mysql_query("UPDATE samples
//             					SET  repeatt  	 =  1 ,BatchComplete=0
//			 					WHERE (ID='$secondsampeID')") or die(mysql_error());
//                } else if ((($testresultID == 2) && ($secondduplicateresult == 3)) || (($testresultID == 3) && ($secondduplicateresult == 2))) {
//                    //1 positive, 1 indeterminate final positive
//                    if ($testresultID == 2) {
//                        //final one with result n ready fro dispatch
//                        $resultsrec = mysql_query("UPDATE samples
//             					SET  result  	 = '$testresultID' ,repeatt  	 =  0 ,BatchComplete=0
//			 					WHERE (ID='$labcode[$a]')") or die(mysql_error());
//                    } elseif ($testresultID == 3) {
//                        //update the atha repeat as final as this one failed
//                        $resultsrec = mysql_query("UPDATE samples
//             					SET  repeatt  	 =  0 ,BatchComplete=0
//			 					WHERE (ID='$secondsampeID')") or die(mysql_error());
//                        //mark the failed repeat as incomplete but not for dispatch
//                        $resultsrec = mysql_query("UPDATE samples
//             					SET  result  	 = '$testresultID' ,repeatt  	 =  1 ,BatchComplete=0
//			 					WHERE (ID='$labcode[$a]')") or die(mysql_error());
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
//             					SET  result  	 = '$testresultID' ,repeatt  	 =  0 ,BatchComplete=0
//			 					WHERE (ID='$labcode[$a]')") or die(mysql_error());
//                    } elseif ($testresultID == 3) {
//                        //update the atha repeat as final as this one failed
//                        $resultsrec = mysql_query("UPDATE samples
//             					SET  repeatt  	 =  0 ,BatchComplete=0
//			 					WHERE (ID='$secondsampeID')") or die(mysql_error());
//                        //mark the failed repeat as incomplete but not for dispatch
//                        $resultsrec = mysql_query("UPDATE samples
//             					SET  result  	 = '$testresultID' ,repeatt  	 =  1 ,BatchComplete=0
//			 					WHERE (ID='$labcode[$a]')") or die(mysql_error());
//                    }
//                }
//            } else if (($noofretests == 2) && ($secondduplicateresult == 0)) {//2nd retest done but 2nd duplicate has no result just yet
//                //update results for repeats
//                $repeatcode = 1;
//                $resultsrec = mysql_query("UPDATE samples
//             					 SET  result  	 =  '$testresultID' ,repeatt  	 =  '$repeatcode' 
//			 					WHERE (ID='$labcode[$a]')") or die(mysql_error());
//
//
//                //update status of worksheet
//                $updateworksheetrec = mysql_query("UPDATE worksheets
//              SET  Flag = 0, reviewedby='$userid',datereviewed='$datereviewed'
//			   			    WHERE ( ((worksheetno = '$waksheetno') AND (ID='$serialno')) )") or die(mysql_error());
//            }
        } else if ($paroid == 0) {//ordinary samples
            $noofretests = GetNoofRetests($paroid, $labss); //get the total no of retests for that samples
            //update results for the samples
            $resultsrec = mysql_query("UPDATE samples
                                       SET result = '$testresultID' 
                                       WHERE (ID='$labcode[$a]')") or die(mysql_error());

            $withresults = mysql_query("UPDATE pendingtasks
             			 	SET status = 1 
			 		WHERE (sample='$labcode[$a]' AND task=1)") or die(mysql_error());

            //update status of worksheet
            $updateworksheetrec = mysql_query("UPDATE worksheets
                                               SET  Flag = 0, reviewedby='$userid',datereviewed='$datereviewed'
                                               WHERE ( ((worksheetno = '$waksheetno') AND (ID='$serialno')) )") or die(mysql_error());
            //This section is done on the final approval; Hence the comment!
            //
            //
//            if ($testresultID == 3) {
//                //update the indeterminate samples as ready for repeat
//                $repeatresults = mysql_query("UPDATE samples
//                                              SET repeatt = 1 
//			 		      WHERE (ID='$labcode[$a]')") or die(mysql_error());
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
//                    $saverepeatsample = GetSavedSamples($patientid, $labss, $batchno, $patient, $facility, $receivedstatus, $spots, $datecollected, $datedispatchedfromfacility, $datereceived, $comments, $labcomment, $labcode[$a], $rejectedreason, $reason_for_repeat, $test_reason);
//                    /*
//                      $saverepeatsample=GetSavedSamples($batchno,$patient,$facility,$receivedstatus,$spots,$datecollected,$datedispatchedfromfacility, $datereceived,$comments,$labcomment,$labcode[$a],$rejectedreason,$reason_for_repeat,$test_reason) ;
//                     */
//                    //save pendin task
//                    $task = 3;
//                    $status = 0;
//                    $lastid = GetLastSampleID($labss);
//                    $repeat = SaveRepeatSamplesTask($task, $batchno, $status, $lastid, $labss);
//                    //save complete batch
//                    $completeentry = mysql_query("UPDATE samples
//                                                  SET inputcomplete = 1 , approved=1
//			  			  WHERE (ID = '$lastid')") or die(mysql_error());
//                    $z = $z + 1;
//                }
//            } else { //negative or positive sample
//                //update sample to be complete and ready for dispatch
//                $ifcompleterec = mysql_query("UPDATE samples
//                                              SET  BatchComplete = 0
//					      WHERE (ID='$labcode[$a]')") or die(mysql_error());
//            }//end if test result
        }//end if for ordubary sample*/
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

        $st = "First approval completed for worksheet. " . $waksheetno . "<br/>" . " Second approval needed before dispatch." . "<br/>" . " All the indeterminates have been set aside for retest. ";
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
        width: 250;}
    </style>	<script language="javascript" src="calendar.js"></script>
    <link type="text/css" href="calendar.css" rel="stylesheet" />	
    <SCRIPT language=JavaScript>
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
    <div class="section-title"><FONT style="font-family:Verdana, Arial, Helvetica, sans-serif">WORKSHEET NO <FONT color="#990000"><?php echo $worksheetno; ?></FONT> RESULTS DETAILS</FONT> </div>
    <div class="xtop">
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
        if ($error != "") {
            ?> 
            <table   >
                <tr>
                    <td style="width:auto" ><div class="error"><?php
                            echo '<strong>' . ' <font color="#666600">' . $error . '</strong>' . ' </font>';
                            ?></div></th>
                </tr>
            </table>
        <?php } ?>
        <form  method="post" action="" name="worksheetform"  onSubmit="return confirm('Are you sure you want to approve the below test results as final results?');" >
            <table  width="" border="1" style="border-right-color:#CCCCCC; border-bottom-color:#CCCCCC; font-family:Courier New, Courier, monospace">
                <tr >

                    <td width="120">Serial No </td>
                    <td width="179"><?php echo $ID; ?><input type="hidden" name="serialno" value="<?php echo $ID; ?>" /> </td>
                    <td width="120">Date Created </td>
                    <td width="179"><?php echo $datecreated; //get  date created          ?></span></td>
                    <td width="120">Master LOT NO</td>
                    <td width="195"><?php echo $Lotno; ?></td>

                </tr>
                <tr >
                    <td>Worksheet No </td>
                    <td><?php echo $worksheetno; ?><input type="hidden" name="waksheetno" value="<?php echo $worksheetno; ?>" /></td>	
                    <td>Created By </td>
                    <td><?php echo $creator; ?></td>
                    <td>EXPIRY Date</td>
                    <td><FONT color="#FF0000"><strong><?php echo $kitexpirydate; ?></strong></FONT>	</td>	
                </tr>

                <tr >
                    <td>Date Run </td>
                    <td><?php echo $daterun; ?></td>	
                    <td>Reviewed By  </td>
                    <td> <?php echo $reviewedby; ?> </td>
                    <td >Reviewed Date </td>
                    <td><?php echo $datereviewedd; ?> </td>	

                </tr>

            </table>
            <table  class="data-table" >

                <tr><th>1</th><th>3</th><th>5</th><th>7</th><th>9</th><th>11</th></tr>
                <tr>
                    <td><table border="1" class="data-table">
                            <?php
// this is the list you had, but in PHP terms (in an array)
                            $dropDownList = array(
                                "Negative", "Positive",
                                "Indeterminate", "Collect New Sample", "Failed"
                            );

                            $count = 1;

                            $rowcount = 1;
                            $d = 1;
                            ?>
                            <?php
//$i=mysql_num_rows($result);
                            for ($i = 1; $i <= 4; $i++) {
                                if ($i == 1) {
                                    $count = "<p>&nbsp;</p><strong>NC1</strong><br>";
                                }
                                if ($i == 2) {
                                    $count = "<p>&nbsp;</p><strong>PC1</strong><br>";
                                } ELSEIF ($i == 3) {
                                    $count = "<p>&nbsp;</p><strong>CDC NC</strong><br>";
                                } ELSEIF ($i == 4) {
                                    $count = "<p>&nbsp;</p><strong>CDC PC</strong><br>";
                                }
                                $RE = $rowcount % 8;
                                ?>
                                <tr><td height="50" > <?php echo $count; ?> </td><td height="50" > <?php echo $count; ?> </td></tr><?php
                                $count++;
                                $rowcount++;
                            }


                            $colcount = 1;

                            $qury = "SELECT ID,patient,nmrlstampno
         FROM samples
		WHERE worksheet='$worksheetno' ORDER BY parentid DESC,nmrlstampno asc,ID ASC";
                            $result = mysql_query($qury) or die(mysql_error());

                            while (list($ID, $patient, $nmrlstampno) = mysql_fetch_array($result)) {
                                $paroid = getParentID($ID, $labss); //get parent id

                                if ($paroid == 0) {
                                    $paroid = "";
                                } else {
                                    $paroid = " - " . $paroid;
                                }


//get sample sample test results
                                $routcome = GetSampleResult($ID);
                                // grab data from database
                                $result2 = mysql_query("SELECT results.name FROM samples,results  WHERE   samples.ID = '$ID' AND 		samples.result=results.ID");
                                // put data into an array
                                $dataArray = mysql_fetch_array($result2);


                                $RE = $rowcount % 8;
                                ?>
                                <tr>
                                    <td  height="50" >  
                                        <font style="font-size:8px">
                                        NMRL Stamp No &nbsp;&nbsp;: <strong><?php echo $nmrlstampno; ?></strong>  <br>   
                                        Request No  : <strong><?php echo $patient; ?></strong> </font> <br>  
                                        <font color="#0000FF">Lab Code &nbsp;&nbsp;&nbsp;&nbsp;:</font> <input name='labcode[]' type='hidden' id='labcode[]' value='<?php echo $ID; ?>'  readonly=''><strong><?php echo $ID . " " . $paroid; ?></strong> </td> <td> <br> <?php
                                        echo "<select name=\"testresult[]\" style='width:80px'>\n";

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
                                        ?> </td>
                                </tr>
                                <?php
                                $rowcount++;
                                $d++;

                                if ($RE == 0) {
                                    ?>
                                </table>	</td>
                            <td>
                                <table class="data-table" border="1">

                                    <?php
                                }
                            }


                            //end while
                            ?>   
                    </td>
                </tr>  
            </table>
            </td>
            </tr>
            <tr class="even">
                <td colspan="13">
                    <div class="error">
                        <u><strong>KINDLY CONFIRM EACH OF THE RESULTS BEFORE APPROVAL!!!!!!!! THESE RESULTS WILL BE SAVED & CANNOT BE EDITED!!!</strong></U></div>
                    </div>
                </td>
            </tr>

            <th colspan='13'>
                <?php
                if ($worksheet['approvalStatus'] == 0) {
                    ?>
                    <input type="submit" name="FirstApproval" value="First Approval" class="button"  />
                    <?php
                } else if ($worksheet['approvalStatus'] == 1) {
                    ?>
                    <input type='submit' name='SaveWorksheet' value='Confirm & Approve Results' class='button' style="width:400px">
                    <?php
                }
                ?>
            </th>
            </table>
        </form>
        <?php include('../includes/footer.php'); ?>