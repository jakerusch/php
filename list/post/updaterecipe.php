<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$item_type=mysqli_real_escape_string($conn, $_POST['item_type']);
$item_name=mysqli_real_escape_string($conn, $_POST['item_name']);
$item_id=mysqli_real_escape_string($conn, $_POST['item_id']);

switch($item_type) {
  case 'title':
    $sql="UPDATE master_recipes
      SET master_recipes.recipe_name='$item_name'
      WHERE master_recipes.unique_id='$item_id'";
    break;
  case 'ingredients':
    $sql="UPDATE recipe_ingredients
      SET recipe_ingredients.ingredient_text='$item_name'
      WHERE recipe_ingredients.item_id='$item_id'";
    break;
  case 'directions':
    $sql="UPDATE recipe_directions
      SET recipe_directions.directions_text='$item_name'
      WHERE recipe_directions.item_id='$item_id'";
    break;
  case 'hyperlink':
    $sql="UPDATE master_recipes
      SET master_recipes.recipe_location='$item_name'
      WHERE master_recipes.unique_id='$item_id'";
    break;
}

if ($conn->query($sql) === TRUE) {
    echo 1;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>
