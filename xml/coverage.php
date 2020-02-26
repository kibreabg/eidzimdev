<?php
include("../connection/config.php");
$currentyear=$_GET['mwaka'];
$currentmonth=$_GET['mwezi'];
//$natAge=Getoverallaverageage($currentyear,$currentmonth);

$rs4 = mysql_query( "CALL Getoverallaverageage($currentyear,$currentmonth, @averageage)" );
$rs4 = mysql_query( "SELECT @averageage as 'averageage'" );
$d4=mysql_fetch_array($rs4);
$natAge=round($d4['averageage'],1);

?><chart lowerLimit='0' bgColor='#FFFFFF' showBorder='0' tickValueDistance='25'upperLimit='18' lowerLimitDisplay='6wks' upperLimitDisplay='18mths' gaugeStartAngle='180' gaugeEndAngle='0' palette='3' numberSuffix='mths' tickValueDistance='20' showValue='1'>
<colorRange>
<color minValue='0' maxValue='10' code='8BBA00'/>
<color minValue='10' maxValue='15' code='FF654F'/>
<color minValue='15' maxValue='18' code='F6BD0F'/>
</colorRange><dials><dial value='<?php echo $natAge ; ?>'  rearExtension='10' baseWidth='4' /></dials></chart>