<?php 
session_start();
//require_once('scheduled_trigger.php');

require_once('connection/config.php');
if($_REQUEST['Login'])
{		
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
	//Sanitize the POST values
	$username = clean($_POST['uname']);
	$password = clean($_POST['upass']);
	
	if ((strlen($username) <1) || (strlen($username) > 32))
	{
		$r='<font color="#CC6600">* Login failed, Please enter Username </font>';

	}
	else if ((strlen($password) < 1) || (strlen($password) > 32))
	{
		$r='<font color="#CC6600">* Login failed,Please enter Password </font>';
	}
	else
	{
		$pass=md5($password);
		$qry="SELECT * FROM users WHERE username='$username' AND  password='$pass' and flag=1";
		$result=@mysql_query($qry);
		//Check whether the query was successful or not
		if($result)
	 	{
			if(mysql_num_rows($result) > 0)
			 {
			//Login Successful
				session_regenerate_id();
				$userrec = mysql_fetch_assoc($result);
			//create sessions to hold vital information
				$_SESSION['unames'] = $userrec['surname'].' '.$userrec['oname'];
				$lastaccess = $userrec['lastaccess'];
				$_SESSION['uaccess'] = $lastaccess;
				$_SESSION['uid'] = $userrec['ID'];
				$_SESSION['uemail'] = $userrec['email'];
				$_SESSION['accounttype'] = $userrec['account'];
				$_SESSION['lab'] = $userrec['lab'];
				$_SESSION['logintime'] = $logintime;
	   	     	session_write_close();
							

					if ($_SESSION['accounttype'] == "3") //program manager
					{
							header("location: overall.php");
					}
					else if ($_SESSION['accounttype'] == "2") //sys admin
					{
							header("location: users/admin.php");
					}
					else if ($_SESSION['accounttype'] == "1") //data clerk
					{
							header("location: users/home.php");
					}
					else if ($_SESSION['accounttype'] == "4") //lab tech
					{
							header("location: users/home.php");
					}
					else if ($_SESSION['accounttype'] == "5") //lab manager
					{
							header("location: users/labdashboard.php");
					}
	     		session_write_close();
			
			}
			else
			{
		
				 $r='<font color="#CC6600">* Login failed, Wrong Username or Password </font>';
			
			}
	}
	else
	 {
		die("Query failed");
	}
	
	}
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

	<div class="main" id="main-two-columns-left">
	<?php
 
						//if the user has not logged in yet, display the logiin box else...
	if ($r != "")
	{ 
	echo"<table   >
				  <tr>
					<td style=width:auto >
					<div class=error>
					 <strong>$r</strong></div>
					</td>
				  </tr>
	  </table>";
	}?>
<form name="form1" method="post" action="">

Username <input type="text" class="text"  name="uname" value=""/> Password <input type="Password" class="text" name="upass" /> <input  type="submit" class="button" name="Login" value="Login" />
	  </form>
		

		<div class="clearer">&nbsp;</div>

	</div>

	<div id="footer">

		<div class="right" id="footer-right">
			
		 <p>&copy; <?php echo date('Y');?> MOHCW.ZW All rights Reserved</p>

			<p class="quiet"></p>
			
			<div class="clearer">&nbsp;</div>

		</div>


		<div class="clearer">&nbsp;</div>

	</div>

</div>

</body>
</html>