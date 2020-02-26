<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php 
$sdoc="14/08/2011";
function getmysqldate($date)
{
list($d, $m, $y) = preg_split('/\//', $date);
$date = sprintf('%4d%02d%02d', $y, $m, $d);
return date("Y-m-d",strtotime($date));
}
echo $start;
echo "<br>";

$sdoc =date("Y-m-d",strtotime($start));



echo $sdoc;
?>

</body>
</html>