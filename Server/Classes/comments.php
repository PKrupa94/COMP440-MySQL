<?php
include "config.php";

if(!isset($_GET['blogid']))
{
    echo "Please provide blogid";
    return;
}
$blogid = $_GET['blogid'];


if(!isset($_GET['posted_by']))
{
    echo "Please provide user";
    return;
}
$posted_by = $_GET['posted_by'];


if(isset($_POST['save']))
{
    $sentiment = $_POST['sentiment'];
    $description = $_POST['description'];
    $cdate = date("Y-m-d");

    mysqli_query($conn, "INSERT INTO `comments`(`sentiment`, `description`, `cdate`, `blogid`, `posted_by`) 
                                        VALUES ('$sentiment', '$description', '$cdate', '$blogid', '$posted_by')");
}



$blog = mysqli_query($conn, "SELECT * FROM `blogs` where blogid='$blogid'");
$blog = mysqli_fetch_assoc($blog);
if(!isset($blog['blogid']))
{
    echo "No record exists";
    return;
}

echo "<h3>" . $blog['subject'] . "</h3>";
echo "<p>" . $blog['description'] . "</p>";

echo "<h4> Comments </h4>";

$url = $site_url . 'getComments.php?blogid='. $blogid;
$comments = json_decode(file_get_contents($url));
foreach($comments as $com)
{
    echo "<div style='background: lightgrey; margin: 5px; border-radius: 10px; padding: 12px'>";
        echo "<b> User : </b>" . $com->posted_by . "<br>";
        echo "<b> Sentiment : </b>" . $com->sentiment . "<br>";
        echo $com->description . "<br>";
        echo "<b> Date : </b>" . $com->cdate . "<br>";
    echo "</div>";
}
?>

<form action="" method="POST">
<table>
<tr>
    <th>Sentiment : </th>
    <td>
        <select name="sentiment" required style="width: 380px;">
            <option value="">Select Sentiment</option>
            <option value="negative">Negative</option>
            <option value="positive">Postive</option>
        </select>
    </td>
</tr>
<tr>
    <th>Comment : </th>
    <td><textarea name="description"cols="50" rows="10" required></textarea></td>
</tr>
<tr>
    <th></th>
    <td> <input type="submit" name="save" value="Post"></td>
</tr>
</table>
</form>