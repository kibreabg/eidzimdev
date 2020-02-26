<?php
require_once('../connection/config.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <script src="jquery-1.9.1.min.js"></script>
        <script type="text/javascript">
            function getLabs(labtype) {
                $("#section").load("labs.php?labtype=" + labtype);	
            }
        </script>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Ajax Test</title>
    </head>
    <body>
        <div style="width: 100%; text-align: center;">    
            <span>Lab Type:</span>&nbsp;&nbsp;
            <select name="labs" id="labs" onchange="getLabs(this.value)">
                <option value="">--Select Lab--</option>
                <?php
                $qry = "SELECT * FROM labs";
                $result = mysql_query($qry);
                if ($result) {
                    while ($row = mysql_fetch_array($result)) {
                        echo "<option value='{$row['withresult']}'>{$row['name']}</option>";
                    }
                } else {
                    die("failed");
                }
                ?>
            </select>
        </div>
        <div id="section">
            
        </div>
    </body>
</html>
