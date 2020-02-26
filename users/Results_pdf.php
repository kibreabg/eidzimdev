<?php 
require_once("mpdf51/mpdf.php");
$html = file_get_contents("http://localhost/eid_zim/users/summary2.html");

$mpdf=new mPDF('',array(190,236), 11, 'T', 15, 15, 16, 16, 9, 9, 'P');

$html_title .= " <h3 style='padding:10px; text-decoration:underline; text-align:center; color:#3B68A8'>HIV DNA-PCR LABORATORY RESULT FORM </h3> ";
$mpdf->SetTitle('WEEKLY EPIDEMIOLOGICAL BULLETIN');
$mpdf->WriteHTML($html_title);
$mpdf->simpleTables = true;
$mpdf->WriteHTML($html);
$mpdf->Output();

?>
