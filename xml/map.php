<?php
$mwaka=$_GET['mwaka'];
$mwezi=$_GET['mwezi'];
require_once('../connection/config.php'); 
//include("nationaldashboardfunctions.php");

?>
<map showBevel='0' showMarkerLabels='1' fillColor='FFFFCC' borderColor='000000' hoverColor='efeaef' canvasBorderColor='FFFFFF' baseFont='Verdana' baseFontSize='10' markerBorderColor='000000' markerBgColor='FF5904' markerRadius='6' legendPosition='bottom' useHoverColor='1' showMarkerToolTip='1'  >
	
   <data>
   <?php 
      $sql="select Code,ID,name,MapID from provinces ";
   $result=mysql_query($sql)or die(mysql_error());
   while($row=mysql_fetch_array($result))
   {
   $provid=$row['Code'];
    $mapid=$row['MapID'];
   $provname=trim($row['name']);
  // $d=Getprovincepositive($provid,2,$mwaka,$mwezi);
   
      //received samples per province 
$rs = mysql_query( "CALL Getallprovincesamplescount($provid,$mwaka,$mwezi, @numsamples)" );
$rs = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d=mysql_fetch_array($rs);
$samplesreceived=$d['numsamples'];

   //tested samples per province 
$rs1 = mysql_query( "CALL Gettestedsamplescountperprovince($provid,$mwaka,$mwezi, @numsamples)" );
$rs1 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d1=mysql_fetch_array($rs1);
$samplestested=$d1['numsamples'];

   //tested samples per province by result type  positive
$rs2 = mysql_query( "CALL Getprovinceresultcount($provid,$mwaka,$mwezi,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives=$d2['numsamples'];
//positive percentage
if  ($positives !=0)
{
$pospercentage=round((($positives/$samplestested)*100),1);
}
else
{
$pospercentage=0;
}

//negatives
$rs2 = mysql_query( "CALL Getprovinceresultcount($provid,$mwaka,$mwezi,1, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$negatives=$d2['numsamples'];
//negative percentage
if  ($negatives !=0)
{
$negpercentage=round((($negatives/$samplestested)*100),1);
}
else
{
$negpercentage=0;
}
//indeterminate
$rs2 = mysql_query( "CALL Getprovinceresultcount($provid,$mwaka,$mwezi,5, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$indeter=$d2['numsamples'];
//indeter percentage
if  ($indeter !=0)
{
$indeterpercentage=round((($indeter/$samplestested)*100),1);
}
else
{
$indeterpercentage=0;
}

//rejected samples
$rs3 = mysql_query( "CALL Getprovincerejectedsamples($provid,$mwaka,$mwezi, @numsamples)" );
$rs3 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d3=mysql_fetch_array($rs3);
$rejected=$d3['numsamples'];

//rejected percentage
if  ($rejected !=0)
{
$rejpercentage=round((($rejected/$samplesreceived)*100),1);
}
else
{
$rejpercentage=0;
}

//PROVINCIAL AVERAGE AGE OF TESTING PER MONTH/ YEAR
$rs4 = mysql_query( "CALL Getprovincialaverageage($provid,$mwaka,$mwezi, @averageage)" );
$rs4 = mysql_query( "SELECT @averageage as 'averageage'" );
$d4=mysql_fetch_array($rs4);
$averageage=round($d4['averageage'],1);
   	?>
		<entity id='<?php echo $mapid; ?>'  toolText='<?php echo $provname . " Province";?>&lt;BR&gt;<?php echo "Samples Received: ". $samplesreceived; ?>&lt;BR&gt;<?php echo "Tests Done: ". $samplestested; ?>&lt;BR&gt;<?php echo "Positive:".$positives;?> <?php echo  " [" .$pospercentage. "%]";?>&lt;BR&gt;<?php echo "Negative:".$negatives;?><?php echo  " [" .$negpercentage. "%]";?>&lt;BR&gt;<?php echo "Redraws:".$indeter;?><?php echo  " [" .$failpercentage. "%]";?>&lt;BR&gt;<?php echo "Rejected:".$rejected;?><?php echo  " [" .$rejpercentage. "%]";?>&lt;BR&gt;<?php echo"Average Age:". $averageage." mths";?>'   color='<?php if ($provid ==0)
{
echo 'F1f1f1';
}
else if  ($provid ==2)
{
echo 'FFFFCC';
}
else if  ($provid ==3) 
{
echo 'E2E2C7';
}
else if  ($provid ==5)
{
echo 'CBCB96';
}
else if  ($provid ==6)
{
echo 'CBCB96';
}
else if  ($provid ==7)
{
echo 'FFFFCC';
}
else if  ($provid ==8)
{
echo 'F1f1f1';
}
else if  ($provid ==9)
{
echo 'E2E2C7';
}
?>'link='n-regional.php?province=<?php echo $provid;?>&mwezi=<?php echo $mwezi;?>&year=<?php echo $mwaka; ?> ' /><?php
		
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


