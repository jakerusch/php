<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn=$obj->getConn();

?>

			<form class="well" id="addNewItem">
				<div class="form-group">
					<label for="itemName">Add New Master Item</label>
					<input type="itemName" class="form-control" id="itemName" name="itemName" placeholder="Item Name">
				</div>
				<button type="submit" class="btn btn-default" id="addItem"><span class="glyphicon glyphicon-plus"></span> Add</button>
			</form>

			<ul class="list-group" id="items">

<?php

$getList = "SELECT item_id, item_name FROM master_items ORDER BY item_name ASC";
$result = $conn->query($getList);
while($row=$result->fetch_assoc()) {
	echo "<li class=\"list-group-item\" id=\"".$row['item_id']."\">".$row['item_name']."<span class=\"glyphicon glyphicon-trash pull-right\"></span></li>";
}

?>
			</ul>
		</div>
	</div>
</div>
	<script>
	$(function() {
		// add new list
		$("#addNewItem").submit(function(event) {
			event.preventDefault();
			var myVal = $("#itemName").val();
			if(myVal.trim().length>0) {
				AddItem(myVal);
			}
		})
		// add new record function
		function AddItem(val) {
			$.ajax({
				type: "POST",
				url: "post/addnewitemmaster.php",
				data: {item_name: val},
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
					DeleteItemRecord(myID.replace("item-", ""));
				}
			}
		})
		function DeleteItemRecord(id) {
			$.ajax({
				type: "POST",
				url: "post/deleteitemmaster.php",
				data: {item_id: id},
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