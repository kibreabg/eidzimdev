<?php 
session_start();
require_once('connection/config.php');
require_once('classes/tc_calendar.php');
include("header.php");

$labss=$_SESSION['lab'];

$currentdate = date('Y'); //show the current year
//$lowestdate = GetAnyDateMin(); //get the lowest year from date received
//$maximumyear = GetMaxYear();

if ($_REQUEST['reset'])
{
	 echo '<script type="text/javascript">' ;
				echo "window.location.href='labreportsp.php'";
				echo '</script>';
}
else
{

}

//if generate report button clicked

 if ($_REQUEST['generatereport'])
{
	//get report type variable
		$reporttype = $_GET['reporttype'];
	//get the select option i.e facility or  province
		$selectoption = $_GET['selectoption'];
	//get the selected reporting period
		$period= $_GET['period'];
		
		if ($selectoption > 0 ) // display the selected option
		{
				if ($selectoption == 1) //facility
				{
						$facility=$_GET['fcode'];
						if ($period ==1) // weekly
						{
							$startdate = $_GET['startdate'];
							$enddate = $_GET['enddate'];	
							
					
						echo '<script type="text/javascript">' ;
						echo "window.location.href='parternweeklyexcelreports.php?startdate=$startdate&enddate=$enddate&facility=$facility&province=0'";
						echo '</script>';
						exit();		
							
						}
						elseif ($period ==2) //monthly
						{
							$monthly = $_GET['monthly'];
							$monthyear = $_GET['monthyear'];
							
							
						echo '<script type="text/javascript">' ;
						echo 					"window.location.href='parternmonthlyexcelreports.php?monthly=$monthly&monthyear=$monthyear&facility=$facility&province=0'";
						echo '</script>';
						exit();
							
		
						}
						elseif ($period ==3)//quaretly
						{
							$quarterly = $_GET['quarterly'];
							$quarteryear = $_GET['quarteryear'];
							
						echo '<script type="text/javascript">' ;
						echo 					"window.location.href='parternquarterlyexcelreports.php?quarterly=$quarterly&quarteryear=$quarteryear &facility=$facility&province=0'";
						echo '</script>';
						exit();
							
					
						}
						elseif ($period ==4)//bi-annual
						{
							$biannual = $_GET['biannual'];
							$biannualyear = $_GET['biannualyear'];
							
						echo '<script type="text/javascript">' ;
						echo "window.location.href='parternbiannualexcelreports.php?biannual=$biannual&biannualyear=$biannualyear &facility=$facility&province=0'";
						echo '</script>';
						exit();	
							
						}
						elseif ($period ==5)//annual
						{
							$yearly = $_GET['yearly'];
							
						echo '<script type="text/javascript">' ;
						echo "window.location.href='parterannualexcelreports.php?yearly=$yearly&facility=$facility&province=0'";
						echo '</script>';
						exit();		
							
						}//end if period
				}
				elseif ($selectoption == 2) //province
				{		$province=$_GET['province'];
						if ($period ==1) // weekly
						{
							$startdate = $_GET['startdate'];
							$enddate = $_GET['enddate'];	
							
							
						echo '<script type="text/javascript">' ;
						echo "window.location.href='parternweeklyexcelreports.php?startdate=$startdate&enddate=$enddate&facility=0&province=$province'";
						echo '</script>';
						exit();		
							
						}
						elseif ($period ==2) //monthly
						{
							$monthly = $_GET['monthly'];
							$monthyear = $_GET['monthyear'];
							
						echo '<script type="text/javascript">' ;
						echo 					"window.location.href='parternmonthlyexcelreports.php?monthly=$monthly&monthyear=$monthyear&facility=0&province=$province'";
						echo '</script>';
						exit();
							
		
						}
						elseif ($period ==3)//quaretly
						{
							$quarterly = $_GET['quarterly'];
							$quarteryear = $_GET['quarteryear'];
							
						echo '<script type="text/javascript">' ;
						echo 					"window.location.href='parternquarterlyexcelreports.php?quarterly=$quarterly&quarteryear=$quarteryear &facility=0&province=$province'";
						echo '</script>';
						exit();
							
					
						}
						elseif ($period ==4)//bi-annual
						{
							$biannual = $_GET['biannual'];
							$biannualyear = $_GET['biannualyear'];
							
						echo '<script type="text/javascript">' ;
						echo "window.location.href='parternbiannualexcelreports.php?biannual=$biannual&biannualyear=$biannualyear &facility=0&province=$province'";
						echo '</script>';
						exit();	
							
						}
						elseif ($period ==5)//annual
						{
							$yearly = $_GET['yearly'];
							
						echo '<script type="text/javascript">' ;
						echo "window.location.href='parterannualexcelreports.php?yearly=$yearly&facility=0&province=$province'";
						echo '</script>';
						exit();		
							
						}//end if period
						
				}
		}
		else //display all
		{
			
			if ($period ==1) // weekly
						{
							$startdate = $_GET['startdate'];
							$enddate = $_GET['enddate'];	
							
							
						echo '<script type="text/javascript">' ;
						echo "window.location.href='parternweeklyexcelreports.php?startdate=$startdate&enddate=$enddate&facility=0&province=0'";
						echo '</script>';
						exit();		
							
						}
						elseif ($period ==2) //monthly
						{
							$monthly = $_GET['monthly'];
							$monthyear = $_GET['monthyear'];
							
						echo '<script type="text/javascript">' ;
						echo 					"window.location.href='parternmonthlyexcelreports.php?monthly=$monthly&monthyear=$monthyear&facility=0&province=0'";
						echo '</script>';
						exit();
							
		
						}
						elseif ($period ==3)//quaretly
						{
							$quarterly = $_GET['quarterly'];
							$quarteryear = $_GET['quarteryear'];
							
						echo '<script type="text/javascript">' ;
						echo 					"window.location.href='parternquarterlyexcelreports.php?quarterly=$quarterly&quarteryear=$quarteryear &facility=0&province=0'";
						echo '</script>';
						exit();
							
					
						}
						elseif ($period ==4)//bi-annual
						{
							$biannual = $_GET['biannual'];
							$biannualyear = $_GET['biannualyear'];
							
						echo '<script type="text/javascript">' ;
						echo "window.location.href='parternbiannualexcelreports.php?biannual=$biannual&biannualyear=$biannualyear &facility=0&province=0'";
						echo '</script>';
						exit();	
							
						}
						elseif ($period ==5)//annual
						{
							$yearly = $_GET['yearly'];
							
						echo '<script type="text/javascript">' ;
						echo "window.location.href='parterannualexcelreports.php?yearly=$yearly&facility=0&province=0'";
						echo '</script>';
						exit();		
							
						}//end if period
		
		}

}
?>
<style type="text/css">
select {
width: 250;}
</style>	
<script>
		window.dhx_globalImgPath="../img/";
	</script>
<script type="text/javascript" src="../includes/validation.js"></script>
<script language="javascript" src="calendar.js"></script>


<link rel="stylesheet" href="../includes/validation.css" type="text/css" media="screen" />

<link type="text/css" href="calendar.css" rel="stylesheet" />	
<link href="users/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
 
  <script src="jquery-ui.min.js"></script>
  <link rel="stylesheet" href="demos.css">
 <script language="javascript" type="text/javascript">
// Roshan's Ajax dropdown code with php
// This notice must stay intact for legal use
// Copyright reserved to Roshan Bhattarai - nepaliboy007@yahoo.com
// If you have any problem contact me at http://roshanbh.com.np
function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
    }
	
	function getperiod(period) {		
		
		var strURL="findtype.php?reportperiod="+period;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('statediv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
						
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
	
function getdropdown(selectoption) {		
		
		var strURL="finddropdown.php?selectoption="+selectoption;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('stateediv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
						
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
</script>
<script>
		window.dhx_globalImgPath="../img/";
	</script>
<script src="users/dhtmlxcombo_extra.js"></script>
 <link rel="STYLESHEET" type="text/css" href="users/dhtmlxcombo.css">
  <script src="users/dhtmlxcommon.js"></script>
  <script src="users/dhtmlxcombo.js"></script>

<div  class="section">
		
		  <div class="section-title" style="width:1120px">REPORTS</div>
		<div class="xtop">
		<?php if ($success !="")
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
   <form id="customForm" method="get" action="" target="_blank" >
       <table  border="1" style="border-color:#CCCCCC" width="1000px" >
       	<tr>
		<td colspan="2" style="font-family:Verdana, Arial, Helvetica, sans-serif"><font color="#FF0000">Please select facility <strong><u>or</u></strong> province report to generate the report.</font></td>
		</tr>
		  <tr >
          
            <td height="50" width="100" colspan="" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">
			<label>
			<input name="selectoption" type="radio" value="1" onFocus="getdropdown(this.value)"  />&nbsp;<strong>Select Facility</strong></label>
			<br /><br />
			
			</td>
         	 <td width="150">
			 <select  style="width:230px"  id='fcode' name="fcode">
    
  </select>  
  <script>
    var combo = dhtmlXComboFromSelect("fcode");
	combo.enableFilteringMode(true,"findpartnerfacilities.php",true);
	

</script>

</td>
          </tr>
		    <tr >
          
            <td height="50" width="100" colspan="" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">
			
			<label>
			<input name="selectoption" type="radio" value="2" onFocus="getdropdown(this.value)"/>&nbsp;<strong>Select Province</strong></label>
			</td>
         	 <td width="150">
			

<div><span id="stateediv"></span></div></td>
          </tr>
	
	
	<tr>
	<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td height="50" colspan="" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2"><strong>Select Period </strong></td>
	 <td>
	 	<label>
			<input name="period" type="radio" value="1" onFocus="getperiod(this.value)"/>&nbsp;Weekly&nbsp;&nbsp;&nbsp;&nbsp;</label>
		<label>
			<input name="period" type="radio" value="2" onFocus="getperiod(this.value)"/>&nbsp;Monthly&nbsp;&nbsp;&nbsp;&nbsp;</label>
		<label>
			<input name="period" type="radio" value="3" onFocus="getperiod(this.value)"/>&nbsp;Quarterly&nbsp;&nbsp;&nbsp;&nbsp;</label>
		<label>
			<input name="period" type="radio" value="4" onFocus="getperiod(this.value)"/>&nbsp;Bi-Annually&nbsp;&nbsp;&nbsp;&nbsp;</label>
		<label>
			<input name="period" type="radio" value="5" onFocus="getperiod(this.value)"/>&nbsp;Annually</label>
		<br /><div id="statediv"></div>
	 </td>
	</tr>
	
	
	<tr>
	<td>&nbsp;</td>
	<td height="20" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">
	<div align="center">
	<input type="submit" name="generatereport" value="Generate Report" class="button" />&nbsp;&nbsp;&nbsp;
	<input type="submit" name="reset" value="Reset Options" class="button" />
	</div></td>
	</tr>
	  </table>
	     </form>
		 
		 
	    </div>
		</div>
		
 <?php include('footer.php');?>