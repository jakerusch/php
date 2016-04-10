<?php
require_once("/xampp/htdocs/shopping_list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$id = $_POST['list_id'];

$sql = "DELETE FROM lists WHERE list_id='".$id."'";
if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>