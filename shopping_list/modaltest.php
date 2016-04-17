<!-- <?php
require_once($_SERVER['DOCUMENT_ROOT']."/shopping_list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$item_id = $_POST['item_id'];
$qty = $_POST['qty'];

$sql = "UPDATE list_content SET qty='".$qty."' WHERE item_id='".$item_id."'";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?> -->