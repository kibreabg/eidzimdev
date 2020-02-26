<?php
session_start();
$labss = $_SESSION['lab'];
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
$creator = GetUserFullnames($createdby);
?>
<html>
    <link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
    <style type="text/css">
        <!--
        .style1 {font-family: "Courier New", Courier, monospace}
        .style4 {font-size: 12}
        .style5 {font-family: "Courier New", Courier, monospace; font-size: 12; }
        .style7 {font-size: x-small}
        -->
    </style>
    <body onLoad="JavaScript:window.print();">
        <div align="center">
            <table>
                <tr>
                    <td><strong>HIV	LAB EARLY INFANT DIAGNOSIS<br/>

                            COBAS AMPLIPREP / TAQMAN TEMPLATE </strong>
                    </td>
                </tr>
            </table>
        </div>
        <table border="0" class="data-table">


            <tr class="even">
                <td >
                    <strong>Worksheet Serial No</strong>		</td>
                <td >
                    <span class="style5"><?php echo $ID; ?></span></td>

        <!--<td><strong>Spek Kit No	</strong>	</td>
        <td  colspan="">
        <div> <input name="spekkitno" type="text" id="spekkitno" value=""  style="width:100px"  class="text"  /> <span id="spekkitnoInfo"></span></div></td> -->
                <td><strong>KIT EXP</strong></td>
                <td ><?php echo $kitexpirydate; ?></td>	
                <td  ><strong>Master Lot No </strong></td>
                <td><span class="comment style1 style4"><?php echo $Lotno; ?></span></td>
            </tr>

            <tr class="even">
                <td >
                    <strong>Worksheet/Template No</strong>		</td>
                <td >
                    <span class="style5"><?php echo $worksheetno; ?></span></td>	

                <td ><strong>Date Cut</strong></td>
                <td  colspan="4"><?php echo $datecut; ?></td>
            </tr>

            <tr class="even">
                <td ><strong>Date Created</strong>		</td>
                <td ><strong><span class="style5"><?php echo $datecreated; ?></span></strong></td> 
                <td><strong>Created By	</strong>    </td>
                <td colspan="4"><strong><span class="style5">
<?php echo $creator; ?>
                        </span></strong>		</td>
                                        <!--<td ><strong>HIQCAP Kit No</strong></td>
                                        <td><div>
                                        <input name="hiqcap" type="text" id="hiqcap" value=""  style="width:100px" class="text" />
                                        <br />
                                        <span id="hiqcapInfo"></span></div></td>	
                
                                        <td><strong>Reviewed By </strong></td>
                                        <td colspan="2" >N/A</td>  -->
            </tr>

            <tr class="even">

<!--<td ><strong>Rack #</strong></td>
<td><div><input name="rackno" type="text" id="rackno" value="<?php //echo $rackno;  ?>"  style="width:100px" class="text" /><br />
<span id="rackInfo"></span></div></td>
<td><strong>Date Reviewed</strong></td>
<td colspan="2">N/A</td> -->
            </tr>

            <tr >
                <th colspan="6" >&nbsp; 		</th>
            </tr>

            <tr style='background:#dddddd;'>
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


                $qury = "SELECT ID,patient,nmrlstampno
         FROM samples
		WHERE worksheet='$wno' ORDER BY parentid DESC,nmrlstampno asc,ID ASC";
                $result = mysql_query($qury) or die(mysql_error());


                while (list($ID, $patient, $nmrlstampno) = mysql_fetch_array($result)) {
                    $scount = $scount + 1;
                    $paroid = getParentID($ID, $labss); //get parent id

                    if ($paroid == 0) {
                        $paroid = "";
                    } else {
                        $paroid = " - " . $paroid;
                    }
                    $RE = $colcount % 6;




                    echo "<td > <span class='style7'>
	  NMRL No&nbsp;&nbsp;&nbsp; :  $nmrlstampno <br> 
	  Request No :  $patient   $paroid <br> 	 
	  Lab Code&nbsp;&nbsp;&nbsp; :  $ID <br> 	  
	  </span>   
	  <div align='center'>
	  <img src=html/image.php?code=code128&o=2&dpi=50&t=50&r=1&rot=0&text=$nmrlstampno&f1=Arial.ttf&f2=8&a1=&a2=B&a3=   />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; 
	  </div>
	  </td>
			
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