<?php 
include("header.php");
require_once('connection/config.php'); 
include("FusionMaps/FusionMaps.php");
include("FusionCharts/FusionCharts.php");
//require_once('classes/tc_calendar.php');

$mwaka=$_GET['year'];
$mwezi=$_GET['mwezi'];

$displaymonth=GetMonthName($mwezi);
if (isset($mwaka))
{
	if (isset($mwezi))
	{
	$defaultmonth=$displaymonth .' - '.$mwaka ; //get current month and year
	$currentmonth=$mwezi;
	$currentyear=$mwaka;
	
	}
	else
	{
	$defaultmonth=$mwaka ; //get current month and year
	$currentmonth=0;
	//get current month and year
	$currentyear=$mwaka;
	
	
	
	}
}
else
{
$defaultmonth=date("M-Y"); //get current month and year
$currentmonth=date("m");
$currentyear=date("Y");
}



?>
<?php /*
If register_global is off in your server then after reloading of the page to get the value of province from query string 
*/
$province=$_GET['province']; // Use this line or below line if register_global is off

$provincequery=mysql_query("select Name from provinces where ID='$province'")or die(mysql_error());
$provarray=mysql_fetch_array($provincequery);
$provincename=$provarray['Name'];

if ($_REQUEST['districtfilter'])
{
$dcode=$_POST['dcode']; // Use this line or below line if register_global is off
$dname=GetDistrictName($dcode);

if ($dcode !="")
{
$dist= ",". '<u>'.$dname . " District" .'</u>';
}
else
{
$dist="";


}
//echo "District: ".$dname . " - ".$currentyear ." / " .$currentmonth;
}
if ($_REQUEST['facilityfilter'])
{
$fcode=$_POST['fcode']; // Use this line or below line if register_global is off
$fname=GetFacilityName($fcode);

if ($fcode !="")
{
$dist= ",". '<u>'.$fname .'</u>';
}
else
{
$dist="";


}
//echo "Facility: ".$fname . " - ".$currentyear ." / " .$currentmonth;

if (!(isset($fcode)))
{
$fcode=0;
}
if (!(isset($dcode)))
{
$dcode=0;
}
 
}

//echo "Codes ".$fcode . " / " .$dcode;
?>

<script type="text/javascript" src="includes/jquery.min.js"></script>
<script type="text/javascript" src="includes/jquery.js"></script>
<script type='text/javascript' src='includes/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="includes/jquery.autocomplete.css" />
<script type="text/javascript">
$().ready(function() {
	
	$("#facility").autocomplete("get_facility.php?prov=<?php echo $province; ?>", {
		width: 260,
		matchContains: true,
		mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
	
	$("#facility").result(function(event, data, formatted) {
		$("#fcode").val(data[1]);
	});
});
</script>
<script type="text/javascript">
$().ready(function() {
	
	$("#district").autocomplete("get_district.php?prov=<?php echo $province; ?>", {
		width: 260,
		matchContains: true,
		mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
	
	$("#district").result(function(event, data, formatted) {
		$("#dcode").val(data[1]);
	});
});
</script>
<style type="text/css">
<!--
.style1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.style3 {font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; color: #330033; }
-->
</style>
<form method="post" name="f3" action="">
		   <table width="1001" border="0" cellpadding="2" cellspacing="2">
             <tr valign="top"> 			
               <td colspan="2" class="xtop"> <a href="regional.php">All </a>  |  <a href="regional.php?province=<?php echo 5;?>&mwezi=<?php echo $currentmonth;?>&year=<?php echo $currentyear;?>">Nairobi </a>  |  <a href="regional.php?province=<?php echo 1;?>&mwezi=<?php echo $currentmonth;?>&year=<?php echo $currentyear;?>">Central </a>  |   <a href="regional.php?province=<?php echo 2;?>&mwezi=<?php echo $currentmonth;?>&year=<?php echo $currentyear;?>">Coast </a>  |   <a href="regional.php?province=<?php echo 3;?>&mwezi=<?php echo $currentmonth;?>&year=<?php echo $currentyear;?>">Eastern </a>  |  <a href="regional.php?province=<?php echo 6;?>&mwezi=<?php echo $currentmonth;?>&year=<?php echo $currentyear;?>">North Eastern </a>  |   <a href="regional.php?province=<?php echo 9;?>&mwezi=<?php echo $currentmonth;?>&year=<?php echo $currentyear;?>">Western</a>  |  <a href="regional.php?province=<?php echo 7;?>&mwezi=<?php echo $currentmonth;?>&year=<?php echo $currentyear;?>">Nyanza </a>  |  <a href="regional.php?province=<?php echo 8;?>&mwezi=<?php echo $currentmonth;?>&year=<?php echo $currentyear;?>">Rift Valley </a>		       </td>
             </tr>
             <tr>
               <td width="600">
			   <?php 
if (isset($province))
{?>
			 District <input type="text" name="district" id="district" size="25" class="text" /> <input type="hidden" name="dcode" id="dcode" /> <input type="submit" name="districtfilter" value="Filter" class="button"/>
			 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;Facility&nbsp;&nbsp;  <input type="text" name="facility" id="facility" size="25" class="text" /> <input type="hidden" name="fcode" id="fcode" /> <input type="submit" name="facilityfilter" value="Filter" class="button"/>
			  
	<?php		   	

			//percentage of samples collected vs sampels tested fpr particular year
	if ( Getalltestedprovincesamplescount($province,$currentyear,$currentmonth,$dcode,$fcode) !=0)
	{
		// percentage number of samples tested: and outcome is positive
	$positivepercentage= round(((Getprovincepositivitycount($province,2,$currentyear,$currentmonth,$dcode,$fcode)/Getalltestedprovincesamplescount($province,$currentyear,$currentmonth,$dcode,$fcode)) * 100),1);
	// percentage number of samples tested: and outcome is negative
	$negativepercentage= round(((Getprovincepositivitycount($province,1,$currentyear,$currentmonth,$dcode,$fcode)/Getalltestedprovincesamplescount($province,$currentyear,$currentmonth,$dcode,$fcode)) * 100),1);
	//percentage number of samples tested: and outcome is indeterminate
	$indeterminatepercentage= round(((Getprovincepositivitycount($province,3,$currentyear,$currentmonth,$dcode,$fcode)/Getalltestedprovincesamplescount($province,$currentyear,$currentmonth,$dcode,$fcode)) * 100),1);
	//percentage of rejected samples nationally per year


	}
	else
	{
	$testedpercentage=0;
	$positivepercentage=0;
	$negativepercentage=0;
	$indeterminatepercentage=0;
	$rejectedpercentage=0;
	}
 if (!(isset($fcode)))
				{
				$fcode=0;
				}
			if (!(isset($dcode)))
				{
				$dcode=0;
				}
?>  


			   <div class="section-title">  <u><?php echo $provincename; ?> Province </u> <?php echo $dist; ?>  EID Results for<u><?php echo $defaultmonth;?></u></div>
<br /><br />
			   <div id="chartdivresult" >The chart will appear within this DIV. This text will be replaced by the chart.</div>
   <script type="text/javascript">
      var myChart = new FusionCharts("FusionCharts/pie3D.swf", "myChartId", "600", "200", "0", "0");
    myChart.setDataURL("provincialeidresultspie.php?province=<?php echo $province;?>%26mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>%26dcode=<?php echo $dcode;?>%26fcode=<?php echo $fcode;?>");      
      myChart.render("chartdivresult");
   </script>
	
<?php
}
else  //provicne not selected
{
	//central tests per month
$rsw = mysql_query( "CALL Getnooftestspermonth($province,$currentyear,1, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$jan=$dw['numtests'];

$rsw = mysql_query( "CALL Getnooftestspermonth($province,$currentyear,2, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$feb=$dw['numtests'];

$rsw = mysql_query( "CALL Getnooftestspermonth($province,$currentyear,3, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$mar=$dw['numtests'];

$rsw = mysql_query( "CALL Getnooftestspermonth($province,$currentyear,4, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$april=$dw['numtests'];
	
	 ?>
	 
		<table  border="0">
  <tr >
    <td colspan=2>
	
		<div class="section-title"> EID Results by Provinces for <?php echo $currentyear;?>
		  
</div>
	
	<table  border="1" width="100"   >
<tr bgcolor="#F0F3FA">
	<td >&nbsp;</td>
	<th >Jan</th>
	<th  bgcolor="#F5F5F5"  ><strong>+ves</strong></th>
	<th >Feb</th>
	<th  bgcolor="#F5F5F5"  ><strong>+ves</strong></th>
	<th >Mar</th>
	<th  bgcolor="#F5F5F5"  ><strong>+ves</strong></th>
	<th >Apr</th>
	<th  bgcolor="#F5F5F5"  ><strong>+ves</strong></th>
	<th >May</th>
	<th  bgcolor="#F5F5F5"  ><strong>+ves</strong></th>
	<th >June</th>
	<th  bgcolor="#F5F5F5"  ><strong>+ves</strong></th>
	<th >July</th>
	<th  bgcolor="#F5F5F5"  ><strong>+ves</strong></th>
	<th >Aug</th>
	<th  bgcolor="#F5F5F5"  ><strong>+ves</strong></th>
	<th >Sep</th>
	<th  bgcolor="#F5F5F5"  ><strong>+ves</strong></th>
	<th >Oct</th>
	<th bgcolor="#F5F5F5"  ><strong>+ves</strong></th>
	<th >Nov</th>
	<th bgcolor="#F5F5F5"  ><strong>+ves</strong></th>
	<th >Dec</th>
	<th bgcolor="#F5F5F5"  ><strong>+ves</strong></th>
	<th  bgcolor="#FDE3B3">Total</th>
	<th  ><strong>+Ves</strong></th>
	<th  bgcolor="#F5F5F5"  ><strong>%+Ves</strong></th>
</tr>
<tr >
	<th bgcolor="#F0F3FA">Central</th>
	<td><?php  //tests in jan 
	$rsw = mysql_query( "CALL Getnooftestspermonth(1,$currentyear,1, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests1=$dw['numtests']; echo $tests1; ?></td>
	<td bgcolor="#F5F5F5"  ><strong>
	  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(1,$currentyear,1,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives1=$d2['numsamples']; echo $positives1; ?>
	</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(1,$currentyear,2, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests2=$dw['numtests']; echo $tests2; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for feb
$rs2 = mysql_query( "CALL Getprovinceresultcount(1,$currentyear,2,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives2=$d2['numsamples']; echo $positives2; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(1,$currentyear,3, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests3=$dw['numtests']; echo $tests3; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(1,$currentyear,3,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives3=$d2['numsamples']; echo $positives3; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(1,$currentyear,4, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests4=$dw['numtests']; echo $tests4; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(1,$currentyear,4,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives4=$d2['numsamples']; echo $positives4; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(1,$currentyear,5, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests5=$dw['numtests']; echo $tests5; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(1,$currentyear,5,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives5=$d2['numsamples']; echo $positives5; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(1,$currentyear,6, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests6=$dw['numtests']; echo $tests6; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(1,$currentyear,6,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives6=$d2['numsamples']; echo $positives6; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(1,$currentyear,7, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests7=$dw['numtests']; echo $tests7; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(1,$currentyear,7,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives7=$d2['numsamples']; echo $positives7; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(1,$currentyear,8, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests8=$dw['numtests']; echo $tests8; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(1,$currentyear,8,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives8=$d2['numsamples']; echo $positives8; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(1,$currentyear,9, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests9=$dw['numtests']; echo $tests9; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(1,$currentyear,9,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives9=$d2['numsamples']; echo $positives9; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(1,$currentyear,10, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests10=$dw['numtests']; echo $tests10; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(1,$currentyear,10,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives10=$d2['numsamples']; echo $positives10; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(1,$currentyear,11, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests11=$dw['numtests']; echo $tests11; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(1,$currentyear,11,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives11=$d2['numsamples']; echo $positives11; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(1,$currentyear,12, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests12=$dw['numtests']; echo $tests12; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(1,$currentyear,12,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives12=$d2['numsamples']; echo $positives12; ?>
</strong></td>
	<td bgcolor="#FDE3B3"><?php  $rsw = mysql_query( "CALL Getnooftestsperyear(1,$currentyear, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$totalyealytests=$dw['numtests']; echo $totalyealytests; ?></td>
	<td bgcolor="#F0F3FA"><strong>
	  <?php  $rsw = mysql_query( "CALL Getprovincepositivitycount(1,2,$currentyear, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$totalpositivetests=$dw['numtests']; echo $totalpositivetests; ?>
	</strong></td>
	<td bgcolor="#F5F5F5"  ><strong>
	  <?php  if ($totalpositivetests != 0)
	{
	  $pospercentages=round((($totalpositivetests / $totalyealytests)*100),1);
	}
	else
	{
	$pospercentages=0;
	}  echo $pospercentages; ?>
	</strong></td>
</tr>
<tr>
	<th bgcolor="#F0F3FA">Coast</th>
	<td><?php  //tests in jan 
	$rsw = mysql_query( "CALL Getnooftestspermonth(2,$currentyear,1, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests11=$dw['numtests']; echo $tests11; ?></td>
	<td bgcolor="#F5F5F5"  ><strong>
	  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(2,$currentyear,1,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives1i=$d2['numsamples']; echo $positives1i; ?>
	</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(2,$currentyear,2, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests2i=$dw['numtests']; echo $tests2i; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for feb
$rs2 = mysql_query( "CALL Getprovinceresultcount(2,$currentyear,2,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives2j=$d2['numsamples']; echo $positives2j; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(2,$currentyear,3, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests3i=$dw['numtests']; echo $tests3i; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(2,$currentyear,3,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives3i=$d2['numsamples']; echo $positives3i; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(2,$currentyear,4, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests4i=$dw['numtests']; echo $tests4i; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(2,$currentyear,4,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives4i=$d2['numsamples']; echo $positives4i; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(2,$currentyear,5, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests5i=$dw['numtests']; echo $tests5i; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(2,$currentyear,5,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives5i=$d2['numsamples']; echo $positives5i; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(2,$currentyear,6, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests6i=$dw['numtests']; echo $tests6i; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(2,$currentyear,6,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives6i=$d2['numsamples']; echo $positives6i; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(2,$currentyear,7, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests7i=$dw['numtests']; echo $tests7i; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(2,$currentyear,7,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives7i=$d2['numsamples']; echo $positives7; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(2,$currentyear,8, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests8i=$dw['numtests']; echo $tests8i; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(2,$currentyear,8,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives8i=$d2['numsamples']; echo $positives8i; ?>
</strong></td>
	<td > <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(2,$currentyear,9, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests9i=$dw['numtests']; echo $tests9i; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(2,$currentyear,9,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives9i=$d2['numsamples']; echo $positives9i; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(2,$currentyear,10, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests10i=$dw['numtests']; echo $tests10i; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(2,$currentyear,10,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives10i=$d2['numsamples']; echo $positives10i; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(2,$currentyear,11, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests11i=$dw['numtests']; echo $tests11i; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(2,$currentyear,11,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives11i=$d2['numsamples']; echo $positives11i; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(2,$currentyear,12, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests12i=$dw['numtests']; echo $tests12i; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(2,$currentyear,12,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives12i=$d2['numsamples']; echo $positives12i; ?>
</strong></td>
	<td bgcolor="#FDE3B3"><?php  $rsw = mysql_query( "CALL Getnooftestsperyear(2,$currentyear, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$totalyealytestsi=$dw['numtests']; echo $totalyealytestsi; ?></td>
	<td bgcolor="#F0F3FA"><strong>
	  <?php  $rsw = mysql_query( "CALL Getprovincepositivitycount(2,2,$currentyear, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$totalpositivetestsi=$dw['numtests']; echo $totalpositivetestsi; ?>
	</strong></td>
	<td bgcolor="#F5F5F5"  ><strong>
	  <?php  if ($totalpositivetestsi != 0)
	{
	  $pospercentagesi=round((($totalpositivetestsi / $totalyealytestsi)*100),1);
	}
	else
	{
	$pospercentagesi=0;
	}  echo $pospercentagesi; ?>
	</strong></td>
</tr>
<tr>
	<th bgcolor="#F0F3FA">Eastern</th>
	<td><?php  //tests in jan 
	$rsw = mysql_query( "CALL Getnooftestspermonth(3,$currentyear,1, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests1=$dw['numtests']; echo $tests1; ?></td>
	<td bgcolor="#F5F5F5"  ><strong>
	  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(3,$currentyear,1,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives1=$d2['numsamples']; echo $positives1; ?>
	</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(3,$currentyear,2, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests2=$dw['numtests']; echo $tests2; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for feb
$rs2 = mysql_query( "CALL Getprovinceresultcount(3,$currentyear,2,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives2=$d2['numsamples']; echo $positives2; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(3,$currentyear,3, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests3=$dw['numtests']; echo $tests3; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(3,$currentyear,3,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives3=$d2['numsamples']; echo $positives3; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(3,$currentyear,4, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests4=$dw['numtests']; echo $tests4; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(3,$currentyear,4,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives4=$d2['numsamples']; echo $positives4; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(3,$currentyear,5, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests5=$dw['numtests']; echo $tests5; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(3,$currentyear,5,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives5=$d2['numsamples']; echo $positives5; ?>
</strong></td>
	<td > <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(3,$currentyear,6, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests6=$dw['numtests']; echo $tests6; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(3,$currentyear,6,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives6=$d2['numsamples']; echo $positives6; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(3,$currentyear,7, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests7=$dw['numtests']; echo $tests7; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(3,$currentyear,7,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives7=$d2['numsamples']; echo $positives7; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(3,$currentyear,8, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests8=$dw['numtests']; echo $tests8; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(3,$currentyear,8,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives8=$d2['numsamples']; echo $positives8; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(3,$currentyear,9, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests9=$dw['numtests']; echo $tests9; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(3,$currentyear,9,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives9=$d2['numsamples']; echo $positives9; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(3,$currentyear,10, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests10=$dw['numtests']; echo $tests10; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(3,$currentyear,10,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives10=$d2['numsamples']; echo $positives10; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(3,$currentyear,11, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests11=$dw['numtests']; echo $tests11; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(3,$currentyear,11,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives11=$d2['numsamples']; echo $positives11; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(3,$currentyear,12, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests12=$dw['numtests']; echo $tests12; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(3,$currentyear,12,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives12=$d2['numsamples']; echo $positives12; ?>
</strong></td>
	<td bgcolor="#FDE3B3"><?php  $rsw = mysql_query( "CALL Getnooftestsperyear(3,$currentyear, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$totalyealytests=$dw['numtests']; echo $totalyealytests; ?></td>
	<td bgcolor="#F0F3FA"><strong>
	  <?php  $rsw = mysql_query( "CALL Getprovincepositivitycount(3,2,$currentyear, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$totalpositivetests=$dw['numtests']; echo $totalpositivetests; ?>
	</strong></td>
	<td bgcolor="#F5F5F5"  ><strong>
	  <?php  if ($totalpositivetests != 0)
	{
	  $pospercentages=round((($totalpositivetests / $totalyealytests)*100),1);
	}
	else
	{
	$pospercentages=0;
	}  echo $pospercentages; ?>
	</strong></td></tr>
<tr>
	<th bgcolor="#F0F3FA">Nairobi</th>
	<td><?php  //tests in jan 
	$rsw = mysql_query( "CALL Getnooftestspermonth(5,$currentyear,1, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests1=$dw['numtests']; echo $tests1; ?></td>
	<td bgcolor="#F5F5F5"  ><strong>
	  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(5,$currentyear,1,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives1=$d2['numsamples']; echo $positives1; ?>
	</strong></td>
	<td ><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(5,$currentyear,2, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests2=$dw['numtests']; echo $tests2; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for feb
$rs2 = mysql_query( "CALL Getprovinceresultcount(5,$currentyear,2,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives2=$d2['numsamples']; echo $positives2; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(5,$currentyear,3, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests3=$dw['numtests']; echo $tests3; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(5,$currentyear,3,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives3=$d2['numsamples']; echo $positives3; ?>
</strong></td>
	<td ><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(5,$currentyear,4, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests4=$dw['numtests']; echo $tests4; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(5,$currentyear,4,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives4=$d2['numsamples']; echo $positives4; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(5,$currentyear,5, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests5=$dw['numtests']; echo $tests5; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(5,$currentyear,5,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives5=$d2['numsamples']; echo $positives5; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(5,$currentyear,6, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests6=$dw['numtests']; echo $tests6; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(5,$currentyear,6,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives6=$d2['numsamples']; echo $positives6; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(5,$currentyear,7, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests7=$dw['numtests']; echo $tests7; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(5,$currentyear,7,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives7=$d2['numsamples']; echo $positives7; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(5,$currentyear,8, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests8=$dw['numtests']; echo $tests8; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(5,$currentyear,8,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives8=$d2['numsamples']; echo $positives8; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(5,$currentyear,9, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests9=$dw['numtests']; echo $tests9; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(5,$currentyear,9,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives9=$d2['numsamples']; echo $positives9; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(5,$currentyear,10, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests10=$dw['numtests']; echo $tests10; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(5,$currentyear,10,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives10=$d2['numsamples']; echo $positives10; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(5,$currentyear,11, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests11=$dw['numtests']; echo $tests11; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(5,$currentyear,11,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives11=$d2['numsamples']; echo $positives11; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(5,$currentyear,12, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests12=$dw['numtests']; echo $tests12; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(5,$currentyear,12,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives12=$d2['numsamples']; echo $positives12; ?>
</strong></td>
	<td bgcolor="#FDE3B3"><?php  $rsw = mysql_query( "CALL Getnooftestsperyear(5,$currentyear, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$totalyealytests=$dw['numtests']; echo $totalyealytests; ?></td>
	<td bgcolor="#F0F3FA"><strong>
	  <?php  $rsw = mysql_query( "CALL Getprovincepositivitycount(5,2,$currentyear, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$totalpositivetests=$dw['numtests']; echo $totalpositivetests; ?>
	</strong></td>
	<td bgcolor="#F5F5F5"  ><strong>
	  <?php  if ($totalpositivetests != 0)
	{
	  $pospercentages=round((($totalpositivetests / $totalyealytests)*100),1);
	}
	else
	{
	$pospercentages=0;
	}  echo $pospercentages; ?>
	</strong></td>
</tr>
<tr>
	<th bgcolor="#F0F3FA">N.Eastern</th>
	<td><?php  //tests in jan 
	$rsw = mysql_query( "CALL Getnooftestspermonth(6,$currentyear,1, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests1=$dw['numtests']; echo $tests1; ?></td>
	<td bgcolor="#F5F5F5"  ><strong>
	  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(6,$currentyear,1,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives1=$d2['numsamples']; echo $positives1; ?>
	</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(6,$currentyear,2, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests2=$dw['numtests']; echo $tests2; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for feb
$rs2 = mysql_query( "CALL Getprovinceresultcount(6,$currentyear,2,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives2=$d2['numsamples']; echo $positives2; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(6,$currentyear,3, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests3=$dw['numtests']; echo $tests3; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(6,$currentyear,3,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives3=$d2['numsamples']; echo $positives3; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(6,$currentyear,4, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests4=$dw['numtests']; echo $tests4; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(6,$currentyear,4,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives4=$d2['numsamples']; echo $positives4; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(6,$currentyear,5, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests5=$dw['numtests']; echo $tests5; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(6,$currentyear,5,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives5=$d2['numsamples']; echo $positives5; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(6,$currentyear,6, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests6=$dw['numtests']; echo $tests6; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(6,$currentyear,6,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives6=$d2['numsamples']; echo $positives6; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(6,$currentyear,7, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests7=$dw['numtests']; echo $tests7; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(6,$currentyear,7,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives7=$d2['numsamples']; echo $positives7; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(6,$currentyear,8, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests8=$dw['numtests']; echo $tests8; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(6,$currentyear,8,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives8=$d2['numsamples']; echo $positives8; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(6,$currentyear,9, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests9=$dw['numtests']; echo $tests9; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(6,$currentyear,9,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives9=$d2['numsamples']; echo $positives9; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(6,$currentyear,10, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests10=$dw['numtests']; echo $tests10; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(6,$currentyear,10,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives10=$d2['numsamples']; echo $positives10; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(6,$currentyear,11, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests11=$dw['numtests']; echo $tests11; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(6,$currentyear,11,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives11=$d2['numsamples']; echo $positives11; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(6,$currentyear,12, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests12=$dw['numtests']; echo $tests12; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(6,$currentyear,12,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives12=$d2['numsamples']; echo $positives12; ?>
</strong></td>
	<td bgcolor="#FDE3B3"><?php  $rsw = mysql_query( "CALL Getnooftestsperyear(6,$currentyear, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$totalyealytests=$dw['numtests']; echo $totalyealytests; ?></td>
	<td bgcolor="#F0F3FA"><strong>
	  <?php  $rsw = mysql_query( "CALL Getprovincepositivitycount(6,2,$currentyear, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$totalpositivetests=$dw['numtests']; echo $totalpositivetests; ?>
	</strong></td>
	<td bgcolor="#F5F5F5"  ><strong>
	  <?php  if ($totalpositivetests != 0)
	{
	  $pospercentages=round((($totalpositivetests / $totalyealytests)*100),1);
	}
	else
	{
	$pospercentages=0;
	}  echo $pospercentages; ?>
	</strong></td>
</tr>
<tr>
	<th bgcolor="#F0F3FA">Nyanza</th>
	<td><?php  //tests in jan 
	$rsw = mysql_query( "CALL Getnooftestspermonth(7,$currentyear,1, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests1=$dw['numtests']; echo $tests1; ?></td>
	<td bgcolor="#F5F5F5"  ><strong>
	  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(7,$currentyear,1,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives1=$d2['numsamples']; echo $positives1; ?>
	</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(7,$currentyear,2, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests2=$dw['numtests']; echo $tests2; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for feb
$rs2 = mysql_query( "CALL Getprovinceresultcount(7,$currentyear,2,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives2=$d2['numsamples']; echo $positives2; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(7,$currentyear,3, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests3=$dw['numtests']; echo $tests3; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(7,$currentyear,3,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives3=$d2['numsamples']; echo $positives3; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(7,$currentyear,4, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests4=$dw['numtests']; echo $tests4; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(7,$currentyear,4,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives4=$d2['numsamples']; echo $positives4; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(7,$currentyear,5, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests5=$dw['numtests']; echo $tests5; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(7,$currentyear,5,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives5=$d2['numsamples']; echo $positives5; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(7,$currentyear,6, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests6=$dw['numtests']; echo $tests6; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(7,$currentyear,6,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives6=$d2['numsamples']; echo $positives6; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(7,$currentyear,7, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests7=$dw['numtests']; echo $tests7; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(7,$currentyear,7,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives7=$d2['numsamples']; echo $positives7; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(7,$currentyear,8, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests8=$dw['numtests']; echo $tests8; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(7,$currentyear,8,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives8=$d2['numsamples']; echo $positives8; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(7,$currentyear,9, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests9=$dw['numtests']; echo $tests9; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(7,$currentyear,9,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives9=$d2['numsamples']; echo $positives9; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(7,$currentyear,10, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests10=$dw['numtests']; echo $tests10; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(7,$currentyear,10,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives10=$d2['numsamples']; echo $positives10; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(7,$currentyear,11, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests11=$dw['numtests']; echo $tests11; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(7,$currentyear,11,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives11=$d2['numsamples']; echo $positives11; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(7,$currentyear,12, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests12=$dw['numtests']; echo $tests12; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(7,$currentyear,12,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives12=$d2['numsamples']; echo $positives12; ?>
</strong></td>
	<td bgcolor="#FDE3B3"><?php  $rsw = mysql_query( "CALL Getnooftestsperyear(7,$currentyear, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$totalyealytests=$dw['numtests']; echo $totalyealytests; ?></td>
	<td bgcolor="#F0F3FA"><strong>
	  <?php  $rsw = mysql_query( "CALL Getprovincepositivitycount(7,2,$currentyear, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$totalpositivetests=$dw['numtests']; echo $totalpositivetests; ?>
	</strong></td>
	<td bgcolor="#F5F5F5"  ><strong>
	  <?php  if ($totalpositivetests != 0)
	{
	  $pospercentages=round((($totalpositivetests / $totalyealytests)*100),1);
	}
	else
	{
	$pospercentages=0;
	}  echo $pospercentages; ?>
	</strong></td></tr>
<tr>
	<th bgcolor="#F0F3FA">R.Valley</th>
<td><?php  //tests in jan 
	$rsw = mysql_query( "CALL Getnooftestspermonth(8,$currentyear,1, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests1=$dw['numtests']; echo $tests1; ?></td>
	<td bgcolor="#F5F5F5"  ><strong>
	  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(8,$currentyear,1,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives1=$d2['numsamples']; echo $positives1; ?>
	</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(8,$currentyear,2, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests2=$dw['numtests']; echo $tests2; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for feb
$rs2 = mysql_query( "CALL Getprovinceresultcount(8,$currentyear,2,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives2=$d2['numsamples']; echo $positives2; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(8,$currentyear,3, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests3=$dw['numtests']; echo $tests3; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(8,$currentyear,3,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives3=$d2['numsamples']; echo $positives3; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(8,$currentyear,4, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests4=$dw['numtests']; echo $tests4; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january

$rs2 = mysql_query( "CALL Getprovinceresultcount(8,$currentyear,4,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives4=$d2['numsamples']; echo $positives4; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(8,$currentyear,5, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests5=$dw['numtests']; echo $tests5; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(8,$currentyear,5,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives5=$d2['numsamples']; echo $positives5; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(8,$currentyear,6, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests6=$dw['numtests']; echo $tests6; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(8,$currentyear,6,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives6=$d2['numsamples']; echo $positives6; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(8,$currentyear,7, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests7=$dw['numtests']; echo $tests7; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(8,$currentyear,7,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives7=$d2['numsamples']; echo $positives7; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(8,$currentyear,8, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests8=$dw['numtests']; echo $tests8; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(8,$currentyear,8,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives8=$d2['numsamples']; echo $positives8; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(8,$currentyear,9, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests9=$dw['numtests']; echo $tests9; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(8,$currentyear,9,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives9=$d2['numsamples']; echo $positives9; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(8,$currentyear,10, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests10=$dw['numtests']; echo $tests10; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(8,$currentyear,10,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives10=$d2['numsamples']; echo $positives10; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(8,$currentyear,11, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests11=$dw['numtests']; echo $tests11; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(8,$currentyear,11,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives11=$d2['numsamples']; echo $positives11; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(8,$currentyear,12, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests12=$dw['numtests']; echo $tests12; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(8,$currentyear,12,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives12=$d2['numsamples']; echo $positives12; ?>
</strong></td>
	<td bgcolor="#FDE3B3"><?php  $rsw = mysql_query( "CALL Getnooftestsperyear(8,$currentyear, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$totalyealytests=$dw['numtests']; echo $totalyealytests; ?></td>
	<td bgcolor="#F0F3FA"><strong>
	  <?php  $rsw = mysql_query( "CALL Getprovincepositivitycount(8,2,$currentyear, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$totalpositivetests=$dw['numtests']; echo $totalpositivetests; ?>
	</strong></td>
	<td bgcolor="#F5F5F5"  ><strong>
	  <?php  if ($totalpositivetests != 0)
	{
	  $pospercentages=round((($totalpositivetests / $totalyealytests)*100),1);
	}
	else
	{
	$pospercentages=0;
	}  echo $pospercentages; ?>
	</strong></td></tr>
<tr>
	<th bgcolor="#F0F3FA">Western</th>
	<td><?php  //tests in jan 
	$rsw = mysql_query( "CALL Getnooftestspermonth(9,$currentyear,1, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests1=$dw['numtests']; echo $tests1; ?></td>
	<td bgcolor="#F5F5F5"  ><strong>
	  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(9,$currentyear,1,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives1=$d2['numsamples']; echo $positives1; ?>
	</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(9,$currentyear,2, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests2=$dw['numtests']; echo $tests2; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for feb
$rs2 = mysql_query( "CALL Getprovinceresultcount(9,$currentyear,2,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives2=$d2['numsamples']; echo $positives2; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(9,$currentyear,3, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests3=$dw['numtests']; echo $tests3; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(9,$currentyear,3,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives3=$d2['numsamples']; echo $positives3; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(9,$currentyear,4, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests4=$dw['numtests']; echo $tests4; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(9,$currentyear,4,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives4=$d2['numsamples']; echo $positives4; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(9,$currentyear,5, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests5=$dw['numtests']; echo $tests5; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(9,$currentyear,5,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives5=$d2['numsamples']; echo $positives5; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(9,$currentyear,6, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests6=$dw['numtests']; echo $tests6; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(9,$currentyear,6,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives6=$d2['numsamples']; echo $positives6; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(9,$currentyear,7, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests7=$dw['numtests']; echo $tests7; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(9,$currentyear,7,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives7=$d2['numsamples']; echo $positives7; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(9,$currentyear,8, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests8=$dw['numtests']; echo $tests8; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(9,$currentyear,8,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives8=$d2['numsamples']; echo $positives8; ?>
</strong></td>
	<td> <?php  $rsw = mysql_query( "CALL Getnooftestspermonth(9,$currentyear,9, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests9=$dw['numtests']; echo $tests9; ?> </td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(9,$currentyear,9,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives9=$d2['numsamples']; echo $positives9; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(9,$currentyear,10, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests10=$dw['numtests']; echo $tests10; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(9,$currentyear,10,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives10=$d2['numsamples']; echo $positives10; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(9,$currentyear,11, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests11=$dw['numtests']; echo $tests11; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(9,$currentyear,11,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives11=$d2['numsamples']; echo $positives11; ?>
</strong></td>
	<td><?php  $rsw = mysql_query( "CALL Getnooftestspermonth(9,$currentyear,12, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$tests12=$dw['numtests']; echo $tests12; ?></td>
<td bgcolor="#F5F5F5"  ><strong>
  <?php   //tested samples per province by result type  positive for january
$rs2 = mysql_query( "CALL Getprovinceresultcount(9,$currentyear,12,2, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$positives12=$d2['numsamples']; echo $positives12; ?>
</strong></td>
	<td bgcolor="#FDE3B3"><?php  $rsw = mysql_query( "CALL Getnooftestsperyear(9,$currentyear, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$totalyealytests=$dw['numtests']; echo $totalyealytests; ?></td>
	<td bgcolor="#F0F3FA"><strong>
	  <?php  $rsw = mysql_query( "CALL Getprovincepositivitycount(9,2,$currentyear, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$totalpositivetests=$dw['numtests']; echo $totalpositivetests; ?>
	</strong></td>
	<td bgcolor="#F5F5F5"  ><strong>
	  <?php  if ($totalpositivetests != 0)
	{
	  $pospercentages=round((($totalpositivetests / $totalyealytests)*100),1);
	}
	else
	{
	$pospercentages=0;
	}  echo $pospercentages; ?>
	</strong></td></tr>
<tr>
	<th bgcolor="#C8D0FB">Total</span></th>
	<td> <span class="style1">
	  <?php  $rsw = mysql_query( "CALL Gettestedsamplescountpermonth($currentyear,1, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$ttests=$dw['numtests']; echo $ttests; ?>
	 </span></td>
	<td bgcolor="#F5F5F5"  ><span class="style1">
	  <?php //tested samples positive per month
$rsr = mysql_query( "CALL Getnationaloutcome($currentyear,1,2, @numsamples)" );
$rsr = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dr=mysql_fetch_array($rsr);
$jpos=$dr['numsamples']; 
echo $jpos; ?>
	</span></td>
	<td> <span class="style1">
	  <?php  $rsw = mysql_query( "CALL Gettestedsamplescountpermonth($currentyear,2, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$ttests=$dw['numtests']; echo $ttests; ?>
	 </span></td>
	<td bgcolor="#F5F5F5"  ><span class="style1">
	  <?php //tested samples positive per month
$rsr = mysql_query( "CALL Getnationaloutcome($currentyear,2,2, @numsamples)" );
$rsr = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dr=mysql_fetch_array($rsr);
$jpos=$dr['numsamples']; 
echo $jpos; ?>
	</span></td>
	<td> <span class="style1">
	  <?php  $rsw = mysql_query( "CALL Gettestedsamplescountpermonth($currentyear,3, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$ttests=$dw['numtests']; echo $ttests; ?>
	 </span></td>
	<td bgcolor="#F5F5F5"  ><span class="style1">
	  <?php //tested samples positive per month
$rsr = mysql_query( "CALL Getnationaloutcome($currentyear,3,2, @numsamples)" );
$rsr = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dr=mysql_fetch_array($rsr);
$jpos=$dr['numsamples']; 
echo $jpos; ?>
	</span></td>
	<td> <span class="style1">
	  <?php  $rsw = mysql_query( "CALL Gettestedsamplescountpermonth($currentyear,4, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$ttests=$dw['numtests']; echo $ttests; ?>
	 </span></td>
	<td bgcolor="#F5F5F5"  ><span class="style1">
	  <?php //tested samples positive per month
$rsr = mysql_query( "CALL Getnationaloutcome($currentyear,4,2, @numsamples)" );
$rsr = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dr=mysql_fetch_array($rsr);
$jpos=$dr['numsamples']; 
echo $jpos; ?>
	</span></td>
<td> <span class="style1">
  <?php  $rsw = mysql_query( "CALL Gettestedsamplescountpermonth($currentyear,5, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$ttests=$dw['numtests']; echo $ttests; ?>
 </span></td>
	<td bgcolor="#F5F5F5"  ><span class="style1">
	  <?php //tested samples positive per month
$rsr = mysql_query( "CALL Getnationaloutcome($currentyear,5,2, @numsamples)" );
$rsr = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dr=mysql_fetch_array($rsr);
$jpos=$dr['numsamples']; 
echo $jpos; ?>
	</span></td>
<td> <span class="style1">
  <?php  $rsw = mysql_query( "CALL Gettestedsamplescountpermonth($currentyear,6, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$ttests=$dw['numtests']; echo $ttests; ?>
 </span></td>
	<td bgcolor="#F5F5F5"  ><span class="style1">
	  <?php //tested samples positive per month
$rsr = mysql_query( "CALL Getnationaloutcome($currentyear,6,2, @numsamples)" );
$rsr = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dr=mysql_fetch_array($rsr);
$jpos=$dr['numsamples']; 
echo $jpos; ?>
	</span></td>

<td> <span class="style1">
  <?php  $rsw = mysql_query( "CALL Gettestedsamplescountpermonth($currentyear,7, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$ttests=$dw['numtests']; echo $ttests; ?>
 </span></td>
	<td bgcolor="#F5F5F5"  ><span class="style1">
	  <?php //tested samples positive per month
$rsr = mysql_query( "CALL Getnationaloutcome($currentyear,7,2, @numsamples)" );
$rsr = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dr=mysql_fetch_array($rsr);
$jpos=$dr['numsamples']; 
echo $jpos; ?>
	</span></td>
<td> <span class="style1">
  <?php  $rsw = mysql_query( "CALL Gettestedsamplescountpermonth($currentyear,8, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$ttests=$dw['numtests']; echo $ttests; ?>
 </span></td>
	<td bgcolor="#F5F5F5"  ><span class="style1">
	  <?php //tested samples positive per month
$rsr = mysql_query( "CALL Getnationaloutcome($currentyear,8,2, @numsamples)" );
$rsr = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dr=mysql_fetch_array($rsr);
$jpos=$dr['numsamples']; 
echo $jpos; ?>
	</span></td>

<td> <span class="style1">
  <?php  $rsw = mysql_query( "CALL Gettestedsamplescountpermonth($currentyear,9, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$ttests=$dw['numtests']; echo $ttests; ?>
 </span></td>
	<td bgcolor="#F5F5F5"  ><span class="style1">
	  <?php //tested samples positive per month
$rsr = mysql_query( "CALL Getnationaloutcome($currentyear,9,2, @numsamples)" );
$rsr = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dr=mysql_fetch_array($rsr);
$jpos=$dr['numsamples']; 
echo $jpos; ?>
	</span></td>

<td> <span class="style1">
  <?php  $rsw = mysql_query( "CALL Gettestedsamplescountpermonth($currentyear,10, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$ttests=$dw['numtests']; echo $ttests; ?>
 </span></td>
	<td bgcolor="#F5F5F5"  ><span class="style1">
	  <?php //tested samples positive per month
$rsr = mysql_query( "CALL Getnationaloutcome($currentyear,10,2, @numsamples)" );
$rsr = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dr=mysql_fetch_array($rsr);
$jpos=$dr['numsamples']; 
echo $jpos; ?>
	</span></td>


<td> <span class="style1">
  <?php  $rsw = mysql_query( "CALL Gettestedsamplescountpermonth($currentyear,11, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$ttests=$dw['numtests']; echo $ttests; ?>
 </span></td>
	<td bgcolor="#F5F5F5"  ><span class="style1">
	  <?php //tested samples positive per month
$rsr = mysql_query( "CALL Getnationaloutcome($currentyear,11,2, @numsamples)" );
$rsr = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dr=mysql_fetch_array($rsr);
$jpos=$dr['numsamples']; 
echo $jpos; ?>
	</span></td>

<td> <span class="style1">
  <?php  $rsw = mysql_query( "CALL Gettestedsamplescountpermonth($currentyear,12, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$ttests=$dw['numtests']; echo $ttests; ?>
 </span></td>
	<td bgcolor="#F5F5F5"  ><span class="style1">
	  <?php //tested samples positive per month
$rsr = mysql_query( "CALL Getnationaloutcome($currentyear,12,2, @numsamples)" );
$rsr = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dr=mysql_fetch_array($rsr);
$jpos=$dr['numsamples']; 
echo $jpos; ?>
	</span></td>
<td bgcolor="#FDE3B3"> <span class="style1">
  <?php  $rsw = mysql_query( "CALL Gettestedsamplescount($currentyear,0, @numtests)" );
$rsw = mysql_query( "SELECT @numtests as 'numtests'" );
$dw=mysql_fetch_array($rsw);
$totaltests=$dw['numtests']; echo $totaltests; ?>
 </span></td>
	<td bgcolor="#F0F3FA"><span class="style1">
	  <?php //tested samples positive per month
$rsr = mysql_query( "CALL Getnationaloutcome($currentyear,0,2, @numsamples)" );
$rsr = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dr=mysql_fetch_array($rsr);
$yearlyjpos=$dr['numsamples']; 
echo $yearlyjpos; ?>
	</span></td>
	<td bgcolor="#F5F5F5"  ><span class="style1">
	  <?php
	if ($yearlyjpos !=0)
	{
	$yeapercentage= round((($yearlyjpos/$totaltests)*100),1);
	}
	else
	{
	$yeapercentage=0;
	}
echo $yeapercentage; ?>

    </span></td>
</tr>
</table>	</td>
  </tr>
  <tr>
   
   <td  > 
   
   <table border="0">
  <tr>
    <td valign="top" class="xtop"> <div class="section-title">EID Results by Province  for <?php echo $defaultmonth;?></div>
				
				  <div id="chartdiv4" align="center">The chart will appear within this DIV. This text will be replaced by the				  </div>
   <script type="text/javascript">
      var myChart = new FusionCharts("FusionCharts/StackedColumn2D.swf", "myChartId", "450", "308", "0", "0");
 				
   myChart.setDataURL("provincepositivity.php?mwaka=<?php echo $currentyear; ?>%26mwezi=<?php echo $currentmonth;?>");
      myChart.render("chartdiv4");
   </script>  </th>
    <td valign="top" class="xtop"> <div class="section-title">% of Positive   Results by Province for <?php echo $defaultmonth;?></div>
				
				  <div id="chartdiv44" align="center">The chart will appear within this DIV. This text will be replaced by the				  </div>
   <script type="text/javascript">
      var myChart = new FusionCharts("FusionCharts/pie2D.swf", "myChartId", "350", "308", "0", "0");
 				
   myChart.setDataURL("provincepositivitypie.php?mwaka=<?php echo $currentyear; ?>%26mwezi=<?php echo $currentmonth;?>");
      myChart.render("chartdiv44");
   </script>  </th>
  </tr>
</table>

  
   
   </td>
    <td valign="top" class="xtop"> <div class="section-title">EID Coverage by Province</div>
				
				  <div id="chartdiv22" align="center">The chart will appear within this DIV. This text will be replaced by the				  </div>
   <script type="text/javascript">
      var myChart2 = new FusionCharts("FusionCharts/StackedColumn2D.swf", "myChartId", "450", "308", "0", "0");
 				
   myChart2.setDataURL("provincesites.php");
      myChart2.render("chartdiv22");
   </script> </td>
  </tr>
</table>
		

	
				
<?php
	}
?>               </td>
               <td width="500" valign="top" align="left" class="xtop"><?php 
			   if (isset($province))
			   {
			     if (!(isset($fcode)))
				{
				$fcode=0;
				}
			if (!(isset($dcode)))
				{
				$dcode=0;
				}
			  
                if ($_REQUEST['facilityfilter'])
{?>
                 <div class="section-title">Facility Statistics</div>
<?php
}
else if  ($_REQUEST['districtfilter'])

{
?> <div class="section-title">District Statistics</div>
<?php
}
else 

{
?>
 <div class="section-title">Provincial Statistics</div>
 <?php } ?>
                   <table width="540" border="0" align="left">
                     <tr>
                       <td width="258">No. of Infants Tested</td>
                       <td width="412"><strong><?php echo   Getalltestedprovincesamplescount($province,$currentyear,$currentmonth,$dcode,$fcode) ; ?></strong></td>
                     </tr>
                     <tr>
                       <td>No. of Infants Tested ( < 2 months)</td>
                       <td><strong><?php echo Getprovinceinfantless2tested($province,$currentyear,$currentmonth,$dcode,$fcode); ?></strong></td>
                     </tr>
                    
                     <tr>
                       <td>Average Age of Testing </td>
                       <td><strong>
                       <?php  $age=Getprovincialaverageage($province,$currentyear,$currentmonth,$dcode,$fcode); echo  $age. ' months '; ?>
                       </strong></td>
                     </tr>
					 <?php
					  if ($fcode !=0)
{
}
else
{
?>
					 <tr>
    <td><span class="style8">Total Number of Health Facilities </span></td>
    <td><span class="style8"><?php echo Gettotalsites($province,$dcode); ?></span></td>
  </tr>
  <tr>
    <td><span class="style8">Total Number of PMTCT Facilities </span></td>
    <td><span class="style8"><?php echo GettotalPMTCTsites($province,$dcode); ?></span></td>
  </tr>
   <tr>
    <td><span class="style8">Total Number of EID Facilities </span></td>
    <td><span class="style8"><?php echo GettotalEIDsites($province,$dcode); ?></span></td>
  </tr>
  
  <?php
  }?>
                     
                 </table>
               <?php } 
				 
				 ?></td>
             </tr>
             <tr>
               <td width="500" valign="top" align="left" class="xtop">
			   
			   <?php 
			   if (isset($province))
			   {
			      
			   

		 if (!(isset($fcode)))
				{
				$fcode=0;
				}
			if (!(isset($dcode)))
				{
				$dcode=0;
				}
?>
			   <div class="section-title">EID Results by Age of Testing </div>
	<div id="chartdiv7" align="center">The chart will appear within this DIV. This text will be replaced by the chart.</div>
   <script type="text/javascript">
      var myChart = new FusionCharts("FusionCharts/StackedColumn2D.swf", "myChartId", "550", "250", "0", "0");
      
     myChart.setDataURL("provincialages.php?province=<?php echo $province;?>%26mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>%26dcode=<?php echo $dcode;?>%26fcode=<?php echo $fcode;?>");
      myChart.render("chartdiv7");
   </script>
   </td>
               <td width="500" valign="top" align="left" class="xtop"> 
			   <table width="541"  border="0">
  <tr>
    <td width="535"><div class="section-title">Turn Around Time (Days)</div>
	<table width="600">
		<tr>
		<td><small><strong> Key </strong></small></td>
		<td ><small><strong> <div class="success">Sample collection to PCR lab	
					</div>  </strong></small></td>
		<td ><small><strong><div class="notice">Processing at PCR lab 	
					</div>   </strong></small></td>
					<td><small><strong> <div class="success2" >Total Turn Around Time	
					</div>     </strong></small></td></tr>
	
		</table>
			      <div id="chartdiv290" align="center">The chart will appear within this DIV. This text will be replaced by the chart.</div>
   <script type="text/javascript">
      var myChart290 = new FusionCharts("FusionWidgets/HLinearGauge.swf", "myChartId", "550", "100", "0", "0");
    myChart290.setDataURL("provincialtat.php?province=<?php echo $province;?>%26mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>%26dcode=<?php echo $dcode;?>%26fcode=<?php echo $fcode;?>");      
      myChart290.render("chartdiv290");
   </script>
 
   
   </td>
  </tr>
  <tr>
    <td><div class="section-title">Average Age of Testing</div>
			     <div id="chartdiv" align="center">
      The chart will appear within this DIV. This text will be replaced by the chart.   </div>
   <script type="text/javascript">
      var myChart = new FusionCharts("FusionWidgets/AngularGauge.swf", "myChartId",  "550", "157", "0", "0");
   myChart.setDataXML("<chart lowerLimit='0' bgColor='#FFFFFF' showBorder='0' tickValueDistance='25'upperLimit='18' lowerLimitDisplay='6wks' upperLimitDisplay='18mths' gaugeStartAngle='180' gaugeEndAngle='0' palette='3' numberSuffix='mths' tickValueDistance='20' showValue='1'><colorRange><color minValue='0' maxValue='10' code='8BBA00'/><color minValue='10' maxValue='15' code='FF654F'/><color minValue='15' maxValue='18' code='F6BD0F'/></colorRange><dials><dial value='<?php echo $age; ?>'  rearExtension='10' baseWidth='2' /></dials></chart>");
      myChart.render("chartdiv");
   </script> <?php } ?></td>
  </tr>
</table>

			    </td> 
             </tr>
             <tr class="xtop"  valign="top">
			 <td> <?php 
			   if (isset($province))
			   {
			   if (!(isset($fcode)))
				{
				$fcode=0;
				}
			if (!(isset($dcode)))
				{
				$dcode=0;
				}
 

			   ?>
			   	<div class="section-title">EID Results by Entry Point </div>


			           <div id="chartdiv90" align="center">The chart will appear within this DIV. This text will be replaced by the chart.</div>
   <script type="text/javascript">
      var myChart = new FusionCharts("FusionCharts/StackedColumn2D.swf", "myChartId", "550", "250", "0", "0");
       myChart.setDataURL("provincialentrypositivity.php?province=<?php echo $province;?>%26mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>%26dcode=<?php echo $dcode;?>%26fcode=<?php echo $fcode;?>");
      
      myChart.render("chartdiv90");
   </script></td>
               <td> 
			   	<div class="section-title">EID Results by PMTCT Intervention</div>


			    <div id="chartdiv2" align="center">The chart will appear within this DIV. This text will be replaced by the chart.</div>
   <script type="text/javascript">
      var myChart = new FusionCharts("FusionCharts/StackedColumn2D.swf", "myChartId", "550", "250", "0", "0");
    myChart.setDataURL("provincepmtctpositivty.php?province=<?php echo $province;?>%26mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>%26dcode=<?php echo $dcode;?>%26fcode=<?php echo $fcode;?>");      
      myChart.render("chartdiv2");
   </script><?php } ?></td>
             </tr>
           </table>
</form>

<?php include("footer.html"); ?>

