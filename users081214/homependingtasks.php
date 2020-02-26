<?php
require_once('../connection/config.php');
$accttype = $_SESSION['accounttype'];
$labss = $_SESSION['lab'];
?>
<table>
    <!--Samples Awaiting Approval -->
    <tr>
        <td>
            <?php
            $approvalqury = "SELECT ID FROM samples
		             WHERE approved = 0 and flag = 1 ";

            $approvalqueryresult = mysql_query($approvalqury) or die(mysql_error());
            $notapproved = mysql_num_rows($approvalqueryresult);

            if ($accttype == 4) {//..lab tech
                $beginlink = '<a href=verifybatcheslist.php title=Print>';
                $endlink = '</a>';
                $divclass = 'error';
            } else {
                $beginlink = '';
                $endlink = '';
                $divclass = 'notice';
            }

            if ($notapproved != 0) {
                ?>
                <div class="<?php echo $divclass; ?>">
                    <?php echo "<strong>$beginlink  Samples  Awaiting Approval [  $notapproved  ]$endlink " . "</strong>"; ?></div>
            </td>
        </tr>
        <?php
    } else {
        ?>
        <?php
        echo 'No Samples Awaiting Approval';
        ?>
    <?php }
    ?>
</td>
</tr>

<!--Samples Awaiting First Approval -->
    <tr>
        <td>
            <?php
            $firstApprovalQuery = "SELECT
                                        ID
                                      FROM
                                        worksheets
                                      WHERE
                                        approvalStatus = 0 AND Flag = 0 AND updatedby IS NOT NULL AND updatedby != ''";

            $approvalResult = mysql_query($firstApprovalQuery) or die(mysql_error());
            $notapproved = mysql_num_rows($approvalResult);

            if ($accttype == 4) {//..lab tech
                $beginlink = '<a href=worksheetlist.php?wtype=4>';
                $endlink = '</a>';
                $divclass = 'error';
            } else {
                $beginlink = '';
                $endlink = '';
                $divclass = 'notice';
            }

            if ($notapproved != 0) {
                ?>
                <div class="<?php echo $divclass; ?>">
                    <?php echo "<strong>$beginlink  Worksheets Awaiting First Approval [  $notapproved  ]$endlink " . "</strong>"; ?></div>
            </td>
        </tr>
        <?php
    } else {
        ?>
        <?php
        echo 'No Worksheets Awaiting First Approval';
        ?>
    <?php }
    ?>
</td>
</tr>

<!--Samples Awaiting Second Approval -->
    <tr>
        <td>
            <?php
            $secondApprovalQuery = "SELECT ID FROM worksheets
		                    WHERE approvalStatus = 1";

            $approvalResult = mysql_query($secondApprovalQuery) or die(mysql_error());
            $notapproved = mysql_num_rows($approvalResult);

            if ($accttype == 4) {//..lab tech
                $beginlink = '<a href=worksheetlist.php?wtype=5>';
                $endlink = '</a>';
                $divclass = 'error';
            } else {
                $beginlink = '';
                $endlink = '';
                $divclass = 'notice';
            }

            if ($notapproved != 0) {
                ?>
                <div class="<?php echo $divclass; ?>">
                    <?php echo "<strong>$beginlink  Worksheets Awaiting Second Approval [  $notapproved  ]$endlink " . "</strong>"; ?></div>
            </td>
        </tr>
        <?php
    } else {
        ?>
        <?php
        echo 'No Worksheets Awaiting Second Approval';
        ?>
    <?php }
    ?>
</td>
</tr>

<!--Samples Pending Testing -->
    <tr>
        <td>
            <?php
            $pendingTestingQuery = "SELECT
                                        *
                                      FROM
                                        samples
                                      WHERE
                                        flag = 1 AND inworksheet = 0 AND result = 0 and receivedstatus = 1;";

            $pendingResult = mysql_query($pendingTestingQuery) or die(mysql_error());
            $pending = mysql_num_rows($pendingResult);

            if ($accttype == 4) {//..lab tech
                $beginlink = '<a href = "worksheets.php">';
                $endlink = '</a>';
                $divclass = 'error';
            } else {
                $beginlink = '';
                $endlink = '';
                $divclass = 'notice';
            }

            if ($pending != 0) {
                ?>
                <div class="<?php echo $divclass; ?>">
                    <?php echo "<strong>$beginlink  Samples Pending Testing (Not in Worksheet) [  $pending  ]$endlink " . "</strong>"; ?></div>
            </td>
        </tr>
        <?php
    } else {
        ?>
        <?php
        echo 'No Worksheets Awaiting Second Approval';
        ?>
    <?php }
    ?>
</td>
</tr>

<!--Samples Awaiting Testing -->
<tr>
    <td>
        <?php
        //if all the samples have been approved then check that they are in a worksheet or if they have been tested
        $qury = "SELECT task_id,task, sample FROM pendingtasks
		 WHERE status = 0 AND task = 1";

        $queryresult = mysql_query($qury) or die(mysql_error());
        $batchesawaitingtest = mysql_num_rows($queryresult);

        if ($batchesawaitingtest != 0) {
            if ($accttype == 4) {//..lab tech
                $beginlink = '<a href=pendingtaskss.php?view=1  title=Print>';
                $endlink = '</a>';
                $divclass = 'error';
            } else {
                $divclass = 'notice';
                $beginlink = '';
                $endlink = '';
            }
            ?>
            <div class="<?php echo $divclass; ?>">
                <?php
                echo "<strong>" . $beginlink . "Samples  Without  Results [  $batchesawaitingtest " . $endlink . " ] " . "</strong>";
                ?>
            </div>
        </td>
    </tr>
    <?php
} else {
    ?>
    <?php
    echo 'No Samples Awaiting Testing';
    ?>
<?php }
?>
</td>
</tr>
<!--Samples Awaiting Dispatch -->
<tr>
    <td>
        <?php
        /* $qury = "SELECT task_id,task,sample
          FROM pendingtasks
          WHERE status=1 AND task=2
          "; */
        $qury = "SELECT ID FROM samples
		 WHERE samples.lab='$labss' AND samples.BatchComplete = 1 and datedispatched = '' and printed = 0 and receivedstatus = 1 and approved = 1 and flag=1";
        $quryresult = mysql_query($qury) or die(mysql_error());
        $noofbatches = mysql_num_rows($quryresult);

        if ($noofbatches != 0) {
            if ($accttype == 1) {//..data clerk
                $beginlink = '<a href=dispatch.php title= View The Batches List>';
                $endlink = '</a>';
                $divclass = 'error';
            } else {
                $divclass = 'notice';
                $beginlink = '';
                $endlink = '';
            }
            ?>
            <div class="<?php echo $divclass; ?>">
                <?php
                echo "<strong>" . $beginlink . "Samples Awaiting Dispatch [  $noofbatches " . $endlink . " ]  " . "</strong>";
                ?>
            </div>

        </td>
    </tr>
    <?php
} else {
    ?>
    <?php
    echo 'No Samples Awaiting Dispatch';
    ?>
<?php }
?></td>
</tr>
<tr>
    <td>
        <?php
        $qury = "SELECT task_id,task,batchno,sample
            FROM pendingtasks
			WHERE status=0 AND task=3
			ORDER BY batchno ASC
			";

        $quryresult = mysql_query($qury) or die(mysql_error());
        $noofsamples = mysql_num_rows($quryresult);

        if ($accttype == 4) {//..lab tech
            $beginlink = '<a href=repeats.php>';
            $endlink = '</a>';
            $divclass = 'error';
        } else {
            $divclass = 'notice';
            $beginlink = '';
            $endlink = '';
        }

        if ($noofsamples != 0) {
            ?>
            <div class="<?php echo $divclass; ?>"><?php
        echo "<strong>$beginlink Samples to be repeated [ $noofsamples ] $endlink </strong>";
            ?>
            </div>

        </td>

    </tr>

    <?php
} else {
    echo 'No Samples to be repeated';
}
?></td>
</tr>
<?php
if ($accttype == 1) {//..data clerk
    ?>
    <tr>
        <td>
            <?php
            $noofsamples = rejectedsamplesforprinting($labss);


            if ($noofsamples != 0) {
                $beginlink = '<a href=dataclerkrejdispatch.php title= View Dispatch Rejected Samples List>';
                $endlink = '</a>';
                $divclass = 'error';
                ?>
                <div class="<?php echo $divclass; ?>"><?php
        echo "<strong>" . $beginlink . "  Rejected Samples Awaiting Printing [ $noofsamples " . $endlink . " ]  " . "</strong>";
                ?>
                </div>

            </td>
        </tr>
        <?php
    } else {
        ?>
        <?php
        echo 'No Rejected Samples Awaiting Printing';
        ?>
        <?php
    }
    ?>
    <?php
} else {//othe account type
    ?>
    <tr><td>
            <?php
            $qury = "SELECT task_id,task,batchno,sample
            FROM pendingtasks
			WHERE status=0 AND task=6
			ORDER BY batchno ASC
			";

            $quryresult = mysql_query($qury) or die(mysql_error());
            $noofsamples = mysql_num_rows($quryresult);


            if ($noofsamples != 0) {
                if ($accttype == 4) {//..labtech
                    $beginlink = '<a href=dispatch_rejected.php title= View Dispatch Rejected Samples List>';
                    $endlink = '</a>';
                    $divclass = 'error';
                } else {
                    $divclass = 'notice';
                    $beginlink = '';
                    $endlink = '';
                }
                ?>
                <div class="<?php echo $divclass; ?>">
                    <?php
                    echo "<strong>" . $beginlink . "  Rejected Samples Awaiting Dispatch [ $noofsamples " . $endlink . " ]  " . "</strong>";
                    ?>
                </div>
            </td>
        </tr>
        <?php
    } else {
        ?>
        <?php
        echo 'No Rejected Samples Awaiting Dispatch';
        ?>
        <?php
    }
}
?>
</td>
</tr>
</table>
