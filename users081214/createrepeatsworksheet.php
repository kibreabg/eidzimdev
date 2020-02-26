<?php
require_once('classes/tc_calendar.php');
?>
<?php include('../includes/header.php');
$error = $_GET['p'];
?>
<style type="text/css">
    select {
        width: 250;}
    </style>	
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
    <div class="section-title">REPEAT TESTS WORKSHEETS</div>
    <div class="xtop">
        <?php
        if ($error != "") {
            ?> 
            <table>
                <tr>
                    <td style="width:auto" >
                        <div class="error">
    <?php echo '<strong>' . ' <font color="#666600">' . $error . '</strong>' . ' </font>'; ?>
                        </div>
                    </td>
                </tr>
            </table>
<?php } ?>
        <form name="myForm" method="POST" action="repeatworksheet.php"    >

            <?php
            $rowsPerPage = 22; //number of rows to be displayed per page
// by default we show first page
            $pageNum = 1;

// if $_GET['page'] defined, use it as page number
            if (isset($_GET['page'])) {
                $pageNum = $_GET['page'];
            }

// counting the offset
            $offset = ($pageNum - 1) * $rowsPerPage;
//query database for all districts

            $qury = "select ID,patient,datereceived,datetested,spots,batchno,facility,parentid from samples where parentid > 0 AND inrepeatworksheet =0
			ORDER BY datetested ASC
			LIMIT $offset, $rowsPerPage";
            $quryresult = mysql_query($qury) or die(mysql_error());
            $no = mysql_num_rows($quryresult);
            if ($no != 0) {
                ?>
                <tr bgcolor="#CDCCCA">
                    <td height="24" bgcolor="#F0F3FA" colspan="6"><a href="javascript:select(1)">Check all</a> |
                        <a href="javascript:select(0)">Uncheck all</a></td>
                </tr><?php
            echo '<table border="0" class="data-table">
 <tr  ><td>Select</td><td>Lab Code</td><td>Sample Code</td><td>Batch No<td>Facility</td><td>Spots</td><td>Date Tested</td><td>Result</td></tr>';
            $i = 0;
            while (list($ID, $patient, $datereceived, $datetested, $spots, $batchno, $facility, $parentid) = mysql_fetch_array($quryresult)) {
                $datetested = GetSampleDateofTest($parentid);
                $facilityname = GetFacility($facility);
                $outcome = GetSampleResultbasedonparentid($parentid);
                $showresult = GetResultType($outcome);
                    ?>

                    <tr bgcolor="#F0F3FA">
                        <td align="center" ><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $i++; ?>" /><input type="hidden" name="many" value="Email Facilities" /></td>
                        <td align="center"><input name="scode[]" type="text" id="lastname" value="<?php echo $ID; ?>" readonly="" size="7"></td>
                        <td align="center"><input name="pid[]" type="text" id="pid[]" value="<?php echo $patient; ?>" readonly="" /></td>
                        <td align="center"><input name="batch[]" type="text" id="lastname" value="<?php echo $batchno; ?>" readonly="" size="3"></td>
                        <td align="center"><input name="fname[]" type="text" id="name" value="<?php echo $facilityname; ?>" readonly=""  size="35"/></td>
                        <td align="center"><input name="sspot[]" type="text" id="email[]" value="<?php echo $spots; ?>" readonly="" size="3" /></td>
                        <td align="center"><input name="datetested[]" type="text" id="email" value="<?php echo $datetested; ?>" readonly="" size="18"></td>
                        <td align="center"><?php echo $showresult; ?></td>

                    </tr> 
                    <p>
                        <?php
                    }
                    ?>
                <tr  bgcolor="#00526C">
                    <td colspan="8" align="center"><input type="submit" name="Submit" value="Create Worksheet"  class="button"/></td>
                </tr>
    <?php
    echo '</table>';
    echo '<br>';
    ?>



                <?php
                $Accepted = "Accepted";

                //echo "<a href=\"createacct2.php"  . "\">Click to Open another account</a>";
                $numrows = GetTotalRepeatSamples();

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
            echo '<strong>' . 'No Repeat Samples as of now.' . '</strong>';
                ?></div></th>
                    </tr>
                </table>

            <?php }
            ?> </p>

        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>