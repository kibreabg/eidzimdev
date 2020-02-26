<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment;Filename=NMRL_National_REPORT.xls");

require_once('../connection/config.php');
require_once('../includes/functions.php');
require_once("../Pnationaldashboardfunctions.php");
//get the filter count for the weekly reports
	
$labss=$_SESSION['labss'];
$mwaka=$_GET['year'];
$mwezi=$_GET['mwezi'];
$displaymonth=GetMonthName($mwezi);
if (isset($mwaka))
{
	if (isset($mwezi))
	{
	$defaultmonth=$displaymonth .' - '.$mwaka ; //get current month and year
	$currentmonth=$mwezi;
	$currentyear=$mwaka;
	}
	else
	{
	$defaultmonth=$mwaka ; //get current month and year
	$currentmonth=0;
	//get current month and year
	$currentyear=$mwaka;
	
	}
}
else
{
$defaultmonth=date("M-Y"); //get current month and year
$currentmonth=date("m");
$currentyear=date("Y");

}
	//$natAge=Getoverallaverageage($currentyear,$currentmonth);

$rs4 = mysql_query( "CALL Getoverallaverageage($currentyear,$currentmonth, @averageage)" );
$rs4 = mysql_query( "SELECT @averageage as 'averageage'" );
$d4=mysql_fetch_array($rs4);
$natAge=round($d4['averageage'],1);

//overall tested samples


$rst = mysql_query( "CALL Gettestedsamplescount($currentyear,$currentmonth, @numsamples)" );
$rst = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dt=mysql_fetch_array($rst);
$overalltestedsamples=$dt['numsamples'];


//overall tested samples less than 2 months
$rss = mysql_query( "CALL Gettestedsamplescountlessthan2months($currentyear,$currentmonth, @numsamples)" );
$rss = mysql_query( "SELECT @numsamples as 'numsamples'" );
$ds=mysql_fetch_array($rss);
$overalltestedsamplesless2months=$ds['numsamples'];

//overall tested samples (first test only)
$rs7 = mysql_query( "CALL Getoverallfirsttestedsamples($currentyear,$currentmonth, @numsamples)" );
$rs7 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dt7=mysql_fetch_array($rs7);
$overallfirsttestedsamples=$dt7['numsamples'];

//overall tested samples (confirmatory at 9months)
$rstu = mysql_query( "CALL Getoverallconfirmatorytestedsamples($currentyear,$currentmonth, @numsamples)" );
$rstu = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dtu=mysql_fetch_array($rstu);
$overallconfirmatorytestedsamples=$dtu['numsamples'];

  //tested samples overall result negative
$rsu = mysql_query( "CALL Getnationaloutcome($currentyear,$currentmonth,1, @numsamples)" );
$rsu = mysql_query( "SELECT @numsamples as 'numsamples'" );
$du=mysql_fetch_array($rsu);
$negative=$du['numsamples'];
//negative percentage
if  ($negative !=0)
{
$negpercentages=round((($negative/$overalltestedsamples)*100),1);
}
else
{
$negpercentages=0;
}

//tested samples overall result positive
$rsi = mysql_query( "CALL Getnationaloutcome($currentyear,$currentmonth,2, @numsamples)" );
$rsi = mysql_query( "SELECT @numsamples as 'numsamples'" );
$di=mysql_fetch_array($rsi);
$positive=$di['numsamples'];
//positive percentage
if  ($positive !=0)
{
$pospercentages=round((($positive/$overalltestedsamples)*100),1);
}
else
{
$pospercentages=0;
}

//tested samples overall result failed
$rsr = mysql_query( "CALL Getnationaloutcome($currentyear,$currentmonth,3, @numsamples)" );
$rsr = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dr=mysql_fetch_array($rsr);
$fail=$dr['numsamples'];
//positive percentage
if  ($fail !=0)
{
$failpercentages=round((($fail/$overalltestedsamples)*100),1);
}
else
{
$failpercentages=0;
}

//tested samples overall result failed
$rsr = mysql_query( "CALL Getnationaloutcome($currentyear,$currentmonth,5, @numsamples)" );
$rsr = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dr=mysql_fetch_array($rsr);
$indeter=$dr['numsamples'];
//positive percentage
if  ($fail !=0)
{
$indeterpercentages=round((($indeter/$overalltestedsamples)*100),1);
}
else
{
$indeterpercentages=0;
}

//overall rejected samples
$rsw = mysql_query( "CALL Getnationalrejectedsamples($currentyear,$currentmonth, @numsamples)" );
$rsw = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dw=mysql_fetch_array($rsw);
$overallrejectedsamples=$dw['numsamples'];

?>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center"><strong>MINISTRY OF HEALTH &amp; CHILD WELFARE ZIMBABWE</strong></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center"><strong>Summary Report for <?php echo $defaultmonth;?></strong></td><br />
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
    <br /><br />
    <table  border="1" cellspacing="1" cellpadding="2">
      <tr align="center" valign="middle">
        <td colspan="6"><strong>National Statistics</strong></td>
        </tr>
      <tr style="font-size:12px;" align="center" valign="middle">
        <td rowspan="2"><strong>No. of PCR Tests</strong></td>
        <!--<td colspan="2"><strong>No. of DNA PCR Test</strong></td>-->
        <td rowspan="2"><strong>No. of Rejected Samples</strong></td>
        <td rowspan="2"><strong>No. of Redraw Samples</strong></td>
        <td rowspan="2"><strong>Avg. Age of Testing</strong></td>
        <td colspan="2"><strong>Total No. of </strong></td>
        </tr>
      <tr style="font-size:12px;">
       <!-- <td align="center"><strong>1st Test</strong></td>
        <td align="center"><strong>2nd Test</strong></td>-->
        <td align="center"><strong>Health Facilities</strong></td>
        <td align="center"><strong>EID Facilities</strong></td>
      </tr>
      <tr>
        <td><?php echo  $overalltestedsamples; ?></td>
        <!--<td><?php //echo  $overallfirsttestedsamples; ?></td>
        <td><?php //echo  $overallconfirmatorytestedsamples; ?></td>-->
        <td><?php echo  $overallrejectedsamples; ?></td>
        <td><?php echo  $indeter ; ?></td>
        <td><?php 
	//OVERALL AGE OF TESTING 
	echo  $natAge . ' Months '; ?></td>
        <td><?php echo Gettotalsites(0,0); ?></td>
        <td><?php echo GettotalEIDsites(0,0); ?></td>
      </tr>
    </table>
    <br />
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
    <?php
	
	//EID Result
	 //tested samples overall result negative
$rsu = mysql_query( "CALL Getnationaloutcome($currentyear,$currentmonth,1, @numsamples)" );
$rsu = mysql_query( "SELECT @numsamples as 'numsamples'" );
$du=mysql_fetch_array($rsu);
$negative=$du['numsamples'];

//tested samples overall result positive
$rsi = mysql_query( "CALL Getnationaloutcome($currentyear,$currentmonth,2, @numsamples)" );
$rsi = mysql_query( "SELECT @numsamples as 'numsamples'" );
$di=mysql_fetch_array($rsi);
$positive=$di['numsamples'];

//tested samples overall result failed
$rsr = mysql_query( "CALL Getnationaloutcome($currentyear,$currentmonth,3, @numsamples)" );
$rsr = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dr=mysql_fetch_array($rsr);
$fail=$dr['numsamples'];

//overall rejected samples
$rsw = mysql_query( "CALL Getnationalrejectedsamples($currentyear,$currentmonth, @numsamples)" );
$rsw = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dw=mysql_fetch_array($rsw);
$overallrejectedsamples=$dw['numsamples'];

$totalrejectedfailed=$overallrejectedsamples + $fail;
$totaloutcome=$totalrejectedfailed + $positive + $negative;

if ($positive !=0)
{
$pospecentage=(($positive / $totaloutcome)* 100);
}
else
{
$pospecentage=0;
}


if ($negative !=0)
{
$negpecentage=(($negative / $totaloutcome)* 100);
}
else
{
$negpecentage=0;
}


if ($totalrejectedfailed !=0)
{
$rejpecentage=(($totalrejectedfailed / $totaloutcome)* 100);
}
else
{
$rejpecentage=0;
}
?>
    <table border="1" cellspacing="1" cellpadding="2">
  <tr align="center">
    <td colspan="3"><strong>EID Results</strong></td>
    </tr>
  <tr style="font-size:12px;" align="center" valign="middle">
    <td><strong>Negative</strong></td>
    <td><strong>Positive</strong></td>
    <td><strong>Rejected</strong></td>
  <!--  <td><strong>Failed</strong></td>-->
  </tr>
  <tr>
    <td><?php echo $negative   ; ?></td>
    <td><?php echo $positive  ;?></td>
    <td><?php echo $overallrejectedsamples   ;?></td>
   <!-- <td><?php //echo $fail   ;?></td>-->
  </tr>
</table>

    <br />
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
    <table border="1" cellpadding="2" cellspacing="1">
    <center><strong> EID Results By Entry Point </strong></center>
    <tr align="center" valign="middle">
    <?php 
      $sql="select ID,name from entry_points  ";
   $result=mysql_query($sql)or die(mysql_error());
    $result2=mysql_query($sql)or die(mysql_error());
	    $result3=mysql_query($sql)or die(mysql_error());
		$result4=mysql_query($sql)or die(mysql_error());
  $count=0; 
   while($row=mysql_fetch_array($result))
{	$count++;
   $entid=$row['ID'];
   		$entname=trim($row['name']);
		   	?>
			
<td colspan="2"><?php echo $entname; ?> </td>
 
 <?php } ?> 
 </tr>
 <tr>
 <?php  for($i=0;$i<$count;$i++)
{	?>
    <td>Negative</td>
    <td>Positive</td>
    <?php } ?>
  </tr>
<tr>

<?php while($row=mysql_fetch_array($result2))
{	?> <?php
   $entid=$row['ID'];
   		$entname=trim($row['name']);
		$rs2 = mysql_query( "CALL GetNationalResultbyEntrypoint($entid,1,$currentyear,$currentmonth, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$nega=$d2['numsamples'];
		   	?>
            <td><?php echo  $nega;?></td>
            <?php
			$rsq2 = mysql_query( "CALL GetNationalResultbyEntrypoint($entid,2,$currentyear,$currentmonth, @numsamples)" );
$rsq2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dq2=mysql_fetch_array($rsq2);
$posi=$dq2['numsamples'];
		   	?>
           <td> <?php echo $posi;?></td>
            
            
            
            <?php }?>
            
            
            

 
            
            </tr>
            </table>
			
<br />
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
    <table border="1" cellspacing="1" cellpadding="2">
  <tr>
    <td colspan="10" align="center"><strong>EID Results By Age Group </strong></td>
    </tr>
  <tr align="center" style="font-size:12px;"  valign="middle">
    <td colspan="2"><strong>Less than 2 Month</strong></td>
    <td colspan="2"><strong>2-9 Months</strong></td>
    <td colspan="2"><strong>9-18 Months</strong></td>
    <td colspan="2"><strong>&gt;18 Month</strong></td>
    <td colspan="2"><strong>No Age Data</strong></td>
    </tr>
  <tr align="center" style="font-size:12px;" valign="middle">
    <td><strong>Negative</strong></td>
    <td><strong>Positive</strong></td>
    <td><strong>Negative</strong></td>
    <td><strong>Positive</strong></td>
    <td><strong>Negative</strong></td>
    <td><strong>Positive</strong></td>
    <td><strong>Negative</strong></td>
    <td><strong>Positive</strong></td>
    <td><strong>Negative</strong></td>
    <td><strong>Positive</strong></td>
  </tr>
  <tr>
    <td><?php echo Getageless2monthspositivitycount(1,$currentyear,$currentmonth)?></td>
    <td><?php echo  Getageless2monthspositivitycount(2,$currentyear,$currentmonth);?></td>
    <td><?php echo Getagemore2monthsto9monthspositivitycount(1,$currentyear,$currentmonth)?></td>
    <td><?php echo Getagemore2monthsto9monthspositivitycount(2,$currentyear,$currentmonth);?></td>
    <td><?php echo Getagemore9to18positivitycount(1,$currentyear,$currentmonth);?></td>
    <td><?php echo Getagemore9to18positivitycount(2,$currentyear,$currentmonth);?></td>
    <td><?php echo Getageabove18positivitycount(1,$currentyear,$currentmonth);?></td>
    <td><?php echo Getageabove18positivitycount(2,$currentyear,$currentmonth);?></td>
    <td><?php echo Getagenullpositivitycount(1,$currentyear,$currentmonth);?></td>
    <td><?php echo Getagenullpositivitycount(2,$currentyear,$currentmonth);?></td>
  </tr>
</table>

    <br />
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
   
   
    
    <table border="1" cellpadding="2">
    <center><strong> EID Results By Province </strong></center>
    <tr align="center" valign="middle">
<?php 
      $sql="select Code,name from provinces   ";
  	 	$result=mysql_query($sql)or die(mysql_error());
    	$result2=mysql_query($sql)or die(mysql_error());
	    $result3=mysql_query($sql)or die(mysql_error());
		$result4=mysql_query($sql)or die(mysql_error());
$count=0;
   while($row=mysql_fetch_array($result))
	{	$count++;
   		$provid=$row['Code'];
   		$Prov=trim($row['name']);
		//$Prov=trim($row['Prov']);
		   	?>
			
 <td colspan="2"><?php echo $Prov;?></td> <?php } ?>
 </tr>
 <tr align="center" valign="middle" style="font-size:12px">
 <?php  for($i=0;$i<$count;$i++)
{	?>
    <td><strong>Negative</strong></td>
    <td><strong>Positive</strong></td>
    <?php } ?>
  </tr>
  
 <tr>

<?php while($row=mysql_fetch_array($result2))
{	
  		$provid=$row['Code'];
   		$prov=trim($row['name']);
		
		//negatives
$rs2 = mysql_query( "CALL Getprovinceresultcount($provid,$currentyear,$currentmonth,1, @numsamples)" );
$rs2 = mysql_query( "SELECT @numsamples as 'numsamples'" );
$d2=mysql_fetch_array($rs2);
$negatives=$d2['numsamples'];
		   	?>
           <td> <?php echo $negatives;?></td>
            <?php
            
            //tested samples per province by result type  positive
$rst = mysql_query( "CALL Getprovinceresultcount($provid,$currentyear,$currentmonth,2, @numsamples)" );
$rst = mysql_query( "SELECT @numsamples as 'numsamples'" );
$dt=mysql_fetch_array($rst);
$positives=$dt['numsamples'];
		   	?>
            <td><?php echo $positives;?></td>
            
            
			<?php }?>
            

 
            </tr>
            </table>
            
			
    
    
    
    
    
    
    
    </td>
  </tr>
  <tr>
    <td>&nbsp;  </td>
    <td>Ministry of Health &amp; Child Welfare Zimbabwe - <?php echo date("M-Y"); ?></td>
  </tr>
</table>
