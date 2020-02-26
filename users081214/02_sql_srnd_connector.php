
<?php
if($_REQUEST['addonly'])
{
$d = $_GET['sdoc'];
$d =date("Y-m-d",strtotime($d));
echo $d;
}?>
<!DOCTYPE html>
<html>
<head>
  <link href="base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src="jquery.min.js"></script>
  <script src="jquery-ui.min.js"></script>
  <link rel="stylesheet" href="demos.css">
  <script>
  $(document).ready(function() {
    $("#datepicker").datepicker();


  });
  </script>
   <script>
  $(document).ready(function() {
    $("#datecollected").datepicker();


  });
  </script>




  
  
</head>
<body style="font-size:62.5%;">
 <form id="customForm" method="get" action="" >


<p>Date: <input id="datepicker" type="text" name="sdoc"></p>


<div type="text" id="datepicker">
</div>
<br>
<p>Date: <input id="datecollected" type="text" name="sdoc2"></p>


<div type="text" id="datecollected">
</div>
	<input name="addonly" type="submit" class="button" value="Save Sample" />
</form>
</body>
</html>




