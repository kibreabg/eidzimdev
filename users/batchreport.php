<?php
session_start();
require('./pdflibrary.php');

require('../connection/config.php');
//require('./rotation2.php');

define('FPDF_FONTPATH', 'font/');

include("../includes/functions.php");
$batchno=$_GET['ID'];
//get patient/sample code
		$patient=GetPatient($batchno);
		//get bach received date
		$sdrec=GetDatereceived($batchno);
		//get patient gender and mother id based on sample code of sample
			$datebatchreceived=date("Y-m-d",strtotime($sdrec));	
		
		$mid=GetMotherID($patient);
		//get patient gender
		$pgender=GetPatientGender($patient);
		//get sample facility code based  on mothers id
		$facility=GetFacilityCode($batchno);
		//get sample facility name based on facility code
		$facilityname=GetFacility($facility);
		//get actual facility code
		$facilitydetails= getFacilityDetails($facility);
		extract($facilitydetails);
		if (($physicaladdress =="") &&  ($PostalAddress ==""))
		{
		$address="";
		}
		else if (($physicaladdress !="") &&  ($PostalAddress ==""))
		{
		$address=$physicaladdress;
		}
		else
		{
		$address=$PostalAddress .  " ".$physicaladdress;
		}
		
		//format display for contact person
			if (($contacttelephone =="") &&  ($contacttelephone2 ==""))
		{
			$phone="";
		}
		else if (($contacttelephone2 !="") &&  ($contacttelephone ==""))
		{
			$phone=$contacttelephone2;
		}
		else
		{
		$phone=$contacttelephone . ' / '. $contacttelephone2;
		}
				//format display fortelephone
					if (($telephone =="") &&  ($telephone2 ==""))
		{
			$facilityphone="";
		}
		else if (($telephone2 !="") &&  ($telephone ==""))
		{
			$facilityphone=$telephone2;
		}
		else
		{
		$facilityphone=$telephone . ' / '. $telephone2;
		}
		
		//format display for email
					if (($email =="") &&  ($ContactEmail ==""))
		{
			$femail="";
		}
		else if (($ContactEmail =="") &&  ($email !=""))
		{
			$femail=$email;
		}
		else if (($ContactEmail !="") &&  ($email ==""))
		{
			$femail=$ContactEmail;
			
		}
		else
		{
		$femail=$email . ' / '. $ContactEmail;
		}
		
		//get district and province
		//get selected district ID
		$distid=GetDistrictID($facility);	
		//get select district name and province id	
		$distname=GetDistrictName($distid);
		//get province ID
		$provid=GetProvid($distid);
			//get province name	
		$provname=GetProvname($provid);	
		
		//get approved requisition
		
define('FPDF_FONTPATH', 'font/');
//get samples in that batch
$qury = "SELECT *
            FROM samples
			WHERE batchno='$batchno'	
			AND repeatt = 0  AND Flag=1  ORDER BY parentid ASC";		
$a = mysql_query($qury) or die(mysql_error());
$b = mysql_query($qury) or die(mysql_error());
$row2 = mysql_fetch_array($b);

$comments=$row2['comments'] ;  
$datedispatchedfromfacility=$row2['datedispatchedfromfacility'] ; 

if (($datedispatchedfromfacility !="0000-00-00") )
{
$datedispatchedfromfacility=date("d-M-Y",strtotime($datedispatchedfromfacility));	
}
else 
{
$datedispatchedfromfacility=" ";
}

//count no of samples in batch
$noofsamples=mysql_num_rows($a);

//$reason= "Batch No ". $batchno . "  |  Facility: ".  $facilityname . " | "." Province: ".  $provname. " | ". "District: ".  $distname;


class PDF extends FPDF
{	
	function Header()
	{$this->SetFont('Arial','B',12);			// Arial bold 15
		$batchno=$_GET['ID'];
		$offset0 	=220;		// set parameters
			$offset11 	=220;	
					$this->Cell($offset2);
$this->Image('../img/zimlogoxm.jpg',250,5);	// Logo

	$this->Ln(29);	
		$this->Cell($offset11);
	$this->Cell(70,7," MINISTRY OF HEALTH & CHILD WELFARE",0,0,'C');	// Title
				$this->Ln(12);	
		
	$offset2 	=175;			
$this->Cell($offset0);
			$this->Cell(70,7," ZIMBABWE ",0,0,'C');	// Title
				$this->Ln(13);	
			
//$this->SetTextColor(204,204,204);
//$batchno=3;
$offsetb 	= 5;		// set parameters
$this->Cell($offsetb);
$this->Cell(50,7,sprintf("Batch No.". $batchno),0,0,'C');
$this->Cell($offset2);
						$this->Cell(50,7,"EARLY INFANT HIV DIAGNOSIS (DNA-PCR) TEST SUMMARY RESULTS ",0,0,'C');	// Title
	$offset 	= 25;		// set parameters
// set parameters

	
		$this->SetTextColor(204,204,204);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('Times','B',12);
		//Header
		$this->Ln(25);
				
$this->Cell($offset);

 $this->SetFillColor(0,82,108);	
 	$this->SetTextColor(204,200,204);
		$this->SetFont('');
		
$this->SetFont('Arial','B',45);
	$this->SetTextColor(204,204,204);//KENYA MEDICAL RESEARCH INSTITUTE
	$this->RotatedText(240,200,'NMRL',38);
		
		
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
	$this->SetY(-47);			// Position at 1.5 cm from bottom
	$this->SetFont('Times','',9);		// Arial italic 8
	
	 $this->Ln(5);
	 $offset9=170;
	  $offset5=10;
/*

		$this->Cell(230,4,' Forward samples to any of the following labaratories; whichever is nearer and/or most convenient ',0,0, 'C');$this->Cell(180,4,' Key Codes ',0,0, 'C');
			 $this->Ln(5);
$this->Cell($offset5);
	$this->Cell(100, 5, 'NAIROBI LAB' , 1, 0, 'L', 0);
		$this->Cell(65, 5, 'KISUMU LAB ' , 1, 0, 'L', 0);
		$this->Cell(65, 5, 'KERICHO LAB ' , 1, 0, 'L', 0);
		$this->Cell(52, 5, 'BUSIA LAB ' , 1, 0, 'L', 0);
		$this->Cell(5, 5, ' ' , 0, 0, 'L', 0);
	$this->Cell(207, 5, '  Test Type : 1- 1st Test , 2- Repeat for rejection , 3- Confirmatory PCR at 9mnths' , 1, 0, 'L', 0);
		$this->Ln(5);
	$this->Cell($offset5);
$this->Cell(100, 5, 'KEMRI HIV-P3 Lab ' , 1, 0, 'L', 0);
		$this->Cell(65, 5, 'CDC HIV/R Lab ', 1, 0, 'L', 0);
		$this->Cell(65, 5, 'Walter Reed CRC Lab ' , 1, 0, 'L', 0);
		$this->Cell(52, 5, 'KEMRI CIPDCR -Alupe ' , 1, 0, 'L', 0);
		$this->Cell(5, 5, ' ' , 0, 0, 'L', 0);
		$this->Cell(207, 5, 'Entry Point: 1-OPD , 2-Paediatric Ward, 3-MCH/PMTCT, 4-CCC/PSC, 5-Maternity, 6-Other, 7-No Data ' , 1, 0, 'L', 0);
		$this->Ln(5);
			$this->Cell($offset5);
$this->Cell(100, 5, 'Centre for Virus Research ' , 1, 0, 'L', 0);
		$this->Cell(65, 5, 'Kisian ' , 1, 0, 'L', 0);
		$this->Cell(65, 5, 'Hospital Road' , 1, 0, 'L', 0);
		$this->Cell(52, 5, 'Busia - Malaba Rd, Busia ' , 1, 0, 'L', 0);
		$this->Cell(5, 5, ' ' , 0, 0, 'L', 0);
		$this->Cell(207, 5, 'Infant Prophylaxis: 8-SdNVP Only, 9-Sd NVP+AZT+3TC,10-NVP for 6weeks,11-NVP during BF,12-Other,13-None,14-No Data ' , 1, 0, 'L', 0);
		$this->Ln(5);
$this->Cell($offset5);
	$this->Cell(100, 5, 'KEMRI HQ, Mbagathi Road, Nairobi' , 1, 0, 'L', 0);
		$this->Cell(65, 5, 'Kisumu-Busia Road, Kisumu ' , 1, 0, 'L', 0);
		$this->Cell(65, 5, 'P O BOX 1357, Kericho ' , 1, 0, 'L', 0);
		$this->Cell(52, 5, ' ' , 1, 0, 'L', 0);
		$this->Cell(5, 5, ' ' , 0, 0, 'L', 0);
		$this->Cell(207, 5, 'Infant Feeding: 0-6 months EBF-Exclusive Breast Feeding , MBF-Mixed Breast Feeding ,ERF-Exclusive Replacement Feeding   ' , 1, 0, 'L', 0);
	$this->Ln(5);
	$this->Cell($offset5);
$this->Cell(100, 5, 'Tel: 020 2722541 Ext: 2256/2290 / 0725793260 / 0725796842 ' , 1, 0, 'L', 0);
		$this->Cell(65, 5, 'Tel: 057 2053017/8  / 0722204614', 1, 0, 'L', 0);
		$this->Cell(65, 5, 'Tel: 052 30388/21064  ' , 1, 0, 'L', 0);
		$this->Cell(52, 5, 'Tel: (055) 22410; 0726 156679 ' , 1, 0, 'L', 0);
		$this->Cell(5, 5, ' ' , 0, 0, 'L', 0);
		$this->Cell(207, 5, 'Infant Feeding: >6 Months  ,BF- Breast Feeding, NBF-Not Breast Feeding  ,None-No Data ' , 1, 0, 'L', 0);
	$this->Ln(5);
			
$this->Cell($offset5);
	$this->Cell(100, 5, 'Email: eid-nairobi@googlegroups.com' , 1, 0, 'L', 0);
		$this->Cell(65, 5, 'Email: eid-kisumu@googlegroups.com ' , 1, 0, 'L', 0);
		$this->Cell(65, 5, ' Email: eid-kericho@googlegroups.com' , 1, 0, 'L', 0);
		$this->Cell(52, 5, ' ' , 1, 0, 'L', 0);
		$this->Cell(5, 5, ' ' , 0, 0, 'L', 0);
		$this->Cell(207, 5, 'PMTCT Intervention: 1-SdNVP Only,2-Interrupted HAART,3-AZT+NVP+3TC,4-HAART,5-None,6-Other,7-No Data ' , 1, 0, 'L', 0);
				$this->Ln(5);
		
				$this->Cell(300,5,'© 2010 NASCOP ',0,0, 'R');
				//$this->Ln(2);
//$this->Cell(0,5,'Page '.$this->PageNo().'/{nb}',0,0,'C');	// Page number*/
}

}
//Create new pdf file
$pdf=new PDF();

//$pdf->SetAutoPageBreak(false);
$pdf->AliasNbPages();
//Add first page
$pdf->AddPage();
$offset 	= 10;		// set parameters
$offset3 	=155;		// set parameters
$pdf->SetFont('Times','B',12);
$pdf->Ln(-16);
			
$pdf->Cell($offset);	
	//$pdf->Cell(479, 8, 'Date samples were dispatched: '.$datedispatchedfromfacility , 1, 0, 'L', 0);
	$pdf->Ln(8);
	$pdf->Cell($offset);
	$pdf->Cell(200, 8, 'Facility Name: ' .$facilityname , 1, 0, 'L', 0);
		$pdf->Cell(130, 8, 'Contact: '. $contactperson , 1, 0, 'L', 0);
		$pdf->Cell(149, 8, 'Tel(personal): '.$phone , 1, 0, 'L', 0);
		$pdf->Ln(8);
		$pdf->Cell($offset);
$pdf->Cell(200, 8, 'Facility Code: '.$facilitycode , 1, 0, 'L', 0);
		$pdf->Cell(130, 8, 'District code: '.$distid , 1, 0, 'L', 0);
		$pdf->Cell(149, 8, 'Tel (facility): '.$facilityphone , 1, 0, 'L', 0);
			$pdf->Ln(8);
			$pdf->Cell($offset);
$pdf->Cell(200, 8, 'Province: '.$provname , 1, 0, 'L', 0);
		$pdf->Cell(130, 8, 'District : '.$distname , 1, 0, 'L', 0);
		$pdf->Cell(149, 8, '' , 1, 0, 'L', 0);
		$pdf->Ln(14);
$pdf->Cell($offset);
	$pdf->Cell(479, 8, 'Receiving Address (via Courier):'.$address , 1, 0, 'L', 0);
	$pdf->Ln(8);
	$pdf->Cell($offset);
	$pdf->Cell(479, 8, 'Email (optional-where provided results will be emailed and also sent by courier ): '.$femail , 1, 0, 'L', 0);
	

	$pdf->Ln(16);
	$pdf->SetFont('Arial','B',10);		// Arial italic 8
			$pdf->SetFont('Arial','B',12);		// Arial italic 8
$pdf->Cell($offset);
$header=array('SAMPLE LOG');
		$w2=array(480);
for($i=0;$i<count($header);$i++)
			$pdf->Cell($w2[$i],6,$header[$i],1,0,'C',0);
			$pdf->SetFont('Arial','B',10);		// Arial italic 8
 $pdf->Ln(6);
$pdf->Cell($offset);
$header1=array('Patient Information',' Samples ','Mother Informaton','Lab Information');
		$w3=array(135,120,110,115);
for($k=0;$k<count($header1);$k++)
			$pdf->Cell($w3[$k],7,$header1[$k],1,0,'C',0);
 $pdf->Ln(7);
$pdf->Cell($offset);

$header2=array('No','Patient ID','Sex','Age (mths)','Prophylaxis','Date Collected','Date Received','Status','Test Type','HIV Status','PMTCT ','Feeding ','Entry Point','Date Tested','Date Dispatched','Result','TAT');
		$w=array(10,55,15,25,30,35,35,25,25,25,35,25,25,35,35,25,20);
		for($j=0;$j<count($header2);$j++)
			$pdf->Cell($w[$j],7,$header2[$j],1,0,'C',0);
			$pdf->SetFont('Times','',12);

 $pdf->Ln(7);
 $count=0;
while($row = mysql_fetch_array($a))
{ 
$ID =$row['ID'] ;  
$patient=$row['patient'];
$datecollected=$row['datecollected'];
if  ($datecollected !="0000-00-00") 
	{
	$sdoc=date("d-M-Y",strtotime($datecollected));
	

	}
$spots=$row['spots'];
$receivedstatus=$row['receivedstatus'];
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
	if  ($datetested !="0000-00-00")
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
if  ($datedispatched !="")
	{
$datedispatched=date("d-M-Y",strtotime($datedispatched));
}
else
{
$datedispatched ="";
}
//get sample sample test results
 $routcome=GetResultType($outcome);;
$datereceived4=date("d-m-Y",strtotime($sdrec));
if  (($datecollected !="") OR ($datecollected !="0000-00-00"))
	{
	
	$datecollected4=date("d-m-Y",strtotime($datecollected));

	}
$tot = round((strtotime($datereceived4) - strtotime($datecollected4)) / (60 * 60 * 24));	
}
else
{
//get sample recevied
		$srecstatus=GetReceivedStatus($receivedstatus);
$datetested=$srecstatus;
$routcome=$rejectedreason;
$daterejdispatched=GetDateDispatchedforRejectedSample($ID);
if (($daterejdispatched != "" ) || ($daterejdispatched != "0000-00-00"))
{
$datedispatched=date("d-M-Y",strtotime($daterejdispatched));
}
else
{
$datedispatched="";
}

}
	
//date collcted
		
		//get patient gender
		$pgender=GetPatientGender($patient);
		//patietn age
		$pAge=GetPatientAge($patient);
		//patient dob
		$pdob=GetPatientDOB($patient);
		//infant prophylaxis
		$pprophylaxis=GetPatientProphylaxis($patient);
		$pprophylaxisID=GetIDfromtableandname($pprophylaxis,"prophylaxis"); 
		//get sample sample test results
		$routcome = GetSampleResult($ID);
		//get sample recevied
		$srecstatus=GetReceivedStatus($receivedstatus);
		//get mother id from patient 
		$mother=GetMotherID($patient);
		//mother hiv
		$mhiv=GetMotherHIVstatus($mother);
		$mhivID=GetIDfromtableandname($mhiv,"results"); //th resuls 1-negative 2-positive
		//mother pmtct intervention
		$mprophylaxis=GetMotherProphylaxis($mother);
		$mprophylaxisID=GetIDfromtableandname($mprophylaxis,"prophylaxis"); 
		//get mothers feeding type
		$mfeeding=GetMotherFeeding($mother);
		$mfeedingID=GetIDfromtableandname($mfeeding,"feedings"); 
		//get entry point
		$entry=GetEntryPoint($mother);
		$entryID=GetIDfromtableandname($entry,"entry_points"); 
		$count=$count+1;
$pdf->SetFillColor(226,231,245);			
$start_row = $start_row_init;
$pdf->Cell($offset);
	$pdf->Cell(10, 6, $count, 1, 0, 'L', 0);
	$pdf->Cell(55, 6, $patient , 1, 0, 'L', 0);
	$pdf->Cell(15, 6, $pgender, 1, 0, 'C', 0);
	$pdf->Cell(25, 6, $pAge, 1, 0, 'C', 0);
	$pdf->Cell(30, 6, $pprophylaxisID, 1, 0, 'C', 0);
	$pdf->Cell(35, 6, $sdoc, 1, 0, 'C', 0);
	$pdf->Cell(35, 6, $sdrec, 1, 0, 'C', 0);
	$pdf->Cell(25, 6, $srecstatus, 1, 0, 'L', 0);
	$pdf->Cell(25, 6, $testtype, 1, 0, 'C', 0);
	$pdf->Cell(25, 6, $mhiv, 1, 0, 'L', 0);
	$pdf->Cell(35, 6, $mprophylaxisID, 1, 0, 'C', 0);
	$pdf->Cell(25, 6, $mfeedingID, 1, 0, 'C', 0);
	$pdf->Cell(25, 6, $entryID, 1, 0, 'C', 0);
	$pdf->Cell(35, 6, $datetested, 1, 0, 'C', 0);
	$pdf->Cell(35, 6, $datedispatched, 1, 0, 'C', 0);
	$pdf->Cell(25, 6, $routcome, 1, 0, 'C', 0);
$pdf->Cell(20, 6, $tot, 1, 0, 'C', 0);
	
$pdf->Ln();
}
$pdf->Ln(5);
//Create file
$batchno="Test Results Summary for ". $facilityname ." Received on ".$sdrec ." Batch No. ".$batchno.".pdf";
$F="I";
$pdf->Output($batchno,$F);?>