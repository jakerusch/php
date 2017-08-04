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
$sql="SELECT master_recipes.recipe_name, master_recipes.unique_id, master_recipes.recipe_location
  FROM master_recipes
  WHERE master_recipes.unique_id='$id'
  AND master_recipes.user_id='$sid'
  LIMIT 1";
$result=$conn->query($sql);
$url="";
while($row=$result->fetch_assoc()) {
  echo '<li class="list-group-item" id="'.$row['unique_id'].'" sort_order="0">'.$row['recipe_name'].'</li>';
  $url=$row['recipe_location'];
}

echo '</ul>';
echo '<div class="list-group hidden hide_button"><button type="submit" class="btn btn-default" id="addIngredient"><span class="glyphicon glyphicon-plus"></span> Add New Ingredient</button></div>';
echo '<ul class="list-group" id="ingredients">';

$sql2="SELECT recipe_ingredients.ingredient_text, recipe_ingredients.unique_id, recipe_ingredients.ingredient_order, recipe_ingredients.item_id
  FROM recipe_ingredients
  WHERE recipe_ingredients.unique_id='$id'
  ORDER BY recipe_ingredients.ingredient_order ASC";
$result2=$conn->query($sql2);
while($row2=$result2->fetch_assoc()) {
  echo '<li class="list-group-item" id="item-'.$row2['item_id'].'"><span class="glyphicon glyphicon-menu-hamburger pull-left hidden"></span>'.$row2['ingredient_text'].'<span class="glyphicon glyphicon-trash pull-right hidden"></span></li>';
}

echo '</ul>';
echo '<div class="list-group hidden hide_button"><button type="submit" class="btn btn-default" id="addDirection"><span class="glyphicon glyphicon-plus"></span> Add New Direction</button></div>';
echo '<ul class="list-group" id="directions">';

$sql3="SELECT recipe_directions.directions_text, recipe_directions.unique_id, recipe_directions.directions_order, recipe_directions.item_id
  FROM recipe_directions
  WHERE recipe_directions.unique_id='$id'
  ORDER BY recipe_directions.directions_order ASC";
$result3=$conn->query($sql3);
while($row3=$result3->fetch_assoc()) {
  echo '<li class="list-group-item" id="item-'.$row3['item_id'].'"><span class="glyphicon glyphicon-menu-hamburger pull-left hidden"></span>'.$row3['directions_text'].'<span class="glyphicon glyphicon-trash pull-right hidden"></span></li>';
}

echo '</ul>';
echo '<ul class="list-group" id="hyperlink"><li class="list-group-item" id="item-'.$id.'"><a href="'.$url.'" target="_blank"> '.$url.'</a></li></ul>';
echo '</div>';
?>


<script>
$(function() {
  // delete item
  $("#ingredients span.glyphicon-trash").click(function(event) {
    // fixes conflict with li.list-group-item click function
    event.preventDefault();
    event.stopPropagation();
    var myID = $(this).closest("li").attr("id");
    var myTitle = $(this).closest("li").text();
    if (confirm('Are you sure you want to delete '+myTitle+'?')) {
      if(confirm('This will delete all instances of '+myTitle+' and cannot be undone.  Are you sure you want to proceed?')) {
        DeleteIngredient(myID.replace("item-", ""));
      }
    }
  })
  function DeleteIngredient(id) {
    $.ajax({
      type: "POST",
      url: "post/deleterecipeingredient.php",
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
  // delete item
  $("#directions span.glyphicon-trash").click(function(event) {
    // fixes conflict with li.list-group-item click function
    event.preventDefault();
    event.stopPropagation();
    var myID = $(this).closest("li").attr("id");
    var myTitle = $(this).closest("li").text();
    if (confirm('Are you sure you want to delete '+myTitle+'?')) {
      if(confirm('This will delete all instances of '+myTitle+' and cannot be undone.  Are you sure you want to proceed?')) {
        DeleteDirection(myID.replace("item-", ""));
      }
    }
  })
  function DeleteDirection(id) {
    $.ajax({
      type: "POST",
      url: "post/deleterecipedirection.php",
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
  $('#addIngredient').click(function(event) {
    event.preventDefault();
    var title = prompt("Add Ingredient");
    if (title!=null) {
      if(confirm("Do you want to add " + title + " to ingredient list?")) {
        InsertIngredient(title);
      }
    }
  });
  function InsertIngredient(title) {
    $.ajax({
      type: "POST",
      url: "post/addnewrecipeingredient.php",
      data: {id: "<?php echo $id; ?>",  title: title},
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
  $('#addDirection').click(function(event) {
    event.preventDefault();
    var title = prompt("Add Direction");
    if (title!=null) {
      if(confirm("Do you want to add " + title + " to directions list?")) {
        InsertDirection(title);
      }
    }
  });
  function InsertDirection(title) {
    $.ajax({
      type: "POST",
      url: "post/addnewrecipedirection.php",
      data: {id: "<?php echo $id; ?>",  title: title},
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
  // sortable
  $("#ingredients").sortable({
    handle: "span.glyphicon-menu-hamburger",
    stop: function(event, ui) {
      var data = $(this).sortable("serialize");
      $.ajax({
        type: "POST",
        url: "post/updaterecipeingredientsorder.php",
        data: data,
        cache: false,
        success: function(response) {
          window.location.reload(false);
        }
      });
    }
  })
  // sortable
  $("#directions").sortable({
    handle: "span.glyphicon-menu-hamburger",
    stop: function(event, ui) {
      var data = $(this).sortable("serialize");
      $.ajax({
        type: "POST",
        url: "post/updaterecipedirectionsorder.php",
        data: data,
        cache: false,
        success: function(response) {
          window.location.reload(false);
        }
      });
    }
  })
  var down;
  var up;
  // hold (for desktop)
  $('html')
  .mousedown(function() {
    down = new Date().getTime();
  })
  .mouseup(function() {
    up = new Date().getTime();
    var timesince = up - down;
    if(timesince>750) {
      var target = $(event.target);
      if(target.is(':not(span)')) {
        if($('.hide_button').hasClass('hidden')) {
          $('.glyphicon-menu-hamburger').removeClass('hidden');
          $('.glyphicon-edit').removeClass('hidden');
          $('.glyphicon-trash').removeClass('hidden');
          $('.hide_button').removeClass('hidden');
        } else {
          $('.glyphicon-menu-hamburger').addClass('hidden');
          $('.glyphicon-edit').addClass('hidden');
          $('.glyphicon-trash').addClass('hidden');
          $('.hide_button').addClass('hidden');
        }
      }
    }
  });
  // taphold (for mobile)
  $('html').on('taphold', function(event) {
    var target = $(event.target);
    if(target.is(':not(span)')) {
      if($('.hide_button').hasClass('hidden')) {
        $('.glyphicon-menu-hamburger').removeClass('hidden');
        $('.glyphicon-edit').removeClass('hidden');
        $('.glyphicon-trash').removeClass('hidden');
        $('.hide_button').removeClass('hidden');
      } else {
        $('.glyphicon-menu-hamburger').addClass('hidden');
        $('.glyphicon-edit').addClass('hidden');
        $('.glyphicon-trash').addClass('hidden');
        $('.hide_button').addClass('hidden');
      }
    }
  });
  // double-click
  var mylatesttap;
  $(".list-group-item").click(function() {
    var now = new Date().getTime();
    var timesince = now - mylatesttap;
    if((timesince<600) && (timesince>0)) {
      var item_id = $(this).attr('id');
      var item_type = $(this).closest('ul').attr('id');
      var currentName = $(this).text();
      // var sort_order = $(this).attr('sort_order');
      var item_name = prompt("Existing text", currentName);
      if (item_name!=null) {
        if(confirm("Do you want to change " + currentName + " to " + item_name + "?")) {
          UpdateRecipe(item_type, item_id.replace("item-",""), item_name);
        }
      }
     }
     mylatesttap = new Date().getTime();
  })
  function UpdateRecipe(item_type, item_id, item_name) {
    $.ajax({
      type: "POST",
      url: "post/updaterecipe.php",
      data: {item_type: item_type, item_id: item_id, item_name: item_name},
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
});
</script>
	</body>
</html>
