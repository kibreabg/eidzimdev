<?php
include("../connection/config.php");
$currentyear=$_GET['mwaka'];
//overall tested samples
$rst = mysql_query( "CALL Gettestedsamplescount($currentyear,1, @numsamples)" );
$rst = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dt=mysql_fetch_array($rst);
$jan=$dt['numsamples'];

$rst = mysql_query( "CALL Gettestedsamplescount($currentyear,2, @numsamples)" );
$rst = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dt=mysql_fetch_array($rst);
$feb=$dt['numsamples'];

$rst = mysql_query( "CALL Gettestedsamplescount($currentyear,3, @numsamples)" );
$rst = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dt=mysql_fetch_array($rst);
$mar=$dt['numsamples'];

$rst = mysql_query( "CALL Gettestedsamplescount($currentyear,4, @numsamples)" );
$rst = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dt=mysql_fetch_array($rst);
$april=$dt['numsamples'];

$rst = mysql_query( "CALL Gettestedsamplescount($currentyear,5, @numsamples)" );
$rst = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dt=mysql_fetch_array($rst);
$may=$dt['numsamples'];

$rst = mysql_query( "CALL Gettestedsamplescount($currentyear,6, @numsamples)" );
$rst = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dt=mysql_fetch_array($rst);
$june=$dt['numsamples'];

$rst = mysql_query( "CALL Gettestedsamplescount($currentyear,7, @numsamples)" );
$rst = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dt=mysql_fetch_array($rst);
$july=$dt['numsamples'];

$rst = mysql_query( "CALL Gettestedsamplescount($currentyear,8, @numsamples)" );
$rst = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dt=mysql_fetch_array($rst);
$aug=$dt['numsamples'];

$rst = mysql_query( "CALL Gettestedsamplescount($currentyear,9, @numsamples)" );
$rst = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dt=mysql_fetch_array($rst);
$sept=$dt['numsamples'];

$rst = mysql_query( "CALL Gettestedsamplescount($currentyear,10, @numsamples)" );
$rst = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dt=mysql_fetch_array($rst);
$oct=$dt['numsamples'];

$rst = mysql_query( "CALL Gettestedsamplescount($currentyear,11, @numsamples)" );
$rst = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dt=mysql_fetch_array($rst);
$nov=$dt['numsamples'];

$rst = mysql_query( "CALL Gettestedsamplescount($currentyear,12, @numsamples)" );
$rst = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dt=mysql_fetch_array($rst);
$dec=$dt['numsamples'];
//echo $jan . " - " .$feb. " - ".$mar. " -".$april . " - ".$may;;
?>
<chart caption="" subcaption="" xAxisName="Month" yAxisName="Tests" yAxisMinValue="15000" numberPrefix="" showValues="0" alternateHGridColor="FCB541" alternateHGridAlpha="20" divLineColor="FCB541" divLineAlpha="50" canvasBorderColor="666666" baseFontColor="666666" lineColor="FCB541" bgColor='#FFFFFF' showBorder='0' formatNumberScale="0">
<set label="Jan" value="<?php echo $jan; ?>"/>
<set label="Feb" value="<?php echo $feb; ?>"/>
<set label="Mar" value="<?php echo $mar; ?>"/>
<set label="Apr" value="<?php echo $april; ?>"/>
<set label="May" value="<?php echo $may; ?>"/>
<set label="Jun" value="<?php echo $june; ?>"/>
<set label="Jul" value="<?php echo $july; ?>"/>
<set label="Aug" value="<?php echo $aug; ?>"/>
<set label="Sep" value="<?php echo $sept; ?>"/>
<set label="Oct" value="<?php echo $oct; ?>"/>
<set label="Nov" value="<?php echo $nov; ?>"/>
<set label="Dec" value="<?php echo $dec; ?>"/>

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