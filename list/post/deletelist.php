<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$id = $_POST['list_id'];

$sql = "DELETE FROM lists WHERE list_id='".$id."'";
if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$sql2 = "DELETE FROM list_content WHERE list_id='".$id."'";
if ($conn->query($sql2) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error: " . $sql2 . "<br>" . $conn->error;
}

?>