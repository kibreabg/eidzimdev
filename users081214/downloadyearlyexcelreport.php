<?php
session_start();
//require('./pdflibrary7.php');
	include("../connection/config.php");
	include('../includes/functions.php');
	
	function export_excel_csv()
{
 $year=$_GET['year'];
	$labss = $_SESSION['lab'];


$reportname="YearlyReport";

$d=$reportname.".xls";
    $sql = "SELECT s.ID,s.patient,f.name,s.batchno,rs.name,s.spots,s.datecollected,s.datereceived,s.datetested,s.datemodified,s.datedispatched,r.name,s.worksheet FROM samples s, facilitys f ,results r, receivedstatus rs WHERE YEAR(s.datereceived) = '$year' AND s.flag = 1 AND s.facility = f.ID AND f.lab = '$labss' AND s.repeatt=0 AND s.receivedstatus=rs.ID AND s.result=r.ID ORDER BY s.datereceived DESC";
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