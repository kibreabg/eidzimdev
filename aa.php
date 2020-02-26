<?php
include("header.php");

	require_once('connection/config.php'); 
//include("FusionMaps/FusionMaps.php");
//include("FusionCharts/FusionCharts.php");

$currentyear=2012;
$currentmonth=10;

//$natAge=Getoverallaverageage($currentyear,$currentmonth);



//overall tested samples





//overall tested samples less than 2 months
$rss = mysql_query( "CALL Gettestedsamplescountlessthan2months($currentyear,$currentmonth, @numsamples)" );
$rss = mysql_query( "SELECT @numsamples as 'numsamples'" );
$ds=mysql_fetch_array($rss);
$overalltestedsamplesless2months=$ds['numsamples'];
echo "11".$overalltestedsamplesless2months. "<br>";
//overall tested samples (first test only)
$rs7 = mysql_query( "CALL Getoverallfirsttestedsamples($currentyear,$currentmonth, @numsamples)" );
$rs7 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dt7=mysql_fetch_array($rs7);
$overallfirsttestedsamples=$dt7['numsamples'];

echo "pp".$overallfirsttestedsamples;

?>