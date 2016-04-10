<?php
require_once("/xampp/htdocs/shopping_list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);

$listTitle=$_POST["listTitle"];

// creates new list
$conn = $obj->getConn();
$sql = "INSERT INTO lists(list_title) VALUES('".$listTitle."')";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>