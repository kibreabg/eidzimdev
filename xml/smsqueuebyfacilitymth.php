<?php
require_once('../connection/config.php');
$facilityID = $_GET['facility'];
$year = $_GET['year'];
?>
<chart lineThickness="1" showValues="0" formatNumberScale="0" anchorRadius="2" divLineAlpha="20" divLineColor="CC3300" divLineIsDashed="1" showAlternateHGridColor="1" alternateHGridAlpha="5" alternateHGridColor="CC3300" shadowAlpha="40" labelStep="0" numvdivlines="5" chartRightMargin="35"  bgAngle="270" bgAlpha="10,10" bgColor='#FFFFFF' showBorder='0' overlapColumns="0" useRoundEdges="1">
    <categories>
        <?php
        for ($i = 1; $i <= 12; $i++) {
            ?>
            <category label="<?php echo $i; ?>"/><?php } ?>
    </categories>

    <dataset seriesName="Success" showValues="0">
        <?php
        for ($i = 1; $i <= 12; $i++) {
            //Successful ones
            $rs2 = mysql_query("CALL Getsmsqueuebyfacilitymth($facilityID,$year,$i,'" . Successful . "', @numprints)");
            $rs2 = mysql_query("SELECT @numprints as 'numprints'");
            $d2 = mysql_fetch_array($rs2);
            $success = $d2['numprints'];
            ?>
            <set value="<?php echo $success; ?>"/><?php } ?>
    </dataset>
    <dataset seriesName="Failed" showValues="0">
        <?php
        for ($i = 1; $i <= 12; $i++) {
            //Failed ones
            $rs3 = mysql_query("CALL Getsmsqueuebyfacilitymth($facilityID,$year,$i,'" . Failed . "', @numprints)");
            $rs3 = mysql_query("SELECT @numprints as 'numprints'");
            $d3 = mysql_fetch_array($rs3);
            $failed = $d3['numprints'];
            ?>
            <set value="<?php echo $failed; ?>"/><?php } ?>
    </dataset>
    <dataset seriesName="Queued" showValues="0">
        <?php
        for ($i = 1; $i <= 12; $i++) {
            //Queued ones
            $rs4 = mysql_query("CALL Getsmsqueuebyfacilitymth($facilityID,$year,$i,'" . Queued . "', @numprints)");
            $rs4 = mysql_query("SELECT @numprints as 'numprints'");
            $d4 = mysql_fetch_array($rs4);
            $queued = $d4['numprints'];
            ?>
            <set value="<?php echo $queued; ?>"/><?php } ?>
    </dataset>
    <dataset seriesName="In Progress" showValues="0">
        <?php
        for ($i = 1; $i <= 12; $i++) {
            //InProgress ones
            $rs5 = mysql_query("CALL Getsmsqueuebyfacilitymth($facilityID,$year,$i,'" . InProgress . "', @numprints)");
            $rs5 = mysql_query("SELECT @numprints as 'numprints'");
            $d5 = mysql_fetch_array($rs5);
            $inProgress = $d5['numprints'];
            ?>
            <set value="<?php echo $inProgress; ?>"/><?php } ?>
    </dataset>
</chart>
