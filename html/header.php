<?php
if(!defined('IN_CB'))die('You are not allowed to access to this page.');

define('VERSION', '2.1.0');

if(version_compare(phpversion(),'5.0.0','>=')!==true)
	exit('Sorry, but you have to run this script with PHP5... You currently have the version <b>'.phpversion().'</b>.');

if(!function_exists('imagecreate'))
	exit('Sorry, make sure you have the GD extension installed before running this script.');

include('config.php');

require('function.php');

echo '<?xml version="1.0" encoding="iso-8859-1"?>'."\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Drawing Barcode</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link type="text/CSS" rel="stylesheet" href="./style.css" />
<script language="JavaScript" type="text/javascript">
function newkey(form,variable){
	form.text2display.value += variable;
}
function newkeyCode(form,variable){
	form.text2display.value += String.fromCharCode(variable);
}
</script>
</head>
<body bgcolor="#ffffff">

<?php
// FileName & Extension
$system_temp_array = explode('/',$_SERVER['PHP_SELF']);
$system_temp_array2 = explode('.',$system_temp_array[count($system_temp_array)-1]);
$filename = $system_temp_array2[0];

$default_value = array();
$default_value['output'] = 2;
$default_value['dpi'] = 72;
$default_value['thickness'] = 30;
$default_value['res'] = 1;
$default_value['rotation'] = 0.0;
$default_value['font_family'] = '0';
$default_value['font_size'] = 8;
$default_value['text2display'] = '';
$default_value['a1'] = '';
$default_value['a2'] = '';
$default_value['a3'] = '';


/*http://localhost/eid_demo/html/image.php?code=%27.$filename.%27&amp;o=%27.1.%27
&amp;dpi=%27.72.%27&amp;t=%27.30.%27&amp;r=%27.1.%27&amp;rot=%27.0.%27&amp;text=%27.urlencode(123).
%27&amp;f1=%27.0.%27&amp;f2=%27.8.%27&amp;a1=%27.$a1.%27&amp;a2=%27.$a2.%27&amp;a3=%27.$a3.
%27%22%20alt=%22Barcode%20Image%22*/

$output = intval(isset($_POST['output'])?$_POST['output']:$default_value['output']);
$dpi = isset($_POST['dpi'])?$_POST['dpi']:$default_value['dpi'];
$thickness = intval(isset($_POST['thickness'])?$_POST['thickness']:$default_value['thickness']);
$res = intval(isset($_POST['res'])?$_POST['res']:$default_value['res']);
$rotation = isset($_POST['rotation'])?$_POST['rotation']:$default_value['rotation'];
$font_family = isset($_POST['font_family'])?$_POST['font_family']:$default_value['font_family'];
$font_size = intval(isset($_POST['font_size'])?$_POST['font_size']:$default_value['font_size']);
$text2display = isset($_POST['text2display'])?$_POST['text2display']:$default_value['text2display'];
$a1 = isset($_POST['a1'])?$_POST['a1']:$default_value['a1'];
$a2 = isset($_POST['a2'])?$_POST['a2']:$default_value['a2'];
$a3 = isset($_POST['a3'])?$_POST['a3']:$default_value['a3'];
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" name="barcode_drawer" method="get">
<?php
$table = new LSTable(10,2,'500',$null);
$table->setTitle('Configs for ' . $filename);
$table->addRowAttribute(0,'class','table_title');
$table->addCellAttribute(0,0,'colspan','2');
$table->addCellAttribute(0,0,'align','center');
$table->setText(0,0,'<font color="#ffffff"><b>General Configs</b></font>');
$table->addCellAttribute(1,0,'width','100');
$table->setText(1,0,'Type');
$table->setText(1,1,display_select($filename));
$table->setText(2,0,'Output');
$table->setText(2,1,display_output($output, $dpi));
$table->setText(3,0,'DPI');
$table->setText(3,1,display_dpi($output, $dpi));
$table->setText(4,0,'Thickness');
$table->setText(4,1,display_thickness($thickness));
$table->setText(5,0,'Resolution');
$table->setText(5,1,display_res($res));
$table->setText(6,0,'Rotation');
$table->setText(6,1,display_rotation($rotation));
$table->setText(7,0,'Font');
$table->setText(7,1,display_font($font_family, $font_size));
$table->setText(8,0,'Text');
$table->setText(8,1,display_text($text2display));
$table->addCellAttribute(9,0,'align','center');
$table->addCellAttribute(9,0,'colspan','2');
$table->setText(9,0,'<input type="submit" value="Generate" />');

if(!empty($text2display)){
	/*$table->insertRows(10,1);
	$table->addCellAttribute(10,0,'align','center');
	$table->addCellAttribute(10,0,'colspan','2');
	$table->addRowAttribute(10,'style','background-color: #ffffff');*/
	

	echo "'code=$filename&amp;o=$output&amp;dpi=$dpi&amp;t=$thickness&amp;r=$res&amp;rot=$rotation&amp;text=urlencode($text2display)&amp;f1=$font_family&amp;f2=$font_size&amp;a1=$a1&amp;a2=$a2&amp;a3=$a3 ";
}
?>