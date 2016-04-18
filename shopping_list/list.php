<?php
require_once($_SERVER['DOCUMENT_ROOT']."/shopping_list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn=$obj->getConn();
$sid=$_SESSION['user_id'];
$id=$_GET['id'];

// get current location instance
$sql = "SELECT location_instances.location_instance_id, DATE_FORMAT(location_instances.location_timestamp, '%c/%e/%Y') as timestamp, master_locations.location_name, location_instances.menu_hide
	FROM location_instances
	LEFT JOIN master_locations ON location_instances.location_id = master_locations.location_id 
	WHERE location_instances.location_instance_id='".$id."'";

$result = $conn->query($sql);
$row = $result->fetch_assoc();
$menu_hide=$row['menu_hide'];

?>

			<form class="well">
				<div class="form-group">
					<div class="text-center">

<?php
	echo "<h3>".$row["location_name"]."<small> ".$row['timestamp']."</small></h3>";
	// echo "<h5><em>Double click to increase quantity.<br />Click and hold to set quantity to one.</em></h5>";
?>

					</div>
					<div class="dropdown">
						<button class="btn btn-default dropdown-toggle" type="button" id="dropdown" name="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Add <span class="caret"></span></button>
					  	<ul class="dropdown-menu" aria-labelledby="dropdown">

<?php

// // items user can add to list and hasn't already done so
// $dropdown = "SELECT item_instances.item_instance_id, item_instances.item_id, item_instances.location_id, item_instances.sort_order, master_items.item_name, location_instances.location_instance_id
// 	FROM item_instances
// 	INNER JOIN master_items ON item_instances.item_id=master_items.item_id
// 	INNER JOIN master_locations ON item_instances.location_id=master_locations.location_id
// 	INNER JOIN location_instances ON master_locations.location_id=location_instances.location_id
// 	WHERE location_instances.location_instance_id='".$id."'
//     AND item_instances.item_instance_id NOT IN
// 	    (SELECT lists.item_instance_id
// 		FROM lists
// 		INNER JOIN location_instances ON lists.location_instance_id=location_instances.location_instance_id
// 		INNER JOIN master_locations ON location_instances.location_id=master_locations.location_id
// 		INNER JOIN item_instances ON lists.item_instance_id=item_instances.item_instance_id
// 		INNER JOIN master_items ON item_instances.item_id=master_items.item_id
// 		WHERE lists.location_instance_id='".$id."')
//     ORDER BY master_items.item_name ASC";

// $result=$conn->query($dropdown);
// while($row=$result->fetch_assoc()) {
// 	echo "<li id=\"".$row["item_instance_id"]."\"><a href=\"#\">".$row["item_name"]."</a></li>";
// }	

?>

				  		</ul>
				  	</div>
		  		</div>
 		  		<div class="row">
		  			<div class="col-md-12">
		  				<span class="glyphicon pull-right" id="showHideButton"></span>
		  			</div>
		  		</div>
			</form>

			<ul class="list-group" id="myList">

<?php

// // items user has added to list
// $getList = "SELECT lists.location_instance_id, lists.item_instance_id, lists.qty, master_locations.location_name, master_items.item_name, item_instances.sort_order, lists.checked_status, lists.qty
// 	FROM lists
// 	INNER JOIN location_instances ON lists.location_instance_id=location_instances.location_instance_id
// 	INNER JOIN master_locations ON location_instances.location_id=master_locations.location_id
// 	INNER JOIN item_instances ON lists.item_instance_id=item_instances.item_instance_id
// 	INNER JOIN master_items ON item_instances.item_id=master_items.item_id
// 	WHERE lists.location_instance_id='".$id."'
// 	ORDER BY lists.checked_status ASC, item_instances.sort_order ASC";

// $result=$conn->query($getList);
// while($row=$result->fetch_assoc()) {
// 	if($row['checked_status']==1) {
// 		echo "<li class=\"list-group-item disabled\" id=\"".$row['item_instance_id']."\"><span class=\"glyphicon glyphicon-check\"></span>".$row['item_name']."</li>";
// 	} else {
// 		echo "<li class=\"list-group-item\" id=\"".$row['item_instance_id']."\"><span class=\"glyphicon glyphicon-unchecked\"></span>".$row['item_name']." x <span class=\"qty\">".$row['qty']."</span><span class=\"glyphicon glyphicon-trash pull pull-right\"></span></li>";
// 	}
// }

?>

			</ul>
		</div>
	</div>
</div>
	<script>
	$(function() {
		if("<?php echo $menu_hide; ?>" == 0) {
			// show 
			$("#showHideButton").addClass("glyphicon-menu-up");
		} else {
			// hide
			$("#showHideButton").addClass("glyphicon-menu-down");
			$("#showHideTarget").hide();
		}	
		$(".dropdown li").click( function() {
		    var id = $(this).attr("id");
		    AddListItem(id);
		})
 		function AddListItem(item_instance_id) {
			jQuery.ajax({
				type: "POST",
				url: "post/addlistitem.php",
				data: {location_instance_id: "<?php echo $id ?>", item_instance_id: item_instance_id},
				cache: false,
				success: function(response) {
					window.location.reload(true);
				}
			})
		}
		$("#showHideButton").click(function() {
			if($("#showHideButton").hasClass("glyphicon-menu-down")) {
				$("#showHideButton").removeClass("glyphicon-menu-down").addClass("glyphicon-menu-up");
				$("#showHideTarget").show();
				MenuHide(0);
			} else {
				$("#showHideButton").removeClass("glyphicon-menu-up").addClass("glyphicon-menu-down");
				$("#showHideTarget").hide();
				MenuHide(1);
			}
		})
		function MenuHide(bool) {
			jQuery.ajax({
				type: "POST",
				url: "post/showhidejumbotron.php",
				data: {location_instance_id: "<?php echo $id; ?>", menu_hide: bool},
				cache: false,
				success: function(response) {
					window.location.reload(false);
				}
			})
		}
		$("#myList li").on("taphold", function() {
			var id = $(this).attr('id');
			var qty = parseInt($(this).find("span.qty").text());
			if(qty>1 && confirm("Do you want to set the quantity to 1?")) {
				UpdateQty(id, 1);
			}
		})
		var mylatesttap;
		$("#myList li").click(function() {
			var now = new Date().getTime();
			var timesince = now - mylatesttap;
			if((timesince<600) && (timesince>0)) {
				var id = $(this).attr('id');
				var qty = parseInt($(this).find("span.qty").text()) + 1;
				if(confirm("Do you want to set the quantity to "+qty+"?")) {
					UpdateQty(id, qty);
				}
		   	}
		   	mylatesttap = new Date().getTime();
		})
		function UpdateQty(item_instance_id, qty) {
			jQuery.ajax({
				type: "POST",
				url: "post/updateqty.php",
				data: {location_instance_id: "<?php echo $id; ?>", item_instance_id: item_instance_id, qty: qty},
				cache: false,
				success: function(response) {
					window.location.reload(true);
				}
			})
		}
		$("span").click(function(event) {
			var id = $(this).parent().attr("id");
			// check, disable, and move to end
			if($(this).hasClass("glyphicon-unchecked")) {
				$(this).parent().addClass("disabled");
				$(this).parent().find("span.glyphicon-trash").removeClass("glyphicon-trash").addClass("glyphicon-trash-hidden");
				$(this).removeClass("glyphicon-unchecked").addClass("glyphicon-check");
				$("#myList").append($(this).parent());
				UpdateRecord(id, "1", false);
			// uncheck, enable, and move to front
			} else if($(this).hasClass("glyphicon-check")) {
				$(this).parent().removeClass("disabled");
				$(this).parent().find("span.glyphicon-trash-hidden").removeClass("glyphicon-trash-hidden").addClass("glyphicon-trash");
				$(this).removeClass("glyphicon-check").addClass("glyphicon-unchecked");
				$("#myList").prepend($(this).parent());
				UpdateRecord(id, "0", true);
			// user delete item
			} else if ($(this).hasClass("glyphicon-trash")) {
				var myTitle = $(this).parent().text();
				if (confirm('Are you sure you want to delete '+myTitle+'?')) {
					DeleteLocationRecord(id);
				}
			}
		})
		function UpdateRecord(item_instance_id, status, refresh) {
			jQuery.ajax({
				type: "POST",
				url: "post/updatehidden.php",
				data: {location_instance_id: "<?php echo $id; ?>", item_instance_id: item_instance_id, status: status},
				cache: false,
				success: function(response) {
					// only refresh is item is unchecked and returned to list
					// forces sort order
					if(refresh==true) {
						window.location.reload(true);
					}
				}
			})
		}	
		function DeleteLocationRecord(item_instance_id) {
			jQuery.ajax({
				type: "POST",
				url: "post/deletelistitem.php",
				data: {location_instance_id: "<?php echo $id; ?>", item_instance_id: item_instance_id},
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