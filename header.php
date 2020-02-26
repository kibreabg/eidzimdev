<?php 
session_start();
include('protectpages.php');
require_once('connection/config.php');
include("nationaldashboardfunctions.php");
$mwaka=$_GET['year'];
$mwezi=$_GET['mwezi'];
$displaymonth=GetMonthName($mwezi);
$minimumyear = GetMinDatetestedYear(); //get the lowest year from date received
$maximumyear = GetMaxDateTestedYear();
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
?>
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
<link rel="shortcut icon" href="favicon.ico" >
  <link rel="icon" type="image/gif" href="animated_favicon1.gif" >
</head>

<body>

<div id="site-wrapper">

	<div id="header">

		<div id="top">

			<div class="left" id="logo">
				<img src="img/welfarelogo.png" alt="" />
			</div>
			<div align="right"><?php echo "Welcome". " <b>".$_SESSION['unames']."</b>".'<br>' ?><?php echo "<b>". date("l, d F Y")."</b>";?></div>
			<div class="clearer">&nbsp;</div>

		</div>

		<div class="navigation" id="sub-nav">

			<ul class="tabbed">
				<li><a href="overall.php">Overall</a></li>
				<li><a href="regional.php">Regional</a></li>
				<li><a href="prophylaxis.php">Prophylaxis</a></li>
				<li><a href="otherstats.php">Other Stats</a></li>
				<li><a href="labperformance.php">Lab Performance</a></li>
				<li><a href="nationalreports.php">Reports</a></li>
				<li><a href="logout.php">Logout</a></li>
			</ul>

			<div class="clearer">&nbsp;</div>

		</div>
<div class="navigation" >
			<ul class="tabbed">
				<table width="1111">
				<tr>	
				<td>	
				<li><?php
				$D=$_SERVER['PHP_SELF'];
					$year = $maximumyear;
						$twoless = $minimumyear;
						for ($year; $year>=$twoless; $year--)
  						{  

  							echo  "<a href=$D?year=$year>   $year  |</a>";    	     
  						}
						?>
						
				
				</li>
				</td>
				<td width='450'><li> &nbsp; </li></td>
				<td ><li><?php $year=$_GET['year'];
						if ($year=="")
{
$year=date('Y')-1;
} 
						 echo "<a href =$D?year=$year&mwezi=1>Jan</a>";?> | <?php echo "<a href =$D?year=$year&mwezi=2>Feb </a>";?>| <?php echo "<a href =$D?year=$year&mwezi=3>Mar</a>";?>  | <?php echo "<a href =$D?year=$year&mwezi=4>Apr</a>";?>  | <?php echo "<a href =$D?year=$year&mwezi=5>May</a>";?>  | <?php echo "<a href =$D?year=$year&mwezi=6>Jun</a>";?>  | <?php echo "<a href =$D?year=$year&mwezi=7>Jul</a>";?>  | <?php echo "<a href =$D?year=$year&mwezi=8>Aug</a>";?>  | <?php echo "<a href =$D?year=$year&mwezi=9>Sept</a>";?>  | <?php echo "<a href =$D?year=$year&mwezi=10>Oct</a>";?>  | <?php echo "<a href =$D?year=$year&mwezi=11>Nov</a>";?>  | <?php echo "<a href =$D?year=$year&mwezi=12>Dec</a>";?>  </li></td>
				
				</tr>
			  </table>	
			</ul>
		</div>
	</div>

	<div class="main" id="main-content">
	
  	<div  class="center" id="main-content">	