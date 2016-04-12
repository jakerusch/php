<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);

$list_id=$_POST["list_id"];
$item_id=$_POST["item_id"];

// creates new list
$conn = $obj->getConn();
$sql = "INSERT INTO list_content(list_id, item_id, qty, checked_status) VALUES('".$list_id."', '".$item_id."', '1', '0')";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>