<?php
require_once('../connection/config.php');
include('../includes/header.php');
$success = $_GET['p'];
//get the user information
$surname = $_GET['surname'];
$oname = $_GET['oname'];
$telephone = $_GET['telephone'];
$postal = $_GET['postal'];
$email = $_GET['email'];
$account = $_GET['account'];
$lab = $_GET['lab'];
$datecreated = date("d-M-Y");
if ($lab < 1) {
    $lab = '0';
}
$username = $_GET['username'];
$password = $_GET['password'];
$password = md5($password);
//end get user information
//Array to store validation errors
$errmsg_arr = array();

//Validation error flag
$errflag = false;

//Function to sanitize values received from the form. Prevents SQL injection
function clean($str) {
    $str = @trim($str);
    if (get_magic_quotes_gpc()) {
        $str = stripslashes($str);
    }
    return mysql_real_escape_string($str);
}

//end sanitization of values
//save the user details
if ($_REQUEST['save']) {
//check for duplicate email
    $qry = "SELECT * FROM users WHERE email='$email'";
    $result = mysql_query($qry);
    if ($result) {
        if (mysql_num_rows($result) > 0) {
            $errmsg_arr[] = 'Email already in use, enter another one';
            $errflag = true;
        }
        @mysql_free_result($result);
    } else {
        die("failed");
    }
    //check for duplicate user name
    $qry = "SELECT * FROM users WHERE 	username 	='$username'";
    $result = mysql_query($qry);
    if ($result) {
        if (mysql_num_rows($result) > 0) {
            $errmsg_arr[] = 'Username is already in use, enter another one';
            $errflag = true;
        }
        @mysql_free_result($result);
    } else {
        die("Query failed");
    }


//If there are input validations, redirect back to the registration form
    if ($errflag) {
        $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    } else {
        $users = SaveUser($surname, $oname, $telephone, $postal, $email, $account, $username, $password, $lab, $datecreated); //call the save user function
        if ($users) { //check if all records entered
            $st = "User: " . $surname . ", " . $oname . " has been added.";
            //direct to users list view
            echo '<script type="text/javascript">';
            echo "window.location.href='userslist.php?p=$st'";
            echo '</script>';
        } else {
            $st = "Save User Failed, please try again.";
        }
    }
} else if ($_REQUEST['add']) {
    //check for duplicate email
    $qry = "SELECT * FROM users WHERE email='$email'";
    $result = mysql_query($qry);
    if ($result) {
        if (mysql_num_rows($result) > 0) {
            $errmsg_arr[] = 'Email already in use, enter another one';
            $errflag = true;
        }
        @mysql_free_result($result);
    } else {
        die("failed");
    }
    //check for duplicate user name
    $qry = "SELECT * FROM users WHERE 	username 	='$username'";
    $result = mysql_query($qry);
    if ($result) {
        if (mysql_num_rows($result) > 0) {
            $errmsg_arr[] = 'Username is already in use, enter another one';
            $errflag = true;
        }
        @mysql_free_result($result);
    } else {
        die("Query failed");
    }


//If there are input validations, redirect back to the registration form
    if ($errflag) {
        $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    } else {
        $users = SaveUser($surname, $oname, $telephone, $postal, $email, $account, $username, $password, $lab, $datecreated); //call the save user function
        if ($users) { //check if all records entered
            $st = "User: " . $surname . ", " . $oname . " has been added.";
            echo '<script type="text/javascript">';
            echo "window.location.href='adduser.php?p=$st'";
            echo '</script>';

            //header("location:adduser.php?p=$st"); //direct to users list view
            //exit();
        } else {
            $st = "Save User Failed, please try again.";
        }
    }
}
//end of saving user details
?>

<script type="text/javascript">
    function reload(form)
    {
        var val=form.account.options[form.account.options.selectedIndex].value;
        self.location='adduser.php?account=' + val ;
    }

</script>
<script type="text/javascript" src="../includes/validateusers.js"></script>
<link rel="stylesheet" href="../includes/validation.css" type="text/css" media="screen" />

<?php
@$account = $_GET['account']; // Use this line or below line if register_global is off
?>
<style type="text/css">
    select {width: 250;}
</style>

<div>
    <div class="section-title">ADD USER </div>


    <!--*********************************************************************** -->
    <div class="xtop">
        <?php
        if (isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) > 0) {
            echo '<table>
				  <tr>
					<td style="width:auto" ><div class="error">';
            foreach ($_SESSION['ERRMSG_ARR'] as $msg) {
                echo '<li>', $msg, '</li>';
            }
            echo '</div></td>
				  </tr>
				</table>';
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
    <!--*********************************************************************** -->
    <?php
///////////// End of query for first list box////////////
    $quer2 = mysql_query("SELECT ID,name FROM usergroups");

/////// for second drop down list we will check if category is selected else we will display all the subcategory///// 
    if ((isset($account) and ($account == 1 )) or (isset($account) and ($account == 2 ))) {
        $quer = mysql_query("SELECT ID,name from labs");
    }
////////// end of query for second subcategory drop down list box ///////////////////////////
    ?>
    <form id="customForm"  method="get" action="" >
        <table>
            <tr>
                <td colspan="4" width="414">The fields indicated asterix (<span class="mandatory">*</span>) are mandatory.</td>
            </tr>
        </table>
        <table  border="0" class="data-table">
            <tr>
                <th colspan="2">Account Information </th>
            </tr>
            <tr class="even">
                <td><span class="mandatory">*</span> Account Type</td>
                <td><div>
                        <!--select the account type -->
                        <?php
                        echo "<select name='account' id='account' onchange=\"reload(this.form)\"><option value=''>-- Select User Group  --</option>";
                        while ($noticia2 = mysql_fetch_array($quer2)) {
                            if ($noticia2['ID'] == @$account) {
                                echo "<option selected value='$noticia2[ID]'>$noticia2[name] </option>" . "<BR>";
                            } else {
                                echo "<option value='$noticia2[ID]'>$noticia2[name] </option>";
                            }
                        }
                        echo "</select>";
                        ?>
                        <span id='accountInfo'></span></div>
                    <!--end select the account type -->		  	</td>
            </tr>
            <tr class="even">
                <?php
                if (($account == 1) or ($account == 2)) {
                    ?>
                    <td><span class="mandatory">*</span> Lab </td>
                    <td >
                        <div>  <!--select the account type -->
                            <?php
                            echo "<select name='lab' id='lab'><option value=''>-- Select Lab --</option>";
                            while ($noticia = mysql_fetch_array($quer)) {
                                echo "<option value='$noticia[ID]'>$noticia[name] </option>";
                            }
                            echo "</select>";
                            ?>
                            <span id='labInfo'></span></div>
                        <!--end select the account type -->		  	</td>
                <?php } ?>
            </tr>
            <tr class="even">
                <td><span class="mandatory">*</span> Username</td>
                <td ><div><input type="text" name="username" id="username" size="25" class="text" /><span id='usernameInfo'></span></div></td>
            </tr>
            <tr class="even">
                <td><span class="mandatory">*</span> Password</td>
                <td ><div><input type="password" name="password" id="password" size="25" class="text" /><span id='passwordInfo'></span></div></td>
            </tr>
            <tr class="even">
                <td><span class="mandatory">*</span>Confirm Password </td>
                <td ><div><input type="password" name="confirmpassword" id="confirmpassword" size="25" class="text" /><span id='confirmpasswordInfo'></span></div></td>
            </tr>
            <tr>
                <th colspan="2">Personal Information </th>
            </tr>
            <tr class="even">
                <td><span class="mandatory">* </span>Surname</td>
                <td><div><input type="text" name="surname" id="surname" size="25" class="text" /><span id='surnameInfo'></span></div></td>
            </tr>
            <tr class="even">
                <td><span class="mandatory">*</span> Other Name(s) </td>
                <td ><div><input type="text" name="oname" id="oname"  size="25" class="text" /><span id='onameInfo'></span></div></td>
            </tr>
            <tr>
                <th colspan="5">Contact Details </th>
            </tr>
            <tr class="even">
                <td>Telephone No. </td>
                <td width="515"><input type="text" name="telephone" size="25" class="text" /></td>
            </tr>
            <tr class="even">
                <td>Postal Address </td>
                <td><textarea name="postal" cols="42" rows="2" wrap="soft">&nbsp;</textarea></td>
            </tr>
            <tr class="even">
                <td><span class="mandatory">*</span> Email Address </td>
                <td><div>
                        <input type="text" name="email" id="email" size="25" class="text" /><span id='emailInfo'></span>
                </td>
            </tr>
            <tr>
                <th colspan="3">
                    <input name="save" id="save" type="submit" class="button" value="Save User" />
                    <input name="add" type="submit" class="button" value="Save & Add User" />
                    <input name="reset" type="reset" class="button" value="Reset" /></th>
            </tr>
        </table>
    </form>
</div>


<?php include('../includes/footer.php'); ?>