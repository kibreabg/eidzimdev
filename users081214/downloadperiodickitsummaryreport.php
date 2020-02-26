<?php
session_start();
require('./pdflibrary5.php');
require_once('../connection/config.php');
require_once('../includes/functions.php');
	$labss = $_SESSION['lab'];

//get the periodic values
$quarterly = $_GET['quarterly'];
$quarteryear = $_GET['quarteryear'];

if ($quarterly ==1)
{
$quota="JAN-MAR";
}
else if ($quarterly ==2)
{
$quota="APR-JUN";
}
else if ($quarterly ==3)
{
$quota="JUL-SEP";
}
else if ($quarterly ==4)
{
$quota="OCT-DEC";
}

	
	
class PDF extends FPDF
{
//Page header
function Header()
{
$labss = $_SESSION['lab'];

//get the periodic values
$quarterly = $_GET['quarterly'];
$quarteryear = $_GET['quarteryear'];

if ($quarterly ==1)
{
$quota="JAN-MAR";
}
else if ($quarterly ==2)
{
$quota="APR-JUN";
}
else if ($quarterly ==3)
{
$quota="JUL-SEP";
}
else if ($quarterly ==4)
{
$quota="OCT-DEC";
}
	$this->SetFont('Arial','B',14);			// Arial bold 15
		
			// set parameters
					$this->Cell($offset2);
$this->Image('../img/kemrilogo.jpg',140,9);	// Logo

	$this->Ln(39);	
		

		$offsett=74;
			$this->Cell($offsett);
			
 	//$this->SetTextColor(204,204,204);

			$this->Cell(140,10,sprintf("SUMMARY OF KITS USED IN QUARTER ". strtoupper($quarterly)." - ".$quota." ,".$quarteryear),0,0,'C');	// Title
	$offset 	=3;		// set parameters

	
		//$this->SetTextColor(204,204,204);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('Times','B',10);
		//Header
		$this->Ln(25);
				

		
	$this->SetFont('Arial','B',30);
	$this->SetTextColor(204,204,204);//KENYA MEDICAL RESEARCH INSTITUTE
	$this->RotatedText(125,98,'KEMRI',45);


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
$WEEKLY= periodickitsused($labss,$quarterly,$quarteryear);
$TESTS=totalperiodictests($labss,$quarterly,$quarteryear);
$wasted=periodickitswasted($labss,$weekstartdate,$weekenddate);
$start_row = $start_row_init;
	$pdf->SetY($start_row);
	$pdf->SetFont('Times','',14);	

	$pdf->Ln(25);


	$pdf->Cell($offset);
	$pdf->Cell(70,6  ,' ',1,0);
		$pdf->Cell(80,6, 'Kits Used',1,0);
		$pdf->Cell(80,6, 'Kits Wasted',1,1);
				


				
$pdf->Cell($offset);
	$pdf->Cell(70,6 ,'No of Kits ',1,0);
	$pdf->Cell(80,6 ,$WEEKLY,1,0);
	$pdf->Cell(80,6 ,$wasted,1,1);
	
$pdf->Cell($offset);
	$pdf->Cell(70,6 ,'No of Tests  ',1,0);
	$pdf->Cell(80,6 ,$TESTS,1,0);
	$pdf->Cell(80,6 ,' - ',1,1);
	
	$pdf->Cell($offset);
	$pdf->Cell(70,6 ,'Kit ',1,0);
	$pdf->Cell(80,6 ,'Worksheet / Template No',1,0);
	$pdf->Cell(80,6 ,'',1,1);

 if ($quarterly == 1 ) //january - March
		{
		$startmonth=1;
		$endmonth=3;
		}
		else if ($quarterly == 2 )
		{
		$startmonth=4;
		$endmonth=6;
		}
		else if ($quarterly == 3 )
		{
		$startmonth=7;
		$endmonth=9;
		}
		else if ($quarterly == 4 )
		{
		$startmonth=10;
		$endmonth=12;
		}
  $kitsquery = "SELECT DISTINCT HIQCAPNo  FROM worksheets WHERE  lab='$labss'AND MONTH(daterun) BETWEEN '$startmonth' AND '$endmonth' AND YEAR(daterun)=  '$quarteryear' ";
		$kitsresult = mysql_query($kitsquery) or die(mysql_error());

 while(list($HIQCAPNo) = mysql_fetch_array($kitsresult))
  {$pdf->Cell($offset);
  $pdf->Cell(70,6 , $HIQCAPNo  ,1,0);
     	
		$sql="SELECT ID  FROM worksheets WHERE  lab='$labss' AND HIQCAPNo= '$HIQCAPNo'";

		$res=@mysql_query($sql) or die ("Sorry, it did not work!");
		
		$totalnos= mysql_num_rows($res);
		if ($totalnos !=2)
		{
			   $worksheetquery = "SELECT ID  FROM worksheets WHERE  lab='$labss' AND HIQCAPNo= '$HIQCAPNo' ORDER BY ID DESC limit 0,1";
		$worksheetresult = mysql_query($worksheetquery) or die(mysql_error());
		$worksheetarray= mysql_fetch_array($worksheetresult);
		$w1=$worksheetarray['ID'];
		$wsheets= $w1;
		}
		else
		{
		   $worksheetquery8 = "SELECT ID  FROM worksheets WHERE  lab='$labss' AND HIQCAPNo= '$HIQCAPNo' ORDER BY ID DESC limit 0,1";
		$worksheetresult8 = mysql_query($worksheetquery8) or die(mysql_error());
		$worksheetarray8= mysql_fetch_array($worksheetresult8);
		$w1=$worksheetarray8['ID'];
		
		 $worksheetquery0 = "SELECT ID  FROM worksheets WHERE  lab='$labss' AND HIQCAPNo= '$HIQCAPNo' ORDER BY ID ASC limit 0,1";
		$worksheetresult0 = mysql_query($worksheetquery0) or die(mysql_error());
		$worksheetarray0= mysql_fetch_array($worksheetresult0);
		$w2=$worksheetarray0['ID'];

		$wsheets= $w1 ." , ". $w2;
		}
		
	
	$pdf->Cell(80,6 ,$wsheets,1,0);
	$pdf->Cell(80,6 ,'',1,1);
	
}
/* $kitsquery = "SELECT DISTINCT HIQCAPNo  FROM kits_wasted WHERE  lab='$labss' AND daterun BETWEEN '$weekstartdate' AND '$weekenddate' ";
		$kitsresult = mysql_query($kitsquery) or die(mysql_error());

 while(list($HIQCAPNo) = mysql_fetch_array($kitsresult))
  {
	$pdf->Cell(80,6 ,'TYYT',1,1);
	
	}*/
	
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

$title= " SUMMARY OF KITS USED IN QUARTER  ".strtoupper($quarterly)." - ".$quota." ,".$quarteryear.".pdf";
$F="I";
$pdf->Output($title,$F);?>
?>