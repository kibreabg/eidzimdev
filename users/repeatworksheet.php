<?php 
session_start();
include('../includes/header.php');
define('IN_CB',true);

define('VERSION', '2.1.0');

if(version_compare(phpversion(),'5.0.0','>=')!==true)
	exit('Sorry, but you have to run this script with PHP5... You currently have the version <b>'.phpversion().'</b>.');

if(!function_exists('imagecreate'))
	exit('Sorry, make sure you have the GD extension installed before running this script.');

include('../html/config.php');

require('../html/function.php');
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
$userid=$_SESSION['uid'] ;			
$creator=GetUserFullnames($userid);
?>
<?php 
$pid= $_POST['pid'];
$checkbox=$_POST['checkbox'] ;
$userid=$_SESSION['uid'] ;	
$worksheetno=GetNewWorksheetNo(); //calculate bnew worksheet no	
?>
<style type="text/css">
select {
width: 250;}
</style>	
<script type="text/javascript" src="../includes/validation2.js"></script>
<link rel="stylesheet" href="../includes/validation.css" type="text/css" media="screen" />
<script language="javascript" src="calendar.js"></script>
<link type="text/css" href="calendar.css" rel="stylesheet" />	

		<div  class="section">
		<div class="section-title">REPEAT WORKSHEET</div>
		<div class="xtop">
		<?php if ($st !="")
				{
				?> 
				<table>
				  <tr>
					<td style="width:auto" >
					<div class="error">
					<?php echo  '<strong>'.' <font color="#666600">'.$st.'</strong>'.' </font>';?>
					</div>
					</td>
				  </tr>
				</table>
				<?php } ?>
				<form  method="post" action="saverepeat.php" name="worksheetform">
		<table border="0" class="data-table">
			<tr >
		<td class="comment style1 style4">
		Worksheet No		</td>
		<td class="comment">
		  <span class="style5"><input name="worksheetno" type="text" id="worksheetno" value="<?php echo $worksheetno; ?>"  style="width:174px" readonly=""  /></span></td>
		<td  >Lot No </td>
		<td><div><input name="lotno" type="text" id="lotno" value=""  style="width:174px"  class="text" /><br />
		<span id="lotInfo"></span></div></td>
		<td>Date Cut</td>
		<td  colspan="2"><?php
	  $myCalendar = new tc_calendar("datecut", true, false);
	  $myCalendar->setIcon("../img/iconCalendar.gif");
	  $myCalendar->setDate(date('d'), date('m'), date('Y'));
	  $myCalendar->setPath("./");
	  $myCalendar->setYearInterval(2000, 2015);
	  $myCalendar->dateAllow('2008-05-13', '2015-03-01');
	  $myCalendar->setDateFormat('j F Y');
	  //$myCalendar->setHeight(350);	  
	  //$myCalendar->autoSubmit(true, "form1");
	  $myCalendar->writeScript();
	  ?></td>
		</tr>
		<tr >
		<td class="comment style1 style4">
		Date Created		</td>
		<td class="comment" ><?php $currentdate=date('d-M-Y'); echo  $currentdate ; //get current date ?></td>
				<td >HIQCAP Kit No</td>
		  <td><div>
		    <input name="hiqcap" type="text" id="hiqcap" value=""  style="width:174px" class="text" />
		    <br />
		  <span id="hiqcapInfo"></span></div></td>	
		  <td>Date Run</td>
		  <td colspan="2" ><?php
	  $myCalendar = new tc_calendar("daterun", true, false);
	  $myCalendar->setIcon("../img/iconCalendar.gif");
	  $myCalendar->setDate(date('d'), date('m'), date('Y'));
	  $myCalendar->setPath("./");
	  $myCalendar->setYearInterval(2000, 2015);
	  $myCalendar->dateAllow('2008-05-13', '2015-03-01');
	  $myCalendar->setDateFormat('j F Y');
	  //$myCalendar->setHeight(350);	  
	  //$myCalendar->autoSubmit(true, "form1");
	  $myCalendar->writeScript();
	  ?></td>
		</tr>
<tr >
		<td>
		Created By	    </td>
		<td>
	    <?php echo $creator; ?>		</td><td >
		Rack <strong>#</strong></td>
		<td><div><input name="rackno" type="text" id="rackno" value="<?php echo $rackno; ?>"  style="width:174px" class="text" /><br />
		<span id="rackInfo"></span></div></td>
		<td>Reviewed By </td>
		<td colspan="2"> N/A</td>
  </tr><tr >
		
		</tr>
		<tr >
		<td>
	  	  Spek Kit No		</td>
		<td  colspan="">
		  <input name="spekkitno" type="text" id="spekkitno" value=""  style="width:174px"  class="text"  /> </td>
		<td>KIT EXP</td>
		<td><?php
	  $myCalendar = new tc_calendar("kitexp", true, false);
	  $myCalendar->setIcon("../img/iconCalendar.gif");
	  //$myCalendar->setDate(date('d'), date('m'), date('Y'));
	  $myCalendar->setPath("./");
	  $myCalendar->setYearInterval(2000, 2015);
	  $myCalendar->dateAllow('2008-05-13', '2015-03-01');
	  $myCalendar->setDateFormat('j F Y');
	  //$myCalendar->setHeight(350);	  
	  //$myCalendar->autoSubmit(true, "form1");
	  $myCalendar->writeScript();
	  ?></td>
		<td>Date Reviewed </td>
		<td colspan="2">N/A</td>
		</tr>
			<tr >
		<td colspan="7" >&nbsp;
		</td>
		</tr><tr  bgcolor="#F0F3FA">
		
<?php
$pid= $_POST['pid'];
$scode= $_POST['scode'];
$batch= $_POST['batch'];

 foreach($_POST['checkbox'] as $i)
 {
 echo "<td bgcolor='#F0F3FA' >
<table border='0' class='data-table'  >
        <tr  >
<td  align='left'>Lab Code<input name='labcode[]' type='text' id='lastname' value='$scode[$i]' size='5' readonly=''> <img src='../html/image.php?code=code128&o=2&dpi=50&t=50&r=1&rot=0&text=$scode[$i]&f1=Arial.ttf&f2=6&a1=&a2=B&a3='  /></td>
        </tr>
</table>

</td>";

if ( (($i+1) % 6) == 0 )
 echo $newrow="</tr>
<tr>"; // change 6 to 8 and see
}
echo"
<td  >PC</td><td bgcolor='#F0F3FA' align='center'>NC</td>";
?>           
</tr>
<tr bgcolor="#999999">
            <td  colspan="7" bgcolor="#00526C" ><center>
              <input type="submit" name="Save" value="Save & Print Worksheet" class="button" />
            </center></td>
          </tr>
		  <tr>
			<td colspan="7"><input name="button" type='button' onclick='history.go(-1)' value='Back' class="button" /></td>
			</tr> 
</table>
	</form>
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>