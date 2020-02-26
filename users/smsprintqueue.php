<?php
require_once('../connection/config.php');
include('../includes/header.php');
include ('select.class.php');
?>
<script type="text/javascript">
    function CheckUncheckAll(the_form,c) 
    {
        for (var i=0; i < the_form.elements.length; i++) 
        {
            if (the_form.elements[i].type=="checkbox") 
            {
                the_form.elements[i].checked = !(the_form.elements[i].checked);
		
            }
        }
        the_form.checkall.checked = c;
    }

    function toggle2(showHideDiv, switchTextDiv) {
        var ele = document.getElementById(showHideDiv);
        var text = document.getElementById(switchTextDiv);
        if(ele.style.display == "block") {
            ele.style.display = "none";
            text.innerHTML = "Open";
        }
        else {
            ele.style.display = "block";
            text.innerHTML = "Close";
        }
    }

</script>

<style type="text/css">

    #headerDiv, #contentDiv {
        float: left;
        width: 100%;
    }
    #titleText {
        float: left;
        font-size: 1.1em;
        font-weight: bold;
        margin: 5px;
        color:#666666;
    }
    #myHeader {
        font-size: 1.1em;
        font-weight: bold;
        margin: 5px;
    }
    #headerDiv {
        background-color: #CCCCCC;
        color: #9EB6FF;
    }
    #contentDiv {
        background-color: #FFE694;
    }
    #myContent {
        margin: 5px 10px;
    }
    #headerDiv a {
        float: right;
        margin: 10px 10px 5px 5px;
    }
    #headerDiv a:hover {
        color: #FFFFFF;
    }
</style>
<?php
//..get the sms variables from the smues page
$smsstatus = $_GET['smsstatus']; //.. 1= sent; 0 = failed send
$sfacility = $_GET['sfacility'];
$spid = $_GET['spid'];
$errorCode = $_GET['errorCode'];
$errormsg = $_GET['errormsg'];
$sfacilityname = GetFacility($sfacility);

$did = "0";
$fid = "0";
$ps = -1;
$sdate = "";
if (isset($_GET['fiform'])) {

    $did = $_GET['district'];
    $fid = $_GET['facility'];
    $ps = $_GET['printed'];
    //echo $ps."<br>";
    $date = $_GET['txtdate'];
    if ($date != "") {
        list($d, $m, $y) = preg_split('/\//', $date);
        $start = sprintf('%4d%02d%02d', $y, $m, $d);

        $sdate = date("Y-m-d", strtotime($start)); //convert to yy-mm-dd
    }
    echo $sdate;
}

$rowsPerPage = 10; //number of rows to be displayed per page
// by default we show first page
$pageNum = 1;
// if $_GET['page'] defined, use it as page number
if (isset($_GET['page'])) {
    $pageNum = $_GET['page'];
}
// counting the offset
$offset = ($pageNum - 1) * $rowsPerPage;

$query = "SELECT
  sampleID, datequeued, failedprinting, successfullyprinted, facilityname, facilityID, dbsrequestno, patientname, dateofbirth, datecollected, datetested, result
FROM
  smsprintqueue
  LEFT JOIN facilitys ON facilitys.ID = smsprintqueue.facilityID
  LEFT JOIN districts ON districts.ID = smsprintqueue.districtID
WHERE
  (facilitys.imei != NULL OR facilitys.imei != '') AND 1 = CASE WHEN '$did' = '0' THEN 1 WHEN '$did' = districts.ID THEN 1 END AND 1 = CASE WHEN ('$fid' = '0' OR '$fid' = '') THEN 1 WHEN '$fid' = smsprintqueue.facilityID THEN 1 END
ORDER BY
  smsprintqueue.datequeued DESC";

$query2 = " LIMIT $offset, $rowsPerPage ";

$query3 = $query . $query2;
//echo $query3;
$queryresult = mysql_query($query3) or die(mysql_error());

$allqueryresult = mysql_query($query) or die(mysql_error());

$no = mysql_num_rows($allqueryresult);
?>
<link rel="stylesheet" href="gprsprint/themes/blue/style.css" type="text/css" media="print, projection, screen" />
<script type="text/javascript" src="gprsprint/jquery-latest.js"></script> 
<script type="text/javascript" src="gprsprint/jquery.tablesorter.js"></script> 
<script type="text/javascript" src="gprsprint/addons/pager/jquery.tablesorter.pager.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
	
        $("#myTable").tablesorter({widgets: ['zebra'],headers: { 13:{sorter: false},12:{sorter: false}}}); 
        //$("#myTable").tablesorterPager({container: $("#pager")});
        //		
        $("select#type").attr("disabled","disabled");
        $("select#category").change(function(){
            $("select#type").attr("disabled","disabled");
            $("select#type").html("<option>wait...</option>");
            var id = $("select#category option:selected").attr('value');
            // 
            $.post("select_type.php", {id:id}, function(data){
		
                $("select#type").removeAttr("disabled");
                $("select#type").html(data);
            });
        });
        //
        $( "#txtdate" ).datepicker();
	
    });
</script>

<!-- search parameter -->
<form id="searchform" method="get" enctype=”multipart/form-data” action="">
    <table>
        <tr><td>
                District:<br />
                <select id="category" name="district">
                    <?php echo $opt->ShowCategory(); ?>
                </select>
            </td>
            <td>
                Facility:<br />
                <select id="type" name="facility">
                    <option value="0">choose...</option>
                </select>
            </td>
            <td>
                SMS Sent Status:<br />
                <select id='printed' name='printed'>
                    <option value="-1">choose...</option>
                    <option value="1">Printed</option>
                    <option value="0">Not Printed</option>

                </select>
            </td>
            <td>
                Date Dispatched(dd/mm/yyyy):<br/>
                <input type="text" id="txtdate" name="txtdate" class="text" size="32" />
                <input type="hidden" id="view" name="view" value="1" />
            </td>
        </tr>
        <tr>
            <td><input  class="button1" name="fiform" type="submit" value="Search"/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td >&nbsp;</td>
        </tr>
    </table>
</form>
<div id="result"></div>
<!-- end search parameter -->
<!--						print result 			-->
<?php
if ($smsstatus != '') {
    echo '<table><tr><td>';
    if ($smsstatus == 1) {//success
        echo "<div class='success'>The SMS for " . $sfacilityname . " [ Sample Request No: " . $spid . "] has been <u>SENT</u>.</div>";
    } else if ($smsstatus == 0) {//fail
        echo "<div class='error'>The SMS for " . $sfacilityname . " [ Sample Request No: " . $spid . "] has <u>NOT</u> been sent.			<br> Error Code: " . $errorCode . "<br> Error Message: " . $errormsg . "</div>";
    } else if ($smsstatus == -1) {//INVALID URL
        echo "<div class='error'>Invalid URL.</div>";
    }
    echo '</td></tr></table>';
}
?>
<!--                        end print result			-->
<!--many print-->
<?php
if (isset($_POST['formSubmit'])) {
    $selectedList = $_POST['tptypes'];
    if (empty($selectedList)) {
        echo "<div class='error'>No Request Selected.</div>";
    } else if (count($selectedList) > 10) {
        echo "<div class='error'>Max. No. allowed to send is 10.</div>";
    } else {
        $N = count($selectedList);

        require_once('gprsprint/multiplesmues.php');
        ?>
        <div id="headerDiv">
            <div id="titleText">SMS Result Log </div><a id="myHeader" href="javascript:toggle2('myContent','myHeader');" >Close</a>
        </div>
        <div style="clear:both;"></div>
        <div id="contentDiv">
            <div id="myContent" style="display: block;">
                <?php
                $urlOk = true;
                if ($urlOk == true) {
                    for ($i = 0; $i < $N; $i++) {
                        list($facility, $pid, $res) = split(",", $selectedList[$i]);
                        $imeDetial = GetFacilityimei($facility);
                        $imei = $imeDetial['pi'];
                        $password = $imeDetial['pp'];
                        $facilityname = GetFacility($facility);
                        $result = GetResultName($res); //get the result name ie either Positive, Negative....
                        $smsstatus = 0;
                        $apiver = 1;
                        $action = 'print';
                        $currentdate1 = date("F j, Y, g:i a");
                        $patientInfo = Get_patientInfo($pid);
                        $sampleInfo = Get_SampleInfo($pid);
                        $print_message = urlencode("Date: ") . urlencode($currentdate1) . "%0A%0A" . urlencode("Hospital Name: ") . urlencode($facilityname) . "%0A%0A" . urlencode("DBS Request No: ") . urlencode($pid) . "%0A" . urlencode("Patient Name: ") . urlencode($patientInfo['name']) . "%0A" . urlencode("DOB: ") . urlencode($patientInfo['dob']) . "%0A%0A" . urlencode("Test Type: ") . urlencode('DNA PCR') . "%0A" . urlencode("Date DBS Collected: ") . urlencode($sampleInfo['datecollected']) . "%0A" . urlencode("Date Sample Tested: ") . urlencode($sampleInfo['datetested']) . "%0A" . urlencode("Result: ") . urlencode($result) . "";

                        $requestUrl = getRequest($apiver, $imei, $password, $action, $print_message);
                        echo $requestUrl;
                        $response = ResponseToArray($requestUrl);
                        print_r( $response);

                        ResponseResult($response, $facilityname, $pid);
                    }
                } else {
                    echo "Invalid URL";
                }
                ?>
            </div>
        </div>
        <?php
    }
}
?>
<!--end many print-->
<form name="fm_dispatch" method="post">
    <?php if ($no != 0) { ?>
        <div style="padding-bottom:3px;" align="right">
            <input  class="button1" name="formSubmit" type="submit" value="SMS Selected"/></div>
        <table  id="myTable" class="tablesorter"> 
            <thead style="border: 1px solid black;">
                <tr>
                    <th>Facility Name</th>	
                    <th>DBS Request No</th>
                    <th>Patient Name</th>	
                    <th>Date of Birth</th>	
                    <th>Date Collected</th>	 
                    <th>Date Tested</th>
                    <th>Result</th>	
                    <th>Date Queued</th>	
                    <th>Print Failed</th>	
                    <th>Print Successful</th>
                    <th>Action</th>
                    <th><input type="checkbox"  name="checkall"  onclick=CheckUncheckAll(document.fm_dispatch,document.fm_dispatch.checkall.checked) /></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                while (list($sampleID, $datequeued, $failedprinting, $successfullyprinted, $facilityname, $facilityID, $dbsrequestno, $patientname, $dateofbirth, $datecollected, $datetested, $result) = mysql_fetch_array($queryresult)) {
                    $i = $i + 1;
                    //..check if result is printed
//                    if ($Smssent == 0) {
//                        $Smssent = "<font color='#FF0000'>N</font>";
//                    } else if ($Smssent == 1) {
//                        $Smssent = " <strong><font color='#339900'> Y </font></strong>";
//                    } else {
//                        $Smssent = " <strong> - </strong>";
//                    }
//
//                    $facilityname = GetFacility($facility);
//                    $routcome = GetResultType($result);
//                    $facilitydetails = getFacilityDetails($facility);
//                    extract($facilitydetails);
//
//                    $distid = GetDistrictID($facility);
//                    $provid = GetProvid($distid);
//                    $provname = GetProvname($provid);
//
//                    //..sanitize date received
//                    if (($datereceived != "" ) && ($datereceived != "0000-00-00") && ($datereceived != "1970-01-01")) {
//                        $date_received = date("d-M-Y", strtotime($datereceived));
//                    } else {
//                        $date_received = "";
//                    }
//                    //..sanitize date tested
//                    if (($datetested != "" ) && ($datetested != "0000-00-00") && ($datetested != "1970-01-01")) {
//                        $date_datetested = date("d-M-Y", strtotime($datetested));
//                    } else {
//                        $date_datetested = "";
//                    }
//                    //..sanitize date result updated		
//                    $date_result_updated = date("d-M-Y", strtotime($datemodified));
//
//                    if ($date_result_updated == '01-Jan-1970') {
//                        $date_result_updated = '';
//                    }
//                    //..sanitize date dispatched
//                    $date_dispatched = date("d-M-Y", strtotime($datedispatched));
//
//                    if ($date_dispatched == '01-Jan-1970') {
//                        $date_dispatched = '';
//                    }
//                    //..sanitize date releaased
//                    $date_released = date("d-M-Y", strtotime($datereleased));
//
//                    if ($date_released == '01-Jan-1970') {
//                        $date_released = '';
//                    }
//
//                    $currentdate = date('d-m-Y'); //get current date
//                    if (($datereceived != "" ) && ($datereceived != "0000-00-00") && ($datereceived != "1970-01-01")) {
//                        $sdrec2 = date("d-m-Y", strtotime($datereceived));
//                        $date_released2 = date("d-m-Y", strtotime($datereleased));
//                        $tat = round(getWorkingDays($sdrec2, $date_released2, $holidays));
//                    } else {
//                        $tat = "";
//                    }
//
//                    //..check TAT value
//                    if ($tat < 0) {
//                        $tat = '';
//                    }
//                    //$emailsent=EmailSent($ID);
//
//                    if ($nmrlstampno == 0) {
//                        $nmrlstampno = '<small>N/A</small>';
//                        $fcolor = '';
//                    } else {
//                        $fcolor = '#0000FF';
//                    }
                    //..get the result color
                    if ($result == 1) {//negative
                        $rcolor = '#009900';
                    } else if ($result == 2) {//positive
                        $rcolor = '#FF0000';
                    } else {
                        $rcolor = '#990000';
                    }
                    //..get the TAT color
//                    if ($tat > 21) {
//                        $tcolor = '#FF0000';
//                        $underline = '<u>';
//                        $endunderline = '</u>';
//                    } else {
//                        $tcolor = '#0000FF';
//                        $underline = '';
//                        $endunderline = '';
//                    }
                    ?>
                    <tr>
                        <td><strong><?php echo $facilityname; ?></strong></td>
                        <td><?php echo $dbsrequestno; ?> </td>
                        <td><?php echo $patientname; ?></td>
                        <td><?php echo $dateofbirth; ?></td>
                        <td><?php echo $datecollected; ?></td>
                        <td><?php echo $datetested; ?></td>
                        <td><?php echo $result; ?> </td>
                        <td><strong><?php echo $datequeued; ?></strong></td>
                        <td><strong><?php echo $failedprinting; ?></strong></td>
                        <td><?php echo $successfullyprinted; ?></td>

                        <?php
                        $sfacilityquery = mysql_query("SELECT imei as pi, pass as pp FROM facilitys where ID=" . $facilityID . "") or die(mysql_error());
                        $sdd = mysql_fetch_array($sfacilityquery);
                        $simei = $sdd['pi'];
                        $spassword = $sdd['pp'];
                        $smues = "";
                        $option = "";
                        if ($simei != '') {//..show link
                            $smues = sprintf("<a href=gprsprint/smues.php?facility=%s&pid=%s&res=%s>Send SMS</a> ", $facilityID, $dbsrequestno, $result);
                            /// 
                            $smsDetail[0] = $facilityID;
                            $smsDetail[1] = $dbsrequestno;
                            $smsDetail[2] = $result;
                        }
                        $option = sprintf("<input type='checkbox'  name='tptypes[]' value='%s,%s,%s,%s' />", $facilityID, $dbsrequestno, $result, $sampleID);
                        ?>
                        <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px"><?php echo $smues; ?></td>
                        <td><div align="center"><?php echo $option; ?></div></td>
                    </tr>
                <?php } ?>
            </tbody> 
        </table>
        <?php
        // how many pages we have when using paging?
        $maxPage = ceil($no / $rowsPerPage);

// print the link to access each page

        $self = $_SERVER['REQUEST_URI']; //$_SERVER['PHP_SELF'];
        $nav = '';
        for ($page = 1; $page <= $maxPage; $page++) {
            if ($page == $pageNum) {
                $nav .= " $page "; // no need to create a link to current page
            }
        }

        // creating previous and next link
        // plus the link to go straight to
        // the first and last page

        if ($pageNum > 1) {
            $page = $pageNum - 1;
            $prev = " <a href=\"$self&page=$page\">Prev  |</a> ";

            $first = " <a href=\"$self&page=1\">First Page | </a> ";
        } else {
            $prev = '&nbsp;'; // we're on page one, don't print previous link
            $first = '&nbsp;'; // nor the first page link
        }

        if ($pageNum < $maxPage) {
            $page = $pageNum + 1;
            $next = " <a href=\"$self&page=$page\"> | Next | </a> ";

            $last = " <a href=\"$self&page=$maxPage\">  Last Page </a> ";
        } else {
            $next = '&nbsp;'; // we're on the last page, don't print next link
            $last = '&nbsp;'; // nor the last page link
        }

        // print the navigation link
        echo '<center><font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px">' . $first . "  " . $prev . " " . $nav . "  " . $next . "  " . $last . '</font></center>';
    } else {
        ?>
        <table   >
            <tr>
                <td style="width:auto" ><div class="notice"><?php
    echo '<strong>' . ' <font color="#666600">' . 'No Sample results' . '</strong>' . ' </font>';
        ?></div></td>
            </tr>
        </table><?php
                }
    ?>
</form> 
<?php include('../includes/footer.php'); ?>