<?php
include("../nationaldashboardfunctions.php");
//require_once('connection/config.php'); 
$province=$_GET['province'];
$currentyear=$_GET['mwaka'];
$currentmonth=$_GET['mwezi'];
$dcode=$_GET['dcode'];
$fcode=$_GET['fcode'];

//echo $currentyear;
//collection to receive
  list($numsamples, $ave) = GetColletiontoReceivedatLabTAT($province,$currentmonth,$currentyear,$dcode,$fcode); 

//GetReceivedatLabtodispatchTAT
list($numsamples5, $ave5) = GetReceivedatLabtoDispatchTAT($province,$currentmonth,$currentyear,$dcode,$fcode); 

/*
//GetReceivedatLabtoProcessingTAT
list($numsamples2, $ave2) = GetReceivedatLabtoProcessingTAT($province,$currentmonth,$currentyear,$dcode,$fcode); 
  //echo $ave2 . " days [Samples:".$numsamples2 . " - ("; 
if ($numsamples !=0) 
{ 
$p2= round((($numsamples2/$receivedsamples)*100),2) . "% of Received Samples) ]";
}
else
{
$p2= "0% of Received Samples) ]";
} 


//GetProcessedatLabtoDispatchTAT
list($numsamples3, $ave3) = GetProcessedatLabtoDispatchTAT($province,$currentmonth,$currentyear,$dcode,$fcode); 
 // echo $ave3 . " days [Samples:".$numsamples3 . " - ("; 
if ($numsamples !=0) 
{ 
$p3= round((($numsamples3/$receivedsamples)*100),2) . "% of Received Samples) ]";
}
else
{
$p3= "0% of Received Samples) ]";
}

*/
//collection to dispatch overall
 list($numsamples4, $ave4) = GetCollectedtoDispatchTAT($province,$currentmonth,$currentyear,$dcode,$fcode); 
// echo $ave4 . " days [Samples:".$numsamples4 . " - ("; 


$total=$ave +  $ave5;
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
<Chart bgColor="FFFFFF" bgAlpha="0" showBorder="0" upperLimit="<?php echo  $upperlimit; ?>" lowerLimit="0" gaugeRoundRadius="5" chartBottomMargin="10" ticksBelowGauge="1" showGaugeLabels="1" valueAbovePointer="1" pointerOnTop="1" pointerRadius="9" >

<colorRange>
<color minValue="0" maxValue="<?php echo round($ave); ?>" label='<?php  echo round($ave);?> days '  />
<color minValue="<?php echo round($ave); ?>" maxValue="<?php echo  round($ave + $ave5); ?>" label='<?php  echo round($ave5);?> days'/>

</colorRange>
<pointers>
   <pointer value='<?php echo  $ave4; ?>' toolText='Total Turn Around Time'  />
 
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