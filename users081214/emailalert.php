<?php
require('../includes/functions.php');

$ID = $_GET['ID'];
$userinfo = GetUserInfo($ID);
	$surname = $userinfo['surname'];
	$oname = $userinfo['oname'];
	$username = $userinfo['username'];
	$password = '123456';
	//$password = $userinfo['password'];
	$account = $userinfo['account'];
	$accountname = UserAccountName($account);
	
echo "		Hello $surname $oname, <br><br>

Your System Login Credentials have been Reset to:<br><br>

<table width='592' border' 1px solid #CCB'>
	<tr  bgcolor='#F6F6F6'>
		<td align='left'>Username</td>
		<td align='left'>Password</td>
		<td align='left'>Account Type</td>
	</tr>
	<tr bgcolor='#F2F6FA'>
		<td align='left'> $username</td>
		<td align='left'> $password</td>
		<td align='left'> $accountname</td>
	</tr> 
</table><br>
-  -  -  -  -  -  <br><br>

This is an auto-generated email therefore <strong><font color='#FF0000'>DO NOT RESPOND</font></strong> to it. Any queries may be forwarded to:<br><br>

<strong>NATIONAL MICROBIOLOGY REFERENCE LABORATORY</strong><br>
<strong>HARARE - ZIMBABWE</strong>";

?>