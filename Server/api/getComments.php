<?php
include "config.php";

if(!isset($_GET['blogid']))
{
    echo "Please provide blogid";
    return;
}

$blogid = $_GET['blogid'];

//echo "test";

$result = array();
$comments = mysqli_query($conn, "SELECT * FROM `comments` WHERE `blogid` = '$blogid' order by cdate");
while($com = mysqli_fetch_assoc($comments))
{
    $result[] = $com;
}

//print "<pre>";
//print_r($result);
print json_encode($result);
?>