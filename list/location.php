<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn=$obj->getConn();
$sid=$_SESSION['user_id'];
$id=$_GET['id'];

$sql = "SELECT location_name FROM master_locations WHERE location_id='".$id."' LIMIT 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

?>

			<form class="well">
				<div class="form-group">
					<div class="text-center">

<?php 
	echo "<h3>".$row["location_name"]."</h3>";
?>

					</div>
					<div class="dropdown">
					<button class="btn btn-default dropdown-toggle" type="button" id="dropdown" name="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Add <span class="caret"></span></button>
				  	<ul class="dropdown-menu" aria-labelledby="dropdown">

<?php

$dropdown = "SELECT master_items.item_name, master_items.item_id
	FROM master_items
	WHERE master_items.item_id NOT IN 
		(SELECT master_items.item_id
			FROM master_items
			LEFT JOIN item_instances ON master_items.item_id=item_instances.item_id
			WHERE item_instances.location_id='".$id."')
			ORDER BY master_items.item_name ASC";

$result2=$conn->query($dropdown);
while($row2=$result2->fetch_assoc()) {
	echo "<li id=\"".$row2["item_id"]."\"><a href=\"#\">".$row2["item_name"]."</a></li>";	
}	

?>

					</ul>
					</div>
			  	</div>
			</form>

			<ul class="list-group" id="sortable">

<?php

$getList = "SELECT item_instances.item_instance_id, master_items.item_name, item_instances.sort_order
	FROM master_locations
	LEFT JOIN item_instances ON master_locations.location_id=item_instances.location_id
	LEFT JOIN master_items ON item_instances.item_id=master_items.item_id
	WHERE master_locations.user_id='".$sid."'
	AND item_instances.item_instance_id IS NOT NULL
	AND master_locations.location_id='".$id."'
	ORDER BY item_instances.sort_order ASC";

$result3 = $conn->query($getList);
while($row3=$result3->fetch_assoc()) {
	echo "<li class=\"list-group-item\" id=\"item-".$row3['item_instance_id']."\"><span class=\"glyphicon glyphicon-menu-hamburger pull-left\"></span> ".$row3['item_name']."<span class=\"glyphicon glyphicon-trash pull-right\"></span></li>";
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
		    AddListItem(id);
		})
		function AddListItem(item_id) {
			jQuery.ajax({
				type: "POST",
				url: "post/addnewlistitemmaster.php",
				data: {item_id: item_id, location_id: "<?php echo $id; ?>"},
				cache: false,
				success: function(response) {
					window.location.reload(true);
				}
			})
		}		
		// sortable 
		$("#sortable").sortable({
			handle: "span.glyphicon-menu-hamburger",
			stop: function(event, ui) {
				var data = $(this).sortable("serialize");
				$.ajax({
					type: "POST",
					url: "post/updatelistordermaster.php",
					data: data,
					cache: false,
					success: function(response) {
						window.location.reload(true);
					}
				});
			}
		})
		$("span").click(function(event) {
			var id = $(this).parent().attr("id");
			if ($(this).hasClass("glyphicon-trash")) {
				var myTitle = $(this).parent().text();
				if (confirm('Are you sure you want to delete '+myTitle+'?')) {
					DeleteRecord(id.replace("item-", ""));
				}
			}
		})
		function DeleteRecord(item_instance_id) {
			jQuery.ajax({
				type: "POST",
				url: "post/deletelistmaster.php",
				data: {item_instance_id: item_instance_id, location_instance_id: "<?php echo $id; ?>"},
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