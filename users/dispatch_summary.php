<?php
session_start();
//require('./pdflibrary7.php');
	include("../connection/config.php");
	include('../includes/functions.php');
	
require('rotation.php');

define('FPDF_FONTPATH', 'font/');
	$batch=$_GET['ID'];

$getresult = "SELECT * FROM  samples WHERE batchno='$batch'  and repeatt = 0 ORDER BY parentid ASC";
$a = mysql_query($getresult) or die('Error, query failed');
$num_samples=GetSamplesPerBatch($batch);

//get patient/sample code
		$patient=GetPatient($batchno);
		//get bach received date
		$sdrec=GetDatereceived($batchno);
		//get patient gender and mother id based on sample code of sample
		$mid=GetMotherID($patient);
		//get patient gender
		$pgender=GetPatientGender($patient);
		//get sample facility code based  on mothers id
		$facility=GetFacilityCode($batchno);
		//get sample facility name based on facility code
		$facilityname=GetFacility($facility);
		$facarray=getFacilityDetails($facility);
		extract($facarray);
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

	
	$this->Cell(217,10," INFANT DIAGNOSIS RESULTS REPORT",0,0,'C');	// Title
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
		

		$header=array('No','Infant ID / Sample Code ','Test Type','Age (months)','Gender','Date Collected','Date Received','Date of Test','Test Result','Date of Dispatch','TAT(dys)');
		$w=array(10,40,15,20,20,30,30,30,25,30,15);
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'C',1);

$this->SetFont('Arial','B',45);
	$this->SetTextColor(204,204,204);//KENYA MEDICAL RESEARCH INSTITUTE
	$this->RotatedText(135,140,'KEMRI',45);
		
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
$this->Cell($offset7);
$this->Cell(160,5,' NOTE: TAT [ Turn Around Time = Date Received at Lab - Date Collected at Facility ] - Test Type : 1- 1st Test , 2- Repeat for rejection , 3- Confirmatory PCR @9mnths',0,0, 'L');
 $this->Ln(5);
$this->Cell($offset7);
$this->Cell(160,5,' NOTE: FAILED SAMPLES REQUIRE NEW SAMPLE COLLECTION',0,0, 'L');
$this->Ln(5);
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
$this->Cell(160, 5, 'Tel: 020 2722541 Ext: 2256/2290 ' , 0, 0, 'L', 0);
$this->Ln(5);
$this->Cell($offset7);
	$this->Cell(160, 5, 'Email: eid-nairobi@googlegroups.com' , 0, 0, 'L', 0);
//eid@kemri.org
	
				$this->Ln(2);
				$this->Cell(300,10,'© 2010 KEMRI ',0,0, 'C');
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
				$pdf->Ln(-21);
				
$offset3 	=100;		// set parameters
//$pdf->Cell($offset3);
//$pdf->Cell(75, 2 ,$reason,'C',1,1);
		$pdf->Cell($offset);
		$pdf->Cell(200, 5, 'Batch No. ' .$batch , 0, 0, 'L', 0);
		$pdf->Ln(5);
		$pdf->Cell($offset);
		$pdf->Cell(200, 5, $facilityname , 0, 0, 'L', 0);
		$pdf->Ln(5);
		$pdf->Cell($offset);
		$pdf->Cell(100, 5, 'Contact Person: ' .$contactperson , 0, 0, 'L', 0);
		$pdf->Cell(100, 5, 'Email: '. $email .'  '.$ContactEmail, 0, 0, 'L', 0);
		$pdf->Ln(5);
		$pdf->Cell($offset);
		$pdf->Cell(100, 5, 'Contact Address: '.$PostalAddress , 0, 0, 'L', 0);
		$pdf->Cell(100,5, 'Contact Telephone: '.$contacttelephone  , 0, 0, 'L', 0);
		$pdf->Cell(100, 2 ,'Number of Samples: '. $num_samples,'C',1,1);

	$pdf->Ln(-4);
//	$pdf->Cell($offset3);
	//$pdf->Cell(75, 2 ,$reason2,'C',1,1);
	//$pdf->Ln(4);			
			
			$pdf->Cell($offset);


	$pdf->Ln(15);			
$count=0;
while($row = mysql_fetch_array($a))
{   
$samplecode=$row['ID'];
 $patient=$row['patient'];
 $wno=$row['worksheet'];

  $receivedstatus=$row['receivedstatus'];
    $rejectedreason=$row['rejectedreason'];
	$rejectedreason=GetRejectedReason($rejectedreason);
	if  ($wno !="")
	{
	
$worksheet2 = getWorksheetDetails($wno);
extract($worksheet2);
$approvedby=GetUserFullnames($reviewedby);
}
 
 $outcome=$row['result'];
$pgender=GetPatientGender($patient);
//patietn age
$pAge=GetPatientAge($patient);
$datereceived=$row['datereceived'];
$datereceived=date("d-M-Y",strtotime($datereceived));
$datecollected=$row['datecollected'];
$datecollected=date("d-M-Y",strtotime($datecollected));	
$datetested=$row['datetested'];
$reason_for_repeat=$row['reason_for_repeat'];
 if  ($reason_for_repeat =="")
 { //1st test
$testtype=1;
 }
 else if ($reason_for_repeat =="Repeat For Rejection")
 {
 $testtype="2";

 }
 else  if ($reason_for_repeat =="Confirmatory PCR at 9 Mths")
 {
  $testtype="3";
 }
 
if   ($receivedstatus != 2)
{
	if  ($datetested !="")
	{
	
	$datetested=date("d-M-Y",strtotime($datetested));	
	}
	else
	{
	$datetested ="";
	}
$datemodified=$row['datemodified'];
$datemodified=date("d-M-Y",strtotime($datemodified));
$datedispatched=$row['datedispatched'];
$datedispatched=date("d-M-Y",strtotime($datedispatched));
//get sample sample test results
 $routcome=GetResultType($outcome);;
$datereceived4=date("d-m-Y",strtotime($datereceived));
$datecollected4=date("d-m-Y",strtotime($datecollected));
$tot = round((strtotime($datereceived4) - strtotime($datecollected4)) / (60 * 60 * 24));	
}
else
{
//get sample recevied
		$srecstatus=GetReceivedStatus($receivedstatus);
$datetested=$srecstatus;
$routcome=$rejectedreason;
$daterejdispatched=GetDateDispatchedforRejectedSample($samplecode);
if (($daterejdispatched != "" ) || ($daterejdispatched != "0000-00-00"))
{
$datedispatched=date("d-M-Y",strtotime($daterejdispatched));
}
else
{
$datedispatched="";
}

}
	
	
	$count=$count+1;
	
	$pdf->SetFillColor(226,231,245);			
	$start_row = $start_row_init;
	$pdf->Cell($offset);
	$pdf->Cell(10, 6, $count, 1, 0, 'L', 0);
	$pdf->Cell(40, 6, $patient, 1, 0, 'L', 0);
	$pdf->Cell(15, 6, $testtype, 1, 0, 'C', 0);
	$pdf->Cell(20, 6, $pAge, 1, 0, 'C', 0);
	$pdf->Cell(20, 6, $pgender, 1, 0, 'C', 0);
	$pdf->Cell(30, 6, $datecollected, 1, 0, 'C', 0);
	$pdf->Cell(30, 6, $datereceived, 1, 0, 'C', 0);
	$pdf->Cell(30, 6, $datetested, 1, 0, 'C', 0);
	$pdf->Cell(25, 6,$routcome , 1, 0, 'C', 0);
	$pdf->Cell(30, 6,$datedispatched , 1, 0, 'C', 0);
	$pdf->Cell(15, 6,$tot , 1, 0, 'C', 0);

	$pdf->Ln();
}
$pdf->Ln(5);
$offset0=240;
$pdf->Cell($offset0);
$pdf->Cell(15, 6,'Approved By '. $approvedby , 0, 0, 'C', 0);
//Create file
$pdf->Output();?>