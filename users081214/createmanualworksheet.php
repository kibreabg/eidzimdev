<?php
session_start();
$labss = $_SESSION['lab'];
include('../includes/header.php');

require_once('../connection/config.php');
require_once('classes/tc_calendar.php');

$userid = $_SESSION['uid'];
$creator = GetUserFullnames($userid);

//$i=mysql_num_rows($result);
$userid = $_SESSION['uid'];
$creator = GetUserFullnames($userid);
//Array to store validation errors
$errmsg_arr = array();

//Validation error flag
$errflag = false;
$currentday = date('Y-m-d') + 1;
if ($_REQUEST['SaveWorksheet']) {
    $worksheetno = $_POST['worksheetno'];
    $worksheetserialno = $_POST['worksheetserialno'];
    $lotno = $_POST['lotno'];
    $hiqcap = $_POST['hiqcap'];
    $rackno = $_POST['rackno'];
    $spekkitno = $_POST['spekkitno'];
    $labcode = $_POST['labcode'];
    $sample = $_POST['sample'];
    $datecreated = date('d-m-Y');
    $kitexp = $_POST['kitexp'];
    $kitexp = date("Y-m-d", strtotime($kitexp)); //convert to yy-mm-dd
//$datecut = $_POST['datecut'];
//$datecut =date("Y-m-d",strtotime($datecut)); //convert to yy-mm-dd
//save worksheet details
    $worksheetdetailsrec = "INSERT INTO 		
worksheets(ID,worksheetno,datecreated,HIQCAPNO,spekkitno,createdby,Lotno,Rackno,kitexpirydate,datecut,lab,type)VALUES
('$worksheetserialno','$worksheetno','$datecreated','$hiqcap','$spekkitno','$userid','$lotno','$rackno','$kitexp','$datecut','$labss',1)";
    $worksheetdetail = @mysql_query($worksheetdetailsrec) or die(mysql_error());

    foreach ($labcode as $t => $b) {


//update sample record
        $samplerec = mysql_query("UPDATE samples
              SET  	Inworksheet  = 1 ,  worksheet='$worksheetno'
			  			   WHERE (ID = '$labcode[$t]')") or die(mysql_error());

        //pdate pendind tasks
        $repeatresults = mysql_query("UPDATE pendingtasks
             			 SET  status  	 =  1 
			 			WHERE (sample='$labcode[$t]' AND task=3)") or die(mysql_error());
    }

    if ($worksheetdetail && $samplerec) { //check if all records entered
        //save user activity
        $tasktime = date("h:i:s a");
        $todaysdate = date("Y-m-d");
        $utask = 5; //user task = create worksheet

        $activity = SaveUserActivity($userid, $utask, $tasktime, $worksheetno, $todaysdate);

        $disable = "Sample: ";
        echo '<script type="text/javascript">';
        echo "window.open('downloadmanualworksheet.php?ID=$worksheetno','_blank')";
        echo '</script>';
    } else {
        $st = "Worksheet Save Failed, try again ";
    }
}
?>
<style type="text/css">
    select {
        width: 250px;
    }
</style>	
<script type="text/javascript" src="../includes/validationM.js"></script>
<link rel="stylesheet" href="../includes/validation.css" type="text/css" media="screen" />

<script language="javascript" src="calendar.js"></script>
<link type="text/css" href="calendar.css" rel="stylesheet" />	
<link href="base/jquery-ui.css" rel="stylesheet" type="text/css"/>

<script src="jquery-ui.min.js"></script>
<link rel="stylesheet" href="demos.css">
<script>
    $(document).ready(function() {
        $( "#kitexp" ).datepicker({ minDate: "+1D", maxDate: "+5Y" });
    });
</script>
<div class="section">
    <div class="section-title">CREATE MANUAL WORKSHEET / TEMPLATES </div>
    <div class="xtop">
        <?php
        if (isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) > 0) {
            echo '<table>
				  <tr>
					<td style="width:auto" ><div class="error">';
            foreach ($_SESSION['ERRMSG_ARR'] as $msg) {
                echo $msg;
            }
            echo '</div></td>
				  </tr>
				</table>';
            unset($_SESSION['ERRMSG_ARR']);
        }
        ?>
        <?php
        if ($st != "") {
            ?> 
            <table>
                <tr>
                    <td style="width:auto">
                        <div class="error">
                            <?php
                            echo '<strong>' . ' <font color="#666600">' . $st . '</strong>' . ' </font>';
                            ?>
                        </div>
                    </td>
                </tr>
            </table>
        <?php } ?>
        <?php
        //select 22 samples for testing
        $qury = " SELECT
                    s.ID, s.nmrlstampno, s.patientid, s.patient, s.parentid, s.datereceived, l.priority, IF(s.parentid > 0 AND s.sampleokforretest = 1, 0, 1) AS isnull, s.nmrlstampno
                  FROM
                    samples AS s, labs AS l
                  WHERE
                    l.withresult != 1 AND s.lab = l.id AND s.Inworksheet = 0 AND s.receivedstatus != 2 AND ((s.result IS NULL) OR (s.result = 0) OR (s.result = 6)) AND s.approved = 1
                  ORDER BY
                    l.priority ASC, isnull ASC, s.nmrlstampno ASC, s.datereceived ASC
                  LIMIT
                    0, {$_GET['limit']}";

        $result = mysql_query($qury) or die(mysql_error());
        $represult = mysql_query($qury) or die(mysql_error());

        $no = mysql_num_rows($result); //no of samples

        if ($no == $_GET['limit']) {
            //..get no of repeat samples in this worksheet
            $repeatcount = 0;
            while (list($sampID, $samppatient, $sampparent) = mysql_fetch_array($represult)) {
                if ($sampparent > 0) {
                    $repeatcount = $repeatcount + 1;
                }
            }
            list($worksheetserialno, $worksheetno) = GetNewWorksheetNo(1, $labss); //capture new worksheet
            ?>
            <form  method="post" action="" id="customForm">
                <?php
                if ($repeatcount > 0) {
                    ?>
                    <p><font style="background-color:#FF0000; font-size:14px" color="#FFFFFF">Please note that this worksheet <strong><?php echo $worksheetno; ?></strong> has <strong><?php echo $repeatcount; ?></strong> samples for repeat.</font></p>
                    <?php
                }
                ?>
                <table width="1000" class="data-table">
                    <tr class="even">
                        <td width="120">Serial No</td>
                        <td width="179">
                            <?php echo $worksheetserialno; ?><input name="worksheetserialno" type="hidden" id="worksheetserialno" value="<?php echo $worksheetserialno; ?>"  />
                        </td>
                        <td width="121"  class="comment style1 style4">Date Created</td>
                        <td width="22" class="comment" colspan="2">
                            <span class="style5">
                                <?php
                                $currentdate = date('d-M-Y');
                                echo $currentdate; //get current date 
                                ?>
                            </span>
                        </td>
                        <td width="133">Master LOT NO</td>
                        <td width="195"  colspan="2">
                            <div>
                                <input name="lotno" type="text" id="lotno" value="" size="26"  class="text" />
                                <span id="lotInfo"></span>
                            </div>
                        </td>
                    </tr>
                    <tr class="even">
                        <td height="33">Worksheet No </td>
                        <td>
                            <?php echo $worksheetno; ?><input name="worksheetno" type="hidden" id="worksheetno" value="<?php echo $worksheetno; ?>"  />
                        </td>

                        <td class="comment style1 style4">Created By </td>
                        <td class="comment"  colspan="2"><?php echo $creator ?></td>
                        <td>EXP Date</td>
                        <td>
                            <div>
                                <input id="kitexp" type="text" name="kitexp" class="text"  size="26" ><span id="kitexpInfo"></span>
                            </div>
                            <div id="kitexp"></div>
                        </td>
                    </tr>
                </table>
                <table width="1000" class="data-table">

                    <tr><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th colspan="2">12</th></tr>
                    <tr>
                        <td>
                            <table border="0" class="data-table">
                                <?php
                                $count = 1;

                                $rowcount = 1;
                                $d = 1;
                                for ($i = 1; $i <= 4; $i++) {
                                    if ($i == 1) {
                                        $count = "<p>&nbsp;</p>NC1<br>";
                                    }
                                    if ($i == 2) {
                                        $count = "<p>&nbsp;</p>PC1<br>";
                                    } ELSEIF ($i == 3) {
                                        $count = "<p>&nbsp;</p>CDC NC<br>";
                                    } ELSEIF ($i == 4) {
                                        $count = "<p>&nbsp;</p>CDC PC<br>";
                                    }
                                    $RE = $rowcount % 8;
                                    ?>
                                    <tr>
                                        <td height="50" >
                                            <?php echo $count; ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $count++;
                                    $rowcount++;
                                }


                                $colcount = 1;


                                while (list($ID, $nmrlstampno, $patientid, $patient) = mysql_fetch_array($result)) {
                                    $paroid = getParentID($ID, $labss); //get parent id
                                    $pparoid = $paroid;
                                    if ($paroid == 0) {
                                        $paroid = "";
                                    } else {
                                        $paroid = " <br><small>[ Old Lab Code: " . $paroid . ' ]</small>';
                                    }
                                    $RE = $rowcount % 8;

                                    //..get the prev nmrlstampno from the paroid
                                    $nmquery = mysql_query("SELECT nmrlstampno FROM samples where ID='$pparoid' ") or die(mysql_error());
                                    $nmdd = mysql_fetch_array($nmquery);
                                    $nmstampno = $nmdd['nmrlstampno'];
                                    ?>
                                    <tr>
                                        <td height="50" width="145px">
                                            NMRL Stamp No: <strong><?php echo $nmstampno; ?></strong><br/>
                                            Sample Request No: <strong><?php echo $patient; ?></strong><input name='patient[]' type='hidden' id='patient' value='<?php echo $patientid; ?>' style="background-color:#F2F2F2"/><br/>  
                                            <font color="#000099">Lab Code:<strong><?php echo $ID; ?></strong></font><input name='labcode[]' type='hidden' id='labcode' value='<?php echo $ID; ?>' style="background-color:#F2F2F2"/><?PHP echo $paroid; ?> 
                                        </td>
                                    </tr>
                                    <?php
                                    $rowcount++;
                                    $d++;

                                    if ($RE == 0) {
                                        ?>
                                    </table>
                                </td>
                                <td>
                                    <table class="data-table">
                                        <tr><td  height="50">CT</td></tr>
                                        <tr><td  height="50">CT</td></tr>
                                        <tr><td  height="50">CT</td></tr>
                                        <tr><td  height="50">CT</td></tr>
                                        <tr><td  height="50">CT</td></tr>
                                        <tr><td  height="50">CT</td></tr>
                                        <tr><td  height="50">CT</td></tr>
                                        <tr><td  height="50">CT</td></tr>
                                    </table>
                                </td>
                                <td>
                                    <table class="data-table">

                                        <?php
                                    }
                                }
                                //end while
                                ?>   
                            </table>
                        </td>
                    </tr>
                    <tr bgcolor="">
                        <td colspan="13" bgcolor="" >
                    <center><input type="submit" name="SaveWorksheet" value="Save & Print Worksheet" class="button" /></center>
                    </td>
                    </tr>
                </table>
            </form>

            <?php
        } else {
            ?>
            <table>
                <tr>
                    <td style="width:auto">
                        <div class="notice">
                            <?php
                            echo '<strong>' . ' <font color="#666600">' . 'Not Enough Samples to run test [' . $no . '].' . '</strong>' . ' </font>';
                            ?>
                        </div>
                    </td>
                </tr>
            </table>
        <?php }
        ?>
    </div>
</div>

<?php include('../includes/footer.php'); ?>