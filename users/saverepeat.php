<?php 
session_start();
$userid=$_SESSION['uid'] ; //id of user who is updatin th record
require_once('../connection/config.php');
//save worksheet details
	
$worksheetno= $_POST['worksheetno'];
$lotno= $_POST['lotno'];
$hiqcap= $_POST['hiqcap'];
$rackno= $_POST['rackno'];
$spekkitno= $_POST['spekkitno'];
$labcode= $_POST['labcode'];
$sample= $_POST['sample'];
$datecreated =date('d-m-Y');
$kitexp = $_POST['kitexp'];
$datecut = $_REQUEST["datecut"] ? $_REQUEST["datecut"] : "";
$daterun = $_REQUEST["daterun"] ? $_REQUEST["daterun"] : "";
if  ($kitexp == "0000-00-00" )
{
$kitexp="";
}
//save worksheet details
$worksheetdetailsrec ="INSERT INTO 		
worksheets(ID,datecreated,HIQCAPNO,spekkitno,createdby,Lotno,Rackno,type,kitexpirydate,datecut,daterun)VALUES
('$worksheetno','$datecreated ','$hiqcap','$spekkitno','$userid','$lotno','$rackno',1,'$kitexp','$datecut','$daterun')";
			$worksheetdetail = @mysql_query($worksheetdetailsrec) or die(mysql_error());

foreach($labcode as $t => $b)
{

/*echo $labcode[$a] . " | ";
echo $worksheetno;*/
//update sample record
 $samplerec = mysql_query("UPDATE samples
              SET  	inrepeatworksheet  = 1 ,  repeatworksheetno='$worksheetno'
			  			   WHERE (ID = '$labcode[$t]')")or die(mysql_error());
 $taskrec = mysql_query("UPDATE pendingtasks
              SET  	status  = 1 
			  			   WHERE (sample = '$labcode[$t]' AND task=3)")or die(mysql_error());

			
}
if ($worksheetdetail && $samplerec) //check if all records entered
		{
				
				// exit();
				header("location: downloadrepeatworksheet.php?ID=$worksheetno");
				//  exit();
//echo "window.open('downloadrepeatworksheet.php?ID=$worksheetno','_blank')";

		}
		else
		{
				$st="Worksheet Save Failed, try again ";
				header("location: createrepeatsworksheet.php?p=$st");
		
		}

		
		?>
