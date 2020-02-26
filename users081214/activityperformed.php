<?php
require_once('../connection/config.php');
//include('../includes/functions.php');
	//check which activity it is
			if (($task == 1) || ($task == 3) || ($task == 4)) // concerns add, delete, approve sample then get sample accession no
			{ 	//get sample that has the accession no
				$staskquery=mysql_query("SELECT ID FROM samples where ID='$patient' ")or die(mysql_error()); 
				$sdd=mysql_fetch_array($staskquery);
				$patientid=$sdd['ID'];

				$ulink= 'sample_details.php?ID='.$patientid;

				$recordtype = 'Request No';
				$record = $patient;
			}
			
			else if ($task == 2) //edit sample show which record
			{
				//get sample that has the accession no
				$astaskquery=mysql_query("SELECT patient FROM samples where ID=$patient")or die(mysql_error()); 
				$asdd=mysql_fetch_array($astaskquery);
				$sampleid=$asdd['patient'];

				$ulink= 'sample_details.php?ID='.$patient;
				$recordtype = 'Request No';
				$record = $sampleid;
			}
			
			else if (($task == 5) || ($task == 6) || ($task == 7) || ($task == 8)) // create, flag, update, review worksheet then show worksheet no
			{ 	$ulink= 'worksheetdetails.php?ID='.$patient;
				$recordtype = 'Worksheet No';
				$record = $patient;
			}
			
			else if (($task == 9) || ($task == 23)) //dispatch, release samples then get the batch no
			{ 	$ulink= 'BatchDetails.php?ID='.$patient;
				$recordtype = 'Batch No';
				$record = $patient;
			}
			
			else if (($task == 16) || ($task == 17) || ($task == 18)) //add,edit,delete facility
			{ 	$ulink= 'facilitydetails.php?ID='.$patient;
				$recordtype = 'Health Unit';
				$record = GetFacility($patient);
				//$record = GetFacilityName($patient);
			}
			
			else if (($task == 19) || ($task == 20) || ($task == 21)) //add,edit,delete district
			{ 	$ulink= '#';
				$recordtype = 'District';
				$record = GetDistrictName($patient);
			}

			else if (($task == 12) || ($task == 13) || ($task == 14) || ($task == 15)) //add,edit,deactivate,activate user
			{ 	$ulink= '#';
				$recordtype = 'User';
				$userinfo = GetUserInfo($patient);
				$record = $userinfo['surname'].' '.$userinfo['oname'];
			}
			?>