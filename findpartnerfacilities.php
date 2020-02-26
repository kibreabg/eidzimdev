<?php
session_start();
$partner=$_SESSION['partner'];
$province=$_GET['province'];
	
	$mysql_link = mysql_connect("localhost", "root", ""); 
	mysql_select_db("eid_zim") or die("Could not select database");
	require("users/combo_connector.php");
	$combo = new ComboConnector($mysql_link);
	$combo->enable_log("temp.log");
	//$combo->render_sql("SELECT * FROM country_data  WHERE country_id >40 ","country_id","name");

		$combo->render_sql("SELECT ID,name  FROM facilitys where  flag=1","ID","name" );
	//$combo->render_sql("SELECT ID,name  FROM facilitys where  partner='$partner' AND district='2'","ID","name" );
	
?>