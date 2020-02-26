<?php
include("../connection/config.php");
include("../FusionMaps/FusionMaps.php");
include("../FusionCharts/FusionCharts.php");
include("../includes/labdashboardfunctions.php");
$labss=$_GET['labss'];
$currentmonth=$_GET['currentmonth'];
$displaymonth=GetMonthName($currentmonth);//month fullnames
$currentyear=$_GET['currentyear'];
$province=$_GET['province'];
$provincequery=mysql_query("select Name from provinces where ID='$province'")or die(mysql_error());
$provarray=mysql_fetch_array($provincequery);
$provincename=$provarray['Name'];
$district=$_GET['district'];
$districtquery=mysql_query("select Name from districts where ID='$district'")or die(mysql_error());
$districtarray=mysql_fetch_array($districtquery);
$districtname=$districtarray['Name'];
if (isset($currentmonth))
	{
	$defaultmonth=$displaymonth .' - '.$currentyear ; //get current month and year
	}
	else
	{
	$defaultmonth=$currentyear ; //get current month and year

	
	}
?><head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<meta name="description" content=""/>
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
	<title>EID: Early Infant Diagonistics System</title>
</head>

	<link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
<SCRIPT LANGUAGE="Javascript" SRC="../FusionMaps/JSClass/FusionMaps.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="../FusionCharts/FusionCharts.js"></SCRIPT>
<script language="JavaScript" src="../FusionWidgets/FusionCharts.js"></script>
			 <div class="section-title"> Received Samples from  <?php echo $districtname; ?> District, in <?php echo $provincename; ?> Province,  for <?php echo $defaultmonth; ?></div>

      <?php

 //Generate the chart element string
 $strXML = "<chart palette='3' bgColor='#FFFFFF' showBorder='0'  xAxisName='Samples' showValues='1' labelStep='1' >";

if ($currentmonth !="")
{
	
		$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'TotalTests',facilitys.ID as 'fcode',facilitys.name as 'facname' FROM samples,facilitys WHERE samples.facility=facilitys.ID AND facilitys.lab='$labss'  AND facilitys.district='$district'   AND YEAR(samples.datereceived)='$currentyear' AND MONTH(samples.datereceived)='$currentmonth' GROUP BY facilitys.name")or die(mysql_error());
	
}
else
{
	$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'TotalTests',facilitys.ID as 'fcode',facilitys.name as 'facname' FROM samples,facilitys WHERE samples.facility=facilitys.ID AND facilitys.lab='$labss'  AND facilitys.district='$district'   AND YEAR(samples.datereceived)='$currentyear'  GROUP BY facilitys.name ")or die(mysql_error());
	
}


  if ($strQuery)
    {
      while($ors = mysql_fetch_array($strQuery)) {
	  $fcode=$ors['fcode'];
	   $facname=$ors['facname'];
		
		            //Here, we convert date into a more readable form for set label.

$strXML .= "<set label='" . $ors['facname'] . "' value='" . $ors['TotalTests']  . "' link='" . urlencode("n-sampledrilldown.php?facility=" .  $fcode) . "&currentyear=".$currentyear. "&district=".$districtname."&currentmonth=".$currentmonth."&province=".$provincename."&fname=".$facname." '/>";

		
      }
   }
			 

    //Close <chart> element
    $strXML .= "</chart>";
	
    //Create the chart - Column 2D Chart with data from strXML
    echo renderChart("../FusionCharts/pie2D.swf", "", $strXML, "samplesResult", 500, 380, false, true);
?>