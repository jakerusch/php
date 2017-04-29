<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn=$obj->getConn();
$sid=$_SESSION['user_id'];

$sql="SELECT  master_recipes.recipe_name, DATE_FORMAT(recipe_instances.recipe_timestamp, '%a, %b %e') as date,
  recipe_instances.recipe_timestamp
  FROM recipe_instances
  INNER JOIN master_recipes ON master_recipes.unique_id=recipe_instances.recipe_id
  WHERE recipe_instances.hidden='1'
  ORDER BY recipe_instances.recipe_timestamp DESC";
$result=$conn->query($sql);

echo '<div class="well"><h1 class="text-center">Meal History</h1></div>';

echo '<div class="well"><table class="table table-striped"><thead><tr><th>Recipe</th><th>Meal Date</th></tr></thead><tbody>';

while($row=$result->fetch_assoc()) {
  echo '<tr><td>'.$row['recipe_name'].'</td><td>'.$row['date'].'</td></tr>';
}

echo '</tbody></table></div>';

?>

		</div>
	</div>
</div>
	<script>
  $(function() {
  });
	</script>
	</body>
</html>
