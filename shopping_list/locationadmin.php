
<?php
require_once($_SERVER['DOCUMENT_ROOT']."/shopping_list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn=$obj->getConn();
$sid=$_SESSION['user_id'];

?>

			<form class="well" id="addNewLocation">
				<div class="form-group">
					<label for="locationTitle">Add New Master Location</label>
					<input type="locationTitle" class="form-control" id="locationTitle" name="locationTitle" placeholder="Location Name">
				</div>
				<button type="submit" class="btn btn-default" id="addLocation"><span class="glyphicon glyphicon-plus"></span> Add</button>
			</form>

			<ul class="list-group" id="locations">

<?php

$getList = "SELECT location_id, location_name FROM master_locations ORDER BY location_name ASC";
$result = $conn->query($getList);
while($row=$result->fetch_assoc()) {
	echo "<li class=\"list-group-item\" id=\"".$row['location_id']."\">".$row['location_name']."<span class=\"glyphicon glyphicon-trash pull-right\"></span></li>";
}

?>
			</ul>
		</div>
	</div>
</div>
	<script>
	$(function() {
		$("li.list-group-item").click(function(event) {
			var id = $(this).attr("id");
			var title = $(this).text();
			window.location.href = "location.php?id="+id;
		})		
		// add new list
		$("#addNewLocation").submit(function(event) {
			event.preventDefault();
			var myVal = $("#locationTitle").val();
			if(myVal.trim().length>0) {
				AddLocation(myVal);
			}
		})
		// add new record function
		function AddLocation(val) {
			jQuery.ajax({
				type: "POST",
				url: "post/addnewlocationmaster.php",
				data: {location_title: val},
				cache: false,
				success: function(response) {
					window.location.reload(true);
				}
			});
		}
		// delete item
		$("span.glyphicon-trash").click(function(event) {
			// fixes conflict with li.list-group-item click function
			event.preventDefault();
			event.stopPropagation();
			var myID = $(this).closest("li").attr("id");
			var myTitle = $(this).closest("li").text();
			if (confirm('Are you sure you want to delete '+myTitle+'?')) {
				if(confirm('This will delete all instances of '+myTitle+' and cannot be undone.  Are you sure you want to proceed?')) {
					DeleteLocationRecord(myID.replace("item-", ""));
				}
			}
		})
		function DeleteLocationRecord(id) {
			jQuery.ajax({
				type: "POST",
				url: "post/deletelocationmaster.php",
				data: {location_id: id},
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