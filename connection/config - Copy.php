<?php
$mysql_link = mysql_connect("localhost", "root", "1qEJ6M3Nn3XbhJH9djG8KE6eXxbLkJwpoEhsiMzM2WoE2DlGQCN4c948VJf1root"); 
mysql_select_db("eid_zim") or die("Could not select database");
//mysql_select_db("eid_mutare") or die("Could not select database");
//mysql_select_db("eid_zim_manual") or die("Could not select database");


$host="http://192.168.0.6/manage/api/";

define("SMSHOST", $host);

//The first parameter is the variable name, and the second parameter is the value
define("Successful", "SUCCESSFUL");
define("Failed", "FAILED");
define("Queued", "QUEUED");
define("InProgress", "INPROGRESS");
?>
