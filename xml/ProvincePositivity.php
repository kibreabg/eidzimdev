<?php
require_once('../connection/config.php'); 
$mwaka=$_GET['mwaka'];
$mwezi=$_GET['mwezi'];
?>
<chart lineThickness="1" showValues="0" formatNumberScale="0" anchorRadius="2" divLineAlpha="20" divLineColor="CC3300" divLineIsDashed="1" showAlternateHGridColor="1" alternateHGridAlpha="5" alternateHGridColor="CC3300" shadowAlpha="40" labelStep="0" numvdivlines="5" chartRightMargin="35"  bgAngle="270" bgAlpha="10,10" bgColor='#FFFFFF' showBorder='0' overlapColumns="0" useRoundEdges="1">
<categories>
<?php 
      $sql="select Code,name from provinces   ";
  	 	$result=mysql_query($sql)or die(mysql_error());
    	$result2=mysql_query($sql)or die(mysql_error());
	    $result3=mysql_query($sql)or die(mysql_error());
		$result4=mysql_query($sql)or die(mysql_error());

   while($row=mysql_fetch_array($result))
	{	
   		$provid=$row['Code'];
   		$Prov=trim($row['name']);
		//$Prov=trim($row['Prov']);
		   	?>
			
<category label="<?php echo $Prov;?>"/><?php } ?></categories>
 
<dataset seriesName="Negative" showValues="0">
<?php while($row=mysql_fetch_array($result2))
{	
  		$provid=$row['Code'];
   		$prov=trim($row['name']);
		
		//negatives
$rs2 = mysql_query( "CALL Getprovinceresultcount($provid,$mwaka,$mwezi,1, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$negatives=$d2['numsamples'];
		   	?><set value="<?php echo $negatives;?>"/><?php }?></dataset>
<dataset seriesName="Positive" showValues="0">
 <?php while($row=mysql_fetch_array($result3))
{	
   $provid=$row['Code'];
   		$prov=trim($row['name']);
		
		//tested samples per province by result type  positive
$rst = mysql_query( "CALL Getprovinceresultcount($provid,$mwaka,$mwezi,2, @numsamples)" );
$rst = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dt=mysql_fetch_array($rst);
$positives=$dt['numsamples'];
		   	?><set value="<?php echo $positives;?>"/><?php }?></dataset>
			
</chart>
