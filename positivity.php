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
$defaultmonth=date("Y")-1; //get current month and year
//$currentmonth=date("m");
$currentmonth=0;
$currentyear=date("Y")-1;;
}
	
	?>
		<table width="100%" cellpadding="5" cellspacing="5">
		<tr><td  width="50%">
		<div class="section-title">Positivity By Province as of <?php echo $defaultmonth; ?></div>
		<div id="chartdiv12" align="center">  New Vs. renewal. </div>
		<script type="text/javascript"> 
		   var chart = new FusionCharts("FusionCharts/Charts/StackedColumn2D.swf", "ChartId", "500", "300", "0", "0");
		   chart.setDataURL("xml/ProvincePositivity.php?mwaka=<?php echo $currentyear; ?>%26mwezi=<?php echo $currentmonth;?>");		   
		   chart.render("chartdiv12");
		</script>
		</td>
		<td valign="top">
		<div class="section-title">Positivity By BreastFeeding Type</div>
		<div id="chartdiv1" align="center">  New Vs. renewal. </div>
		<script type="text/javascript"> 
		   var chart = new FusionCharts("FusionCharts/Charts/StackedColumn2D.swf", "ChartId", "500", "300", "0", "0");
		   chart.setDataURL("xml/breastfeeding.php?mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>");		   
		   chart.render("chartdiv1");
		</script>
		
		 </td>
		 
		 </tr>
		 <tr>
		<td>
		<div class="section-title">Average Age Ranges by Positivity</div>
		<div id="chartdiv3" align="center">  New Vs. renewal. </div>
		<script type="text/javascript"> 
		   var chart = new FusionCharts("FusionCharts/Charts/StackedColumn2D.swf", "ChartId", "500", "300", "0", "0");
		   chart.setDataURL("xml/ages.php?mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>");		   
		   chart.render("chartdiv3");
		</script>
		
		</td>
		<td>
		<div class="section-title">Positvity vs Mode of Delivery</div>
		<div id="chartdiv5" align="center">  New Vs. renewal. </div>
		<script type="text/javascript"> 
		   var chart = new FusionCharts("FusionCharts/Charts/StackedColumn2D.swf", "ChartId", "400", "300", "0", "0");
		   chart.setDataURL("xml/delivery.php?mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>");		   
		   chart.render("chartdiv5");
		</script></td>
		</tr>
		</table>
		
		
		
		
		
		
		
		
		
		</div>

		<div class="clearer">&nbsp;</div>

	</div>

	<div id="footer">

		<div class="right" id="footer-right">
			
		 <p>&copy; 1987-2010 MOHCW.ZW All rights Reserved</p>

			<p class="quiet"></p>
			
			<div class="clearer">&nbsp;</div>

		</div>


		<div class="clearer">&nbsp;</div>

	</div>

</div>

</body>
</html>