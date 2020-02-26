<?php
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
include("../includes/functions.php");
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
if ($datereviewed != "") {
    $datereviewed = date("d-M-Y", strtotime($datereviewed));
} else {
    $datereviewed = "";
}
$reviewedby = GetUserFullnames($reviewedby);
$creator = GetUserFullnames($createdby);
?>
<html>
    <link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
    <style type="text/css">
        <!--
        .style1 {font-family: "Courier New", Courier, monospace}
        .style4 {font-size: 12}
        .style5 {font-family: "Courier New", Courier, monospace; font-size: 12; }
        -->
    </style>
    <body onLoad="JavaScript:window.print();" >

        <table border="1" class="" style="border-bottom-color:#CCCCCC; border-right-color:#999999">
            <tr>
                <td colspan="7"><div align="center"><span class="style5"><strong>HIV-1 DNA PCR WORKSHEET</strong></span></div></td>
            </tr>		
            <tr >
                <td class="comment style1 style4">Serial No		</td>
                <td><span class="style5"><?php echo $ID; ?></span></td>
                <td class="comment style1 style4">Lot No </td>
                <td><span class="comment style1 style4"><?php echo $Lotno; ?></span></td>
                <td><span class="style5">KIT EXPIRY </span></td>
                <td colspan="2"><span class="style5"><?php echo $kitexpirydate; ?></span></td>
            </tr>
            <tr >
                <td class="comment style1 style4">Worksheet No		</td>
                <td class="comment"><span class="style5"><?php echo $worksheetno; ?></span></td>		
                <td><span class="style5">Date Cut </span></td>
                <td><span class="style5"><?php echo $datecut; ?></span></td> 
                <td><span class="style5">Date Run </span></td>
                <td colspan="2"><span class="style5"><?php echo $daterun; ?></span></td>
            </tr>
            <tr >
                <td class="comment style1 style4">Date Created		</td>
                <td class="comment" ><span class="style5"><?php echo $datecreated; ?></span></td>
                <td class="comment style1 style4">		Created By	    </td>
                <td class="comment" colspan="4"  ><span class="style5">	    <?php echo $creator; ?>		</span></td>	 
            </tr>
            <tr >
                <td><span class="style5">Date Reviewed </span></td>
                <td ><span class="style5"><?php echo $datereviewed; ?></span>	</td>
                <td><span class="style5">Reviewed By </span></td>
                <td colspan="4"><span class="style5"><?php echo $reviewedby; ?></span></td>
            </tr>
            <tr>
                <th colspan="6" ><DIV align="center"><small> <strong><?php echo $no; ?> WORKSHEET SAMPLES [2 Controls]</strong></small></DIV>		</th>
            </tr>
            <tr >
                <?php
                $count = 1;
                $colcount = 1;
                for ($i = 1; $i <= 2; $i++) {
                    if ($count == 1) {
                        $pc = "<div align='right'>
	 	</div><div align='center'>Negative Control<br><strong>NC</strong></div>";
                    } elseif ($count == 2) {
                        $pc = "<div align='center'>Positive Control<br><strong>PC</strong></div>";
                    }

                    $RE = $colcount % 6;
                    ?>  <td height="50" bgcolor="#dddddd" class="comment style1 style4"> <?php echo $pc; ?> </td><?php
                    $count ++;
                    $colcount ++;
                }
                $scount = 2;


                $qury = "SELECT ID,patient,result,nmrlstampno
                         FROM samples
                         WHERE worksheet='$wno' order by parentid desc, nmrlstampno asc";
                $quryresult = mysql_query($qury) or die(mysql_error());


                while (list($ID, $patient, $result, $nmrlstampno) = mysql_fetch_array($quryresult)) {
                    $scount = $scount + 1;
                    $paroid = getParentID($ID, $labss); //get parent id

                    if ($paroid == 0) {
                        $paroid = "";
                    } else {
                        $paroid = " - " . $paroid;
                    }
//get sample sample test results
                    $routcome = GetResultType($result);

                    $RE = $colcount % 6;



                    if ($result == 1) {//negative
                        $beginfont = '<font color="#009900">';
                    } else if ($result == 2) {//positive
                        $beginfont = '<font color="#FF0000">';
                    } else {
                        $beginfont = '<font color="">';
                    }



                    echo "<td ><small>
	  NMRL No &nbsp;&nbsp;&nbsp;: $nmrlstampno <br>
	  Request No : $patient  <br> 
	  Lab Code  &nbsp;&nbsp; : $ID<input name='labcode[]' type='hidden' id='labcode' value='$ID' size='5' readonly=''> $paroid   <br>
	  Results &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $beginfont <strong>$routcome</strong> </font> </small></td>
";




                    $colcount ++;


                    if ($RE == 0) {
                        ?>
                    </tr>
                    <?php
                }//end if modulus is 0
            }//end while
            ?>
        </table>

    </body>
</html>