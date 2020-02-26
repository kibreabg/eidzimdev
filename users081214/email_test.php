<?php
require('../phpmailer/class.phpmailer.php');
require('../includes/functions.php');

$userid = $_GET['userid'];
$ID = $_GET['ID'];
$email = $_GET['email'];
$userinfo = GetUserInfo($ID);
	$surname = $userinfo['surname'];
	$oname = $userinfo['oname'];

if ($email != '')
{
	$mail = new PHPMailer();
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->Host = "smtp.gmail.com";
	$mail->SMTPAuth = true;
	/*$mail->Username = 'kemrinrb@gmail.com';
	$mail->Password = 'kemrinrb123456';*/
	$mail->Username = 'zimnmrl@gmail.com';
	$mail->Password = 'p@55w0rd123456';
	$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
	$mail->Port = 465; 
	
	
	$mail->From="zimnmrl@gmail.com";
	$mail->FromName="NMRL Lab";
	$mail->Sender="zimnmrl@gmail.com";
	$mail->AddReplyTo("", "NMRL Lab");
	
	$ContactEmail = "smutheu@gmail.com";
	
			
	if (($ContactEmail =="") &&  ($email !=""))
	{
		$mail->AddAddress($email);
	}
	else if (($ContactEmail !="") &&  ($email ==""))
	{
		$mail->AddAddress($ContactEmail);
		
	}
	else
	{
		$mail->AddAddress($email);
		$mail->AddBCC($ContactEmail);
	}
	
	
	ob_start();
	
	/*if ($res)*/
	
	include("http://localhost/eid/users/emailalert.php?ID=$ID");
	$content = ob_get_flush();
	
	$mail->Subject = 'EID NMRL LIMS RESET PASSWORD';
	$mail->IsHTML(true);
	$mail->Body =$content;
	
	//$mail->AltBody="This is text only alternative body.";
	
	//..save the user activity
	$tasktime= date("h:i:s a");
	$todaysdate=date("Y-m-d"); //get the patient ID then one can retreave the info saved even though it has been edited
	$utask = 24; //user task = reset password
	
	//$activity = SaveUserActivity($userid,$leo,$task,$tasktime,$lastid);
	$activity = SaveUserActivity($userid,$utask,$tasktime,$ID,$todaysdate);

	if(!$mail->Send())
	{
		$st="Password for ".$surname." ".$oname." has successfully been reset but THE EMAIL NOTIFICATION TO THE USER <font color='#FF0000'>FAILED TO SEND.</font>";
		
		echo '<script type="text/javascript">' ;
		echo "window.location.href='userslist.php?resetsuccess=$st'";
		echo '</script>';
	}
	else
	{
		$st="Password for ".$surname." ".$oname." has successfully been reset. THE EMAIL NOTIFICATION TO THE USER HAS BEEN SUCCESSFULLY SENT.";
		
		echo '<script type="text/javascript">' ;
		echo "window.location.href='userslist.php?resetsuccess=$st'";
		echo '</script>';
	}
}
else if ($email=='')
{
	//..save the user activity
	$tasktime= date("h:i:s a");
	$todaysdate=date("Y-m-d"); //get the patient ID then one can retreave the info saved even though it has been edited
	$utask = 24; //user task = reset password
	
	//$activity = SaveUserActivity($userid,$leo,$task,$tasktime,$lastid);
	$activity = SaveUserActivity($userid,$utask,$tasktime,$ID,$todaysdate);

	$st="Password for ".$surname." ".$oname." has successfully been reset.";
		
		echo '<script type="text/javascript">' ;
		echo "window.location.href='userslist.php?resetsuccess=$st'";
		echo '</script>';
}
?>