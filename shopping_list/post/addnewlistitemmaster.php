<?php
require_once($_SERVER['DOCUMENT_ROOT']."/shopping_list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();
$sid=$_SESSION['user_id'];

$location_id=$_POST["location_id"];
$item_id=$_POST["item_id"];

// get largest sort order for location
$result = $conn->query("SELECT MAX(sort_order) FROM item_instances WHERE location_id = '".$location_id."'");
$row = mysqli_fetch_row($result);
$max = $row[0] + 1;

// creates new list
$sql = "INSERT INTO item_instances(item_id, location_id, sort_order) VALUES('".$item_id."', '".$location_id."', $max)";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>