<?php

session_start();

$mysql_link = mysql_connect("localhost", "root", "1qEJ6M3Nn3XbhJH9djG8KE6eXxbLkJwpoEhsiMzM2WoE2DlGQCN4c948VJf1root");
mysql_select_db("eid_zim") or die("Could not select database");
require("combo_connector.php");
$combo = new ComboConnector($mysql_link);
$combo->enable_log("temp.log");
$combo->render_sql("SELECT ID,name FROM facilitys WHERE district = {$_GET['dID']}", "ID", "name");
?>