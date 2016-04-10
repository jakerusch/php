<?php
require_once("/xampp/htdocs/shopping_list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$id = $_POST['item_id'];

$sql = "DELETE FROM master_list WHERE item_id='".$id."'";
if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>