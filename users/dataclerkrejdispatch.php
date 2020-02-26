<?php
session_start();
$success = $_GET['z'];
$commentsuccess = $_GET['p'];
$labss = $_SESSION['lab'];
$accounttype = $_SESSION['accounttype'];

include('../includes/header.php');
if ($_REQUEST['ReleaseBatches']) {
    $checkbox = $_POST['checkbox'];
    $datedispatched = date('Y-m-d');
    $labcode = $_POST['labcode'];
    $patient = $_POST['patient'];
    $dispatch = $_POST['dispatch'];
    $BatchNo = $_POST['BatchNo'];
    foreach ($checkbox as $a => $b) { //update worksheet items with date dispac v
        $samplerec = mysql_query("UPDATE samples
              SET   datedispatched = '$datedispatched',resultprinted=1
			  			   WHERE (ID = '$labcode[$a]')") or die(mysql_error());

        //update pending tasks
        /* $samplerec2 = mysql_query("UPDATE pendingtasks
          SET   status = 1, dateupdated='$datedispatched'
          WHERE ( sample = '$patient[$a]' AND batchno='$BatchNo' AND task=2)")or die(mysql_error()); */

        $samplerec2 = mysql_query("Delete from pendingtasks
             	  			   WHERE sample = '$labcode[$a]' AND batchno='$BatchNo[$a]' AND task=6 and status = 1") or die(mysql_error());


        //save user activity			
        $tasktime = date("h:i:s a");
        $todaysdate = date("Y-m-d");
        $utask = 25; //dispatch samples by data clerk 1
        $activity = SaveUserActivity($userid, $utask, $tasktime, $labcode[$a], $todaysdate);
    }
    if ($samplerec && $samplerec2) {

        $st = '<center>' . ' The Selected Samples successfully released for printing below.</center>';
        echo '<script type="text/javascript">';
        echo "window.location.href='dispatchedrejectedsamples.php?releaseforprinting=$st'";
        echo '</script>';
    } else {
        $rr = '<center>' . ' Failed to Release Sample  ' . $labcode[$a] . ' for Result Printing, try again.</center>';
    }
}
?>

<style type="text/css">
    select {
        width: 250;}
    </style>	
    <script type="text/javascript" language="JavaScript">
        function checkscript() {

            var boxesTicked = ""

            for (i = document.getElementsByName('checkbox[]').length - 1; i >= 0; i--) {

                if (document.getElementsByName('checkbox[]')[i].checked) {

                    boxesTicked = boxesTicked + document.getElementsByName('checkbox[]')[i].value + "\n"

                }

            }

            if (boxesTicked == "") {
                alert("You must select a Sample to continue.")
                return false
            } else {

                return true;
            }

        }

    </script>
    <script>
        function select(a) {
            var theForm = document.myForm;
            for (i = 0; i < theForm.elements.length; i++) {
                if (theForm.elements[i].name == 'checkbox[]')
                    theForm.elements[i].checked = a;
            }
        }
    </script>
    <div  class="section">
    <div class="section-title">
        <?php
        if ($accttype == 4) {//..lab tech
            $menuname = 'RELEASE SAMPLES RESULTS for Dispatch';
            $waiting = 'Release';
        } else if ($accttype == 1) {//..data clerk
            $menuname = 'DISPATCH SAMPLE RESULTS';
            $waiting = 'Dispatch';
        }
        echo $menuname;
        ?>
    </div>
    <div class="xtop">
        <?php
        if ($success != "") {
            ?> 
            <table   >
                <tr>
                    <td style="width:auto" ><div class="success"><?php
                            echo '<strong>' . $success . '</strong>';
                            ?></div></th>
                </tr>
            </table>
        <?php } ?>
        <?php
        if ($commentsuccess != "") {
            ?> 
            <table   >
                <tr>
                    <td style="width:auto" ><div class="success"><?php
                            echo '<strong>' . $commentsuccess . '</strong>';
                            ?></div></th>
                </tr>
            </table>
        <?php } ?> 
        <table>
            <tr >
                <td colspan="6"><div class="notice"><?php echo '<strong>' . "Total Rejected Samples Waiting " . $waiting . " : " . rejectedsamplesforprinting($labss) . '</strong>'; ?></div></td>
            </tr>
            <tr >
                <td colspan="6"><a href="javascript:select(1)"><small>Check all</small></a> <small>|</small>
                    <a href="javascript:select(0)"><small>Uncheck All</small></a></td>
            </tr>
            <tr>

                <td><small>Key:   <strong> Delay   </strong> <font color='#0000FF'> Turn Around Time in Days from Date Released to Current  Date </font> </small></td>
            </tr>
        </table>		
        <form name="myForm" method="post" action="" onsubmit="return checkscript()" >
            <?php
            $rowsPerPage = 30; //number of rows to be displayed per page
// by default we show first page
            $pageNum = 1;

// if $_GET['page'] defined, use it as page number
            if (isset($_GET['page'])) {
                $pageNum = $_GET['page'];
            }

// counting the offset
            $offset = ($pageNum - 1) * $rowsPerPage;
//query database for all districts
            $query = "SELECT ID as 'labcode',batchno,patient,facility,datereceived,datetested,datemodified,result,datereleased,rejectedreason FROM samples
			WHERE samples.lab='$labss' AND  samples.receivedstatus=2 AND  samples.BatchComplete=1 AND  samples.resultprinted=0 and  samples.Flag=1 order by facility , datereleased ASC
			LIMIT $offset, $rowsPerPage";

            $resultt = mysql_query($query) or die(mysql_error()); //for main display

            $no = mysql_num_rows($resultt);

            if ($no != 0) {
                ?>

                <?php
// print the districts info in table
                echo '<table border="0"   class="data-table">
 <tr ><th>No</th><th>Sample ID</th><th>Lab No</th><th>Batch No</th><th>Facility</th><th>Province</th><th>Date Received</th><th>Rejected Reason</th><th>Date Released </th><th>Delay </th></tr>';
                $i = 0;
                while (list($labcode, $batchno, $patient, $facility, $datereceived, $datetested, $datemodified, $result, $datereleased, $rejectedreason) = mysql_fetch_array($resultt)) {
                    echo $jejectedreason;
                    //get sample facility name based on facility code
                    $facilityname = GetFacility($facility);

                    $facilitydetails = getFacilityDetails($facility);
                    extract($facilitydetails);
                    //get selected district ID
                    $distid = GetDistrictID($facility);
                    //get province ID
                    $provid = GetProvid($distid);
                    //get province name	
                    $provname = GetProvname($provid);



                    if (($datereceived != "" ) && ($datereceived != "0000-00-00") && ($datereceived != "1970-01-01")) {
                        $date_received = date("d-M-Y", strtotime($datereceived));
                    } else {
                        $date_received = "";
                    }
                    if (($datetested != "" ) && ($datetested != "0000-00-00") && ($datetested != "1970-01-01")) {
                        $date_datetested = date("d-M-Y", strtotime($datetested));
                    } else {
                        $date_datetested = "";
                    }
                    if (($datemodified != "" ) && ($datemodified != "0000-00-00") && ($datemodified != "1970-01-01")) {
                        $date_result_updated = date("d-M-Y", strtotime($datemodified));
                    } else {
                        $date_result_updated = "";
                    }
                    if (($datereleased != "" ) && ($datereleased != "0000-00-00") && ($datereleased != "1970-01-01")) {
                        $date_released = date("d-M-Y", strtotime($datereleased));
                    } else {
                        $date_released = "";
                    }


                    $currentdate = date('d-m-Y'); //get current date

                    if (($datereleased != "" ) && ($datereleased != "0000-00-00") && ($datereleased != "1970-01-01")) {

                        $date_released2 = date("d-m-Y", strtotime($datereleased));
                        $extradays = round(getWorkingDays($date_released2, $currentdate, $holidays));
                    } else {
                        $extradays = "";
                    }


                    $rejectedreason = GetRejectedReason($rejectedreason);
                    ?>
                    <tr class="even">
                        <td ><div align="center"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $i++; ?>" /></div>  </td>
                        <td ><strong><?php echo $patient; ?></strong>
                            <input name="patient[]" type="hidden" id="patient[]" value="<?php echo $patient; ?>"   ></td>
                        <td><input name="labcode[]" type="text"  id="labcode[]" value="<?php echo $labcode; ?>"  ></td>
                        <td ><div align="center"><?php echo $batchno; ?></div>
                            <input name="BatchNo[]" type="hidden" id="BatchNo[]" value="<?php echo $batchno; ?>" readonly="" size="2" ></td>
                        <td ><?php echo $facilityname; ?><input name="facility[]" type="hidden" id="facility[]" value="<?php echo $facilityname; ?>" readonly="" >
                            <input name="facilitycode[]" type="hidden"  id="facilitycode[]" value="<?php echo $facility; ?>"  > </td>
                        <td><?php echo $provname; ?></td>
                        <td ><?php echo $date_received; ?><input name="sampledrec[]" type="hidden" id="sampledrec[]" value="<?php echo $date_received; ?>" readonly="" size="11"></td>

                        <td ><?php echo $rejectedreason; ?></td>

                        <td ><?php echo $date_released; ?><input name="datereleased[]" type="hidden"  id="datereleased[]"value="<?php echo $date_released; ?>" readonly="" size="11" > </td>


                        <td ><div align="center"><?php echo $extradays; ?></div><input name="delay[]" type="hidden"  id="delay[]"value="<?php echo $extradays; ?>" readonly="" size="10" > </td>
                        <!--<td > <textarea rows='2' cols='40' name='msg[]'   id='msg[]' > </textarea> --></td>
                    </tr>
                    <?php
                }
                ?>
                <tr  bgcolor="#F0F0F0">
                    <td  align="center" colspan="2"><input type="submit" name="ReleaseBatches" value="Dispatch " class="button"> </td>
                    <td  align="center" colspan="12"><input name="btnCancel" type="button" id="btnCancel" value="Cancel " onClick=window.location.href = "home.php" class="button">  </td>
                </tr>
                </table>';

                <?php
                echo '<br>';


                $numrows = rejectedsamplesforprinting($labss);

                // how many pages we have when using paging?
                $maxPage = ceil($numrows / $rowsPerPage);

// print the link to access each page
                $self = $_SERVER['PHP_SELF'];
                $nav = '';
                for ($page = 1; $page <= $maxPage; $page++) {
                    if ($page == $pageNum) {
                        $nav .= " $page "; // no need to create a link to current page
                    } else {
                        $nav .= " <a href=\"$self?page=$page\">$page</a> ";
                    }
                }

// creating previous and next link
// plus the link to go straight to
// the first and last page

                if ($pageNum > 1) {
                    $page = $pageNum - 1;
                    $prev = " <a href=\"$self?page=$page\">[Prev]</a> ";

                    $first = " <a href=\"$self?page=1\">[First Page]</a> ";
                } else {
                    $prev = '&nbsp;'; // we're on page one, don't print previous link
                    $first = '&nbsp;'; // nor the first page link
                }

                if ($pageNum < $maxPage) {
                    $page = $pageNum + 1;
                    $next = " <a href=\"$self?page=$page\">[Next]</a> ";

                    $last = " <a href=\"$self?page=$maxPage\">[Last Page]</a> ";
                } else {
                    $next = '&nbsp;'; // we're on the last page, don't print next link
                    $last = '&nbsp;'; // nor the last page link
                }

// print the navigation link
                echo '<center>' . ' Page ' . $first . $prev . $nav . $next . $last . '</center>';
            } else {
                ?>
                <table   >
                    <tr>
                        <td style="width:auto" ><div class="notice"><?php
                                echo '<strong>' . ' <font color="#666600">' . 'No Batches Ready for Dispatch' . '</strong>' . ' </font>';
                                ?></div></th>
                    </tr>
                </table><?php
            }
            ?>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>