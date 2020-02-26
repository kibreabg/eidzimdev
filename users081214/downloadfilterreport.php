<?php
session_start();
//require('./pdflibrary7.php');
	include("../connection/config.php");
	include('../includes/functions.php');
	
require('rotation.php');

define('FPDF_FONTPATH', 'font/');
	$startdate=$_GET['startdate'];
	$enddate=$_GET['enddate'];
	
	$fcode=$_GET['fcode'];
	
	$dislaystartdate=date("d-M-Y",strtotime($startdate));  //formatted date in DAY-MONTH-YEAR FOR DISPLAY
	$dislayenddate=date("d-M-Y",strtotime($enddate));

$facilityname=GetFacility($fcode);

//...used date received

$qury = "SELECT *
        FROM samples WHERE datereceived BETWEEN '$startdate' AND '$enddate' AND samples.Flag = 1 AND facility='$fcode'  AND repeatt = 0	
		ORDER BY samples.datereceived ASC";
$a = mysql_query($qury) or die(mysql_error());
$noofsamples=mysql_num_rows($a);
$reason=  $dislaystartdate .  " - ". $dislayenddate ;
$facarray=getFacilityDetails($fcode);
extract($facarray);
class PDF extends PDF_Rotate
{
	function Header()
	{
		$startdate=$_GET['startdate'];
	$enddate=$_GET['enddate'];
	
	$fcode=$_GET['fcode'];
	
	$dislaystartdate=date("d-M-Y",strtotime($startdate));  //formatted date in DAY-MONTH-YEAR FOR DISPLAY
	$dislayenddate=date("d-M-Y",strtotime($enddate));

$facilityname=GetFacility($fcode);
			$offset2 	=27;		// set parameters
			
					$this->Cell($offset2);	// set parameters
$this->Image('../img/lablogo.jpg',20,10);	// Logo
$this->SetFont('Arial','B',14);	
	$this->Cell(217,10," NATIONAL MICROBIOLOGY REFERENCE LABORATORY",0,0,'C');	// Title
$this->Image('../img/zimlogo.jpg',260,10);	// Logo
$this->SetFont('Arial','B',10);			// Arial bold 15
		
	$this->Ln(25);	
	$this->Cell($offset2);
 //	$this->SetTextColor(204,204,204);

	
	$this->Cell(217,10," HIV DNA PCR LABORATORY REQUEST FORMS  RECEIVED BETWEEN ".sprintf($dislaystartdate) . " & ".sprintf($dislayenddate),0,0,'C');	// Title 
	$offset 	= 15;		// set parameters

	
	//	$this->SetTextColor(204,204,204);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('Times','B',10);
		//Header
		$this->Ln(28);
				
$this->Cell($offset);

 $this->SetFillColor(204,204,204);	
 	//$this->SetTextColor(204,200,214);
		$this->SetFont('');
		

		$header=array('Patient ID ','Age (Months)','Gender','Date Collected','Date Received','Date of Test','Test Result','Date of Dispatch','TAT(dys)');
		$w=array(40,20,20,35,35,35,25,35,15);
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'C',1);

$this->SetFont('Arial','B',30);
	$this->SetTextColor(204,204,204);//KENYA MEDICAL RESEARCH INSTITUTE
	$this->RotatedText(135,140,'NMRL',45);
		
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
	/*	$this->SetY(-39);			// Position at 1.5 cm from bottom
	$this->SetFont('Arial','',5);		// Arial italic 8
	
	 $this->Ln(5);
	 $offset9=170;
	 // $offset5=20;
//$this->Cell($offset9);
 $offset5 	=25;		// set parameters
 $offset7 	=15;		// set parameters

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
				$this->Cell(300,10,'© 2010 NASCOP ',0,0, 'C');
$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');	// Page number
	
	*/
}
}
//Create new pdf file
$pdf=new PDF();


$pdf->AliasNbPages();
$pdf->SetFont('Times','',8);

//Add first page
$pdf->AddPage();
$offset 	=15;		// set parameters

				$pdf->Ln(-14);
				$offset3 	=117;		// set parameters
				//$pdf->Cell($offset3);
				//$pdf->Cell(180, 2 ,$reason,'C',1,1);
$pdf->SetFont('Times','',11);

		$pdf->Cell($offset);
		$pdf->Cell(200, 4, 'Hospital Name: ' .$facilityname , 0, 0, 'L', 0);
		$pdf->Ln(4);
		$pdf->Cell($offset);
		$pdf->Cell(100, 4, 'Contact Person: ' .$contactperson , 0, 0, 'L', 0);
		$pdf->Cell(100, 4, 'Email: '. $email .'  '.$ContactEmail, 0, 0, 'L', 0);
		$pdf->Ln(4);
		$pdf->Cell($offset);
		$pdf->Cell(100, 4, 'Contact Address: '.$PostalAddress , 0, 0, 'L', 0);
		$pdf->Cell(100,4, 'Contact Telephone: '.$contacttelephone  , 0, 0, 'L', 0);
		$pdf->Cell(100, 2 ,'Number of Samples: '. $noofsamples,'C',1,1);

	$pdf->Ln(-4);
//	$pdf->Cell($offset3);
	//$pdf->Cell(75, 2 ,$reason2,'C',1,1);
	//$pdf->Ln(4);			
			
			$pdf->Cell($offset);


	$pdf->Ln(15);			

while($row = mysql_fetch_array($a))
{   
 $patient=$row['patient'];
 $outcome=$row['result'];
$pgender=GetPatientGender($patient);
//patietn age
$pAge=GetPatientAge($patient);
$datetested=$row['datetested'];
$datetested=date("d-M-Y",strtotime($datetested));	
$datereceived=$row['datereceived'];
$datereceived=date("d-M-Y",strtotime($datereceived));	

$datemodified=$row['datemodified'];
$datemodified=date("d-M-Y",strtotime($datemodified));
$datecollected=$row['datecollected'];
$datecollected=date("d-M-Y",strtotime($datecollected));
$datedispatched=$row['datedispatched'];
$datedispatched=date("d-M-Y",strtotime($datedispatched));
//get sample sample test results
 $routcome=GetResultType($outcome);;
$datereceived4=date("d-m-Y",strtotime($datereceived));
$datedispatched4=date("d-m-Y",strtotime($datedispatched));
$tot = round((strtotime($datedispatched4) - strtotime($datereceived4)) / (60 * 60 * 24));		
	
	
	
	$pdf->SetFillColor(226,231,245);			
	$start_row = $start_row_init;
	$pdf->Cell($offset);
	$pdf->Cell(40, 4, $patient, 1, 0, 'L', 0);
	$pdf->Cell(20, 4, $pAge, 1, 0, 'C', 0);
	$pdf->Cell(20, 4, $pgender, 1, 0, 'C', 0);
	$pdf->Cell(35,4, $datecollected, 1, 0, 'C', 0);
	$pdf->Cell(35, 4, $datereceived, 1, 0, 'C', 0);
	$pdf->Cell(35, 4, $datetested, 1, 0, 'C', 0);
	$pdf->Cell(25, 4,$routcome , 1, 0, 'C', 0);
	$pdf->Cell(35, 4,$datedispatched , 1, 0, 'C', 0);
	$pdf->Cell(15, 4,$tot , 1, 0, 'C', 0);

	$pdf->Ln();
}
$pdf->Ln(2);


//Create file
$display="Filtered Report of samples Received from ". $facilityname ." between ". $dislaystartdate . " & ".$dislayenddate .".pdf";
$F="I";
$pdf->Output($display,$F);?>