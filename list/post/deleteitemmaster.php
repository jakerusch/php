<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$item_id = $_POST['item_id'];

$sql1 = "DELETE lists 
	FROM lists
	INNER JOIN item_instances ON lists.item_instance_id=item_instances.item_instance_id
	WHERE item_id='".$item_id."'";

$sql2 = "DELETE FROM item_instances WHERE item_id='".$item_id."'";

$sql3 = "DELETE FROM master_items WHERE item_id='".$item_id."'";

if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE && $conn->query($sql3) === TRUE) {
	echo 1;
} else {
	echo "Error: " . $sql1 . "<br>" . $conn->error;
}

?>