<?php
session_start();
require_once '../connection/config.php';
require_once 'classes/tc_calendar.php';
include '../includes/header.php';
$success = $_GET['p'];
if (isset($_GET['wtype'])) {
    $_SESSION['wtype'] = $_GET['wtype']; // Use this line or below line if register_global is off
} else {
    $_SESSION['wtype'] = 10;
}
$userID = $_SESSION['uid'];
$searchparameter = ltrim(rtrim($_POST['wsheet'])); //get the search parameter from the userheader and trim the value
$searchparameterid = ltrim(rtrim($_POST['wsheetid'])); //get the search parameter from the userheader and trim the value
$fromfilter = date('Y-m-d', strtotime('01-01-1900'));
$tomorrow = new DateTime('tomorrow');
$tofilter = $tomorrow->format('Y-m-d');
$currentdate = date('Y');
$lowestdate = date('Y', strtotime('01-01-2000'));

?>
<style type="text/css">
    select {
        width: 250;
    }
</style>
<script language="javascript" src="calendar.js"></script>
<link type="text/css" href="calendar.css" rel="stylesheet" />
<script type="text/javascript">
    function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
    }
    $(document).ready(function () {


    });
</script>
<script type="text/javascript" language="javascript">
    function reload(form) {
        var val = form.cat.options[form.cat.options.selectedIndex].value;
        self.location = 'addsample.php?catt=' + val;
    }

    function appendWorksheetParam(wtype) {
        var wType = $("#worksheetType").val();
        var url = window.location.href;
        if (url.indexOf('?') > -1 && url.indexOf('wtype') == -1) {
            url += "&wtype=" + wType;
            window.location.href = url;
        }
    }
</script>

<div class="section">
    <div class="section-title">WORKSHEET LIST</div>
    <div class="xtop">
        <?php
if (isset($_SESSION['wtype'])) {
    include "worksheetslink.php";
}
if ($success != "") {
    ?>
        <table>
            <tr>
                <td style="width:auto">
                    <div class="success">
                        <?php echo '<strong>' . ' <font color="#666600">' . $success . '</strong>' . ' </font>'; ?>
                    </div>
                </td>
            </tr>
        </table>
        <?php }?>

        <!--show the filter calendar--------------------------------------------------->
        <div>
            <form name="filterform" action="">
                <table border="0">
                    <tr>
                        <td>Select Date Range: From</td>
                        <td>
                            <?php
$myCalendar = new tc_calendar("fromfilter", true, false);
$myCalendar->setIcon("../img/iconCalendar.gif");
$myCalendar->setDate(date('d'), date('m'), date('Y'));
$myCalendar->setPath("./");
$myCalendar->setYearInterval($lowestdate, $currentdate);
$myCalendar->setDateFormat('j F Y');
$myCalendar->writeScript();
?>
                        </td>
                        <td>To</td>
                        <td>
                            <?php
$myCalendar = new tc_calendar("tofilter", true, false);
$myCalendar->setIcon("../img/iconCalendar.gif");
$myCalendar->setDate(date('d'), date('m'), date('Y'));
$myCalendar->setPath("./");
$myCalendar->setYearInterval($lowestdate, $currentdate);
$myCalendar->setDateFormat('j F Y');
$myCalendar->writeScript();
?>
                        </td>
                        <td>
                            <input type="submit" name="submitfrom" value="Filter" class="button" /><br />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <!--end show the filter calendar------------------- -->

        <?php

if ($_REQUEST['submitfrom']) {
    //Change the date format
    $fromfilter = date("Y-m-d", strtotime($_GET['fromfilter']));
    if ($fromfilter != date('Y-m-d', strtotime('01-01-1900'))) {
        $_SESSION['fromfilter'] = $fromfilter;
    }
    $tofilter = date("Y-m-d", strtotime($_GET['tofilter']));
    $tmrw = new DateTime('tomorrow');
    if ($tofilter != $tmrw->format('Y-m-d')) {
        $_SESSION['tofilter'] = $tofilter;
    }

}

if (isset($_SESSION['wtype'])) {
    if ($_SESSION['wtype'] == 1) { //complete worksheets
        $rowsPerPage = 15; //number of rows to be displayed per page
        // by default we show first page
        $pageNum = 1;

        if (isset($_GET['page'])) {
            $pageNum = $_GET['page'];
        }

        $offset = ($pageNum - 1) * $rowsPerPage;
        $qury = "SELECT ID,worksheetno,datecreated,HIQCAPNo,spekkitno,createdby,Lotno,Rackno,Flag,daterun,datereviewed,type
                 FROM worksheets
                WHERE Flag = 1 AND STR_TO_DATE(datecreated, '%d-%m-%Y') BETWEEN '$fromfilter' AND '$tofilter'
                ORDER BY ID DESC
                LIMIT $offset, $rowsPerPage";

        $result = mysql_query($qury) or die(mysql_error()); //for main display
        $result2 = mysql_query($qury) or die(mysql_error()); //for calculating samples with results and those without
        $no = mysql_num_rows($result);
        if ($no != 0) {
            ?>
        <table border="0" class="data-table">
            <tr>
                <th><small>Serial #</small></th>
                <th><small>Worksheet No</small></th>
                <th><small>Date Created</small></th>
                <th><small>Created By</small></th>
                <th><small>Type</small></th>
                <th><small>No. of Samples</small></th>
                <th><small>Lot No</small></th>
                <th><small>Date Run</small></th>
                <th><small>Date Reviewed</small></th>
                <th><small>Status</small></th>
                <th><small>Task</small></th>
            </tr>
            <?php
while (list($ID, $worksheetno, $datecreated, $HIQCAPNo, $spekkitno, $createdby, $Lotno, $Rackno, $Flag, $daterun, $datereviewed, $type) = mysql_fetch_array($result)) {

                if ($Flag == 0) {
                    $status = " <strong><small><font color='#0000FF'>In Process</small></font></strong>";
                } else {
                    $status = " <strong><font color='#339900'>Complete</font></strong>";
                }
                $numsamples = GetSamplesPerworksheet($worksheetno);
                if ($type == 0) {
                    //get number of sampels per  worksheet
                    $worksheettype = "<small><font color='#0000FF'>Taqman</font></small>";
                } else {
                    $worksheettype = "<small><font color='#FF0000'>Manual</font></small>";
                }
                if ($daterun != "") {
                    $daterun = date("d-M-Y", strtotime($daterun));
                }
                if ($datereviewed != "") {
                    $datereviewed = date("d-M-Y", strtotime($datereviewed));
                }
                $datecreated = date("d-M-Y", strtotime($datecreated));
                $creator = GetUserFullnames($createdby);

                if ($type == 0) {
                    $d = " <a href=\"completeworksheetDetails.php?ID=$worksheetno&view=1\" title='Click to view Samples in this batch'>View Details</a> | <a href=\"downloadcompleteworksheet.php?ID=$worksheetno\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> ";
                } elseif ($type == 1) {
                    $d = " <a href=\"completemanualworksheetdetails.php?ID=$worksheetno&view=1\" title='Click to view Samples in this batch'>View Details</a> | <a href=\"downloadcompletemanualworksheet.php?ID=$worksheetno\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> ";
                }
                ?>
            <tr class='even'>
                <td>
                    <?php echo $ID; ?>
                </td>
                <td>
                    <?php echo $worksheetno; ?>
                </td>
                <td>
                    <?php echo $datecreated; ?>
                </td>
                <td>
                    <?php echo $creator; ?> </td>
                <td>
                    <?php echo $worksheettype; ?>
                </td>
                <td>
                    <?php echo $numsamples; ?>
                </td>
                <td>
                    <?php echo $Lotno; ?>
                </td>
                <td>
                    <?php echo $daterun; ?>
                </td>
                <td>
                    <?php echo $datereviewed; ?>
                </td>
                <td>
                    <?php echo $status; ?>
                </td>
                <td>
                    <?php echo $d; ?>
                </td>
            </tr>
            <?php }?>
        </table>
        <br />
        <?php
$numrows = Gettotalcompleteworksheets(); //get total no of batches
            // how many pages we have when using paging?
            $maxPage = ceil($numrows / $rowsPerPage);
            // print the link to access each page
            $self = $_SERVER['PHP_SELF'];
            $nav = '';
            for ($page = 1; $page <= $maxPage; $page++) {
                if ($page == $pageNum) {
                    $nav .= " $page "; // no need to create a link to current page
                } else {
                    $nav .= " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">$page</a> ";
                }
            }

            // creating previous and next link
            // plus the link to go straight to
            // the first and last page

            if ($pageNum > 1) {
                $page = $pageNum - 1;
                $prev = " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">[Prev]</a> ";

                $first = " <a href=\"$self?page=1&wtype={$_SESSION['wtype']}\">[First Page]</a> ";
            } else {
                $prev = '&nbsp;'; // we're on page one, don't print previous link
                $first = '&nbsp;'; // nor the first page link
            }

            if ($pageNum < $maxPage) {
                $page = $pageNum + 1;
                $next = " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">[Next]</a> ";
                $last = " <a href=\"$self?page=$maxPage&wtype={$_SESSION['wtype']}\">[Last Page]</a> ";
            } else {
                $next = '&nbsp;'; // we're on the last page, don't print next link
                $last = '&nbsp;'; // nor the last page link
            }

            // print the navigation link
            echo '<center>' . ' Page ' . $first . $prev . $nav . $next . $last . '</center>';
        } else {?>
        <table>
            <tr>
                <td style="width:auto">
                    <div class="notice">
                        <strong>
                            <font color="#666600">No Completed Worksheets</font>
                        </strong>
                    </div>
                </td>
            </tr>
        </table>
        <?php
}
    } elseif ($_SESSION['wtype'] == 0) { //Pending Worksheets
        $rowsPerPage = 15; //number of rows to be displayed per page
        // by default we show first page
        $pageNum = 1;

        // if $_GET['page'] defined, use it as page number
        if (isset($_GET['page'])) {
            $pageNum = $_GET['page'];
            if (isset($_SESSION['fromfilter']) && isset($_SESSION['tofilter'])) {
                $fromfilter = $_SESSION['fromfilter'];
                $tofilter = $_SESSION['tofilter'];
            }

        } else {
            if (!$_REQUEST['submitfrom']) {
                unset($_SESSION['fromfilter']);
                unset($_SESSION['tofilter']);
            }
        }

        //Counting the offset
        $offset = ($pageNum - 1) * $rowsPerPage;
        $query = "SELECT ID,worksheetno,datecreated,HIQCAPNo,spekkitno,createdby,Lotno,Rackno,Flag,daterun,datereviewed,type FROM worksheets
                    WHERE (approvalstatus = 0 or approvalstatus = 1) AND Flag = 0
                    AND STR_TO_DATE(datecreated, '%d-%m-%Y') BETWEEN '$fromfilter' AND '$tofilter'
                    ORDER BY ID DESC
                    LIMIT $offset, $rowsPerPage";

        $queryNum = "SELECT ID,worksheetno,datecreated,HIQCAPNo,spekkitno,createdby,Lotno,Rackno,Flag,daterun,datereviewed,type FROM worksheets
                    WHERE (approvalstatus = 0 or approvalstatus = 1) AND Flag = 0
                    AND STR_TO_DATE(datecreated, '%d-%m-%Y') BETWEEN '$fromfilter' AND '$tofilter'
                    ORDER BY ID DESC";

        $result = mysql_query($query) or die(mysql_error()); //for main display
        $resultNum = mysql_query($queryNum) or die(mysql_error()); //for main display

        $pendingWorksheets = mysql_num_rows($result);
        $pendingWorksheetsNum = mysql_num_rows($resultNum);

        echo "<table><th><div class='notice'>The Batch filter for <strong>" . date("d-M-Y", strtotime($fromfilter)) . " upto " . date("d-M-Y", strtotime($tofilter)) . "</strong> returned <strong>" . $pendingWorksheetsNum . " </strong>results</div></th></table>";

        if ($pendingWorksheetsNum != 0) {
            ?>
        <table border="0" class="data-table">
            <tr>
                <th><small>Serial #</small></th>
                <th><small>Worksheet No</small></th>
                <th><small>Date Created</small></th>
                <th><small>Created By</small></th>
                <th><small>Type</small></th>
                <th><small>No. of Samples</small></th>
                <th><small>Lot No</small></th>
                <th><small>Date Run</small></th>
                <th><small>Date Reviewed</small></th>
                <th><small>Status</small></th>
                <th><small>Task</small></th>
            </tr>
            <?php
while (list($ID, $worksheetno, $datecreated, $HIQCAPNo, $spekkitno, $createdby, $Lotno, $Rackno, $Flag, $daterun, $datereviewed, $type) = mysql_fetch_array($result)) {

                //get number of sampels per  worksheet
                $numsamples = GetSamplesPerworksheet($worksheetno);

                if ($daterun != "") {
                    $daterun = date("d-M-Y", strtotime($daterun));
                }
                if ($datereviewed != "") {
                    $datereviewed = date("d-M-Y", strtotime($datereviewed));
                }
                $datecreated = date("d-M-Y", strtotime($datecreated));
                $creator = GetUserFullnames($createdby);

                $wsheet = "General worksheet";

                //if - Taqman worksheets else - Manual worksheets
                if ($type == 0) {
                    $d2 = "<a href=\"worksheetDetails.php" . "?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadworksheet.php" . "?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" . "?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $worksheetno');\" >Delete Worksheet  </a>";
                    $wSheet = getWorksheetDetails($worksheetno);
                    if ($wSheet['approvalStatus'] == 1) {
                        if ($userID != $wSheet['firstApprovalBy']) {
                            $dl = "| <a href=\"confirmresults.php" . "?q=$worksheetno" . "\" title='Click to Confirm Results For Worksheet' > Confirm Results </a>";
                        } else {
                            $dl = "";
                        }

                    } else {
                        $dl = "| <a href=\"updateresults.php" . "?ID=$worksheetno" . "\" title='Click to Update Results Worksheet' > Update Results </a>";
                    }
                    $worksheettype = "<small><font color='#0000FF'>Taqman</font></small>";
                } else {
                    $d2 = "<a href=\"manualworksheetDetails.php" . "?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadmanualworksheet.php" . "?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" . "?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $ID');\" >Delete Worksheet  </a>";
                    $wSheet = getWorksheetDetails($worksheetno);
                    if ($wSheet['approvalStatus'] == 1) {
                        if ($userID != $wSheet['firstApprovalBy']) {
                            $dl = "| <a href=\"confirmresults.php" . "?q=$worksheetno" . "\" title='Click to Confirm Results For Worksheet' > Confirm Results </a>";
                        } else {
                            $dl = "";
                        }

                    } else {
                        $dl = "| <a href=\"updatemanualresults.php" . "?view=1&ID=$worksheetno" . "\" title='Click to Update Results Worksheet' > Update Results </a>";
                    }
                    $worksheettype = "<small><font color='#FF0000'>Manual</font></small>";
                }

                if ($Flag == 0) {
                    $status = " <strong><small><font color='#0000FF'> In Process  </small></font></strong>";
                } else {
                    $status = " <strong><font color='#339900'> Complete </font></strong>";
                }
                ?>
            <tr class='even'>
                <td>
                    <?php echo $ID; ?>
                </td>
                <td>
                    <a href="worksheetDetails.php?ID=<?php echo $worksheetno; ?>&view=1" title='Click to view  Samples in this batch'>
                        <?php echo $worksheetno; ?>
                    </a>
                </td>
                <td>
                    <?php echo $datecreated; ?>
                </td>
                <td>
                    <?php echo $creator; ?>
                </td>
                <td>
                    <?php echo $worksheettype; ?>
                </td>
                <td>
                    <?php echo $numsamples; ?>
                </td>
                <td>
                    <?php echo $Lotno; ?>
                </td>
                <td>
                    <?php echo $daterun; ?>
                </td>
                <td>
                    <?php echo $datereviewed; ?>
                </td>
                <td>
                    <?php echo $status; ?>
                </td>
                <td>
                    <?php echo $d2 . ' ' . $dl; ?>
                </td>
            </tr>
            <?php }?>
        </table>
        <br />
        <?php
$numrows = $pendingWorksheetsNum; //GettotalPendingworksheets(); //get total no of batches
            // how many pages we have when using paging?
            $maxPage = ceil($numrows / $rowsPerPage);

            // print the link to access each page
            $self = $_SERVER['PHP_SELF'];
            $nav = '';
            for ($page = 1; $page <= $maxPage; $page++) {
                if ($page == $pageNum) {
                    $nav .= " $page "; // no need to create a link to current page
                } else {
                    $nav .= " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">$page</a> ";
                }
            }

            // creating previous and next link
            // plus the link to go straight to
            // the first and last page

            if ($pageNum > 1) {
                $page = $pageNum - 1;
                $prev = " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">[Prev]</a> ";

                $first = " <a href=\"$self?page=1&wtype={$_SESSION['wtype']}\">[First Page]</a> ";
            } else {
                $prev = '&nbsp;'; // we're on page one, don't print previous link
                $first = '&nbsp;'; // nor the first page link
            }

            if ($pageNum < $maxPage) {
                $page = $pageNum + 1;
                $next = " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">[Next]</a> ";

                $last = " <a href=\"$self?page=$maxPage&wtype={$_SESSION['wtype']}\">[Last Page]</a> ";
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
                        <strong>
                            <font color="#666600">No Pending Worksheets</font>
                        </strong>
                    </div>
                </td>
            </tr>
        </table>
        <?php
} //end if number pending worksheet
    } //end if wtype=0
    elseif ($_SESSION['wtype'] == 2) { //manual worksheets
        $rowsPerPage = 15; //number of rows to be displayed per page
        // by default we show first page
        $pageNum = 1;
        if (isset($_GET['page'])) {
            $pageNum = $_GET['page'];
        }

        $offset = ($pageNum - 1) * $rowsPerPage;

        $qury = "SELECT ID,worksheetno,datecreated,HIQCAPNo,spekkitno,createdby,Lotno,Rackno,Flag,daterun,datereviewed,type
                FROM worksheets where type='1'
                AND STR_TO_DATE(datecreated, '%d-%m-%Y') BETWEEN '$fromfilter' AND '$tofilter'
                ORDER BY ID DESC
                LIMIT $offset, $rowsPerPage";

        $result = mysql_query($qury) or die(mysql_error()); //for main display
        $result2 = mysql_query($qury) or die(mysql_error()); //for calculating samples with results and those without

        $no = mysql_num_rows($result);

        if ($no != 0) {
            ?>
        <table border="0" class="data-table">
            <tr>
                <th><small>Serial #</small></th>
                <th><small>Worksheet No</small></th>
                <th><small>Date Created</small></th>
                <th><small>Created By</small></th>
                <th><small>Type</small></th>
                <th><small>No. of Samples</small></th>
                <th><small>Lot No</small></th>
                <th><small>Date Run</small></th>
                <th><small>Date Reviewed</small></th>
                <th><small>Status</small></th>
                <th><small>Task</small></th>
            </tr>
            <?php
while (list($ID, $worksheetno, $datecreated, $HIQCAPNo, $spekkitno, $createdby, $Lotno, $Rackno, $Flag, $daterun, $datereviewed, $type) = mysql_fetch_array($result)) {

                //get number of sampels per  worksheet
                $numsamples = GetSamplesPerworksheet($worksheetno);

                if ($daterun != "") {
                    $daterun = date("d-M-Y", strtotime($daterun));
                }
                if ($datereviewed != "") {
                    $datereviewed = date("d-M-Y", strtotime($datereviewed));
                }
                $datecreated = date("d-M-Y", strtotime($datecreated));
                $creator = GetUserFullnames($createdby);

                if ($Flag == 0) {
                    $status = " <strong><small><font color='#0000FF'> In Process  </small></font></strong>";
                    if ($type == 0) {
                        $d = "<a href=\"worksheetDetails.php?ID=$worksheetno&view=1\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadworksheet.php" . "?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" . "?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $worksheetno');\" >Delete Worksheet  </a> | <a href=\"updateresults.php" . "?ID=$worksheetno" . "\" title='Click to Update Results Worksheet' > Update Results </a>";

                        $worksheettype = "<small><font color='#0000FF'>Taqman</font></small>";
                    } else if ($type == 1) {
                        $d = "<a href=\"manualworksheetDetails.php" . "?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadmanualworksheet.php" . "?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" . "?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $ID');\" >Delete Worksheet  </a> | <a href=\"updatemanualresults.php" . "?view=1&ID=$worksheetno" . "\" title='Click to Update Results Worksheet' > Update Results </a>";
                        $worksheettype = "<small><font color='#FF0000'>Manual</font></small>";
                    }
                } else {
                    $status = " <strong><font color='#339900'> Complete </font></strong>";
                    if ($type == 0) {
                        $worksheettype = "<small><font color='#0000FF'>Taqman</font></small>";
                    } else if ($type == 1) {
                        $worksheettype = "<small><font color='#FF0000'>Manual</font></small>";
                    }
                    $d = " <a href=\"completeworksheetDetails.php" . "?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this batch'>View Details</a> | <a href=\"downloadcompleteworksheet.php" . "?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> ";
                }
                ?>
            <tr class='even'>
                <td>
                    <?php echo $ID; ?>
                </td>
                <td>
                    <a href=\ "worksheetDetails.php" . "?ID=$worksheetno" . "\"
                        title='Click to view  Samples in this batch'>
                        <?php echo $worksheetno; ?>
                    </a>
                </td>
                <td>
                    <?php echo $datecreated; ?>
                </td>
                <td>
                    <?php echo $creator; ?>
                </td>
                <td>
                    <?php echo $worksheettype; ?>
                </td>
                <td>
                    <?php echo $numsamples; ?>
                </td>
                <td>
                    <?php echo $Lotno; ?>
                </td>
                <td>
                    <?php echo $daterun; ?>
                </td>
                <td>
                    <?php echo $datereviewed; ?>
                </td>
                <td>
                    <?php echo $status; ?>
                </td>
                <td>
                    <?php echo $d; ?>
                </td>
            </tr>
            <?php }?>
        </table>
        <br />
        <?php
$numrows = Gettotalworksheetsbytype(1); //get total no of batches
            // how many pages we have when using paging?
            $maxPage = ceil($numrows / $rowsPerPage);

            // print the link to access each page
            $self = $_SERVER['PHP_SELF'];
            $nav = '';
            for ($page = 1; $page <= $maxPage; $page++) {
                if ($page == $pageNum) {
                    $nav .= " $page "; // no need to create a link to current page
                } else {
                    $nav .= " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">$page</a> ";
                }
            }

            // creating previous and next link
            // plus the link to go straight to
            // the first and last page

            if ($pageNum > 1) {
                $page = $pageNum - 1;
                $prev = " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">[Prev]</a> ";

                $first = " <a href=\"$self?page=1&wtype={$_SESSION['wtype']}\">[First Page]</a> ";
            } else {
                $prev = '&nbsp;'; // we're on page one, don't print previous link
                $first = '&nbsp;'; // nor the first page link
            }

            if ($pageNum < $maxPage) {
                $page = $pageNum + 1;
                $next = " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">[Next]</a> ";

                $last = " <a href=\"$self?page=$maxPage&wtype={$_SESSION['wtype']}\">[Last Page]</a> ";
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
                        <strong>
                            <font color="#666600">No Manual Worksheets Created</font>
                        </strong>
                    </div>
                </td>
            </tr>
        </table>
        <?php
}
    } elseif ($_SESSION['wtype'] == 3) { //taqman worksheets
        $rowsPerPage = 15; //number of rows to be displayed per page
        // by default we show first page
        $pageNum = 1;

        if (isset($_GET['page'])) {
            $pageNum = $_GET['page'];
        }

        $offset = ($pageNum - 1) * $rowsPerPage;
        $qury = "SELECT ID,worksheetno,datecreated,HIQCAPNo,spekkitno,createdby,Lotno,Rackno,Flag,daterun,datereviewed,type
                FROM worksheets where type='0'
                AND STR_TO_DATE(datecreated, '%d-%m-%Y') BETWEEN '$fromfilter' AND '$tofilter'
                ORDER BY ID DESC
                LIMIT $offset, $rowsPerPage";

        $result = mysql_query($qury) or die(mysql_error()); //for main display
        $result2 = mysql_query($qury) or die(mysql_error()); //for calculating samples with results and those without

        $no = mysql_num_rows($result);

        if ($no != 0) {
            ?>
        <table border="0" class="data-table">
            <tr>
                <th><small>Serial #</small></th>
                <th><small>Worksheet No</small></th>
                <th><small>Date Created</small></th>
                <th><small>Created By</small></th>
                <th><small>Type</small></th>
                <th><small>No. of Samples</small></th>
                <th><small>Lot No</small></th>
                <th><small>Date Run</small></th>
                <th><small>Date Reviewed</small></th>
                <th><small>Status</small></th>
                <th><small>Task</small></th>
            </tr>
            <?php
while (list($ID, $worksheetno, $datecreated, $HIQCAPNo, $spekkitno, $createdby, $Lotno, $Rackno, $Flag, $daterun, $datereviewed, $type) = mysql_fetch_array($result)) {

                //get number of sampels per  worksheet
                $numsamples = GetSamplesPerworksheet($worksheetno);

                if ($daterun != "") {
                    $daterun = date("d-M-Y", strtotime($daterun));
                }
                if ($datereviewed != "") {
                    $datereviewed = date("d-M-Y", strtotime($datereviewed));
                }
                $datecreated = date("d-M-Y", strtotime($datecreated));
                $creator = GetUserFullnames($createdby);

                if ($Flag == 0) {
                    $status = " <strong><small><font color='#0000FF'> In Process  </small></font></strong>";
                    if ($type == 0) {
                        $d = "<a href=\"worksheetDetails.php" . "?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadworksheet.php" . "?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" . "?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $worksheetno');\" >Delete Worksheet  </a> | <a href=\"updateresults.php" . "?ID=$worksheetno" . "\" title='Click to Update Results Worksheet' > Update Results </a>";

                        $worksheettype = "<small><font color='#0000FF'>Taqman</font></small>";
                    } else if ($type == 1) {
                        $d = "<a href=\"manualworksheetDetails.php" . "?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadmanualworksheet.php" . "?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" . "?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $ID');\" >Delete Worksheet  </a> | <a href=\"updatemanualresults.php" . "?view=1&ID=$worksheetno" . "\" title='Click to Update Results Worksheet' > Update Results </a>";
                        $worksheettype = "<small><font color='#FF0000'>Manual</font></small>";
                    }
                } else {
                    $status = " <strong><font color='#339900'> Complete </font></strong>";
                    if ($type == 0) {
                        $worksheettype = "<small><font color='#0000FF'>Taqman</font></small>";
                    } else if ($type == 1) {
                        $worksheettype = "<small><font color='#FF0000'>Manual</font></small>";
                    }
                    if ($type == 0) {
                        $d = " <a href=\"completeworksheetDetails.php" . "?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this batch'>View Details</a> | <a href=\"downloadcompleteworksheet.php" . "?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> ";
                    } elseif ($type == 1) {
                        $d = " <a href=\"completemanualworksheetdetails.php" . "?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this batch'>View Details</a> | <a href=\"downloadcompletemanualworksheet.php" . "?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> ";
                    }
                }?>
            <tr class='even'>
                <td>
                    <?php echo $ID; ?>
                </td>
                <td>
                    <a href=\ "worksheetDetails.php" . "?ID=$worksheetno" . "\"
                        title='Click to view  Samples in this batch'>
                        <?php echo $worksheetno; ?>
                    </a>
                </td>
                <td>
                    <?php echo $datecreated; ?>
                </td>
                <td>
                    <?php echo $creator; ?>
                </td>
                <td>
                    <?php echo $worksheettype; ?>
                </td>
                <td>
                    <?php echo $numsamples; ?>
                </td>
                <td>
                    <?php echo $Lotno; ?>
                </td>
                <td>
                    <?php echo $daterun; ?>
                </td>
                <td>
                    <?php echo $datereviewed; ?>
                </td>
                <td>
                    <?php echo $status; ?>
                </td>
                <td>
                    <?php echo $d; ?>
                </td>
            </tr>
            <?php }?>
        </table>
        <br />
        <?php
$numrows = Gettotalworksheetsbytype(0); //get total no of batches
            // how many pages we have when using paging?
            $maxPage = ceil($numrows / $rowsPerPage);

            // print the link to access each page
            $self = $_SERVER['PHP_SELF'];
            $nav = '';
            for ($page = 1; $page <= $maxPage; $page++) {
                if ($page == $pageNum) {
                    $nav .= " $page "; // no need to create a link to current page
                } else {
                    $nav .= " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">$page</a> ";
                }
            }

            // creating previous and next link
            // plus the link to go straight to
            // the first and last page

            if ($pageNum > 1) {
                $page = $pageNum - 1;
                $prev = " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">[Prev]</a> ";

                $first = " <a href=\"$self?page=1&wtype={$_SESSION['wtype']}\">[First Page]</a> ";
            } else {
                $prev = '&nbsp;'; // we're on page one, don't print previous link
                $first = '&nbsp;'; // nor the first page link
            }

            if ($pageNum < $maxPage) {
                $page = $pageNum + 1;
                $next = " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">[Next]</a> ";

                $last = " <a href=\"$self?page=$maxPage&wtype={$_SESSION['wtype']}\">[Last Page]</a> ";
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
                        <strong>
                            <font color="#666600">No Taqman Worksheets Created</font>
                        </strong>
                    </div>
                </td>
            </tr>
        </table>
        <?php
}
    } elseif ($_SESSION['wtype'] == 4) { //worksheets awating first approval
        $rowsPerPage = 15; //number of rows to be displayed per page
        // by default we show first page
        $pageNum = 1;

        // if $_GET['page'] defined, use it as page number
        if (isset($_GET['page'])) {
            $pageNum = $_GET['page'];
        }

        // counting the offset
        $offset = ($pageNum - 1) * $rowsPerPage;

        $qury = "SELECT ID,worksheetno,datecreated,HIQCAPNo,spekkitno,createdby,Lotno,Rackno,Flag,daterun,datereviewed,type FROM worksheets
                WHERE approvalstatus = 0 AND Flag = 0 AND updatedby IS NOT NULL AND updatedby != ''
                AND STR_TO_DATE(datecreated, '%d-%m-%Y') BETWEEN '$fromfilter' AND '$tofilter'
                ORDER BY ID DESC
                LIMIT $offset, $rowsPerPage";
        //Where Flag = 1 (Remember)

        $result = mysql_query($qury) or die(mysql_error()); //for main display
        $result2 = mysql_query($qury) or die(mysql_error()); //for calculating samples with results and those without

        $no = mysql_num_rows($result);

        if ($no != 0) {
            ?>
        <table border="0" class="data-table">
            <tr>
                <th><small>Serial #</small></th>
                <th><small>Worksheet No</small></th>
                <th><small>Date Created</small></th>
                <th><small>Created By</small></th>
                <th><small>Type</small></th>
                <th><small>No. of Samples</small></th>
                <th><small>Lot No</small></th>
                <th><small>Date Run</small></th>
                <th><small>Date Reviewed</small></th>
                <th><small>Status</small></th>
                <th><small>Task</small></th>
            </tr>
            <?php
while (list($ID, $worksheetno, $datecreated, $HIQCAPNo, $spekkitno, $createdby, $Lotno, $Rackno, $Flag, $daterun, $datereviewed, $type) = mysql_fetch_array($result)) {

                //get number of sampels per  worksheet
                $numsamples = GetSamplesPerworksheet($worksheetno);

                if ($daterun != "") {
                    $daterun = date("d-M-Y", strtotime($daterun));
                }
                if ($datereviewed != "") {
                    $datereviewed = date("d-M-Y", strtotime($datereviewed));
                }
                $datecreated = date("d-M-Y", strtotime($datecreated));
                $creator = GetUserFullnames($createdby);

                $wsheet = "General worksheet";

                //if - Taqman worksheets else - Manual worksheets
                if ($type == 0) {
                    $d2 = "<a href=\"worksheetDetails.php" . "?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadworksheet.php" . "?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" . "?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $worksheetno');\" >Delete Worksheet  </a>";
                    $wSheet = getWorksheetDetails($worksheetno);
                    if ($wSheet['approvalStatus'] == 1) {
                        if ($userID != $wSheet['firstApprovalBy']) {
                            $dl = "| <a href=\"confirmresults.php" . "?q=$worksheetno" . "\" title='Click to Confirm Results For Worksheet' > Confirm Results </a>";
                        } else {
                            $dl = "";
                        }

                    } else {
                        $dl = "| <a href=\"confirmresults.php?q={$worksheetno}" . "\" title='Click to Update Results Worksheet' > Approve Results </a>";
                    }
                    $worksheettype = "<small><font color='#0000FF'>Taqman</font></small>";
                } else {
                    $d2 = "<a href=\"manualworksheetDetails.php" . "?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadmanualworksheet.php" . "?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" . "?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $ID');\" >Delete Worksheet  </a>";
                    $wSheet = getWorksheetDetails($worksheetno);
                    if ($wSheet['approvalStatus'] == 1) {
                        if ($userID != $wSheet['firstApprovalBy']) {
                            $dl = "| <a href=\"confirmresults.php" . "?q=$worksheetno" . "\" title='Click to Confirm Results For Worksheet' > Confirm Results </a>";
                        } else {
                            $dl = "";
                        }

                    } else {
                        $dl = "| <a href=\"confirmmanualresults.php?q={$worksheetno}" . "\" title='Click to Update Results Worksheet' > Approve Results </a>";
                    }
                    $worksheettype = "<small><font color='#FF0000'>Manual</font></small>";
                }

                if ($Flag == 0) {
                    $status = " <strong><small><font color='#0000FF'> In Process  </small></font></strong>";
                } else {
                    $status = " <strong><font color='#339900'> Complete </font></strong>";
                }?>
            <tr class='even'>
                <td>
                    <?php echo $ID; ?>
                </td>
                <td>
                    <a href=\ "worksheetDetails.php" . "?ID=$worksheetno&view=1" . "\"
                        title='Click to view  Samples in this batch'>
                        <?php echo $worksheetno ?>
                    </a>
                </td>
                <td>
                    <?php echo $datecreated; ?>
                </td>
                <td>
                    <?php echo $creator ?>
                </td>
                <td>
                    <?php echo $worksheettype ?>
                </td>
                <td>
                    <?php echo $numsamples ?>
                </td>
                <td>
                    <?php echo $Lotno ?>
                </td>
                <td>
                    <?php echo $daterun ?>
                </td>
                <td>
                    <?php echo $datereviewed ?>
                </td>
                <td>
                    <?php echo $status ?>
                </td>
                <td>
                    <?php echo $d2 . ' ' . $dl ?>
                </td>
            </tr>
            <?php }?>
        </table>
        <br />
        <?php
$numrows = GettotalPendingworksheets(); //get total no of batches
            // how many pages we have when using paging?
            $maxPage = ceil($numrows / $rowsPerPage);

            // print the link to access each page
            $self = $_SERVER['PHP_SELF'];
            $nav = '';
            for ($page = 1; $page <= $maxPage; $page++) {
                if ($page == $pageNum) {
                    $nav .= " $page "; // no need to create a link to current page
                } else {
                    $nav .= " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">$page</a> ";
                }
            }

            // creating previous and next link
            // plus the link to go straight to
            // the first and last page

            if ($pageNum > 1) {
                $page = $pageNum - 1;
                $prev = " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">[Prev]</a> ";

                $first = " <a href=\"$self?page=1&wtype={$_SESSION['wtype']}\">[First Page]</a> ";
            } else {
                $prev = '&nbsp;'; // we're on page one, don't print previous link
                $first = '&nbsp;'; // nor the first page link
            }

            if ($pageNum < $maxPage) {
                $page = $pageNum + 1;
                $next = " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">[Next]</a> ";

                $last = " <a href=\"$self?page=$maxPage&wtype={$_SESSION['wtype']}\">[Last Page]</a> ";
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
                        <strong>
                            <font color="#666600">No Pending Worksheets</font>
                        </strong>
                    </div>
                </td>
            </tr>
        </table>
        <?php
} //end if number pending worksheet
    } elseif ($_SESSION['wtype'] == 5) { //worksheets awaiting second approval
        $rowsPerPage = 15; //number of rows to be displayed per page
        // by default we show first page
        $pageNum = 1;

        if (isset($_GET['page'])) {
            $pageNum = $_GET['page'];
        }

        $offset = ($pageNum - 1) * $rowsPerPage;
        $qury = "SELECT ID,worksheetno,datecreated,HIQCAPNo,spekkitno,createdby,Lotno,Rackno,Flag,daterun,datereviewed,type FROM worksheets
                WHERE approvalstatus = 1 AND Flag = 0
                AND STR_TO_DATE(datecreated, '%d-%m-%Y') BETWEEN '$fromfilter' AND '$tofilter'
                ORDER BY ID DESC
                LIMIT $offset, $rowsPerPage";

        $result = mysql_query($qury) or die(mysql_error()); //for main display
        $result2 = mysql_query($qury) or die(mysql_error()); //for calculating samples with results and those without

        $no = mysql_num_rows($result);

        if ($no != 0) {?>

        <table border="0" class="data-table">
            <tr>
                <th><small>Serial #</small></th>
                <th><small>Worksheet No</small></th>
                <th><small>Date Created</small></th>
                <th><small>Created By</small></th>
                <th><small>Type</small></th>
                <th><small>No. of Samples</small></th>
                <th><small>Lot No</small></th>
                <th><small>Date Run</small></th>
                <th><small>Date Reviewed</small></th>
                <th><small>Status</small></th>
                <th><small>Task</small></th>
            </tr>
            <?php
while (list($ID, $worksheetno, $datecreated, $HIQCAPNo, $spekkitno, $createdby, $Lotno, $Rackno, $Flag, $daterun, $datereviewed, $type) = mysql_fetch_array($result)) {

            //get number of sampels per  worksheet
            $numsamples = GetSamplesPerworksheet($worksheetno);

            if ($daterun != "") {
                $daterun = date("d-M-Y", strtotime($daterun));
            }
            if ($datereviewed != "") {
                $datereviewed = date("d-M-Y", strtotime($datereviewed));
            }
            $datecreated = date("d-M-Y", strtotime($datecreated));
            $creator = GetUserFullnames($createdby);

            $wsheet = "General worksheet";

            //if - Taqman worksheets else - Manual worksheets
            if ($type == 0) {
                $d2 = "<a href=\"worksheetDetails.php" . "?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadworksheet.php" . "?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" . "?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $worksheetno');\" >Delete Worksheet  </a>";
                $wSheet = getWorksheetDetails($worksheetno);
                if ($wSheet['approvalStatus'] == 1) {
                    if ($userID != $wSheet['firstApprovalBy']) {
                        $dl = "| <a href=\"confirmresults.php" . "?q=$worksheetno" . "\" title='Click to Confirm Results For Worksheet' > Confirm Results </a>";
                    } else {
                        $dl = "";
                    }

                } else {
                    $dl = "| <a href=\"updateresults.php" . "?ID=$worksheetno" . "\" title='Click to Update Results Worksheet' > Update Results </a>";
                }
                $worksheettype = "<small><font color='#0000FF'>Taqman</font></small>";
            } else {
                $d2 = "<a href=\"manualworksheetDetails.php" . "?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadmanualworksheet.php" . "?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" . "?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $ID');\" >Delete Worksheet  </a>";
                $wSheet = getWorksheetDetails($worksheetno);
                if ($wSheet['approvalStatus'] == 1) {
                    if ($userID != $wSheet['firstApprovalBy']) {
                        $dl = "| <a href=\"confirmresults.php" . "?q=$worksheetno" . "\" title='Click to Confirm Results For Worksheet' > Confirm Results </a>";
                    } else {
                        $dl = "";
                    }

                } else {
                    $dl = "| <a href=\"updatemanualresults.php" . "?view=1&ID=$worksheetno" . "\" title='Click to Update Results Worksheet' > Update Results </a>";
                }
                $worksheettype = "<small><font color='#FF0000'>Manual</font></small>";
            }

            if ($Flag == 0) {
                $status = " <strong><small><font color='#0000FF'> In Process  </small></font></strong>";
            } else {
                $status = " <strong><font color='#339900'> Complete </font></strong>";
            }?>

            <tr class='even'>
                <td>
                    <?php echo $ID; ?>
                </td>
                <td>
                    <a href=\ "worksheetDetails.php" . "?ID=$worksheetno&view=1" . "\"
                        title='Click to view  Samples in this batch'>
                        <?php echo $worksheetno; ?>
                    </a>
                </td>
                <td>
                    <?php echo $datecreated; ?>
                </td>
                <td>
                    <?php echo $creator; ?>
                </td>
                <td>
                    <?php echo $worksheettype; ?>
                </td>
                <td>
                    <?php echo $numsamples; ?>
                </td>
                <td>
                    <?php echo $Lotno; ?>
                </td>
                <td>
                    <?php echo $daterun; ?>
                </td>
                <td>
                    <?php echo $datereviewed; ?>
                </td>
                <td>
                    <?php echo $status; ?>
                </td>
                <td>
                    <?php echo $d2 . ' ' . $dl; ?>
                </td>
            </tr>
            <?php }?>
        </table>
        <br />
        <?php
$numrows = GettotalPendingworksheets(); //get total no of batches
            // how many pages we have when using paging?
            $maxPage = ceil($numrows / $rowsPerPage);

            // print the link to access each page
            $self = $_SERVER['PHP_SELF'];
            $nav = '';
            for ($page = 1; $page <= $maxPage; $page++) {
                if ($page == $pageNum) {
                    $nav .= " $page "; // no need to create a link to current page
                } else {
                    $nav .= " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">$page</a> ";
                }
            }

            // creating previous and next link
            // plus the link to go straight to
            // the first and last page

            if ($pageNum > 1) {
                $page = $pageNum - 1;
                $prev = " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">[Prev]</a> ";

                $first = " <a href=\"$self?page=1&wtype={$_SESSION['wtype']}\">[First Page]</a> ";
            } else {
                $prev = '&nbsp;'; // we're on page one, don't print previous link
                $first = '&nbsp;'; // nor the first page link
            }

            if ($pageNum < $maxPage) {
                $page = $pageNum + 1;
                $next = " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">[Next]</a> ";

                $last = " <a href=\"$self?page=$maxPage&wtype={$_SESSION['wtype']}\">[Last Page]</a> ";
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
                        <strong>
                            <font color="#666600">No Pending Worksheets</font>
                        </strong>
                    </div>
                </td>
            </tr>
        </table>
        <?php
} //end if number pending worksheet
    } //end if wtype=4
    else {
        $rowsPerPage = 15; //number of rows to be displayed per page
        // by default we show first page
        $pageNum = 1;

        // if $_GET['page'] defined, use it as page number
        if (isset($_GET['page'])) {
            $pageNum = $_GET['page'];
        }

        $offset = ($pageNum - 1) * $rowsPerPage;
        if ($searchparameterid > 0) { //seach worksheets
            $qury = "SELECT ID,worksheetno,datecreated,HIQCAPNo,spekkitno,createdby,Lotno,Rackno,Flag,daterun,datereviewed,type
            FROM worksheets
            WHERE ID='$searchparameterid'
			ORDER BY ID DESC";

            $result = mysql_query($qury) or die(mysql_error()); //for main display
        } else {

            $qury = "SELECT ID,worksheetno,datecreated,HIQCAPNo,spekkitno,createdby,Lotno,Rackno,Flag,daterun,datereviewed,type
            FROM worksheets
            WHERE STR_TO_DATE(datecreated, '%d-%m-%Y') BETWEEN '$fromfilter' AND '$tofilter'
			ORDER BY ID DESC
            LIMIT $offset, $rowsPerPage";

            $queryNum = "SELECT ID,worksheetno,datecreated,HIQCAPNo,spekkitno,createdby,Lotno,Rackno,Flag,daterun,datereviewed,type
            FROM worksheets
            WHERE STR_TO_DATE(datecreated, '%d-%m-%Y') BETWEEN '$fromfilter' AND '$tofilter'
			ORDER BY ID DESC";

            $result = mysql_query($qury) or die(mysql_error()); //for main display
            $resultNum = mysql_query($queryNum) or die(mysql_error()); //for calculating samples with results and those without
        }
        $no = mysql_num_rows($result);
        $filterNum = mysql_num_rows($resultNum);

        if ($filterNum != '') {
            echo "<table><th><div class='notice'>The Batch filter for <strong>" . date("d-M-Y", strtotime($fromfilter)) . " upto " . date("d-M-Y", strtotime($tofilter)) . "</strong> returned <strong>" . $filterNum . " </strong>results</div></th></table>";
        }

        if ($searchparameterid > 0) { //seach worksheets
            ?>
        <table>
            <tr>
                <td style="width:auto">
                    <div class="notice">
                        The search for Worksheet with Serial #
                        <strong><?php echo LTRIM(RTRIM($searchparameterid)); ?></strong> returned <?php echo $no; ?> results.<br />
                    </div>
                </td>
            </tr>
        </table>
        <?php }
        if ($no != 0) {
            ?>
        <table border="0" class="data-table">
            <tr>
                <th><small>Serial #</small></th>
                <th><small>Worksheet No</small></th>
                <th><small>Date Created</small></th>
                <th><small>Created By</small></th>
                <th><small>Type</small></th>
                <th><small>No. of Samples</small></th>
                <th><small>Lot No</small></th>
                <th><small>Date Run</small></th>
                <th><small>Date Reviewed</small></th>
                <th><small>Status</small></th>
                <th><small>Task</small></th>
            </tr>
            <?php
while (list($ID, $worksheetno, $datecreated, $HIQCAPNo, $spekkitno, $createdby, $Lotno, $Rackno, $Flag, $daterun, $datereviewed, $type) = mysql_fetch_array($result)) {
                //get number of sampels per worksheet $numsamples = GetSamplesPerworksheet($worksheetno);
                if ($daterun != "") {
                    $daterun = date("d-M-Y", strtotime($daterun));
                }
                if ($datereviewed != "") {
                    $datereviewed = date("d-M-Y", strtotime($datereviewed));
                }
                $datecreated = date("d-M-Y", strtotime($datecreated));
                $creator = GetUserFullnames($createdby);
                if ($Flag == 0) {
                    $status = "<strong><small><font color='#0000FF'>In Process</small></font></strong>";
                    if ($type == 0) {
                        $d = "<a href=\"worksheetDetails.php" . "?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadworksheet.php" . "?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" . "?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $worksheetno');\">Delete Worksheet<a> | <a href=\"updateresults.php" . "?ID=$worksheetno" . "\" title='Click to Update Results Worksheet'>Update Results </a>";
                        $worksheettype = "<small><font color='#0000FF'>Taqman</font></small>";
                    } else if ($type == 1) {
                        $d = "<a href=\"manualworksheetDetails.php" . "?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadmanualworksheet.php" . "?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" . "?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $ID');\">Delete Worksheet </a> | <a href=\"updatemanualresults.php" . "?view=1&ID=$worksheetno" . "\" title='Click to Update Results Worksheet'> Update Results </a>";
                        $worksheettype = "<small><font color='#FF0000'>Manual</font></small>";
                    }
                } else {
                    $status = "<strong><font color='#339900'>Complete</font></strong>";
                    if ($type == 0) {
                        $worksheettype = "<small><font color='#0000FF'>Taqman</font></small>";
                    } else if ($type == 1) {
                        $worksheettype = "<small><font color='#FF0000'>Manual</font></small>";
                    }if ($type == 0) {
                        $d = "<a href=\"completeworksheetDetails.php" . "?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this batch'>View Details</a> | <a href=\"downloadcompleteworksheet.php" . "?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> ";
                    } elseif ($type == 1) {
                        $d = " <a href=\"completemanualworksheetdetails.php" . "?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this batch'>View Details</a> | <a href=\"downloadcompletemanualworksheet.php" . "?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> ";
                    }
                }?>
            <tr class='even'>
                <td>
                    <?php echo $ID; ?>
                </td>
                <td>
                    <a href="worksheetDetails.php?ID=<?php echo $worksheetno; ?>" title="Click to view  Samples in this batch">
                        <?php echo $worksheetno ?>
                    </a>
                </td>
                <td>
                    <?php echo $datecreated; ?>
                </td>
                <td>
                    <?php echo $creator ?>
                </td>
                <td>
                    <?php echo $worksheettype ?>
                </td>
                <td>
                    <?php echo $numsamples ?>
                </td>
                <td>
                    <?php echo $Lotno ?>
                </td>
                <td>
                    <?php echo $daterun ?>
                </td>
                <td>
                    <?php echo $datereviewed ?>
                </td>
                <td>
                    <?php echo $status ?>
                </td>
                <td>
                    <?php echo $d ?>
                </td>
            </tr>
            <?php }?>
        </table>
        <br />
        <?php
if ($searchparameterid > 0) {
                $numrows = $no;
            } else {
                $numrows = $filterNum; //Gettotalworksheets(); //get total no of batches
            }

            // how many pages we have when using paging?
            $maxPage = ceil($numrows / $rowsPerPage);

            // print the link to access each page
            $self = $_SERVER['PHP_SELF'];
            $nav = '';
            for ($page = 1; $page <= $maxPage; $page++) {
                if ($page == $pageNum) {
                    $nav .= " $page "; // no need to create a link to current page
                } else {
                    $nav .= " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">$page</a> ";
                }
            }

            // creating previous and next link
            // plus the link to go straight to
            // the first and last page

            if ($pageNum > 1) {
                $page = $pageNum - 1;
                $prev = " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">[Prev]</a> ";

                $first = " <a href=\"$self?page=1&wtype={$_SESSION['wtype']}\">[First Page]</a> ";
            } else {
                $prev = '&nbsp;'; // we're on page one, don't print previous link
                $first = '&nbsp;'; // nor the first page link
            }

            if ($pageNum < $maxPage) {
                $page = $pageNum + 1;
                $next = " <a href=\"$self?page=$page&wtype={$_SESSION['wtype']}\">[Next]</a> ";

                $last = " <a href=\"$self?page=$maxPage&wtype={$_SESSION['wtype']}\">[Last Page]</a> ";
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
                        <strong>
                            <font color="#666600">No Worksheets Created</font>
                        </strong>
                    </div>
                </td>
            </tr>
        </table>
        <?php
}
    }
}
?>

    </div>
</div>

<?php include '../includes/footer.php';?>