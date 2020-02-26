<?php include('../includes/header.php');?>
<?php 
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
$wno=$_GET['ID'];
$worksheet = getWorksheetDetails($wno);
extract($worksheet);			
$datecreated=date("d-M-Y",strtotime($datecreated));
if ($kitexpirydate !="")
{
$kitexpirydate=date("d-M-Y",strtotime($kitexpirydate));
}
if ($datecut != "")
{
$datecut=date("d-M-Y",strtotime($datecut));
}
else
{
$datecut="";
}
if ($daterun !="")
{
$daterun=date("d-M-Y",strtotime($daterun));
}
else
{
$daterun="";
}
if ($datereviewed !="")
{
$datereviewed=date("d-M-Y",strtotime($datereviewed));
}
else
{
$datereviewed="";
}
$samplesPerRow = 3;
$creator=GetUserFullnames($createdby);
$reviewedby=GetUserFullnames($reviewedby);
?>

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
<script language="JavaScript">
function submitPressed() {
document.worksheetform.SaveWorksheet.disabled = true;
//stuff goes here
document.worksheetform.submit();
}
</script> 
		<div  class="section">
		<div class="section-title">WORKSHEET NO <?php echo $wno; ?> DETAILS </div>
		<p>
				<A HREF='javascript:history.back(-1)'><img src='../img/back.gif' alt='Go Back'/></A>
		</p>
	<!--	<div class="xtop"> -->
		<form  method="post" action="downloadcompleteworksheet.php?ID=<?php echo $wno; ?>" name="worksheetform" target="_blank" >
		<table border="0" class="data-table">
		
		<tr class="even">		
			<th  style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><div align="left">Serial No	</div>	</th>
			<td><span class="style5" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><?php echo $ID; ?></span></td>
			<th style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><div align="left">Lot No</div> </th>
			<td><span class="comment style1 style4" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><?php echo $Lotno; ?> </span></td>	
			<th><span class="style5" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><div align="left">KIT EXPIRY </div></span></th>
			<td><span style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><font color="#FF0000"><strong><?php echo $kitexpirydate; ?></strong></font></span></td>					
		</tr>
		<!-- -->
		<tr  class="even">
			<th style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><div align="left">Worksheet No</div>		</th>
			<td ><span class="style5" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><?php echo $worksheetno; ?></span></td>
			<th><span class="style5" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><div align="left">Date Cut </div></span></th>
			<td ><span style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><?php echo $datecut; ?></span></td>
			<th><span class="style5" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><div align="left">Date Run </div></span></th>
			<td colspan="2"><span style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><?php echo $daterun; ?></span></td>
		</tr>
		<!-- -->
		<tr  class="even">
			<th style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><div align="left">Date Created</div></th>
			<td><span class="style5" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><?php echo $datecreated; //get current date ?></span></td>
			<th  style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><div align="left"><div align="left">Created By	</div></div> </th>
			<td  colspan="4"><span class="style5" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><?php echo $creator;  ?></span></td>				
		</tr>
		<!-- -->
		<tr  class="even">
			<th><span class="style5" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><div align="left">Date Reviewed </div></span></th>
			<td><span style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><?php echo $datereviewed; ?></span></td>
			<th><span class="style5" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><div align="left">Reviewed By </div></span></th>
			<td colspan="4"><span style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><?php echo $reviewedby; ?></span></td>
		</tr>
		<!-- -->
		
		<tr>
		<th colspan="6" ><small> <strong><?php echo $no; ?> WORKSHEET SAMPLES [2 Controls]</strong></small>		</th>
		</tr>
		<!-- -->
		
		<!--BEGIN DISPLAYING THE WORKSHEET VALUES -->
<tr >
<?php
	 $count = 1;
$colcount=1;
for($i = 1; $i <= 2; $i++) 
{
		if ($count==1)
		{
		$pc="<div align='right'>
	 	</div><div align='center'>Negative Control<br><strong>NC</strong></div>";
		}
		elseif ($count==2)
		{
		$pc="<div align='center'>Positive Control<br><strong>PC</strong></div>";
		}
		
				$RE= $colcount%6;
?>  <td height="50"> <?php echo $pc; ?> </td><?php	 
	

       $count ++;         
	$colcount ++;
			
}
$scount = 2;
	
	 $qury = "SELECT ID,patient,result, nmrlstampno
         FROM samples
		WHERE worksheet='$wno' order by parentid desc, nmrlstampno asc
			";			
			$quryresult = mysql_query($qury) or die(mysql_error());

	 $i = 0;
	$samplesPerRow = 7;
	while(list($ID,$patient,$result,$nmrlstampno) = mysql_fetch_array($quryresult))
	{  
		$scount = $scount + 1;
		$paroid=getParentID($ID,$labss);//get parent id
	
		if ($paroid ==0)
		{
		$paroid="";
		}
		else
		{
		$paroid=" - ". $paroid;
		}
	//get sample sample test results
		$routcome = GetResultType($result);
		$RE= $colcount%6;
     
       if ($result==1)//negative
	   {	$beginfont = '<font color="#009900">';
	   }
	   else if ($result == 2)//positive
	   {	$beginfont = '<font color="#FF0000">';
	   }
	   else
	   { 	$beginfont = '<font color="">';
	   }

  
        
      echo "<td width='140'>
	  	NMRL No &nbsp;&nbsp;&nbsp; : $nmrlstampno  <br>
		Request No : $patient  <br>
		Lab Code &nbsp;&nbsp;&nbsp; : $ID<input name='labcode[]' type='hidden' id='labcode' value='$ID' size='5' readonly=''> $paroid  <br>
		Result &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : $beginfont <strong>$routcome</strong> </font> </td>
";
	
       
   
    
       $colcount ++;
		 
		
             if ($RE==0)
			 { 
			?>
     </tr>
	 
	
<?php
		 }//end if modulus is 0
	 }//end while?>


            <th colspan="7"><input type="submit" name="SaveWorksheet" value="Print Worksheet" class="button" style="width:400px"  /></th>
         
</table>
	
 
    <?php include('../includes/footer.php');?>
