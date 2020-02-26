<?php 
require_once("mpdf51/mpdf.php");
$html = file_get_contents("http://localhost/idsr/summary2.html");

$mpdf=new mPDF('','A4-L', 0, '', 15, 15, 16, 16, 9, 9, '');
$epiweek = $_GET['epiweek'];
$week_ending = $_GET['weekending'];
$html_title = "<img src='coat_of_arms.png' style='position:absolute; width:160px; width:160px; top:0px; left:0px; margin-bottom:-100px;margin-right:-100px;'></img>";
$html_title .= "<h2 style='text-align:center; text-decoration:underline;'>Republic of Kenya</h2>";
$html_title .= "<h3 style='text-align:center; text-decoration:underline;'>Ministry of Public Health and Sanitation</h3>";
$html_title .= "<h1 style='text-align:center; text-decoration:underline;'>WEEKLY EPIDEMIOLOGICAL BULLETIN</h1>";
$html_title .= " <h3 style='padding:10px; text-decoration:underline; text-align:center; color:#3B68A8'>Week $epiweek  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Week Ending $week_ending</h3> ";
$mpdf->SetTitle('WEEKLY EPIDEMIOLOGICAL BULLETIN');
$mpdf->WriteHTML($html_title);
$mpdf->simpleTables = true;
$mpdf->WriteHTML($html);
$mpdf->Output();

?>
