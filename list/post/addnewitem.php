<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$title = $_POST['title'];

$result = $conn->query("SELECT MAX(sort_order) FROM master_list");
$row = mysqli_fetch_row($result);
$max = $row[0] + 1;

$sql = "INSERT INTO master_list(title, sort_order) VALUES('".$title."', '".$max."')";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>