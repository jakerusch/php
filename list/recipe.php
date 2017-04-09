<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn=$obj->getConn();
$sid=$_SESSION['user_id'];
$id=$_GET['id'];

echo '<div class="well">';
echo '<ul class="list-group">';

// get title
$sql="SELECT master_recipes.recipe_name
  FROM master_recipes
  WHERE master_recipes.unique_id='$id'
  AND master_recipes.user_id='$sid'
  LIMIT 1";
$result=$conn->query($sql);
while($row=$result->fetch_assoc()) {
  echo '<li class="list-group-item">'.$row['recipe_name'].'</li>';
}

echo '</ul>';
echo '<ul class="list-group">';

$sql2="SELECT recipe_ingredients.ingredient_text
  FROM recipe_ingredients
  WHERE recipe_ingredients.unique_id='$id'
  ORDER BY recipe_ingredients.ingredient_order ASC";
$result2=$conn->query($sql2);
while($row2=$result2->fetch_assoc()) {
  echo '<li class="list-group-item">'.$row2['ingredient_text'].'</li>';
}

echo '</ul>';
echo '<ul class="list-group">';

$sql3="SELECT recipe_directions.directions_text
  FROM recipe_directions
  WHERE recipe_directions.unique_id='$id'
  ORDER BY recipe_directions.directions_order ASC";
$result3=$conn->query($sql3);
while($row3=$result3->fetch_assoc()) {
  echo '<li class="list-group-item">'.$row3['directions_text'].'</li>';
}

echo '</ul>';
echo '</div>';
?>

	</body>
</html>
