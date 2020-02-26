<?php

class SelectList {

    protected $conn;

    public function __construct() {
        $this->DbConnect();
    }

    protected function DbConnect() {
        include "../connection/config.php";
        //$this->conn = mysql_connect($host,$user,$password) OR die("Unable to connect to the database");
        //mysql_select_db($db,$this->conn) OR die("can not select the database $db");
        return TRUE;
    }

    public function ShowCategory() {
        $sql = "SELECT DISTINCT districts.ID,districts.name FROM districts inner join facilitys on facilitys.district=districts.ID and (facilitys.imei !=NULL or facilitys.imei!='') ";
        $res = mysql_query($sql);
        $category = '<option value="0">choose...</option>';
        while ($row = mysql_fetch_array($res)) {
            $category .= '<option value="' . $row['ID'] . '">' . $row['name'] . '</option>';
        }
        return $category;
    }

    public function ShowType() {
        $did = $_POST['id'];
        $sql = "SELECT ID,name FROM facilitys WHERE district=$did and (facilitys.imei !=NULL or facilitys.imei!='')";

        $res = mysql_query($sql);
        $type = '<option value="0">choose...</option>';
        while ($row = mysql_fetch_array($res)) {
            $type .= '<option value="' . $row['ID'] . '">' . $row['name'] . '</option>';
        }
        return $type;
    }

}

$opt = new SelectList();
?>