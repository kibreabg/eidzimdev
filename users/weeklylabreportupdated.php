<?php
require_once('../connection/config.php');
include('../includes/header.php');
$labss = $_SESSION['lab'];
//get the weekly report date filter variables
$weekenddate = $_GET['weekenddate'];
$weekstartdate = $_GET['weekstartdate'];
?>

<div>
    <div class="section-title">LAB REPORT FILTER RESULTS</div>
    <div><a href='javascript:history.back(-1)'><img src='../img/back.gif' alt='Go Back'/></a>
        <?php
        //get the filter count for the weekly reports
        $showsample = "SELECT
                        s.ID,
                        s.patient,
                        s.nmrlstampno,
                        s.facility,
                        s.receivedstatus,
                        s.dateenteredindb,
                        s.datetested,
                        s.datecollected,
                        s.datedispatched,
                        s.datereceived,
                        s.datereleased,
                        s.rejectedreason,
                        s.loggedinby
                        FROM samples s, facilitys f , rejectedreasons r
                        WHERE s.dateenteredindb BETWEEN '$weekstartdate' AND '$weekenddate' 
                          AND s.flag = 1 
                          AND s.facility = f.ID 
						  
                          AND f.lab = '$labss' 
                          AND s.repeatt = 0 
                          ORDER BY s.dateenteredindb ASC";
        $displaysample = @mysql_query($showsample) or die(mysql_error());
        $showcount = mysql_num_rows($displaysample); //get the search count

        $weekstartdatee = date("d-M-Y", strtotime($weekstartdate));
        $weekenddatee = date("d-M-Y", strtotime($weekenddate));

        if ($showcount != 0) { //display search results if search parameter is NOT NULL
            $samplecount = 0;

            $weekstartdatee = date("d-M-Y", strtotime($weekstartdate));
            $weekenddatee = date("d-M-Y", strtotime($weekenddate));

            //filter message
            echo "<table border=0>
                    <tr>
                        <td width='600'><div class='notice'><small>Samples Received between </small><strong>$weekstartdatee</strong><small> and </small><strong>$weekenddatee</strong> <small>returned </small><strong>$showcount</strong> results.</div></td>
			<td>&nbsp;</td>
			<td width='400'><div align='right'><a href='downloadweeklyreportupdated.php?startdate=$weekstartdate&enddate=$weekenddate' title='Click to Download PDF Report' target='_blank'><img src='../img/pdf.jpeg' alt='Pdf'>&nbsp;<small>PDF</small> </a> &nbsp;&nbsp;  |&nbsp;&nbsp;  <a href='downloadweeklyexcelupdated.php?weekstartdate=$weekstartdate&weekenddate=$weekenddate&labss=$labss' title='Click to Download Excel Report' target='_blank'><img src='../img/excel.gif' alt='Excel'>&nbsp;<small>EXCEL</small></a></div></td>
                    </tr>
                  </table>";
            //display the table and results
            echo "<table border=0 class='data-table'>
			<tr>
                        <th></th><th>Sample Request No</th><th>Facility</th><th>NMRL Stamp No.</th><th>Received Status</th><th>Date Captured</th><th>Date Tested</th><th>Date Collected</th><th>Date Dispatched</th><th>Date Received</th><th>Date Released</th><th>Rejected Reason</th><th>Captured By</th></tr>";

            while (list($ID, $patient, $nmrlstampno, $facility, $receivedstatus, $dateenteredindb, $datetested, $datecollected, $datedispatched, $datereceived, $datereleased, $rejectedreason, $loggedinby) = mysql_fetch_array($displaysample)) {
                $getfacilityname = GetFacility($facility);
                $capturedBy = GetUserFullnames($loggedinby);
				$name = GetRejectedReason($rejectedreason);
                $samplecount = $samplecount + 1;
                $dateenteredindb = date("d-M-Y", strtotime($dateenteredindb));
                $datecollected = date("d-M-Y", strtotime($datecollected));
                $datereceived = date("d-M-Y", strtotime($datereceived));
                $datereleased = date("d-M-Y", strtotime($datereleased));
                $datetested = date("d-M-Y", strtotime($datetested));
                $datedispatched = date("d-M-Y", strtotime($datedispatched));

                if ($receivedstatus != 1) {//..if the received status is NOT ACCEPTED then show RED
                    $fcolor = '#FF0000';
                } else {
                    $fcolor = '';
                }

                if ($result != 1) {//..if the received status is NOT NEGATIVE then show RED
                    $rfcolor = '#FF0000';
                } else {
                    $rfcolor = '';
                }

                if (($receivedstatus == 2) || (($result < 0 ) || ($result == ""))) {//..if the status was accepted OR result is blank
                    //$datetested = "";
                    //$datereleased = "";
                    $datemodified = "";
                    //$datedispatched = "";
                    $showresult = "";
                    $showstatus = GetReceivedStatus($receivedstatus); //display received status
                } else {
                    $showstatus = GetReceivedStatus($receivedstatus); //display received status	


                    $datereceived4 = date("d-m-Y", strtotime($datereceived));
                    $showresult = GetResultType($result); //display the result type
                }

                echo "<tr class='even'>
                        <td><div align='center'>$samplecount</div></td>
                        <td>$patient</td>
			<td>$getfacilityname</td>
                        <td><div align='center'>$nmrlstampno</div></td>
                        <td><div align='center'>$showstatus</div></td>   
                        <td><div align='center'>$dateenteredindb</div></td>
                        <td><div align='center'>$datetested</div></td>                          
			<td><div align='center'>$datecollected</div></td>
                        <td><div align='center'>$datedispatched</div></td>
			<td><div align='center'>$datereceived</div></td>
                        <td><div align='center'>$datereleased</div></td>
                        <td><div align='center'>$name</div></td>
                        <td><div align='center'>$capturedBy</div></td>
                     </tr>";
            }echo "</table>";
        } else { //show message if the search parameter is null
            echo "<center><strong>There are no samples received between $weekstartdatee and $weekenddatee. <br><a href='labreports.php'>Please try again.</a></strong></center>";
        }
        ?>	
    </div>	
</div>


<?php include('../includes/footer.php'); ?>