<?php
include("../connection/config.php");
include("../includes/labdashboardfunctions.php");
$labss=$_GET['labss'];
$currentyear=$_GET['currentyear'];
?>
<chart lineThickness="1" showValues="0" formatNumberScale="0" anchorRadius="2" divLineAlpha="20" divLineColor="CC3300" divLineIsDashed="1" showAlternateHGridColor="1" alternateHGridAlpha="5" alternateHGridColor="CC3300" shadowAlpha="40" labelStep="0" numvdivlines="5" chartRightMargin="35"  bgAngle="270" bgAlpha="10,10" bgColor='#FFFFFF' showBorder='0'>

<categories>
<category label="Jan"/>
<category label="Feb"/>
<category label="Mar"/>
<category label="Apr"/>
<category label="May"/>
<category label="Jun"/>
<category label="Jul"/>
<category label="Aug"/>
<category label="Sep"/>
<category label="Oct"/>
<category label="Nov"/>
<category label="Dec"/>
</categories>


<dataset seriesName="Received Samples" color="1D8BD1" anchorBorderColor="2AD62A" anchorBgColor="1D8BD1">
<set value="<?php echo GetReceivedSamplesPerlab($labss,1,$currentyear); ?>"/>
<set value="<?php echo GetReceivedSamplesPerlab($labss,2,$currentyear); ?>"/>
<set value="<?php echo GetReceivedSamplesPerlab($labss,3,$currentyear); ?>"/>
<set value="<?php echo GetReceivedSamplesPerlab($labss,4,$currentyear); ?>"/>
<set value="<?php echo GetReceivedSamplesPerlab($labss,5,$currentyear); ?>"/>
<set value="<?php echo GetReceivedSamplesPerlab($labss,6,$currentyear); ?>"/>
<set value="<?php echo GetReceivedSamplesPerlab($labss,7,$currentyear); ?>"/>
<set value="<?php echo GetReceivedSamplesPerlab($labss,8,$currentyear); ?>"/>
<set value="<?php echo GetReceivedSamplesPerlab($labss,9,$currentyear); ?>"/>
<set value="<?php echo GetReceivedSamplesPerlab($labss,10,$currentyear); ?>"/>
<set value="<?php echo GetReceivedSamplesPerlab($labss,11,$currentyear); ?>"/>
<set value="<?php echo GetReceivedSamplesPerlab($labss,12,$currentyear); ?>"/>

</dataset>
<dataset seriesName="Rejected Samples" color="F1683C" anchorBorderColor="F1683C" anchorBgColor="F1683C">
<set value="<?php echo GetRejectedSamplesPerlab($labss,1,$currentyear); ?>"/>
<set value="<?php echo GetRejectedSamplesPerlab($labss,2,$currentyear); ?>"/>
<set value="<?php echo GetRejectedSamplesPerlab($labss,3,$currentyear); ?>"/>
<set value="<?php echo GetRejectedSamplesPerlab($labss,4,$currentyear); ?>"/>
<set value="<?php echo GetRejectedSamplesPerlab($labss,5,$currentyear); ?>"/>
<set value="<?php echo GetRejectedSamplesPerlab($labss,6,$currentyear); ?>"/>
<set value="<?php echo GetRejectedSamplesPerlab($labss,7,$currentyear); ?>"/>
<set value="<?php echo GetRejectedSamplesPerlab($labss,8,$currentyear); ?>"/>
<set value="<?php echo GetRejectedSamplesPerlab($labss,9,$currentyear); ?>"/>
<set value="<?php echo GetRejectedSamplesPerlab($labss,10,$currentyear); ?>"/>
<set value="<?php echo GetRejectedSamplesPerlab($labss,11,$currentyear); ?>"/>
<set value="<?php echo GetRejectedSamplesPerlab($labss,12,$currentyear); ?>"/>

</dataset>

<dataset seriesName="Tested Samples" color="2AD62A" anchorBorderColor="2AD62A" anchorBgColor="2AD62A">
<set value="<?php echo GetTestedSamplesPerlab($labss,1,$currentyear); ?>"/>
<set value="<?php echo GetTestedSamplesPerlab($labss,2,$currentyear); ?>"/>
<set value="<?php echo GetTestedSamplesPerlab($labss,3,$currentyear); ?>"/>
<set value="<?php echo GetTestedSamplesPerlab($labss,4,$currentyear); ?>"/>
<set value="<?php echo GetTestedSamplesPerlab($labss,5,$currentyear); ?>"/>
<set value="<?php echo GetTestedSamplesPerlab($labss,6,$currentyear); ?>"/>
<set value="<?php echo GetTestedSamplesPerlab($labss,7,$currentyear); ?>"/>
<set value="<?php echo GetTestedSamplesPerlab($labss,8,$currentyear); ?>"/>
<set value="<?php echo GetTestedSamplesPerlab($labss,9,$currentyear); ?>"/>
<set value="<?php echo GetTestedSamplesPerlab($labss,10,$currentyear); ?>"/>
<set value="<?php echo GetTestedSamplesPerlab($labss,11,$currentyear); ?>"/>
<set value="<?php echo GetTestedSamplesPerlab($labss,12,$currentyear); ?>"/>
</dataset>



<styles>

<definition>
<style name="CaptionFont" type="font" size="12"/>
</definition>

<application>
<apply toObject="CAPTION" styles="CaptionFont"/>
<apply toObject="SUBCAPTION" styles="CaptionFont"/>
</application>
</styles>
</chart>