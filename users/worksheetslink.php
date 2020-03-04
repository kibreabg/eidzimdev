<?php
$wtype = $_SESSION['wtype'];

if ((isset($wtype))) {
    if ($wtype == 1) {
        ?>
    <table>
        <tr>
            <td colspan="10">
                <strong><?php echo "Total Complete Worksheets: [ " . Gettotalcompleteworksheets() . " ]"; ?></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; View:- &nbsp;&nbsp;&nbsp;&nbsp;<a href="worksheetlist.php">All
            </a> | <a href="worksheetlist.php?wtype=<?php echo "0"; ?>">Pending Worksheets </a> | <a href="worksheetlist.php?wtype=<?php echo "1"; ?>"> Complete Worksheets </a> | <a href="worksheetlist.php?wtype=<?php echo "2"; ?>"> Manual Worksheets </a>| <a href="worksheetlist.php?wtype=<?php echo "3"; ?>"> Taqman Worksheets </a>
            </td>
        </tr>
    </table>

    <?php
} elseif ($wtype == 0) {
        ?>
        <table>
            <tr>
                <td colspan="10">
                    <strong><?php echo "Total Pending Worksheets: [ " . GettotalPendingworksheets() . " ]"; ?></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; View:- &nbsp;&nbsp;&nbsp;&nbsp;<a href="worksheetlist.php">All
            </a> | <a href="worksheetlist.php?wtype=<?php echo "0"; ?>">Pending Worksheets </a> | <a href="worksheetlist.php?wtype=<?php echo "1"; ?>"> Complete Worksheets </a> | <a href="worksheetlist.php?wtype=<?php echo "2"; ?>"> Manual Worksheets </a>| <a href="worksheetlist.php?wtype=<?php echo "3"; ?>"> Taqman Worksheets </a>
                </td>
            </tr>
        </table>
        <?php
} elseif ($wtype == 2) {
        ?>
            <table>
                <tr>
                    <td colspan="10">
                        <strong><?php echo "Total Manual Worksheets: [ " . Gettotalworksheetsbytype(1) . " ]"; ?></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; View:- &nbsp;&nbsp;&nbsp;&nbsp;<a href="worksheetlist.php">All
            </a> | <a href="worksheetlist.php?wtype=<?php echo "0 "; ?>">Pending Worksheets </a> | <a href="worksheetlist.php?wtype=<?php echo " 1 "; ?>"> Complete Worksheets </a> | <a href="worksheetlist.php?wtype=<?php echo " 2 "; ?>"> Manual Worksheets </a>| <a href="worksheetlist.php?wtype=<?php echo " 3 "; ?>"> Taqman Worksheets </a>
                    </td>
                </tr>
            </table>
            <?php
} elseif ($wtype == 3) {
        ?>
                <table>
                    <tr>
                        <td colspan="10">
                            <strong><?php echo "Total Taqman Worksheets: [ " . Gettotalworksheetsbytype(2) . " ]"; ?></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; View:- &nbsp;&nbsp;&nbsp;&nbsp;<a href="worksheetlist.php">All
            </a> | <a href="worksheetlist.php?wtype=<?php echo "0"; ?>">Pending Worksheets </a> | <a href="worksheetlist.php?wtype=<?php echo "1"; ?>"> Complete Worksheets </a> | <a href="worksheetlist.php?wtype=<?php echo "2"; ?>"> Manual Worksheets </a>| <a href="worksheetlist.php?wtype=<?php echo "3"; ?>"> Taqman Worksheets </a>
                        </td>
                    </tr>
                </table>
                <?php
}
} //end ifiseet wroksheet
else {

    ?>
                    <table>
                        <tr>
                            <td colspan="10"><strong><?php echo "All Worksheets: [ " . Gettotalworksheets() . " ]"; ?></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; View:- &nbsp;&nbsp;&nbsp;&nbsp;<a href="worksheetlist.php">All
            </a> | <a href="worksheetlist.php?wtype=<?php echo "0"; ?>">Pending Worksheets </a> | <a href="worksheetlist.php?wtype=<?php echo "1"; ?>"> Complete Worksheets </a> | <a href="worksheetlist.php?wtype=<?php echo "2"; ?>"> Manual Worksheets </a>| <a href="worksheetlist.php?wtype=<?php echo "3"; ?>"> Taqman Worksheets </a>
                            </td>
                        </tr>
                    </table>
                    <?php
}

?>