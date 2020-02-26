<?php
include("../nationaldashboardfunctions.php");
//require_once('connection/config.php'); 

$currentyear=$_GET['mwaka'];
$currentmonth=$_GET['mwezi'];


?>
<chart palette='3' caption=''  showValues='1' decimals='1' formatNumberScale='0' useRoundEdges='1' showBorder="0"  bgColor='FFFFFF'  smartLineThickness='2' smartLineColor='333333' isSmartLineSlanted='1'  enableRotation="1" startingAngle="90">
<?php
$getprovinces=mysql_query("select Code,name from provinces")or die(mysql_error());

while(list($Code,$name)=mysql_fetch_array($getprovinces))
{
			$totaltests=Getalltestedprovincesamplescount($Code,$currentyear,$currentmonth,0,0) ;
			$positive=Getprovincepositivitycount($Code,2,$currentyear,$currentmonth,0,0);
			
			
			
			$percent=round((($positive/$totaltests)*100),1);
?>

  <set label="<?php echo $name; ?>" value="<?php echo $percent;?>" /> 
 
  <?php
    }
    ?>
</chart>