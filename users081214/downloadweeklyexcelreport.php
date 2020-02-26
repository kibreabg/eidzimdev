<?php
session_start();
//require('./pdflibrary7.php');
	include("../connection/config.php");
	include('../includes/functions.php');
	
	function export_excel_csv()
{
  $startdate=$_GET['startdate'];
	$enddate=$_GET['enddate'];
	$labss = $_SESSION['lab'];
	$dislaystartdate=date("d-M-Y",strtotime($startdate));  //formatted date in DAY-MONTH-YEAR FOR DISPLAY
	$dislayenddate=date("d-M-Y",strtotime($enddate));

	//echo $startdate . "-". $enddate . " - ".$province;
	
	
	$dislaystartdate=date("d-M-Y",strtotime($startdate));  //formatted date in DAY-MONTH-YEAR FOR DISPLAY
	$dislayenddate=date("d-M-Y",strtotime($enddate));


$reportname="WeeklyReport";

$d=$reportname.".xls";
    $sql = "SELECT f.name as 'Facility',s.ID as 'Lab ID',s.patient,s.batchno,s.receivedstatus,s.spots,s.datecollected,s.datereceived,s.datetested,s.datemodified,s.datedispatched,s.result,s.worksheet FROM samples s, facilitys f WHERE s.datereceived BETWEEN '$startdate' AND '$enddate' AND s.flag = 1 AND s.facility = f.ID AND f.lab = '$labss'  ORDER BY s.datereceived DESC";
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