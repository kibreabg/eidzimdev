<?php
session_start();
$labss = $_SESSION['lab'];
require_once('../connection/config.php');
include('../includes/header.php');
$userid = $_SESSION['uid']; //id of user who is updatin th record
$wno = $_GET['ID'];

$worksheet = getWorksheetDetails($wno);
extract($worksheet);
$datecreated = date("d-M-Y", strtotime($datecreated));
if ($kitexpirydate != "") {
    $kitexpirydate = date("d-M-Y", strtotime($kitexpirydate));
}
if ($datecut != "") {
    $datecut = date("d-M-Y", strtotime($datecut));
} else {
    $datecut = "";
}
if ($daterun != "") {
    $daterun = date("d-M-Y", strtotime($daterun));
} else {
    $daterun = "";
}


$creator = GetUserFullnames($createdby);


if (isset($_POST['submit'])) {
    $work = $_POST['work'];
    $serialno = $_POST['serialno'];
    $labcode = $_POST['labcode'];
    $outcome = $_POST['testresult'];
    $daterun = $_POST['daterun'];
    if ($daterun != "") {
        $daterun = date("Y-m-d", strtotime($daterun)); //convert to yy-mm-dd
    } else {
        $daterun = "";
    }
    $dateresultsupdated = date('Y-m-d');
    $datereviewed = date("Y-m-d");
    foreach ($labcode as $a => $b) {

        $import = mysql_query("UPDATE samples
              SET result = '$outcome[$a]' ,datemodified = '$dateresultsupdated', datetested='$daterun'
			  			  WHERE (ID = '$labcode[$a]')");
        // echo  "wsheet" . $work   ."date run " . $daterun ."<br/>";
        //  echo  "Lab ID ". $labcode[$a] . " Result" .$outcome[$a] . "<br/>";
    }

    $updateworksheetrec = mysql_query("UPDATE worksheets
             SET Updatedby='$userid' ,daterun='$daterun' , Flag=0 , reviewedby='$userid',datereviewed='$datereviewed'
			   			   WHERE ( ((worksheetno = '$work') AND (ID='$serialno')) )") or die(mysql_error());


    if ($import && $updateworksheetrec) {
        //save user activity
        $tasktime = date("h:i:s a");
        $todaysdate = date("Y-m-d");
        $utask = 7; //user task = update worksheet

        $activity = SaveUserActivity($userid, $utask, $tasktime, $work, $todaysdate);

        $st = "Results Updated successfully, Please Confirm and approve the updated results below";
        echo '<script type="text/javascript">';
        echo "window.location.href='confirmmanualresults.php?view=1&p=$st&q=$work'";
        echo '</script>';
    }
}
?>
<html>
    <link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
    <style type="text/css">
        <!--
        .style1 {font-family: "Courier New", Courier, monospace}
        .style4 {font-size: 12}
        .style5 {font-family: "Courier New", Courier, monospace; font-size: 12; }
        .style7 {
            font-size: small;
            font-weight: bold;
        }
        .style11 {font-family: "Courier New", Courier, monospace; font-size: 12px; }
        -->
    </style>
    <style type="text/css">
        select {
            width: 250;}
        </style>	
        <script type="text/javascript" src="../includes/validationresults.js"></script>
        <link rel="stylesheet" href="../includes/validation.css" type="text/css" media="screen" />

        <script language="javascript" src="calendar.js"></script>
        <link type="text/css" href="calendar.css" rel="stylesheet" />	
        <link href="base/jquery-ui.css" rel="stylesheet" type="text/css"/>

        <script src="jquery-ui.min.js"></script>
        <link rel="stylesheet" href="demos.css">
        <script>
            $(document).ready(function() {
                // $("#dob").datepicker();
                $( "#daterun" ).datepicker({ minDate: "-7D", maxDate: "+0D" });
            });


            //  });
        </script>
        <div  class="section">
        <div class="section-title"><FONT style="font-family:Verdana, Arial, Helvetica, sans-serif">UPDATE TEST RESULTS FOR WORKSHEET NO <FONT color="#990000"><?php echo $worksheetno; ?> </FONT></FONT></div>
        <div class="xtop"><div class="error">
                Select the sample result from the drop down for each sample. <u><strong>KINDLY CONFIRM</strong></u> the results <U><strong>BEFORE UPDATING!!!!!!!!</strong></U></div>
        </div>
        <form action="" method="post" id="customForm">
            <table width="" border="1" style="border-right-color:#CCCCCC; border-bottom-color:#CCCCCC; font-family:Courier New, Courier, monospace">
                <tr>

                    <td width="120"  >Serial No </td>
                    <td width="179"><?php echo $ID; ?></span><input name='serialno' type='hidden' id='serialno' value='<?php echo $ID; ?>'  style='width:174px' readonly=''  /> </td>
                    <td width="120">Date Created </td>
                    <td width="179"><?php echo $datecreated; //get  date created ?></td>
                    <td width="120">Master LOT NO</td>
                    <td width="195"><?php echo $Lotno; ?></td>

                </tr>
                <tr>
                    <td >Worksheet No </td>
                    <td><?php echo $worksheetno; ?></span> <input name='work' type='hidden' id='work' value='<?php echo $worksheetno; ?>'  style='width:174px' readonly=''  /></td>
                    <td>Created By </td>
                    <td><?php echo $creator; ?></td>
                    <td >EXPIRY Date</td>
                    <td><FONT color="#FF0000"><strong><?php echo $kitexpirydate; ?></strong></FONT>	</td>			
                </tr>
                <tr>
                    <td>Date Run </td>
                    <td><div><input id="daterun" type="text" name="daterun" class="text"  size="26" ><span id="daterunInfo"></span></div><div type="text" id="daterun"></div></td>
                    <td>Reviewed By  </td>
                    <td> N/A </td>
                    <td >Reviewed Date </td>
                    <td>N/A </td>	
                </tr>
            </table>

            <table width="" class="data-table">

                <tr><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th></tr>
                <tr class="even">
                    <td><table border="1" class="data-table">
<?php
$count = 1;

$rowcount = 1;
$d = 1;
?>
<?php
//$i=mysql_num_rows($result);
for ($i = 1; $i <= 4; $i++) {
    if ($i == 1) {
        $count = "<p>&nbsp;</p><p>NC1</p><br><br>";
    }
    if ($i == 2) {
        $count = "<p>&nbsp;</p><p>PC1</p><br><br>";
    } ELSEIF ($i == 3) {
        $count = "<p>&nbsp;</p><p>CDC NC</p><br><br>";
    } ELSEIF ($i == 4) {
        $count = "<p>&nbsp;</p><p>CDC PC</p><br><br>";
    }
    $RE = $rowcount % 8;
    ?>
                                <tr><td height="50" > <?php echo $count; ?> </td></tr><?php
                            $count++;
                            $rowcount++;
                        }


                        $colcount = 1;

                        $qury = "SELECT ID,patient,nmrlstampno
         FROM samples
		WHERE worksheet='$wno' ORDER BY  parentid DESC,nmrlstampno asc,ID ASC";
                        $result = mysql_query($qury) or die(mysql_error());

                        while (list($ID, $patient, $nmrlstampno) = mysql_fetch_array($result)) {
                            $paroid = getParentID($ID, $labss); //get parent id

                            if ($paroid == 0) {
                                $paroid = "";
                            } else {
                                $paroid = " - " . $paroid;
                            }
                            $RE = $rowcount % 8;
    ?>
                                <tr>
                                    <td  height="50" width="">

                                        NMRL No &nbsp;&nbsp;&nbsp;&nbsp;: <strong><?php echo $nmrlstampno; ?></strong>  <br>   
                                        Request No  : <strong><?php echo $patient; ?></strong>  <br>  
                                        Lab Code &nbsp;&nbsp;&nbsp;&nbsp;: <input name='labcode[]' type='hidden' id='labcode[]' value='<?php echo $ID; ?>' size='5' readonly=''><strong><?php echo $ID . " " . $paroid; ?></strong>  <br> 
                                        <font color="#0000FF" style="font-family:Verdana, Arial, Helvetica, sans-serif">Result</font> &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php
                                $requery = "SELECT ID,Name FROM results where ID !=4  "; //and ID !=5

                                $rreesult = mysql_query($requery) or die('Error, query failed'); //onchange='submitForm();'

                                echo "<select name='testresult[]' id='testresult[]' ;>\n";
                                echo " <option value=''> Select One </option>";
                                while ($rerow = mysql_fetch_array($rreesult)) {
                                    $reID = $rerow['ID'];
                                    $rename = $rerow['Name'];
                                    echo "<option value='$reID'> $rename</option>\n";
                                }
                                echo "</select>\n";
                                ?> </td>
                                </tr>
                                <?php
                                $rowcount++;
                                $d++;

                                if ($RE == 0) {
                                    ?>
                                </table>	</td>
                            <td>
        <!--				<table class="data-table" border="1">
                                    <tr><td  height="50">&nbsp;&nbsp;</td></tr>
                                    
                                    <tr><td  height="50">&nbsp;&nbsp;</td></tr>
                                    <tr><td  height="50">&nbsp;&nbsp;</td></tr>
                                    <tr><td  height="50">&nbsp;&nbsp;</td></tr>
                                    <tr><td  height="50">&nbsp;&nbsp;</td></tr>
                                    <tr><td  height="50">&nbsp;&nbsp;</td></tr>
                                    <tr><td  height="50">&nbsp;&nbsp;</td></tr>
                                    
                                    <tr><td  height="50">&nbsp;</td></tr>
                                    </table>
                                -->			</td>
                            <td>
                                <table class="data-table" border="1">

                                            <?php
                                        }
                                    }


                                    //end while
                                    ?>   
                    </td></tr>  
            </table>
            </td>
            </tr>
            <tr class="even"><td colspan="13">
                    <div class="error">
                        <u><strong>KINDLY CONFIRM EACH OF THE RESULTS BEFORE UPDATING!!!!!!!!</strong></U></div>
                    </div></td>
            </tr>

            <th colspan='13'>
                <input type='submit' name='submit' value='Update Results' class='button' style="width:400px"></th>

            </table>
        </form>
<?php include('../includes/footer.php'); ?>
