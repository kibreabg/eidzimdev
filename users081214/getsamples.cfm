<?php
	include("../connection/config.php");
$q = strtolower($_GET["q"]);
if (!$q) return;

$sql = "select scode,pid from sample where pid LIKE '$q%'";
$rsd = mysql_query($sql);
while($rs = mysql_fetch_array($rsd)) {
	$cid = $rs['scode'];
	$cname = $rs['pid'] ;
	echo "$cname|$cid\n";
}
?>
