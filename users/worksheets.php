<?php
require_once('../connection/config.php');
include('../includes/header.php');
?>	
<script type="text/javascript">
    function getWorksheets(worksheetinfo) {
    
        var separator = worksheetinfo.indexOf("_");
        var worksheetid = worksheetinfo.slice(0,separator);
        var worksheetlimit = worksheetinfo.slice(separator+1,worksheetinfo.length);
               
        //TAQMAN worksheets
        if(worksheetid == '2'){
            window.location.href = "createworksheet.php?limit=" + worksheetlimit;
        }
        else{
            window.location.href = "createmanualworksheet.php?limit=" + worksheetlimit;
        }
    }
</script>

<div class="section">
    <div class="section-title">Choose Worksheet Type</div>
    <div style="width: 100%; text-align: center;">    
        <span>Worksheet Type:</span>&nbsp;&nbsp;
        <select name="labs" id="labs" onchange="getWorksheets(this.value)">
            <option value="">--Select Worksheet TYpe--</option>
            <?php
            $qry = "SELECT * FROM worksheettype";
            $result = mysql_query($qry);
            if ($result) {
                while ($row = mysql_fetch_array($result)) {
                    echo "<option value='{$row['ID']}_{$row['maxlimit']}'>{$row['name']}</option>";
                }
            } else {
                die("failed");
            }
            ?>
        </select>
    </div>
</div>
<?php include('../includes/footer.php'); ?>