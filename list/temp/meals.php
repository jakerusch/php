<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn=$obj->getConn();
$sid=$_SESSION['user_id'];

$sql = "SELECT master_meals.meal_name, master_meals.meal_recipe
  FROM master_meals
  WHERE master_meals.user_id='1'";

$result=$conn->query($sql);

while($row=$result->fetch_assoc()) {
  $meal_recipe=$row['meal_recipe'];
  $meal_recipe=str_replace("<p>", "<li class=\"list-group-item\">", $meal_recipe);
  $meal_recipe=str_replace("</p>", "</li>", $meal_recipe);

  echo "<div class=\"panel panel-default\">";
  echo "<div class=\"panel-heading\">".$row['meal_name']."</div>";
  echo "<ul class=\"list-group\">";
  echo $meal_recipe;
  echo "</ul></div>";
}

?>

<script>
</script>
</body>
</html>
