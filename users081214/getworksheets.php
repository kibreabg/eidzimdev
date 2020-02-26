<?php
session_start();
	include("../connection/config.php");
$q = strtolower($_GET["q"]);
$labss=$_SESSION['lab'];
if (!$q) return;

$sql = "select worksheets.ID, worksheets.worksheetno  FROM worksheets
					WHERE worksheets.ID LIKE '$q%' AND worksheets.lab='$labss' ";
$rsd = mysql_query($sql)or die(mysql_error());
while($rs = mysql_fetch_array($rsd)) {
	$cid = $rs['ID'];
	$cname = $rs['ID'] ;
	echo "$cname|$cid\n";
	
	
	
}
?>
