<?php
require_once($_SERVER['DOCUMENT_ROOT']."/shopping_list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();
$sid=$_SESSION['user_id'];

$i = 0;
foreach($_POST['item'] as $value) {
	$sql = "UPDATE items SET sort_order='".$i."' WHERE item_instance_id='".$value."'";
	if ($conn->query($sql) === TRUE) {
	    echo "New record created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}	
	$i++;
}

?>