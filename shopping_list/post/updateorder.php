<?php
require_once("/xampp/htdocs/shopping_list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$i = 0;
foreach($_POST['item'] as $value) {
	$sql = "UPDATE master_list SET sort_order='".$i."' WHERE item_id='".$value."'";
	if ($conn->query($sql) === TRUE) {
	    echo "New record created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}	
	$i++;
}

?>