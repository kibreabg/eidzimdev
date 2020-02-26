<?php
include("../nationaldashboardfunctions.php");
//require_once('connection/config.php'); 
$lab=$_GET['lab'];
$currentyear=$_GET['mwaka'];
$currentmonth=$_GET['mwezi'];
$startdate=$_GET['startmonth'];
$enddate=$_GET['endmonth'];

//echo $currentyear . " = ". $currentmonth .  " = " .$startdate . " & ".$enddate;s

//GetReceivedatLabtoProcessingTAT
list($numsamples, $ave) = GetCollectiontoReceivedatLabTAT($lab,$currentmonth,$currentyear,$startdate,$enddate); 


//GetReceivedatLabtoProcessingTAT
list($numsamples2, $ave2) = GetReceivedatLabtoProcessingLABTAT($lab,$currentmonth,$currentyear,$startdate,$enddate); 

//GetProcessedatLabtoDispatchTAT
list($numsamples3, $ave3) = GetProcessedatLabtoDispatchLABTAT($lab,$currentmonth,$currentyear,$startdate,$enddate); 

//received to dispatch overall
 //list($numsamples4, $ave4) = GetReceivedtoDispatchLABTAT($lab,$currentmonth,$currentyear,$startdate,$enddate); 
 //collection to dispatch overall
 list($numsamples5, $ave5) = GetcollectiontoDispatchLABTAT($lab,$currentmonth,$currentyear,$startdate,$enddate); 


$total=$ave +  $ave2+  $ave3;
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
$upperlimit=round($ave + $ave2 + $ave3);
?>

<Chart bgColor="FFFFFF" bgAlpha="0" showBorder="0" upperLimit="<?php echo (round($ave) + round($ave2) + round($ave3)); ?>" lowerLimit="0" gaugeRoundRadius="5" chartBottomMargin="10" ticksBelowGauge="1" showGaugeLabels="1" valueAbovePointer="1" pointerOnTop="1" pointerRadius="9" tickValueStep='2' majorTMNumber='12' minorTMNumber='3' forceTickValueDecimals='0' tickValueDecimals='0' >

<colorRange>
<color minValue="0" maxValue="<?php echo round($ave); ?>"  code='FF654F' label=' <?php  echo round($ave);?> '  />
<color minValue="<?php echo round($ave); ?>" maxValue="<?php echo  round($ave + $ave2); ?>" code='F6BD0F' label='<?php  echo round($ave2);?> '/>
<color minValue="<?php echo round($ave + $ave2); ?>" maxValue="<?php echo  round($ave + $ave2 + $ave3); ?>" code='8BBA00' label='<?php  echo round($ave3);?> '/>
</colorRange>


<pointers>
   <pointer value='<?php echo  $ave5; ?>' toolText='Total Turn Around Time' link="P-detailsWin,width=450,height=150,toolbar=no,scrollbars=no, resizable=no-provincialtatbreakdown.php?province=<?php echo $province;?>%26mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>%26dcode=<?php echo $dcode;?>%26fcode=<?php echo $fcode;?>" />
 
</pointers>


<styles>

<definition>
<style name="ValueFont" type="Font" bgColor="333333" size="10" color="FFFFFF"/>
</definition>
<application>
<apply toObject="VALUE" styles="valueFont"/>
</application>
</styles>
</Chart>