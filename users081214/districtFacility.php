<?php

session_start();
require_once('../connection/config.php');
include('../includes/functions.php');
?>
<script src="dhtmlx/dhtmlx.js" type="text/javascript" charset="utf-8"></script>
<script src="dhtmlx/connector.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="dhtmlx/dhtmlx.css" type="text/css"/>
<script type="text/javascript" src="../includes/validatesample.js"></script>
<script src="jquery-ui.min.js" type="text/javascript"></script>
    <span class="mandatory">*</span> Referring Clinic / Hospital Name
    <div id="facilitycombo" style="width:200px; height:10px;"></div>
    <script>
        var districtID = $("#dist").val();
        var z = new dhtmlXCombo("facilitycombo","facility",200);
        z.enableFilteringMode(true,"02_sql_connectorFilter.php?dID=" + districtID,true);
    </script>
<br/>
<span id="facilityInfo"></span>