<?php 
session_start();
require_once('../connection/config.php');
include('../includes/functions.php');

 $userid = $_GET["ID"];
 $username = $_GET["name"];
 $email = $_GET["email"];
 $unames=$_GET['unames'];

 $newpass="123456";
 $password=md5($newpass);
 
/*if ($email !="")
{				   
			//update password		   
		$del = mysql_query("UPDATE users
              SET password = '$password' 
			  			   WHERE (ID = '$userid')");
						   
		//send mail			   
		$from = 'nmrl@nmrl.org';
		$to = "$email";
		$subject = 'Reset Password ';
		$message = 'Hello '. $username.','."\r\n\n".
		'This is to notify you that your password has been reset.' ."\r\n\n\n".'New Password: '.$newpass."\r\n\n".' You will now use the above password to log into the system.'."\r\n".
		'This E-mail was sent by an automatic response system. DO NOT reply to this email. It WILL NOT be received.'."\r\n\n";
		$reply   =  'webmaster@nmrl.org';


		$headers = 'From: ' .$from. "\r\n" .
					'Reply-To: ' .$reply.  "\r\n" .
				  'X-Mailer: PHP/' . phpversion();
 
		$d=mail ( $email, $subject, $message, $headers);
		
		if ($del && $d)
		{
				 //..save the user activity
				$tasktime= date("h:i:s a");
				$todaysdate=date("Y-m-d"); //get the patient ID then one can retreave the info saved even though it has been edited
				$utask = 24; //user task = reset password
				
				//$activity = SaveUserActivity($userid,$leo,$task,$tasktime,$lastid);
				$activity = SaveUserActivity($_SESSION['uid'],$utask,$tasktime,$userid,$todaysdate);

				$st="Password for ".$username." has successfully been reset";
				echo '<script type="text/javascript">' ;
				echo "window.location.href='userslist.php?resetsuccess=$st'";
				echo '</script>';
		}
}
else
{*/
	//just reset password
	$del = mysql_query("UPDATE users
		  SET password = '$password' 
					   WHERE (ID  = '$userid')");
					   
	   //..save the user activity
		$tasktime= date("h:i:s a");
		$todaysdate=date("Y-m-d"); //get the patient ID then one can retreave the info saved even though it has been edited
		$utask = 24; //user task = reset password
		
		//$activity = SaveUserActivity($userid,$leo,$task,$tasktime,$lastid);
		$activity = SaveUserActivity($_SESSION['uid'],$utask,$tasktime,$userid,$todaysdate);

	if ($del)
	{
		$st="Password for ".$unames." has successfully been reset <small>to 123456</small>";
		echo '<script type="text/javascript">' ;
		echo "window.location.href='userslist.php?resetsuccess=$st'";
		echo '</script>';
	}
//}		
?>
