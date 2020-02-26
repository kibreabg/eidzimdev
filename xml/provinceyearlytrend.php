<?php
include("../nationaldashboardfunctions.php");
$province=$_GET['province'];
$currentyear=$_GET['mwaka'];
$currentmonth=$_GET['mwezi'];
$dcode=$_GET['dcode'];
$fcode=$_GET['fcode'];



//echo $jan . " - " .$feb. " - ".$mar. " -".$april . " - ".$may;;
?>
<chart caption="" subcaption="" xAxisName="Month" yAxisName="Tests" yAxisMinValue="15000" numberPrefix="" showValues="0" alternateHGridColor="FCB541" alternateHGridAlpha="20" divLineColor="FCB541" divLineAlpha="50" canvasBorderColor="666666" baseFontColor="666666" lineColor="FCB541" bgColor='#FFFFFF' showBorder='0' formatNumberScale="0">

<?php
for($i = 1; $i < 13; $i++)
    {
	$displaymonth=GetMonthName($i);
	//overall tested samples

$teststotal=Getalltestedprovincesamplescount($province,$currentyear,$i,$dcode,$fcode) ; 
	?>
<set label="<?php echo $displaymonth ;?>" value="<?php echo $teststotal; ?>"/>
<?php
	
	
	
	}

?>
<styles>

<definition>
<style name="Anim1" type="animation" param="_xscale" start="0" duration="1"/>
<style name="Anim2" type="animation" param="_alpha" start="0" duration="0.6"/>
<style name="DataShadow" type="Shadow" alpha="40"/>
</definition>
-
<application>
<apply toObject="DIVLINES" styles="Anim1"/>
<apply toObject="HGRID" styles="Anim2"/>
<apply toObject="DATALABELS" styles="DataShadow,Anim2"/>
</application>
</styles>
</chart>