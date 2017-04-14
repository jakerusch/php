<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$item_name=mysqli_real_escape_string($conn, $_POST['item_name']);
$item_id=mysqli_real_escape_string($conn, $_POST['item_id']);

$sql = "UPDATE master_items
  SET master_items.item_name='$item_name'
  WHERE master_items.item_id='$item_id'";

if ($conn->query($sql) === TRUE) {
    echo 1;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>
