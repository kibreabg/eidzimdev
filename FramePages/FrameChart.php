<html>
<head>
	<title>Frame Example</title>
	<script language="JavaScript" src="FusionCharts/FusionCharts.js"></script>
</head>

<body bgcolor="#ffffff">

<div id="chartdiv" align="center">The chart will appear within this DIV. This text will be replaced by the chart.</div>
   <script type="text/javascript">
	var myChart = new FusionCharts("FusionCharts/Column2D.swf", "myChartId", "500", "300", "0", "0");
	myChart.setDataURL("FrameData.xml");
	myChart.render("chartdiv");
   </script>

</body>
</html>
