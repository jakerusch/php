<?php
require_once($_SERVER['DOCUMENT_ROOT']."/shopping_list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$list_id=$_POST['list_id'];
$item_id=$_POST['item_id'];

$sql = "DELETE FROM list_content WHERE list_id='".$list_id."' AND item_id='".$item_id."'";
if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>