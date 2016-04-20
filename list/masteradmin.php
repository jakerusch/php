<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn=$obj->getConn();

?>

			<form class="well" id="addNewForm">
				<div class="form-group">
					<label for="itemTitle">Add New Master List Item</label>
					<input type="itemTitle" class="form-control" id="itemTitle" name="itemTitle" placeholder="Name">
				</div>
				<button type="submit" class="btn btn-default" id="addItem"><span class="glyphicon glyphicon-plus"></span> Add</button>
			</form>

			<ul class="list-group" id="sortable">

<?php

$getList = "SELECT * FROM master_list ORDER BY sort_order ASC";
$result = $conn->query($getList);
while($row=$result->fetch_assoc()) {
	echo "<li class=\"list-group-item\" id=\"item-".$row['item_id']."\" sort=\"".$row['sort_order']."\"><span class=\"glyphicon glyphicon-menu-hamburger pull-left\"></span> ".$row['title']."<span class=\"glyphicon glyphicon-trash pull-right\"></span></li>";
}

?>
			</ul>
		</div>
	</div>
</div>
	<script>
	$(function() {
		// sortable 
		$("#sortable").sortable({
			handle: "span.glyphicon-menu-hamburger",
			stop: function(event, ui) {
				var data = $(this).sortable("serialize");
				$.ajax({
					type: "POST",
					url: "post/updateorder.php",
					data: data,
					cache: false,
					success: function(response) {
						window.location.reload(true);
					}
				});
			}
		})
		// add new record
		$("#addNewForm").submit(function(event) {
			var myVal = $("#itemTitle").val();
			if(myVal.trim().length>0) {
				AddRecord(myVal);
			}
			event.preventDefault();
		})
		// add new record function
		function AddRecord(val) {
			$.ajax({
				type: "POST",
				url: "post/addnewitem.php",
				data: {title: val},
				cache: false,
				success: function(response) {
					// alert(response);
					window.location.reload(true);
				}
			});
		}		
		// delete record
		$("#sortable li span.glyphicon-trash").click(function(event) {
			var myID = $(this).closest("li").attr("id");
			var myTitle = $(this).closest("li").text();
			if (confirm('Are you sure you want to delete '+myTitle+'?')) {
				DeleteRecord(myID.replace("item-", ""));
			}
			event.preventDefault();
		})
		function DeleteRecord(id) {
			$.ajax({
				type: "POST",
				url: "post/deleteitem.php",
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