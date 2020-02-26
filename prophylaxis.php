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
//mother arvs
$onart=GetmotheronARTs(1,$currentyear,$currentmonth);	
$notonart=GetmotheronARTs(2,$currentyear,$currentmonth);
$unknwonart=GetmotheronARTs(3,$currentyear,$currentmonth);
$total=$onart + $notonart + $unknwonart;
$onarv=GetmotheronARVs(1,$currentyear,$currentmonth);
$notonarv=GetmotheronARVs(2,$currentyear,$currentmonth);
$unknwonarv=GetmotheronARVs(3,$currentyear,$currentmonth);
$totalarv=$onarv + $notonarv + $unknwonarv;
//infant arvs
$infantonarv=GetinfantsonARVs(1,$currentyear,$currentmonth);
$$infantnotonarv=GetinfantsonARVs(2,$currentyear,$currentmonth);
$$infantunknwonarv=GetinfantsonARVs(3,$currentyear,$currentmonth);
$totalinfantarv=$infantonarv + $$infantnotonarv + $$infantunknwonarv;
$onctx=GetinfantsonCTX('Y',$currentyear,$currentmonth);
$notonctx=GetinfantsonCTX('N',$currentyear,$currentmonth);
$unknwonctx=GetinfantsonCTX('U',$currentyear,$currentmonth);
$totalctx=$onctx + $notonctx + $unknwonctx;
	?>
		<table width="100%" cellpadding="5" cellspacing="5">
		<tr><td  width="50%">
		<div class="section-title">Mother Prophylaxis by Positivity for  <?php echo $defaultmonth;?></div>
		<?PHP
	IF ($total == 0)
{
  ?>
  <small><strong> <div class="notice"> * No Data  </div>  </strong></small>
  <?PHP
}
ELSE
{?>
		<div id="chartdiv2" align="center">  New Vs. renewal. </div>
		<script type="text/javascript"> 
		   var chart = new FusionCharts("FusionCharts/Charts/StackedColumn2D.swf", "ChartId", "500", "270", "0", "0");
		   chart.setDataURL("xml/mothertprophpositivity.php?mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>");		   
		   chart.render("chartdiv2");
		</script>
		 <?PHP
   }
   ?></td>
		<td valign="top">
		<div class="section-title">Statistics</div>
		 <table width="90%" border="0"  cellpadding="0" cellspacing="0">
 

  <tr>
    <td ><span class="style8">No. of Mothers on ART :- <?php echo  $onart; ?></span></td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"> <div id="chartdivtrend44" align="center"> </div>
	<?PHP
	IF ($total == 0)
{
  ?>
  <small><strong> <div class="notice"> * No Data  </div>  </strong></small>
  <?PHP
}
ELSE
{?>
		 <script type="text/javascript">
      var myChart = new FusionCharts("FusionWidgets/HLinearGauge.swf", "myChartId", "430", "90", "0", "0");
    myChart.setDataURL("xml/ARTmothers.php?mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>");
	myChart.render("chartdivtrend44");
   </script>
   <?PHP
   }
   ?></td>
  </tr>
  <tr>
    <td ><span class="style8">No. of Mothers who received prophylaxis :- <?php echo  $onarv; ?></span></td>
    <td >&nbsp;</td>
  </tr>
   <tr>
    <td colspan="2" > <div id="chartdivtrend" align="center"> </div>
	<?PHP
	IF ($totalarv == 0)
{
  ?>
  <small><strong> <div class="notice"> * No Data  </div>  </strong></small>
  <?PHP
}
ELSE
{?>
		<script type="text/javascript">
      var myChart = new FusionCharts("FusionWidgets/HLinearGauge.swf", "myChartId", "430", "90", "0", "0");
    myChart.setDataURL("xml/ARVmothers.php?mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>");
	myChart.render("chartdivtrend");
   </script>
   <?php
   }
   ?></td>
  </tr>

 

  
</table>
		
		
		</td>
		</tr>
		<tr><td  width="50%">
		<div class="section-title">Infant Prophylaxis by Positvity</div>
		<?PHP
	IF ($totalinfantarv == 0)
{
  ?>
  <small><strong> <div class="notice"> * No Data  </div>  </strong></small>
  <?PHP
}
ELSE
{?>
		<div id="chartdiv4" align="center">  New Vs. renewal. </div>
		<script type="text/javascript"> 
		   var chart = new FusionCharts("FusionCharts/Charts/StackedColumn2D.swf", "ChartId", "500", "270", "0", "0");
		   chart.setDataURL("xml/infantprophpositivity.php?mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>");		   
		   chart.render("chartdiv4");
		</script>
		<?php
   }
   ?></td>
		<td valign="top">
		<div class="section-title">Statistics</div>
		 <table width="90%" border="0"  cellpadding="0" cellspacing="0">
 

  <tr>
    <td ><span class="style8">No. of Infants Given ARV Prophlyxais :- <?php echo  $infantonarv; ?></span></td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"> <div id="chartdivtrend344" align="center"> </div>
	<?PHP
	IF ($totalinfantarv == 0)
{
  ?>
  <small><strong> <div class="notice"> * No Data  </div>  </strong></small>
  <?PHP
}
ELSE
{?>
		 <script type="text/javascript">
      var myChart = new FusionCharts("FusionWidgets/HLinearGauge.swf", "myChartId", "430", "90", "0", "0");
    myChart.setDataURL("xml/ARVpatients.php?mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>");
	myChart.render("chartdivtrend344");
   </script>
   <?php
   }
   ?></td>
  </tr>
  <tr>
    <td ><span class="style8">No. of Infants on CTX :- <?php echo  $onctx; ?></span></td>
    <td >&nbsp;</td>
  </tr>
   <tr>
    <td colspan="2" > <div id="chartdivtrend0" align="center"> </div>
	<?PHP
	IF ($totalctx == 0)
{
  ?>
  <small><strong> <div class="notice"> * No Data  </div>  </strong></small>
  <?PHP
}
ELSE
{?>
		<script type="text/javascript">
      var myChart = new FusionCharts("FusionWidgets/HLinearGauge.swf", "myChartId", "430", "90", "0", "0");
    myChart.setDataURL("xml/CTXpatients.php?mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>");
	myChart.render("chartdivtrend0");
   </script>
   <?php
   }
   ?></td>
  </tr>

 

  
</table>
		
		</td>
		</tr>
		</table>
			
		
		
		
		
		
		
		<?php include("footer.php"); ?>