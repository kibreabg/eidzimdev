
<?php
$email="nam.e.,;'";
$email=filter_var($email, FILTER_SANITIZE_EMAIL);	
echo $email;
#### Roshan's Ajax dropdown code with php
#### Copyright reserved to Roshan Bhattarai - nepaliboy007@yahoo.com
#### if you have any problem contact me at http://roshanbh.com.np
#### fell free to visit my blog http://roshanbh.com.np
?>

<?php 
require('../connection/config.php');
$tested = intval($_GET['tested']);
if ($tested == 'Y')   //yes
{
?>
		<td><span class="mandatory">*</span> <em>If Yes, What was the result? </em></td>
					<td colspan=""><div><?php
						$entryquery = "SELECT ID,name FROM results where (ID !=3 ) ";
						
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
						?>   <span id="mhivstatusInfo"></span></div>      </td>				
	
	<?php
}
else if (($tested == 'N') ) //no or unk
{

}

?>

