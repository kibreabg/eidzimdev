<?php

//include("../connection/config.php");
include("../nationaldashboardfunctions.php");
$currentyear=$_GET['mwaka'];
$currentmonth=$_GET['mwezi'];
$activesites=GettotalActivesites($currentyear,$currentmonth); 
$totalsites=Gettotalsites();
$notsubmitting=$totalsites-$activesites;
?>
<chart palette='2' caption=''  showValues='1' decimals='0' formatNumberScale='0' useRoundEdges='1' showBorder="0"  bgColor='FFFFFF' >
  <set label="Submitting" value="<?php echo $activesites; ?>"  /> 
  <set label="Not Submitting" value="<?php echo $notsubmitting;?>" /> 

</chart>