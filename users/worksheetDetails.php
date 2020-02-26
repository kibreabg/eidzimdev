<?php include('../includes/header.php');

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
$samplesPerRow = 3;
$creator=GetUserFullnames($createdby);
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
		<div class="xtop">
		<table border="0" >

<tr class='even'>

 <th width='40' >
<A HREF='javascript:history.back(-1)'><img src='../img/back.gif' alt='Go Back'/></A>
  </th>
	</tr>
		</table>
		<form  method="post" action="downloadworksheet.php?ID=<?php echo $wno; ?>" name="worksheetform" target="_blank" >
		<table border="0" class="data-table">
		<tr class="even">
		<td class="comment style1 style4" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px">
		<strong>Worksheet Serial No </strong>		</td>
		<td class="comment">
		  <span class="style5" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><?php echo $ID; ?></span></td>
		<!--<td><span class="style5" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px">
	  	 <strong> Spek Kit No	</strong>	</span></td>
		<td  colspan="">
		  <span class="style5" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><?php echo $Spekkitno; ?></span> </td> -->
		<td><span class="style5" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><strong>KIT EXP </strong></span></td>
		<td ><span style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><?php echo $kitexpirydate; ?></span></td>
		<td class="comment style1 style4" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><strong>Lot No</strong> </td>
		<td><span class="comment style1 style4" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><?php echo $Lotno; ?> </span></td>
		</tr>
		
		<tr class="even">
		<td class="comment style1 style4" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px">
		<strong>Worksheet No</strong>		</td>
		<td class="comment">
		  <span class="style5" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><?php echo $worksheetno; ?></span></td>
		 <td><span class="style5" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><strong>Date Cut</strong> </span></td>
		<td colspan="4"><span style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><?php echo $datecut; ?></span></td>
		</tr>
		
		<tr class="even">
		<td class="comment style1 style4" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px">
		<strong>Date Created</strong>		</td>
		<td class="comment" ><span class="style5" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><?php echo $datecreated; //get current date ?></span></td>
				<!--<td class="comment style1 style4" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><strong>HIQCAP Kit No</strong></td>
		  <td><span class="comment style1 style4" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px"><?php echo $HIQCAPNo; ?></span></td> -->
		<td class="comment style1 style4" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px">
		<strong>Created By	</strong>    </td>
		<td colspan="4" class="comment"  ><span class="style5" style="font-family: &quot;Courier New&quot;, Courier, monospace; font-size: 12px">
	    <?php echo $creator;  ?>
		</span></td>
 		 </tr>
		
		

		<tr >
			<th colspan="6" ><small> <strong>22 WORKSHEET SAMPLES [2 Controls]</strong></small>		</th>
			</tr>
<tr  >
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
?>  <td height="50" bgcolor="#dddddd" class="comment style1 style4"> <?php echo $pc; ?> </td><?php	 
	

       $count ++;         
	$colcount ++;
			
}
$scount = 2;
	
	 $qury = "SELECT ID,patient, nmrlstampno
         FROM samples
		WHERE worksheet='$wno' ORDER BY parentid DESC, nmrlstampno asc
			";			
			$result = mysql_query($qury) or die(mysql_error());

	
	while(list($ID,$patient,$nmrlstampno) = mysql_fetch_array($result))
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
$RE= $colcount%6;
     
       

  
        
      echo "<td width='146px' height='50px'>
	  	<strong>NMRL No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong>&nbsp;&nbsp; $nmrlstampno   <br>
		<strong>Request No :</strong>&nbsp;&nbsp; $patient  <input name='scode[]' type='hidden' id='scode' value='$patient' size='20' readonly=''> <br>
		<strong>Lab Code &nbsp;&nbsp;&nbsp;&nbsp;:</strong>&nbsp;&nbsp; $ID  <input name='labcode[]' type='hidden' id='labcode' value='$ID' size='5' readonly=''>  
		
			 <img src='../html/image.php?code=code128&o=2&dpi=50&t=50&r=1&rot=0&text=$ID&f1=Arial.ttf&f2=6&a1=&a2=B&a3=' align=centre  /> </td>
";
	
       
   
    
        $colcount ++;
		 
		
             if ($RE==0)
			 { 
			?>
     </tr>
<?php
		 }//end if modulus is 0
	 }//end while?>

<tr bgcolor="#999999">
            <td  colspan="7" bgcolor="#00526C" ><center>
			
			    <input type="submit" name="SaveWorksheet" value="Print Worksheet" class="button"  />
				
            </center></td>
          </tr>
</table>
	</form>
 
    <?php include('../includes/footer.php');?>
