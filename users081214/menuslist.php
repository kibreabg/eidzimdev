<?php 
require_once('../connection/config.php');
$success=$_GET['p'];
include('../includes/header.php');
?>

<div>
	<div class="section-title">MENUS LIST</div>
	
		<?php if ($success !="")
		{
		?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="success"><?php 
		
echo  '<strong>'.' <font color="#666600">'.$success.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
<?php } ?>		
	<!--*********************************************************** -->
	<?php
		$rowsPerPage = 15; //number of rows to be displayed per page

// by default we show first page
$pageNum = 1;

// if $_GET['page'] defined, use it as page number
if(isset($_GET['page']))
{
$pageNum = $_GET['page'];
}

// counting the offset
$offset = ($pageNum - 1) * $rowsPerPage;
			
		$showuser = "SELECT ID,name,url FROM menus LIMIT $offset, $rowsPerPage ";
		$displayusers = @mysql_query($showuser) or die(mysql_error());
		$no=mysql_num_rows($displayusers);
if ($no !=0)
{ 
	?>
	<table border="0"   class="data-table">
	<tr ><th>ID</th><th>Menu</th><th>URL</th><th>Task</th></tr>

	<?php	
		
		while(list($ID,$name,$url) = mysql_fetch_array($displayusers))
		{  
			echo "<tr class='even'>
					<td >$ID</td>
					<td >$name</td>
					<td >$url</td>
					<td ><a href=\"editmenus.php" ."?ID=$ID" . "\" title='Click to edit menu details'>Edit</a> 
</td>
					</tr>";
		}
	?>
		</table>
	<?php	
	
	echo '<br>';
	$numrows=GetTotalMenus(); //get total no of batches

	// how many pages we have when using paging?
	$maxPage = ceil($numrows/$rowsPerPage);

// print the link to access each page
$self = $_SERVER['PHP_SELF'];
$nav  = '';
for($page = 1; $page <= $maxPage; $page++)
{
   if ($page == $pageNum)
   {
      $nav .= " $page "; // no need to create a link to current page
   }
   else
   {
      $nav .= " <a href=\"$self?page=$page\">$page</a> ";
   }
}

// creating previous and next link
// plus the link to go straight to
// the first and last page

if ($pageNum > 1)
{
   $page  = $pageNum - 1;
   $prev  = " <a href=\"$self?page=$page\">[Prev]</a> ";

   $first = " <a href=\"$self?page=1\">[First Page]</a> ";
}
else
{
   $prev  = '&nbsp;'; // we're on page one, don't print previous link
   $first = '&nbsp;'; // nor the first page link
}

if ($pageNum < $maxPage)
{
   $page = $pageNum + 1;
   $next = " <a href=\"$self?page=$page\">[Next]</a> ";

   $last = " <a href=\"$self?page=$maxPage\">[Last Page]</a> ";
}
else
{
   $next = '&nbsp;'; // we're on the last page, don't print next link
   $last = '&nbsp;'; // nor the last page link
}

// print the navigation link
echo '<center>'. ' Page ' .$first . $prev . $nav . $next . $last .'</center>';

	
	}

else
{
?>
<table   >
  <tr>
    <td style="width:auto" ><div class="notice"><?php 
		
echo  '<strong>'.' <font color="#666600">'.'No Menus Added'.'</strong>'.' </font>';

?></div></th>
  </tr>
</table><?php
}  
  ?>  
	<!--***********************************************************	 -->
		
		
</div>

		
 <?php include('../includes/footer.php');?>