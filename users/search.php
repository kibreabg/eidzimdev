<?php
require_once('../connection/config.php');
include('../includes/header.php');

$searchparameter = ltrim(rtrim($_POST['sample'])); //get the search parameter from the userheader and trim the value
$searchparameterid = ltrim(rtrim($_POST['sampleid'])); //get the search parameter from the userheader and trim the value
?>

    <div>
        <div class="section-title">SEARCH RESULTS</div>
        <div>
            <?php
        if (!($searchparameter == '')) { //display search results if search parameter is NOT NULL         

            //start the search
            
            $showsample = "SELECT ID,LTRIM(RTRIM(patient)),facility,batchno,receivedstatus,rejectedreason,spots,BatchComplete,datecollected,datereceived,datetested,datemodified,datedispatched,result,worksheet,sampleokforretest,repeatt,approved FROM samples WHERE patient = '$searchparameter' AND flag = 1";
            $displaysample = mysql_query($showsample) or die(mysql_error());
            $arrayResult = mysql_fetch_array($displaysample);
            $isRejected = $arrayResult["receivedstatus"];

            $showcount = mysql_num_rows($displaysample); //get the search count


            if ($showcount != 0) { //display table is count is NOT 0
                //show the table
                $samplecount = 0;
                ?>
                <table>
                    <tr>
                        <td style="width:auto">
                            <div class="notice">
                                <?php echo "The search for Sample with Request # <strong>" . LTRIM(RTRIM($searchparameter)) . "</strong> returned " . $showcount . " results.<br/>"; ?>
                            </div>
                    </tr>
                </table>
                <table border="0">
                    <tr class="even">
                        <th width='40'>
                            <a href='javascript:history.back(-1)'><img src='../img/back.gif' alt='Go Back' /></a>
                        </th>
                    </tr>
                </table>
                <table border=0 class='data-table'>
                    <tr>
                        <th><small>Count</small></th>
                        <th><small>Lab # </small></th>
                        <th><small>Request # </small></th>
                        <th><small>Facility</small></th>
                        <th><small>Batch No</small></th>
                        <th><small>Received Status</small></th>
                        <th><small>Date Collected</small></th>
                        <th><small>Date Received</small></th>
                        <th><small>Date Tested</small></th>
                        <th><small>Date Modified</small></th>
                        <th><small>Date Dispatched</small></th>
                        <th><small>Status</small></th>
                        <th><small>Result</small></th>
                        <th><small>Worksheet</small></th>
                        <th><small>Task</small></th>
                        <?php if ($isRejected == 2) { ?>
                        <th><small>Print</small></th>
                    </tr>
                    <?php } else { ?>
                    </tr>
                    <?php }

                mysql_data_seek($displaysample, 0); //To use mysql_fetch_array for the second time
                while (list($ID, $patient, $facility, $batchno, $receivedstatus, $rejectedreason, $spots, $BatchComplete, $datecollected, $datereceived, $datetested, $datemodified, $datedispatched, $result, $worksheet, $sampleokforretest, $repeatt, $approved) = mysql_fetch_array($displaysample)) {
                    $showstatus = GetReceivedStatus($receivedstatus); //display received status
                    $showresult = GetResultType($result); //display the result type
                    $getfacilityname = GetFacility($facility);
                    $samplecount = $samplecount + 1;
                    $datecollected = date("d-M-Y", strtotime($datecollected));
                    $datereceived = date("d-M-Y", strtotime($datereceived));
                    if (($receivedstatus == 2)) {
                        $datetested = "";
                        $datemodified = "";
                        $datedispatched = "";
                        $template = "";
                    } else {
                        $datetested = date("d-M-Y", strtotime($datetested));
                        $datemodified = date("d-M-Y", strtotime($datemodified));
                        $datedispatched = date("d-M-Y", strtotime($datedispatched));
                        $template = "<a href=\"worksheetdetails.php" . "?ID=$worksheet" . "\" title='Click to view Worksheets Details'>$worksheet</a>";
                    }

                    if (($showresult != "") && ($repeatt == 0) && ($BatchComplete == 1)) {
                        $samplestatus = " <strong><font color='#339900'> Complete </font></strong>";
                    } elseif (($showresult == "") && ($repeatt == 1)) {
                        $samplestatus = " <strong><small><font color='#0000FF'> In Process  </small></font></strong>";
                    } elseif (($showresult == "") && ($worksheet > 0)) {
                        $samplestatus = " <strong><small><font color='#0000FF'> In Process  </small></font></strong>";
                    } elseif (($showresult == "") && ($worksheet == 0) && ($approved == 0)) {
                        $samplestatus = "<small><font color='#FF0000'>Awaiting Approval</font></small>";
                    }
                    ?>
                    <tr class="even">
                        <td><small><?php echo $samplecount; ?></small></td>
                        <td><small><?php echo $ID; ?></small></td>
                        <td><a href="sample_details.php?ID=<?php echo $ID; ?>" title="Click to view sample details"><small><?php echo $patient; ?></small></a></td>
                        <td><small><?php echo $getfacilityname; ?></small></td>
                        <td><small><a href="batchdetails.php?ID=<?php echo $batchno; ?>" title="Click to view Batch Details"><?php echo $batchno; ?></a></small></td> <!--Pass the batch no variable to the batch no page-->
                        <td><small><?php echo $showstatus; ?></small></td>
                        <td><small><?php echo $datecollected; ?></small></td>
                        <td><small><?php echo $datereceived; ?></small></td>
                        <td><small><?php echo $datetested; ?></small></td>
                        <td><small><?php echo $datemodified; ?></small></td>
                        <td><small><?php echo $datedispatched; ?></small></td>
                        <td><small><?php echo $samplestatus; ?></small></td>
                        <td><small><?php echo $showresult; ?></small></td>
                        <td><small><?php echo $template; ?></small>
                        </td><!--Pass the worksheet number to the worksheet page-->
                        <td>
                            <?php
                        if (($showresult != "") && ($repeatt == 0) && ($BatchComplete == 1)) {
                            ?>
                                <a href=sample_detailsprint.php?ID=<?php echo $ID; ?> target='_blank'>Print</a>
                                <?php
                        }
                        if ($receivedstatus == 2) {
                            //get sample recevied
                            $srecstatus = GetReceivedStatus($receivedstatus);
                            $rejectedreason = GetRejectedReason($rejectedreason);
                            echo $srecstatus . " - " . $rejectedreason;
                        }
                        ?>

                        </td>
                        <?php
                    if ($receivedstatus == 2) {
                        ?>
                            <td><a href=sample_detailsprint.php?ID=<?php echo $ID; ?> target='_blank'>Print</a></td>
                            <?php } ?>
                    </tr>
                    <?php
                }

                //end show search results
            } else { //display message of count is 0
                ?>
                        <table>
                            <tr>
                                <td style="width:auto">
                                    <div class="error">
                                        <?php echo "The search for <strong>" . LTRIM(RTRIM($searchparameter)) . "</strong> returned " . $showcount . " results.<br/>"; ?>
                                    </div>
                                    </th>
                            </tr>
                        </table>
                        <?php
            }
            ?>
                </table>

                <!--***********************************************************	 -->
                <?php
        } else { //show message if the search parameter is null
            ?>
                    <table>
                        <tr>
                            <td style="width:auto">
                                <div class="error">
                                    <?php echo '<strong>' . ' <font color="#666600">' . 'Please enter a valid record to search' . '</strong>' . ' </font>'; ?>
                                </div>
                                </th>
                        </tr>
                    </table>

                    <?php
        }
        ?>
        </div>
    </div>


    <?php include('../includes/footer.php'); ?>