<?php
session_start();
require_once('../connection/config.php');
include('../includes/functions.php');

//$pID = GetProvid($_GET['pID']);
//$province = GetProvname($pID);

//$districtName = GetDistrictName($dID);
//echo "<span style='color: black; font-weight: bold;'>Province:</span> {$province} AND <span style='color: black; font-weight: bold;'>District:</span> {$district}";
?>
District&nbsp;&nbsp;
<select id="dist" name="district">
    <option value=''> Select One </option>
    <?php
    $distQuery = mysql_query("SELECT * FROM districts WHERE province = '{$_GET['pID']}'");

    while ($district = mysql_fetch_array($distQuery, MYSQL_ASSOC)) {
        $ID = $district['ID'];
        $name = $district['name'];
        echo "<option value='$ID'>{$name}</option>\n";
    }
    ?>
</select>

