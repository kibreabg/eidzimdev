<?php
include("header.php");
include ("users/select.class.php");
require_once("connection/config.php");
include("FusionMaps/FusionMaps.php");
include("FusionCharts/FusionCharts.php");

$mwaka = $_GET['year'];
$mwezi = $_GET['mwezi'];
$displaymonth = GetMonthName($mwezi);
if (isset($mwaka)) {
    if (isset($mwezi)) {
        $defaultmonth = $displaymonth . ' - ' . $mwaka; //get current month and year
        $currentmonth = $mwezi;
        $currentyear = $mwaka;
    } else {
        $defaultmonth = $mwaka; //get current month and year
        $currentmonth = 0;
        //get current month and year
        $currentyear = $mwaka;
    }
} else {
    $defaultmonth = date("M-Y"); //get current month and year
    $currentmonth = date("m");
    $currentyear = date("Y");
}
//$natAge=Getoverallaverageage($currentyear,$currentmonth);

$rs4 = mysql_query("CALL Getoverallaverageage($currentyear,$currentmonth, @averageage)");
$rs4 = mysql_query("SELECT @averageage as 'averageage'");
$d4 = mysql_fetch_array($rs4);
$natAge = round($d4['averageage'], 1);

//overall tested samples
$rst = mysql_query("CALL Gettestedsamplescount($currentyear,$currentmonth, @numsamples)");
$rst = mysql_query("SELECT @numsamples as 'numsamples'");
$dt = mysql_fetch_array($rst);
$overalltestedsamples = $dt['numsamples'];

//tested samples overall result negative
$rsu = mysql_query("CALL Getnationaloutcome($currentyear,$currentmonth,1, @numsamples)");
$rsu = mysql_query("SELECT @numsamples as 'numsamples'");
$du = mysql_fetch_array($rsu);
$negative = $du['numsamples'];
//negative percentage
if ($negative != 0) {
    $negpercentages = round((($negative / $overalltestedsamples) * 100), 1);
} else {
    $negpercentages = 0;
}

//tested samples overall result positive
$rsi = mysql_query("CALL Getnationaloutcome($currentyear,$currentmonth,2, @numsamples)");
$rsi = mysql_query("SELECT @numsamples as 'numsamples'");
$di = mysql_fetch_array($rsi);
$positive = $di['numsamples'];
//positive percentage
if ($positive != 0) {
    $pospercentages = round((($positive / $overalltestedsamples) * 100), 1);
} else {
    $pospercentages = 0;
}

//tested samples overall result failed
$rsr = mysql_query("CALL Getnationaloutcome($currentyear,$currentmonth,3, @numsamples)");
$rsr = mysql_query("SELECT @numsamples as 'numsamples'");
$dr = mysql_fetch_array($rsr);
$fail = $dr['numsamples'];
//positive percentage
if ($fail != 0) {
    $failpercentages = round((($fail / $overalltestedsamples) * 100), 1);
} else {
    $failpercentages = 0;
}

//tested samples overall result failed
$rsr = mysql_query("CALL Getnationaloutcome($currentyear,$currentmonth,5, @numsamples)");
$rsr = mysql_query("SELECT @numsamples as 'numsamples'");
$dr = mysql_fetch_array($rsr);
$indeter = $dr['numsamples'];
//positive percentage
if ($fail != 0) {
    $indeterpercentages = round((($indeter / $overalltestedsamples) * 100), 1);
} else {
    $indeterpercentages = 0;
}

//overall rejected samples
$rsw = mysql_query("CALL Getnationalrejectedsamples($currentyear,$currentmonth, @numsamples)");
$rsw = mysql_query("SELECT @numsamples as 'numsamples'");
$dw = mysql_fetch_array($rsw);
$overallrejectedsamples = $dw['numsamples'];
?>	
<script type="text/javascript" src="includes/jquery.min.js"></script>
<script type="text/javascript">    
    $().ready(function(){        
        $.urlParam = function(name){
            var results = new RegExp('[\\?&amp;]' + name + '=([^&amp;#]*)').exec(window.location.href);
            return results[1] || 0;
        }	
        
        $("select#type").attr("disabled","disabled");
        $("select#category").change(function(){
            $("select#type").attr("disabled","disabled");
            $("select#type").html("<option>wait...</option>");
            var id = $("select#category option:selected").attr('value');
            // 
            $.post("users/select_type.php", {id:id}, function(data){
		
                $("select#type").removeAttr("disabled");
                $("select#type").html(data);
            });
        });
        
        var currentYear = $.urlParam('year');
        $("select#type").change(function(){
            window.location.href = "smsqueuereport.php?facility=" + $("select#type").val() + "&year=" + currentYear;
        });
    });
</script>

<table cellpadding="0" cellspacing="0">
    <tr>
        <td>
            District:
            <select id="category" name="district">
                <?php echo $opt->ShowCategory(); ?>
            </select>
        </td>
        <td>
            Facility:
            <select id="type" name="facility">
                <option value="0">choose...</option>
            </select>
        </td>
    </tr>
</table>
<table width="100%" cellpadding="5" cellspacing="5">
    <tr>
        <td>
            <div class="section-title">Successful Vs Failed SMS Prints (Overall) </div>
            <div id="chartdivs" align="center"></div>
            <script type="text/javascript">		  
                var chart = new FusionCharts("FusionCharts/Charts/Pie3D.swf", "ChartId", "300", "250", "0", "0");
                chart.setDataURL("xml/smsqueuepie.php?mwaka=<?php echo $currentyear; ?>%26mwezi=<?php echo $currentmonth; ?>");		   
                chart.render("chartdivs");
            </script>
        </td>
        <td>
            <div class="section-title">SMS print success By Province (Overall)</div>
            <div id="chartdiv4" align="left">The chart will appear within this DIV. This text will be replaced by the</div>
            <script type="text/javascript">
                var myChart = new FusionCharts("FusionCharts/StackedColumn2D.swf", "myChartId", "780", "250", "0", "0"); 				
                myChart.setDataURL("xml/smsqueuebyprovince.php?mwaka=<?php echo $currentyear; ?>%26mwezi=<?php echo $currentmonth; ?>");
                myChart.render("chartdiv4");
            </script>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class="section-title">SMS print success by Facility (Yearly)</div>
            <div id="chartByFacYr" align="left">The chart will appear within this DIV. This text will be replaced by the</div>
            <script type="text/javascript">
                var myChart = new FusionCharts("FusionCharts/StackedColumn2D.swf", "myChartId", "900", "250", "0", "0"); 				
                myChart.setDataURL("xml/smsqueuebyfacilityyr.php?facility=<?php echo $_GET['facility']; ?>");
                myChart.render("chartByFacYr");
            </script>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class="section-title">SMS print success by Facility (Monthly)</div>
            <div id="chartByFacMt" align="left">The chart will appear within this DIV. This text will be replaced by the</div>
            <script type="text/javascript">
                var myChart = new FusionCharts("FusionCharts/StackedColumn2D.swf", "myChartId", "900", "250", "0", "0"); 	
                var url = escape("xml/smsqueuebyfacilitymth.php?facility=<?php echo $_GET['facility']; ?>&year=<?php echo $_GET['year']; ?>");
                myChart.setDataURL(url);
                myChart.render("chartByFacMt");
            </script>
        </td>
    </tr>
</table>
<?php
include("footer.php");
?>
