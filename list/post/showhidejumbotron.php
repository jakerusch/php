<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$menu_hide=$_POST['menu_hide'];
$location_instance_id=$_POST['location_instance_id'];

$sql = "UPDATE location_instances SET menu_hide='".$menu_hide."' WHERE location_instance_id='".$location_instance_id."'";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>