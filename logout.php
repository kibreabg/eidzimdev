<?php
session_start();
include("connection/config.php");
$id= $_SESSION['uid'];
//$logout= date("H:i:s A");
$logintime=	$_SESSION['logintime']  ;
$logout=date("l dS \of F Y h:i:s A");

 /*$accesslog = "INSERT INTO 		
accesslogs(uid,alogintime,alogout,adate)VALUES('$id','$logintime','$logout',Now())";
$accessrec = @mysql_query($accesslog) or die(mysql_error());
*/
$sql = "UPDATE users SET lastaccess='$logout' WHERE  ID=$id";
      $lastaccessrec= mysql_query($sql);
	   
session_destroy();
if ($lastaccessrec )
{ 
header('Location: index.php');
}
?>
