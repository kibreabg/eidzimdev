<?php
include("../connection/config.php");
include("../includes/labdashboardfunctions.php");
$labss=$_GET['labss'];
$currentmonth=$_GET['currentmonth'];
$currentyear=$_GET['currentyear'];
?>
<map showBevel='0' showMarkerLabels='1' fillColor='FFFFCC' borderColor='000000' hoverColor='E2E2C7' canvasBorderColor='FFFFFF' baseFont='Verdana' baseFontSize='10' markerBorderColor='000000' markerBgColor='FF5904' markerRadius='6' legendPosition='bottom' useHoverColor='1' showMarkerToolTip='1'  >
	
   <data>
   <?php 
      $sql="select ID,name from provinces ";
   $result=mysql_query($sql)or die(mysql_error());
   while($row=mysql_fetch_array($result))
   {$provid=$row['ID'];
   $provname=trim($row['name']);
   	?>
 
	<entity id='<?php echo "0".$provid; ?>'  toolText='<?php echo $provname . " Province";?>&lt;BR&gt;<?php echo "Samples Received: ". GetReceivedSamplesPerlabPerProvince($provid,$labss,$currentmonth,$currentyear); ?>&lt;BR&gt;<?php echo "Tests Done: ". GetTestedSamplesPerlabPerProvince($provid,$labss,$currentmonth,$currentyear); ?><?php echo  " [" .GetTestedpercentagePerProvince($provid,$labss,$currentmonth,$currentyear). "%]";?>&lt;BR&gt;<?php echo "Positive:".GetTestedSamplesPerlabByResultPerProvince($provid,$labss,$currentmonth,$currentyear,2);?> <?php echo  " [" .GetTestedpercentagePerResultPerProvince($provid,$labss,$currentmonth,$currentyear,2). "%]";?>&lt;BR&gt;<?php echo "Negative:".GetTestedSamplesPerlabByResultPerProvince($provid,$labss,$currentmonth,$currentyear,1);?><?php echo  " [" .GetTestedpercentagePerResultPerProvince($provid,$labss,$currentmonth,$currentyear,1). "%]";?>&lt;BR&gt;<?php echo "Indeterminate:".GetTestedSamplesPerlabByResultPerProvince($provid,$labss,$currentmonth,$currentyear,3);?><?php echo  " [" .GetTestedpercentagePerResultPerProvince($provid,$labss,$currentmonth,$currentyear,3). "%]";?>&lt;BR&gt;<?php echo "Repeats:".GetTestedRepeatSamplesPerlabPerProvince($provid,$labss,$currentmonth,$currentyear);?>&lt;BR&gt;<?php echo "Rejected:".GetRejectedSamplesPerlabPerProvince($provid,$labss,$currentmonth,$currentyear);?><?php echo  " [" .GetRejectedpercentagePerProvince($provid,$labss,$currentmonth,$currentyear). "%]";?>&lt;BR&gt;' color='666600'  link="n-districtsdrilldown.php?labss=<?php echo $labss;?>%26province=<?php echo $provid;?>%26currentmonth=<?php echo $currentmonth;?>%26currentyear=<?php echo $currentyear;?>"/>
	<?php
		
   }
   ?>
   </data>
	<styles> 
  <definition>
   <style name='TTipFont' type='font' isHTML='1'  color='FFFFFF' bgColor='666666' size='11'/>
   <style name='HTMLFont' type='font' color='333333' borderColor='CCCCCC' bgColor='FFFFFF'/>
   <style name='myShadow' type='Shadow' distance='1'/>
  </definition>
  <application>
   <apply toObject='MARKERS' styles='myShadow' /> 
   <apply toObject='MARKERLABELS' styles='HTMLFont,myShadow' />
   <apply toObject='TOOLTIP' styles='TTipFont' />
  </application>
 </styles>
</map>
