<?php
session_start();
	include("../connection/config.php");
$q = strtolower($_GET["q"]);
$labss=$_SESSION['lab'];
if (!$q) return;

$sql = "select DISTINCT(samples.patient) AS 'patient'  FROM samples
					WHERE samples.patient LIKE '$q%' AND samples.lab='$labss' AND samples.flag=1";
$rsd = mysql_query($sql)or die(mysql_error());
while($rs = mysql_fetch_array($rsd)) {
	$cid = $rs['ID'];
	$cname = $rs['patient'] ;
	echo "$cname|$cid\n";
	
	
	
}
?>
