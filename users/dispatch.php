<?php
session_start();
$success = $_GET['z'];
$commentsuccess = $_GET['p'];
$labss = $_SESSION['lab'];
$accounttype = $_SESSION['accounttype'];
if ($accounttype == 1) { //data clerk...redirect to dispatchedresults.php
    echo '<script type="text/javascript">';
    echo "window.location.href='dataclerkdispatch.php'";
    echo '</script>';
}
?>
<?php include('../includes/header.php'); ?>
<style type="text/css">
    select {
        width: 250;
    }
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
            alert ("You must select a Batch to continue.")
            return false
        }
        else {
            return true;
        }

    }

</script>
<script>
    function select(a) {
        var theForm = document.myForm;
        for (i=0; i<theForm.elements.length; i++) {
            if (theForm.elements[i].name=='checkbox[]')
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
            <table>
                <tr>
                    <td style="width:auto"><div class="success">
                            <?php
                            echo '<strong>' . $success . '</strong>';
                            ?>
                        </div>
                    </td>
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
            <tr>
                <td colspan="6"><div class="notice"><?php echo '<strong>' . "Total Samples Waiting " . $waiting . " : " . GetTotalCompleteBatches(2, $labss) . '</strong>'; ?></div></td>
            </tr>
            <tr>
                <td colspan="6"><a href="javascript:select(1)"><small>Check all</small></a> <small>|</small>
                    <a href="javascript:select(0)"><small>Uncheck All</small></a></td>
            </tr>
        </table>		
        <form name="myForm" method="post" action="confirmdispatch.php" onsubmit="return checkscript()" >
            <?php
            $rowsPerPage = 30; //number of rows to be displayed per page
// by default we show first page
            $pageNum = 1;

// if $_GET['page'] defined, use it as page number
            if (isset($_GET['page'])) {
                $pageNum = $_GET['page'];
            }

            //counting the offset
            $offset = ($pageNum - 1) * $rowsPerPage;
            //query database for all districts
            $query = "SELECT ID,batchno,patient,facility,datereceived,datetested,datemodified,result FROM samples
			WHERE samples.BatchComplete=2 and samples.Flag=1 and datedispatched = '' and printed = 0 and receivedstatus = 1 and approved = 1 and flag=1 and repeatt=0 order by facility , datetested ASC
			LIMIT $offset, $rowsPerPage";

            $resultt = mysql_query($query) or die(mysql_error()); //for main display

            $no = mysql_num_rows($resultt);



            if ($no != 0) {
                ?>

                <?php
                // print the districts info in table
                echo '<table border="0" class="data-table">
                      <tr><th>Check</th><th>Sample Request No</th><th>Batch No</th><th>Facility</th><th>Date Received</th><th>Date Tested </th><th>Date Updated </th><th> Result</th><th>Delay {days}</th></tr>';
                $i = 0;
                while (list($ID, $batchno, $patient, $facility, $datereceived, $datetested, $datemodified, $result) = mysql_fetch_array($resultt)) {

                    //get sample facility name based on facility code
                    $facilityname = GetFacility($facility);
                    //resut in words
                    $routcome = GetResultType($result);
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

                    $date_result_updated = date("d-M-Y", strtotime($datemodified));

                    $currentdate = date('d-m-Y'); //get current date

                    $extradays = round((strtotime($currentdate) - strtotime($date_result_updated)) / (60 * 60 * 24));
                    ?>
                    <tr class="even">
                        <td ><div align="center"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $i++; ?>" /></div>  </td>
                        <td ><strong><?php echo $patient; ?></strong><input name="patient[]" type="hidden" id="patient[]" value="<?php echo $patient; ?>" readonly="" size="15" >
                            <input name="labcode[]" type="hidden"  id="labcode[]" value="<?php echo $ID; ?>"  ></td>
                        <td ><div align="center"><?php echo $batchno; ?></div><input name="BatchNo[]" type="hidden" id="BatchNo[]" value="<?php echo $batchno; ?>" readonly="" size="2" ></td>
                        <td ><?php echo $facilityname; ?><input name="facility[]" type="hidden" id="facility[]" value="<?php echo $facilityname; ?>" readonly="" >
                            <input name="facilitycode[]" type="hidden"  id="facilitycode[]" value="<?php echo $facility; ?>"  > </td>
                        <td ><?php echo $date_received; ?><input name="sampledrec[]" type="hidden" id="sampledrec[]" value="<?php echo $date_received; ?>" readonly="" size="11"></td>
                        <td ><?php echo $date_datetested; ?><input name="dateoftest[]" type="hidden" id="dateoftest[]" value="<?php echo $date_datetested; ?>" readonly="" size="11"></td>
                        <td ><?php echo $date_result_updated; ?><input name="dateupdated[]" type="hidden"  id="dateupdated[]"value="<?php echo $date_result_updated; ?>" readonly="" size="11" > </td>
                        <td ><?php echo $routcome; ?><input name="sampleresult[]" type="hidden"  id="sampleresult[]"value="<?php echo $routcome; ?>" readonly="" size="20" > </td>
                        <td ><div align="center"><?php echo $extradays; ?></div><input name="delay[]" type="hidden"  id="delay[]"value="<?php echo $extradays; ?>" readonly="" size="10" > </td>
                        <!--<td > <textarea rows='2' cols='40' name='msg[]'   id='msg[]' > </textarea> --></td>
                    </tr>
                    <?php
                }
                ?>
                <tr  bgcolor="#F0F0F0">
                    <td colspan="12" align="center"><input type="submit" name="Submit" value="Proceed " class="button"></td>
                </tr>
                </table>';

                <?php
                echo '<br>';
                $numrows = GetTotalCompleteBatches(2, $labss); //get total no of batches
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
                <table>
                    <tr>
                        <td style="width:auto">
                            <div class="notice">
                                <?php
                                echo '<strong>' . ' <font color="#666600">' . 'No Batches Ready for Dispatch' . '</strong>' . ' </font>';
                                ?>
                            </div>
                        </td>
                    </tr>
                </table>
                <?php
            }
            ?>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>