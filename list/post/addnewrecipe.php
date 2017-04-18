<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();
$sid=$_SESSION['user_id'];

$title = mysqli_real_escape_string($conn, $_POST['title']);

$sql = "INSERT INTO master_recipes(user_id, recipe_name)
  VALUES('$sid', '$title')";

if ($conn->query($sql) === TRUE) {
    echo 1;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>
