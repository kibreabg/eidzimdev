<?php
session_start();

$labss=$_SESSION['lab'];

$fromfilter = $_GET['fromfilter'];
$tofilter = $_GET['tofilter'];
$fromfilter=date("Y-m-d",strtotime($fromfilter));
$tofilter=date("Y-m-d",strtotime($tofilter));
$displayfromfilter=date("d-M-Y",strtotime($fromfilter));
$displaytofilter=date("d-M-Y",strtotime($tofilter));

require('./pdflibrary.php');
include("../connection/config.php");
include("../includes/functions.php");

define('FPDF_FONTPATH', 'font/');


$qury = "SELECT *
        FROM samples,facilitys WHERE samples.datereceived BETWEEN '$fromfilter' AND '$tofilter' AND samples.Flag = 1 AND samples.facility=facilitys.ID AND facilitys.lab='$labss'	
			ORDER BY samples.datereceived ASC";
		
			$a = mysql_query($qury) or die(mysql_error());
$noofsamples=mysql_num_rows($a);
$reason= "SAMPLES RECEIVED BETWEEN ". strtoupper($displayfromfilter) .  " AND ". strtoupper($displaytofilter) ;
class PDF extends FPDF
{
	function Header()
	{$this->SetFont('Arial','B',15);			// Arial bold 15
		
		$offset2 	=143;		// set parameters
					$this->Cell($offset2);
$this->Image('../img/kemrilogo.jpg',230,9);	// Logo

	$this->Ln(39);	
		

		
			
		$offsett=134;
			$this->Cell($offsett);
			
 	//$this->SetTextColor(204,204,204);

			$this->Cell(200,10," SAMPLES LOG BOOK ",0,0,'C');	// Title
	$offset 	=3;		// set parameters

	
	//	$this->SetTextColor(204,204,204);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('Times','B',10);
		//Header
		$this->Ln(25);
				
$this->Cell($offset);

 $this->SetFillColor(204,204,204);	
 	//$this->SetTextColor(204,200,214);
		$this->SetFont('');
		$header2=array('Facility','Patient Infomation','Sample Infomation','Mother Informaton');
		$w2=array(166,75,194,60);
for($i=0;$i<count($header2);$i++)
			$this->Cell($w2[$i],7,$header2[$i],1,0,'C',1);
 $this->Ln(7);
$this->Cell($offset);

		$header=array('No','Facility','Province','District','Patient ID','Sex','Age','Proph.','D. Collected','D. Received','Cond.','Spots','D.Tested','Result','D.Updated','D.Dispatched','Status','Drug(s)','B.F','Entry');
		$w=array(10,98,25,33,36,12,12,15,31,31,12,12,31,15,31,31,15,15,15,15);
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'L',1);

		
	$this->SetFont('Arial','B',50);
	$this->SetTextColor(204,204,204);//KENYA MEDICAL RESEARCH INSTITUTE
	$this->RotatedText(235,210,'KEMRI',45);
		
	}
function RotatedText($x, $y, $txt, $angle)
{
	//Text rotated around its origin
	$this->Rotate($angle,$x,$y);
	$this->Text($x,$y,$txt);
	$this->Rotate(0);
}
var $angle=0;

function Rotate($angle,$x=-1,$y=-1)
{
	if($x==-1)
		$x=$this->x;
	if($y==-1)
		$y=$this->y;
	if($this->angle!=0)
		$this->_out('Q');
	$this->angle=$angle;
	if($angle!=0)
	{
		$angle*=M_PI/180;
		$c=cos($angle);
		$s=sin($angle);
		$cx=$x*$this->k;
		$cy=($this->h-$y)*$this->k;
		$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
	}
}
function Footer()
{
	$this->SetY(-25);			// Position at 1.5 cm from bottom
	$this->SetFont('Arial','B',8);		// Arial italic 8
		/*$this->Cell(50,10,'© 2010 Rutna Real Estate ',0,0,'C');	// Title
$this->Ln(4);	*/	
	$this->Cell(100,5,'Key Codes',0,0, 'L');
 $this->Ln(5);
 	 	$this->MultiCell(0, 5 ,	'Cond [Received Status]: 1-Accepted, 2-Rejected , 3-Repeat | Results/Status: 1-Negative, 2-Positive, 3-Failed, 4-Unknown | Infant Feeding:- EBF-Exclusive Breast Feeding , MBF-Mixed Breast Feeding ,ERF-Exclusive Replacement Feeding , BF- Breast Feeding, NBF-Not Breast Feeding  ,None-No Data | Drug(s)[PMTCT Intervention]: 1-SdNVP Only,2-Interrupted HAART,3-AZT+NVP+3TC,4-HAART,5-None,6-Other,7-No Data  | Proph [Infant Prophylaxis]:-  Infant Prophylaxis: 8-SdNVP Only, 9-Sd NVP+AZT+3TC,10-NVP for 6weeks,11-NVP during BF,12-Other,13-None,14-No Data | Entry [Entry Point]:- 1-OPD , 2-Paediatric Ward, 3-MCH/PMTCT, 4-CCC/PSC, 5-Maternity, 6-Other, 7-No Data ',0,1);

 $this->Ln(2);

		$this->Cell(100,10,'© 2010 NASCOP ',0,0, 'L');

$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');	// Page number
}

}
//Create new pdf file
$pdf=new PDF();

//Open file
//$pdf->Open();

//Disable automatic page break
//$pdf->SetAutoPageBreak(false);
$pdf->AliasNbPages();

//Add first page
$pdf->AddPage();
$offset 	= 3;		// set parameters
		$offset3 	=170;		// set parameters

$pdf->SetFont('Times','',12);
				$pdf->Ln(-16);
			$pdf->Cell($offset3);
$pdf->Cell(100, 2 ,$reason,'C',1,1);
	$pdf->Ln(4);			
			$pdf->Cell($offset);

$pdf->Cell(100, 2 ,'Number of Samples: '. $noofsamples,'C',1,1);

	$pdf->Ln(15);			

$n=0;
while($row = mysql_fetch_array($a))
{   
$scode =$row['ID'] ;
$sdoc=$row['datecollected'];
$sdoc=date("d-M-Y",strtotime($sdoc));
$sdrec=$row['datereceived'];
$sdrec=date("d-M-Y",strtotime($sdrec));
$sspot=$row['spots'];
$testresult=$row['result'];
$facility=$row['facility'];
$srecstatus=$row['receivedstatus'];
$scomments=$row['scomments'];
$patient=$row['patient'];
$date_of_test=$row['datetested'];
$date_dispatched=$row['datedispatched'];
$date_modified=$row['datemodified'];
$pgender=GetPatientGender($patient);
		//get sample facility name based on facility code
		$facilityname=GetFacility($facility) ;
		//get district and province
		//get selected district ID
		$distid=GetDistrictID($facility);	
		//get select district name and province id	
		$distname=GetDistrictName($distid);
		//get province ID
		$provid=GetProvid($distid);
			//get province name	
		$provname=GetProvname($provid);
		//patietn age
		$pAge=GetPatientAge($patient);
		//patient dob
		$pdob=GetPatientDOB($patient);
		//infant prophylaxis
		$pprophylaxis=GetPatientProphylaxis($patient);
		$pprophylaxisid=GetIDfromtableandname($pprophylaxis,"prophylaxis");
		//get sample sample test results
		$routcome=GetResultType($testresult);
		$routcomeid=GetIDfromtableandname($routcome,"results");
		//get sample recevied
		//$srecstatus=GetReceivedStatus($receivedstatus);
		//get mother id from patient 
		$mother=GetMotherID($patient);
		//mother hiv
		$mhiv=GetMotherHIVstatus($mother);
		$mhivid=GetIDfromtableandname($mhiv,"results");
		//mother pmtct intervention
		$mprophylaxis=GetMotherProphylaxis($mother);
		$mprophylaxisid=GetIDfromtableandname($mprophylaxis,"prophylaxis");
		//get mothers feeding type
		$mfeeding=GetMotherFeeding($mother);
		$mfeedingid=GetIDfromtableandname($mfeeding,"feedings");
		//get entry point
$entry=GetEntryPoint($mother);
$entryid=GetIDfromtableandname($entry,"entry_points");
	
$n=$n+1;
	
if ($date_of_test =="0000-00-00")
{
$date_of_test = "";
}
else
{
$date_of_test=date("d-M-Y",strtotime($date_of_test));
}
if ($date_dispatched =="")
{
$date_dispatched = "";
}
else
{
$date_dispatched=date("d-M-Y",strtotime($date_dispatched));
}
if ($date_modified =="0000-00-00")
{
$date_modified = "";
}
else
{
$date_modified=date("d-M-Y",strtotime($date_modified));
}

	 $pdf->SetFillColor(226,231,245);			


$start_row = $start_row_init;
$pdf->Cell($offset);
$pdf->Cell(10, 6, $n , 1, 0, 'L', 0);
	$pdf->Cell(98, 6, $facilityname , 1, 0, 'L', 0);
			$pdf->Cell(25, 6, $provname, 1, 0, 'L', 0);
	$pdf->Cell(33, 6, $distname, 1, 0, 'L', 0);

	$pdf->Cell(36, 6, $patient, 1, 0, 'L', 0);
	
	$pdf->Cell(12, 6, $pgender, 1, 0, 'C', 0);
	$pdf->Cell(12, 6, $pAge, 1, 0, 'L', 0);
		$pdf->Cell(15, 6, $pprophylaxisid, 1, 0, 'L', 0);
		$pdf->Cell(31, 6, $sdoc, 1, 0, 'L', 0);

	$pdf->Cell(31, 6, $sdrec, 1, 0, 'L', 0);
	$pdf->Cell(12, 6, $srecstatus, 1, 0, 'C', 0);
	$pdf->Cell(12, 6, $sspot, 1, 0, 'C', 0);
		$pdf->Cell(31, 6, $date_of_test, 1, 0, 'L', 0);
	$pdf->Cell(15, 6, $routcomeid, 1, 0, 'C', 0);
	$pdf->Cell(31, 6, $date_modified, 1, 0, 'L', 0);
	$pdf->Cell(31, 6, $date_dispatched, 1, 0, 'L', 0);

	
	$pdf->Cell(15, 6, $mhivid, 1, 0, 'C', 0);
	$pdf->Cell(15, 6, $mprophylaxisid, 1, 0, 'L', 0);
	$pdf->Cell(15, 6, $mfeeding, 1, 0, 'C', 0);
	$pdf->Cell(15, 6, $entryid, 1, 0, 'L', 0);


	


	$pdf->Ln();


}
$pdf->Ln(5);
$batchno="LOG BOOK FOR SAMPLES RECEIVED BETWEEN ". $displayfromfilter ." - ". $displayfromfilter.".pdf";
$F="I";
$pdf->Output($batchno,$F);?>