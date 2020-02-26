<?php
include("../nationaldashboardfunctions.php");
$currentyear=$_GET['mwaka'];
$currentmonth=$_GET['mwezi'];
$province=$_GET['province'];
$age=Getprovincialaverageage($province,$currentyear,$currentmonth);
?>
<chart lowerLimit='0' bgColor='#FFFFFF' showBorder='0' tickValueDistance='20'upperLimit='24' lowerLimitDisplay='0'
 upperLimitDisplay='24' gaugeStartAngle='180' gaugeEndAngle='0' palette='3' numberSuffix='weeks' tickValueDistance='1' showValue='1'>
<colorRange>
<color minValue='0' maxValue='6' code='FF654F'/>
<color minValue='6' maxValue='18' code='F6BD0F'/>
<color minValue='18' maxValue='24' code='8BBA00'/>
</colorRange><dials><dial value='<?php echo  $age;?>'  rearExtension='10' baseWidth='4' /></dials></chart>