<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$qty=$_POST['qty'];
$item_instance_id=$_POST['item_instance_id'];
$location_instance_id=$_POST['location_instance_id'];

$sql = "UPDATE lists SET qty='".$qty."' WHERE location_instance_id='".$location_instance_id."' AND item_instance_id='".$item_instance_id."'";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>