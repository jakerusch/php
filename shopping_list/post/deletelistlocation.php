<?php
require_once($_SERVER['DOCUMENT_ROOT']."/shopping_list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$location_instance_id = $_POST['location_instance_id'];

$sql1 = "DELETE FROM lists WHERE location_instance_id='".$location_instance_id."'";
$sql2 = "DELETE FROM location_instances WHERE location_instance_id='".$location_instance_id."'";
if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>