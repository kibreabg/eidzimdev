 <?php
session_start();
$labss = $_SESSION['lab'];
include('../includes/header.php');

define('IN_CB', true);

define('VERSION', '2.1.0');

if (version_compare(phpversion(), '5.0.0', '>=') !== true)
    exit('Sorry, but you have to run this script with PHP5... You currently have the version <b>' . phpversion() . '</b>.');

if (!function_exists('imagecreate'))
    exit('Sorry, make sure you have the GD extension installed before running this script.');

include('../html/config.php');
require('../html/function.php');
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');

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
    $datecut = $_POST['datecut'];
    $datecut = date("Y-m-d", strtotime($datecut)); //convert to yy-mm-dd
//save worksheet details
    $worksheetdetailsrec = "INSERT INTO worksheets(ID,worksheetno,datecreated,HIQCAPNO,spekkitno,createdby,Lotno,Rackno,kitexpirydate,datecut,lab)VALUES
('$worksheetserialno','$worksheetno','$datecreated','$hiqcap','$spekkitno','$userid','$lotno','$rackno','$kitexp','$datecut','$labss')";
    $worksheetdetail = @mysql_query($worksheetdetailsrec) or die(mysql_error());

    foreach ($labcode as $t => $b) {
        //update sample record
        $samplerec = mysql_query("UPDATE samples SET Inworksheet  = 1 ,  worksheet='$worksheetno' WHERE (ID = '$labcode[$t]')") or die(mysql_error());

        //update pendind tasks
        $repeatresults = mysql_query("UPDATE pendingtasks SET status = 1 WHERE (sample='$labcode[$t]' AND task=3)") or die(mysql_error());
    }

    if ($worksheetdetail && $samplerec) { //check if all records entered
        //save user activity
        $tasktime = date("h:i:s a");
        $todaysdate = date("Y-m-d");
        $utask = 5; //user task = create worksheet

        $activity = SaveUserActivity($userid, $utask, $tasktime, $worksheetno, $todaysdate);

        $disable = "Sample: ";
        echo '<script type="text/javascript">';
        echo "window.open('downloadworksheet.php?ID=$worksheetno','_blank')";
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
<script type="text/javascript" src="../includes/validation2.js"></script>
<link rel="stylesheet" href="../includes/validation.css" type="text/css" media="screen" />
<script language="javascript" src="calendar.js"></script>
<link type="text/css" href="calendar.css" rel="stylesheet" />	
<link href="base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="jquery-ui.min.js"></script>
<link rel="stylesheet" href="demos.css">
<script>
    $(document).ready(function() {
        // $("#dob").datepicker();
        $( "#kitexp" ).datepicker({ minDate: "-5D", maxDate: "+5Y" });
    });
</script>
<script>
    $(document).ready(function() {
        // $("#datecollected").datepicker();
        $( "#datecut" ).datepicker({ minDate: "-7D", maxDate: "+0D" });

    });
</script>

<script language="JavaScript">
    function submitPressed() {
        document.worksheetform.SaveWorksheet.disabled = true;
        //stuff goes here
        document.worksheetform.submit();
    }
</script> 
<div class="section">
    <div class="section-title">CREATE TAQMAN WORKSHEET / TEMPLATES </div>
    <div class="xtop">
        <?php
        if ($st != "") {
            ?> 
            <table>
                <tr>
                    <td style="width:auto" >
                        <div class="error">
                            <?php
                            echo '<strong>' . ' <font color="#666600">' . $st . '</strong>' . ' </font>';
                            ?>
                        </div>
                </tr>
            </table>
        <?php } ?>
        <?php
        //select TAQMAN maxlimit samples for testing
        $qury = "SELECT
                    s.ID, s.nmrlstampno, s.patientid, s.patient, s.parentid, s.datereceived, l.priority, IF(s.parentid > 0 OR s.parentid IS NULL, 0, 1) AS isnull, s.nmrlstampno
                  FROM
                    samples AS s, labs AS l
                  WHERE
                    l.withresult != 1 AND s.lab = l.id AND s.Inworksheet = 0 AND s.receivedstatus != 2 AND ((s.result IS NULL) OR (s.result = 0) OR (s.result = 6)) AND s.inputcomplete = 1 AND s.Flag = 1 AND s.approved = 1 
                  ORDER BY
                  l.priority ASC, s.nmrlstampno ASC,  s.priority ASC, isnull ASC,  s.datereceived ASC LIMIT 0,{$_GET['limit']}";
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
            list($worksheetserialno, $worksheetno) = GetNewWorksheetNo(0, $labss); //capture new worksheet
            ?>
            <form  method="post" action="" id="customForm">
                <?php
                if ($repeatcount > 0) {
                    ?>
                    <p><font style="background-color:#FF0000; font-size:14px" color="#FFFFFF">Please note that this worksheet <strong><?php echo $worksheetno; ?></strong> has <strong><?php echo $repeatcount; ?></strong> samples for repeat.</font></p>
                    <?php
                }
                ?>
                <table  border="0" class="data-table">
                    <tr class="even">
                        <td>
                            <strong>Worksheet Serial No</strong>		
                        </td>
                        <td>
                            <?php echo $worksheetserialno; ?><input name="worksheetserialno" type="hidden" id="worksheetserialno" value="<?php echo $worksheetserialno; ?>" />
                        </td>   
                        <td><strong>KIT EXP</strong></td>
                        <td>
                            <div>
                                <input id="kitexp" type="text" name="kitexp" class="text"  size="20" ><span id="kitexpInfo"></span>
                            </div>
                            <div id="kitexp">
                            </div>
                        </td>	
                        <td>
                            <strong>Master Lot No </strong>
                        </td>
                        <td>
                            <div>
                                <input name="lotno" type="text" id="lotno" value=""  style="width:100px"  class="text" />
                                <br/>
                                <span id="lotInfo"></span>
                            </div>
                        </td>
                    </tr>
                    <tr class="even">
                        <td>
                            <strong>Worksheet/Template No</strong>		
                        </td>
                        <td>
                            <?php echo $worksheetno; ?><input name="worksheetno" type="hidden" id="worksheetno" value="<?php echo $worksheetno; ?>"  />
                        </td>	
                        <td>
                            <strong>Date Cut</strong>
                        </td>
                        <td colspan="4">
                            <div>
                                <input id="datecut" type="text" name="datecut" class="text"  size="20" />
                                <br/>
                                <span id="datecutInfo"></span>
                            </div>
                            <div id="datecut">
                            </div>
                        </td>
                    </tr>
                    <tr class="even">
                        <td>
                            <strong>Date Created</strong>		
                        </td>
                        <td>
                            <strong>
                                <?php
                                $currentdate = date('d-M-Y');
                                echo '<strong>' . $currentdate . '</strong>'; //get current date  
                                ?>
                            </strong>
                        </td> 
                        <td>
                            <strong>Created By	</strong>
                        </td>
                        <td colspan="4">
                            <strong><?php echo $creator ?></strong>		
                        </td>
                    </tr>
                    <tr class="even">
                    </tr>
                    <tr>
                        <th colspan="6">
                            <small><strong><?php echo $no; ?> WORKSHEET SAMPLES [2 Controls]</strong></small>		
                        </th>
                    </tr>
                    <tr>
                        <?php
                        $count = 1;
                        $colcount = 1;
                        for ($i = 1; $i <= 2; $i++) {
                            if ($count == 1) {
                                $pc = "<div align='center'>Negative Control<br><strong>NC</strong></div>";
                            } elseif ($count == 2) {
                                $pc = "<div align='center'>Positive Control<br><strong>PC</strong></div>";
                            }

                            $RE = $colcount % 6;
                            ?>
                            <td height="50"> <?php echo $pc; ?> </td>
                            <?php
                            $count++;
                            $colcount++;
                        }
                        $scount = 2;


                        while (list($ID, $nmrlstampno, $patientid, $patient) = mysql_fetch_array($result)) {
                            $scount = $scount + 1;
                            $paroid = getParentID($ID, $labss); //get parent id
                            $pparoid = $paroid;

                            if ($paroid == 0) {
                                $paroid = "";
                            } else {
                                $paroid = " <br><small>[ Old Lab Code: " . $paroid . ' ]</small>';
                            }

                            $RE = $colcount % 6;

                            //..get the prev nmrlstampno from the paroid
                            $nmquery = mysql_query("SELECT nmrlstampno FROM samples where ID='$pparoid' ") or die(mysql_error());
                            $nmdd = mysql_fetch_array($nmquery);
                            $nmstampno = $nmdd['nmrlstampno'];



                            echo "<td width='230px'>
	  		NMRL Stamp No: <strong>$nmrlstampno</strong><br>  
			Request No: <strong>$patient</strong> <input name='patient[]' type='hidden' id='patientid' value='$patient' size='14' readonly=''>  <br>  
			<font color='#000099'>Lab Code  &nbsp;&nbsp;: <strong>$ID</strong> </font><input name='labcode[]' type='hidden' id='labcode' value='$ID' size='5' readonly=''> $paroid  <p>
			<div align='center'>
	 		<img src='html/image.php?code=code128&o=2&dpi=50&t=50&r=1&rot=0&text=$nmrlstampno&f1=Arial.ttf&f2=6&a1=&a2=B&a3='  /> 
			</div></p></td>";

                            $colcount++;

                            if ($RE == 0) {
                                ?>
                            </tr>
                            <?php
                        }//end if modulus is 0
                    }//end while
                    ?>
                    <tr>
                        <th colspan="7">
                            <input type="submit" name="SaveWorksheet" value="Save & Print Worksheet" class="button" />
                        </th>
                    </tr>
                </table>
            </form>
            <?php
        } else {
            ?>
            <table>
                <tr>
                    <td style="width:auto" ><div class="notice">
                            <?php
                            echo '<strong>' . ' <font color="#666600">' . 'No Enough Samples to run a test[' . $no . ']' . '</strong>' . ' </font>';
                            ?>
                        </div>
                </tr>
            </table>
        <?php }
        ?>
    </div>
</div>

<?php include('../includes/footer.php'); ?>