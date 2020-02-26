<?php
require('../phpmailer/class.phpmailer.php');
require('../includes/functions.php');

$labss=$_SESSION['lab'];
$ebatch = $_GET['ebatch'];
$batchno = $_GET['batchno'];
$q = $batchno;


if ($ebatch != '')
{
	$mail = new PHPMailer();
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->Host = "smtp.gmail.com";
	$mail->SMTPAuth = true;
	$mail->Username = 'kemrinrb@gmail.com';
	$mail->Password = 'kemrinrb123456';
	$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
	$mail->Port = 465; 
	
	
	$mail->From="eid-nairobi@googlegroups.com";
	$mail->FromName="KEMRI-Nairobi Group";
	$mail->Sender="eid-nairobi@googlegroups.com";
	$mail->AddReplyTo("eid-nairobi@googlegroups.com", "KEMRI-Nairobi Group");
	
	//eid-alupe@googlegroups.com
	$efacility=GetFacilityCode($ebatch);
	
	//get the facility email address if it exists
	$cgetfcode = "SELECT email FROM facilitys WHERE ID='$efacility'";
	$cgotfcode = mysql_query($cgetfcode) or die(mysql_error());
	$cfcoderec = mysql_fetch_array($cgotfcode);
	$femail = $cfcoderec['email'];
	
	$email = $femail; //get facility email address as the main contact
	//$email="tngugi@gmail.com"; //should cc the facility
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
	
	if ($res)
	include("http://kemri001/eid/users/emailalert.php?ebatch=$ebatch&batchno=$batchno");
	$content = ob_get_flush();
	
	$mail->Subject = 'RECEIVED SAMPLES FOR TESTING';
	$mail->IsHTML(true);
	$mail->Body =$content;
	 
	//$mail->AltBody="This is text only alternative body.";
	
	if(!$mail->Send())
	{
		$a = 0;	//email not sent
		
		$completeentry = mysql_query("UPDATE samples
										  SET notice = 0 
													   WHERE (batchno = '$ebatch')")or die(mysql_error());
		
		$pendingtask  =  SavePendingEmailTask($ebatch,$labss);											   
		
		echo '<script type="text/javascript">' ;
		echo "window.location.href='sampleslist.php?e=$a&qa=$q'";
		echo '</script>';
	}
	else
	{
		$a = 1; //email sent successfully
		
		$completeentry = mysql_query("UPDATE samples
										  SET notice = 1 
													   WHERE (batchno = '$ebatch')")or die(mysql_error());
		
		echo '<script type="text/javascript">' ;
		echo "window.location.href='sampleslist.php?e=$a&qa=$q'";
		echo '</script>';
	}
}
?>