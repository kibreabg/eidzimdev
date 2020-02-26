<?php	//Start session
session_start();
include("../connection/config.php");
$userid=$_SESSION['uid'] ;
$labss=$_SESSION['lab'];

//Check whether the clients session variable  is present or not
	if(!isset($_SESSION['uid']) || (trim($_SESSION['uid']) == '')) {
		header("location: ../access_denied.php");
		
	}
	
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	
	
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
	//Sanitize the POST values
	$cpassword = clean($_POST['cpassword']);
	$newpassword = clean($_POST['password']);
	$confirm = clean($_POST['confirm']);
		
	


	//Check for duplicate login ID
	if($cpassword != '')
	 {    
	    $pass=md5($cpassword);
		$qry = "SELECT * FROM users WHERE password='$pass' AND ID='$userid'";
		$result = mysql_query($qry);
		$j=mysql_fetch_array($result);
		$name=$j['surname'];
		$username=$j['username'];
		$email=$j['email'];
		if($result) 
		{
			if(mysql_num_rows($result) == 0)
			 {
				$errmsg_arr[] = 'Wrong current password, reenter';
				$errflag = true;
			}
			@mysql_free_result($result);
		}
		else
		{
			die("Query failed to execute");
		}
	}
	
	//If there are input validations, redirect back to the registration form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: changepassword.php");
		exit();
	}
	$new=md5($newpassword);
  $sql =("UPDATE users SET password='$new' WHERE ID='$userid'");
  	
	$result = @mysql_query($sql);
	
	//Check whether the query was successful or not
if($result )
{

$st=" Password Successfully Changed ";
		header("location: changepassword.php?changesucess=$st");
		exit();
	if ($email !="")
	{
	  	$site_email="eid-nairobi@googlegroups.com";
	
	$site_url ='http://localhost/eid_demo/';

 $headers = 'From: ' .$site_email. "\r\n" .
            'Reply-To: ' .$site_email.  "\r\n" .
          'X-Mailer: PHP/' . phpversion();

	  
$subject = "Log in Details";
$message = "
	Hello $name;

You have successfully changed your password.

Here  below are your log in details.
--------------------------
Email: $email
Username: $username
Password: $newpassword
--------------------------
You may login below:
$site_url

You can of course change this password yourself via the profile page. If you have any difficulties please contact the administrator.

--
-Thanks
$site_name

This email was automatically generated.
Please do not respond to this email or it will ignored.";
		  
		//  mail($email, $subject, $message, $headers); old
		  
		  
		  

$d= mail($email,$subject,$message,$headers);

		if ($d)
	{
	
	
			$st=" Password Successfully Changed ";
		header("location: changepassword.php?changesucess=$st");
		exit();
		}
		else
		{
		$st=" Failed to send Email ";
		header("location: changepassword.php?changesucess=$st");
		exit();
		}
	}
	else
	{	$st=" Password Successfully Changed ";
		header("location: changepassword.php?changesucess=$st");
		exit();
	}
}
else {
		die("Query failed");
	}
?>