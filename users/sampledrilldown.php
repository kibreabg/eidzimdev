<?php
include("../connection/config.php");
include("../FusionMaps/FusionMaps.php");
include("../FusionCharts/FusionCharts.php");include("../includes/labdashboardfunctions.php");
include("../includes/functions.php");

$fcode=$_GET['facility'];
$fname=$_GET['fname'];
$currentmonth=$_GET['currentmonth'];
$displaymonth=GetMonthName($currentmonth);//month fullnames
$currentyear=$_GET['currentyear'];
$province=$_GET['province'];
$district=$_GET['district'];
if (isset($currentmonth))
	{
	$defaultmonth=$displaymonth .' - '.$currentyear ; //get current month and year
	}
	else
	{
	$defaultmonth=$currentyear ; //get current month and year

	
	}
?><head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<meta name="description" content=""/>
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
	<title>EID: Early Infant Diagonistics System</title>
</head>

	<link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
<SCRIPT LANGUAGE="Javascript" SRC="../FusionMaps/JSClass/FusionMaps.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="../FusionCharts/FusionCharts.js"></SCRIPT>
<script language="JavaScript" src="../FusionWidgets/FusionCharts.js"></script>
			 <div class="section-title"> Received Samples from  <?php echo $fname; ?> in <?php echo $districtname; ?> District, in <?php echo $provincename; ?> Province,  for <?php echo $defaultmonth; ?></div>

    <?php 
   $rowsPerPage = 15; //number of rows to be displayed per page

// by default we show first page
$pageNum = 1;

// if $_GET['page'] defined, use it as page number
if(isset($_GET['page']))
{
$pageNum = $_GET['page'];
}

// counting the offset
$offset = ($pageNum - 1) * $rowsPerPage;
//query database for all districts

if ($currentmonth !="")
{
   $qury = "SELECT ID,patient,datereceived,spots,datecollected,receivedstatus
            FROM samples
			WHERE facility='$fcode' AND MONTH(datereceived)='$currentmonth' AND YEAR(datereceived)='$currentyear'
			ORDER BY ID DESC
			LIMIT $offset, $rowsPerPage";
			$result = mysql_query($qury) or die(mysql_error());
			$no=mysql_num_rows($result);
			
			
}
else
{
	   $qury = "SELECT ID,patient,datereceived,spots,datecollected,receivedstatus
            FROM samples
			WHERE facility='$fcode'  AND YEAR(datereceived)='$currentyear'
			ORDER BY ID DESC
			LIMIT $offset, $rowsPerPage";
			$result = mysql_query($qury) or die(mysql_error());
			$no=mysql_num_rows($result);
}
			
			

if ($no !=0)
{ 
echo '<table border="0" align="centre" class="data-table">';	

echo "
<tr class='even'>
<th width='558'>
 Facility:  $fname | Province:  $province | District:  $district
</th>
	<th width='200' >
   	Date Received: $defaultmonth
 
  </th>
  <th width='200' >
  	 <a href=\"facilityexcel.php" ."?fcode=$fcode&currentyear=$currentyear&currentmonth=$currentmonth&fname=$fname&district=$district&province=$province" . "\" title='Click to Download Excel of the report'>Download Exel</a>
 </th>

 <th width='30' >
<input name='back' type='button' class='button' value='Back' onclick='history.go(-1)'/>
  </th>
	</tr>";
		echo '</table>';
			
// print the districts info in table
echo '<table border="0" align="center" class="data-table">
<tr ><th colspan="14">Sample Log</th></tr>
<tr><th colspan="6">Patient Information</th><th colspan="3">Sample</th><th colspan="3">Mother Information</th><th colspan="1"></th><th colspan="1"></th></th></tr>
<tr><th>No</th><th>Patient ID</th><th>Sex</th><th>Age (mths)</th><th>Infant Prophylaxis</th><th>Date Collected</th><th>Status</th><th>Spots</th><th>HIV Status</th><th>PMTCT Intervention</th><th>Feeding Type</th><th>Entry Point</th><th>Result</th><th>Task</th></tr>';
	$No=0;
	while(list($ID,$patient,$datereceived,$spots,$datecollected,$receivedstatus) = mysql_fetch_array($result))
	{  
		//date collcted
		$sdoc=date("d-M-Y",strtotime($datecollected));
		//get patient gender
		$pgender=GetPatientGender($patient);
		//patietn age
		$pAge=GetPatientAge($patient);
		//patient dob
		$pdob=GetPatientDOB($patient);
		//infant prophylaxis
		$pprophylaxis=GetPatientProphylaxis($patient);
		//get sample sample test results
		$routcome = GetSampleResult($ID);
		//get sample recevied
		$srecstatus=GetReceivedStatus($receivedstatus);
		//get mother id from patient 
		$mother=GetMotherID($patient);
		//mother hiv
		$mhiv=GetMotherHIVstatus($mother);
		//mother pmtct intervention
		$mprophylaxis=GetMotherProphylaxis($mother);
		//get mothers feeding type
		$mfeeding=GetMotherFeeding($mother);
		//get entry point
		$entry=GetEntryPoint($mother);
		$No=$No+1;
	echo "<tr class='even'>
	<td >$No</td>
	<td ><a href=\"sample_details.php" ."?ID=$ID" . "\" title='Click to view sample details'>$patient</a></td>
			<td >$pgender </td>
			<td >$pAge</td>
			
			<td >$pprophylaxis</td>
			<td >$sdoc</td>
			<td > $srecstatus</td>
			<td >$spots </td>
			<td >$mhiv</td>
			<td >$mprophylaxis</td>
			<td >$mfeeding</td>
			<td >$entry</td>
			<td >$routcome </td>

	<td ><a href=\"sample_details.php" ."?ID=$ID" . "\" title='Click to view sample details'>View Detail</a> | <a href=\"edit_sample.php" ."?ID=$ID" . "\" title='Click to edit sample details' > Edit</a>  
</td>

	
	</tr>";
	}/*<a href="somepage.htm" title="Click to see this page!!!">Somepage</a>*/
	echo '</table>';
	
 //echo "<a href=\"createacct2.php"  . "\">Click to Open another account</a>";

/*$numrows =  GetSamplesPerBatch($batchno);

// how many pages we have when using paging?
$NumberOfPages = ceil($numrows/$rowsPerPage);


$Nav="";
if($pageNum > 1) {
$Nav .= "<A HREF=\"BatchDetails.php?page=" . ($pageNum-1) . "&ID=" .urlencode($batchno) . "\"><<  Prev  </A>";
}
for($i = 1 ; $i <= $NumberOfPages ; $i++) {
if($i == $pageNum) {
$Nav .= "<B>  $i  </B>";
}else{
$Nav .= "<A HREF=\"BatchDetails.php?page=" . $i . "&ID=" .urlencode($batchno) . "\">  $i  </A>";
}
}
if($pageNum < $NumberOfPages) {
$Nav .= "<A HREF=\"BatchDetails.php?page=" . ($pageNum+1) . "&ID=" .urlencode($batchno) . "\">  Next   >></A>";
}
echo '<center>';
echo "<BR> <BR>" . $Nav; 
echo '<center>';*/
}

else
{
?>
<table   >
  <tr>
    <td style="width:auto" ><div class="notice"><?php 
		
echo  '<strong>'.' <font color="#666600">'.'No Samples '.'</strong>'.' </font>';

?></div></th>
  </tr>
</table><?php
}  
  ?>  