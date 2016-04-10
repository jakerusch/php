<?php
require_once($_SERVER['DOCUMENT_ROOT']."/shopping_list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);

?>

			<form class="well" id="addNewList">
				<div class="form-group">
					<label for="listTitle">Add New List</label>
					<input type="listTitle" class="form-control" id="listTitle" name="listTitle" placeholder="List Name">
				</div>
				<button type="submit" class="btn btn-default" id="addTitle"><span class="glyphicon glyphicon-plus"></span> Add</button>
			</form>

			<ul class="list-group" id="items">

<?php

$conn = $obj->getConn();
$getList = "SELECT * FROM lists ORDER BY timestamp ASC";
$result = $conn->query($getList);
while($row=$result->fetch_assoc()) {
	echo "<li class=\"list-group-item\" id=\"".$row['list_id']."\">".$row['list_title']."<span class=\"glyphicon glyphicon-trash pull-right\"></span></li>";
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
			window.location.href = "list.php?id="+id;
		})
		// add new list
		$("#addNewList").submit(function(event) {
			event.preventDefault();
			var myVal = $("#listTitle").val();
			if(myVal.trim().length>0) {
				AddList(myVal);
			} else {
				alert("no go bro");
			}
		})
		// add new record function
		function AddList(val) {
			jQuery.ajax({
				type: "POST",
				url: "post/addnewlist.php",
				data: {listTitle: val},
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
				DeleteRecord(myID.replace("item-", ""));
			}
		})
		function DeleteRecord(id) {
			jQuery.ajax({
				type: "POST",
				url: "post/deletelist.php",
				data: {list_id: id},
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