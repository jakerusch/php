<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();
$sid=$_SESSION['user_id'];

$uid=$_POST['uid'];

$sql1 = "DELETE recipe_directions
  FROM recipe_directions
  WHERE recipe_directions.unique_id='$uid'";

$sql2 = "DELETE recipe_ingredients
  FROM recipe_ingredients
  WHERE recipe_ingredients.unique_id='$uid'";

$sql3 = "DELETE master_recipes
  FROM master_recipes
  WHERE master_recipes.unique_id='$uid'";

if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE && $conn->query($sql3) === TRUE) {
    echo 1;
} else {
    echo "Error: " . $sql1 . "<br>" . $conn->error;
    echo "Error: " . $sql2 . "<br>" . $conn->error;
    echo "Error: " . $sql3 . "<br>" . $conn->error;
}

?>
