<?php
session_start();
require_once('../connection/config.php');
include('../includes/header.php');

//Get form entries
$name = $_POST['name'];
$maxlimit = $_POST['maxlimit'];
$status = $_POST['status'];

//Success holding variable
$success = $_GET['p'];
//Array to store validation errors
$errmsg_arr = array();

//Validation error flag
$errflag = false;

//save the lab details
if ($_REQUEST['save']) {
    if ($errflag) {
        $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    } else {
        $WorkSheetType = SaveWorkSheetType($name, $maxlimit, $status);
        if ($WorkSheetType) { //check if all records are entered
            $st = "WorkSheet Type: " . $name . ", " . "has been added.";
            echo '<script type="text/javascript">';
            echo "window.location.href='worksheettypelist.php?p=$st'";
            echo '</script>';
        } else {
            $st = "Save Worksheet Type Failed, please try again.";
        }
    }
} else if ($_REQUEST['add']) {
    if ($errflag) {
        $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    } else {
        $WorkSheetType = SaveWorkSheetType($name, $maxlimit, $status);
        if ($WorkSheetType) { //check if all records entered
            $st = "WorkSheet Type: " . $name . ", " . "has been added.";
            echo '<script type="text/javascript">';
            echo "window.location.href='addworksheettype.php?p=$st'";
            echo '</script>';
        } else {
            $st = "Save Worksheet Type Failed, please try again.";
        }
    }
}
//end of saving user details
?>
<style type="text/css">
    select {
        width: 250;
    }
</style>
<script type="text/javascript" src="../includes/validateworksheettype.js"></script>
<link rel="stylesheet" href="../includes/validation.css" type="text/css" media="screen" />

<div>
    <div class="section-title">ADD WORKSHEET TYPE</div>
    <div class="xtop">
        <?php
        //validation errors
        if (isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) > 0) {
            echo '<table><tr><td style="width:auto" ><div class="error">';
            foreach ($_SESSION['ERRMSG_ARR'] as $msg) {
                echo '<strong>' . $msg . '</strong>';
            }
            echo '</div></td></tr></table>';
            unset($_SESSION['ERRMSG_ARR']);
        }
        ?>
        <!--display the save message -->
        <?php
        if ($success != "") {
            ?> 
            <table>
                <tr>
                    <td style="width:auto" >
                        <div class="success">
                            <?php echo '<strong>' . ' <font color="#666600">' . $success . '</strong>' . ' </font>'; ?>
                        </div>
                    </td>
                </tr>
            </table>
        <?php } ?>
        <?php
        if ($st != "") {
            ?> 
            <table>
                <tr>
                    <td style="width:auto" >
                        <div class="error">
                            <?php echo '<strong>' . ' <font color="#666600">' . $st . '</strong>' . ' </font>'; ?></div>
                    </td>
                </tr>
            </table>
        <?php } ?>
        <!-- end display the save message -->
    </div>
    <div>
        <span><a style="color: blue; font-weight: bold; padding-left: 5px;" href="worksheettypelist.php">Show Worksheet Type List</a></span>
    </div>
    <!--The form starts here-->
    <form id="customForm" method="post" action="addworksheettype.php">
        <table>
            <tr>
                <td width="444">The fields indicated asterisk (<span class="mandatory">*</span>) are mandatory.</td>
            </tr>
        </table>
        <div>
            <table border="0" class="data-table">
                <tr>
                    <th colspan="2">Worksheet Information</th>
                </tr>
                <tr class="even">
                    <td><img src="../img/red.png" />&nbsp;Name</td>
                    <td>
                        <div>
                            <input type="text" name="name" size="30" id="name" class="text" />
                            <span id='nameInfo'></span>
                        </div>
                    </td>
                </tr>
                <tr class="even">
                    <td><img src="../img/red.png" />&nbsp;MAX limit</td>
                    <td>
                        <div>
                            <input type="text" name="maxlimit" size="7" id="maxlimit" class="text" />
                            <span id='maxlimitInfo'></span>
                        </div>
                    </td>
                </tr>
               
                <tr class="even">
                    <td>&nbsp;Status</td>
                    <td>
                        <div>
                            <input type="checkbox" checked="checked" name="status" id="status" value="1"/>
                            
                            <span id='statusInfo'></span>
                        </div>
                    </td>
                </tr>                               
                <tr>
                    <th colspan="2">
                        <input name="save" type="submit" class="button" value="Save Worksheet Type" />
                        <input name="add" type="submit" class="button" value="Save & Add Worksheet Type" />
                        <input name="reset" type="submit" class="button" value="Reset" />
                    </th>
                </tr>
            </table>
        </div>
    </form>
</div>
<?php include('../includes/footer.php'); ?>