<?php
session_start();
$userid = $_SESSION['uid'];
$accttype = $_SESSION['accounttype'];
$userlab = $_SESSION['lab'];
$labss = $_SESSION['lab'];
require_once('../connection/config.php');
include('../includes/functions.php');
//require_once('monitorpendingtasks.php');
//require_once('monitorbatchdispatch.php');
require_once('protectpages.php');
//get the search variable
$searchparameter = $_GET['search'];
$top = "Top";
$side = "Side";
$totaltasks = gettotalpendingtasks();
$labname = GetLabNames($userlab); //get lab name	
$accttypename = GetAccountType($accttype);

if ($totaltasks != 0) {
    $d = '<strong> [' . $totaltasks . ']</strong>';
} else {
    $d = '<strong> [' . '0' . ']</strong>';
}

$view = $_GET['view']; //check if a value has been passed to hide the side bar for better viewing
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">

    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <meta name="description" content=""/>
        <meta name="keywords" content="" />
        <meta name="author" content="" />
        <link rel="stylesheet" type="text/css" href="../style.css" media="screen" />

        <title>EID ZIM</title>
        <link rel="shortcut icon" href="favicon.ico" />
        <link rel="icon" type="image/gif" href="../animated_favicon1.gif" />  

        <script type="text/javascript" src="../includes/jquery.min.js"></script>
        <script type="text/javascript" src="../includes/jquery.js"></script>
        <script type='text/javascript' src='../includes/jquery.autocomplete.js'></script>
        <link rel="stylesheet" type="text/css" href="../includes/jquery.autocomplete.css" />

        <!-- <script type="text/javascript" src="reflection.js"></script>  -->

        <script type="text/javascript">
            $().ready(function () {

                $("#sample").autocomplete("getsamples.php", {
                    width: 260,
                    matchContains: true,
                    mustMatch: true,
                    //minChars: 0,
                    //multiple: true,
                    //highlight: false,
                    //multipleSeparator: ",",
                    selectFirst: false
                });

                $("#sample").result(function (event, data, formatted) {
                    $("#sampleid").val(data[1]);
                });
            });
        </script>


        <script type="text/javascript">
            $().ready(function () {

                $("#labid").autocomplete("getlabnos.php", {
                    width: 260,
                    matchContains: true,
                    mustMatch: true,
                    //minChars: 0,
                    //multiple: true,
                    //highlight: false,
                    //multipleSeparator: ",",
                    selectFirst: false
                });

                $("#labid").result(function (event, data, formatted) {
                    $("#labno").val(data[1]);
                });
            });
        </script>

        <script type="text/javascript">
            $().ready(function () {

                $("#wsheet").autocomplete("getworksheets.php", {
                    width: 260,
                    matchContains: true,
                    mustMatch: true,
                    //minChars: 0,
                    //multiple: true,
                    //highlight: false,
                    //multipleSeparator: ",",
                    selectFirst: false
                });

                $("#wsheet").result(function (event, data, formatted) {
                    $("#wsheetid").val(data[1]);
                });
            });
        </script>

        <script type="text/javascript">
            $().ready(function () {

                $("#patientname").autocomplete("getpatientnames.php", {
                    width: 260,
                    matchContains: true,
                    mustMatch: true,
                    //minChars: 0,
                    //multiple: true,
                    //highlight: false,
                    //multipleSeparator: ",",
                    selectFirst: false
                });

                $("#patientname").result(function (event, data, formatted) {
                    $("#patientid").val(data[1]);
                });
            });
        </script>
        <script type="text/javascript" src="reflection.js"></script> 
    </head>

    <body>
        <div id="site-wrapper">

            <div id="header">
                <!--top-->
                <div id="top">
                    <?php //echo "Welcome&nbsp;&nbsp;&nbsp;". " <b>".	$_SESSION['unames'] ."</b>". ' - '. $labname .'<br>Account Type&nbsp;&nbsp;'.$accttypename.'<br>'."<b>". date("l, d F Y")."</b>";  ?>
                    <div class="left" id="logo"><img src="../img/welfarelogo.png"/></div>
                    <div align="right">
                        <table>
                            <tr style="height: 10px;">
                                <td>
                                    <small><?php echo "Welcome"; ?></small><br />
                                    <small>Account Type</small><br />
                                    <small>Date</small>
                                </td>
                                <td>
                                    <small><strong><?php echo $_SESSION['unames'] . ' - ' . $labname; ?></strong></small><br />
                                    <small><strong><?php echo $accttypename; ?></strong></small><br />
                                    <small><strong><?php echo date("l, d F Y"); ?></strong></small>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="clearer">&nbsp;</div>

                </div>
                <!--end top-->

                <!--menu-->
                <div class="navigation" id="sub-nav">
                    <ul class="tabbed">

                        <?php
                        if ($accttype != "") { //...if the accttype is not blank then show top bar menu
                            //query for top bar
                            $menuresult = mysql_query("SELECT groupmenus.menu as 'topmenu' from groupmenus,menus where groupmenus.usergroup='$accttype' AND menus.ID=groupmenus.menu AND  menus.location='Top' ORDER BY groupmenus.orderno ASC") or die(mysql_error());

                            while (list($topmenu) = mysql_fetch_array($menuresult)) {
                                if ($topmenu == 33) {
                                    $title = GetMenuName($topmenu);
                                    $link = GetMenuUrl($topmenu);
                                    echo "<li>";
                                    echo "<a href=$link target='_blank'>$title &nbsp;</a> |&nbsp";
                                    echo"</li>";
                                } else {
                                    $title = GetMenuName($topmenu);
                                    $link = GetMenuUrl($topmenu);
                                    echo "<li>";
                                    echo "<a href=$link>$title &nbsp;</a> |&nbsp";
                                    echo"</li>";
                                }
                            }
                        } else {
                            
                        }
                        ?>

                    </ul>
                    <div class="clearer">&nbsp;</div>

                </div>
                <!--end menu-->

            </div>

            <?php
//check if a view value has been passed...if it has...disable the side menu view
            if ($view == '1') {
                ?>
                <div class="left sidebar" id="sidebar">

                    <?php
                } else {
                    ?>
                    <div class="left sidebar" id="sidebar">
                        <div class="section">
                            <div class="section-title">Quick Menu</div>
                            <!--side bar menu-->
                            <div class="section-content">

                                <ul class="nice-list">

                                    <?php
                                    if ($accttype != "") {
                                        //query for side bar
                                        $result2 = mysql_query("SELECT  groupmenus.menu as 'sidemenu' from groupmenus,menus where groupmenus.usergroup='$accttype' AND menus.ID=groupmenus.menu AND  menus.location='Side' ORDER BY groupmenus.orderno ASC") or die(mysql_error());
                                        $DD = mysql_num_rows($result2);

                                        while (list($sidemenu) = mysql_fetch_array($result2)) {


                                            if ($sidemenu == 36) {//..pending tasks
                                                $menuname = GetMenuName($sidemenu);
                                                $title = $menuname;
                                                // $title= $menuname . $d;
                                                $link = GetMenuUrl($sidemenu);
                                                echo "<li> <div class='left'>";

                                                echo "<a href=$link>$title &nbsp;</a> </div>";
                                                echo"<div class='clearer'>&nbsp;</div></li>";
                                                //echo "jina ".$title;
                                            } else if ($sidemenu == 35) {//..dispatch results
                                                if ($accttype == 4) {//..lab tech
                                                    $menuname = 'Release Samples';
                                                    $noofbatches = GetTotalCompleteBatches(2, $labss);
                                                } else if ($accttype == 1) {//..data clerk
                                                    $menuname = 'Dispatch Results';
                                                    $qury = "SELECT ID FROM samples
							     WHERE samples.lab='$labss' AND samples.BatchComplete=1 and datedispatched ='' and printed = 0 and receivedstatus = 1 and approved = 1 and flag=1 and repeatt !=1";

                                                    $quryresult = mysql_query($qury) or die(mysql_error());
                                                    $noofbatches = mysql_num_rows($quryresult);
                                                }


                                                if ($noofbatches > 0) {
                                                    $focolor = '#FF0000';
                                                } else {
                                                    $focolor = '';
                                                }

                                                $title = $menuname . ' <strong>[ <font color=' . $focolor . '>' . $noofbatches . '</font> ]</strong>';
                                                $link = GetMenuUrl($sidemenu);
                                                echo "<li> <div class='left'>";

                                                echo "<a href=$link>$title &nbsp;</a> </div>";
                                                echo"<div class='clearer'>&nbsp;</div></li>";
                                            } else if ($sidemenu == 8) {//..create TAQMAN wsheet
                                                $twqury = "select ID,patient,parentid,datereceived, IF(parentid > 0 OR parentid IS NULL, 0, 1) AS isnull  from samples  WHERE Inworksheet=0 AND receivedstatus !=2  AND ((result IS NULL ) OR (result =0 )) AND inputcomplete =1 AND Flag=1 and approved = 1";
                                                $twresult = mysql_query($twqury) or die(mysql_error());
                                                $twno = mysql_num_rows($twresult); //no of samples

                                                if ($twno > 21) {
                                                    $focolor = '#FF0000';
                                                } else {
                                                    $focolor = '';
                                                }

                                                $menuname = GetMenuName($sidemenu);
                                                $title = $menuname . ' <strong>[ <font color=' . $focolor . '>' . $twno . '</font> ]</strong>';
                                                $link = GetMenuUrl($sidemenu);
                                                echo "<li> <div class='left'>";

                                                echo "<a href=$link>$title &nbsp;</a> </div>";
                                                echo"<div class='clearer'>&nbsp;</div></li>";
                                                //echo "jina ".$title;
                                            } else if ($sidemenu == 20) {//..create MANUAL wsheet
                                                $manqury = "select ID,patient,parentid,datereceived, IF(parentid > 0 AND sampleokforretest=1, 0, 1) AS isnull  from samples  WHERE Inworksheet=0 AND receivedstatus !=2  AND ((result IS NULL ) OR (result =0 )) and approved = 1";
                                                $manresult = mysql_query($manqury) or die(mysql_error());
                                                $manno = mysql_num_rows($manresult); //no of samples

                                                if ($manno > 42) {
                                                    $mfocolor = '#FF0000';
                                                } else {
                                                    $mfocolor = '';
                                                }

                                                $menuname = GetMenuName($sidemenu);
                                                $title = $menuname . ' <strong>[ <font color=' . $mfocolor . '>' . $manno . '</font> ]</strong>';
                                                $link = GetMenuUrl($sidemenu);
                                                echo "<li> <div class='left'>";

                                                echo "<a href=$link>$title &nbsp;</a> </div>";
                                                echo"<div class='clearer'>&nbsp;</div></li>";
                                                //echo "jina ".$title;
                                            } else if ($sidemenu == 47) {//..approve samples
                                                $approvalqury = "SELECT ID FROM samples	WHERE approved = 0 and flag =1 ";

                                                $approvalqueryresult = mysql_query($approvalqury) or die(mysql_error());
                                                $notapproved = mysql_num_rows($approvalqueryresult);

                                                if ($notapproved > 0) {
                                                    $fcolor = '#FF0000';
                                                } else {
                                                    $fcolor = '';
                                                }
                                                $menuname = GetMenuName($sidemenu);
                                                $title = $menuname . ' <strong>[ <font color=' . $fcolor . '>' . $notapproved . '</font> ]</strong>';
                                                $link = GetMenuUrl($sidemenu);
                                                echo "<li> <div class='left'>";

                                                echo "<a href=$link>$title &nbsp;</a> </div>";
                                                echo"<div class='clearer'>&nbsp;</div></li>";
                                                //echo "jina ".$title;
                                            } else {
                                                $title = GetMenuName($sidemenu);
                                                $link = GetMenuUrl($sidemenu);

                                                echo "<li> <div class='left'>";

                                                echo "<a href=$link>$title &nbsp;</a> </div>";
                                                echo"<div class='clearer'>&nbsp;</div></li>";
                                            }
                                        }
                                    } else {
                                        
                                    }
                                    ?>

                                </ul>

                            </div>
                        </div>
                        <!--end side bar menu-->

                        <!--search form-->
                        <div class="section">

                            <?php if ($accttype == 1) {  //..show only if it is not the admin or program manager //echo $accttype;
                                ?>
                                <div class="section-title"> <small>Search Sample by Request # </small></div>

                                <div class="section-content">

                                    <form method="post" action="search.php">
                                        <input name="sample" id="sample" type="text" class="text" size="15" />
                                        <input type="hidden" name="sampleid" id="sampleid" />&nbsp; 
                                        <input name="submit" type="submit" class="button" value="Go"/>
                                    </form>
                                </div>
							<div class="section-title"> <small> Search Sample by NMRL #</small></div>

                                <div class="section-content">
                                    <form method="post" action="searchnmrlno.php">
                                        <input name="nmrlno" id="nmrlno" type="text" class="text" size="15" />
                                        <input type="hidden" name="nmrlstampno" id="nmrlstampno" />&nbsp; 
                                        <input name="submit" type="submit" class="button" value="Go"/>

                                    </form>
                                </div>
                                <!--</div> -->
                                <!--</div> -->
                                <div class="section-title"> <small> Search Sample by Lab #</small></div>

                                <div class="section-content">
                                    <form method="post" action="searchlabno.php">
                                        <input name="labid" id="labid" type="text" class="text" size="15" />
                                        <input type="hidden" name="labno" id="labno" />&nbsp; 
                                        <input name="submit" type="submit" class="button" value="Go"/>

                                    </form>
                                </div>

                                <div class="section-title"><small> Search by Patient Names </small></div>
                                <div class="section-content">
                                    <form method="post" action="searchpatientnames.php">
                                        <input name="patientname" id="patientname" type="text" class="text" size="15" />
                                        <input type="hidden" name="patientid" id="patientid" />&nbsp; 
                                        <input name="submit" type="submit" class="button" value="Go"/>

                                    </form>
                                </div>

                                <!--</div> -->

                                <?php
                            } else if (($accttype == 4) || ($accttype == 5)) {
                                ?>	
                                <div class="section-title"> <small>Search Sample by Request # </small></div>

                                <div class="section-content">

                                    <form method="post" action="search.php">
                                        <input name="sample" id="sample" type="text" class="text" size="15" />
                                        <input type="hidden" name="sampleid" id="sampleid" />&nbsp; 
                                        <input name="submit" type="submit" class="button" value="Go"/>
                                    </form>
                                </div>

                                <!--</div> -->
                                <div class="section-title"> <small> Search Sample by Lab #</small></div>

                                <div class="section-content">
                                    <form method="post" action="searchlabno.php">
                                        <input name="labid" id="labid" type="text" class="text" size="15" />
                                        <input type="hidden" name="labno" id="labno" />&nbsp; 
                                        <input name="submit" type="submit" class="button" value="Go"/>

                                    </form>
                                </div>

                                <div class="section-title"><small> Search by Patient Names </small></div>
                                <div class="section-content">
                                    <form method="post" action="searchpatientnames.php">
                                        <input name="patientname" id="patientname" type="text" class="text" size="15" />
                                        <input type="hidden" name="patientid" id="patientid" />&nbsp; 
                                        <input name="submit" type="submit" class="button" value="Go"/>

                                    </form>
                                </div>
                                <div class="section-title"> <small> Search Worksheet by Serial # </small></div>

                                <div class="section-content">
                                    <form method="post" action="worksheetlist.php">
                                        <input name="wsheet" id="wsheet" type="text" class="text" size="15" />
                                        <input type="hidden" name="wsheetid" id="wsheetid" />&nbsp; 
                                        <input name="submit" type="submit" class="button" value="Go"/>

                                    </form>
                                </div>

                                <!--</div> --><?php
                            } elseif ($accttype == 2) {
                                ?>
                                <div class="section-title">Search (all)</div>

                                <div class="section-content">
                                    <form method="post" action="adminsearch.php">
                                        <input name="search" type="text" class="text" size="15" />
                                        &nbsp; 
                                        <input name="submit" type="submit" class="button"/>

                                    </form>
                                </div>

                            <?php } ?>

                        </div>
                        <!--end search form-->
                        <?php
                    }
                    ?>	

                    <!--<div  class="center" id="main-content">-->

                </div>