<?php
session_start();
require_once('../connection/config.php');
include('../includes/header.php');
//get the search variable
$searchparameter = $_GET['search'];
$success = $_GET['p'];
?>

<div>
    <div class="section-title">Worksheet Type LIST</div>
    <?php
    if ($success != "") {
        ?> 
        <table   >
            <tr>
                <td style="width:auto">
                    <div class="success">
                        <?php echo '<strong>' . ' <font color="#666600">' . $success . '</strong>' . ' </font>'; ?>
                    </div>
                </td>
            </tr>
        </table>
    <?php }
    ?>
    <?php
    //query database for all worksheet type
    $worksheettypequery = "SELECT name,maxlimit,status FROM worksheettype";
    $result = mysql_query($worksheettypequery) or die(mysql_error()); //for main display		
    $no = mysql_num_rows($result);

    if ($no != 0) {
        $rowsPerPage = 15; //number of rows to be displayed per page
        // by default we show first page
        $pageNum = 1;

        // if $_GET['page'] defined, use it as page number
        if (isset($_GET['page'])) {
            $pageNum = $_GET['page'];
        }

        // counting the offset
        $offset = ($pageNum - 1) * $rowsPerPage;

        echo '<table border=0 class="data-table">
		<tr><th style="width:400px">Name</th><th style="width:150px">Maxlimit</th><th>Actions</th></tr>';
        /* <!--*********************************************************** --> */

        //query database for all worksheet types
        $worksheettypequery = "SELECT ID,name,maxlimit FROM worksheettype LIMIT $offset, $rowsPerPage";
        $result = mysql_query($worksheettypequery) or die(mysql_error());

        while (list($ID,$name, $status) = mysql_fetch_array($result)) {
            //display the table
            echo "<tr>
                    <td>$name</td>
                    <td>$status</td>
                    <td><a style='color: blue; font-weight: bold;' href='updatewstype.php?wstypeid={$ID}'>Update Worksheet Type</a></td>
                  </tr>";
        }
        echo "</table>";

        $numrows = $no; //get total no of batches
        // how many pages we have when using paging?
        $maxPage = ceil($numrows / $rowsPerPage);

        // print the link to access each page
        $self = $_SERVER['PHP_SELF'];
        $nav = '';
        for ($page = 1; $page <= $maxPage; $page++) {
            if ($page == $pageNum) {
                $nav .= " $page "; // no need to create a link to current page
            } else {
                $nav .= " <a href=\"$self?page=$page\">$page</a> ";
            }
        }

        // creating previous and next link
        // plus the link to go straight to
        // the first and last page

        if ($pageNum > 1) {
            $page = $pageNum - 1;
            $prev = " <a href=\"$self?page=$page\">[Prev]</a> ";

            $first = " <a href=\"$self?page=1\">[First Page]</a> ";
        } else {
            $prev = '&nbsp;'; // we're on page one, don't print previous link
            $first = '&nbsp;'; // nor the first page link
        }

        if ($pageNum < $maxPage) {
            $page = $pageNum + 1;
            $next = " <a href=\"$self?page=$page\">[Next]</a> ";

            $last = " <a href=\"$self?page=$maxPage\">[Last Page]</a> ";
        } else {
            $next = '&nbsp;'; // we're on the last page, don't print next link
            $last = '&nbsp;'; // nor the last page link
        }

        // print the navigation link
        echo '<center>Page ' . $first . $prev . $nav . $next . $last . '</center>';
    } else if ($no == 0) {
        echo '<center>No Worksheet Type have been Added</center>';
        exit();
    }
    ?>	
</div>

<?php include('../includes/footer.php'); ?>