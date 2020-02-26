<?php
//include("../connection/config.php");
include("../nationaldashboardfunctions.php");
$currentyear=$_GET['mwaka'];
$currentmonth=$_GET['mwezi'];


 //tested samples overall result negative
$rsu = mysql_query( "CALL Getnationaloutcome($currentyear,$currentmonth,1, @numsamples)" );
$rsu = mysql_query( "SELECT @numsamples as 'numsamples'" );
$du=mysql_fetch_array($rsu);
$negative=$du['numsamples'];

//tested samples overall result positive
$rsi = mysql_query( "CALL Getnationaloutcome($currentyear,$currentmonth,2, @numsamples)" );
$rsi = mysql_query( "SELECT @numsamples as 'numsamples'" );
$di=mysql_fetch_array($rsi);
$positive=$di['numsamples'];

//tested samples overall result failed
$rsr = mysql_query( "CALL Getnationaloutcome($currentyear,$currentmonth,3, @numsamples)" );
$rsr = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dr=mysql_fetch_array($rsr);
$fail=$dr['numsamples'];

//overall rejected samples
$rsw = mysql_query( "CALL Getnationalrejectedsamples($currentyear,$currentmonth, @numsamples)" );
$rsw = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dw=mysql_fetch_array($rsw);
$overallrejectedsamples=$dw['numsamples'];

$totalrejectedfailed=$overallrejectedsamples + $fail;
$totaloutcome=$totalrejectedfailed + $positive + $negative;

if ($positive !=0)
{
$pospecentage=(($positive / $totaloutcome)* 100);
}
else
{
$pospecentage=0;
}


if ($negative !=0)
{
$negpecentage=(($negative / $totaloutcome)* 100);
}
else
{
$negpecentage=0;
}


if ($totalrejectedfailed !=0)
{
$rejpecentage=(($totalrejectedfailed / $totaloutcome)* 100);
}
else
{
$rejpecentage=0;
}
?>
<chart palette='3' caption=''  showValues='1' decimals='0' formatNumberScale='0' useRoundEdges='1' showBorder="0"  bgColor='FFFFFF'  smartLineThickness='2' smartLineColor='333333' isSmartLineSlanted='1'>
  <set label="Negative" value="<?php echo $negative   ; ?>"    /> 
  <set label="Positive" value="<?php echo $positive  ;?>" isSliced="1"/> 
 <!-- <set label="Rejected" value="<?php //echo $totalrejectedfailed   ;?>" /> -->
  <set label="Rejected" value="<?php echo $overallrejectedsamples   ;?>" /> 
  <!--<set label="Failed" value="<?php //echo $fail   ;?>" /> -->
</chart>