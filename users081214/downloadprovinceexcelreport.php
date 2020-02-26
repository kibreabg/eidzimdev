<?php
session_start();
//require('./pdflibrary7.php');
	include("../connection/config.php");
	include('../includes/functions.php');
	
	function export_excel_csv()
{
   $labss=$_SESSION['lab'];
	$startdate=$_GET['startdate'];
	$enddate=$_GET['enddate'];
	$province=$_GET['province'];
	//echo $startdate . "-". $enddate . " - ".$province;
	
	
	$dislaystartdate=date("d-M-Y",strtotime($startdate));  //formatted date in DAY-MONTH-YEAR FOR DISPLAY
	$dislayenddate=date("d-M-Y",strtotime($enddate));


$reportname="REPORTFORSAMPLESRECEIVEDBETWEEN".$dislaystartdate . "&".$dislayenddate;

$d=$reportname.".xls";
    $sql = "SELECT f.name as ' Facility ',s.ID as 'Lab ID',s.patient as 'Patient',p.gender as 'Gender',p.age as 'Age (months)',pr.name as 'Infant Prophylaxis',s.batchno as 'Batch No',s.datecollected as ' Date Collected',s.spots as ' Spots',s.datereceived as 'Date Received',rs.name as 'Received Status',s.datetested as 'Date Tested',s.datemodified as 'Date Results Updated',s.datedispatched as 'Date Dispatched',r.name as 'Result',s.worksheet as 'Worksheet' FROM samples s, facilitys f,districts d ,receivedstatus rs, results r , patients p ,prophylaxis pr WHERE s.facility = f.ID AND f.district = d.id AND d.province = '$province' AND s.datereceived  BETWEEN '$startdate' AND '$enddate' AND s.flag = 1 AND f.lab = '$labss' AND s.receivedstatus=rs.ID AND s.result=r.ID AND s.patient=p.ID AND p.prophylaxis=pr.ID ORDER BY s.datereceived ASC ";
    $rec = mysql_query($sql) or die (mysql_error());
   
    $num_fields = mysql_num_fields($rec);
	

    for($i = 0; $i < $num_fields; $i++ )
    {
       $header .= mysql_field_name($rec,$i)."\t";
    }
   
    while($row = mysql_fetch_row($rec))
    {
		
        $line = '';
        foreach($row as $value)
        {                                           
            if((!isset($value)) || ($value == ""))
            {
                $value = "\t";
            }
            else
            {
                $value = str_replace( '"' , '""' , $value );
                $value = '"' . $value . '"' . "\t";
            }
            $line .= $value;
        }
        $data .= trim( $line ) . "\n";
    }
   
    $data = str_replace("\r" , "" , $data);
   
    if ($data == "")
    {
        $data = "\n No Record Found!n";                       
    }
   
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$d");
    header("Pragma: no-cache");
    header("Expires: 0");
    print "$header\n$data";
}

$d=export_excel_csv();

	
	?>