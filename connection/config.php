<?php
$mysql_link = mysql_connect("localhost", "root", "123"); 
mysql_select_db("eid_zim_dev") or die("Could not select database");


$host="http://sms2printer.com/manage/api/";

define("SMSHOST", $host);

//The first parameter is the variable name, and the second parameter is the value
define("Successful", "SUCCESSFUL");
define("Failed", "FAILED");
define("Queued", "QUEUED");
define("InProgress", "INPROGRESS");
?>
