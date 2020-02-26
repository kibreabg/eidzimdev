<?php include("header.php");  
//include("nationaldashboardfunctions.php");  
	require_once('connection/config.php'); 
include("FusionMaps/FusionMaps.php");
include("FusionCharts/FusionCharts.php");
require_once('classes/tc_calendar.php');

 $ttestedsamples= 0;
  	$trejectedsamples=0;
	$tpositives=0;
	$tnegatives=0;
	$tindeter=0; 
	$tfailed=0;
	$tfacilityssupported=0;

$mwaka=$_GET['year'];
$mwezi=$_GET['mwezi'];
$lowestdate = GetAnyDateMin(); //get the lowest year from date received
$maximumyear = GetMaxYear();
$currentdate = date('Y'); //show the current year

$displaymonth=GetMonthName($mwezi);
if (isset($mwaka))
{
	if (isset($mwezi))
	{
	$defaultmonth=$displaymonth .' - '.$currentyear ; //get current month and year
	$currentmonth=$mwezi;
	$currentyear=$mwaka;
	}
	else
	{
	$defaultmonth=$currentyear ; //get current month and year
	$currentmonth="0";
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

 	if ($_REQUEST['filterlab'])
{
$enddate = $_GET['enddate'];
$startdate = $_GET['startdate'];//echo "Facility: ".$fname . " - ".$currentyear ." / " .$currentmonth;
$currentmonth="-3";
$startdatee = date("d-M-Y",strtotime($startdate));
$enddatee = date("d-M-Y",strtotime($enddate));
$defaultmonth=strtoupper($startdatee) . " TO ".strtoupper($enddatee) ; //get current month and year

}

//echo "tat " .GetReceivedatLabtoProcessingLABTAT(1,$month,$year,$startdate,$enddate)
?>
<script language="javascript" src="calendar.js"></script>
<link type="text/css" href="calendar.css" rel="stylesheet" />	

<div class="section-title" style="width:1260px">LAB PERFORMANCE STATS FOR <U><B><?php echo $defaultmonth; ?></B></U></div>
					<form id="customForm" method="get" action=""  >
       <table border="0">
	 
		
		  <tr >
          
          
         	 
         	 <td width="77" height="24" ><span class="style11">Start Date </span></td>
            <td   colspan="2" ><?php
			
			
	  $myCalendar = new tc_calendar("startdate", true, false);
	  $myCalendar->setIcon("img/iconCalendar.gif");
	  $myCalendar->setDate(date('d'), date('m'), date('Y'));
	  $myCalendar->setPath("./");
	  $myCalendar->setYearInterval($lowestdate, $currentdate );
	  //$myCalendar->dateAllow('1990-05-13', '2015-03-01');
	  $myCalendar->setDateFormat('j F Y');
	  //$myCalendar->setHeight(350);	  
	  //$myCalendar->autoSubmit(true, "form1");
	  $myCalendar->writeScript();
	  ?></td>
         
            <td width="69"  height="24" >End Date </td>
            <td  colspan="2" ><?php
	  $myCalendar = new tc_calendar("enddate", true, false);
	  $myCalendar->setIcon("img/iconCalendar.gif");
	  $myCalendar->setDate(date('d'), date('m'), date('Y'));
	  $myCalendar->setPath("./");
	  $myCalendar->setYearInterval($lowestdate,$currentdate );
	  //$myCalendar->dateAllow('1990-05-13', '2015-03-01');
	  $myCalendar->setDateFormat('j F Y');
	  //$myCalendar->setHeight(350);	  
	  //$myCalendar->autoSubmit(true, "form1");
	  $myCalendar->writeScript();
	  ?></td>
	  <td width="96" height="24">
	  <input name="filterlab" type="submit" value="Filter" size="2" class="button"/>	  </td>
          </tr>
		  </table>
		  </form>
<table width="1000" border="1" class="data-table">
  <tr>
   <th scope="col">No</th>
    <th scope="col">Lab</th>
	 <th scope="col">Facilities Serviced</th>
    <th  scope="col"> Samples Tested</th>
    <th scope="col" > Rejected Samples</th>
    <th  scope="col">Positives</th>
	    <th  scope="col">%</th>

    <th  scope="col">Negatives</th>
	  <th scope="col">%</th>
    <th scope="col">Redraws</th>
	  <th  scope="col">%</th>
	 <th scope="col">Indeterminate</th>
	  <th  scope="col">%</th>
  </tr>
  <?php 
  //overall tested samples
$rst = mysql_query( "CALL Gettestedsamplescount($currentyear,$currentmonth, @numsamples)" );
//$rst = mysql_query( "CALL Gettestedsamplescountfilter($currentyear,$currentmonth,$startdate,$enddate, @numsamples)" );
$rst = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dt=mysql_fetch_array($rst);
$overalltestedsamples=$dt['numsamples'];
//overall rejected samples
$rsw = mysql_query( "CALL Getnationalrejectedsamples($currentyear,$currentmonth, @numsamples)" );
$rsw = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dw=mysql_fetch_array($rsw);
$overallrejectedsamples=$dw['numsamples'];

//tested samples overall result negative
$rsu = mysql_query( "CALL Getnationaloutcome($currentyear,$currentmonth,1, @numsamples)" );
$rsu = mysql_query( "SELECT @numsamples as 'numsamples'" );
$du=mysql_fetch_array($rsu);
$negative=$du['numsamples'];
//negative percentage
if  ($negative !=0)
{
$negpercentage=round((($negative/$overalltestedsamples)*100),1);
}
else
{
$negpercentage=0;
}

//tested samples overall result positive
$rsi = mysql_query( "CALL Getnationaloutcome($currentyear,$currentmonth,2, @numsamples)" );
$rsi = mysql_query( "SELECT @numsamples as 'numsamples'" );
$di=mysql_fetch_array($rsi);
$positive=$di['numsamples'];
//positive percentage
if  ($positive !=0)
{
$pospercentage=round((($positive/$overalltestedsamples)*100),1);
}
else
{
$pospercentage=0;
}

//tested samples overall result failed
$rsr = mysql_query( "CALL Getnationaloutcome($currentyear,$currentmonth,3, @numsamples)" );
$rsr = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dr=mysql_fetch_array($rsr);
$fail=$dr['numsamples'];
//positive percentage
if  ($fail !=0)
{
$failpercentage=round((($fail/$overalltestedsamples)*100),1);
}
else
{
$failpercentage=0;
}
//tested samples overall result failed
$rsry = mysql_query( "CALL Getnationaloutcome($currentyear,$currentmonth,5, @numsamples)" );
$rsry = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dry=mysql_fetch_array($rsry);
$indeter=$dry['numsamples'];
//positive percentage
if  ($indeter !=0)
{
$indeterpercentage=round((($indeter/$overalltestedsamples)*100),1);
}
else
{
$indeterpercentage=0;
}
 $labquery= mysql_query("select ID,Name from labs ")or die(mysql_error());
 $no=0;
 //total
  $tnegpercentages=0;
 //echo  $currentmonth . " , ".$currentyear . " , ".$startdate. " , ". $enddate;
  while(list($ID,$Name)=mysql_fetch_array($labquery))
    { 
	
	$testedsamples= GetTestedSamplesPerlab($ID,$currentmonth,$currentyear,$startdate,$enddate);
  	$rejectedsamples=GetRejectedSamplesPerlab($ID,$currentmonth,$currentyear,$startdate,$enddate);
	$positives=GetTestedSamplesPerlabByResult($ID,$currentmonth,$currentyear,2,$startdate,$enddate) ;
	$negatives=GetTestedSamplesPerlabByResult($ID,$currentmonth,$currentyear,1,$startdate,$enddate) ;
	$indeter=GetTestedSamplesPerlabByResult($ID,$currentmonth,$currentyear,3,$startdate,$enddate) ; //not determined
	$failed=GetTestedSamplesPerlabByResult($ID,$currentmonth,$currentyear,5,$startdate,$enddate) ;//redraws
	$facilityssupported=GetSupportedfacilitysPerlab($ID,$currentmonth,$currentyear,3,$startdate,$enddate);


	 $ttestedsamples += $testedsamples;
	
  	$trejectedsamples+=$rejectedsamples;
	$tpositives+=$positives;
	$tnegatives+=$negatives;
	$tindeter+=$indeter; 
	$tfailed+=$failed;
	$tfacilityssupported+=$facilityssupported;
	
	/*if  ($testedsamples !=0)
	{*/
	//negative percentage
	if  ($negatives !=0)
	{
		$negpercentages=round((($negatives/$testedsamples)*100),1);
	}
	else
	{
		$negpercentages=0;
	}
	

	//positive percentage
	if  ($positives !=0)
	{
		$pospercentages=round((($positives/$testedsamples)*100),1);
	}
	else
	{
		$pospercentages=0;
	}
	
	

//positive percentage
	if  ($failed !=0)
	{
		$failpercentages=round((($failed/$testedsamples)*100),1);
	}
	else
	{
	$failpercentages=0;
	}
	
	//positive percentage
	if  ($indeter !=0)
	{
		$indeterpercentages=round((($indeter/$testedsamples)*100),1);
	}
	else
	{
	$indeterpercentages=0;
	}
	
/*}*/

$no++;
  ?>
  <tr class="even">
    <td><?php echo $no;?></td>
    <td><?php echo $Name;?></td>
	    <td><?php echo $facilityssupported;?></td>
    <td><?php echo $testedsamples;  ?></td>
    <td><?php echo $rejectedsamples; ?></td>
    <td><?php echo $positives; ?></td>
    <td><?php echo $pospercentages; ?></td>
    <td><?php echo $negatives; ?></td>
	 <td><?php echo $negpercentages; ?></td>
    <td><?php echo $failed; ?></td>
    <td><?php echo $failpercentages; ?></td>
    <td><?php echo $indeter; ?></td>
    <td><?php echo $indeterpercentages; ?></td>
  </tr>
   
  
  <?php 
  
  }
  ?>
   <tr>
    <td></td>
	<td></td>
    <td>Total</td>
    <td><?php echo  $ttestedsamples;//$overalltestedsamples;  ?></td>
    <td><?php echo $trejectedsamples;//$overallrejectedsamples; ?></td>
    <td><?php echo $tpositives;//$positive; ?></td>
    <td><?php if($ttestedsamples==0){ echo "0"; } else {  echo round((($tpositives/$ttestedsamples)*100),1); }// $pospercentage; ?></td>
    <td><?php echo $tnegatives;//$negative; ?></td>
	 <td><?php if($ttestedsamples==0){ echo "0"; } else {  echo round((($tnegatives/$ttestedsamples)*100),1); }//$negpercentage; ?></td>
    <td><?php echo $tfailed;//$fail; ?></td>
    <td><?php if($ttestedsamples==0){ echo "0"; } else {   echo round((($tfailed/$ttestedsamples)*100),1); }//$failpercentage; ?></td>
     <td><?php echo $tindeter;//$indeter; ?></td>
    <td><?php if($ttestedsamples==0){ echo "0"; } else {   echo round((($tindeter/$ttestedsamples)*100),1); }//$tindeterpercentage; ?></td>

	<!--$tfacilityssupported+=$facilityssupported;-->
  </tr>
</table>
<br />
<div class="section-title" >LAB Turn Around Times FOR <U><B><?php echo $defaultmonth; ?></B></U> </div>
<table width="950">
		<tr>
		<td><small><strong> Key  (in days)</strong></small></td>
		<td ><small><strong> <div class="error">Collection - Receipt at Lab 	
					</div>  </strong></small></td>
		<td ><small><strong> <div class="notice">Receipt at Lab - Processing at Lab	
					</div>  </strong></small></td>
		<td ><small><strong><div class="success">Processing at Lab - Dispatch from Lab	
					</div>   </strong></small></td>
					<td><small><strong> <div class="success2" >Total  Turn Around Time	{collection - Dispatch}
					</div>     
					</strong></small></td></tr>
	
		</table>
		<?php
		
		if ($_REQUEST['filterlab'])
{
$enddate = $_GET['enddate'];
$startdate = $_GET['startdate'];//echo "Facility: ".$fname . " - ".$currentyear ." / " .$currentmonth;
$currentmonth="-3";
$startdatee = date("d-M-Y",strtotime($startdate));
$enddatee = date("d-M-Y",strtotime($enddate));
$defaultmonth=strtoupper($startdatee) . " TO ".strtoupper($enddatee) ; //get current month and year

} 

?>	 
			 <table  border="0" width="1100">
			 
<tr>
    <td colspan="2" align="center">
	<div align="center">
	NMRL </div>
	<div id="chartdiv290" align="center">The chart will appear within this DIV. This text will be replaced by the chart.</div>
   <script type="text/javascript">
      var myChart290 = new FusionCharts("FusionWidgets/HLinearGauge.swf", "myChartId", "550", "100", "0", "0");
    myChart290.setDataURL("xml/labtat.php?lab=<?php echo 1;?>%26mwaka=<?php echo $currentyear;?>%26mwezi=<?php echo $currentmonth;?>%26startmonth=<?php echo $startdate;?>%26endmonth=<?php echo $enddate;?>");      
      myChart290.render("chartdiv290");
   </script></td>
   </tr>
 
</table>
      
   
 
   
<?php include("footer.php"); ?>