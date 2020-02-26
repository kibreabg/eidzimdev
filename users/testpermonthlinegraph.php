<?php
include("../connection/config.php");
include("../includes/labdashboardfunctions.php");
$labss=$_GET['labss'];
$currentyear=$_GET['currentyear'];
?>

<chart caption="" subcaption="" xAxisName="Month" yAxisName="Tests" yAxisMinValue="15000" numberPrefix="" showValues="1" alternateHGridColor="FCB541" alternateHGridAlpha="20" divLineColor="FCB541" divLineAlpha="50" canvasBorderColor="666666" baseFontColor="666666" lineColor="FCB541" bgColor='#FFFFFF' showBorder='0'>
<set label="Jan" value="<?php echo GetTestedSamplesPerlab($labss,1,$currentyear); ?>"/>
<set label="Feb" value="<?php echo GetTestedSamplesPerlab($labss,2,$currentyear); ?>"/>
<set label="Mar" value="<?php echo GetTestedSamplesPerlab($labss,3,$currentyear); ?>"/>
<set label="Apr" value="<?php echo GetTestedSamplesPerlab($labss,4,$currentyear); ?>"/>
<set label="May" value="<?php echo GetTestedSamplesPerlab($labss,5,$currentyear); ?>"/>
<set label="Jun" value="<?php echo GetTestedSamplesPerlab($labss,6,$currentyear); ?>"/>
<set label="Jul" value="<?php echo GetTestedSamplesPerlab($labss,7,$currentyear); ?>"/>
<set label="Aug" value="<?php echo GetTestedSamplesPerlab($labss,8,$currentyear); ?>"/>
<set label="Sep" value="<?php echo GetTestedSamplesPerlab($labss,9,$currentyear); ?>"/>
<set label="Oct" value="<?php echo GetTestedSamplesPerlab($labss,10,$currentyear); ?>"/>
<set label="Nov" value="<?php echo GetTestedSamplesPerlab($labss,11,$currentyear); ?>"/>
<set label="Dec" value="<?php echo GetTestedSamplesPerlab($labss,12,$currentyear); ?>"/>
-
<styles>
-
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