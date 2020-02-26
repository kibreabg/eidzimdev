<?php
//include("../connection/config.php");
include("../nationaldashboardfunctions.php");
$currentyear=$_GET['mwaka'];
$currentmonth=$_GET['mwezi'];
$d=Getalltestedprovincesamplescount(6,$currentyear,$currentmonth);
echo $d; 
?>
<chart palette='2' caption=''  showValues='0' decimals='0' formatNumberScale='0' useRoundEdges='1' showBorder="0"  bgColor='FFFFFF' >
<set label='MN' value="<?php echo Getalltestedprovincesamplescount(6,$currentyear,$currentmonth); ?>" toolText='Matebeland North ' />
<set label='MI' value='<?php echo Getalltestedprovincesamplescount(8,$currentyear,$currentmonth); ?>' toolText='Midlands' />
<set label='MW' value='<?php echo Getalltestedprovincesamplescount(5,$currentyear,$currentmonth); ?>' toolText='Mashonaland West' />
<set label='MC' value='<?php echo Getalltestedprovincesamplescount(3,$currentyear,$currentmonth) ;?>' toolText='Mashonaland Central' />
<set label='ME' value='<?php echo Getalltestedprovincesamplescount(4,$currentyear,$currentmonth); ?>' toolText='Mashonaland East' />
<set label='MA' value='<?php echo Getalltestedprovincesamplescount(2,$currentyear,$currentmonth); ?>' toolText='Manicaland' />
<set label='MV' value='<?php echo Getalltestedprovincesamplescount(9,$currentyear,$currentmonth) ;?>' toolText='Masvingo' />
<set label='MS' value='<?php  echo Getalltestedprovincesamplescount(7,$currentyear,$currentmonth); ?>' toolText='Matebeland South' />
<set label='HA' value='<?php echo Getalltestedprovincesamplescount(1,$currentyear,$currentmonth); ?>' toolText='Harare' />
<set label='BU' value='<?php echo Getalltestedprovincesamplescount(10,$currentyear,$currentmonth); ?>' toolText='Bulawayo' />
</chart>