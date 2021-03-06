<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn=$obj->getConn();
$sid=$_SESSION['user_id'];

?>

			<form class="well" id="addNewLocation">
				<div class="form-group">
					<label for="locationTitle">Add Location</label>
					<input type="text" class="form-control" id="locationTitle" name="locationTitle" placeholder="Location Name">
				</div>
				<button type="submit" class="btn btn-default" id="addLocation"><span class="glyphicon glyphicon-plus"></span> Add</button>
			</form>

			<ul class="list-group" id="locations">

<?php

$getList = "SELECT master_locations.location_id, master_locations.location_name FROM master_locations WHERE master_locations.user_id='".$sid."' ORDER BY location_name ASC";
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
		$('#locationTitle').focus();
		// // taphold
    // $('html').on('taphold', function(event) {
    //   var target = $(event.target);
    //   if($('#addNewLocation').hasClass('hidden')) {
    //     $('#addNewLocation').removeClass('hidden');
		// 		// set focus on input box
		// 		$('#locationTitle').focus();
    //   } else {
    //     $('#addNewLocation').addClass('hidden');
    //   }
    // });
		// direct to location.php when li is clicked
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
			$.ajax({
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
			$.ajax({
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
