<?php 
session_start();
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
			<div align="right"><?php echo "<b>". date("l, d F Y")."</b>";?></div>
			<div class="clearer">&nbsp;</div>

		</div>



	</div>

	<div class="main" id="main-content">
	
			
				<div class="error">
					<h2 align="center">
					<?php echo  '<strong>'.' <font color="#CC3333">'.'Access Denied'.'</strong>'.' </font>';?>
					 </h2>
					
			<p align="center"> You need to log in to access this page.			</p>  
			</div>
			<p align="center">Click here to
			<a href="index.php">  <strong><font color="#0033FF">Log In</font></strong></a> .</p>
		
	

		<div class="clearer">&nbsp;</div>

	</div>

	<div id="footer">

		<div class="right" id="footer-right">
			
		 <p><small>&copy; MOHCW ZIMBABWE <?php echo date('Y');?> | All Rights Reserved.</small></p>

			<p class="quiet"></p>
			
			<div class="clearer">&nbsp;</div>

		</div>


		<div class="clearer">&nbsp;</div>

	</div>

</div>

</body>
</html>