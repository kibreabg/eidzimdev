<?php include('../includes/header.php');?>
<?php 
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
$wno=$_GET['ID'];
$worksheet = getWorksheetDetails($wno);
extract($worksheet);			
$datecreated=date("d-M-Y",strtotime($datecreated));
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
		<form  method="post" action="downloadworksheet.php?ID=<?php echo $wno; ?>" name="worksheetform" target="_blank" >
		<table border="0" class="data-table">
		<tr >
		<td class="comment style1 style4">
		Worksheet No		</td>
		<td class="comment">
		  <span class="style5"><?php echo $ID; ?></span></td>
		<td class="comment style1 style4">Lot No </td>
		<td><span class="comment style1 style4"><?php echo $Lotno; ?> </span></td>
		<td><span class="style5">Date Cut </span></td>
		<td colspan="2">&nbsp;</td>
		</tr>
		<tr >
		<td class="comment style1 style4">
		Date Created		</td>
		<td class="comment" ><span class="style5"><?php echo $datecreated; //get current date ?></span></td>
				<td class="comment style1 style4">HIQCAP Kit No</td>
		  <td><span class="comment style1 style4"><?php echo $HIQCAPNo; ?></span></td>	
		  <td><span class="style5">Date Run </span></td>
		  <td colspan="2">&nbsp;</td>
		</tr>
<tr >
		<td class="comment style1 style4">
		Created By	    </td>
		<td class="comment"  ><span class="style5">
	    <?php echo $creator;  ?>
		</span></td><td class="comment style1 style4">
		Rack <strong>#</strong></td>
		<td><span class="comment style1 style4"><?php echo $Rackno; ?>
		</span></td>
		<td><span class="style5">Reviewed By </span></td>
		<td colspan="2">&nbsp;</td>
  </tr><tr >
		
		</tr>
		<tr >
		<td><span class="style5">
	  	  Spek Kit No		</span></td>
		<td  colspan="">
		  <span class="style5"><?php echo $Spekkitno; ?></span> </td>
		<td><span class="style5">KIT EXP </span></td>
		<td>&nbsp;</td>
		<td><span class="style5">Date Reviewed </span></td>
		<td colspan="2">&nbsp;</td>
		</tr>

		<td colspan="7" >&nbsp;
		</td>
		</tr>
<tr  >
	 <?php
	 $qury = "SELECT ID,patient
         FROM samples
		WHERE worksheet='$wno'
			";			
			$result = mysql_query($qury) or die(mysql_error());

	 $i = 0;
	$samplesPerRow = 7;
	while(list($ID,$patient) = mysql_fetch_array($result))
	{  
	
	

     
        if ($i % $samplesPerRow == 0) {
            echo '<tr >';
        }

  
        
      echo "<td >Lab Code  <input name='labcode[]' type='text' id='labcode' value='$ID' size='5' readonly=''>  
			 <img src='../html/image.php?code=code128&o=2&dpi=50&t=50&r=1&rot=0&text=$ID&f1=Arial.ttf&f2=6&a1=&a2=B&a3=' align=centre  /> </td>
";
	
       
   
    
        if ($i % $samplesPerRow == $samplesPerRow - 1) {
            echo '</tr>';
        }
        
        $i += 1;

	


	}
	 echo"<td  align='center' colspan='3'> PC </td><td  align='center' colspan='3'> NC </td>";
	?>

</tr>
<tr bgcolor="#999999">
            <td  colspan="7" bgcolor="#00526C" ><center>
			
			    <input type="submit" name="SaveWorksheet" value="Print Worksheet" class="button"  />
				
            </center></td>
          </tr>
		 
</table>
	
   
	</tr> 

		    
		
		
	
		
 <?php include('../includes/footer.php');?>