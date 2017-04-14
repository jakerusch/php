<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$item_instance_id=$_POST['item_instance_id'];
$location_instance_id=$_POST['location_instance_id'];

$sql = "DELETE FROM lists
  WHERE item_instance_id='$item_instance_id' AND location_instance_id='$location_instance_id'";

if ($conn->query($sql) === TRUE) {
    echo 1;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>
