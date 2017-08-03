<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn=$obj->getConn();
$sid=$_SESSION['user_id'];

?>

			<form class="well" id="addMeal">
				<div class="form-group">
					<div class="dropdown">
						<button class="btn btn-default dropdown-toggle" type="button" id="dropdown" name="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Add New Meal <span class="caret"></span></button>
					  	<ul class="dropdown-menu" aria-labelledby="dropdown">

<?php

// get all available locations for user to select from
$sql = "SELECT master_recipes.recipe_name, master_recipes.unique_id
  FROM master_recipes
  WHERE master_recipes.user_id='$sid'
  ORDER BY master_recipes.recipe_name ASC";
$result=$conn->query($sql);
while($row=$result->fetch_assoc()) {
	echo '<li id="'.$row["unique_id"].'"><a href="#">'.$row["recipe_name"].'</a></li>';
}

?>

					  	</ul>
					</div>
			  	</div>
			</form>

<?php

$dates="SELECT DATE_FORMAT(recipe_instances.recipe_timestamp, '%a, %b %e') as date, COUNT(recipe_instances.recipe_timestamp) as count,
  recipe_instances.recipe_timestamp
  FROM recipe_instances
  WHERE recipe_instances.hidden=FALSE
  GROUP BY date
  ORDER BY recipe_instances.recipe_timestamp ASC";
$dateResult=$conn->query($dates);

while($dateRow=$dateResult->fetch_assoc()) {
  echo '<ul class="list-group">';
  // get all location instances that have been created by the user
  $getList="SELECT master_recipes.recipe_name, master_recipes.unique_id, recipe_instances.recipe_instance_id,
    DATE_FORMAT(recipe_instances.recipe_timestamp, '%a, %b %e') as timestamp, recipe_instances.recipe_timestamp,
    recipe_instances.hidden
    FROM recipe_instances
    INNER JOIN master_recipes ON master_recipes.unique_id=recipe_instances.recipe_id
    WHERE master_recipes.user_id='$sid'
    AND DATE_FORMAT(recipe_instances.recipe_timestamp, '%a, %b %e')='".$dateRow['date']."'
    AND recipe_instances.hidden=FALSE
    ORDER BY recipe_instances.recipe_timestamp ASC";
  $result = $conn->query($getList);
  $i=0;
  while($row=$result->fetch_assoc()) {
		date_default_timezone_set('America/Chicago');
		$today = new DateTime();
		$today->setTime(0,0,0);
		// $today=date_timezone_set($today, timezone_open('America/Chicago'));
		$itemDate = new DateTime($dateRow['recipe_timestamp']);
		$itemDate->setTime(0,0,0);
		// $itemDate=date_timezone_set($itemDate, timezone_open('America/Chicago'));
		$difference = $today->diff($itemDate)->format("%r%a");
		$color='disabled';

		if($difference==0) {
			$date='Today';
		} elseif($difference==-1) {
			$date='Yesterday';
			$color='list-group-item-danger';
		} elseif($difference==1) {
			$date='Tomorrow';
		} elseif($difference<-1) {
			$date=$row['timestamp'];
			$color='list-group-item-danger';
		} else {
			$date=$row['timestamp'];
		}

    if($i==0) {
      echo '<li class="list-group-item '.$color.'">'.$date.'<span class="badge">'.$dateRow['count'].'</span></li>';
    }
    echo '<li class="list-group-item" id="'.$row['unique_id'].'" instance_id="'.$row['recipe_instance_id'].'" timestamp="'.$row['recipe_timestamp'].'" name="'.$row['recipe_name'].'"><span class="glyphicon glyphicon-calendar pull-left" data-toggle="modal" data-target="#myModal" id="'.$row['recipe_instance_id'].'"></span>'.$row['recipe_name'].'<span class="glyphicon glyphicon-trash pull-right hidden"></span><span class="glyphicon glyphicon-unchecked pull-right"></span></li>';
    $i++;
  }
  echo '</ul>';
}

?>

<div class="container">
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="currentDate"></h4>
        </div>
        <div class="modal-body">
          <p>Select Due Date</p>
          <input type="text" id="datepicker" value="" readonly></input>
					<input hidden id="rid"></input>
        </div>
        <div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal" id="saveModal">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

		</div>
	</div>
</div>
	<script>
	$(function() {
		$('#datepicker').datepicker();
		$(".dropdown li").click(function() {
		    var id = $(this).attr("id");
		    AddRecipe(id);
		});
		function AddRecipe(recipe_id) {
			jQuery.ajax({
				type: "POST",
				url: "post/addnewmenu.php",
				data: {recipe_id: recipe_id},
				cache: false,
				success: function(response) {
					window.location.reload(true);
				}
			})
		}
		$('#saveModal').click(function(event) {
			var dp = $('#datepicker').datepicker('getDate');
			var ts = moment(new Date(dp)).format("YYYY-MM-DD HH:mm:ss");
			var id = $('#rid').val();
			$.ajax({
				type: "POST",
				url: "post/updaterecipeinstancetimestamp.php",
				data: {id: id, timestamp: ts},
				cache: false,
				success: function(response) {
					window.location.reload(true);
				}
			})
		});
		// fix for modal
		$("li.list-group-item").not('.disabled,.list-group-item-danger').click(function(event) {
			var id = $(this).attr("id");
			if(event.target.nodeName=='LI') {
				window.location.href = "recipe.php?id="+id;
			} else {
				var text = $(this).attr('name');
				var rid = $(this).attr('instance_id');
				var ts = new Date($(this).attr('timestamp'));
				var fDate = moment(ts).format('MM/DD/YYYY');
				$('#currentDate').text(text);
				$('#datepicker').val(fDate);
				$('#rid').val(rid);
			}
		});
    // // taphold
    // $('html').on('taphold', function(event) {
		// 	if($('.glyphicon-trash').hasClass('hidden')) {
    //       $('.glyphicon-trash').removeClass('hidden');
		// 			$('.glyphicon-unchecked').addClass('hidden');
    //     } else {
    //       $('.glyphicon-trash').addClass('hidden');
		// 			$('.glyphicon-unchecked').removeClass('hidden');
    //     }
    // });
		var down;
    var up;
    $('html')
    .mousedown(function() {
      down = new Date().getTime();
    })
    .mouseup(function() {
      up = new Date().getTime();
      var timesince = up - down;
      if(timesince>750) {
        if($('.glyphicon-trash').hasClass('hidden')) {
            $('.glyphicon-trash').removeClass('hidden');
  					$('.glyphicon-unchecked').addClass('hidden');
          } else {
            $('.glyphicon-trash').addClass('hidden');
  					$('.glyphicon-unchecked').removeClass('hidden');
          }
      }
    });
		// check item
		$('.glyphicon-unchecked').click(function(event) {
			event.preventDefault();
			event.stopPropagation();
			var myID = $(this).closest('li').attr('instance_id');
			var myTitle = $(this).closest('li').contents().get(1).nodeValue;
			var ts = moment($(this).closest('li').attr('timestamp')).format("MMMM DD");
			if(confirm('Do you want to archive the ' + ts + ' instance of ' + myTitle + '?')) {
				ArchiveRecipe(myID);
			}
		});
		// archive recipe
		function ArchiveRecipe(recipe_instance_id) {
			$.ajax({
				type: "POST",
				url: "post/updatemealhidden.php",
				data: {id: recipe_instance_id},
				cache: false,
				success: function(response) {
					window.location.reload(true);
				}
			})
		}
		// delete item
		$("span.glyphicon-trash").click(function(event) {
			// fixes conflict with li.list-group-item click function
			event.preventDefault();
			event.stopPropagation();
			var myID = $(this).closest("li").attr("instance_id");
			var myTitle = $(this).closest("li").text();
			if (confirm('Are you sure you want to delete '+myTitle+'?')) {
				DeleteMealInstance(myID);
			}
		})
		function DeleteMealInstance(recipe_instance_id) {
			$.ajax({
				type: "POST",
				url: "post/deletemealinstance.php",
				data: {id: recipe_instance_id},
				cache: false,
				success: function(response) {
					window.location.reload(true);
				}
			})
		}
		});
	</script>
	</body>
</html>
