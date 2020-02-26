<?php
session_start();
$userid=$_SESSION['uid'] ; //id of user who is updatin th record
require_once('../connection/config.php');
require_once('../includes/functions.php');

$db=$_GET['db'];
$originalreq=getRequisitionDetails($db);
extract($originalreq);
$provname=$_GET['r'];
$distname=$_GET['z'];
$fcode=  $_GET['cat'];
$province = $_GET['province'];
$district = $_GET['district'];


//get requisition details
$edbs = $_GET['edbs'];
$eziploc = $_GET['eziploc'];
$edessicants = $_GET['edessicants'];
$erack = $_GET['erack'];
$eglycline = $_GET['eglycline'];
$ehumidity = $_GET['ehumidity'];
$elancets = $_GET['elancets'];
$ereqform = $_GET['ereqform'];
$ecomments = $_GET['ecomments'];
$disapprovecomments= $_GET['disapprovecomments'];
$approvecomments= $_GET['approvecomments'];
$datecreated = $_GET['datecreated'];

$requisitiondate = $_GET['requisitiondate'];

//change the date formats
$datecreated=date('Y-m-d',strtotime($datecreated));
$requisitiondate =date('Y-m-d',strtotime($requisitiondate ));

//get today's date
$datemodified = date('Y-m-d');

$requisitions = SaveRequisition($fcode,$edbs,$eziploc,$edessicants,$erack,$eglycline,$ehumidity,$elancets,$ereqform,$comments,$datecreated,$db,$disapprovecomments,$ecomments,$requisitiondate,$datemodified);//call the save function
$lastrno=GetLastRequisitionID();
if ($requisitions) //check if all records entered
{	 //update original requsition staus to complete
			$updatestatus = mysql_query("UPDATE requisitions
              SET status = 1
			  			   WHERE (id = '$db')");
			//update newly crreated requisition
			$updatestatus = mysql_query("UPDATE requisitions
              SET status = 1
			  			   WHERE (parentid = '$db')");
			
			$updatestatus = mysql_query("UPDATE requisitions
              SET status = 1
			  			   WHERE (id = '$lastrno')");
			$query = "SELECT email,ContactEmail  FROM  facilitys WHERE ID='$fcode'";
$result = mysql_query($query) or die('Error, query failed');
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$email = $row['email'];
$ContactEmail = $row['ContactEmail'];

 	$facilityname = GetFacility($fcode);
 	$districtidquery=mysql_query("SELECT district FROM facilitys WHERE  ID='$fcode'"); 
							$noticia = mysql_fetch_array($districtidquery);  
							$distid=$noticia['district'];
							//get select district name and province id	
							$districtnamequery=mysql_query("SELECT province,name FROM districts WHERE  ID='$distid'"); 
							$districtname = mysql_fetch_array($districtnamequery);  
							$provid=$districtname['province'];
							$distname=$districtname['name'];
							//get province name	
							$provincenamequery=mysql_query("SELECT name FROM provinces WHERE  ID='$provid'"); 
							$provincename = mysql_fetch_array($provincenamequery);  
							$provname=$provincename['name'];
							

	if ($email !="" && $ContactEmail !="" )
	{
	$site_name="EID";
	//$site_url="http://localhost/HostelProject/cancel.php";
  	$site_email="eid-nairobi@googlegroups.com";
	$subject = "Approved Requisition";
	$message = "
	<html>
<head>
<title>HTML email</title>
</head>
<body>

	
	Hello $facilityname; <br>

Please find below the approved requisition details that was made on $datecreated; <br>-------------------------------------------------------------------------------------------------------------------
	<table  class='data-table'>
            <tr>
              <td width='127' >Facility</td>
              <td colspan='4'>
			  
		
			$facilityname;
					
		</td>
            </tr>
            <tr>
              <td>Province</td>
              <td colspan='3'><strong>$provname</strong></td>
            </tr>
            <tr>
              <td>District</td>
              <td colspan='3'><strong> $distname</strong></td>
            </tr>
        </table>
 <table class='data-table'>
            <tr>
              <td class='subsection-title' colspan='3'>Consumables Requested </td>
			  <td colspan='3' ><small><strong>Date Created</strong>&nbsp;&nbsp;$datecreated</small></td>
            </tr>
            <tr>
              <td width='125'>DBS Filter Paper </td>
              <td width='45'><strong>$dbs</strong></td>
			  <td><strong>$edbs</strong></td>
              <td width='135'>Ziploc Bags</td>
              <td width='45'><strong> $ziploc</strong></td>
			  <td> $eziploc</td>
            </tr>
            <tr>
              <td>Dessicants</td>
              <td><strong> $dessicants</strong></td>
			  <td> $edessicants</td>
              <td>Drying Racks</td>
              <td><strong> $rack</strong></td>
			  <td>$erack</td>
            </tr>
            <tr>
              <td>Glycline Envelopes </td>
              <td><strong>$glycline</strong></td>
			  <td> $eglycline</td>
              <td>Humidity Indicators</td>
              <td><strong> $humidity</strong></td>
			  <td> $ehumidity</td>
            </tr>
            <tr>
              <td>Lancets</td>
              <td><strong> $lancets</strong></td>
			  <td colspan='4'> $elancets</td>
            </tr>
            <tr>
              <td > Comments </td>
              <td colspan='5'><strong>$comments</strong></td>
            </tr>
            <tr>
			 <td > Approval Comments </td>
			  <td colspan='5'>$ecomments</td>
            </tr>
			
          </table>

------------------------------------------------------------------------------------------------ <br>
 <br>



--
-Thanks<br>
$site_name<br>

</body>
</html>";
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

// More headers
$headers .= 'From: <eid-nairobi@googlegroups.com>' . "\r\n";

 $sent=mail($email,$subject,$message, $headers); //FACILITY EMAIL
  $sent2=mail($ContactEmail,$subject,$message, $headers);//CONTACT PERSON
  if ($sent && $sent2)
 {
	$st="Material requisition for ".$facilityname." have been approved. An Email with the approval details has been sent to the Facility Email and  Contact Person Email.";
				//header("location:requisitionslist.php?p=$st"); //direct to list view
				echo '<script type="text/javascript">' ;
				echo "window.location.href='requisitionslist.php?approvesuccess=$st'";
				echo '</script>';//do nothing
				}
	}
	else if ($email =="" && $ContactEmail !="")
	{
	$site_name="EID";
	//$site_url="http://localhost/HostelProject/cancel.php";
  	$site_email="eid-nairobi@googlegroups.com";
	$subject = "Approved Requisition";
	$message = "
	<html>
<head>
<title>HTML email</title>
</head>
<body>

	
	Hello $facilityname; <br>

Please find below the approved requisition details that was made on $datecreated; <br>-------------------------------------------------------------------------------------------------------------------
	<table  class='data-table'>
            <tr>
              <td width='127' >Facility</td>
              <td colspan='4'>
			  
		
			$facilityname;
					
		</td>
            </tr>
            <tr>
              <td>Province</td>
              <td colspan='3'><strong>$provname</strong></td>
            </tr>
            <tr>
              <td>District</td>
              <td colspan='3'><strong> $distname</strong></td>
            </tr>
        </table>
 <table class='data-table'>
            <tr>
              <td class='subsection-title' colspan='3'>Consumables Requested </td>
			  <td colspan='3' ><small><strong>Date Created</strong>&nbsp;&nbsp;$datecreated</small></td>
            </tr>
            <tr>
              <td width='125'>DBS Filter Paper </td>
              <td width='45'><strong>$dbs</strong></td>
			  <td><strong>$edbs</strong></td>
              <td width='135'>Ziploc Bags</td>
              <td width='45'><strong> $ziploc</strong></td>
			  <td> $eziploc</td>
            </tr>
            <tr>
              <td>Dessicants</td>
              <td><strong> $dessicants</strong></td>
			  <td> $edessicants</td>
              <td>Drying Racks</td>
              <td><strong> $rack</strong></td>
			  <td>$erack</td>
            </tr>
            <tr>
              <td>Glycline Envelopes </td>
              <td><strong>$glycline</strong></td>
			  <td> $eglycline</td>
              <td>Humidity Indicators</td>
              <td><strong> $humidity</strong></td>
			  <td> $ehumidity</td>
            </tr>
            <tr>
              <td>Lancets</td>
              <td><strong> $lancets</strong></td>
			  <td colspan='4'> $elancets</td>
            </tr>
            <tr>
              <td > Comments </td>
              <td colspan='5'><strong>$comments</strong></td>
            </tr>
            <tr>
			 <td > Approval Comments </td>
			  <td colspan='5'>$ecomments</td>
            </tr>
			
          </table>

------------------------------------------------------------------------------------------------ <br>
 <br>



--
-Thanks<br>
$site_name<br>

</body>
</html>";
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

// More headers
$headers .= 'From: <eid-nairobi@googlegroups.com>' . "\r\n";


 $sent=mail($ContactEmail,$subject,$message, $headers);//CONTACT PERSON
  if ($sent)
 {
	$st="Material requisition for ".$facilityname." have been approved. An Email with the approval details has been sent to the Facility Contact Person Email.";
				//header("location:requisitionslist.php?p=$st"); //direct to list view
				echo '<script type="text/javascript">' ;
				echo "window.location.href='requisitionslist.php?approvesuccess=$st'";
				echo '</script>';//do nothing
				}

	}
	else if ($email !="" && $ContactEmail =="")
	{
	$site_name="EID";
	//$site_url="http://localhost/HostelProject/cancel.php";
  	$site_email="eid-nairobi@googlegroups.com";
	$subject = "Approved Requisition";
	$message = "
	<html>
<head>
<title>HTML email</title>
</head>
<body>

	
	Hello $facilityname; <br>

Please find below the approved requisition details that was made on $datecreated; <br>-------------------------------------------------------------------------------------------------------------------
	<table  class='data-table'>
            <tr>
              <td width='127' >Facility</td>
              <td colspan='4'>
			  
		
			$facilityname;
					
		</td>
            </tr>
            <tr>
              <td>Province</td>
              <td colspan='3'><strong>$provname</strong></td>
            </tr>
            <tr>
              <td>District</td>
              <td colspan='3'><strong> $distname</strong></td>
            </tr>
        </table>
  <table class='data-table'>
            <tr>
              <td class='subsection-title' colspan='3'>Consumables Requested </td>
			  <td colspan='3' ><small><strong>Date Created</strong>&nbsp;&nbsp;$datecreated</small></td>
            </tr>
            <tr>
              <td width='125'>DBS Filter Paper </td>
              <td width='45'><strong>$dbs</strong></td>
			  <td><strong>$edbs</strong></td>
              <td width='135'>Ziploc Bags</td>
              <td width='45'><strong> $ziploc</strong></td>
			  <td> $eziploc</td>
            </tr>
            <tr>
              <td>Dessicants</td>
              <td><strong> $dessicants</strong></td>
			  <td> $edessicants</td>
              <td>Drying Racks</td>
              <td><strong> $rack</strong></td>
			  <td>$erack</td>
            </tr>
            <tr>
              <td>Glycline Envelopes </td>
              <td><strong>$glycline</strong></td>
			  <td> $eglycline</td>
              <td>Humidity Indicators</td>
              <td><strong> $humidity</strong></td>
			  <td> $ehumidity</td>
            </tr>
            <tr>
              <td>Lancets</td>
              <td><strong> $lancets</strong></td>
			  <td colspan='4'> $elancets</td>
            </tr>
            <tr>
              <td > Comments </td>
              <td colspan='5'><strong>$comments</strong></td>
            </tr>
            <tr>
			 <td > Approval Comments </td>
			  <td colspan='5'>$ecomments</td>
            </tr>
			
          </table>

------------------------------------------------------------------------------------------------ <br>
 <br>



--
-Thanks<br>
$site_name<br>

</body>
</html>";
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

// More headers
$headers .= 'From: <eid-nairobi@googlegroups.com>' . "\r\n";

 $sent=mail($email,$subject,$message, $headers); //FACILITY EMAIL
 if ($sent)
 {
	$st="Material requisition for ".$facilityname." have been approved. An Email with the approval details has been sent to the Facility.";
				//header("location:requisitionslist.php?p=$st"); //direct to list view
				echo '<script type="text/javascript">' ;
				echo "window.location.href='requisitionslist.php?approvesuccess=$st'";
				echo '</script>';//do nothing
				}
	}
	else
	{
		$st="Material requisition for ".$facilityname." have been approved.";
				//header("location:requisitionslist.php?p=$st"); //direct to list view
				echo '<script type="text/javascript">' ;
				echo "window.location.href='requisitionslist.php?approvesuccess=$st'";
				echo '</script>';//do nothing

	}
}
else
{
				$error="Approve Requisition Failed, please try again.";
				echo '<script type="text/javascript">' ;
				echo "window.location.href='approverequisition.php?db=$db&error=$error'";
				echo '</script>';//do nothing
		
}





?>