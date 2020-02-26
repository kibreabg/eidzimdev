<?php

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment;Filename=NMRL_WEEKLY_REPORT.xls");

require_once('../connection/config.php');
require_once('../includes/functions.php');
//get the filter count for the weekly reports

$labss = $_GET['labss'];
$weekenddate = $_GET['weekenddate'];
$weekstartdate = $_GET['weekstartdate'];

//show the records in excel	
$weekstartdatee = date("d-M-Y", strtotime($weekstartdate));
$weekenddatee = date("d-M-Y", strtotime($weekenddate));

$showsample = "SELECT samples.ID,
                        samples.patient,
                        samples.nmrlstampno,
                        samples.facility,
                        samples.receivedstatus,
                        samples.dateenteredindb,
                        samples.datetested,
                        samples.datecollected,
                        samples.datedispatched,
                        samples.datereceived,
                        samples.datereleased,
                        samples.rejectedreason,
                        samples.loggedinby
                FROM samples, facilitys
                WHERE samples.dateenteredindb BETWEEN '$weekstartdate' AND '$weekenddate' 
                  AND samples.flag = 1 
                  AND samples.facility = facilitys.ID 
                  AND facilitys.lab ='$labss' 
                  ORDER BY samples.dateenteredindb ASC";
$displaysample = @mysql_query($showsample) or die(mysql_error());
$showcount = mysql_num_rows($displaysample); //get the search count
//display the table and results
echo "<table>
	<tr><td>Start $weekstartdatee</td><td>End $weekenddatee</td><td>Counts$showcount</td></tr>
	<tr><th>Sample Request No</th><th>Province</th><th>District</th><th>Facility</th><th>NMRL Stamp No.</th><th>Received Status</th><th>Date Captured</th><th>Date Tested</th><th>Date Collected</th><th>Date Dispatched</th><th>Date Received</th><th>Date Released</th><th>Rejected Reason</th><th>Captured By</th></tr>";



while (list($ID, $patient, $nmrlstampno, $facility, $receivedstatus, $dateenteredindb, $datetested, $datecollected, $datedispatched, $datereceived, $datereleased, $rejectedreason, $loggedinby) = mysql_fetch_array($displaysample)) {

    $getfacilityname = GetFacility($facility);
    $distID = GetDistrictID($facility);
    $district = GetDistrictName($distID);
    $getfacilityname = GetFacility($facility);
    $provID = GetProvid($distID);
    $province = GetProvname($provID);
    $capturedBy = GetUserFullnames($loggedinby);
    $rejectedReasonName = GetRejectedReason($rejectedreason);
    $dateenteredindb = date("d-M-Y", strtotime($dateenteredindb));
    $datecollected = date("d-M-Y", strtotime($datecollected));
    $datereceived = date("d-M-Y", strtotime($datereceived));
    $datereleased = date("d-M-Y", strtotime($datereleased));
    $datetested = date("d-M-Y", strtotime($datetested));
    $datedispatched = date("d-M-Y", strtotime($datedispatched));


    if (($receivedstatus == 2) || (($result < 0 ) || ($result == ""))) {//..if the status was accepted OR result is blank
        //$datetested = "";
        //$datereleased = "";
        $datemodified = "";
        //$datedispatched = "";
        $showresult = "";
        $showstatus = GetReceivedStatus($receivedstatus); //display received status
    } else {
        $showstatus = GetReceivedStatus($receivedstatus); //display received status	
        //..check if the date tested is blank
        $datereceived4 = date("d-m-Y", strtotime($datereceived));
        $showresult = GetResultType($result); //display the result type
        //..check if the datedispatched is blank
        if ($datedispatched != '0000-00-00') {
            $datedispatched = date("d-M-Y", strtotime($datedispatched));
            $datedispatched4 = date("d-m-Y", strtotime($datedispatched));
            $tot = round((strtotime($datedispatched4) - strtotime($datereceived4)) / (60 * 60 * 24));
        } else {
            $datedispatched = '';
        }
    }

    echo "<tr>
            <td>$patient</td>
            <td>$province</td>
            <td>$district</td>
            <td>$getfacilityname</td>
            <td>$nmrlstampno</td>
            <td>$showstatus</td>
            <td>$dateenteredindb</td>
            <td>$datetested</td>
            <td>$datecollected</td>
            <td>$datedispatched</td>    
            <td>$datereceived</td>
            <td>$datereleased</td>
            <td>$rejectedReasonName</td>
            <td>$capturedBy</td>
	</tr>";
}
echo "</table>";
?>
