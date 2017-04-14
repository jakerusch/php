<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();
$sid=$_SESSION['user_id'];

$location_id = $_POST['location_id'];

$sql1 = "DELETE lists
	FROM lists
	INNER JOIN location_instances ON lists.location_instance_id=location_instances.location_instance_id
	WHERE location_instances.location_id='$location_id'";

$sql2 = "DELETE location_instances
	FROM location_instances
	WHERE location_instances.location_id='$location_id'";

$sql3 = "DELETE item_instances
	FROM item_instances
	WHERE item_instances.location_id='$location_id'";

$sql4 = "DELETE FROM master_locations
	WHERE master_locations.location_id='$location_id'
	AND user_id='$sid'";

if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE && $conn->query($sql3) === TRUE && $conn->query($sql4) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error: " . $sql1 . "<br>" . $conn->error;
}

?>
