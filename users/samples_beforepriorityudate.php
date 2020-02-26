<!DOCTYPE html>
<?php
session_start();
require_once('../connection/config.php');
include('../includes/header.php');
?>
<script src="jquery-1.9.1.min.js"></script>
<link href="base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="demos.css">
<script type="text/javascript">
    function getLabs(labinfo) {

        var separator = labinfo.indexOf("_");
        var labid = labinfo.slice(0, separator);
        var labtype = labinfo.slice(separator + 1, labinfo.length);

        if (labtype == '0') {
            window.location.href = "addsampleswithoutresult.php?view=1&labid=" + labid;
        } else if (labtype == '1') {
            window.location.href = "addsampleswithresult.php?view=1&labid=" + labid;
        } else {
            window.location.href = "addsamplesrejected.php?view=1&labid=" + labid;
        }
    }
</script>

<div class="section">
    <div class="section-title">Choose Laboratory</div>
    <div style="width: 100%; text-align: center;">    
        <span>Lab Type:</span>&nbsp;&nbsp;
        <select name="labs" id="labs" onchange="getLabs(this.value)">
            <option value="">--Select Lab--</option>
<?php
$qry = "SELECT * FROM labs";
$result = mysql_query($qry);
if ($result) {
    while ($row = mysql_fetch_array($result)) {
        echo "<option value='{$row['ID']}_{$row['withresult']}'>{$row['name']}</option>";
    }
} else {
    die("failed");
}
?>
        </select>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
