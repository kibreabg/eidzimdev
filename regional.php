<?php include("header.php");
	require_once('connection/config.php'); 
include("FusionMaps/FusionMaps.php");
include("FusionCharts/FusionCharts.php");

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





$province=$_GET['province']; // Use this line or below line if register_global is off
	if (!(isset($province)))
{
$province=1;
}
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
//$fcode=$_POST['fcode']; // Use this line or below line if register_global is off
$fcode=  $_POST['cat'];
$fname=GetFacilityName($fcode);
//facility

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


?>
	<script type="text/javascript" src="includes/jquery.min.js"></script>
<script type="text/javascript" src="includes/jquery.js"></script>
<script type='text/javascript' src='includes/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="includes/jquery.autocomplete.css" />

<style type="text/css">
<!--
.style1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.style3 {font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; color: #330033; }
-->
</style>

<script>
		window.dhx_globalImgPath="../img/";
	</script>
<script src="users/dhtmlxcombo_extra.js"></script>
 <link rel="STYLESHEET" type="text/css" href="users/dhtmlxcombo.css">
  <script src="users/dhtmlxcommon.js"></script>
  <script src="users/dhtmlxcombo.js"></script>

<form method="post" name="f3" action="">
		<table width="100%" cellpadding="5" cellspacing="5">
		<tr>
		<td colspan="2"> <a href="regional.php?province=<?php echo 1;?>&mwezi=<?php echo $currentmonth;?>&year=<?php echo $currentyear;?>">Harare </a> | <a href="regional.php?province=<?php echo 6;?>&mwezi=<?php echo $currentmonth;?>&year=<?php echo $currentyear;?>">Matebeland North</a> | <a href="regional.php?province=<?php echo 8;?>&mwezi=<?php echo $currentmonth;?>&year=<?php echo $currentyear;?>">Midlands</a> | <a href="regional.php?province=<?php echo 5;?>&mwezi=<?php echo $currentmonth;?>&year=<?php echo $currentyear;?>">Mash West</a> | <a href="regional.php?province=<?php echo 3;?>&mwezi=<?php echo $currentmonth;?>&year=<?php echo $currentyear;?>">Mash Central</a> | <a href="regional.php?province=<?php echo 4;?>&mwezi=<?php echo $currentmonth;?>&year=<?php echo $currentyear;?>">Mash East</a> | <a href="regional.php?province=<?php echo 2;?>&mwezi=<?php echo $currentmonth;?>&year=<?php echo $currentyear;?>">Manicaland </a> | <a href="regional.php?province=<?php echo 9;?>&mwezi=<?php echo $currentmonth;?>&year=<?php echo $currentyear;?>">Masvingo </a> | <a href="regional.php?province=<?php echo 7;?>&mwezi=<?php echo $currentmonth;?>&year=<?php echo $currentyear;?>">Matebeland South </a> |  <a href="regional.php?province=<?php echo 10;?>&mwezi=<?php echo $currentmonth;?>&year=<?php echo $currentyear;?>">Bulawayo </a></td>
		</tr>
	
	 <tr>
         <td width="600">
			  
<table cellspacing="2" cellpadding="2" border="0">
<tr>
	<td> District </td>
	<td><select  style="width:210px"  id='dcode' name="dcode">
    
  </select>  
  <script>
    var combo = dhtmlXComboFromSelect("dcode");
	combo.enableFilteringMode(true,"getprovincedistrict.php?province=<?php echo $province; ?>",true);
	

</script></td> 
<td><input type="submit" name="districtfilter" value="Filter" class="button"/></td> 
	<td> Facility </td>
	<td><select  style="width:210px"  id='cat' name="cat">
    
  </select>  
  <script>
    var combo = dhtmlXComboFromSelect("cat");
	combo.enableFilteringMode(true,"getprovincefacility.php?province=<?php echo $province; ?>",true);
	

</script></td> 
<td><input type="submit" name="facilityfilter" value="Filter" class="button"/></td> 
</tr>
</table>
</form>
		</td>
	</tr>
	<tr>
		<td  valign="top"  >
		<div class="section-title"><?php echo $provincename;?> Province <?php echo $dist; ?> Tests Summary as of <?php echo $defaultmonth; ?> </div><br><br><br> <div id="chartdivresult" >The chart will appear within this DIV. This text will be replaced by the chart.</div>
   <script type="text/javascript">
      var myChart = new FusionCharts("FusionCharts/pie2D.swf", "myChartId", "600", "200", "0", "0");
    myChart.setDataURL("XML/provincialeidresultspie.php?province=<?php echo $province;?>%26mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>%26dcode=<?php echo $dcode;?>%26fcode=<?php echo $fcode;?>");      
      myChart.render("chartdivresult");
   </script>
		</td>
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
				$firstpcrtest=Getregionalfirsttestedsamples($province,$currentyear,$currentmonth,$dcode,$fcode);
				$secondpcrtest=Getregionalsecondtestedsamples($province,$currentyear,$currentmonth,$dcode,$fcode);
$positive=Getprovincepositivitycount($province,2,$currentyear,$currentmonth,$dcode,$fcode);
$negative=Getprovincepositivitycount($province,1,$currentyear,$currentmonth,$dcode,$fcode);
$failed=Getprovincepositivitycount($province,3,$currentyear,$currentmonth,$dcode,$fcode);
$indeterminate=Getprovincepositivitycount($province,5,$currentyear,$currentmonth,$dcode,$fcode);
$rejected=Getprovincerejectedsamples($province,$currentyear,$currentmonth,$dcode,$fcode);
$totalrejectedfailed=$rejected;

$totaloutcome=$positive+$negative+$totalrejectedfailed+$indeterminate;
if ($positive !=0)
{
$pospecentage=(($positive / $totaloutcome)* 100);
}
else
{
$pospecentage=0;
}


if ($negative !=0)
{
$negpecentage=(($negative / $totaloutcome)* 100);
}
else
{
$negpecentage=0;
}


if ($totalrejectedfailed !=0)
{
$rejpecentage=(($totalrejectedfailed / $totaloutcome)* 100);
}
else
{
$rejpecentage=0;
}


if ($indeterminate !=0)
{
$indeterminatepecentage=(($indeterminate / $totaloutcome)* 100);
}
else
{
$indeterminatepecentage=0;
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
                       <td width="258"><span class="style8">No. of All  PCR Tests</span></td>
                       <td width="412"><span class="style8"><strong><?php echo   Getalltestedprovincesamplescount($province,$currentyear,$currentmonth,$dcode,$fcode) ; ?></strong></span></td>
                     </tr>
                     <tr>
                       <td><span class="style8">No. of 1st DNA PCR Tests</span></td>
                       <td><span class="style8"><strong><?php echo $firstpcrtest; ?></strong></span></td>
                     </tr>
                    <tr>
                       <td><span class="style8">No. of 2nd DNA PCR Tests</span></td>
                       <td><span class="style8"><strong><?php echo $secondpcrtest; ?></strong></span></td>
                     </tr>
					   <tr>
    <td ><span class="style8">No. of Rejected Samples</span></td>
    <td ><span class="style8"><?php echo  $rejected  .' [ '.  round($rejpecentage) . '%' .' ] '; ?></span></td>
  </tr>
   <tr>
    <td ><span class="style8">No. of Redraw Samples</span></td>
    <td ><span class="style8"><?php echo  $indeterminate .' [ '.  $indeterminatepecentage . '%' .' ] '; ?></span></td>
  </tr>
                     <tr>
                       <td><span class="style8">Average Age of Testing </span></td>
                       <td><span class="style8"><strong>
                       <?php  $age=Getprovincialaverageage($province,$currentyear,$currentmonth,$dcode,$fcode); echo  $age. ' months '; ?>
                       </strong></span></td>
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
    <td><span class="style8">Total Number of EID Facilities </span></td>
    <td><span class="style8"><?php echo GettotalEIDsites($province,$dcode); ?></span></td>
  </tr>
  
  <?php
  }?>
                     
                 </table>
               <?php } 
				 
				 ?>			</td>
     </tr>

<tr>
		 
    <td ><div class="section-title">Turn Around Time (Days)</div>
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
			      <div id="chartdiv291" align="center">The chart will appear within this DIV. This text will be replaced by the chart.</div>
   <script type="text/javascript">
      var myChart290 = new FusionCharts("FusionWidgets/HLinearGauge.swf", "myChartId", "500", "100", "0", "0");
    myChart290.setDataURL("xml/provincialtat.php?province=<?php echo $province;?>%26mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>%26dcode=<?php echo $dcode;?>%26fcode=<?php echo $fcode;?>");      
      myChart290.render("chartdiv291");
   </script>
	</td>
	<td valign="top" colspan="2" >
				
		  <div class="section-title">Average Age of Testing</div>
 <div id="chartdiv2"  > </div>
     <script type="text/javascript" >
  var myChart = new FusionCharts("FusionWidgets/AngularGauge.swf", "myChartId", "540", "150", "0", "0");
       myChart.setDataXML("<chart lowerLimit='0' bgColor='#FFFFFF' showBorder='0' tickValueDistance='25'upperLimit='18' lowerLimitDisplay='6wks' upperLimitDisplay='18mths' gaugeStartAngle='180' gaugeEndAngle='0' palette='3' numberSuffix='mths' tickValueDistance='20' showValue='1'><colorRange><color minValue='0' maxValue='10' code='8BBA00'/><color minValue='10' maxValue='15' code='FF654F'/><color minValue='15' maxValue='18' code='F6BD0F'/></colorRange><dials><dial value='<?php echo $age; ?>'  rearExtension='10' baseWidth='1' /></dials></chart>");
      myChart.render("chartdiv2");
   </script> <br />		</td>
</tr>


		</table>
		
		
<table width="100%" cellpadding="5" cellspacing="5">
	<tr>
	<td>
		<div class="section-title"> EID Trend  Per Month for  <?php echo $currentyear;?></div>
  <div id="chartdivtrend" align="center"> </div>
		 <script type="text/javascript">
      var myChart = new FusionCharts("FusionCharts/Line.swf", "myChartId", "430", "300", "0", "0");
    myChart.setDataURL("xml/provinceyearlytrend.php?province=<?php echo $province;?>%26mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>%26dcode=<?php echo $dcode;?>%26fcode=<?php echo $fcode;?>");
	myChart.render("chartdivtrend");
   </script> </td>
<td>
		 	<div class="section-title">EID Results by Entry Point </div>


			           <div id="chartdiv90" align="center">The chart will appear within this DIV. This text will be replaced by the chart.</div>
   <script type="text/javascript">
      var myChart = new FusionCharts("FusionCharts/StackedColumn2D.swf", "myChartId", "490", "300", "0", "0");
       myChart.setDataURL("xml/provincialentrypositivity.php?province=<?php echo $province;?>%26mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>%26dcode=<?php echo $dcode;?>%26fcode=<?php echo $fcode;?>");
      
      myChart.render("chartdiv90");
   </script></td>
		<td>
		 <div class="section-title">EID Results by Age of Testing </div>
	<div id="chartdiv7" align="center">The chart will appear within this DIV. This text will be replaced by the chart.</div>
   <script type="text/javascript">
      var myChart = new FusionCharts("FusionCharts/StackedColumn2D.swf", "myChartId", "290", "300", "0", "0");
      
     myChart.setDataURL("XML/provincialages.php?province=<?php echo $province;?>%26mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>%26dcode=<?php echo $dcode;?>%26fcode=<?php echo $fcode;?>");
      myChart.render("chartdiv7");
   </script></td>
		</tr>
</table>
		
		
		
		
		
		
		
		
		
		<?php
	include("footer.php");
?>