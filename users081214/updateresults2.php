<?php 
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
$success=$_GET['p'];
?>
<?php include('../includes/header.php');?>
<style type="text/css">
select {
width: 250;}
</style>	<script language="javascript" src="calendar.js"></script>

<link type="text/css" href="calendar.css" rel="stylesheet" />	
		<SCRIPT language=JavaScript>
function reload(form)
{
var val=form.cat.options[form.cat.options.selectedIndex].value;
self.location='addsample.php?catt=' + val ;
}
</script>

		<div  class="section">
		<div class="section-title">UPDATE TEST RESULTS </div>
		<div class="xtop">
		

		 DDDD
       
	
	
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>