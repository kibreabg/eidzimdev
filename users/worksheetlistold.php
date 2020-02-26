<?php 
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
$success=$_GET['p'];
$wtype=$_GET['wtype']; // Use this line or below line if register_global is off

$searchparameter = ltrim(rtrim($_POST['wsheet'])); //get the search parameter from the userheader and trim the value
$searchparameterid = ltrim(rtrim($_POST['wsheetid'])); //get the search parameter from the userheader and trim the value

include('../includes/header.php');?>
<style type="text/css">
select {
width: 250;}
</style>	<script language="javascript" src="calendar.js"></script>

<link type="text/css" href="calendar.css" rel="stylesheet" />	
		<SCRIPT language=JavaScript>
function reload(form)
{
var val=form.cat.options[form.cat.options.selectedIndex].value;
self.location='addsample.php?catt=' + val ;
}
</script>

		<div  class="section">
		<div class="section-title">WORKSHEET LIST </div>
		<div class="xtop">
		<?php
			if (isset($wtype))
			   {
		include("http://localhost/eid_zim/users/worksheetslink.php?wtype=$wtype");
		}
		else
		{
		include("http://localhost/eid_zim/users/worksheetslink.php");
		}

		
		if ($success !="")
				{
				?> 
				<table>
				  <tr>
					<td style="width:auto" >
					<div class="success">
					<?php echo  '<strong>'.' <font color="#666600">'.$success.'</strong>'.' </font>';?>
					</div>
					</td>
				  </tr>
				</table>
				<?php } ?>

	 <?php 
			   if (isset($wtype))
			   {
			   		if ($wtype==1) //complete worksheets
					{
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
   $qury = "SELECT ID,worksheetno,datecreated,HIQCAPNo,spekkitno,createdby,Lotno,Rackno,Flag,daterun,datereviewed,type
            FROM worksheets
			WHERE Flag=1
			ORDER BY ID DESC
			LIMIT $offset, $rowsPerPage";
			
			$result = mysql_query($qury) or die(mysql_error()); //for main display
			$result2 = mysql_query($qury) or die(mysql_error()); //for calculating samples with results and those without

$no=mysql_num_rows($result);



if ($no !=0)
{ 
// print the districts info in table
echo '<table border="0"   class="data-table">
            
<tr ><th><small>Serial #</small></th><th><small>Worksheet No</small></th><th><small>Date Created</small></th><th><small>Created By</small></th><th><small>Type</small></th><th><small>No. of Samples</th><th><small>Lot No</small></th><th><small>Date Run</small></th><th><small>Date Reviewed</small></th><th><small>Status</small></th><th><small>Task</small></th></tr>';	while(list($ID,$worksheetno,$datecreated,$HIQCAPNo,$spekkitno,$createdby,$Lotno,$Rackno,$Flag,$daterun,$datereviewed,$type)  = mysql_fetch_array($result))
	{  
	
		if ($Flag ==0)
{
$status=" <strong><small><font color='#0000FF'> In Process  </small></font></strong>";
}
else
{
$status=" <strong><font color='#339900'> Complete </font></strong>";
}
	$numsamples=GetSamplesPerworksheet($worksheetno);
		if ($type==0)
		{
		//get number of sampels per  worksheet
		$worksheettype="<small><font color='#0000FF'>Taqman</font></small>";
		
		}
		else
		{
		$worksheettype="<small><font color='#FF0000'>Manual</font></small>";
		}
		if ($daterun !="")
		{
		$daterun=date("d-M-Y",strtotime($daterun));
		}
		if ($datereviewed !="")
		{
		$datereviewed=date("d-M-Y",strtotime($datereviewed));
		}
		$datecreated=date("d-M-Y",strtotime($datecreated));
$creator=GetUserFullnames($createdby);

if ($type ==0)
{
$d=" <a href=\"completeworksheetDetails.php" ."?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this batch'>View Details</a> | <a href=\"downloadcompleteworksheet.php" ."?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> ";
}
elseif ($type ==1)
{
$d=" <a href=\"completemanualworksheetdetails.php" ."?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this batch'>View Details</a> | <a href=\"downloadcompletemanualworksheet.php" ."?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> ";
}



	echo "<tr class='even'>
			<td >$ID</td>
			<td >$worksheetno</td>
			<td >$datecreated</td>
			<td >$creator </td>
			<td >$worksheettype</td>
			<td > $numsamples</td>
			<td > $Lotno</td>
				<td >$daterun</td>
			<td >$datereviewed</td>
			<td >$status</td>
			<td >$d</td>
			
			
			
	</tr>";
	}
	echo '</table>';
	?>
		<?php
	echo '<br>';
	$numrows=Gettotalcompleteworksheets(); //get total no of batches

	// how many pages we have when using paging?
	$maxPage = ceil($numrows/$rowsPerPage);

// print the link to access each page
$self = $_SERVER['PHP_SELF'];
$nav  = '';
for($page = 1; $page <= $maxPage; $page++)
{
   if ($page == $pageNum)
   {
      $nav .= " $page "; // no need to create a link to current page
   }
   else
   {
      $nav .= " <a href=\"$self?page=$page\">$page</a> ";
   }
}

// creating previous and next link
// plus the link to go straight to
// the first and last page

if ($pageNum > 1)
{
   $page  = $pageNum - 1;
   $prev  = " <a href=\"$self?page=$page\">[Prev]</a> ";

   $first = " <a href=\"$self?page=1\">[First Page]</a> ";
}
else
{
   $prev  = '&nbsp;'; // we're on page one, don't print previous link
   $first = '&nbsp;'; // nor the first page link
}

if ($pageNum < $maxPage)
{
   $page = $pageNum + 1;
   $next = " <a href=\"$self?page=$page\">[Next]</a> ";

   $last = " <a href=\"$self?page=$maxPage\">[Last Page]</a> ";
}
else
{
   $next = '&nbsp;'; // we're on the last page, don't print next link
   $last = '&nbsp;'; // nor the last page link
}

// print the navigation link
echo '<center>'. ' Page ' .$first . $prev . $nav . $next . $last .'</center>';


}

else
{

?>
<table   >
  <tr>
    <td style="width:auto" ><div class="notice"><?php 
		
echo  '<strong>'.' <font color="#666600">'.'No Completed Worksheets '.'</strong>'.' </font>';

?></div></th>
  </tr>
</table><?php

 }  
					}
					
				
			   		elseif ($wtype==0) //pendin worksheets
					{
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
   $qury = "SELECT ID,worksheetno,datecreated,HIQCAPNo,spekkitno,createdby,Lotno,Rackno,Flag,daterun,datereviewed,type
            FROM worksheets
			WHERE Flag=0
			ORDER BY ID DESC
			LIMIT $offset, $rowsPerPage";
			
			$result = mysql_query($qury) or die(mysql_error()); //for main display
			$result2 = mysql_query($qury) or die(mysql_error()); //for calculating samples with results and those without

$no=mysql_num_rows($result);



if ($no !=0)
{ ?><!--<table>
	 <tr> 			
               <td colspan="10"><strong><?php //echo "Total Pending Worksheets: [ " .GettotalPendingworksheets() . " ]";?></strong>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  View:- &nbsp;&nbsp;&nbsp;&nbsp;<a href="worksheetlist.php">All </a>  |  <a href="worksheetlist.php?wtype=<?php //echo "0";?>">Pending Worksheets </a>  |  <a href="worksheetlist.php?wtype=<?php //echo "2";?>"> Complete Worksheets </a>  
			    </td>
             </tr></table> -->
	<?php
// print the districts info in table
echo '<table border="0"   class="data-table">
            
<tr ><th><small>Serial #</small></th><th><small>Worksheet No</small></th><th><small>Date Created</small></th><th><small>Created By</small></th><th><small>Type</small></th><th><small>No. of Samples</th><th><small>Lot No</small></th><th><small>Date Run</small></th><th><small>Date Reviewed</small></th><th><small>Status</small></th><th><small>Task</small></th></tr>';	while(list($ID,$worksheetno,$datecreated,$HIQCAPNo,$spekkitno,$createdby,$Lotno,$Rackno,$Flag,$daterun,$datereviewed,$type) = mysql_fetch_array($result))
	{  
	
	
		//get number of sampels per  worksheet
		$numsamples=GetSamplesPerworksheet($worksheetno);
		
		if ($daterun !="")
		{
		$daterun=date("d-M-Y",strtotime($daterun));
		}
		if ($datereviewed !="")
		{
		$datereviewed=date("d-M-Y",strtotime($datereviewed));
		}
		$datecreated=date("d-M-Y",strtotime($datecreated));
$creator=GetUserFullnames($createdby);

$wsheet="General worksheet";

if ($type ==0)
{
$d2="<a href=\"worksheetDetails.php" ."?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadworksheet.php" ."?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" ."?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $worksheetno');\" >Delete Worksheet  </a>";
$d="| <a href=\"updateresults.php" ."?ID=$worksheetno" . "\" title='Click to Update Results Worksheet' > Update Results </a>";
$worksheettype="<small><font color='#0000FF'>Taqman</font></small>";
}
else
{
$d2="<a href=\"manualworksheetDetails.php" ."?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadmanualworksheet.php" ."?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" ."?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $ID');\" >Delete Worksheet  </a>";
$d="| <a href=\"updatemanualresults.php" ."?view=1&ID=$worksheetno" . "\" title='Click to Update Results Worksheet' > Update Results </a>";
$worksheettype="<small><font color='#FF0000'>Manual</font></small>";
}

if ($Flag ==0)
{
$status=" <strong><small><font color='#0000FF'> In Process  </small></font></strong>";
}
else
{
$status=" <strong><font color='#339900'> Complete </font></strong>";
}




	echo "<tr class='even'>
			<td >$ID</td>
			<td ><a href=\"worksheetDetails.php" ."?ID=$worksheetno&view=1" . "\" title='Click to view  Samples in this batch'>$worksheetno</a></td>
	
			<td >$datecreated</td>
			<td >$creator </td>
			<td >$worksheettype</td>
						<td > $numsamples</td>
			<td > $Lotno</td>
					<td >$daterun</td>
			<td >$datereviewed</td>
				<td >$status</td>
			<td > $d2 $d 
			</td>
			
			
			
	</tr>";
	}
	echo '</table>';
	?>
		<?php
	echo '<br>';
	$numrows=GettotalPendingworksheets(); //get total no of batches

	// how many pages we have when using paging?
	$maxPage = ceil($numrows/$rowsPerPage);

// print the link to access each page
$self = $_SERVER['PHP_SELF'];
$nav  = '';
for($page = 1; $page <= $maxPage; $page++)
{
   if ($page == $pageNum)
   {
      $nav .= " $page "; // no need to create a link to current page
   }
   else
   {
      $nav .= " <a href=\"$self?page=$page\">$page</a> ";
   }
}

// creating previous and next link
// plus the link to go straight to
// the first and last page

if ($pageNum > 1)
{
   $page  = $pageNum - 1;
   $prev  = " <a href=\"$self?page=$page\">[Prev]</a> ";

   $first = " <a href=\"$self?page=1\">[First Page]</a> ";
}
else
{
   $prev  = '&nbsp;'; // we're on page one, don't print previous link
   $first = '&nbsp;'; // nor the first page link
}

if ($pageNum < $maxPage)
{
   $page = $pageNum + 1;
   $next = " <a href=\"$self?page=$page\">[Next]</a> ";

   $last = " <a href=\"$self?page=$maxPage\">[Last Page]</a> ";
}
else
{
   $next = '&nbsp;'; // we're on the last page, don't print next link
   $last = '&nbsp;'; // nor the last page link
}

// print the navigation link
echo '<center>'. ' Page ' .$first . $prev . $nav . $next . $last .'</center>';


}

else
{

?>
<table   >
  <tr>
    <td style="width:auto" ><div class="notice"><?php 
		
echo  '<strong>'.' <font color="#666600">'.'No Pending Worksheets'.'</strong>'.' </font>';

?></div></th>
  </tr>
</table><?php

 }  //end if number pending worksheet
 
					} //end if wtype=0
			   
	elseif ($wtype==2) //manuak worksheets
 {
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



   $qury = "SELECT ID,worksheetno,datecreated,HIQCAPNo,spekkitno,createdby,Lotno,Rackno,Flag,daterun,datereviewed,type
            FROM worksheets where type='1'
			ORDER BY ID DESC
			LIMIT $offset, $rowsPerPage";
			
			$result = mysql_query($qury) or die(mysql_error()); //for main display
			$result2 = mysql_query($qury) or die(mysql_error()); //for calculating samples with results and those without

$no=mysql_num_rows($result);



if ($no !=0)
{ ?><!--<table>
	 <tr> 			
               <td colspan="10"><strong><?php //echo "Total Worksheets: [ " .Gettotalworksheets() . " ]";?></strong>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  View:- &nbsp;&nbsp;&nbsp;&nbsp;<a href="worksheetlist.php">All </a>  |  <a href="worksheetlist.php?wtype=<?php //echo "0";?>">Pending Worksheets </a>  |  <a href="worksheetlist.php?wtype=<?php //echo "2";?>"> Complete Worksheets </a>  
			    </td>
             </tr></table> -->
	<?php
// print the districts info in table
echo '<table border="0"   class="data-table">
            
<tr ><th><small>Serial #</small></th><th><small>Worksheet No</small></th><th><small>Date Created</small></th><th><small>Created By</small></th><th><small>Type</small></th><th><small>No. of Samples</th><th><small>Lot No</small></th><th><small>Date Run</small></th><th><small>Date Reviewed</small></th><th><small>Status</small></th><th><small>Task</small></th></tr>';	while(list($ID,$worksheetno,$datecreated,$HIQCAPNo,$spekkitno,$createdby,$Lotno,$Rackno,$Flag,$daterun,$datereviewed,$type) = mysql_fetch_array($result))
	{  
	
		//get number of sampels per  worksheet
		$numsamples=GetSamplesPerworksheet($worksheetno);
		
		
		if ($daterun !="")
		{
		$daterun=date("d-M-Y",strtotime($daterun));
		}
		if ($datereviewed !="")
		{
		$datereviewed=date("d-M-Y",strtotime($datereviewed));
		}
		$datecreated=date("d-M-Y",strtotime($datecreated));
$creator=GetUserFullnames($createdby);


if ($Flag ==0)
{
$status=" <strong><small><font color='#0000FF'> In Process  </small></font></strong>";
if ($type ==0)
{
$d="<a href=\"worksheetDetails.php" ."?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadworksheet.php" ."?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" ."?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $worksheetno');\" >Delete Worksheet  </a> | <a href=\"updateresults.php" ."?ID=$worksheetno" . "\" title='Click to Update Results Worksheet' > Update Results </a>";

$worksheettype="<small><font color='#0000FF'>Taqman</font></small>";

}
else if ($type ==1)
{
$d="<a href=\"manualworksheetDetails.php" ."?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadmanualworksheet.php" ."?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" ."?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $ID');\" >Delete Worksheet  </a> | <a href=\"updatemanualresults.php" ."?view=1&ID=$worksheetno" . "\" title='Click to Update Results Worksheet' > Update Results </a>";
$worksheettype="<small><font color='#FF0000'>Manual</font></small>";
}

}
else
{
$status=" <strong><font color='#339900'> Complete </font></strong>";
if ($type ==0)
{
$worksheettype="<small><font color='#0000FF'>Taqman</font></small>";
}
else if ($type ==1)
{
$worksheettype="<small><font color='#FF0000'>Manual</font></small>";
}
$d=" <a href=\"completeworksheetDetails.php" ."?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this batch'>View Details</a> | <a href=\"downloadcompleteworksheet.php" ."?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> ";
}
	echo "<tr class='even'>
			<td >$ID</td>
			<td ><a href=\"worksheetDetails.php" ."?ID=$worksheetno" . "\" title='Click to view  Samples in this batch'>$worksheetno</a></td>
	
			<td >$datecreated</td>
			<td >$creator </td>
			<td >$worksheettype</td>
			
			<td > $numsamples</td>
			<td > $Lotno</td>
					<td >$daterun</td>
			<td >$datereviewed</td>
				<td >$status</td>
			<td > $d
			</td>
			
			
			
	</tr>";
	}
	echo '</table>';
	?>
		<?php
	echo '<br>';
	$numrows=Gettotalworksheetsbytype(1); //get total no of batches

	// how many pages we have when using paging?
	$maxPage = ceil($numrows/$rowsPerPage);

// print the link to access each page
$self = $_SERVER['PHP_SELF'];
$nav  = '';
for($page = 1; $page <= $maxPage; $page++)
{
   if ($page == $pageNum)
   {
      $nav .= " $page "; // no need to create a link to current page
   }
   else
   {
      $nav .= " <a href=\"$self?page=$page\">$page</a> ";
   }
}

// creating previous and next link
// plus the link to go straight to
// the first and last page

if ($pageNum > 1)
{
   $page  = $pageNum - 1;
   $prev  = " <a href=\"$self?page=$page\">[Prev]</a> ";

   $first = " <a href=\"$self?page=1\">[First Page]</a> ";
}
else
{
   $prev  = '&nbsp;'; // we're on page one, don't print previous link
   $first = '&nbsp;'; // nor the first page link
}

if ($pageNum < $maxPage)
{
   $page = $pageNum + 1;
   $next = " <a href=\"$self?page=$page\">[Next]</a> ";

   $last = " <a href=\"$self?page=$maxPage\">[Last Page]</a> ";
}
else
{
   $next = '&nbsp;'; // we're on the last page, don't print next link
   $last = '&nbsp;'; // nor the last page link
}

// print the navigation link
echo '<center>'. ' Page ' .$first . $prev . $nav . $next . $last .'</center>';


}

else
{

?>
<table   >
  <tr>
    <td style="width:auto" ><div class="notice"><?php 
		
echo  '<strong>'.' <font color="#666600">'.'No Manual Worksheets Created'.'</strong>'.' </font>';

?></div></th>
  </tr>
</table><?php

 }  
 }
 elseif ($wtype==3) //taqman worksheets
 {
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



   $qury = "SELECT ID,worksheetno,datecreated,HIQCAPNo,spekkitno,createdby,Lotno,Rackno,Flag,daterun,datereviewed,type
            FROM worksheets where type='0'
			ORDER BY ID DESC
			LIMIT $offset, $rowsPerPage";
			
			$result = mysql_query($qury) or die(mysql_error()); //for main display
			$result2 = mysql_query($qury) or die(mysql_error()); //for calculating samples with results and those without

$no=mysql_num_rows($result);



if ($no !=0)
{ ?><!--<table>
	 <tr> 			
               <td colspan="10"><strong><?php //echo "Total Worksheets: [ " .Gettotalworksheets() . " ]";?></strong>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  View:- &nbsp;&nbsp;&nbsp;&nbsp;<a href="worksheetlist.php">All </a>  |  <a href="worksheetlist.php?wtype=<?php //echo "0";?>">Pending Worksheets </a>  |  <a href="worksheetlist.php?wtype=<?php //echo "2";?>"> Complete Worksheets </a>  
			    </td>
             </tr></table> -->
	<?php
// print the districts info in table
echo '<table border="0"   class="data-table">
            
<tr ><th><small>Serial #</small></th><th><small>Worksheet No</small></th><th><small>Date Created</small></th><th><small>Created By</small></th><th><small>Type</small></th><th><small>No. of Samples</th><th><small>Lot No</small></th><th><small>Date Run</small></th><th><small>Date Reviewed</small></th><th><small>Status</small></th><th><small>Task</small></th></tr>';	while(list($ID,$worksheetno,$datecreated,$HIQCAPNo,$spekkitno,$createdby,$Lotno,$Rackno,$Flag,$daterun,$datereviewed,$type) = mysql_fetch_array($result))
	{  
	
		//get number of sampels per  worksheet
		$numsamples=GetSamplesPerworksheet($worksheetno);
		
		
		if ($daterun !="")
		{
		$daterun=date("d-M-Y",strtotime($daterun));
		}
		if ($datereviewed !="")
		{
		$datereviewed=date("d-M-Y",strtotime($datereviewed));
		}
		$datecreated=date("d-M-Y",strtotime($datecreated));
$creator=GetUserFullnames($createdby);


if ($Flag ==0)
{
$status=" <strong><small><font color='#0000FF'> In Process  </small></font></strong>";
if ($type ==0)
{
$d="<a href=\"worksheetDetails.php" ."?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadworksheet.php" ."?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" ."?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $worksheetno');\" >Delete Worksheet  </a> | <a href=\"updateresults.php" ."?ID=$worksheetno" . "\" title='Click to Update Results Worksheet' > Update Results </a>";

$worksheettype="<small><font color='#0000FF'>Taqman</font></small>";

}
else if ($type ==1)
{
$d="<a href=\"manualworksheetDetails.php" ."?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadmanualworksheet.php" ."?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" ."?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $ID');\" >Delete Worksheet  </a> | <a href=\"updatemanualresults.php" ."?view=1&ID=$worksheetno" . "\" title='Click to Update Results Worksheet' > Update Results </a>";
$worksheettype="<small><font color='#FF0000'>Manual</font></small>";
}

}
else
{
$status=" <strong><font color='#339900'> Complete </font></strong>";
if ($type ==0)
{
$worksheettype="<small><font color='#0000FF'>Taqman</font></small>";
}
else if ($type ==1)
{
$worksheettype="<small><font color='#FF0000'>Manual</font></small>";
}
if ($type ==0)
{
$d=" <a href=\"completeworksheetDetails.php" ."?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this batch'>View Details</a> | <a href=\"downloadcompleteworksheet.php" ."?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> ";
}
elseif ($type ==1)
{
$d=" <a href=\"completemanualworksheetdetails.php" ."?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this batch'>View Details</a> | <a href=\"downloadcompletemanualworksheet.php" ."?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> ";
}

}
	echo "<tr class='even'>
			<td >$ID</td>
			<td ><a href=\"worksheetDetails.php" ."?ID=$worksheetno" . "\" title='Click to view  Samples in this batch'>$worksheetno</a></td>
	
			<td >$datecreated</td>
			<td >$creator </td>
			<td >$worksheettype</td>
			
			<td > $numsamples</td>
			<td > $Lotno</td>
					<td >$daterun</td>
			<td >$datereviewed</td>
				<td >$status</td>
			<td > $d
			</td>
			
			
			
	</tr>";
	}
	echo '</table>';
	?>
		<?php
	echo '<br>';
	$numrows=Gettotalworksheetsbytype(0); //get total no of batches

	// how many pages we have when using paging?
	$maxPage = ceil($numrows/$rowsPerPage);

// print the link to access each page
$self = $_SERVER['PHP_SELF'];
$nav  = '';
for($page = 1; $page <= $maxPage; $page++)
{
   if ($page == $pageNum)
   {
      $nav .= " $page "; // no need to create a link to current page
   }
   else
   {
      $nav .= " <a href=\"$self?page=$page\">$page</a> ";
   }
}

// creating previous and next link
// plus the link to go straight to
// the first and last page

if ($pageNum > 1)
{
   $page  = $pageNum - 1;
   $prev  = " <a href=\"$self?page=$page\">[Prev]</a> ";

   $first = " <a href=\"$self?page=1\">[First Page]</a> ";
}
else
{
   $prev  = '&nbsp;'; // we're on page one, don't print previous link
   $first = '&nbsp;'; // nor the first page link
}

if ($pageNum < $maxPage)
{
   $page = $pageNum + 1;
   $next = " <a href=\"$self?page=$page\">[Next]</a> ";

   $last = " <a href=\"$self?page=$maxPage\">[Last Page]</a> ";
}
else
{
   $next = '&nbsp;'; // we're on the last page, don't print next link
   $last = '&nbsp;'; // nor the last page link
}

// print the navigation link
echo '<center>'. ' Page ' .$first . $prev . $nav . $next . $last .'</center>';


}

else
{

?>
<table   >
  <tr>
    <td style="width:auto" ><div class="notice"><?php 
		
echo  '<strong>'.' <font color="#666600">'.'No Taqman Worksheets Created'.'</strong>'.' </font>';

?></div></th>
  </tr>
</table><?php

 }  
 }		   
			   
			   
				}
			else
			{
		 
       

	
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

if ($searchparameterid >0 ) //seach worksheets
{
 $qury = "SELECT ID,worksheetno,datecreated,HIQCAPNo,spekkitno,createdby,Lotno,Rackno,Flag,daterun,datereviewed,type
            FROM worksheets WHERE ID='$searchparameterid'
			ORDER BY ID DESC
			";
			
			$result = mysql_query($qury) or die(mysql_error()); //for main display
			$result2 = mysql_query($qury) or die(mysql_error()); //for calculating samples with results and those without
}
else
{

   $qury = "SELECT ID,worksheetno,datecreated,HIQCAPNo,spekkitno,createdby,Lotno,Rackno,Flag,daterun,datereviewed,type
            FROM worksheets
			ORDER BY ID DESC
			LIMIT $offset, $rowsPerPage";
			
			$result = mysql_query($qury) or die(mysql_error()); //for main display
			$result2 = mysql_query($qury) or die(mysql_error()); //for calculating samples with results and those without
}
$no=mysql_num_rows($result);

 if ($searchparameterid >0 ) //seach worksheets
 {
 ?>
 <table   >
  <tr>
    <td style="width:auto" ><div class="notice">
		
<?php echo "The search for Worksheet with Serial # <strong>".LTRIM(RTRIM($searchparameterid))."</strong> returned ".$no." results.<br/>"; ?>

</div></th>
  </tr>
</table>
 <?php
 }

if ($no !=0)
{ ?>


<!--<table>
	 <tr> 			
               <td colspan="10"><strong><?php //echo "Total Worksheets: [ " .Gettotalworksheets() . " ]";?></strong>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  View:- &nbsp;&nbsp;&nbsp;&nbsp;<a href="worksheetlist.php">All </a>  |  <a href="worksheetlist.php?wtype=<?php //echo "0";?>">Pending Worksheets </a>  |  <a href="worksheetlist.php?wtype=<?php //echo "2";?>"> Complete Worksheets </a>  
			    </td>
             </tr></table> -->
	<?php
// print the districts info in table
echo '<table border="0"   class="data-table">
            
<tr ><th><small>Serial #</small></th><th><small>Worksheet No</small></th><th><small>Date Created</small></th><th><small>Created By</small></th><th><small>Type</small></th><th><small>No. of Samples</th><th><small>Lot No</small></th><th><small>Date Run</small></th><th><small>Date Reviewed</small></th><th><small>Status</small></th><th><small>Task</small></th></tr>';	while(list($ID,$worksheetno,$datecreated,$HIQCAPNo,$spekkitno,$createdby,$Lotno,$Rackno,$Flag,$daterun,$datereviewed,$type) = mysql_fetch_array($result))
	{  
	
		//get number of sampels per  worksheet
		$numsamples=GetSamplesPerworksheet($worksheetno);
		
		
		if ($daterun !="")
		{
		$daterun=date("d-M-Y",strtotime($daterun));
		}
		if ($datereviewed !="")
		{
		$datereviewed=date("d-M-Y",strtotime($datereviewed));
		}
		$datecreated=date("d-M-Y",strtotime($datecreated));
$creator=GetUserFullnames($createdby);


if ($Flag ==0)
{
$status=" <strong><small><font color='#0000FF'> In Process  </small></font></strong>";
if ($type ==0)
{
$d="<a href=\"worksheetDetails.php" ."?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadworksheet.php" ."?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" ."?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $worksheetno');\" >Delete Worksheet  </a> | <a href=\"updateresults.php" ."?ID=$worksheetno" . "\" title='Click to Update Results Worksheet' > Update Results </a>";

$worksheettype="<small><font color='#0000FF'>Taqman</font></small>";

}
else if ($type ==1)
{
$d="<a href=\"manualworksheetDetails.php" ."?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this Worksheet'>View Details</a> | <a href=\"downloadmanualworksheet.php" ."?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> | <a href=\"deleteworksheet.php" ."?ID=$worksheetno&serial=$ID" . "\" title='Click to Delete Worksheet' OnClick=\"return confirm('Are you sure you want to delete Worksheet $ID');\" >Delete Worksheet  </a> | <a href=\"updatemanualresults.php" ."?view=1&ID=$worksheetno" . "\" title='Click to Update Results Worksheet' > Update Results </a>";
$worksheettype="<small><font color='#FF0000'>Manual</font></small>";
}

}
else
{
$status=" <strong><font color='#339900'> Complete </font></strong>";
if ($type ==0)
{
$worksheettype="<small><font color='#0000FF'>Taqman</font></small>";
}
else if ($type ==1)
{
$worksheettype="<small><font color='#FF0000'>Manual</font></small>";
}
if ($type ==0)
{
$d=" <a href=\"completeworksheetDetails.php" ."?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this batch'>View Details</a> | <a href=\"downloadcompleteworksheet.php" ."?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> ";
}
elseif ($type ==1)
{
$d=" <a href=\"completemanualworksheetdetails.php" ."?ID=$worksheetno&view=1" . "\" title='Click to view Samples in this batch'>View Details</a> | <a href=\"downloadcompletemanualworksheet.php" ."?ID=$worksheetno" . "\" title='Click to Download Worksheet' target='_blank'>Print Worksheet </a> ";
}
}
	echo "<tr class='even'>
			<td >$ID</td>
			<td ><a href=\"worksheetDetails.php" ."?ID=$worksheetno" . "\" title='Click to view  Samples in this batch'>$worksheetno</a></td>
	
			<td >$datecreated</td>
			<td >$creator </td>
			<td >$worksheettype</td>
			
			<td > $numsamples</td>
			<td > $Lotno</td>
					<td >$daterun</td>
			<td >$datereviewed</td>
				<td >$status</td>
			<td > $d
			</td>
			
			
			
	</tr>";
	}
	echo '</table>';
	?>
		<?php
	echo '<br>';
	$numrows=Gettotalworksheets(); //get total no of batches

	// how many pages we have when using paging?
	$maxPage = ceil($numrows/$rowsPerPage);

// print the link to access each page
$self = $_SERVER['PHP_SELF'];
$nav  = '';
for($page = 1; $page <= $maxPage; $page++)
{
   if ($page == $pageNum)
   {
      $nav .= " $page "; // no need to create a link to current page
   }
   else
   {
      $nav .= " <a href=\"$self?page=$page\">$page</a> ";
   }
}

// creating previous and next link
// plus the link to go straight to
// the first and last page

if ($pageNum > 1)
{
   $page  = $pageNum - 1;
   $prev  = " <a href=\"$self?page=$page\">[Prev]</a> ";

   $first = " <a href=\"$self?page=1\">[First Page]</a> ";
}
else
{
   $prev  = '&nbsp;'; // we're on page one, don't print previous link
   $first = '&nbsp;'; // nor the first page link
}

if ($pageNum < $maxPage)
{
   $page = $pageNum + 1;
   $next = " <a href=\"$self?page=$page\">[Next]</a> ";

   $last = " <a href=\"$self?page=$maxPage\">[Last Page]</a> ";
}
else
{
   $next = '&nbsp;'; // we're on the last page, don't print next link
   $last = '&nbsp;'; // nor the last page link
}

// print the navigation link
echo '<center>'. ' Page ' .$first . $prev . $nav . $next . $last .'</center>';


}

else
{

?>
<table   >
  <tr>
    <td style="width:auto" ><div class="notice"><?php 
		
echo  '<strong>'.' <font color="#666600">'.'No Worksheets Created'.'</strong>'.' </font>';

?></div></th>
  </tr>
</table><?php

 }  
 }
  ?>
	
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>