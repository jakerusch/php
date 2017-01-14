<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn=$obj->getConn();
$sid=$_SESSION['user_id'];
$id=$_GET['id'];

//get search term
$searchTerm = $_GET['term'];

//get all items possible from particular location_instance_id
$dropdown = "SELECT item_instances.item_instance_id, item_instances.item_id,
	item_instances.location_id, item_instances.sort_order, master_items.item_name,
	location_instances.location_instance_id
	FROM item_instances
	INNER JOIN master_items ON item_instances.item_id=master_items.item_id
	INNER JOIN master_locations ON item_instances.location_id=master_locations.location_id
	INNER JOIN location_instances ON master_locations.location_id=location_instances.location_id
	WHERE location_instances.location_instance_id='".$id."'
	AND item_instances.item_instance_id NOT IN
		(SELECT lists.item_instance_id
		FROM lists
		INNER JOIN location_instances ON lists.location_instance_id=location_instances.location_instance_id
		INNER JOIN master_locations ON location_instances.location_id=master_locations.location_id
		INNER JOIN item_instances ON lists.item_instance_id=item_instances.item_instance_id
		INNER JOIN master_items ON item_instances.item_id=master_items.item_id
		WHERE lists.location_instance_id='".$id."')
	AND master_items.item_name LIKE '%".$searchTerm."%'
  ORDER BY master_items.item_name ASC;";

		$result=$conn->query($dropdown);
		while($row=$result->fetch_assoc()) {
			$data[] = array(
				'label' => $row['item_name'],
				'id' => $row['item_instance_id'],
			);
		}

//return json data
echo json_encode($data);
?>
