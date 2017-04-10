<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn=$obj->getConn();
$sid=$_SESSION['user_id'];
$id=$_GET['id'];

echo'<div class="well">';
echo '<ul class="list-group" id="title">';

// get title
$sql="SELECT master_recipes.recipe_name, master_recipes.unique_id
  FROM master_recipes
  WHERE master_recipes.unique_id='$id'
  AND master_recipes.user_id='$sid'
  LIMIT 1";
$result=$conn->query($sql);
while($row=$result->fetch_assoc()) {
  echo '<li class="list-group-item" id="'.$row['unique_id'].'">'.$row['recipe_name'].'</li>';
}

echo '</ul>';
echo '<ul class="list-group" id="ingredients">';

$sql2="SELECT recipe_ingredients.ingredient_text, recipe_ingredients.unique_id, recipe_ingredients.ingredient_order
  FROM recipe_ingredients
  WHERE recipe_ingredients.unique_id='$id'
  ORDER BY recipe_ingredients.ingredient_order ASC";
$result2=$conn->query($sql2);
while($row2=$result2->fetch_assoc()) {
  echo '<li class="list-group-item" id="'.$row2['unique_id'].'" sort_order="'.$row2['ingredient_order'].'">'.$row2['ingredient_text'].'</li>';
}

echo '</ul>';
echo '<ul class="list-group" id="directions">';

$sql3="SELECT recipe_directions.directions_text, recipe_directions.unique_id, recipe_directions.directions_order
  FROM recipe_directions
  WHERE recipe_directions.unique_id='$id'
  ORDER BY recipe_directions.directions_order ASC";
$result3=$conn->query($sql3);
while($row3=$result3->fetch_assoc()) {
  echo '<li class="list-group-item" id="'.$row3['unique_id'].'" sort_order="'.$row3['directions_order'].'">'.$row3['directions_text'].'</li>';
}

echo '</ul>';
echo '</div>';
?>


<script>
$(function() {
  // // add new record function
  // function AddItem(val) {
  //   $.ajax({
  //     type: "POST",
  //     url: "post/addnewitemmaster.php",
  //     data: {item_name: val},
  //     cache: false,
  //     success: function(response) {
  //       if(response==1) {
  //         window.location.reload(true);
  //       } else {
  //         alert(response);
  //       }
  //     }
  //   });
  // }
  // delete item
  $("span.glyphicon-trash").click(function(event) {
    // fixes conflict with li.list-group-item click function
    event.preventDefault();
    event.stopPropagation();
    var myID = $(this).closest("li").attr("id");
    var myTitle = $(this).closest("li").text();
    if (confirm('Are you sure you want to delete '+myTitle+'?')) {
      if(confirm('This will delete all instances of '+myTitle+' and cannot be undone.  Are you sure you want to proceed?')) {
        // DeleteItemRecord(myID.replace("item-", ""));
      }
    }
  })
  // double-click
  var mylatesttap;
  $("#ingredients li,#directions li,#title li").click(function() {
    var now = new Date().getTime();
    var timesince = now - mylatesttap;
    if((timesince<600) && (timesince>0)) {
      var item_id = $(this).attr('id');
      var item_type = $(this).closest('ul').attr('id');
      var currentName = $(this).text();
      var sort_order = $(this).attr('sort_order');
      var item_name = prompt("Existing name", currentName);
      if (item_name!=null) {
        if(confirm("Do you want to change " + currentName + " to " + item_name + "?")) {
          UpdateRecipe(item_type, sort_order, item_id, item_name);
        }
      }
     }
     mylatesttap = new Date().getTime();
  })
  function UpdateRecipe(item_type, sort_order, item_id, item_name) {
    $.ajax({
      type: "POST",
      url: "post/updaterecipe.php",
      data: {item_type: item_type, sort_order: sort_order, item_id: item_id, item_name: item_name},
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
  function UpdateDirection(item_id, item_name) {
    $.ajax({
      type: "POST",
      url: "post/updaterecipedirection.php",
      data: {item_id: item_id, item_name: item_name},
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
  // test end
  function DeleteItemRecord(id) {
    $.ajax({
      type: "POST",
      url: "post/deleteitemmaster.php",
      data: {item_id: id},
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
