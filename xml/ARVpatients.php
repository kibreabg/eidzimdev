<?php
//include("../connection/config.php");
include("../nationaldashboardfunctions.php");
$currentyear=$_GET['mwaka'];
$currentmonth=$_GET['mwezi'];
$onarv=GetinfantsonARVs(1,$currentyear,$currentmonth);
$notonarv=GetinfantsonARVs(2,$currentyear,$currentmonth);
$unknwonarv=GetinfantsonARVs(3,$currentyear,$currentmonth);
$total=$onarv + $notonarv + $unknwonarv;
IF ($onarv != $total)
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
<color minValue="0" maxValue="<?php  echo $onarv;?>"  label="Yes" />
<color minValue="<?php  echo $onarv;?>" maxValue="<?php  echo $onarv + $notonarv ;?>" label="No" />
<color minValue="<?php  echo $onarv + $notonarv ;?>" maxValue="<?php  echo $onarv + $notonarv + $unknwonarv;?>" label="Unknw" />
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