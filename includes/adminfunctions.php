<?php
/*save facility details
function Savefacility($code,$name,$type,$district,$lab,$postal,$telephone,$otelephone,$fax,$email,$fullname,$contacttelephone,$ocontacttelephone)
{
$saved = "INSERT INTO 		
facilitys(facilitycode,name,ftype,district,lab,physicaladdress,telephone,telephone2,fax,email,contactperson,contacttelephone,contacttelephone2,flag)VALUES('$code','$name','$type','$district','lab','$postal','$telephone','$otelephone','$fax','$email','$fullname','$contacttelephone','$ocontacttelephone',1)";
			$users = @mysql_query($saved) or die(mysql_error());
	return $users;
}
//save user details
function SaveUser($surname,$oname,$telephone,$postal,$email,$account,$username,$password)
{
$saved = "INSERT INTO 		
users(surname,oname,telephone,postal,email,account,username,password,flag)VALUES('$surname','$oname','$telephone','$postal','$email','$account','$username','$password',1)";
			$users = @mysql_query($saved) or die(mysql_error());
	return $users;
}
//save district details
function Savedistrict($name,$province,$comment)
{
$savedistrict = "INSERT INTO 		
districts(name,province,comment,flag)VALUES('$name','$province','$comment',1)";
			$districts = @mysql_query($savedistrict) or die(mysql_error());
	return $districts;

}
//get province name
function GetProvince($province)
{
	$province = "SELECT name FROM provinces WHERE ID = '$province'";
		$getprovince = mysql_query($province) or die(mysql_error());
		$provincename = mysql_fetch_array($getprovince);
		$showprovince = $provincename['name'];
	
return $showprovince;
}
//get facility type
function GetFacilityType($ftype)
{
	$ftype = "SELECT name FROM facilitytype WHERE ID = '$ftype'";
		$gettype = mysql_query($ftype) or die(mysql_error());
		$facilitytype = mysql_fetch_array($gettype);
		$showfacility = $facilitytype['name'];
	
return $showfacility;
}
//get facility type no
function GetFacilityTypeNo($facility)
{
	$getfacilitytype = "SELECT ftype FROM facilitys WHERE ID = '$facility'";
		$getfacilityno = mysql_query($getfacilitytype) or die(mysql_error());
		$facilityno = mysql_fetch_array($getfacilityno);
		$showfacilityno = $facilityno['ftype'];
	
return $showfacilityno;
}
//get facility type
function GetFacility($getfacilitytypeno)
{
	$getfacilityname = "SELECT name FROM facilitytype WHERE ID = '$getfacilitytypeno'";
		$getfacility = mysql_query($getfacilityname) or die(mysql_error());
		$facility = mysql_fetch_array($getfacility);
		$showfacility = $facility['name'];
	
return $showfacility;
}
//get facility name
function GetFacilityName($facility)
{
	$getfname = "SELECT name FROM facilitys WHERE ID = '$facility'";
		$getf = mysql_query($getfname) or die(mysql_error());
		$f = mysql_fetch_array($getf);
		$showf = $f['name'];
	
return $showf;
}
//get user account type
function GetAccountType($account)
{
	$account = "SELECT name FROM usergroups WHERE ID = '$account'";
		$getaccount = mysql_query($account) or die(mysql_error());
		$accounttype = mysql_fetch_array($getaccount);
		$showaccount = $accounttype['name'];
	
return $showaccount;
}
//get total number of districts
function GetTotalNoDistricts()
{
		$query = "SELECT * FROM districts where flag = 1";
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);
return $numrows;
}
//get sample received status
function GetReceivedStatus($receivedstatus)
{
	$status = "SELECT name FROM receivedstatus WHERE ID = '$receivedstatus'";
	$getstatus = mysql_query($status) or die(mysql_error());
	$statustype = mysql_fetch_array($getstatus);
	$showstatus = $statustype['name'];
	
return $showstatus;
}
//get sample result
function GetResultType($result)
{
	$result = "SELECT name FROM results WHERE ID = '$result'";
	$getresult = mysql_query($result) or die(mysql_error());
	$resulttype = mysql_fetch_array($getresult);
	$showresult = $resulttype['name'];
	
return $showresult;
}*/
//save requisition
function SaveRequisition($fcode,$dbs,$ziploc,$dessicants,$rack,$glycline,$humidity,$lancets,$comments,$date)
{
	$saved = "INSERT INTO 		
requisitions(facility,dbs,ziploc,dessicants,rack,glycline,humidity,lancets,comments,datecreated,flag)VALUES('$fcode','$dbs','$ziploc','$dessicants','$rack','$glycline','$humidity','$lancets','$comments','$date',1)";
	$requisitions = mysql_query($saved) or die(mysql_error());
	return $requisitions;
}
//get any date
function GetAnyDateMin()
{
	$getanydate = "SELECT YEAR(MIN(datereceived)) AS lowdate FROM samples WHERE flag=1 AND datereceived > 0";
	$anydate = mysql_query($getanydate) or die(mysql_error());
	$dateresult = mysql_fetch_array($anydate);
	$showdate = $dateresult['lowdate'];
	
return $showdate;
}
function GetReqMin()
{
	$getanydate = "SELECT YEAR(MIN(datecreated)) AS lowdate FROM requisitions WHERE flag=1 AND datecreated > 0";
	$anydate = mysql_query($getanydate) or die(mysql_error());
	$dateresult = mysql_fetch_array($anydate);
	$showdate = $dateresult['lowdate'];
	
return $showdate;
}
//get requisition details
function GetRequisitionInfo($db)
{
	$req = "SELECT dbs,dessicants,glycline,lancets,ziploc,rack,humidity,comments,datecreated FROM requisitions WHERE ID = '$db'";
$getreq = mysql_query($req) or die(mysql_error());
$requisition = mysql_fetch_array($getreq);
	
return $requisition;
}
//update requisition details
function UpdateRequisition($db,$edbs,$eziploc,$edessicants,$erack,$eglycline,$ehumidity,$elancets,$ecomments,$datemodified)
{
	$req = "UPDATE requisitions SET dbs='$edbs',ziploc='$eziploc',dessicants='$edessicants',rack='$erack',glycline='$eglycline',humidity='$ehumidity',lancets='$elancets',comments='$ecomments',datemodified='$datemodified' WHERE id = '$db'";
$getreq = mysql_query($req) or die(mysql_error());
	
return $getreq;
}
//delete requisition details
function DeleteRequisition($db,$datemodified)
{
	$delreq = "UPDATE 		
requisitions SET flag=0, datemodified='$datemodified' WHERE id = '$db'";
$deletedreq = mysql_query($delreq) or die(mysql_error());
//$requisition = mysql_fetch_array($getreq);
	
return $deletedreq;
}
?>
