<?php
include("../nationaldashboardfunctions.php");
$mwaka=$_GET['mwaka'];
$mwezi=$_GET['mwezi'];
?>
<chart palette='2' caption=''  showValues='1' decimals='0' formatNumberScale='0' useRoundEdges='1' showBorder="0"  bgColor='FFFFFF' >
<?php
 $sql="select ID,name from prophylaxis where ptype=2   ";
  	 	$result=mysql_query($sql)or die(mysql_error());
 while($row=mysql_fetch_array($result))
{	
   $prophid=$row['ID'];
   		$prophname=trim($row['name']);
		$count=Getinfantprophusagecount($prophid,$mwaka,$mwezi);
		   	?>
<set label='<?php echo $prophname;?>' value='<?php echo  $count;?>' toolText='<?php echo $prophname . ", ". $count;?>' />
<?php }?>
</chart>