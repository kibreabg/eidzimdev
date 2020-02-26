<?php
include("../connection/config.php");
include("../nationaldashboardfunctions.php");
$currentyear = $_GET['mwaka'];
$currentmonth = $_GET['mwezi'];

//SMS prints that succeeded
$query = "CALL Getsmsqueuesuccessfail($currentyear,$currentmonth,'" . Successful . "', @numprints)";
$rsu = mysql_query($query);
$rsu = mysql_query("SELECT @numprints as 'numprints'");
$du = mysql_fetch_array($rsu);
$success = $du['numprints'];

//SMS prints that failed
$rsi = mysql_query("CALL Getsmsqueuesuccessfail($currentyear,$currentmonth,'" . Failed . "', @numprints)");
$rsi = mysql_query("SELECT @numprints as 'numprints'");
$di = mysql_fetch_array($rsi);
$fail = $di['numprints'];

//SMS prints that are queued
$rsl = mysql_query("CALL Getsmsqueuesuccessfail($currentyear,$currentmonth,'" . Queued . "', @numprints)");
$rsl = mysql_query("SELECT @numprints as 'numprints'");
$dz = mysql_fetch_array($rsl);
$queued = $dz['numprints'];

//SMS prints that are in progress
$rsd = mysql_query("CALL Getsmsqueuesuccessfail($currentyear,$currentmonth,'" . InProgress . "', @numprints)");
$rsd = mysql_query("SELECT @numprints as 'numprints'");
$dx = mysql_fetch_array($rsd);
$inProgress = $dx['numprints'];

$totaloutcome = $fail + $success + $queued + $inProgress;

if ($positive != 0) {
    $pospecentage = (($positive / $totaloutcome) * 100);
} else {
    $pospecentage = 0;
}

if ($negative != 0) {
    $negpecentage = (($negative / $totaloutcome) * 100);
} else {
    $negpecentage = 0;
}
?>
<chart palette='4' caption='' showValues='1' decimals='0' formatNumberScale='0' useRoundEdges='1' showBorder="0"  bgColor='FFFFFF'  smartLineThickness='2' smartLineColor='333333' isSmartLineSlanted='1'>
    <set label="Failed" value="<?php echo $fail; ?>" />
    <set label="Successful" value="<?php echo $success; ?>" isSliced="1" />
    <set label="In Progress" value="<?php echo $inProgress; ?>" isSliced="1" />
    <set label="Queued" value="<?php echo $queued; ?>" isSliced="1" />
</chart>