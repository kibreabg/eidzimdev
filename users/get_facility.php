<?php
session_start();
$labss=$_SESSION['lab'];
	include("../connection/config.php");
$q = strtolower($_GET["q"]);
if (!$q) return;

$sql = "select ID,name from facilitys where name LIKE '$q%' AND facilitys.lab='$labss'";
$rsd = mysql_query($sql);
while($rs = mysql_fetch_array($rsd)) {
	$cid = $rs['ID'];
	$cname = $rs['name']  ;

	echo "$cname|$cid\n";
}
?>
