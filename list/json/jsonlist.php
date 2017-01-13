<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn=$obj->getConn();
$token=$_GET['token'];
$id=$_GET['id'];

$sql="SELECT lists.item_instance_id, lists.qty,
	master_items.item_name,
	item_instances.sort_order, lists.checked_status, lists.qty
	FROM lists
	INNER JOIN location_instances ON lists.location_instance_id=location_instances.location_instance_id
	INNER JOIN master_locations ON location_instances.location_id=master_locations.location_id
	INNER JOIN item_instances ON lists.item_instance_id=item_instances.item_instance_id
	INNER JOIN master_items ON item_instances.item_id=master_items.item_id
	WHERE lists.location_instance_id='$id'
	ORDER BY lists.checked_status ASC, item_instances.sort_order ASC";

$result=$conn->query($sql);

while($row=$result->fetch_assoc()) {
  $rows[] = $row;
}

echo json_encode($rows);

?>
