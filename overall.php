<?php
include("header.php");
require_once('connection/config.php');
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


//overall tested samples less than 2 months
$rss = mysql_query("CALL Gettestedsamplescountlessthan2months($currentyear,$currentmonth, @numsamples)");
$rss = mysql_query("SELECT @numsamples as 'numsamples'");
$ds = mysql_fetch_array($rss);
$overalltestedsamplesless2months = $ds['numsamples'];

//overall tested samples (first test only)
$rs7 = mysql_query("CALL Getoverallfirsttestedsamples($currentyear,$currentmonth, @numsamples)");
$rs7 = mysql_query("SELECT @numsamples as 'numsamples'");
$dt7 = mysql_fetch_array($rs7);
$overallfirsttestedsamples = $dt7['numsamples'];

//overall tested samples (confirmatory at 9months)
$rstu = mysql_query("CALL Getoverallconfirmatorytestedsamples($currentyear,$currentmonth, @numsamples)");
$rstu = mysql_query("SELECT @numsamples as 'numsamples'");
$dtu = mysql_fetch_array($rstu);
$overallconfirmatorytestedsamples = $dtu['numsamples'];

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
<table width="100%" cellpadding="5" cellspacing="5">
    <tr><td colspan="2"><a href="regional.php?province=<?php echo 1; ?>&mwezi=<?php echo $currentmonth; ?>&year=<?php echo $currentyear; ?>" target="_blank">Harare </a> | <a href="regional.php?province=<?php echo 6; ?>&mwezi=<?php echo $currentmonth; ?>&year=<?php echo $currentyear; ?>" target="_blank">Matebeland North</a> | <a href="regional.php?province=<?php echo 8; ?>&mwezi=<?php echo $currentmonth; ?>&year=<?php echo $currentyear; ?>" target="_blank">Midlands</a> | <a href="regional.php?province=<?php echo 5; ?>&mwezi=<?php echo $currentmonth; ?>&year=<?php echo $currentyear; ?>" target="_blank" >Mash West</a> | <a href="regional.php?province=<?php echo 3; ?>&mwezi=<?php echo $currentmonth; ?>&year=<?php echo $currentyear; ?>" target="_blank">Mash Central</a> | <a href="regional.php?province=<?php echo 4; ?>&mwezi=<?php echo $currentmonth; ?>&year=<?php echo $currentyear; ?>" target="_blank">Mash East</a> | <a href="regional.php?province=<?php echo 2; ?>&mwezi=<?php echo $currentmonth; ?>&year=<?php echo $currentyear; ?>" target="_blank">Manicaland </a> | <a href="regional.php?province=<?php echo 9; ?>&mwezi=<?php echo $currentmonth; ?>&year=<?php echo $currentyear; ?>" target="_blank">Masvingo </a> | <a href="regional.php?province=<?php echo 7; ?>&mwezi=<?php echo $currentmonth; ?>&year=<?php echo $currentyear; ?>" target="_blank">Matebeland South </a> |  <a href="regional.php?province=<?php echo 10; ?>&mwezi=<?php echo $currentmonth; ?>&year=<?php echo $currentyear; ?>" target="_blank">Bulawayo </a></td></tr>
</table>
<fieldset><legend>Printable Reports</legend>
    <?php echo "<a href='users/overallexcel.php?year=$currentyear&mwezi=$currentmonth&labss=$labss' title='Click to Download Excel Report' target='_blank'><img src='img/excel.gif' alt='Excel'>&nbsp;<small>EXCEL</small></a>";  ?>
</fieldset>
<table width="113%" cellpadding="5" cellspacing="5">
    <tr><td  width="73%" rowspan="4" align="left" valign="top" class="xtop">
            <div class="section-title">Zimbabwe Summary as of <?php echo $defaultmonth; ?></div>
            <div id="mapDiv">
                The map will replace this text. If any users do not have Flash Player 8 (or above), they'll see this message.
            </div>
            <script type="text/javascript">
                var map = new FusionMaps("FusionMaps/Maps/FCMap_Zimbabwe.swf", "ZimbabweMap", "650", "550", "0", "0");
                
                map.setDataURL("xml/map.php?mwaka=<?php echo $currentyear; ?>%26mwezi=<?php echo $currentmonth; ?>");
                map.render("mapDiv");
            </script>

            <small><strong><div class="success"> * Hoover over the Map to view Provincial Statistics. <br> * Click on a Province to view Detailed  Statistics </div>  </strong></small>
        </td>
        <td width="60%" valign="top">
            <div class="section-title">National Statistics</div>
            <table width="90%" border="0"  cellpadding="0" cellspacing="0">
                <tr>
                    <td><span class="style8">No. of All  PCR Tests</span></td>
                    <td><span class="style8"><?php echo $overalltestedsamples; ?></span></td>
                </tr>
               <!-- <tr>
                  <td ><span class="style8">No. of 1st DNA PCR Tests</span></td>
                  <td ><span class="style8"><?php //echo  $overallfirsttestedsamples;   ?></span></td>
                </tr>
                <tr>
                  <td ><span class="style8">No. of 2nd DNA PCR Tests</span></td>
                  <td ><span class="style8"><?php //echo  $overallconfirmatorytestedsamples;   ?></span></td>
                </tr>-->
                <tr>
                    <td><span class="style8">No. of Rejected Samples</span></td>
                    <td><span class="style8"><?php echo $overallrejectedsamples; ?></span></td>
                </tr>
                <tr>
                    <td><span class="style8">No. of Redraw Samples</span></td>
                    <td><span class="style8"><?php echo $indeter; ?></span></td>
                </tr>

                <tr>
                    <td><span class="style8">Average Age of Testing </span></td>
                    <td><span class="style8">
                            <?php
                            //OVERALL AGE OF TESTING 
                            echo $natAge . ' months ';
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><span class="style8">Total Number of Health Facilities </span></td>
                    <td><span class="style8"><?php echo Gettotalsites(0, 0); ?></span></td>
                </tr>

                <tr>
                    <td><span class="style8">Total Number of EID Facilities </span></td>
                    <td><span class="style8"><?php echo GettotalEIDsites(0, 0); ?></span></td>
                </tr>

            </table>
        </td>
    </tr>
    <tr>
        <td class="xtop"  valign="top"> <div class="section-title">EID Results</div>
            <div id="chartdivs" align="center">  Funds bar</div>
            <script type="text/javascript">
		  
                var chart = new FusionCharts("FusionCharts/Charts/Pie3D.swf", "ChartId", "540", "150", "0", "0");
                chart.setDataURL("xml/eidresultspie.php?mwaka=<?php echo $currentyear; ?>%26mwezi=<?php echo $currentmonth; ?>");		   
                chart.render("chartdivs");
            </script>

        </td>
    </tr>
    <tr>
        <td><div class="section-title">Average Age of Testing</div>
            <div id="chartdiv2"  > </div>
            <script type="text/javascript" >
                var myChart = new FusionCharts("FusionWidgets/AngularGauge.swf", "myChartId", "540", "150", "0", "0");
                myChart.setDataXML("<chart lowerLimit='0' bgColor='#FFFFFF' showBorder='0' tickValueDistance='25'upperLimit='18' lowerLimitDisplay='6wks' upperLimitDisplay='18mths' gaugeStartAngle='180' gaugeEndAngle='0' palette='3' numberSuffix='mths' tickValueDistance='20' showValue='1'><colorRange><color minValue='0' maxValue='10' code='8BBA00'/><color minValue='10' maxValue='15' code='FF654F'/><color minValue='15' maxValue='18' code='F6BD0F'/></colorRange><dials><dial value='<?php echo $natAge; ?>'  rearExtension='10' baseWidth='1' /></dials></chart>");
                myChart.render("chartdiv2");
            </script>
        </td>
    </tr>
</table>
</td>
</tr>
</table>
<table width="100%" cellpadding="5" cellspacing="5">
    <tr>
        <td>
            <div class="section-title"> EID Trend  Per Month for  <?php echo $currentyear; ?></div>
            <div id="chartdivtrend" align="center"> </div>
            <script type="text/javascript">
                var myChart = new FusionCharts("FusionCharts/Line.swf", "myChartId", "400", "250", "0", "0");
                myChart.setDataURL("xml/yearlytrend.php?mwaka=<?php echo $currentyear; ?>");
                myChart.render("chartdivtrend");
            </script> 
        </td>
        <td>
            <div class="section-title">EID Results by Entry Point </div>
            <div id="chartdiv66" align="center"></div>
            <script type="text/javascript">
                var myChart2 = new FusionCharts("FusionCharts/StackedColumn2D.swf", "myChartId", "500", "250", "0", "0");
             
                myChart2.setDataURL("xml/nationalresultsbyentrypoint.php?mwaka=<?php echo $currentyear; ?>%26mwezi=<?php echo $currentmonth; ?>");
                myChart2.render("chartdiv66");
            </script></td>
        <td>
            <div class="section-title">EID Results by Age of Testing </div>
            <div id="chartdiv7" align="center">The chart will appear within this DIV. This text will be replaced by the chart.</div>
            <script type="text/javascript">
                var myChart = new FusionCharts("FusionCharts/StackedColumn2D.swf", "myChartId", "300", "250", "0", "0");
      
                myChart.setDataURL("xml/ages.php?mwaka=<?php echo $currentyear; ?>%26mwezi=<?php echo $currentmonth; ?>");
                myChart.render("chartdiv7");
            </script></td>
    </tr>
    <tr>
        <td colspan="2">
            <div class="section-title">Tests By Province</div>
            <div id="chartdiv4" align="left">The chart will appear within this DIV. This text will be replaced by the				  </div>
            <script type="text/javascript">
                var myChart = new FusionCharts("FusionCharts/StackedColumn2D.swf", "myChartId", "780", "300", "0", "0");
 				
                myChart.setDataURL("xml/provincepositivity.php?mwaka=<?php echo $currentyear; ?>%26mwezi=<?php echo $currentmonth; ?>");
                myChart.render("chartdiv4");
            </script> 
        </td>
        <td valign="top" class="xtop"> 
            <div class="section-title">% of Positive   Results by Province </div>

            <div id="chartdiv44" align="center">The chart will appear within this DIV. This text will be replaced by the				  </div>
            <script type="text/javascript">
                var myChart = new FusionCharts("FusionCharts/pie2D.swf", "myChartId", "290", "300", "0", "0");
 				
                myChart.setDataURL("xml/provincepositivitypie.php?mwaka=<?php echo $currentyear; ?>%26mwezi=<?php echo $currentmonth; ?>");
                myChart.render("chartdiv44");
            </script>  
        </td>
    </tr>
</table>
<?php
include("footer.php");
?>
