<?php
session_start();
require_once('connection/config.php');
require_once('classes/tc_calendar.php');

$selectoption=intval($_GET['selectoption']);

?>
<link href="users/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
 
  <script src="users/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="users/demos.css">
<script>
		window.dhx_globalImgPath="../img/";
	</script>
<script src="users/dhtmlxcombo_extra.js"></script>
 <link rel="STYLESHEET" type="text/css" href="users/dhtmlxcombo.css">
  <script src="users/dhtmlxcommon.js"></script>
  <script src="users/dhtmlxcombo.js"></script>
<?php
 if ($selectoption == '2')
{?>
	
<?php
	   $accountquery = "SELECT ID,name FROM provinces";
						
		$result = mysql_query($accountquery) or die('Error, query failed'); //onchange='submitForm();'
	
	   echo "<select name='province' style='width:200px';>\n";
		echo " <option value='0'> Select Province </option>";
		
	  while ($row = mysql_fetch_array($result))
	  {
			 $ID = $row['ID'];
			$name = $row['name'];
		echo "<option value='$ID'> $name</option>\n";
	  }
	  echo "</select>\n";
				  	
}
else if ($selectoption == '')
{?>
	
<?php
}
?>