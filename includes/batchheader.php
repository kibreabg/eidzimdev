<?php
session_start();
$userid=$_SESSION['uid'] ;
$accttype=$_SESSION['accounttype'];

$userlab=$_SESSION['lab'];

require_once('../connection/config.php');
include('../includes/functions.php');
require_once('monitorpendingtasks.php');
require_once('monitorbatchdispatch.php');
//require_once('releasebatches.php');
require_once('../protectpages.php');
//get the search variable
$searchparameter = $_GET['search'];
$top="Top";
$side="Side";
$totaltasks=gettotalpendingtasks($accttype);

		$labname=GetLabNames($userlab);//get lab name	
if ($totaltasks != 0)
{
$d='<strong> ['.$totaltasks .']</strong>';

}
else
{
$d='<strong> ['.'0' .']</strong>';
}
		//$_SESSION['unames'] = $userrec['surname'].' '.$userrec['oname'];
				
		
		
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<meta name="description" content=""/>
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	
<link rel="stylesheet" type="text/css" href="../style.css" media="screen" />

	<title>EID ZIM</title>

<script type="text/javascript" src="../includes/jquery.min.js"></script>
<script type="text/javascript" src="../includes/jquery.js"></script>
<script type='text/javascript' src='../includes/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="../includes/jquery.autocomplete.css" />
<script type="text/javascript">
$().ready(function() {
	
	$("#sample").autocomplete("getsamples.php", {
		width: 260,
		matchContains: true,
		mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
	
	$("#sample").result(function(event, data, formatted) {
		$("#sampleid").val(data[1]);
	});
});
</script>


<script type="text/javascript">
$().ready(function() {
	
	$("#batch").autocomplete("getbatches.php", {
		width: 260,
		matchContains: true,
		mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
	
	$("#batch").result(function(event, data, formatted) {
		$("#batchid").val(data[1]);
	});
});
</script>
<link rel="shortcut icon" href="../favicon.ico" >
<link rel="icon" type="image/gif" href="../animated_favicon1.gif" >
<script type="text/javascript" src="reflection.js"></script> 
</head>

<body>
<div id="site-wrapper">

	<div id="header">
		<!--top-->
		<div id="top">

			<div class="left" id="logo"><img src="../img/welfarelogo.png"/></div>
			<div align="right"><?php echo "Welcome". " <b>".	$_SESSION['unames'] ."</b>". ' - '. $labname .'<br>'."<b>". date("l, d F Y")."</b>"; ?></div>
			<div class="clearer">&nbsp;</div>

		</div>
		<!--end top-->
		
		<!--menu-->
		<div class="navigation" id="sub-nav">
			<ul class="tabbed">
				
				<?php  if ($accttype !="")
				{
				 //query for top bar
					 $menuresult = mysql_query("SELECT groupmenus.menu as 'topmenu' from groupmenus,menus where groupmenus.usergroup='$accttype' AND menus.ID=groupmenus.menu AND  menus.location='Top' ORDER BY groupmenus.menu ASC" ) or die(mysql_error());
				
								while(list($topmenu) = mysql_fetch_array($menuresult))
								{ 
									if ($topmenu ==  33)
									{
									$title=GetMenuName($topmenu);
									   $link=GetMenuUrl($topmenu);
									 echo "<li>";
									echo "<a href=$link target='_blank'>$title &nbsp;</a> |&nbsp";
									 echo"</li>";
									}
									else
									{ 
									$title=GetMenuName($topmenu);
									   $link=GetMenuUrl($topmenu);
									 echo "<li>";
									echo "<a href=$link>$title &nbsp;</a> |&nbsp";
									 echo"</li>";
									}
								
								} 
				}
				else
				{
				
				}?>
			
				
			</ul>
			<div class="clearer">&nbsp;</div>

		</div>
		<!--end menu-->
	
	</div>

		