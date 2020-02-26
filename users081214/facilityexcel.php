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
	$fcode=$_GET['fcode'];
$fname=$_GET['fname'];
$currentmonth=$_GET['currentmonth'];

$currentyear=$_GET['currentyear'];
$province=$_GET['province'];
$district=$_GET['district'];
	
	
$reportname="FacilityDrillDown";

$d=$reportname.".xls";
if ($currentmonth !="")
{
 $sql = "SELECT ID,patient,datereceived,spots,datecollected,receivedstatus
            FROM samples
			WHERE facility='$fcode' AND MONTH(datereceived)='$currentmonth' AND YEAR(datereceived)='$currentyear'
			ORDER BY ID DESC
			";
			  $rec = mysql_query($sql) or die (mysql_error());
}
else
{
$sql = "SELECT ID,patient,datereceived,spots,datecollected,receivedstatus
            FROM samples
			WHERE facility='$fcode'  AND YEAR(datereceived)='$currentyear'
			ORDER BY ID DESC
			";
			  $rec = mysql_query($sql) or die (mysql_error());
}
   
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