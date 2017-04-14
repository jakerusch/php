<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();
$sid=$_SESSION['user_id'];

$item_instance_id = $_POST['item_instance_id'];
$location_instance_id = $_POST['location_instance_id'];

$sql1 = "DELETE lists
  FROM lists
  WHERE lists.item_instance_id='$item_instance_id' AND lists.location_instance_id='$location_instance_id'";

$sql2 = "DELETE item_instances
  FROM item_instances
  WHERE item_instances.item_instance_id='$item_instance_id'";

if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE) {
    echo 1;
} else {
    echo "Error: " . $sql1 . "<br>" . $conn->error;
}

?>
