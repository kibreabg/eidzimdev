<?php
require_once('../connection/config.php');
$mwaka = $_GET['mwaka'];
$mwezi = $_GET['mwezi'];
?>
<chart lineThickness="1" showValues="0" formatNumberScale="0" anchorRadius="2" divLineAlpha="20" divLineColor="CC3300" divLineIsDashed="1" showAlternateHGridColor="1" alternateHGridAlpha="5" alternateHGridColor="CC3300" shadowAlpha="40" labelStep="0" numvdivlines="5" chartRightMargin="35"  bgAngle="270" bgAlpha="10,10" bgColor='#FFFFFF' showBorder='0' overlapColumns="0" useRoundEdges="1">
    <categories>
        <?php
        $sql = "select Code,name from provinces";
        $result = mysql_query($sql) or die(mysql_error());
        $result2 = mysql_query($sql) or die(mysql_error());
        $result3 = mysql_query($sql) or die(mysql_error());
        $result4 = mysql_query($sql) or die(mysql_error());
        $result5 = mysql_query($sql) or die(mysql_error());

        while ($row = mysql_fetch_array($result)) {
            $provid = $row['Code'];
            $Prov = trim($row['name']);
            ?>
            <category label="<?php echo $Prov; ?>"/><?php } ?>
    </categories>

    <dataset seriesName="Success" showValues="0">
        <?php
        while ($row = mysql_fetch_array($result2)) {
            $provid = $row['Code'];
            $prov = trim($row['name']);

            //Successful ones
            $rs2 = mysql_query("CALL Getsmsqueuebyprovince($provid,$mwaka,$mwezi,'" . Successful . "', @numprints)");
            $rs2 = mysql_query("SELECT @numprints as 'numprints'");
            $d2 = mysql_fetch_array($rs2);
            $success = $d2['numprints'];
            ?>
            <set value="<?php echo $success; ?>"/><?php } ?>
    </dataset>
    <dataset seriesName="Failed" showValues="0">
        <?php
        while ($row = mysql_fetch_array($result3)) {
            $provid = $row['Code'];
            $prov = trim($row['name']);

            //Failed ones
            $rs3 = mysql_query("CALL Getsmsqueuebyprovince($provid,$mwaka,$mwezi,'" . Failed . "', @numprints)");
            $rs3 = mysql_query("SELECT @numprints as 'numprints'");
            $d3 = mysql_fetch_array($rs3);
            $failed = $d3['numprints'];
            ?>
            <set value="<?php echo $failed; ?>"/><?php } ?>
    </dataset>
    <dataset seriesName="Queued" showValues="0">
        <?php
        while ($row = mysql_fetch_array($result4)) {
            $provid = $row['Code'];
            $prov = trim($row['name']);

            //Queued ones
            $rs4 = mysql_query("CALL Getsmsqueuebyprovince($provid,$mwaka,$mwezi,'" . Queued . "', @numprints)");
            $rs4 = mysql_query("SELECT @numprints as 'numprints'");
            $d4 = mysql_fetch_array($rs4);
            $queued = $d4['numprints'];
            ?>
            <set value="<?php echo $queued; ?>"/><?php } ?>
    </dataset>
    <dataset seriesName="In Progress" showValues="0">
        <?php
        while ($row = mysql_fetch_array($result5)) {
            $provid = $row['Code'];
            $prov = trim($row['name']);

            //InProgress ones
            $rs5 = mysql_query("CALL Getsmsqueuebyprovince($provid,$mwaka,$mwezi,'" . InProgress . "', @numprints)");
            $rs5 = mysql_query("SELECT @numprints as 'numprints'");
            $d5 = mysql_fetch_array($rs5);
            $inProgress = $d5['numprints'];
            ?>
            <set value="<?php echo $inProgress; ?>"/><?php } ?>
    </dataset>
</chart>
