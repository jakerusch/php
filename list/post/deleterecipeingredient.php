<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$item_id = $_POST['item_id'];

$sql = "DELETE recipe_ingredients
	FROM recipe_ingredients
	WHERE item_id='$item_id'";

if ($conn->query($sql) === TRUE) {
	echo 1;
} else {
	echo "Error: " . $sql1 . "<br>" . $conn->error;
}

?>
