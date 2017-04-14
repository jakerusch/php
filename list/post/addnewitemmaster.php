<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();
$sid=$_SESSION['user_id'];

// clean string
$item_name = mysqli_real_escape_string($conn, $_POST['item_name']);

$sql = "INSERT INTO master_items(user_id, item_name)
  VALUES('$sid', '$item_name')";

if ($conn->query($sql) === TRUE) {
    echo 1;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>
