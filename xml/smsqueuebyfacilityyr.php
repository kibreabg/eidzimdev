<?php
require_once('../connection/config.php');
$facilityID = $_GET['facility'];
?>
<chart lineThickness="1" showValues="0" formatNumberScale="0" anchorRadius="2" divLineAlpha="20" divLineColor="CC3300" divLineIsDashed="1" showAlternateHGridColor="1" alternateHGridAlpha="5" alternateHGridColor="CC3300" shadowAlpha="40" labelStep="0" numvdivlines="5" chartRightMargin="35"  bgAngle="270" bgAlpha="10,10" bgColor='#FFFFFF' showBorder='0' overlapColumns="0" useRoundEdges="1">
    <categories>
        <?php
        $getminyear = "SELECT MIN(YEAR(dateQueued)) AS lowdate FROM smsprintqueue WHERE dateQueued !='1970-01-01' AND dateQueued !='0000-00-00' AND dateQueued !=''";
        $minyear = mysql_query($getminyear) or die(mysql_error());
        $dateresult = mysql_fetch_array($minyear);
        $minyr = $dateresult['lowdate'];

        $getmaxyear = "SELECT MAX( YEAR( dateQueued ) ) AS maximumyear FROM smsprintqueue WHERE dateQueued !='1970-01-01' AND dateQueued !='0000-00-00' AND datetested !=''";
        $maxyear = mysql_query($getmaxyear) or die(mysql_error());
        $year = mysql_fetch_array($maxyear);
        $maxyr = $year['maximumyear'];

        $year = $maxyr;
        $min = $minyr;
        for ($year; $year >= $min; $year--) {
            ?>
            <category label="<?php echo $year; ?>"/><?php } ?>
    </categories>

    <dataset seriesName="Success" showValues="0">
        <?php
        $year = $maxyr;
        $min = $minyr;
        for ($year; $year >= $min; $year--) {
            //Successful ones
            $successVar = Successful;
            $rs2 = mysql_query("CALL Getsmsqueuebyfacilityyr($facilityID,$year,'{$successVar}', @numprints)");
            $rs2 = mysql_query("SELECT @numprints as 'numprints'");
            $d2 = mysql_fetch_array($rs2);
            $success = $d2['numprints'];
            ?>
            <set value="<?php echo $success; ?>"/><?php } ?>
    </dataset>
    <dataset seriesName="Failed" showValues="0">
        <?php
        $year = $maxyr;
        $min = $minyr;
        for ($year; $year >= $min; $year--) {
            //Failed ones
            $failedVar = Failed;
            $rst = mysql_query("CALL Getsmsqueuebyfacilityyr($facilityID,$year,'{$failedVar}', @numprints)");
            $rst = mysql_query("SELECT @numprints as 'numprints'");
            $dt = mysql_fetch_array($rst);
            $failed = $dt['numprints'];
            ?>
            <set value="<?php echo $failed; ?>"/><?php } ?>
    </dataset>
    <dataset seriesName="Queued" showValues="0">
        <?php
        $year = $maxyr;
        $min = $minyr;
        for ($year; $year >= $min; $year--) {
            //Queued ones
            $queuedVar = Queued;
            $rsk = mysql_query("CALL Getsmsqueuebyfacilityyr($facilityID,$year,'{$queuedVar}', @numprints)");
            $rsk = mysql_query("SELECT @numprints as 'numprints'");
            $d3 = mysql_fetch_array($rsk);
            $queued = $d3['numprints'];
            ?>
            <set value="<?php echo $queued; ?>"/><?php } ?>
    </dataset>
    <dataset seriesName="In Progress" showValues="0">
        <?php
        $year = $maxyr;
        $min = $minyr;
        for ($year; $year >= $min; $year--) {
            //In Progress ones
            $inProgVar = InProgress;
            $rsf = mysql_query("CALL Getsmsqueuebyfacilityyr($facilityID,$year,'{$inProgVar}', @numprints)");
            $rsf = mysql_query("SELECT @numprints as 'numprints'");
            $d4 = mysql_fetch_array($rsf);
            $inProgress = $d4['numprints'];
            ?>
            <set value="<?php echo $inProgress; ?>"/><?php } ?>
    </dataset>
</chart>
