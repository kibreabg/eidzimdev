
<?php
#### Roshan's Ajax dropdown code with php
#### Copyright reserved to Roshan Bhattarai - nepaliboy007@yahoo.com
#### if you have any problem contact me at http://roshanbh.com.np
#### fell free to visit my blog http://roshanbh.com.np
?>

<?php 
require('../connection/config.php');
$arv = intval($_GET['arv']);
$mart = intval($_GET['mart']);
$tested = intval($_GET['tested']);
$itested = intval($_GET['itested']);
$entry = intval($_GET['entry']);
$reason = intval($_GET['reason']);
$feeding = intval($_GET['feeding']);
$receivearv = intval($_GET['receivearv']);

//get the feeding options
if ($feeding == 1) //yes then show EBF, MBF, RBF
{
		$d9=mysql_query("SELECT ID,description,name FROM feedings where ID between 2 and 4")or die(mysql_error());
		while(list($mepID,$mepname,$desc)=mysql_fetch_array($d9))
		{
		?>
		  <input type="radio" name="mbfeeding" id="mbfeeding" value="<?php echo $mepID;?>" />
		  <font color="#000000"><?php echo $mepname.' [ '.$desc.' ]';?>&nbsp;</font>
	<?php
		}
		?><span id="mbfeedingInfo"></span>
<?php
}
else if ($feeding == 2) //no then show NBF
{
	$d91=mysql_query("SELECT ID,description,name FROM feedings where ID = 6")or die(mysql_error());
		while(list($mepID,$mepname,$desc)=mysql_fetch_array($d91))
		{
		?>
		  <input type="radio" name="mbfeeding" id="mbfeeding" value="<?php echo $mepID;?>" checked="checked" />
		  <font color="#000000"><?php echo $mepname.' [ '.$desc.' ]';?>&nbsp;</font>
	<?php
		}
		?><!--<span id="mbfeedingInfo"></span> -->
<?php
}
else if ($feeding == 3) //unk then show Unk
{
}


//get reason for test
if ($reason == 4)
{?>
	&nbsp;<em>Please Specify</em>&nbsp;&nbsp;<input type="text" class="text" name="othertest" size="30" />
<?php
}

//get entry point other
if ($entry == 6)
{?>
	&nbsp;<em>Please Specify</em>&nbsp;&nbsp;<input type="text" class="text" name="otherentry" size="30" />
<?php
}
//get the infant tested before
if ($itested == 1) //yes
{?>
			<p><em>If <strong><u><font color="#FF0000">Yes</font></u></strong>, what was the result?</em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

					<?php
					$resultquery = "SELECT ID,name FROM results";
					
					$showresult = mysql_query($resultquery) or die('Error, query failed'); //onchange='submitForm();'
					
					echo "<select name='infanthivstatus' id='infanthivstatus' style='width:110px';>\n";
					echo " <option value=''> Select One </option>";
					
					while ($srow = mysql_fetch_array($showresult))
					{
						$ID = $srow['ID'];
						$name = $srow['name'];
						echo "<option value='$ID'> $name</option>\n";
					}
					echo "</select>\n";
					?> </p>
					
				<p>
				<em>If <strong><u><font color="#FF0000">Yes</font></u></strong>, what type of test was it? </em>&nbsp;&nbsp;
				   <input name="testtype" type="radio" value="DNA PCR" /> 
					<font color="#000000"> DNA PCR</font>
					&nbsp;
					<input name="testtype" type="radio" value="RAPID HIV" /><font color="#000000">
					Rapid HIV </font>
				</p>	
			
				<p>		
				<em>If <font color="#0000FF"><strong>DNA PCR</strong></font>, <small>give original patient Lab Request No</small></em> &nbsp;
				<strong><font color="#000000"><small>Year</small></font></strong>&nbsp;
				 <input type="text" name="originalrequestno_year" size="3" class="text" id="Input" value=""/>
				 &nbsp; <strong><font color="#000000"><small>No</small></font></strong>&nbsp;
				 <input type="text" name="originalrequestno_no" size="5" class="text" id="Input" value=""/>
				</p>
			
<?php
}
else if (($itested == 2)||($itested == 3)) //no or unk
{
}

//get the mother hiv status
if ($tested == 1) //yes
{?>
	<img src="../img/red.png" /> <em>If <strong><u><font color="#FF0000">Yes</font></u></strong>, What was the result? </em>
	&nbsp;
		<?php
		$entryquery = "SELECT ID,name FROM results where ID !=3 AND ID !=5 ";
		
		$result = mysql_query($entryquery) or die('Error, query failed'); //onchange='submitForm();'
		
		echo "<select name='mhivstatus' id='mhivstatus' style='width:188px';>\n";
		echo " <option value=''> Select One </option>";
		
		while ($row = mysql_fetch_array($result))
		{
			$ID = $row['ID'];
			$name = $row['name'];
			echo "<option value='$ID'> $name</option>\n";
		}
		echo "</select>\n";
		?>   
		<span id="mhivstatusInfo"></span>
	
<?php
}
else if ( ($tested == 2) || ($tested == 3) ) //no or unk
{
}

//get the child arv
if ($arv == '1')   //yes
{
?>
		<img src="../img/red.png" /><em> If <strong><u><font color="#FF0000">Yes</font></u></strong>, what did the<br />
				    infant receive?</em>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					
					<?php
					$prquery = "SELECT ID,name FROM prophylaxis WHERE ptype=2 order by name";
				
					$prresult = mysql_query($prquery) or die('Error, query failed');
		
					echo "<select name='infantprophylaxis' id='infantprophylaxis' style='width:130px' ;>\n";
					echo " <option value=''> Select One </option>";
					  while ($prrow = mysql_fetch_array($prresult))
					  {
							 $prID = $prrow['ID'];
							$prname = $prrow['name'];
						echo "<option value='$prID'> $prname</option>\n";
					  }
				  echo "</select>\n";
				  ?>
					
					<!--<span id="infantprophylaxisInfo"></span> -->					
	
	<?php
}
else if (($arv == '2') || ($arv == '3')) //no or unk
{

}

//check the mother arv
if ($mart == '2') //no
{?>
	<img src="../img/red.png" />&nbsp;<em>If <strong><u><font color="#FF0000">No</font></u></strong>, did mother receive<br />
                ARV prophylaxis?</em>&nbsp;&nbsp;&nbsp;
              <input name="receivearv" type="radio" value="1" onChange='getMotherProph(this.value)'/>
               <font color="#000000">Yes&nbsp;</font>
                <input name="receivearv" type="radio" value="2" onChange='getMotherProph(this.value)'/>
                <font color="#000000">No&nbsp;</font>	
                <input name="receivearv" type="radio" value="3" onChange='getMotherProph(this.value)'/>
                <font color="#000000">Unk </font>
<?php
}
else if ($mart == '1') //yes
{
				 
}
else if ($mart == '3') //unk
{
}

//check the mother receive arv
if ($receivearv == '2') //no
{
}
else if ($receivearv== '1') //yes
{?>
	<img src="../img/red.png" />&nbsp;<em>If <strong><u><font color="#FF0000">Yes</font></u></strong>, what did the <br />
                mother receive?</em>&nbsp;&nbsp;&nbsp;&nbsp;
				
					<?php
					$mrquery = "SELECT ID,name FROM prophylaxis WHERE ptype=1 order by name";
				
					$mrresult = mysql_query($mrquery) or die('Error, query failed');
		
					echo "<select name='mdrug' id='mdrug' style='width:110px' ;>\n";
					echo " <option value=''> Select One </option>";
					  while ($mrrow = mysql_fetch_array($mrresult))
					  {
							 $mrID = $mrrow['ID'];
							$mrname = $mrrow['name'];
						echo "<option value='$mrID'> $mrname</option>\n";
					  }
				  echo "</select>\n";
				 
}
else if ($receivearv == '3') //unk
{
}
?>

