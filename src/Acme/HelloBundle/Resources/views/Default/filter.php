<?php
require_once 'db_connect.php'; 

$renttype = $_POST["renttype"];

$result = mysql_query("SELECT * FROM location where renttype='$renttype'") or die();
   while($row = mysql_fetch_array($result)){
    $title= $row['title'];
$ave= $row['avenue'];
$street= $row['street'];
$des= $row['description'];
$contact = $row['contact'];

echo $title,$ave,$street,$des,$contact;
   }
?>
