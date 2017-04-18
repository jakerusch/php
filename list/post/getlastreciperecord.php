<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();
$sid=$_SESSION['user_id'];

$sql = "SELECT MAX(master_recipes.unique_id) as max
  FROM master_recipes";

$result = $conn->query($sql);
echo mysqli_fetch_row($result)[0];
?>
