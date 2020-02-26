<?php
session_start();
//require('./pdflibrary7.php');
	include("../connection/config.php");
	include('../includes/functions.php');
	
require('rotation.php');

define('FPDF_FONTPATH', 'font/');
	$monthly=$_GET['month'];
	$year=$_GET['year'];
	//translate the month values to text
			 if ($monthly ==1) {$month = strtoupper("January");}
		else if ($monthly ==2) {$month = strtoupper("February");}
		else if ($monthly ==3) {$month = strtoupper("March");}
		else if ($monthly ==4) {$month = strtoupper("April");}
		else if ($monthly ==5) {$month = strtoupper("May");}
		else if ($monthly ==6) {$month = strtoupper("June");}
		else if ($monthly ==7) {$month = strtoupper("July");}
		else if ($monthly ==8) {$month = strtoupper("August");}
		else if ($monthly ==9) {$month = strtoupper("September");}
		else if ($monthly ==10) {$month = strtoupper("October");}
		else if ($monthly ==11) {$month = strtoupper("November");}
		else if ($monthly ==12) {$month = strtoupper("December");}
	
	$labss = $_SESSION['lab'];



$qury = "SELECT s.ID,s.patient,s.facility,s.batchno,s.receivedstatus,s.spots,s.datecollected,s.datereceived,s.datetested,s.datemodified,s.datedispatched,s.result,s.worksheet FROM samples s, facilitys f WHERE MONTH(s.datereceived) = $monthly AND YEAR(s.datereceived) = $year AND s.flag = 1 AND s.facility = f.ID AND f.lab = $labss AND s.repeatt=0 ORDER BY s.datereceived DESC";//"SELECT s.ID,s.patient,s.facility,s.batchno,s.receivedstatus,s.spots,s.datecollected,s.datereceived,s.datetested,s.datemodified,s.datedispatched,s.result,s.worksheet FROM samples s, facilitys f WHERE MONTH(s.datereceived) = '$monthly' AND YEAR(s.datereceived) = '$year' AND s.flag = 1 AND s.facility = f.ID AND f.lab = '$labss' AND s.repeatt=0 ORDER BY s.datereceived DESC ";
$a = mysql_query($qury) or die(mysql_error());
$noofsamples=mysql_num_rows($a);

class PDF extends PDF_Rotate
{
	function Header()
	{
		$monthly=$_GET['month'];
	$year=$_GET['year'];
	//translate the month values to text
			if ($monthly ==1) {$month = strtoupper("January");}
		else if ($monthly ==2) {$month = strtoupper("February");}
		else if ($monthly ==3) {$month = strtoupper("March");}
		else if ($monthly ==4) {$month = strtoupper("April");}
		else if ($monthly ==5) {$month = strtoupper("May");}
		else if ($monthly ==6) {$month = strtoupper("June");}
		else if ($monthly ==7) {$month = strtoupper("July");}
		else if ($monthly ==8) {$month = strtoupper("August");}
		else if ($monthly ==9) {$month = strtoupper("September");}
		else if ($monthly ==10) {$month = strtoupper("October");}
		else if ($monthly ==11) {$month = strtoupper("November");}
		else if ($monthly ==12) {$month = strtoupper("December");}
	
	$labss = $_SESSION['lab'];
			$offset2 	=27;		// set parameters
			
					$this->Cell($offset2);	// set parameters
$this->Image('../img/logopdf.jpg',20,10);	// Logo
$this->SetFont('Arial','B',14);	
	$this->Cell(217,10," NMRL Zimbabwe",0,0,'C');	// Title
$this->Image('../img/naslogo.jpg',260,10);	// Logo
$this->SetFont('Arial','B',10);			// Arial bold 15
		
	$this->Ln(25);	
	$this->Cell($offset2);
 //	$this->SetTextColor(204,204,204);

	
	$this->Cell(217,8," INFANT DIAGNOSIS  REPORT FOR SAMPLES RECEIVED IN ".sprintf($month) . ", ".sprintf($year)  ,0,0,'C');	// Title 
	$offset 	= 8;		// set parameters

	
	//	$this->SetTextColor(204,204,204);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('Times','B',10);
		//Header
		$this->Ln(15);
				
$this->Cell($offset);

 $this->SetFillColor(204,204,204);	
 	//$this->SetTextColor(204,200,214);
		$this->SetFont('');
		

		$header=array('Infant ID / Sample Code ','Age (months)','Gender','Date Collected','Date Received','Received Status','Date of Test','Test Result','Date of Dispatch','TAT(dys)');
		$w=array(40,20,15,30,30,25,30,25,30,15);
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],5,$header[$i],1,0,'C',1);

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
$this->Cell(160,3,' If you have questions or problems regarding samples, please contact the NMRL at',0,0, 'L');
$this->Ln(3);
	$this->Cell($offset7);
$this->Cell(160, 3, 'National Microbiology Reference Laboratory' , 0, 0, 'L', 0);
$this->Ln(3);
$this->Cell($offset7);
$this->Cell(160, 3, 'New Laboratory Complex 2nd Floor' , 0, 0, 'L', 0);
$this->Ln(3);
	$this->Cell($offset7);
$this->Cell(160, 3, 'Harare Central Hospita, P.O. Box ST 749,Southerton ,Harare, Zimbabwe ' , 0, 0, 'L', 0);
$this->Ln(3);
$this->Cell($offset7);
	$this->Cell(160, 3, 'Email:eid-info@nmrl.org.zw' , 0, 0, 'L', 0);
	
				$this->Ln(2);
				$this->Cell(300,5,'© 2010 NMRL ',0,0, 'C');
$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');	// Page number
	
	


		



}
}
//Create new pdf file
$pdf=new PDF();


$pdf->AliasNbPages();
$pdf->SetFont('Times','',8);

//Add first page
$pdf->AddPage();
$offset 	=8;		// set parameters

				$pdf->Ln(-5);
				$offset3 	=117;		// set parameters
				//$pdf->Cell($offset3);
				//$pdf->Cell(180, 2 ,$reason,'C',1,1);
$pdf->SetFont('Times','',11);

		$pdf->Cell($offset);
			$pdf->Cell(100, 2 ,'Number of Samples: '. $noofsamples,'C',1,1);
		//$pdf->Ln(1);
		
	//$pdf->Ln(-4);
//	$pdf->Cell($offset3);
	//$pdf->Cell(75, 2 ,$reason2,'C',1,1);
	//$pdf->Ln(4);			
			
			$pdf->Cell($offset);


	$pdf->Ln(8);			

while($row = mysql_fetch_array($a))
{   
 $patient=$row['patient'];
 $outcome=$row['result'];
$pgender=GetPatientGender($patient);
//patietn age
$pAge=GetPatientAge($patient);
$receivedstatus=$row['receivedstatus'];
	$showstatus = GetReceivedStatus($receivedstatus);//display received status
$datetested=$row['datetested'];
$datemodified=$row['datemodified'];
$datedispatched=$row['datedispatched'];
$datecollected=$row['datecollected'];
$datecollected=date("d-M-Y",strtotime($datecollected));
$datereceived=$row['datereceived'];
$datereceived=date("d-M-Y",strtotime($datereceived));	
if (($receivedstatus ==2) || (($outcome < 0 )||($outcome =="")))
							{
		$datetested="";
		$datemodified="";
		$datedispatched="";
		 $routcome="";
							} 
							else
							{
							
							
							
					   $datetested=date("d-M-Y",strtotime($datetested));
					   $datemodified=date("d-M-Y",strtotime($datemodified));
					 
					   $datereceived4=date("d-m-Y",strtotime($datereceived));
					   if ($datedispatched !="")
					   {
  $datedispatched=date("d-M-Y",strtotime($datedispatched));	//get sample sample test results
$datedispatched4=date("d-m-Y",strtotime($datedispatched));
$tot = round((strtotime($datedispatched4) - strtotime($datereceived4)) / (60 * 60 * 24));	

 $routcome=GetResultType($outcome);}
else
{
}

					   }





	
	
	
	$pdf->SetFillColor(226,231,245);			
	$start_row = $start_row_init;
	$pdf->Cell($offset);
	$pdf->Cell(40, 4, $patient, 1, 0, 'L', 0);
	$pdf->Cell(20, 4, $pAge, 1, 0, 'C', 0);
	$pdf->Cell(15, 4, $pgender, 1, 0, 'C', 0);
	$pdf->Cell(30,4, $datecollected, 1, 0, 'C', 0);
	$pdf->Cell(30, 4, $datereceived, 1, 0, 'C', 0);
	$pdf->Cell(25, 4, $showstatus, 1, 0, 'C', 0);
	$pdf->Cell(30, 4, $datetested, 1, 0, 'C', 0);
	$pdf->Cell(25, 4,$routcome , 1, 0, 'C', 0);
	$pdf->Cell(30, 4,$datedispatched , 1, 0, 'C', 0);
	$pdf->Cell(15, 4,$tot , 1, 0, 'C', 0);

	$pdf->Ln();
}
$pdf->Ln(2);


//Create file
$display="Filtered Report of samples Received for ". $month .", ". $year .".pdf";
$F="I";
$pdf->Output($display,$F);?>