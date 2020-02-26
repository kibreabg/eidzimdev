<?php
session_start();
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
include('../includes/header.php');
$labss = $_SESSION['lab'];
$accttype = $_SESSION['accounttype'];
$success = $_GET['p'];
$datefilter = $_GET['datefilter'];
$fromfilter = $_GET['fromfilter'];
$tofilter = $_GET['tofilter'];
$approvesuccess = $_GET['approvesuccess'];
$sampleid = $_GET['sampleid'];
$currentdate = date('Y'); //show the current year
$lowestdate = GetAnyDateMin(); //get the lowest year from date received
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
        var val=form.cat.options[form.cat.options.selectedIndex].value;
        self.location='addsample.php?catt=' + val ;
    }
</script>

<div  class="section">
    <div class="section-title">BATCHES AWAITING APPROVAL </div>
    <div class="xtop">

        <?php
        if ($success != "") {
            ?> 
            <table>
                <tr>
                    <td style="width:auto" ><div class="success">
                            <?php
                            echo '<strong>' . ' <font color="#666600">' . $success . '</strong>' . ' </font>';
                            ?>
                        </div></th>
                </tr>
            </table>
            <?php
        }
        if ($approvesuccess == "1") {
            ?> 
            <table>
                <tr>
                    <td style="width:auto"><div class="success">
                            <?php
                            echo '<strong>' . ' <font color="#666600">Sample Request No ' . $sampleid . ' received status has been updated.</strong>' . ' </font>';
                            ?>
                        </div></th>
                </tr>
            </table>
            <?php
        } else if ($approvesuccess == "0") {
            ?> 
            <table   >
                <tr>
                    <td style="width:auto"><div class="error">
                            <?php
                            echo '<strong>' . ' <font color="#666600">Sample Request No ' . $sampleid . ' received status has <u>NOT</u> been updated. Please try again.</strong>' . ' </font>';
                            ?>
                        </div></th>
                </tr>
            </table>
            <?php
        }


        $rowsPerPage = 50; //number of rows to be displayed per page
// by default we show first page
        $pageNum = 1;

// if $_GET['page'] defined, use it as page number
        if (isset($_GET['page'])) {
            $pageNum = $_GET['page'];
        }

// counting the offset
        $offset = ($pageNum - 1) * $rowsPerPage;
//query database for all districts
        $qury = "SELECT DISTINCT batchno
		 FROM samples
		 WHERE  samples.lab='$labss' and samples.Flag = 1 AND samples.approved = 0
		 ORDER BY datereceived ASC
		 LIMIT $offset, $rowsPerPage";

        $result = mysql_query($qury) or die(mysql_error()); //for main display
        $result2 = mysql_query($qury) or die(mysql_error()); //for calculating samples with results and those without

        $no = mysql_num_rows($result);

        //get the total no of samples awaiting approval
        $approvalqury = "SELECT ID FROM samples
			 WHERE approved = 0 and flag = 1 ";

        $approvalqueryresult = mysql_query($approvalqury) or die(mysql_error());
        $notapproved = mysql_num_rows($approvalqueryresult);


        if ($no != 0) {
            echo '
		<table><tr><td>
		<div class="notice"><strong><small>No of Samples Awaiting Approval : ' . $notapproved . '</small></strong></div></td></tr></table>';
            //echo "Total Samples:" .Gettotalsamples($labss);
            // print the districts info in table
            echo "<table class='data-table'>
		 <tr ><th><small>Batch No</small></th>
			 <th><small>Facility</small></th>
			 <th><small>Date Received</small></th>
			 <th><small><font color='#0000FF'>No. of Samples</font></small></th>
			 <th><small><font color='#FF0000'>No. Not Received</font></small></th>
			 <th><small><font color='#FF0000'>No. of Rejected Samples</font></small></th>
			 <th><small><font color='#336600'>Samples With Results</font></small></th>
			 <th><small>Samples With No Results</small></th>
			 <th><small>Task</small></th></tr>";
            while (list($batchno) = mysql_fetch_array($result)) {
                //get patient/sample code
                $patient = GetPatient($batchno, $labss);
                //get bach received date
                $sdrec = GetDatereceived($batchno, $labss);
                //get patient gender and mother id based on sample code of sample
                $mid = GetMotherID($patient);
                //get atient gender
                $pgender = GetPatientGender($patient);
                //get sample facility code based  on mothers id
                $facility = GetFacilityCode($batchno, $labss);
                //get sample facility name based on facility code
                $facilityname = GetFacility($facility);
                //count no. of samples per batch
                $num_samples = GetSamplesPerBatch($batchno, $labss);
                //count no. of samples per batch that are not received
                $notrec_samples = GetNotReceivedSamplesPerBatch($batchno, $labss);
                //count no. of samples per batch that are rejected
                $rej_samples = GetRejectedSamplesPerBatch($batchno, $labss);
                //no of saMPLES IN BATCH with results
                $with_result_samples = GetSamplesPerBatchwithResults($batchno, $labss);
                ////no of saMPLES IN BATCH without results
                $no_result_samples = (($num_samples - $with_result_samples) - ($rej_samples + $notrec_samples));
                //count no of samples in the particular batch awaiting confirmation
                $pendindsamples = gettotalpendingsamplesinbatches($batchno, $labss);

                echo "<tr class='even'>
					<td ><div align='center'><a href=\"BatchDetails.php" . "?ID=$batchno" . "\" title='Click to view  Samples in this batch'>$batchno</a></div></td>
					<td >$facilityname</td>
					<td >$sdrec </td>
					<td ><div align='center'> $num_samples</div></td>
					<td ><div align='center'>$notrec_samples</div></td>
					<td ><div align='center'> $rej_samples</div></td>
					<td ><div align='center'> $with_result_samples</div></td>
					<td ><div align='center'>$no_result_samples</div> </td>
					<td ><a href=\"BatchDetails.php" . "?view=1&ID=$batchno&labview=$labss" . "\" title='Click to view Samples in this batch awaiting your approval'>View Samples for approval [ $pendindsamples ] </a>  
					</td>
			</tr>";
            }
            echo '</table>';


            echo '<br>';
            $numrows = GetTotalNoBatches($labss); //get total no of batches
            // how many pages we have when using paging?
            $maxPage = ceil($numrows / $rowsPerPage);

            // print the link to access each page
            $self = $_SERVER['PHP_SELF'];
            $nav = '';

            for ($page = 1; $page <= $maxPage; $page++) {
                if ($page == $pageNum) {
                    $nav .= " $page "; // no need to create a link to current page
                }
                /* else
                  {
                  $nav .= " <a href=\"$self?page=$page\">$page</a> ";
                  } */
            }

            // creating previous and next link
            // plus the link to go straight to
            // the first and last page

            if ($pageNum > 1) {
                $page = $pageNum - 1;
                $prev = " <a href=\"$self?page=$page\">Prev  |</a> ";

                $first = " <a href=\"$self?page=1\">First Page | </a> ";
            } else {
                $prev = '&nbsp;'; // we're on page one, don't print previous link
                $first = '&nbsp;'; // nor the first page link
            }

            if ($pageNum < $maxPage) {
                $page = $pageNum + 1;
                $next = " <a href=\"$self?page=$page\"> | Next | </a> ";

                $last = " <a href=\"$self?page=$maxPage\">  Last Page </a> ";
            } else {
                $next = '&nbsp;'; // we're on the last page, don't print next link
                $last = '&nbsp;'; // nor the last page link
            }

            // print the navigation link
            echo '<center>' . $first . "  " . $prev . " " . $nav . "  " . $next . "  " . $last . '</center>';
        } else {
            ?>
            <table>
                <tr>
                    <td style="width:auto" >
                        <div class="notice">
                            <?php echo '<strong>' . ' <font color="#666600">' . 'No Batches Awaiting Approval' . '</strong>' . ' </font>'; ?></div>
                    </td>
                </tr>
            </table>
        <?php } ?>

    </div>
</div>

<?php include('../includes/footer.php'); ?>