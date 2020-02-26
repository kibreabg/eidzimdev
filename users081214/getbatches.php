<?php
session_start();
	include("../connection/config.php");
$q = strtolower($_GET["q"]);
$labss=$_SESSION['lab'];
if (!$q) return;

$sql = "select DISTINCT samples.batchno,facilitys.name  FROM samples,facilitys
					WHERE samples.batchno LIKE '$q%' AND samples.facility=facilitys.ID AND samples.lab='$labss'";
$rsd = mysql_query($sql)or die(mysql_error());
while($rs = mysql_fetch_array($rsd)) {
	$cid = $rs['batchno'];
	$cname = $rs['name'] ;
	echo "$cid-$cname|$cid\n";

}
?>
