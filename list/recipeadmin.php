<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn=$obj->getConn();
$sid=$_SESSION['user_id'];

?>

      <form class="well" id="test">
        <button type="submit" class="btn btn-default" id="showImportRecipe">Import</button>
        <button type="submit" class="btn btn-default" id="showAddManually">Add Manually</button>
      </form>

      <form class="well" id="importNewRecipe" style="display:none;">
          <div class="form-group">
              <label for="recipeURL">Import Recipe from URL</label>
              <input type="text" class="form-control" id="recipeURL" name="recipeURL">
          </div>
          <button type="submit" class="btn btn-default" id="importRecipe"><span class="glyphicon glyphicon-plus"></span> Add</button>
      </form>

      <form class="well" id="manuallyAddNewRecipe" style="display:none;">
        <div class="form-group">
          <label for="manualName">Manually Add Recipe</label>
          <input type="text" class="form-control" id="manualName" name="manualName">
        </div>
        <button type="submit" class="btn btn-default" id="manuallyAddRecipe"><span class="glyphicon glyphicon-plus"></span> Add Recipe Title</button>
      </form>

      <ul class="list-group" id="recipes">

<?php

$sql="SELECT master_recipes.unique_id, master_recipes.recipe_name, master_recipes.recipe_id
  FROM master_recipes
  ORDER BY recipe_name ASC";
$result = $conn->query($sql);

while($row=$result->fetch_assoc()) {
	echo "<li class=\"list-group-item\" id=\"".$row['unique_id']."\">".$row['recipe_name']."<span class=\"glyphicon glyphicon-trash pull-right\"></span></li>";
}

?>
			</ul>


		</div>
	</div>
</div>
	<script>
	$(function() {
    // hide buttons on submit to prevent callback
    $('#showImportRecipe').click(function(event) {
      event.preventDefault();
      $('#importNewRecipe').css('display','block');
      $('#manuallyAddNewRecipe').css('display','none');
      $('#showAddManually').prop('disabled', false);
      $('#showImportRecipe').prop('disabled', true);
    });
    $('#showAddManually').click(function(event) {
      event.preventDefault();
      $('#importNewRecipe').css('display','none');
      $('#manuallyAddNewRecipe').css('display','block');
      $('#showAddManually').prop('disabled', true);
      $('#showImportRecipe').prop('disabled', false);
    });
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
      event.preventDefault();
      $('#importRecipe').prop('disabled', true);
      $('#showImportRecipe').prop('disabled', true);
      $('#showAddManually').prop('disabled', true);
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
            $.ajax({
                type: "POST",
                url: "post/getlastreciperecord.php",
                success: function(secondResponse) {
                  // alert(secondResponse);
                  window.location.href="recipe.php?id=" + secondResponse;
                }
            });
          } else {
            alert(response);
          }
          $('#importRecipe').prop('disabled', false);
				}
			});
		}
    // manually add recipe
    $('#manuallyAddNewRecipe').submit(function(event) {
      event.preventDefault();
      $('#manuallyAddRecipe').prop('disabled', true);
      $('#showImportRecipe').prop('disabled', true);
      $('#showAddManually').prop('disabled', true);
      var title = $('#manualName').val();
      if(title.trim().length>0) {
        CreateRecipe(title);
      }
    });
    function CreateRecipe(title) {
      $.ajax({
        type: "POST",
        url: "post/addnewrecipe.php",
        data: {title: title},
        cache: false,
        success: function(response) {
          if(response==1) {
              $.ajax({
                  type: "POST",
                  url: "post/getlastreciperecord.php",
                  success: function(secondResponse) {
                    window.location.href="recipe.php?id=" + secondResponse;
                  }
              });
          } else {
            alert(response);
          }
          $('#manuallyAddRecipe').prop('disabled', false);
          $('#showImportRecipe').prop('disabled', false);
          $('#showAddManually').prop('disabled', false);
        }
      })
    }
    // delete item
		$("span.glyphicon-trash").click(function(event) {
			event.preventDefault();
			event.stopPropagation();
			var myID = $(this).closest("li").attr("id");
			var myTitle = $(this).closest("li").text();
			if (confirm('Are you sure you want to delete '+myTitle+'?')) {
				if(confirm('This will delete all instances of '+myTitle+' and cannot be undone.  Are you sure you want to proceed?')) {
					DeleteRecipeRecord(myID);
				}
			}
		});
    // test end
		function DeleteRecipeRecord(uid) {
			$.ajax({
				type: "POST",
				url: "post/deleterecipemaster.php",
				data: {uid: uid},
				cache: false,
				success: function(response) {
					if(response==1) {
						window.location.reload(true);
					} else {
						alert(response);
					}
				}
			})
		}
	});
	</script>
	</body>
</html>
