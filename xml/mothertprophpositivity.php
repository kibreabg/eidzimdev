<?php
require_once('../connection/config.php'); 
$mwaka=$_GET['mwaka'];
$mwezi=$_GET['mwezi'];

?>
<chart palette="3"  bgColor="#FFFFFF" showBorder="0"  shownames="1" showvalues="0" numberPrefix="" showSum="1" decimals="0" overlapColumns="0" useRoundEdges="1" formatNumberScale='0'>
<categories>
<?php 
      $sql="select ID,name from prophylaxis where  ptype=1  ";
   		$result=mysql_query($sql)or die(mysql_error());
   		 $result2=mysql_query($sql)or die(mysql_error());
	    $result3=mysql_query($sql)or die(mysql_error());
		 $result4=mysql_query($sql)or die(mysql_error());
 // $row=mysql_fetch_array($result);
   while($row=mysql_fetch_array($result))
{	
   $profid=$row['ID'];
   		$profname=trim($row['name']);
		 


		   	?>
			
<category label="<?php echo $profname?>"/><?php } ?></categories>
 
<dataset seriesName="Negative" showValues="0">
<?php while($row2=mysql_fetch_array($result2))
{	
   $profid=$row2['ID'];
   		$profname=trim($row2['name']);
		 //negative outcome based on drug
$rs = mysql_query( "CALL Getinterventionspositivitycount($profid,$mwaka,$mwezi,1, @numtests)" );
$rs = mysql_query( "SELECT @numtests as 'numtests'" );
$d=mysql_fetch_array($rs);
$negatives=$d['numtests'];
		   	?><set value="<?php echo $negatives?>"/><?php }?></dataset>
<dataset seriesName="Positive" showValues="0">
 <?php while($row3=mysql_fetch_array($result3))
{	
   $profid=$row3['ID'];
   		$profname=trim($row3['name']);
		 //positive  outcome based on drug
$rs2 = mysql_query( "CALL Getinterventionspositivitycount($profid,$mwaka,$mwezi,2, @numtests)" );
$rs2 = mysql_query( "SELECT @numtests as 'numtests'" );
$d2=mysql_fetch_array($rs2);
$positives=$d2['numtests'];
		   	?><set value="<?php echo $positives; ?>"/><?php }?></dataset>
			
</chart>
