<?php 
require_once('../connection/config.php');
include('../includes/header.php');

$searchparameter = ltrim(rtrim($_POST['search'])); //get the search parameter from the userheader and trim the value

?>

<div>
	<div class="section-title">SEARCH RESULTS FOR <strong><em><?php echo $searchparameter?></em></strong></div>
	<div>
	<?php 
	if (!($searchparameter=='')) //display search results if search parameter is NOT NULL
	{
	?>
		
		<!--start the search*********************************************************** -->
		<?php		
			
			//////////////////SEARCH FOR FACILITY///////////////////////////////
			$showfacility = "SELECT LTRIM(RTRIM(name)),facilitycode,ftype,lab,district,telephone,fax,contactperson,contacttelephone,email FROM facilitys WHERE name LIKE '%$searchparameter%' AND flag = 1";
			$displayfacility = @mysql_query($showfacility) or die(mysql_error());
			
			$showfacilitycount = mysql_num_rows($displayfacility);//get the search count			
			//////////////////END SEARCH FOR FACILITY///////////////////////////////
			
			//////////////////SEARCH FOR DISTRICT///////////////////////////////
			$showdistrict = "SELECT LTRIM(RTRIM(name)),province,comment FROM districts WHERE name LIKE '%$searchparameter%' AND flag = 1";
			$displaydistrict = @mysql_query($showdistrict) or die(mysql_error());
			
			$showdistrictcount = mysql_num_rows($displaydistrict);//get the search count
			//////////////////END SEARCH FOR DISTRICT///////////////////////////////
			
			//////////////////SEARCH FOR USERS///////////////////////////////
			$showuser = "SELECT LTRIM(RTRIM(surname)),username,account,LTRIM(RTRIM(oname)),telephone,email FROM users WHERE surname LIKE '%$searchparameter%' OR oname LIKE '%$searchparameter%' AND flag = 1";
			$displayuser = @mysql_query($showuser) or die(mysql_error());
			
			$showusercount = mysql_num_rows($displayuser);//get the search count
			//////////////////END SEARCH FOR USERS///////////////////////////////
			
			///////////////SEARCH VIEWS/////////////////////////////////////////
			if (($showfacilitycount == 0) && ($showdistrictcount == 0)  && ($showusercount == 0))
			{
				echo "Your search for <strong><em><big>".$searchparameter."</big></em></strong> did not match any records. Please try again.";
			}
			else
			{
				if ($showfacilitycount!=0)
				{
					echo "<br/><strong>Facility </strong>Search returned <strong>".$showfacilitycount."</strong> results";	
					echo "&nbsp;&nbsp;<small><a href='facilitieslist.php?search=$searchparameter'>View Results</a></small><br/><br/>";
					echo "<hr size=1>";
					
				}
				if ($showdistrictcount!=0)
				{
					echo "<br/><strong>District </strong>Search returned <strong>".$showdistrictcount."</strong> results";
					echo "&nbsp;&nbsp;<small><a href='districtslist.php?search=$searchparameter'>View Results</a></small><br/><br/>";	
					echo "<hr size=1>";
				}
				if ($showusercount!=0)
				{
					echo "<br/><strong>User </strong>Search returned <strong>".$showusercount."</strong> results";
					echo "&nbsp;&nbsp;<small><a href='userslist.php?search=$searchparameter'>View Results</a></small><br/><br/>";	
					echo "<hr size=1>";
				}
			}
			///////////////END SEARCH VIEWS/////////////////////////////////////////
		?>
		<!--*************************************************************************** -->
	<?php
	}
	else //show message if the search parameter is null
	{
		echo "<center><strong>Please enter a valid record to search.</strong></center>";
		exit();
	}
	?>	
	</div>	
</div>

		
 <?php include('../includes/footer.php');?>