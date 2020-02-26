<?php
include("../nationaldashboardfunctions.php");
//require_once('connection/config.php'); 
$province=$_GET['province'];
$currentyear=$_GET['mwaka'];
$currentmonth=$_GET['mwezi'];
$dcode=$_GET['dcode'];
$fcode=$_GET['fcode'];

$positive=Getprovincepositivitycount($province,2,$currentyear,$currentmonth,$dcode,$fcode);
$negative=Getprovincepositivitycount($province,1,$currentyear,$currentmonth,$dcode,$fcode);
$failed=Getprovincepositivitycount($province,3,$currentyear,$currentmonth,$dcode,$fcode);
$indeterminate=Getprovincepositivitycount($province,5,$currentyear,$currentmonth,$dcode,$fcode);
$rejected=Getprovincerejectedsamples($province,$currentyear,$currentmonth,$dcode,$fcode);
$totalrejectedfailed=$rejected;

$totaloutcome=$positive+$negative+$totalrejectedfailed+$indeterminate;
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


if ($indeterminate !=0)
{
$indeterminatepecentage=(($indeterminate / $totaloutcome)* 100);
}
else
{
$indeterminatepecentage=0;
}

?>
<chart palette='3' caption=''  showValues='1' decimals='0' formatNumberScale='0' useRoundEdges='1' showBorder="0"  bgColor='FFFFFF'  smartLineThickness='2' smartLineColor='333333' isSmartLineSlanted='1'>
  <set label="Negative" value="<?php echo $negative   ; ?>"    /> 
  <set label="Positive" value="<?php echo $positive  ;?>" isSliced="1"/> 
  <set label="Indeterminate" value="<?php echo $indeterminate   ;?>" /> 
  <set label="Rejected" value="<?php echo $totalrejectedfailed   ;?>" /> 
</chart>