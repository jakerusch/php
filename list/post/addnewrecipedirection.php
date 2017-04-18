<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();
$sid=$_SESSION['user_id'];

$id = $_POST['id'];
$title = mysqli_real_escape_string($conn, $_POST['title']);

$sql1 = "SELECT MAX(directions_order) as max
  FROM recipe_directions
  WHERE unique_id='$id'";
$result=$conn->query($sql1);
$row=mysqli_fetch_row($result);
$count=$row[0]+1;

$sql2 = "INSERT INTO recipe_directions(unique_id, directions_order, directions_text)
  VALUES('$id', '$count', '$title')";

if ($conn->query($sql2) === TRUE) {
    echo 1;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>
