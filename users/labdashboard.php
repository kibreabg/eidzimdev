<?php
session_start();
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
include("../FusionMaps/FusionMaps.php");
include("../FusionCharts/FusionCharts.php");
include("../includes/labdashboardfunctions.php");
$labss = $_SESSION['lab']; //get lab id
$labname = GetLabName($labss); //get lab name
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
        $currentmonth = "";
        //get current month and year
        $currentyear = $mwaka;
    }
} else {
    $defaultmonth = date("M-Y"); //get current month and year
    $currentmonth = date("m");
    $currentyear = date("Y");
}
$receivedsamples = GetReceivedSamplesPerlab($labss, $currentmonth, $currentyear);
?>
<?php include('../includes/header.php'); ?>
<style type="text/css">
    select {width: 250;}
</style>	
<script type="text/javascript" src="../includes/jquery.min.js"></script>
<script type="text/javascript" src="../includes/jquery.js"></script>
<script type='text/javascript' src='../includes/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="../includes/jquery.autocomplete.css" />
<SCRIPT LANGUAGE="Javascript" SRC="../FusionMaps/JSClass/FusionMaps.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="../FusionCharts/FusionCharts.js"></SCRIPT>
<script language="JavaScript" src="../FusionWidgets/FusionCharts.js"></script>
<script type="text/javascript">
    $().ready(function() {
	
        $("#course2").autocomplete("get_facility.php", {
            width: 260,
            matchContains: true,
            mustMatch: true,
            //minChars: 0,
            //multiple: true,
            //highlight: false,
            //multipleSeparator: ",",
            selectFirst: false
        });
	
        $("#course2").result(function(event, data, formatted) {
            $("#course_val2").val(data[1]);
        });
    });
</script>
<script language="javascript" src="calendar.js"></script>

<link type="text/css" href="calendar.css" rel="stylesheet" />	
<script type="text/javascript">

    function masterCheck( btn, ca, ua )
    {
        var elems = btn.form.elements, state = (btn.value == ca);

        for( var i = 0, len = elems.length; i < len; i++ )
            if( elems[ i ].type && elems[ i ].type=='checkbox' )
                elems[ i ].checked = state;
   
        btn.value = state ? ua : ca;  
    }
</script>


<div  class="section">
    <div class="section-title"><?php echo $labname; ?> LAB DASHBOARD  FOR <strong><u><?php echo $defaultmonth; ?></u></strong></div>
    <div class="navigation" >

        <ul class="tabbed">
            <table>
                <tr>
                    <td>	
                <li>
                    <?php
                    $D = $_SERVER['PHP_SELF'];
                    $year = date('Y');
                    $twoless = $year - 3;
                    for ($year; $year >= $twoless; $year--) {
                        echo "<a href=$D?year=$year>   $year  |</a>";
                    }
                    ?>
                </li>
                </td>
                <td width='500'><li> &nbsp; </li></td>
                <td><li>
                    <?php
                    $year = $_GET['year'];
                    if ($year == "") {
                        $year = date('Y');
                    }
                    echo "<a href =$D?year=$year&mwezi=1>Jan</a>";
                    ?> | <?php echo "<a href =$D?year=$year&mwezi=2>Feb </a>"; ?>| <?php echo "<a href =$D?year=$year&mwezi=3>Mar</a>"; ?>  | <?php echo "<a href =$D?year=$year&mwezi=4>Apr</a>"; ?>  | <?php echo "<a href =$D?year=$year&mwezi=5>May</a>"; ?>  | <?php echo "<a href =$D?year=$year&mwezi=6>Jun</a>"; ?>  | <?php echo "<a href =$D?year=$year&mwezi=7>Jul</a>"; ?>  | <?php echo "<a href =$D?year=$year&mwezi=8>Aug</a>"; ?>  | <?php echo "<a href =$D?year=$year&mwezi=9>Sept</a>"; ?>  | <?php echo "<a href =$D?year=$year&mwezi=10>Oct</a>"; ?>  | <?php echo "<a href =$D?year=$year&mwezi=11>Nov</a>"; ?>  | <?php echo "<a href =$D?year=$year&mwezi=12>Dec</a>"; ?>  </li></td>

                </tr>
            </table>	
        </ul>
    </div>
    <div class="xtop">
        <table width="89%" border="0" cellpadding="2" cellspacing="2"   >
            <tr>
                <?php if (!isset($_GET["mwezi"]) || empty($_GET["mwezi"])) {
                    ?> <td width="51%"  valign="top" class="xtop" >

                        <div class="section-title">Monthly Tests Done Summary For the year <?php echo $currentyear; ?></div>

                        <div id="chartdiv5" align="center">The chart will appear within this DIV. This text will be replaced by the chart.</div>

                        <script type="text/javascript">
                            var myChart = new FusionCharts("../FusionCharts/MSLine.swf", "myChartId", "500", "300", "0", "0");
                            myChart.setDataURL("testpermonthlinegraph2.php?labss=<?php echo $labss; ?>%26currentyear=<?php echo $currentyear; ?>");
                            myChart.render("chartdiv5");
                        </script>
                        <!--<div class="section-title">Lab Summary as per Provinces Served:</div>
                        
                
                        <div id='mapDiv'>
                                        The map will replace this text. If any users do not have Flash Player 8 (or above), they'll see this message.
                                </div>
                                <script type="text/javascript">
                var map = new FusionMaps("../FusionMaps/FCMap_Kenya.swf", "KenyaMap", 600, 470, "0", "0");
                map.setDataURL("labmap.php?labss=<?php echo $labss; ?>%26currentmonth=<?php echo $currentmonth; ?>%26currentyear=<?php echo $currentyear; ?>");
                                        map.render("mapDiv");
                                </script>
                        -->
                    </td>
                <?php } ?>
                <td width="49%" valign="top"  class="xtop" >
                    <div class="section-title">Lab Statistics</div>
                    <table  border="0"  cellpadding="0" cellspacing="0">
                        <tr>
                            <td ><span class="style8">No. of Received Samples</span></td>
                            <td ><span class="style8"><a href="" title="Click to View Received Samples {in detail }" target="_blank"><?php echo $receivedsamples; ?></a></span></td>
                        </tr>
                        <tr> 
                            <td><span class="style8">No. of Rejected Samples</span></td>
                            <td><span class="style8"><a href="" title="Click to View Rejected Samples {in detail }" target="_blank"><?php echo GetRejectedSamplesPerlab($labss, $currentmonth, $currentyear); //. " [ ". GetRejectedpercentage($labss,$currentmonth,$currentyear)."% ] ";       ?></a></span></td>
                        </tr>
                        <tr>
                            <td ><span class="style8">No. of Tested Samples</span></td>
                            <td ><span class="style8"><a href="" title="Click to View Tested Samples {in detail }" target="_blank"><?php echo GetTestedSamplesPerlab($labss, $currentmonth, $currentyear); // . " [ ". GetTestedpercentage($labss,$currentmonth,$currentyear) ."% ]";     ?></a></span></td>
                        </tr>
                        <tr>
                            <td><span class="style8">No. of Positives { 1st test }</span></td>
                            <td> <span class="style8"><a href="" title="Click to View No of Positives from 1st PCR Test" target="_blank"><?php echo GetTestedSamplesPerlabByResult($labss, $currentmonth, $currentyear, 2); //. " [ ". GetTestedpercentagePerResult($labss,$currentmonth,$currentyear,2)."% ] ";      ?></a></span></td>
                        </tr>
                        <tr>
                            <td><span class="style8">No. of Negatives</span></td>
                            <td> <span class="style8"><a href="" title="Click to View No of Negatives" target="_blank"><?php echo GetTestedSamplesPerlabByResult($labss, $currentmonth, $currentyear, 1); // . " [ ". GetTestedpercentagePerResult($labss,$currentmonth,$currentyear,1)."% ] ";      ?></a></span> </td>
                        </tr>
                        <tr>
                            <td><span class="style8">No. of Indeterminates</span></td>
                            <td><span class="style8"><a href="" title="Click to View No of Indeterminates" target="_blank"><?php echo GetTestedSamplesPerlabByResult($labss, $currentmonth, $currentyear, 3); // . " [ ". GetTestedpercentagePerResult($labss,$currentmonth,$currentyear,3)."% ] ";      ?></a></span></td>
                        </tr>
                        <tr>
                            <td><span class="style8">No. of Repeats </span></td>
                            <td><span class="style8"><a href="" title="Click to View No of Repeats" target="_blank"><?php echo GetTestedRepeatSamplesPerlab($labss, $currentmonth, $currentyear); ?></a></span></td>
                        </tr>
                        <?php /* ?>
                          <tr>
                          <td><span class="style8">TAT [Collection - Receipt at lab] </span></td>
                          <td><span class="style8"><?php 	list($numsamples, $ave) = GetColletiontoReceivedatLabTAT($labss,$currentmonth,$currentyear); echo $ave . " days [Samples:".$numsamples . " - (";
                          if ($numsamples !=0)
                          {
                          echo round((($numsamples/$receivedsamples)*100),2) . "% of Received Samples) ]";
                          }
                          else
                          {
                          echo  "0% of Received Samples) ]";
                          } ?></span></td>
                          </tr>
                          <tr>
                          <td><span class="style8">TAT [Receipt at lab - Testing ] </span></td>
                          <td><span class="style8"><?php 	list($numsamples, $ave) = GetReceivedatLabtoTestingTAT($labss,$currentmonth,$currentyear); echo $ave . " days [Samples:".$numsamples . " - (";
                          if ($numsamples !=0)
                          {
                          echo round((($numsamples/$receivedsamples)*100),2) . "% of Received Samples) ]";
                          }
                          else
                          {
                          echo  "0% of Received Samples) ]";
                          } ?></span></td>
                          </tr>
                          <tr>
                          <td><span class="style8">TAT [Testing - Update Results ] </span></td>
                          <td><span class="style8"><?php 	list($numsamples, $ave) = GetTestedatLabtoUpdateTAT($labss,$currentmonth,$currentyear); echo $ave . " days [Samples:".$numsamples . " - (";
                          if ($numsamples !=0)
                          {
                          echo round((($numsamples/$receivedsamples)*100),2) . "% of Received Samples) ]";
                          }
                          else
                          {
                          echo  "0% of Received Samples) ]";
                          } ?></span></td>
                          </tr>
                          <tr>
                          <td><span class="style8">TAT [Update Results - Release for Printing] </span></td>
                          <td><span class="style8"><?php 	list($numsamples, $ave) = GetUpdatedResultsatLabtoReleaseforPrintingTAT($labss,$currentmonth,$currentyear); echo $ave . " days [Samples:".$numsamples . " - (";
                          if ($numsamples !=0)
                          {
                          echo round((($numsamples/$receivedsamples)*100),2) . "% of Received Samples) ]";
                          }
                          else
                          {
                          echo  "0% of Received Samples) ]";
                          } ?></span></td>
                          </tr>
                          <tr>
                          <td><span class="style8">TAT [Release for Printing - Dispatch] </span></td>
                          <td><span class="style8"><?php 	list($numsamples, $ave) = GetReleaseforPrintingatLabtoDispatchTAT($labss,$currentmonth,$currentyear); echo $ave . " days [Samples:".$numsamples . " - (";
                          if ($numsamples !=0)
                          {
                          echo round((($numsamples/$receivedsamples)*100),2) . "% of Received Samples) ]";
                          }
                          else
                          {
                          echo  "0% of Received Samples) ]";
                          } ?></span></td>
                          </tr>
                          <?php

                          <tr>
                          <td><span class="style8">Total TAT [Collection - Dispatch] </span></td>
                          <td><span class="style8"><?php list($numsamples, $ave) = GetCollectedtoDispatchTAT($labss,$currentmonth,$currentyear); echo $ave . " days [Samples:".$numsamples . " - (";
                          if ($numsamples !=0)
                          {
                          echo round((($numsamples/$receivedsamples)*100),2) . "% of Received Samples) ]";
                          }
                          else
                          {
                          echo  "0% of Received Samples) ]";
                          }?></span></td>
                          </tr>
                         */ ?>


                    </table>
                </td>
            </tr>

            <tr>
                <td   valign="top" class="xtop" >

                    <div class="section-title">Monthly Tests Results Summary For the year <?php echo $currentyear; ?></div>


                    <div id="chartdivtotaltests" align="center"> </div>
                    <script type="text/javascript">
                        var myChart = new FusionCharts("../FusionCharts/StackedColumn3DLineDY.swf ", "myChartId", "500", "340", "0", "0");
                        myChart.setDataURL("../xml/labtestsdone.php?mwaka=<?php echo $currentyear; ?>%26lab=<?php echo $labss; ?>");
                        myChart.render("chartdivtotaltests");
                    </script>

                </td>
                <td   valign="top" class="xtop" >
                    <div class="section-title">Lab TAT</div><table width="500">
                        <tr>

                            <td ><small><strong> Key </strong></small><small><strong> <div class="success4">Collection - Receipt at Lab	
                                        </div>  </strong></small></td><td ><small><strong><div class="notice">Receipt at Lab - Testing 	 </div>   
                                    </strong></small></td>
                            <td ><small><strong> <div class="success">Testing - Update	
                                        </div>  
                                    </strong></small></td>
                        </tr>
                        <tr>
                            <td ><small><strong><div class="success3">Update - Release 	 </div>   
                                    </strong></small></td>
                            <td ><small><strong><div class="error"> Release - Dispatch	 </div>   
                                    </strong></small></td>
                            <td><small><strong> <div class="success2" >Total TAT	
                                        </div>     
                                    </strong></small></td></tr>

                    </table>
                    <div id="chartdiv29" align="center">The chart will appear within this DIV. This text will be replaced by the chart.</div>
                    <script type="text/javascript">
                        var myChart = new FusionCharts("../FusionWidgets/HLinearGauge.swf", "myChartId", "550", "100", "0", "0");
                        myChart.setDataURL("../xml/labdashboardtat.php?lab=<?php echo $labss; ?>%26mwaka=<?php echo $currentyear; ?>%26mwezi=<?php echo $currentmonth; ?>");      
                        myChart.render("chartdiv29");
                    </script>


                </td>

            </tr>	 

        </table>


    </div>

    <?php include('../includes/footer.php'); ?>