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
	$y=Getinfantsonfeeding(1,$currentyear,$currentmonth);
$n=Getinfantsonfeeding(2,$currentyear,$currentmonth);
$unk=Getinfantsonfeeding(3,$currentyear,$currentmonth);
$total=$y + $n + $unk;
	?>
		<table width="100%" cellpadding="5" cellspacing="5">
		<tr>
		<td  width="50%">
			<div class="section-title">Test Results by Infant Feeding Options for <?php echo $defaultmonth;?></div>
		<div id="chartdiv1" align="center">  New Vs. renewal. </div>
		<script type="text/javascript"> 
		   var chart = new FusionCharts("FusionCharts/Charts/StackedColumn2D.swf", "ChartId", "500", "246", "0", "0");
		   chart.setDataURL("xml/breastfeeding.php?mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>");		   
		   chart.render("chartdiv1");
		</script>
		</td>
		<td valign="top">
		<div class="section-title">Statistics</div>
		 <table width="90%" border="0"  cellpadding="0" cellspacing="0">
 

  <tr>
    <td ><span class="style8">No. of Infants Breast Fed in last 6 weeks :- <?php echo  $y; ?></span></td>
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
      var myChart = new FusionCharts("FusionWidgets/HLinearGauge.swf", "myChartId", "480", "120", "0", "0");
    myChart.setDataURL("xml/fedinfants.php?mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>");
	myChart.render("chartdivtrend44");
   </script>
   <br><br/><br/><br/>
   <?PHP
   }
   ?></td>
  </tr>
 
  

 

  
</table>
		</tr>
		<tr>
		<td valign="top" colspan="2">
	
		<div class="section-title">Delivery Mode by Result</div>
		<div id="chartdiv5" align="center">  New Vs. renewal. </div>
		<script type="text/javascript"> 
		   var chart = new FusionCharts("FusionCharts/Charts/StackedBar2D.swf", "ChartId", "800", "300", "0", "0");
		   chart.setDataURL("xml/delivery.php?mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>");		   
		   chart.render("chartdiv5");
		</script>
		 </td>
		 
		 </tr>
		
		</table>
		
		
		
		
		
		
		
		
		
		<?php
	include("footer.php");
?>
