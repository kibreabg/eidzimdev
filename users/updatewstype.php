<?php
session_start();
require_once('../connection/config.php');
include('../includes/header.php');

//Find the Worksheet to be updated
$wstypeid = $_GET['wstypeid'];
$query = "SELECT * FROM worksheettype WHERE ID = {$wstypeid} LIMIT 1";
$result = mysql_query($query);
$worksheet = mysql_fetch_array($result);

//Get form entries
$id = $_POST['id'];
$name = $_POST['name'];
$maxlimit = $_POST['maxlimit'];
$status = $_POST['status'];
if ($status == "on") {
    $status = 1;
} else {
    $status = 0;
}

//Success holding variable
$success = $_GET['p'];
//Array to store validation errors
$errmsg_arr = array();

//Validation error flag
$errflag = false;

//Update the worksheet type details
if ($_REQUEST['update']) {
    if ($errflag) {
        $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    } else {
        $WorkSheetType = UpdateWorksheetType($id, $name, $maxlimit, $status);
        if ($WorkSheetType) { //check if all records are entered
            $st = "WorkSheet Type: " . $name . ", " . "has been updated.";
            echo '<script type="text/javascript">';
            echo "window.location.href='worksheettypelist.php?p=$st'";
            echo '</script>';
        } else {
            $st = "Update Worksheet Type Failed, please try again.";
        }
    }
}
?>
<script type="text/javascript" src="../includes/validateworksheettype.js"></script>
<link rel="stylesheet" href="../includes/validation.css" type="text/css" media="screen" />

<div>
    <div class="section-title">UPDATE WORKSHEET TYPE</div>
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
    <form id="customForm" method="post" action="updatewstype.php">
        <input type="hidden" name="id" value="<?php echo $worksheet['ID']; ?>"/>
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
                            <input type="text" name="name" size="30" id="name" class="text" value="<?php echo $worksheet['name']; ?>" />
                            <span id='nameInfo'></span>
                        </div>
                    </td>
                </tr>
                <tr class="even">
                    <td><img src="../img/red.png" />&nbsp;MAX limit</td>
                    <td>
                        <div>
                            <input type="text" name="maxlimit" size="7" id="maxlimit" class="text" value="<?php echo $worksheet['maxlimit']; ?>" />
                            <span id='maxlimitInfo'></span>
                        </div>
                    </td>
                </tr>

                <tr class="even">
                    <td>&nbsp;Status</td>
                    <td>
                        <div>
                            <?php
                            if ($worksheet['status'] == 1) {
                                echo "<input type='checkbox' checked='checked' name='status' id='status'/>";
                            } else if ($worksheet['status'] == 0) {
                                echo "<input type='checkbox' name='status' id='status'/>";
                            }
                            ?>                            
                            <span id='statusInfo'></span>
                        </div>
                    </td>
                </tr>                               
                <tr>
                    <th colspan="2">
                        <input name="update" type="submit" class="button" value="Update Worksheet Type" />
                        <input name="reset" type="submit" class="button" value="Reset" />
                    </th>
                </tr>
            </table>
        </div>
    </form>
</div>
<?php include('../includes/footer.php'); ?>