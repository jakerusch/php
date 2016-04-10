<?php
require_once($_SERVER['DOCUMENT_ROOT']."/shopping_list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn = $obj->getConn();
$id=$_GET['id'];

$sql = "SELECT list_title, DATE_FORMAT(timestamp, '%c/%e/%Y') as timestamp FROM lists WHERE list_id='".$id."' LIMIT 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

?>

			<form class="well">
				<div class="form-group">
					<div class="text-center"><h3>

<?php 
	echo $row["list_title"]."<small> ".$row["timestamp"]."</small></h3>";
?>

					</div>
					<div class="dropdown">
						<button class="btn btn-default dropdown-toggle" type="button" id="dropdown" name="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Add <span class="caret"></span></button>
					  	<ul class="dropdown-menu" aria-labelledby="dropdown">

<?php
$sql2 = "SELECT item_id, title FROM master_list ORDER BY title ASC";
$result2=$conn->query($sql2);
while($row2=$result2->fetch_assoc()) {
	echo "<li id=\"".$row2["item_id"]."\"><a href=\"#\">".$row2["title"]."</a></li>";
}
?>

					  	</ul>
					</div>
			  	</div>
			</form>

			<ul class="list-group" id="myList">

<?php

$getList = "SELECT master_list.item_id, master_list.title, master_list.sort_order, list_content.checked_status
	FROM list_content
	JOIN master_list
	ON list_content.item_id=master_list.item_id
	WHERE list_content.list_id='".$id."' 
	ORDER BY list_content.checked_status ASC, master_list.sort_order ASC";

$result = $conn->query($getList);
while($row=$result->fetch_assoc()) {
	if($row["checked_status"]==1) {
		echo "<li class=\"list-group-item disabled\" id=\"".$row['item_id']."\"><span class=\"glyphicon glyphicon-check\"></span> ".$row['title']."</li>";
	} else {
		echo "<li class=\"list-group-item\" id=\"".$row['item_id']."\"><span class=\"glyphicon glyphicon-unchecked\"></span> ".$row['title']."</li>";
	}
}

?>			

			</ul>
		</div>
	</div>
</div>
	<script>
	$(function() {
			$(".dropdown li").click( function() {
			    var id = $(this).attr("id");
			    AddListItem(id);
			});
		$("#myList li").click(function() {
			var id=$(this).attr("id");
			if($(this).hasClass("disabled")) {
				// clear
				$(this).removeClass("disabled").find("span").removeClass("glyphicon-check").addClass("glyphicon-unchecked");
				$("#myList").prepend($(this));
				UpdateRecord(id, "0", true);
			} else {
				// check and disable
				$(this).addClass("disabled").find("span").removeClass("glyphicon-unchecked").addClass("glyphicon-check");
				$("#myList").append($(this));
				UpdateRecord(id, "1", false);
			}
		})
		function AddListItem(item_id) {
			jQuery.ajax({
				type: "POST",
				url: "post/addnewlistitem.php",
				data: {item_id: item_id, list_id: "<?php echo $id; ?>"},
				cache: false,
				success: function(response) {
					window.location.reload(true);
				}
			})
		}
		function UpdateRecord(item_id, status, refresh) {
			jQuery.ajax({
				type: "POST",
				url: "post/updatehidden.php",
				data: {list_id: "<?php echo $id; ?>", item_id: item_id, status: status},
				cache: false,
				success: function(response) {
					// alert(response);
					// only refresh is item is unchecked and returned to list
					// forces sort order
					if(refresh==true) {
						window.location.reload(true);
					}
				}
			})
		}	
		});
	</script>
	</body>
</html>