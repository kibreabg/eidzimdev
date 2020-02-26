<?php
include("../connection/config.php");
$artoption==intval($_GET['artoption']);
if (!$artoption) return;

if ($artoption=1)
{?>
	<tr>
	
		<td>If yes, what did the mother receive? </td>
		<td>
		<?php
			$motherquery = "SELECT ID,name FROM prophylaxis WHERE ptype=1 ";
			
			$result = mysql_query($motherquery) or die('Error, query failed'); //onchange='submitForm();'
			
			echo "<select name='mdrug' id='mdrug' style='width:188px';>\n";
			echo " <option value=''> Select One </option>";
	
			while ($row = mysql_fetch_array($result))
			{
				$ID = $row['ID'];
				$name = $row['name'];
				echo "<option value='$ID'> $name</option>\n";
			}
		echo "</select>\n";
		?></td>
	</tr>
<?php
}
else if ($artoption=2)
{?>
	<tr>
		<td>If No, did mother receive ARV Prophylaxis? </td>
		<td>
		<input name="proph" type="radio" value="Y" />
		Yes&nbsp;
		<input name="proph" type="radio" value="N" />
		No </td>
	</tr>
<?php
}
?>
