<?php
include("connection/config.php");

$sql = "select districts.ID as 'did',districts.name as 'dname'  from facilitys,districts where facilitys.district=districts.name AND facilitys.district =0" ;

$result=  mysql_query($sql) or die(mysql_error());
$count=0;
while ($row=mysql_fetch_array($result))
{
$d=$row['did'];
$d2=$row['dname'];
$xs=mysql_query("update facilitys set district='$d' where   districtname='$d2'")or die(mysql_error());
$count=$count+1;
 }
 if  ($xs)
{
echo  $count." yes to disticts";
}



?>
