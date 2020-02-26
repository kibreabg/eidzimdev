<?php
include("../nationaldashboardfunctions.php");
$mwaka=$_GET['mwaka'];
$mwezi=$_GET['mwezi'];
?>
<chart palette="3"  bgColor="#FFFFFF" showBorder="0"  shownames="1" showvalues="0" numberPrefix="" showSum="1" decimals="0" overlapColumns="0"  useRoundEdges="1" formatNumberScale='0'>
<categories>
<?php 
      $sql="select ID,name from deliverymode   ";
   $result=mysql_query($sql)or die(mysql_error());
    $result2=mysql_query($sql)or die(mysql_error());
	    $result3=mysql_query($sql)or die(mysql_error());
 $result4=mysql_query($sql)or die(mysql_error());

   while($row=mysql_fetch_array($result))
{	
   $feedid=$row['ID'];
   		$feedname=trim($row['name']);
		   	?>
			
<category label="<?php echo $feedname?>"/><?php } ?></categories>
 
<dataset seriesName="Negative" showValues="0">
<?php while($row=mysql_fetch_array($result2))
{	
      $feedid=$row['ID'];
   		$feedname=trim($row['name']);?><set value="<?php echo Getdeliverypositivitycount($feedid,1,$mwaka,$mwezi)?>"/><?php }?></dataset>
<dataset seriesName="Positive" showValues="0">
 <?php while($row=mysql_fetch_array($result3))
{	
      $feedid=$row['ID'];
   		$feedname=trim($row['name']);		   	?><set value="<?php echo Getdeliverypositivitycount($feedid,2,$mwaka,$mwezi)?>"/><?php }?></dataset>
 
</chart>



