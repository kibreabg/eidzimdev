<?php
session_start();
$province=$_GET['province']; // Use this line or below line if register_global is off
	//require_once("../config.php");
	/*$res=mysql_connect($mysql_server,$mysql_user,$mysql_pass);
	mysql_select_db($mysql_db);*/
	
	if ($province ==1)
	{
	$start=5;
	$end=9;
	}
	else if ($province ==2)
	{
	$start=10;
	$end=16;
	}
	else if ($province ==3)
	{
	$start=17;
	$end=24;
	}
	else if ($province ==4)
	{
	$start=25;
	$end=33;
	}
	else if ($province ==5)
	{
	$start=34;
	$end=39;
	}
	else if ($province ==6)
	{
	$start=47;
	$end=53;
	}
	else if ($province ==7)
	{
	$start=54;
	$end=60;
	
	}
	else if ($province ==8)
	{
	$start=61;
	$end=68;
	}
	else if ($province ==9)
	{
	$start=40;
	$end=46;
	}
		else if ($province ==10)
	{
	$start=1;
	$end=4;
	}
	$mysql_link = mysql_connect("localhost", "root", ""); 
	mysql_select_db("eid_zim") or die("Could not select database");
	require("users/combo_connector.php");
	$combo = new ComboConnector($mysql_link);
	$combo->enable_log("temp.log");
	//$combo->render_sql("SELECT * FROM country_data  WHERE country_id >40 ","country_id","name");
	//$combo->render_sql("SELECT ID,name  FROM facilitys where  districts BETWEEN  53 AND 55 ","ID","name" );
	
	
	$combo->render_sql("SELECT ID,name  FROM facilitys WHERE district BETWEEN $start AND $end ","ID","name" );
	
	
	
	
?>