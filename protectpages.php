<?php
session_start();
//Check whether the users session variable  is present or not
	if(!isset($_SESSION['uid']) || (trim($_SESSION['uid']) == ''))
	 {
		header("location: access_denied.php");
		
	}
			


?>