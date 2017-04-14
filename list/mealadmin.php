<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn=$obj->getConn();
$sid=$_SESSION['user_id'];

?>

<form class="well">
  <div class="form-group">
    <div class="dropdown">
      <button class="btn btn-default dropdown-toggle" type="button" id="dropdown" name="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Create New Meal <span class="caret"></span></button>
        <ul class="dropdown-menu" aria-labelledby="dropdown">

<?php

// get all available locations for user to select from
$sql = "SELECT master_recipes.recipe_name, master_recipes.unique_id
  FROM master_recipes";
$result=$conn->query($sql);
while($row=$result->fetch_assoc()) {
echo "<li id=\"".$row["unique_id"]."\"><a href=\"#\">".$row["recipe_name"]."</a></li>";
}

?>

        </ul>
    </div>
    </div>
</form>

      <!-- <form class="well" id="importNewRecipe">
          <div class="form-group">
              <label for="recipeURL">Import Recipe from URL</label>
              <input type="text" class="form-control" id="recipeURL" name="recipeURL">
          </div>
          <button type="submit" class="btn btn-default" id="importRecipe"><span class="glyphicon glyphicon-plus"></span> Add</button>
      </form> -->

      <ul class="list-group" id="meals">

<?php

$sql="SELECT master_meals.unique_id, master_meals.master_recipe_id, master_recipes.recipe_name
  FROM master_meals
  INNER JOIN master_recipes ON master_meals.master_recipe_id=master_recipes.unique_id";
$result = $conn->query($sql);

while($row=$result->fetch_assoc()) {
	echo "<li class=\"list-group-item\" id=\"".$row['unique_id']."\">".$row['recipe_name']."<span class=\"glyphicon glyphicon-trash pull-right\"></span></li>";
}

?>
			</ul>


		</div>
	</div>
</div>
	<!-- <script>
	$(function() {
		// set cursor to input box
		$('#recipeURL').focus();
    // direct to location.php when li is clicked
		$("li.list-group-item").click(function(event) {
			var id = $(this).attr("id");
			var title = $(this).text();
			window.location.href = "recipe.php?id="+id;
		})
		// add new list
		$("#importNewRecipe").submit(function(event) {
      // prevent callback
      $('#importRecipe').prop('disabled', true);
			event.preventDefault();
			var url = $("#recipeURL").val();
			if(url.trim().length>0) {
				ImportRecipe(url);
			}
		});
		// add new recipe function
		function ImportRecipe(url) {
			$.ajax({
				type: "POST",
				url: "post/scraperecipe.php",
				data: {uid: url},
				cache: false,
				success: function(response) {
          if(response==1) {
            window.location.reload(true);
          } else {
            alert(response);
          }
          $('#importRecipe').prop('disabled', false);
				}
			});
		}
	});
	</script> -->
	</body>
</html>
