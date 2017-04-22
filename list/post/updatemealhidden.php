<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$id=$_POST['id'];

$sql = "UPDATE recipe_instances
	SET recipe_instances.hidden='1'
	WHERE recipe_instance_id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>
