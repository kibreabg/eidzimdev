<?php
session_start();
//require('./pdflibrary7.php');
	include("../connection/config.php");
	include('../includes/functions.php');
	
	function export_excel_csv()
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


$reportname="MonthlyReport";

$d=$reportname.".xls";
    $sql ="SELECT s.patient as 'Sample/Infant ID',s.facility,s.batchno  as 'Batch No',s.receivedstatus,s.spots as 'Spots',s.datecollected as 'Date Collected',s.datereceived as 'Date Received',s.datetested as 'Date Tested',s.datemodified as 'Date Modified',s.datedispatched as 'Date Dispatched',s.result,s.worksheet as 'Worksheet No' FROM samples s, facilitys f WHERE MONTH(s.datereceived) = $monthly AND YEAR(s.datereceived) = $year AND s.flag = 1 AND s.facility = f.ID AND f.lab = $labss AND s.repeatt=0 ORDER BY s.datereceived DESC";// "SELECT s.ID ,s.patient as 'Sample/Infant ID',s.facility,s.batchno as 'Batch No',rs.name as 'Received Status',s.spots as 'Spots',s.datecollected as 'Date Collected',s.datereceived as 'Date Received',s.datetested as 'Date Tested',s.datemodified as 'Date Modified',s.datedispatched as 'Date Dispatched',r.name as 'Result',s.worksheet as 'Worksheet No' FROM samples s, facilitys f ,results r, receivedstatus rs WHERE MONTH(s.datereceived) = '$monthly' AND YEAR(s.datereceived) = '$year' AND s.flag = 1 AND s.facility = f.ID AND f.lab = '$labss' AND s.repeatt=0 AND s.receivedstatus=rs.ID AND s.result=r.ID ORDER BY s.datereceived DESC ";


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