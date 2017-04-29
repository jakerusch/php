<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$recipe_id=$_POST["recipe_id"];

date_default_timezone_set('America/Chicago');
$today = new DateTime();
$today = $today->format('Y-m-d H:i:s');

// creates new list location instance
$sql = "INSERT INTO recipe_instances(recipe_id, recipe_timestamp)
  VALUES('$recipe_id', '$today')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>
