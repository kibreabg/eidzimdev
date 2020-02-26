<?php
//include("../connection/config.php");
include("../nationaldashboardfunctions.php");
$currentyear=$_GET['mwaka'];
$currentmonth=$_GET['mwezi'];
$y=Getinfantsonfeeding(1,$currentyear,$currentmonth);
$n=Getinfantsonfeeding(2,$currentyear,$currentmonth);
$unk=Getinfantsonfeeding(3,$currentyear,$currentmonth);
$total=$y + $n + $unk;
IF ($y != $total)
{
$modulus=$total%3;
if (  $modulus !=0)
{
$extra=3 - $modulus;
$total=$total + $extra;
}
}
?>
<Chart bgColor="FFFFFF" bgAlpha="0" showBorder="0" upperLimit="<?php echo  $total; ?>" lowerLimit="0" gaugeRoundRadius="5" chartBottomMargin="10" ticksBelowGauge="1" showGaugeLabels="1" valueAbovePointer="1" pointerOnTop="1" pointerRadius="9" >

<colorRange>
<color minValue="0" maxValue="<?php  echo $y;?>"  label="Yes" />
<color minValue="<?php  echo $y;?>" maxValue="<?php  echo $y + $n ;?>" label="No" />
<color minValue="<?php  echo $y + $n ;?>" maxValue="<?php  echo $y + $n + $unk;?>" label="Unknw" />
</colorRange>


<styles>

<definition>
<style name="ValueFont" type="Font" bgColor="333333" size="10" color="FFFFFF"/>
</definition>

<application>
<apply toObject="VALUE" styles="valueFont"/>
</application>
</styles>
</Chart>