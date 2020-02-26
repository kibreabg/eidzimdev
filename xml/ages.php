<?php
include("../nationaldashboardfunctions.php");
$mwaka=$_GET['mwaka'];
$mwezi=$_GET['mwezi'];
?>
<chart lineThickness="1" showValues="0" formatNumberScale="0" anchorRadius="2" divLineAlpha="20" divLineColor="CC3300" divLineIsDashed="1" showAlternateHGridColor="1" alternateHGridAlpha="5" alternateHGridColor="CC3300" shadowAlpha="40" labelStep="0" numvdivlines="5" chartRightMargin="35"  bgAngle="270" bgAlpha="10,10" bgColor='#FFFFFF' showBorder='0' overlapColumns="0" useRoundEdges="1" showPercentageValues='1' >

<categories>
<category label="<?php echo 'Less 2M'; ?>"/>
<category label="<?php echo '2M - 9M '; ?>"/>
<category label="<?php echo '9M - 18M'; ?>"/>
<category label="<?php echo 'No Data'; ?>"/>
<category label="<?php echo '> 18M'; ?>"/>
</categories>


<dataset seriesName="Negative"  anchorBorderColor="2AD62A" anchorBgColor="1D8BD1">
<set value="<?php echo Getageless2monthspositivitycount(1,$mwaka,$mwezi)?>"/>
<set value="<?php echo Getagemore2monthsto9monthspositivitycount(1,$mwaka,$mwezi)?>"/>
<set value="<?php echo Getagemore9to18positivitycount(1,$mwaka,$mwezi);?>"/>
<set value="<?php echo Getagenullpositivitycount(1,$mwaka,$mwezi);?>"/>
<set value="<?php echo Getageabove18positivitycount(1,$mwaka,$mwezi);?>"/>
</dataset>
<dataset seriesName="Positive"  anchorBorderColor="F1683C" anchorBgColor="F1683C">
<set value="<?php echo  Getageless2monthspositivitycount(2,$mwaka,$mwezi);?>"/>
<set value="<?php echo Getagemore2monthsto9monthspositivitycount(2,$mwaka,$mwezi);?>"/>
<set value="<?php echo Getagemore9to18positivitycount(2,$mwaka,$mwezi);?>"/>
<set value="<?php echo Getagenullpositivitycount(2,$mwaka,$mwezi);?>"/>
<set value="<?php echo Getageabove18positivitycount(2,$mwaka,$mwezi);?>"/>
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