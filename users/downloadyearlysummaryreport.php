<?php
session_start();
require('./pdflibrary5.php');
require_once('../connection/config.php');
require_once('../includes/functions.php');
	//get the periodic values
$yearly = $_GET['year'];


$labss = $_SESSION['lab'];
$rejected=yearlyrejectedsamples($labss,$yearly);
	$total= totalyearlytests($labss,$yearly);
	$positives=yearlytestsbyresult($labss,$yearly,2);
	if ($positives !=0)
	{
	$pospercentage= round((($positives/$total)*100),1);
	}
	else
	{
	$pospercentage=0;
	}
	$negatives=yearlytestsbyresult($labss,$yearly,1);
	if ($negatives !=0)
	{
	$negpercentage= round((($negatives/$total)*100),1);
	}
	else
	{
	$negpercentage=0;
	}
	$failed=yearlytestsbyresult($labss,$yearly,3);
	if ($failed !=0)
	{
	$failpercentage= round((($failed/$total)*100),1);
	}
	else
	{
	$failpercentage=0;
	}
	$totalpercentage=$failpercentage+$negpercentage+$pospercentage;
	
class PDF extends FPDF
{
//Page header
function Header()
{
$yearly = $_GET['year'];

	$this->SetFont('Arial','B',14);			// Arial bold 15
		
			// set parameters
					$this->Cell($offset2);
$this->Image('../img/kemrilogo.jpg',140,9);	// Logo

	$this->Ln(39);	
		

		$offsett=74;
			$this->Cell($offsett);
			
 	//$this->SetTextColor(204,204,204);

			$this->Cell(140,10,sprintf("SUMMARY OF TESTS DONE IN ". $yearly),0,0,'C');	// Title
	$offset 	=3;		// set parameters

	
		//$this->SetTextColor(204,204,204);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('Times','B',10);
		//Header
		$this->Ln(25);
				

		
	$this->SetFont('Arial','B',30);
	$this->SetTextColor(204,204,204);//KENYA MEDICAL RESEARCH INSTITUTE
	$this->RotatedText(125,98,'NMRL',45);


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
//Page footer
function Footer()
{
	$this->SetY(-15);			// Position at 1.5 cm from bottom
	$this->SetFont('Arial','I',8);		// Arial italic 8
		$offsett=100;
			$this->Cell($offsett);
		
		$this->Cell(100,10,'© 2010 NASCOP ',0,0, 'C');


}
}

//Instanciation of inherited class
$pdf = new PDF();
//$pdf=new PDF_ImageAlpha();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',14);

$offset 	= 20;		// set parameters
$img_offset 	= 20;
$img_width 	= 35;
$start_row_init	= 40;
$cell_width 	= 0;
$line_height 	= 6;
$spacing 	= 6;		// spacing between items

$start_row = $start_row_init;
	$pdf->SetY($start_row);
	$pdf->SetFont('Times','',14);	

	$pdf->Ln(25);


	$pdf->Cell($offset);
	$pdf->Cell(70,6  ,' ',1,0);
		$pdf->Cell(80,6, 'Number of Tests',1,0);
		$pdf->Cell(80,6 ,'% ' ,1,1);
				


				
$pdf->Cell($offset);
	$pdf->Cell(70,6 ,'Positives ',1,0);
	$pdf->Cell(80,6 ,$positives,1,0);
	$pdf->Cell(80,6 ,$pospercentage,1,1);
	
$pdf->Cell($offset);
	$pdf->Cell(70,6 ,'Negatives ',1,0);
	$pdf->Cell(80,6 ,$negatives,1,0);
	$pdf->Cell(80,6 ,$negpercentage,1,1);
	
	$pdf->Cell($offset);
	$pdf->Cell(70,6 ,'Failed ',1,0);
	$pdf->Cell(80,6 ,$failed,1,0);
	$pdf->Cell(80,6 ,$failpercentage,1,1);
$pdf->Cell($offset);

	$pdf->Cell(70,6 ,'Rejected ',1,0);
	$pdf->Cell(80,6 ,$rejected,1,0);
	$pdf->Cell(80,6 ,' - ',1,1);
	$pdf->Cell($offset);
	
	$pdf->Cell(70,6 ,'Total Tests ',1,0);
	$pdf->Cell(80,6 ,$total,1,0);
	$pdf->Cell(80,6 ,$totalpercentage,1,1);
$pdf->Cell($offset);
	
	
	
		$pdf->Cell($offset);

	
	
	
	
	
	
	
	
			
		

	
	$current_row = $pdf->GetY();
	$row_dif = ($current_row - $start_row);

	// choose greatest
	if ($current_row > $start_row + $img_height)
		$start_row = $current_row;
	else
		$start_row = $start_row + $img_height;
	// add spacing between rows
	$start_row = $start_row + $spacing;
//} while ($row_Products = mysql_fetch_assoc($Products)); 

$title= $yearly ." TESTS SUMMARY REPORT ".".pdf";
$F="I";
$pdf->Output($title,$F);?>
?>