<?php
require_once('../connection/config.php');?>

<table class="data-table">
	<tr>
		<td>
			
		</td>
		<td> | </td>
		<td>
	</tr>
	<?php if ((samplesawaitingtests()) > 0) 
			{
			?>
	<tr>
		<td colspan="6"><small><strong><font color="#FF0000">The samples have been ordered by Date Received Aecending (Oldest - Earliest)</font></strong></small>
		</td>
	</tr><?php 
			}?>
</table>
