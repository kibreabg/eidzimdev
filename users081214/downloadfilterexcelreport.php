<?php
session_start();
//require('./pdflibrary7.php');
	include("../connection/config.php");
	include('../includes/functions.php');
	
	function export_excel_csv()
{
   /* $conn = mysql_connect("localhost","root","");
    $db = mysql_select_db("eid_kemri2",$conn);*/
    //$today=date("Y-m-d");
	$startdate=$_GET['startdate'];
	$enddate=$_GET['enddate'];
	
	$fcode=$_GET['fcode'];
	
	$dislaystartdate=date("d-M-Y",strtotime($startdate));  //formatted date in DAY-MONTH-YEAR FOR DISPLAY
	$dislayenddate=date("d-M-Y",strtotime($enddate));

$facilityname=GetFacility($fcode);
$reportname="REPORTFORSAMPLESRECEIVEDBETWEEN".$dislaystartdate . "&".$dislayenddate;

$d=$reportname.".xls";
    $sql = "SELECT s.ID as 'Lab ID', s.patient as 'Sample/Infant ID' ,p.age as 'Age Months', p.gender as 'Gender',s.spots as 'Spots',s.datecollected as 'Date Collected',s.datereceived as 'Date Received',s.datetested as 'Date Tested', s.datemodified as 'Date Results Updated', s.datedispatched as 'Date Dispatched' 
        FROM samples s,patients p WHERE s.datetested BETWEEN '$startdate' AND '$enddate' AND s.Flag = 1 AND s.facility='$fcode' AND s.patient=p.ID AND repeatt = 0	
		ORDER BY s.datetested ASC ";
    $rec = mysql_query($sql) or die (mysql_error());
   
    $num_fields = mysql_num_fields($rec);
	 $num_fields4 = mysql_num_rows($rec);
  
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