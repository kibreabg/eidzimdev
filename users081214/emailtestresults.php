<?php
session_start();
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
include("../includes/functions.php");
$batchno=$_GET['ID'];

$sampelid=$_GET['sample'];
$labss=$_SESSION['lab'];
if ($batchno !="")
{
$patient=GetPatient($batchno);
//get patient gender and mother id based on sample code of sample
$mid=GetMotherID($patient);
//get patient gender
$pgender=GetPatientGender($patient);
//get sample facility code based  on mothers id

$facility=GetFacilityCode($batchno );
}
else
{
$facility=$_GET['fcode'];
}

		//get sample facility name based on facility code
$facilityname=GetFacility($facility);
		//get district and province
		//get selected district ID
$distid=GetDistrictID($facility);	
		//get select district name and province id	
$distname=GetDistrictName($distid);
		//get province ID
$provid=GetProvid($distid);
			//get province name	
$provname=GetProvname($provid);
$from=GetLabEmail($labss);
if($_SERVER['REQUEST_METHOD']=="POST")
{	
$contactperson=$_POST['contact'];
$contactemail=stripslashes($_POST['contactemail']);
$emailaddress=stripslashes($_POST['emailaddress']);
$subject=$_POST['subject'];
$message=$_POST['msg'];
$from=GetLabEmail($labss);
 $file1 = $_FILES['filename']['name'];
//$from="eid-nairobi@googlegroups.com";
 // get the sender's name and email address
   // we'll just plug them a variable to be used later

 
   // here, we'll start the message body.
   // this is the text that will be displayed
   // in the e-mail

	function validateEmail($email)
	{
		return ereg("^[a-zA-Z0-9]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$", $email);
	}
$d= validateEmail($contactemail);
$d2= validateEmail($emailaddress);
if  ($file1 =="" )
{
$error='<center>'."Please Select Attachment".'</center>';
}
else if  ($contactemail !="" && $d !=1)
{
$error='<center>'."Invalid  Contact Email".'</center>';
}
else if ($emailaddress !="" && $d2 !=1)
{
$error='<center>'."Invalid  Facility Email".'</center>';

}
else if ($contactemail =="" && $emailaddress =="" )
{
 $error='<center>'."Missing Email Address, Please Enter one or both".'</center>';
}
else if ($contactemail =="" && $emailaddress !="" )
{ //send to facility email 
   // we'll begin by assigning the To address and message subject
     // get the sender's name and email address
   // we'll just plug them a variable to be used later
   	
   // generate a random string to be used as the boundary marker
  // generate a random string to be used as the boundary marker
   $mime_boundary="==Multipart_Boundary_x".md5(mt_rand())."x";

   // store the file information to variables for easier access
   $tmp_name = $_FILES['filename']['tmp_name'];
   $type = $_FILES['filename']['type'];
   $name = $_FILES['filename']['name'];
   $size = $_FILES['filename']['size'];

   // here we'll hard code a text message
   // again, in reality, you'll normally get this from the form submission
   $message=$_POST['msg'];

   // if the upload succeded, the file will exist
   if (file_exists($tmp_name)){

      // check to make sure that it is an uploaded file and not a system file
      if(is_uploaded_file($tmp_name)){

         // open the file for a binary read
         $file = fopen($tmp_name,'rb');

         // read the file content into a variable
         $data = fread($file,filesize($tmp_name));

         // close the file
         fclose($file);

         // now we encode it and split it into acceptable length lines
         $data = chunk_split(base64_encode($data));
     }

      // now we'll build the message headers
      $headers = "From: $from\r\n" .
         "MIME-Version: 1.0\r\n" .
         "Content-Type: multipart/mixed;\r\n" .
         " boundary=\"{$mime_boundary}\"";

      // next, we'll build the message body
      // note that we insert two dashes in front of the
      // MIME boundary when we use it
      $message = "This is a multi-part message in MIME format.\n\n" .
         "--{$mime_boundary}\n" .
         "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
         "Content-Transfer-Encoding: 7bit\n\n" .
         $message . "\n\n";

      // now we'll insert a boundary to indicate we're starting the attachment
      // we have to specify the content type, file name, and disposition as
      // an attachment, then add the file content and set another boundary to
      // indicate that the end of the file has been reached
      $message .= "--{$mime_boundary}\n" .
         "Content-Type: {$type};\n" .
         " name=\"{$name}\"\n" .
         //"Content-Disposition: attachment;\n" .
         //" filename=\"{$fileatt_name}\"\n" .
         "Content-Transfer-Encoding: base64\n\n" .
         $data . "\n\n" .
         "--{$mime_boundary}--\n";

      // now we just send the message
      if (@mail($emailaddress, $subject, $message, $headers))
         $success ="Message Sent";
   else
     $error= "Failed to sendd";
}
}
else if ($contactemail !="" && $emailaddress =="" )
{//send to contact email only
     	
     $mime_boundary="==Multipart_Boundary_x".md5(mt_rand())."x";

   // store the file information to variables for easier access
   $tmp_name = $_FILES['filename']['tmp_name'];
   $type = $_FILES['filename']['type'];
   $name = $_FILES['filename']['name'];
   $size = $_FILES['filename']['size'];

   // here we'll hard code a text message
   // again, in reality, you'll normally get this from the form submission
   $message=$_POST['msg'];

   // if the upload succeded, the file will exist
   if (file_exists($tmp_name)){

      // check to make sure that it is an uploaded file and not a system file
      if(is_uploaded_file($tmp_name)){

         // open the file for a binary read
         $file = fopen($tmp_name,'rb');

         // read the file content into a variable
         $data = fread($file,filesize($tmp_name));

         // close the file
         fclose($file);

         // now we encode it and split it into acceptable length lines
         $data = chunk_split(base64_encode($data));
     }

      // now we'll build the message headers
      $headers = "From: $from\r\n" .
         "MIME-Version: 1.0\r\n" .
         "Content-Type: multipart/mixed;\r\n" .
         " boundary=\"{$mime_boundary}\"";

      // next, we'll build the message body
      // note that we insert two dashes in front of the
      // MIME boundary when we use it
      $message = "This is a multi-part message in MIME format.\n\n" .
         "--{$mime_boundary}\n" .
         "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
         "Content-Transfer-Encoding: 7bit\n\n" .
         $message . "\n\n";

      // now we'll insert a boundary to indicate we're starting the attachment
      // we have to specify the content type, file name, and disposition as
      // an attachment, then add the file content and set another boundary to
      // indicate that the end of the file has been reached
      $message .= "--{$mime_boundary}\n" .
         "Content-Type: {$type};\n" .
         " name=\"{$name}\"\n" .
         //"Content-Disposition: attachment;\n" .
         //" filename=\"{$fileatt_name}\"\n" .
         "Content-Transfer-Encoding: base64\n\n" .
         $data . "\n\n" .
         "--{$mime_boundary}--\n";

      // now we just send the message
      if (@mail($contactemail, $subject, $message, $headers))
         $success ="Message Sent";
 		  else
     	$error= "Failed to send45";
	}
}
else
{//send to both
    
	     	$to1= stripslashes($contactemail);
			$to2= stripslashes($emailaddress);
    $mime_boundary="==Multipart_Boundary_x".md5(mt_rand())."x";

   // store the file information to variables for easier access
   $tmp_name = $_FILES['filename']['tmp_name'];
   $type = $_FILES['filename']['type'];
   $name = $_FILES['filename']['name'];
   $size = $_FILES['filename']['size'];

   // here we'll hard code a text message
   // again, in reality, you'll normally get this from the form submission
   $message=$_POST['msg'];

   // if the upload succeded, the file will exist
   if (file_exists($tmp_name)){

      // check to make sure that it is an uploaded file and not a system file
      if(is_uploaded_file($tmp_name)){

         // open the file for a binary read
         $file = fopen($tmp_name,'rb');

         // read the file content into a variable
         $data = fread($file,filesize($tmp_name));

         // close the file
         fclose($file);

         // now we encode it and split it into acceptable length lines
         $data = chunk_split(base64_encode($data));
     }

      // now we'll build the message headers
      $headers = "From: $from\r\n" .
         "MIME-Version: 1.0\r\n" .
         "Content-Type: multipart/mixed;\r\n" .
         " boundary=\"{$mime_boundary}\"";

      // next, we'll build the message body
      // note that we insert two dashes in front of the
      // MIME boundary when we use it
      $message = "This is a multi-part message in MIME format.\n\n" .
         "--{$mime_boundary}\n" .
         "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
         "Content-Transfer-Encoding: 7bit\n\n" .
         $message . "\n\n";

      // now we'll insert a boundary to indicate we're starting the attachment
      // we have to specify the content type, file name, and disposition as
      // an attachment, then add the file content and set another boundary to
      // indicate that the end of the file has been reached
      $message .= "--{$mime_boundary}\n" .
         "Content-Type: {$type};\n" .
         " name=\"{$name}\"\n" .
         //"Content-Disposition: attachment;\n" .
         //" filename=\"{$fileatt_name}\"\n" .
         "Content-Transfer-Encoding: base64\n\n" .
         $data . "\n\n" .
         "--{$mime_boundary}--\n";

      // now we just send the message
      if ((@mail($to1, $subject, $message, $headers)) && (@mail($to2, $subject, $message, $headers)))
         $success ="Message Sent";
  	 else
     $error= "Failed to send900";
	}
	}

}
?>
<html>
<link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
<style type="text/css">
<!--
.style1 {font-family: "Courier New", Courier, monospace}
.style4 {font-size: 12}
.style5 {font-family: "Courier New", Courier, monospace; font-size: 12; }
-->
</style>
<title>Early Infant Diagnosis Program</title>
<body >
<?php if ($success !="")
		{
		?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="error"><?php 
		
echo  '<strong>'.' <font color="#666600">'.$success.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
<?php } ?>
<?php if ($error !="")
		{
		?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="error"><?php 
		
echo  '<strong>'.' <font color="#666600">'.$error.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
<?php } ?>
  <form   method="post" action=""  enctype="multipart/form-data" name="form1">
<table  border="0" class="data-table">
		<tr>
<td height="31" colspan="2"><strong> Confirm Email Address, and attach the results pdf then Click 'Send Email':</strong></td>
</tr>
		<tr >
		<td height="30" class="comment style1 style4">
		Facility</td>
		<td   class="comment">
		  <span class="style5"><?php echo $facilityname ." | ". "<strong>Province: </strong>".  $provname ." |  <strong>District:</strong> " . $distname; ?></span></td>
		</tr>
		<tr >
		<td height="30" class="comment style1 style4">
		Contact Person</td>
		<td  class="comment" ><span class="style5"><input name="contact" type="text" id="contact" value="<?php echo $contactperson; ?>"  style="width:174px" class="text"   /></span></td>
		</tr>
		<tr >
		<td height="30" class="comment style1 style4">
		Contact Email</td>
		<td  class="comment" ><span class="style5">
		  <input name="contactemail" type="text" id="contactemail" value="<?php echo $contactemail; ?>"  style="width:174px" class="text"   />
		</span></td>
		</tr>
		<tr >
		<td height="30" class="comment style1 style4">
		Facility Email Address</td>
		<td  class="comment" ><span class="style5"><input name="emailaddress" type="text" id="emailaddress" value="<?php echo $email; ?>"  style="width:174px"  class="text"  /></span></td>
		</tr>
		  <td height="30" class="comment style1 style4">
		Subject</td>
		    <td  class="comment" ><span class="style5"><input name="subject" type="text" id="subject" value="<?php echo "Test Results"; ?>"  style="width:174px"   class="text" /></span></td>
		</tr>
		<tr >
		<tr bgcolor="#CDCCCA">
        <td height="24" bgcolor="#F0F3FA">Message</td>
              <td bgcolor="#F0F3FA"><textarea rows="6" cols="60" name="msg" >Hello <?php echo $contactperson; ?>
	</textarea>
                <span class="mandatory">*</span> </td>
		    </tr>
		
		<tr >
		<td height="30" class="comment style1 style4">Select Attachment </td>
		<td  class="comment" ><span class="style5"> <p>File: <input type="file" name="filename"></p>
  </span></td>
		</tr>
		<tr >
		<td height="37" colspan="2" class="comment style1 style4">
		<input name="sendemail" type="submit" class="button" value="Send Results" />
           <input name="btnCancel" type="button" id="btnCancel" value="Cancel" onClick=window.location.href="dispatchedResults.php" class="button"></td>
		</tr>
</table>

</form>
</body>
</html>