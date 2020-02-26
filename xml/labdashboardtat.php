<?php
include("../includes/labdashboardfunctions.php");
//require_once('connection/config.php'); 
$labss=$_GET['lab'];
$currentyear=$_GET['mwaka'];
$currentmonth=$_GET['mwezi'];


list($numsamples, $ave) = GetColletiontoReceivedatLabTAT($labss,$currentmonth,$currentyear);


list($numsamples2, $ave2) = GetReceivedatLabtoTestingTAT($labss,$currentmonth,$currentyear);

list($numsamples3, $ave3) = GetTestedatLabtoUpdateTAT($labss,$currentmonth,$currentyear);


list($numsamples4, $ave4) = GetUpdatedResultsatLabtoReleaseforPrintingTAT($labss,$currentmonth,$currentyear);

list($numsamples5, $ave5) = GetReleaseforPrintingatLabtoDispatchTAT($labss,$currentmonth,$currentyear);


list($numsamples6, $ave6) = GetCollectedtoDispatchTAT($labss,$currentmonth,$currentyear);

$total=$ave + $ave2 + $ave3 + $ave4  + $ave5;
$extra=ceil($total)%5;
if ( $extra!= 0)
{
$bonus=5-$extra;
$upperlimit=ceil($total) + $bonus;
}
else
{
$upperlimit=ceil($total);
}

?>
<Chart bgColor="FFFFFF" bgAlpha="0" showBorder="0" upperLimit="<?php echo round($ave + $ave2 + $ave3 + $ave4  + $ave5);//  $upperlimit; ?>" lowerLimit="0" gaugeRoundRadius="5" chartBottomMargin="10" ticksBelowGauge="1" showGaugeLabels="1" valueAbovePointer="1" pointerOnTop="1" pointerRadius="9" >

<colorRange>
<color minValue="0" maxValue="<?php echo round($ave); ?>"  code='FF654F' label=' <?php  echo round($ave);?>  '  />
<color minValue="<?php echo round($ave); ?>" maxValue="<?php echo  round($ave + $ave2); ?>" code='F6BD0F' label='<?php  echo round($ave2);?> '/>
<color minValue="<?php echo round($ave + $ave2); ?>" maxValue="<?php echo  round($ave + $ave2 + $ave3); ?>" code='8BBA00' label='<?php  echo round($ave3);?> '/>
<color minValue="<?php echo round($ave + $ave2 + $ave3); ?>" maxValue="<?php echo  round($ave + $ave2 + $ave3 + $ave4); ?>"  label='<?php  echo round($ave4);?> '/>
<color minValue="<?php echo round($ave + $ave2 + $ave3 + $ave4); ?>" maxValue="<?php echo  round($ave + $ave2 + $ave3 + $ave4  + $ave5); ?>"  label='<?php  echo round($ave5);?> '/>
</colorRange>
<!--<pointers>
   <pointer value='<?php //echo $ave6; ?>' toolText='Total Turn Around Time'  />
 
</pointers> -->


<styles>

<definition>
<style name="ValueFont" type="Font" bgColor="333333" size="10" color="FFFFFF"/>
</definition>
<application>
<apply toObject="VALUE" styles="valueFont"/>
</application>
</styles>
</Chart>