<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$id=$_POST['id'];
$timestamp=$_POST['timestamp'];

$sql = "UPDATE recipe_instances
  SET recipe_instances.recipe_timestamp='$timestamp'
  WHERE recipe_instances.recipe_instance_id='$id'";

if ($conn->query($sql) === TRUE) {
    echo $qty+1;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>
