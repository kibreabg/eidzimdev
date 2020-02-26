<?php
require_once('../connection/config.php');
include('../includes/header.php');

$labss = $_SESSION['lab'];

//get the periodic values
$monthly = $_GET['monthly'];
$monthyear = $_GET['monthyear'];
//translate the month values to text
if ($monthly == 1) {
    $month = "January";
} else if ($monthly == 2) {
    $month = "February";
} else if ($monthly == 3) {
    $month = "March";
} else if ($monthly == 4) {
    $month = "April";
} else if ($monthly == 5) {
    $month = "May";
} else if ($monthly == 6) {
    $month = "June";
} else if ($monthly == 7) {
    $month = "July";
} else if ($monthly == 8) {
    $month = "August";
} else if ($monthly == 9) {
    $month = "September";
} else if ($monthly == 10) {
    $month = "October";
} else if ($monthly == 11) {
    $month = "November";
} else if ($monthly == 12) {
    $month = "December";
}
?>

<div>
    <div class="section-title">SUMMARY OF TESTS DONE IN <?PHP echo strtoupper($month) . "," . $monthyear; ?>
        <div>
            <br />
            <?php
            $total = totalmonthlytests($labss, $monthly, $monthyear);
            $positives = monthlytestsbyresult($labss, $monthly, 2, $monthyear);
            if ($positives != 0) {
                $pospercentage = round((($positives / $total) * 100), 1);
            } else {
                $pospercentage = 0;
            }
            $negatives = monthlytestsbyresult($labss, $monthly, 1, $monthyear);
            if ($negatives != 0) {
                $negpercentage = round((($negatives / $total) * 100), 1);
            } else {
                $negpercentage = 0;
            }
            $failed = monthlytestsbyresult($labss, $monthly, 3, $monthyear);
            if ($failed != 0) {
                $failpercentage = round((($failed / $total) * 100), 1);
            } else {
                $failpercentage = 0;
            }
            $totalpercentage = $failpercentage + $negpercentage + $pospercentage;
            ?>
            <?php
            echo "
						<table border=0>
							<tr >
								
								<td><a href=\"downloadsummarymonthlyreport.php" . "?monthly=$monthly&monthyear=$monthyear" . "\" title='Click to Download PDF Report' target='_blank'>Download to Pdf </a> &nbsp;&nbsp;  </td>
							</tr>
					
							
						</table>";
            ?>
            <table width="325" border="0" class="data-table">
                <tr>
                    <th scope="col" colspan="3">&nbsp;</th>
                    <th scope="col" colspan="2">Number of Tests </th>
                    <th width="46" scope="col">% </th>
                </tr>
                <tr>
                    <td colspan="3">Positives</td>
                    <td colspan="2"><?php echo monthlytestsbyresult($labss, $monthly, 2, $monthyear); ?></td>
                    <td><?php echo $pospercentage; ?></td>
                </tr>
                <tr>
                    <td colspan="3">Negatives</td>
                    <td colspan="2"><?php echo monthlytestsbyresult($labss, $monthly, 1, $monthyear); ?></td>
                    <td><?php echo $negpercentage; ?></td>
                </tr>
                <tr>
                    <td colspan="3">Failed</td>
                    <td colspan="2"><?php echo monthlytestsbyresult($labss, $monthly, 3, $monthyear); ?></td>
                    <td><?php echo $failpercentage; ?></td>
                </tr>
                <tr>
                    <td colspan="3">Rejected</td>
                    <td colspan="2"><?php echo monthlyrejectedsamples($labss, $monthly, $monthyear); ?></td>
                    <td>&nbsp; - &nbsp; </td>
                </tr>
                <tr>
                    <td colspan="3">Total Tests</td>
                    <td colspan="2"><?php echo totalmonthlytests($labss, $monthly, $monthyear); ?></td>
                    <td><?php echo $totalpercentage; ?></td>
                </tr>
            </table>
            <?php
            echo "
						<table border=0>
							
					
							<tr >
								<td><a href='labreports.php'><strong>Go back to Lab Report Filter.</strong></a></td>
							</tr>
						</table>";
            ?>		
        </div>	
    </div>


<?php include('../includes/footer.php'); ?>