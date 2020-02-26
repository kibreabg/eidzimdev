<?php
//include("nationaldashboardfunctions.php");
require_once('../connection/config.php'); 
$mwaka=$_GET['mwaka'];
$mwezi=$_GET['mwezi'];
$provid=$_GET['province'];
$dcode=$_GET['dcode'];
$fcode=$_GET['fcode'];
//ECHO  $provid . " / ".$mwaka . " / " . $mwezi . " / ".$dcode. " / ".$fcode;
?>
<chart lineThickness="1" showValues="0" formatNumberScale="0" anchorRadius="2" divLineAlpha="20" divLineColor="CC3300" divLineIsDashed="1" showAlternateHGridColor="1" alternateHGridAlpha="3" alternateHGridColor="CC3300" shadowAlpha="40" labelStep="0" numvdivlines="5" chartRightMargin="35"  bgAngle="270" bgAlpha="10,10" bgColor='#FFFFFF' showBorder='0' overlapColumns="0" useRoundEdges="1">
<categories>
<?php 
      $sql="select ID,name from entry_points  ";
   $result=mysql_query($sql)or die(mysql_error());
    $result2=mysql_query($sql)or die(mysql_error());
	    $result3=mysql_query($sql)or die(mysql_error());
		$result4=mysql_query($sql)or die(mysql_error());
  
   while($row=mysql_fetch_array($result))
{	
   $entid=$row['ID'];
   		$entname=trim($row['name']);
		   	?>
			
<category label="<?php echo $entname?>"/><?php } ?></categories>
 
<dataset seriesName="Negative" showValues="0">
<?php while($row=mysql_fetch_array($result2))
{	
   $entid=$row['ID'];
   		$entname=trim($row['name']);
		$rs2 = mysql_query( "CALL Getprovinceentrypositivitycount($provid,$entid,1,$mwaka,$mwezi,$dcode,$fcode, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$nega=$d2['numsamples'];
		   	?><set value="<?php echo  $nega;?>"/><?php }?></dataset>
<dataset seriesName="Positive" showValues="0">
 <?php while($row=mysql_fetch_array($result3))
{	
   $entid=$row['ID'];
   		$entname=trim($row['name']);
		$rs2 = mysql_query( "CALL Getprovinceentrypositivitycount($provid,$entid,2,$mwaka,$mwezi,$dcode,$fcode, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$posi=$d2['numsamples'];
		   	?><set value="<?php echo $posi;?>"/><?php }?></dataset>
			
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

