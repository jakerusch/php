<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn=$obj->getConn();
$sid=$_SESSION['user_id'];

$sql="SELECT master_recipes.recipe_name, master_recipes.unique_id, DATE_FORMAT(recipe_instances.recipe_timestamp, '%a, %b %e') as date,
  recipe_instances.recipe_timestamp
  FROM recipe_instances
  INNER JOIN master_recipes ON master_recipes.unique_id=recipe_instances.recipe_id
  WHERE recipe_instances.hidden='1'
  ORDER BY recipe_instances.recipe_timestamp DESC";
$result=$conn->query($sql);

echo '<div class="well"><h1 class="text-center">Meal History</h1></div>';
echo '<div class="well"><table class="table table-striped"><thead><tr><th>Recipe</th><th>Meal Date</th><th>Last 30</th><th>Total Count</th></tr></thead><tbody>';

while($row=$result->fetch_assoc()) {

  $sql2="SELECT COUNT(*) as 'total', recipe_instances.recipe_timestamp
    FROM recipe_instances
    WHERE recipe_id='".$row['unique_id']."'
    AND recipe_instances.recipe_timestamp BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
    AND hidden='1'
    LIMIT 1";
  $result2=$conn->query($sql2);
  $row2=$result2->fetch_assoc();

  $sql3="SELECT COUNT(*) as 'total', recipe_instances.recipe_timestamp
    FROM recipe_instances
    WHERE recipe_id='".$row['unique_id']."'
    AND hidden='1'
    LIMIT 1";
  $result3=$conn->query($sql3);
  $row3=$result3->fetch_assoc();

  echo '<tr><td id="'.$row['unique_id'].'" class="link">'.$row['recipe_name'].'</td><td>'.$row['date'].'</td><td>'.$row2['total'].'</td><td>'.$row3['total'].'</td></tr>';
}

echo '</tbody></table></div>';

?>

		</div>
	</div>
</div>
	<script>
  $(function() {
    $(".link").click(function() {
      var id = $(this).attr('id');
      window.location.href="recipe.php?id=" + id;
    });
  });
	</script>
	</body>
</html>
