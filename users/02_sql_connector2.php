<?php
	//require_once("../config.php");
	/*$res=mysql_connect($mysql_server,$mysql_user,$mysql_pass);
	mysql_select_db($mysql_db);*/
	$fcode=$_GET['p'];
	$mysql_link = mysql_connect("localhost", "root", ""); 
	mysql_select_db("eid_kemri2") or die("Could not select database");
	
	require("combo_connector.php");
	$combo = new ComboConnector($mysql_link);
//	$combo->enable_log("temp.log");
	//$combo->render_sql("SELECT * FROM country_data  WHERE country_id >40 ","country_id","name");
	
	$combo->render_sql("SELECT ID,name  FROM facilitys where ID='$fcode'","ID","name" );
?>