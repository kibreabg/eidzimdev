<?php
//session_start();
//include('../includes/header.php');
include('../includes/functions.php');

include error_reporting(0);
?>

<style type="text/css">
    @media all
    {
        .page-break  { display:none; }
    }

    @media print
    {
        .page-break  { display:block; page-break-before:always; }
    }
</style>

<html><!---->
    <body onLoad="JavaScript:window.print();">
        <?php

        function Get_SampleInfo($pid) {
            $samplesquery = mysql_query("SELECT ID FROM samples where patient=" . $pid . " ") or die(mysql_error());
            $samples = mysql_fetch_array($samplesquery);

            return $samples;
        }

        $selectedList = unserialize(urldecode(stripslashes($_GET['SelctedL'])));

        $N = count($selectedList);

        for ($isel = 0; $isel < $N; $isel++) {

            list($facilitysel, $pidsel, $ressel, $labc) = split(",", $selectedList[$isel]);
            $samplessel = Get_SampleInfo($pidsel);

            $samplecode = $labc; //$samplessel['ID'];

            $updateprinted = mysql_query("update samples set printed=1 where ID='$samplecode'") or die(mysql_error());
            $samples = getSampleetails($samplecode);

            extract($samples);
            $stampNo = $nmrlstampno;
            
            
            //get sample facility name based on facility code
            $facilityname = GetFacility($facility);




            if (($datecollected != "") && ($datecollected != "0000-00-00") && ($datecollected != "1970-01-01")) {
                $datecollected = date("d-M-Y", strtotime($datecollected));
            } else {
                $datecollected = "";
            }



            if (($datereceived != "") && ($datereceived != "0000-00-00") && ($datereceived != "1970-01-01")) {
                $datereceived = date("d-M-Y", strtotime($datereceived));
            } else {
                $datereceived = "";
            }
            if (($datetested != "") && ($datetested != "0000-00-00") && ($datetested != "1970-01-01")) {
                $datetested = date("d-M-Y", strtotime($datetested));
            } else {
                $datetested = "";
            }
            if (($datemodified != "") && ($datemodified != "0000-00-00") && ($datemodified != "1970-01-01")) {
                $datemodified = date("d-M-Y", strtotime($datemodified));
            } else {
                $datemodified = "";
            }
            if (($datedispatched != "") && ($datedispatched != "0000-00-00") && ($datedispatched != "1970-01-01")) {
                $datedispatched = date("d-M-Y", strtotime($datedispatched));
            } else {
                $datedispatched = "";
            }

            $patientinfo = GetPatientInfo($patientid);
            extract($patientinfo);

            $plid = "SELECT name FROM patients WHERE AutoID ='$patientid'";
            $plname = mysql_query($plid);
            $planame = mysql_fetch_array($plname);
            $infantname = $planame['name'];



            $lid = "SELECT name FROM labs WHERE ID ='$lab'";
            $lname = mysql_query($lid);
            $laname = mysql_fetch_array($lname);
            $labname = $laname['name'];



            $pgender = GetPatientGender($patientid);
            //patietn age
            $pAge = GetPatientAge($patientid);
            //patient dob
            $pdob = GetPatientDOB($patientid);
            //infant prophylaxis
            $pprophylaxis = GetPatientProphylaxis($patientid);
            //get sample sample test results
            $routcome = GetSampleResult($ID);
            //get sample recevied
            $srecstatus = GetReceivedStatus($receivedstatus);
            //get mother id from patient 
            $mother = GetMotherID($patientid);
            $motherinf = GetMotherANC($patientid);
            $anc = $motherinf['anc'];
            $mname = $motherinf['name'];
            //mother hiv
            $mhiv = GetMotherHIVstatus($mother);
            //mother pmtct intervention
            $mprophylaxis = GetMotherProphylaxis($mother);
            //get mothers feeding type
            $mfeeding = GetMotherFeeding($mother);
            //get entry point
            $entry = GetEntryPoint($mother);

            //*****************$facility=GetFacilityCode($batchno,$labss);
            //get sample facility name based on facility code
            //******************$facilityname=GetFacility($facility);
            //echo $facilityname."---";
            //}
            //get district and province
            //get selected district ID
            $distid = GetDistrictID($facility);
            //get select district name and province id	
            $distname = GetDistrictName($distid);
            //get province ID
            $provid = GetProvid($distid);
            //get province name	
            $provname = GetProvname($provid);



            /* $finfo = getFacilityDetails($facility);
              extract($finfo); */
            $faquery = mysql_query("SELECT facilitycode FROM facilitys where ID='$facility' ") or die(mysql_error());
            $fdd = mysql_fetch_array($faquery);
            $facilitycode = $fdd['facilitycode'];
            ?>
            <!--onLoad="JavaScript:window.print();" -->
            <table style="font-family:Georgia, 'Times New Roman', Times, serif; font-size:11px">

             <!--<th colspan="4">FACILITY INFORMATION</th> -->
                 <!--<tr><td colspan="7"><div align="center"><img src="../img/zimlogoxm.jpg" alt="" /></div></td></tr> -->

                <tr><!--
                <td><div align="center"><img src="../img/zimlogo.jpg" alt="" /></div></td> -->
                    <th colspan="7" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2; font-size:18px">NATIONAL MICROBIOLOGY REFERENCE LABORATORY <br><br>HIV TNA PCR LABORATORY RESULT FORM</th>
                    <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2; font-size:18px"><div align="center"><img src="../img/lab_logo.png" alt="" /></div></td>
                </tr>
                <tr>
                    <td><strong>Province Name</strong></td>
                    <td colspan="" ><?php echo strtoupper($provname); ?></td>
                    <?php
                    $provinfo = GetProvInfo($provid);
                    extract($provinfo);
                    ?>
                    <td><strong>Province Code</strong></td>
                    <td colspan="" style="border-right:inset; border-right-color:#CCCCCC"><?php
                $provid = $ID;
                echo substr($facilitycode, 0, -4); //$ID; 
                ?></td>
                    <td ><span style="font-weight: bold">Request No </span></td>
                    <?php
                    //get all patient infor for use
                    //$pinfo = GetPatientInfo($patient);
                    //extract($pinfo);
                    ?>
                    <td colspan="2">
    <?php echo '<strong>Year &nbsp;</strong>' . $requestno_year . '&nbsp;&nbsp;<strong>No &nbsp;</strong>' . $requestno_no; ?>
                    </td>
                <input name="sampleid" type="hidden" class="text" value="<?php echo $requestno_year . $requestno_no; ?>" />
                <input name="samplecode" type="hidden" class="text" value="<?php echo $samplecode; ?>" />
                <input name="batchno" type="hidden" class="text" value="<?php echo $batchno; ?>" />
                <!--<td><div align="center"><img src="../img/lab_logo.png" alt="" /></div></td> -->
            </tr>
            <tr>
                <td><strong>District Name</strong></td>
                <td ><?php echo $distname; ?></td>
                <td><strong>District Code</strong></td>
                <?php
                $distinfo = GetDistInfo($distid);
                extract($distinfo);
                ?>
                <td colspan="" style="border-right:inset; border-right-color:#CCCCCC">
                    <?php
                    $distid = $ID;
                    echo substr($facilitycode, 2, -2); // $ID;
                    ?>
                </td>
                <td>
                    <b>Lab No.</b>                    
                </td>
                <td>
                    <?php echo $stampNo; ?>
                </td>
            </tr>
            <tr>
                <td ><strong>Referring Clinic or Hospital Name</strong></td>
                <td ><?php echo $facilityname; ?>&nbsp;</td>

                <td><strong>Hospital Code</strong></td>
                <td style="border-right:inset; border-right-color:#CCCCCC"><?php echo $facilitycode; //$provid.$distid.$facilitycode;   ?></td>
            </tr>
                          <!--<tr ><td colspan="7">&nbsp;</td></tr> -->
            <th height="10" colspan="8" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">&nbsp;</th>
            <?php
            //get all mother infor for use
            $minfo = GetMotherInfo($mother);
            extract($minfo);

            if ($testedbefore == 1) {
                $testedbefore = "Yes";
            } else if ($testedbefore == 2) {
                $testedbefore = "No";
            } else if ($testedbefore == 3) {
                $testedbefore = "No Data";
            } else {
                $testedbefore = "";
            }

            if ($onart == 1) {
                $onart = "Yes";
            } else if ($onart == 2) {
                $onart = "No";
            } else if ($onart == 3) {
                $onart = "No Data";
            } else {
                $onart = "";
            }

            if ($breastfeeding == 1) {
                $breastfeeding = "Yes";
            } else if ($breastfeeding == 2) {
                $breastfeeding = "No";
            } else if ($breastfeeding == 3) {
                $breastfeeding = "No Data";
            } else {
                $breastfeeding = "";
            }

            if ($receivearv == 1) {
                $receivearv = "Yes";
            } else if ($receivearv == 2) {
                $receivearv = "No";
            } else if ($receivearv == 3) {
                $receivearv = "No Data";
            } else {
                $receivearv = "";
            }

            //mother pmtct intervention
            $art = GetMotherProphylaxis($ID);

            if ($delivery == 1) {
                $delivery = "Caesarean";
            } else if ($delivery == 2) {
                $delivery = "Vaginal";
            } else if ($delivery == 3) {
                $delivery = "No Data";
            } else {
                $delivery = "";
            }
            ?>	

            <tr >
                <td><span style="font-weight: bold">Name of mother </span></td>
                <td><?php echo $mname; ?>&nbsp;</td>
                <td><span style="font-weight: bold">Was Mother Tested for HIV Before? </span></td>
                <td><?php echo $testedbefore; ?>&nbsp;</td>
                <td><span style="font-weight: bold">HIV Result </span></td>
                <td><?php echo $mhiv; ?>&nbsp;</td>
            </tr>
            <tr>
            <tr >
                <td><span style="font-weight: bold">Mother ANC #</span></td>
                <td><?php echo $anc; ?>&nbsp;</td>
            </tr>
            <th height="10" colspan="8" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">&nbsp;</th>

            <tr >

                <td ><span style="font-weight: bold">Infant's Name</span> </td>
                <td ><?php echo $infantname; ?></td>
                <td ><span style="font-weight: bold">Date of Birth
                    </span></td>
                <td ><?php echo $pdob; ?></td>
                <td ><span style="font-weight: bold">Sex of Baby</span> </td>
                <td colspan="3"><?php echo $pgender; ?></td>
            </tr>
                <!--<tr class="even">

         <td ><span style="font-weight: bold">Age At Testing
    </span></td>
      <td bgcolor="#F0F3FA"><?php /*
              if ($pAge < 12)
              { $agetype = " Months";}
              else if ($pAge >= 12)
              { $agetype = " Years";}
              echo $pAge.$agetype; */ ?></td>
    </tr> -->
            <tr >		    </tr>
            <tr >
                <td><span style="font-weight: bold">Date of taking DBS </span></td>
                <td><?php echo $datecollected; ?></td>
                <td><span style="font-weight: bold">Mode of Delivery </span></td>
                <td><?php echo $delivery; ?></td>
            </tr>  

            <tr >
                <td colspan="7" >&nbsp;</td>
            </tr>

            <th colspan="2" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2; border-right:inset; border-right-color:#CCCCCC">INFANT PROPHYLAXIS</th><th colspan="2" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2; border-right:inset; border-right-color:#CCCCCC">MOTHER PMTCT Prophylaxis</th><th colspan="5" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">Infant Testing (Check Child Health Card)</th>
            <?php
            //get all patient infor for use
            $pinfo = GetPatientInfo($patientid);
            extract($pinfo);

            if ($infantarv == 1) {
                $infantarv = "Yes";
            } else if ($infantarv == 2) {
                $infantarv = "No";
            } else if ($infantarv == 3) {
                $infantarv = "No Data";
            } else {
                $infantarv = "";
            }

            if ($testedbefore == 1) {
                $testedbefore = "Yes";
            } else if ($testedbefore == 2) {
                $testedbefore = "No";
            } else if ($testedbefore == 3) {
                $testedbefore = "No Data";
            } else {
                $testedbefore = "";
            }
            //infant prophylaxis
            $prophylaxis = GetPatientProphylaxis($patientid);
            //echo $patientid;
            ?>
            <tr >
                <td><strong>ARV Prophylaxis given to Infant</strong></td>
                <td style="border-right:inset; border-right-color:#CCCCCC"><?php echo $infantarv; ?></td>
                <td><strong>Is Mother on ART</strong></td>
                <td style="border-right:inset; border-right-color:#CCCCCC"><?php echo $onart; ?></td>
                <td rowspan="3" ><span style="font-weight: bold">Was the infant tested for HIV before? </span></td>
                <td rowspan="3" style="border-right:inset; border-right-color:#CCCCCC"><?php echo $testedbefore; ?></td>
                <td ><span style="font-weight: bold"><small>If yes, what was the result?</small></span></td>
                <td ><?php
            $infanthivstatus = GetResultName($infanthivstatus);
            echo $infanthivstatus;
            ?></td>
            </tr>


            <tr >
                <td><strong> If Yes, what did the infant receive? </strong></td>
                <td style="border-right:inset; border-right-color:#CCCCCC"><?php echo $prophylaxis; ?></td>
                <td><strong> If No, did the mother receive ARV Prophylaxis?  </strong></td>
                <td style="border-right:inset; border-right-color:#CCCCCC"><?php echo $receivearv; ?></td>
                <td ><span style="font-weight: bold"><small>If yes, what type of test was it?</small> </span></td>
                <td ><?php echo $testtype; ?></td>
            </tr>
            <tr >
                <td><strong> Infant already on CTX prophylaxis? </strong></td>
                <td style="border-right:inset; border-right-color:#CCCCCC"><?php echo $onctx; ?></td>
                <td><strong> If Yes, what did the mother receive?  </strong></td>
                <td style="border-right:inset; border-right-color:#CCCCCC"><?php echo $art; ?></td>

                <?php
                if ($testedbefore != '1') {
                    if ($testtype == 'DNA PCR') {
                        ?>
                        <td ><span style="font-weight: bold"><small>If DNA PCR,  original patient Lab Request No </small></span></td>
                        <td ><?php
                        echo //$originalpatientno; 
                        '<small><strong>Year </strong>' . $patientinfo['originalrequestno_year'] . ' <strong>No</strong> ' . $patientinfo['originalrequestno_no'] . '</small>'
                        ?></td>
                        <?php
                    }
                }
                ?>
            </tr>

            <tr >
                <td colspan="7" >&nbsp;</td>
            </tr>

            <td colspan="8" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2"><strong>Infant Feeding </strong></td>



            <tr >
                <td colspan="2"><strong>Infant breastfed in the last 6 weeks  </strong> </td>
                <td ><?php echo $breastfeeding; ?></td>	
                <td height="24" ><strong>Type of Feeding</strong> </td>
                <td ><?php echo $mfeeding; ?></td>	
            </tr>

            <tr >
                <td colspan="7" >&nbsp;</td>
            </tr>

            <th style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">Entry Point</th><td><?php echo $entry; ?></td><th style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2">Reasons for DNA / PCR Test</th><td colspan="3" ><?php
            $test_reason = GetTestReason($test_reason);
            echo $test_reason;
            ?></td>

            <tr>
                <td colspan="7" >&nbsp;</td>
            </tr>
    <!--	<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>-->

            <th colspan="8" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2; font-size:16px">********* &nbsp;<?php echo strtoupper($labname); ?>&nbsp;  INFORMATION &nbsp;&nbsp;************ </th>
            <tr >



                <td colspan="2" ><span style="font-weight: bold">Date Received  </span></td>
                <td ><?php echo $datereceived; ?></td>
                <td colspan="2"><span style="font-weight: bold">Received Status </span></td>
                <td ><?php
            if ($receivedstatus == 0) {
                $receivedstatus = '[No Status Selected]';
            } else {
                $receivedstatus = GetReceivedStatus($receivedstatus);
            }

            /* if (($approve != '') || ($notselected == 1))
              {
              $rquery = "SELECT ID,Name FROM receivedstatus ";

              $rresult = mysql_query($rquery) or die('Error, query failed'); //onchange='submitForm();'

              echo "<select name='receivedstatus' id='receivedstatus' style='width:188px' onChange='getRejectedreasons(this.value)';>\n";
              echo " <option value=''> Select One </option>";
              while ($rrow = mysql_fetch_array($rresult))
              {
              $rID = $rrow['ID'];
              $rname = $rrow['Name'];

              if ($rID == 2) //rejected
              {
              $fcolor = '#FF0000';
              }
              else if ($rID == 3) //repeat
              {
              $fcolor = '#0000FF';
              }
              else if ($rID == 4) //not received
              {
              $fcolor = '';
              }
              else //accepted
              {
              $fcolor = '#00CC00';
              }
              echo "<option value='$rID' style='color:".$fcolor."'> $rname</option>\n";
              }
              echo "</select>\n
              </td>
              <td width=150><div id='statediv'></div></td>";
              }
              else
              { */
            echo $receivedstatus
            ?></td>
            </tr>
                <!--<tr >

                
    <td><span style="font-weight: bold">No of Spots </span></td>
            <td colspan="7"><?php //echo $spots;  ?></td>
                </tr> -->


            <?php
            if ($result != 0) {
                ?>
                <?php
                $qury = mysql_query("SELECT  worksheet
            FROM samples
			WHERE ID='$samplecode'	
			") or die(mysql_error());
                $noofworksheets = mysql_num_rows($qury);

                if ($noofworksheets == 1) {
                    $gotwsheet = mysql_fetch_array($qury);
                    $worksheet = $gotwsheet['worksheet'];

                    $getuser = mysql_query("select users.surname, users.oname,worksheets.datereviewed from users,worksheets where users.ID =worksheets.reviewedby AND worksheets.worksheetno='$worksheet'") or die(mysql_error());
                    $gotuser = mysql_fetch_array($getuser);
                    $surname = $gotuser['surname'];
                    $othernames = $gotuser['oname'];
                    $datereviewed = $gotuser['datereviewed'];
                    $reviewer = $surname . " " . $othernames;
                }
                ?>
                <tr >
                    <td colspan="2"  ><span style="font-weight: bold">Date Tested</span></td>
                    <td ><?php echo $datetested; ?></td>	
                    <td colspan="2"  ><span style="font-weight: bold">Lab ref # (affix barcode sticker)</span></td>
                    <td style="font-size:18px" ><strong><?php echo $samplecode; ?></strong></td>	
                </tr>
                <tr >
                    <td colspan="2" ><span style="font-weight: bold">Date Result Updated </span></td>
                    <td ><?php echo $datemodified; ?></td>
                </tr>
                <tr >
                    <td colspan="2" ><span style="font-weight: bold">Date Result Dispatched </span></td>
                    <td ><?php echo $datedispatched; ?></td>
                </tr>
                <tr><td>&nbsp;</td></tr><tr><td colspan="2"><span style="font-weight: bold">Dispatch Comments</span></td><td><?php echo $DispatchComments; ?></td>
                </tr><tr><td>&nbsp;</td></tr>

                <TR>
                    <td colspan="2"  style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2; ; font-size:18px"><span style="font-weight: bold"><?php echo strtoupper('Test Result'); ?></span></td>
                    <?php
                    $result = GetResultType($result);
                    ?>
                    <td style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2; font-size:18px"><strong><u><?php echo strtoupper($result); ?></u></strong></td>
                    <td colspan="2"  style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2; ; font-size:10px"><span style="font-weight: bold"><?php echo strtoupper('Approved By'); ?></span></td>

                    <td colspan="3" style="font-family:Georgia, 'Times New Roman', Times, serif ;background-color: #F2F2F2; font-size:10px"><strong><?php echo ucwords($reviewer); ?></strong></td>
                </TR>
    <?php }
    ?>
        </table>
        <hr>
        <div class="page-break"></div>
        <?php
    }
    ?>
</body>
</html>	