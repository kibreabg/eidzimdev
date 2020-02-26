<?php
require_once('../connection/config.php');
include('../includes/header.php');
?>
<style type="text/css">
    select {
        width: 250;
    }
</style>	


<div  class="section">
<div class="section-title">PENDING TASKS<!--SAMPLES WAITING TESTING [ <?php //echo samplesawaitingtests();   ?> ] --></div>
    <div class="xtop"><A HREF='javascript:history.back(-1)'><img src='../img/back.gif' alt='Go Back'/></A> 
            <?php
            include('homependingtasks.php');
//include('pendinglink.php');

            /* $Limit = 50;


              $qury = "SELECT task_id,task, sample,batchno  FROM pendingtasks WHERE status=0 AND task=1  ";

              $result = mysql_query($qury) or die('error');
              $batchesawaitingtest=mysql_num_rows($result);

              if ($batchesawaitingtest!=0)
              {
              echo '<table class="data-table">
              <th>Count</th>
              <th>Lab Code</th>
              <th>Sample Code</th>
              <th>Batch No</th>
              <th>Worksheet No</th>
              <th>Facility</th>
              <th>Province</th>
              <th>Date Collected</th>
              <th>Date Received</th>
              <th>Received Status</th>
              <th>Waiting Time (days)</th>
              <th>Task</th>
              ';

              $SUM=0;$samplesrank =0;
              while(list($task_id,$task,$sample,$batchno) = mysql_fetch_array($result))
              {
              $Limit = 50;
              // by default we show first page
              $pageNum = 1;

              // if $_GET['page'] defined, use it as page number
              if(isset($_GET['page']))
              {
              $pageNum = $_GET['page'];
              }

              // counting the offset
              $offset = ($pageNum - 1) * $limit;

              $pendingsamples=mysql_query("SELECT ID,patient,batchno,datereceived,spots,datecollected,receivedstatus,facility,worksheet
              FROM   samples
              WHERE patient='$sample' and batchno='$batchno' AND  ((result IS NULL) OR (result <1))  order by datereceived asc LIMIT " . ($pageNum-1)*$Limit . ",$Limit")or die(mysql_error());

              $samplescount=mysql_query("SELECT ID
              FROM   samples
              WHERE patient='$sample' and batchno='$batchno' AND  ((result IS NULL) OR (result <1)) ")or die(mysql_error());
              $totalsamples=mysql_num_rows($samplescount);
              $SUM=$totalsamples;

              while(list($ID,$patient,$batchno,$datereceived,$spots,$datecollected,$receivedstatus,$facility,$worksheet) = mysql_fetch_array($pendingsamples))
              {

              //get sample facility name based on facility code
              $facilityname=GetFacility($facility);
              //get district and province
              //get selected district ID
              $distid=GetDistrictID($facility);
              //get select district name and province id
              $distname=GetDistrictName($distid);
              //get province ID
              $provid=GetProvid($distid);
              //get province name
              $provname=GetProvname($provid);
              //date collcted
              $sdoc=date("d-M-Y",strtotime($datecollected));
              //get date received
              $sdrec=date("d-M-Y",strtotime($datereceived));
              $daterec =date("d-m-Y",strtotime($sdrec));

              //end check the dates if they are not 1-Jan-1970 (basically null)


              $currentdate=date('d-m-Y'); //get current date

              $workingdays=getWorkingDays($daterec,$currentdate,$holidays) ;
              $extradays =round($workingdays);

              //get patient gender
              $pgender=GetPatientGender($patient);
              //patietn age
              $pAge=GetPatientAge($patient);
              //patient dob
              $pdob=GetPatientDOB($patient);
              //infant prophylaxis
              $pprophylaxis=GetPatientProphylaxis($patient);
              //get sample sample test results
              $routcome = GetSampleResult($ID);
              //get sample recevied
              $srecstatus=GetReceivedStatus($receivedstatus);
              //get mother id from patient
              $mother=GetMotherID($patient);
              //mother hiv
              $mhiv=GetMotherHIVstatus($mother);
              //mother pmtct intervention
              $mprophylaxis=GetMotherProphylaxis($mother);
              //get mothers feeding type
              $mfeeding=GetMotherFeeding($mother);
              //get entry point
              $entry=GetEntryPoint($mother);
              $samplesrank = $samplesrank + 1;

              if ($worksheet ==0)
              {
              $worksheet="Not In Worksheet";
              }
              echo
              "<tr class='even'>
              <td >$samplesrank</td>
              <td >$ID </td>
              <td ><a href=\"sample_details.php" ."?view=1&ID=$ID" . "\" title='Click to view sample details'>$patient</a></td>
              <td ><a href='BatchDetails.php?view=1&ID=$ID'>$batchno </a></td>
              <td >$worksheet </td>
              <td >$facilityname </td>
              <td >$provname</td>
              <td >$sdoc</td>
              <td >$sdrec</td>
              <td >$srecstatus</td>
              <td >$extradays</td>
              <td ><a href=\"sample_details.php" ."?view=1&ID=$ID" . "\" title='Click to view sample details'>View</a> </td>
              </tr>";
              }

              }
              echo '</table>';


              $maxPage = ceil($SUM/$Limit);

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
              echo '<center>'.$first . $prev . $nav . $next . $last .'</center>';

              }
              else
              { */
            ?>
    <!--<table   >
      <tr>
        <td style="width:auto" ><div class="notice"><?php
//echo  '<strong>'.' <font color="#666600">'.'There are no samples awaiting testing'.'</strong>'.' </font>';
            ?></div></th>
      </tr>
    </table><?php
//}
            ?>
            
                    </div>
                    </div> -->

        <?php include('../includes/footer.php'); ?>