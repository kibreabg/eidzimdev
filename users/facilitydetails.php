<?php 
session_start();
require_once('../connection/config.php');
include('../includes/header.php');
//get the facility information
$fcode=$_GET['ID'];
$facility = getfacilitydetails($fcode);
extract($facility);

//get select district name and province id	
		$distname=GetDistrictName($district);
		//get province ID
		$provid=GetProvid($district);
			//get province name	
		$provname=GetProvname($provid);
		$labname=GetLabNames($lab);//get lab name	
?>
<style type="text/css">
select {
width: 250;}
</style>
<script type="text/javascript" src="../includes/validatefacility.js"></script>
<link rel="stylesheet" href="../includes/validation.css" type="text/css" media="screen" />

		<link href="../style.css" rel="stylesheet" type="text/css" />
<div>
		<div class="section-title"><?php echo strtoupper($name); ?>  DETAILS</div>
  <div>
		
	<div class="xtop">
	<table>
			<tr>
				<td>
				<A HREF='javascript:history.back(-1)'><img src='../img/back.gif' alt='Go Back'/></A>
				</td>
				<td>&nbsp;|&nbsp;
				</td>
				<td>
				<A HREF='editfacility.php?ID=<?php echo $ID;?>'><strong>Edit Details</strong></A>
				</td>
			</tr>
		</table>
		
		</div>
	
	<!--		****************************************************************************** -->
		<form id="customForm"   method="get" action=""  >
        <table border="0" class="data-table">
          <tr>
            <th colspan="5">Facility Information </th>
          </tr>
          <tr class="even">
            <td > Code</td>
            <td colspan="3"><?php echo $facilitycode; ?></td>
          </tr>
          <tr class="even">
            <td> Name</td>
            <td colspan="3"><?php echo $name; ?></td>
            
          </tr>
          <tr class="even">
            <td > District</td>
            <td colspan="3"><?php echo $distname; ?></td>
          </tr>
		   <tr class="even">
            <td > Province</td>
            <td colspan="3"><?php echo $provname; ?></td>
          </tr>
          <tr class="even">
            <td> Laboratory</td>
            <td colspan="3"><div><?php
		   		echo $labname;
		  	?></td>
          </tr>
          <tr>
            <th colspan="4" >Facility Contact Details </th>
          </tr>
          <tr class="even">
            <td width="117">Postal Address </td>
            <td colspan="4"><?php echo $PostalAddress; ?></td>
          </tr>
		   <tr class="even">
            <td width="117">Physical Address </td>
            <td colspan="4"><?php echo $physicaladdress; ?></td>
          </tr>
          <tr class="even">
            <td>Telephone No. 1 </td>
            <td width='200'><?php echo $telephone; ?></td>
			<td >Telephone No. 2 </td>
            <td width='200'><?php echo $telephone2; ?></td>
          </tr>
          <tr class="even">
            <td>Fax </td>
            <td ><?php echo $fax; ?></td>
			<td>Email Address </td>
            <td ><?php echo $email; ?></td>
          </tr>
          <tr>
            <th colspan="4">Facility Contact Person </th>
          </tr>
          <tr class="even">
            <td width="117"> Full Name(s) </td>
            <td ><?php echo $contactperson; ?></td>
			<td>Contact Email Address </td>
            <td width="117"><?php echo $ContactEmail; ?></td>
          </tr>
          <tr class="even">
            <td> Contact Tel. No. 1 </td>
            <td ><?php echo $contactperson; ?></td>
			<td>Contact Tel. No. 2 </td>
            <td width="117"><?php echo $contacttelephone2; ?></td>
          </tr>
        </table>
	</form>
	<!--		****************************************************************************** -->
  </div>
<!--</div> -->
		
 <?php include('../includes/footer.php');?>