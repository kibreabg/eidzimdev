<?php

session_start();
include("../connection/config.php");
include('../includes/functions.php');
require('rotation.php');

define('FPDF_FONTPATH', 'font/');
$startdate = $_GET['startdate'];
$enddate = $_GET['enddate'];
$labss = $_SESSION['lab'];
$dislaystartdate = date("d-M-Y", strtotime($startdate));  //formatted date in DAY-MONTH-YEAR FOR DISPLAY
$dislayenddate = date("d-M-Y", strtotime($enddate));


$qury = "SELECT
            *
          FROM samples s,
               facilitys f
          WHERE s.dateenteredindb BETWEEN '$startdate' AND '$enddate' 
            AND s.FLAG = 1 
            AND s.facility = f.ID 
            AND f.lab = '$labss' 
            AND s.repeatt = 0
          ORDER BY s.dateenteredindb ASC";
$a = mysql_query($qury) or die(mysql_error());
$noofsamples = mysql_num_rows($a);

class PDF extends PDF_Rotate {

    function Header() {
        $startdate = $_GET['startdate'];
        $enddate = $_GET['enddate'];

        $province = $_GET['province'];
        $provincename = GetProvname($province);
        $provincename = strtoupper($provincename);
        $dislaystartdate = date("d-M-Y", strtotime($startdate));  //formatted date in DAY-MONTH-YEAR FOR DISPLAY
        $dislayenddate = date("d-M-Y", strtotime($enddate));

        $offset2 = 27;  // set parameters

        $this->Cell($offset2); // set parameters
        $this->Image('../img/lablogo.jpg', 20, 10); // Logo
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(217, 10, " NATIONAL MICROBIOLOGY REFERENCE LABORATORY", 0, 0, 'C'); // Title
        $this->Image('../img/zimlogo.jpg', 260, 10); // Logo
        $this->SetFont('Arial', 'B', 10);   // Arial bold 15

        $this->Ln(25);
        $this->Cell($offset2);
        //	$this->SetTextColor(204,204,204);


        $this->Cell(217, 8, " HIV DNA PCR LABORATORY REQUEST FORMS RECEIVED BETWEEN " . sprintf($dislaystartdate) . " & " . sprintf($dislayenddate), 0, 0, 'C'); // Title 
        $offset = 8;  // set parameters
        //	$this->SetTextColor(204,204,204);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('Times', 'B', 10);
        //Header
        $this->Ln(15);

        $this->Cell($offset);

        $this->SetFillColor(204, 204, 204);
        //$this->SetTextColor(204,200,214);
        $this->SetFont('');


        $header = array('Sample Request No', 'Facility', 'NMRL Stamp No.', 'Received Status', 'Date Captured', 'Date Collected', 'Date Received', 'Date Released', 'Captured By');
        $w = array(30, 65, 26, 26, 26, 26, 26, 26, 26);
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 5, $header[$i], 1, 0, 'C', 1);

        $this->SetFont('Arial', 'B', 30);
        $this->SetTextColor(204, 204, 204); //KENYA MEDICAL RESEARCH INSTITUTE
        $this->RotatedText(135, 140, 'NMRL', 45);
    }

    function RotatedText($x, $y, $txt, $angle) {
        //Text rotated around its origin
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }

    function Footer() {/*
      $this->SetY(-28);			// Position at 1.5 cm from bottom
      $this->SetFont('Arial','',5);		// Arial italic 8

      $this->Ln(5);
      $offset9=170;
      // $offset5=20;
      //$this->Cell($offset9);
      $offset5 	=25;		// set parameters
      $offset7 	=8;		// set parameters

      $this->Cell($offset7);
      $this->Cell(160,3,' NOTE: Failed samples require new sample collection',0,0, 'L');
      $this->Ln(3);
      $this->Cell($offset7);
      $this->Cell(160,3,' If you have questions or problems regarding samples, please contact the KEMRI-Nairobi Lab at',0,0, 'L');
      $this->Ln(3);
      $this->Cell($offset7);
      $this->Cell(160, 3, 'KEMRI HIV-P3 Lab ' , 0, 0, 'L', 0);
      $this->Ln(3);
      $this->Cell($offset7);
      $this->Cell(160, 3, 'KEMRI HQ, Mbagathi Road, Nairobi' , 0, 0, 'L', 0);
      $this->Ln(3);
      $this->Cell($offset7);
      $this->Cell(160, 3, 'Tel: 020 2722541 Ext: 2256/2290  / 0725 793260 / 0725 796842 ' , 0, 0, 'L', 0);
      $this->Ln(3);
      $this->Cell($offset7);
      $this->Cell(160, 3, 'Email:eid-nairobi@googlegroups.com' , 0, 0, 'L', 0);

      $this->Ln(2);
      $this->Cell(300,5,'� 2010 NASCOP ',0,0, 'C');
      $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');	// Page number
     */
    }

}

//Create new pdf file
$pdf = new PDF();


$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 8);

//Add first page
$pdf->AddPage();
$offset = 8;  // set parameters

$pdf->Ln(-5);
$offset3 = 117;  // set parameters
//$pdf->Cell($offset3);
//$pdf->Cell(180, 2 ,$reason,'C',1,1);
$pdf->SetFont('Times', '', 11);

$pdf->Cell($offset);
$pdf->Cell(100, 2, 'Number of Samples: ' . $noofsamples, 'C', 1, 1);
//$pdf->Ln(1);
//$pdf->Ln(-4);
//	$pdf->Cell($offset3);
//$pdf->Cell(75, 2 ,$reason2,'C',1,1);
//$pdf->Ln(4);			

$pdf->Cell($offset);


$pdf->Ln(8);

while ($row = mysql_fetch_array($a)) {
    $patient = $row['patient'];
    $patientid = $row['patientid'];
    $outcome = $row['result'];
    $pgender = GetPatientGender($patientid);
    $facility = $row['facility'];
    $nmrlstampno = $row['nmrlstampno'];
    $loggedinby = $row['loggedinby'];
    $capturedBy = GetUserFullnames($loggedinby);
    $getfacilityname = GetFacility($facility);
    //patietn age
    $pAge = GetPatientAge($patientid);
    $receivedstatus = $row['receivedstatus'];
    $showstatus = GetReceivedStatus($receivedstatus); //display received status
    $datetested = $row['datetested'];
    $datemodified = $row['datemodified'];
    $datedispatched = $row['datedispatched'];
    $dateenteredindb = $row['dateenteredindb'];
    $dateenteredindb = date("d-M-Y", strtotime($dateenteredindb));
    $datecollected = $row['datecollected'];    
    $datecollected = date("d-M-Y", strtotime($datecollected));
    $datereceived = $row['datereceived'];
    $datereceived = date("d-M-Y", strtotime($datereceived));
    $datereleased = $row['datereleased'];
    $datereleased = date("d-M-Y", strtotime($datereleased));
    

    if (($receivedstatus == 2) || (($outcome < 0 ) || ($outcome == ""))) {
        $datetested = "";
        $datemodified = "";
        $datedispatched = "";
        $routcome = "";
    } else {
        //..sanitize date tested
        if (($datetested != "") && ($datetested != '0000-00-00')) {
            $datetested = date("d-M-Y", strtotime($datetested));
        } else {
            $datetested = '';
        }
        //..sanitize date modified
        if (($datemodified != "") && ($datemodified != '0000-00-00')) {
            $datemodified = date("d-M-Y", strtotime($datemodified));
        } else {
            $datemodified = '';
        }
        //..sanitize date dispatched
        if (($datedispatched != "") && ($datedispatched != '0000-00-00')) {
            $datedispatched = date("d-M-Y", strtotime($datedispatched));
            $datedispatched4 = date("d-m-Y", strtotime($datedispatched));
            $tot = round((strtotime($datedispatched4) - strtotime($datereceived4)) / (60 * 60 * 24));
        } else {
            $datedispatched = '';
        }

        $datereceived4 = date("d-m-Y", strtotime($datereceived));
        $routcome = GetResultType($outcome);
    }




    $pdf->SetFillColor(226, 231, 245);
    $start_row = $start_row_init;
    $pdf->Cell($offset);
    $pdf->Cell(30, 4, $patient, 1, 0, 'L', 0);
    $pdf->Cell(65, 4, $getfacilityname, 1, 0, 'C', 0);
    $pdf->Cell(26, 4, $nmrlstampno, 1, 0, 'C', 0);
    $pdf->Cell(26, 4, $showstatus, 1, 0, 'C', 0);
    $pdf->Cell(26, 4, $dateenteredindb, 1, 0, 'C', 0);
    $pdf->Cell(26, 4, $datecollected, 1, 0, 'C', 0);
    $pdf->Cell(26, 4, $datereceived, 1, 0, 'C', 0);
    $pdf->Cell(26, 4, $datereleased, 1, 0, 'C', 0);
    $pdf->Cell(26, 4, $capturedBy, 1, 0, 'C', 0);

    $pdf->Ln();
}
$pdf->Ln(2);


//Create file
$display = "Filtered Report of samples Received from " . $provincename . " between " . $dislaystartdate . " & " . $dislayenddate . ".pdf";
$F = "I";
$pdf->Output($display, $F);
?>