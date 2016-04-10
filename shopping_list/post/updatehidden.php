<?php
require_once($_SERVER['DOCUMENT_ROOT']."/shopping_list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$list_id=$_POST['list_id'];
$item_id=$_POST['item_id'];
$status=$_POST['status'];

$sql = "UPDATE list_content SET checked_status='".$status."' WHERE item_id='".$item_id."' AND list_id='".$list_id."'";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>