<?php
session_start();
require_once('../connection/config.php');
include('functions.php');
$top="Top";
$side="Side";
		//$_SESSION['unames'] = $userrec['surname'].' '.$userrec['oname'];
				
				$userid-$_SESSION['uid'] ;
				$usergrup=$_SESSION['uaccount'];
				$userlab=$_SESSION['lab'];
				
	 //query for top bar
	 $result = mysql_query("SELECT menu from groupmenus,menus where groupmenus.usergroup='$usergrup' AND menus.ID=groupmenus.menu AND  menus.location='$top'") or die(mysql_error());
	 //query for side bar
     $result2 = mysql_query("SELECT  menu from groupmenus,menus where groupmenus.usergroup='$usergrup' AND menus.ID=groupmenus.menu AND  menus.location='$side'") or die(mysql_error());
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<meta name="description" content=""/>
	<meta name="keywords" content="" />
	<meta name="author" content="" />
<link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
	<title>Early Infant Diagnosis Program</title>


</head>

<body>
<div id="site-wrapper">

	<div id="header">
		<!--top-->
		<div id="top">

			<div class="left" id="logo"><img src="../img/logo.jpg"/></div>
			<div align="right"><?php echo "Welcome". " <b>".	$_SESSION['unames'] ."</b>".'<br>'."<b>". date("l, d F Y")."</b>".'<br>'." Last Access". " <b>".	$_SESSION['uaccess'] ."</b>" ?></div>
			<div class="clearer">&nbsp;</div>

		</div>
		<!--end top-->
		
		<!--menu-->
		<div class="navigation" id="sub-nav">
			<ul class="tabbed">
				
				<?php  while(list($menu) = mysql_fetch_array($result))
				{ 
				   $title=GetMenuName($menu);
				   $link=GetMenuUrl($menu);
				 echo "<li>";
				echo "<a href=$link>$title &nbsp;</a> |&nbsp";
				 echo"</li>";
				} ?>
			
				
			</ul>
			<div class="clearer">&nbsp;</div>

		</div>
		<!--end menu-->
	
	</div>
	
		<div class="left sidebar" id="sidebar">
		<div class="section">
				<div class="section-title">Quick Menu</div>
				<!--side bar menu-->
				<div class="section-content">

					<ul class="nice-list">
						<li>
							<div class="left"><a href="../users/addsample.php">Add Sample </a></div>
							<div class="clearer">&nbsp;</div>
						</li>
						<li>
							<div class="left"><a href="../users/createworksheet.php"> Create Worksheet </a></div>
							<div class="clearer">&nbsp;</div>
						</li>
						<li>
							<div class="left"><a href="../users/addrequisition.php">Make Requisition </a></div>
							<div class="clearer">&nbsp;</div>
						</li>
						<li>
							Update Results
							  <div class="clearer">&nbsp;</div>
						</li>
						<li>
							<div class="left"><a href="../users/sampleslist.php">View Samples </a></div>
							<div class="clearer">&nbsp;</div>
						</li>
						<li>
							<div class="left"><a href="#">Dispatch Results </a></div>
							<div class="clearer">&nbsp;</div>
						</li>
						<li>
							<div class="left"><a href="#">Pending Tasks </a></div>
							<div class="clearer">&nbsp;</div>
						</li>
					</ul>
				
				</div>
				<!--end side bar menu-->
			</div>
			<!--search form-->
			<div class="section">

				<div class="section-title">Search</div>

				<div class="section-content">
					<form method="post" action="">
					  <input name="text" type="text" class="text" size="15" />
					  &nbsp; 
					  <input type="submit" class="button" value="Submit" />
					</form>
				</div>

			</div>
			<!--end search form-->

			
		
		
		<!--<div  class="center" id="main-content">-->
	
</div>