<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<meta name="description" content=""/>
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
	<title>EID: Early Infant Diagonistics System</title>
	<script language="JavaScript" src="scripts/FusionMaps.js"></script>
	<script language="JavaScript" src="scripts/FusionCharts.js"></script>

</head>

<body>

<div id="site-wrapper">

	<div id="header">

		<div id="top">

			<div class="left" id="logo">
				<a href="index.html"><img src="img/logo.jpg" alt="" /></a> 
			</div>
			
			<div class="clearer">&nbsp;</div>

		</div>

		<div class="navigation" id="sub-nav">

			<ul class="tabbed">
				<li><a href="overall.html">Overall</a></li>
				<li><a href="regional.html">Regional</a></li>
				<li><a href="positivity.html">Positivity</a></li>
				<li><a href="prophylaxis.html">Prophylaxis</a></li>
				<li><a href="reports.html">Reports</a></li>
			</ul>

			<div class="clearer">&nbsp;</div>

		</div>

	</div>

	<div class="main" id="main-content">
	
  	<div  class="center" id="main-content">
		
		<table width="100%" cellpadding="5" cellspacing="5">
		<tr><td  width="50%">
		<div class="section-title">Entry points</div>
		<div id="chartdiv1" align="center">  New Vs. renewal. </div>
		<script type="text/javascript"> 
		   var chart = new FusionCharts("FusionCharts/Charts/Pie2D.swf", "ChartId", "400", "300", "0", "0");
		   chart.setDataURL("xml/entrypoints.xml");		   
		   chart.render("chartdiv1");
		</script>
		</td>
		<td valign="top">
		<div class="section-title">Lab Turn Around Time</div>
		<div id="chartdiv2" align="center">  Coverage </div>
		<script type="text/javascript"> 
	var myChart = new FusionCharts("FusionWidgets/Charts/AngularGauge.swf", "myChartId", "400", "300", "0", "0");
	myChart.setDataURL("xml/coverage.xml");
	myChart.render("chartdiv2");
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