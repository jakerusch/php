<?php
require_once($_SERVER['DOCUMENT_ROOT']."/shopping_list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn=$obj->getConn();
$sid=$_SESSION['user_id'];

?>

			<form class="well">
				<div class="form-group">
					<div class="dropdown">
						<button class="btn btn-default dropdown-toggle" type="button" id="dropdown" name="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Create New List <span class="caret"></span></button>
					  	<ul class="dropdown-menu" aria-labelledby="dropdown">

<?php

$sql = "SELECT location_id, user_id, location_name FROM master_locations WHERE user_id='".$sid."'";
$result=$conn->query($sql);
while($row=$result->fetch_assoc()) {
	echo "<li id=\"".$row["location_id"]."\"><a href=\"#\">".$row["location_name"]."</a></li>";
}

?>

					  	</ul>
					</div>
			  	</div>
			</form>

 			<ul class="list-group" id="myLists">

<?php

$getList = "SELECT locations.location_instance_id, locations.location_id, DATE_FORMAT(locations.location_timestamp, '%c/%e/%Y') as timestamp, master_locations.location_name
	FROM locations
	LEFT JOIN master_locations ON locations.location_id = master_locations.location_id
	WHERE master_locations.user_id = '".$sid."'";
	

$result = $conn->query($getList);
while($row=$result->fetch_assoc()) {
	echo "<li class=\"list-group-item\" id=\"".$row['location_instance_id']."\">".$row['location_name']." ".$row['timestamp']."<span class=\"glyphicon glyphicon-trash pull-right\"></span></li>";
}

?>

			</ul>
		</div>
	</div>
</div>
	<script>
	$(function() {
		$(".dropdown li").click(function() {
		    var id = $(this).attr("id");
		    AddList(id);
		});		
		$("li.list-group-item").click(function(event) {
			var id = $(this).attr("id");
			var title = $(this).text();
			window.location.href = "list.php?id="+id;
		})		
		function AddList(location_id) {
			jQuery.ajax({
				type: "POST",
				url: "post/addnewlistlocation.php",
				data: {location_id: location_id},
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
			var myID = $(this).closest("li").attr("id");
			var myTitle = $(this).closest("li").text();
			if (confirm('Are you sure you want to delete '+myTitle+'?')) {
				DeleteListLocation(myID.replace("item-", ""));
			}
		})
		function DeleteListLocation(location_instance_id) {
			jQuery.ajax({
				type: "POST",
				url: "post/deletelistlocation.php",
				data: {location_instance_id: location_instance_id},
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