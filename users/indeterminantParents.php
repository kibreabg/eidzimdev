<?php
require_once('../connection/config.php');

?>
<div>
    <div class="section-title">Samples repeated due to indeterminate results</div>
    <!--The form starts here-->

        <table>
            <tr>
                <td width="444">The fields indicated asterisk (<span class="mandatory">*</span>) are mandatory.</td>
            </tr>
        </table>
        <div>
            <table border="1" class="data-table">
                <tr>
                    <td>ID</td>
                    <td>Result</td>
                </tr>
                <?php
                $query = "select * from samples
                                          where (result = 1 OR result = 2 OR result = 3) and parentid in
                                            (select id
                                            from samples
                                            where result = 3 and parentid = 0)";$positive = 0;
                $negative = 0;
                $Indeterminate = 0;
                $total = 0;
                
                $result = mysql_query($query);

                while ($row = mysql_fetch_array($result)) {
                    $total++;
                    if ($row["result"] == 1) {
                        $value = "-";
                        $negative++;
                    } else if ($row["result"] == 2) {
                        $value = "+";
                        $positive++;
                    } else {
                        $value = "IND..";
                        $Indeterminate++;
                    }

                    echo "<tr class='even'>
                    <td>
                        <div>{$row["patient"]}</div>
                    </td>
                    <td>
                        <div>{$value}</div>
                    </td>
                </tr>";
                }
                ?>
            </table>
            <?php
                
            $pp=round($positive/$total*100,2);
            $np=round($negative/$total*100,2);
            $Ip=round($Indeterminate/$total*100,2);
            
                echo "Positive = {$positive}({$pp}%)<br>";
                echo "Negative = {$negative} ({$np}%) <br> Indeterminate = {$Indeterminate} ({$Ip})% <br>";
                echo "Total = {$total}";
            ?>
        </div>
  
</div>
<?php include('../includes/footer.php'); ?>