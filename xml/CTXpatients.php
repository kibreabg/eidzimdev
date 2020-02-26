<?php
//include("../connection/config.php");
include("../nationaldashboardfunctions.php");
$currentyear=$_GET['mwaka'];
$currentmonth=$_GET['mwezi'];
$onctx=GetinfantsonCTX('Y',$currentyear,$currentmonth);
$notonctx=GetinfantsonCTX('N',$currentyear,$currentmonth);
$unknwonctx=GetinfantsonCTX('U',$currentyear,$currentmonth);
$total=$onctx + $notonctx + $unknwonctx;
IF ($onctx != $total)
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
<color minValue="0" maxValue="<?php  echo $onctx;?>"  label="Yes" />
<color minValue="<?php  echo $onctx;?>" maxValue="<?php  echo $onctx + $notonctx ;?>" label="No" />
<color minValue="<?php  echo $onctx + $notonctx ;?>" maxValue="<?php  echo $onctx + $notonctx + $unknwonctx;?>" label="Unknw" />
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