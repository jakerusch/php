<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$i = 0;
foreach($_POST['item'] as $value) {
	$sql = "UPDATE recipe_directions
		SET recipe_directions.directions_order='$i'
		WHERE recipe_directions.item_id='$value'";

	if ($conn->query($sql) === TRUE) {
	    echo "New record created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}
	$i++;
}

?>
