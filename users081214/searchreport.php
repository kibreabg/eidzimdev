<?php
session_start();
//require('./pdflibrary7.php');
	include("../connection/config.php");
	include('../includes/functions.php');
	
require('rotation3.php');

define('FPDF_FONTPATH', 'font/');
	$searchparameter=$_GET['searchparameter'];

$getresult = "SELECT * FROM samples WHERE patient LIKE '%$searchparameter%' AND flag = 1";
$a = mysql_query($getresult) or die('Error, query failed');
$num_samples= mysql_num_rows($a);//get the search count

class PDF extends PDF_Rotate
{
	function Header()
	{
	
			$offset2 	=27;		// set parameters
			
					$this->Cell($offset2);	// set parameters
$this->Image('../img/logopdf.jpg',20,10);	// Logo
$this->SetFont('Arial','B',14);	
	$this->Cell(217,10," HIV Lab CVR,  KEMRI Nairobi",0,0,'C');	// Title
$this->Image('../img/naslogo.jpg',260,10);	// Logo
$this->SetFont('Arial','B',10);			// Arial bold 15
		
	$this->Ln(25);	
	$this->Cell($offset2);
 	$this->SetTextColor(204,204,204);

	
	$this->Cell(217,10," INFANT DIAGNOSIS SEARCH REPORT",0,0,'C');	// Title
	$offset 	= 15;		// set parameters

	
		$this->SetTextColor(204,204,204);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('Times','B',10);
		//Header
		$this->Ln(26);
				
$this->Cell($offset);

 $this->SetFillColor(0,82,108);	
 	$this->SetTextColor(204,200,214);
		$this->SetFont('');
		

		$header=array('No','Infant ID','Facility','Batch No','Rec.Status','Spots','Date Collected','Date Received','Worksheet','Date of Test','Test Result','Date of Dispatch');
		$w=array(10,30,40,15,20,10,25,25,20,25,20,25);
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'C',1);

$this->SetFont('Arial','B',30);
	$this->SetTextColor(204,204,204);//KENYA MEDICAL RESEARCH INSTITUTE
	$this->RotatedText(135,120,'KEMRI',45);
		
	}
function RotatedText($x, $y, $txt, $angle)
{
	//Text rotated around its origin
	$this->Rotate($angle,$x,$y);
	$this->Text($x,$y,$txt);
	$this->Rotate(0);
}
	
function Footer()
{
		$this->SetY(-39);			// Position at 1.5 cm from bottom
	$this->SetFont('Times','',6);		// Arial italic 8
	
	 $this->Ln(5);
	 $offset9=170;
	 // $offset5=20;
//$this->Cell($offset9);
 $offset7 	=15;		// set parameters
// $this->Ln(5);
 $this->Cell($offset7);
$this->Cell(160,5,' If you have questions or problems regarding samples, please contact the KEMRI-Nairobi Lab at',0,0, 'L');
$this->Ln(5);
	$this->Cell($offset7);
$this->Cell(160, 5, 'KEMRI HIV-P3 Lab ' , 0, 0, 'L', 0);
$this->Ln(5);
$this->Cell($offset7);
$this->Cell(160, 5, 'KEMRI HQ, Mbagathi Road, Nairobi' , 0, 0, 'L', 0);
$this->Ln(5);
$this->Cell($offset7);
$this->Cell(160, 5, 'Box 54628-00200, Nairobi Kenya ' , 0, 0, 'L', 0);
$this->Ln(5);
	$this->Cell($offset7);
$this->Cell(160, 5, 'Tel: 020 2722541 Ext: 2256/2290 / 0725 793260 / 0725 796842 ' , 0, 0, 'L', 0);
$this->Ln(5);
$this->Cell($offset7);
	$this->Cell(160, 5, 'Email: eid-nairobi@googlegroups.com' , 0, 0, 'L', 0);
//eid@kemri.org
	
				$this->Ln(2);
				$this->Cell(300,10,'© 2010 NASCOP ',0,0, 'C');
$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');	// Page number
	
	


		



}
}
//Create new pdf file
$pdf=new PDF();


$pdf->AliasNbPages();

//Add first page
$pdf->AddPage();
$offset 	=15;		// set parameters

$pdf->SetFont('Times','',10);
				$pdf->Ln(-13);
				
$offset3 	=100;		// set parameters
//$pdf->Cell($offset3);
//$pdf->Cell(75, 2 ,$reason,'C',1,1);
		$pdf->Cell($offset);
		$pdf->Cell(200, 5, 'Search for. ' .$searchparameter , 0, 0, 'L', 0);
		$pdf->Ln(7);
		$pdf->Cell($offset);
		$pdf->Cell(100, 2 ,'Number of Samples: '. $num_samples,'C',1,1);

	$pdf->Ln(-4);
//	$pdf->Cell($offset3);
	//$pdf->Cell(75, 2 ,$reason2,'C',1,1);
	//$pdf->Ln(4);			
			
			$pdf->Cell($offset);


	$pdf->Ln(15);			
$samplecount=0;
while($row = mysql_fetch_array($a))
{   
	$samplecode=$row['ID'];
 	$patient=$row['patient'];
 	$worksheet=$row['worksheet'];
  	$receivedstatus=$row['receivedstatus'];
	$facility=$row['facility'];
    $result=$row['result'];
	$batchno=$row['batchno'];
	$spots=$row['spots'];
	$datecollected=$row['datecollected'];
 $datereceived=$row['datereceived'];
 $datetested=$row['datetested'];
 $datemodified=$row['datemodified'];
 $datedispatched=$row['datedispatched'];
 $showstatus = GetReceivedStatus($receivedstatus);//display received status
					$showresult = GetResultType($result);//display the result type
					///////////////////////////////////////////////////////////////////////////////
					$facilityname = GetFacility($facility);
						///////////////////////////////////////////////////////////////////////////////
					$samplecount = $samplecount + 1;
					 $datecollected=date("d-M-Y",strtotime($datecollected));
					  $datereceived=date("d-M-Y",strtotime($datereceived));
					   $datetested=date("d-M-Y",strtotime($datetested));
					   $datemodified=date("d-M-Y",strtotime($datemodified));
					   $datedispatched=date("d-M-Y",strtotime($datedispatched));
			
	
	
	$count=$count+1;
	
	$pdf->SetFillColor(226,231,245);			
	$start_row = $start_row_init;
	$pdf->Cell($offset);
	$pdf->Cell(10, 6, $samplecount, 1, 0, 'L', 0);
	$pdf->Cell(30, 6, $patient, 1, 0, 'L', 0);
	$pdf->Cell(40, 6, $facilityname, 1, 0, 'C', 0);
	$pdf->Cell(15, 6, $batchno, 1, 0, 'C', 0);
		$pdf->Cell(20, 6, $showstatus  , 1, 0, 'C', 0);
			$pdf->Cell(10, 6,$spots , 1, 0, 'C', 0);
	$pdf->Cell(25, 6, $datecollected, 1, 0, 'C', 0);
		$pdf->Cell(25, 6, $datereceived, 1, 0, 'C', 0);
	$pdf->Cell(20, 6, $worksheet, 1, 0, 'C', 0);
	$pdf->Cell(25, 6, $datetested, 1, 0, 'C', 0);
	$pdf->Cell(20, 6,$showresult , 1, 0, 'C', 0);
	$pdf->Cell(25, 6,$datedispatched , 1, 0, 'C', 0);


	$pdf->Ln();
}
$pdf->Ln(5);
//Create file
$batchno="Search Report for  ". $searchparameter.".pdf";
$F="I";
$pdf->Output($batchno,$F);?>