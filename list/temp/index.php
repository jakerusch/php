<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn=$obj->getConn();
$sid=$_SESSION['user_id'];
$id=$_GET['id'];
?>
<div class="ui-widget">
  <label for="skills">Skills: </label>
  <input id="skills">
</div>
<div id="id">ID: </div>
<div id="label">Label: </div>

<?php

// items user has added to list
$getList = "SELECT lists.location_instance_id, lists.item_instance_id, lists.qty, master_locations.location_name, master_items.item_name, item_instances.sort_order, lists.checked_status, lists.qty
	FROM lists
	INNER JOIN location_instances ON lists.location_instance_id=location_instances.location_instance_id
	INNER JOIN master_locations ON location_instances.location_id=master_locations.location_id
	INNER JOIN item_instances ON lists.item_instance_id=item_instances.item_instance_id
	INNER JOIN master_items ON item_instances.item_id=master_items.item_id
	WHERE lists.location_instance_id='".$id."'
	ORDER BY lists.checked_status ASC, item_instances.sort_order ASC";

$result=$conn->query($getList);
while($row=$result->fetch_assoc()) {
	if($row['checked_status']==1) {
		echo "<li class=\"list-group-item disabled\" id=\"".$row['item_instance_id']."\"><span class=\"glyphicon glyphicon-check\"></span>".$row['item_name']."</li>";
	} else {
		if($row['qty']>1) {
			// if greater than 1
			echo "<li class=\"list-group-item\" id=\"".$row['item_instance_id']."\"><span class=\"glyphicon glyphicon-unchecked\"></span>".$row['item_name']."<span class=\"multiplier\"> x </span><span class=\"qty\">".$row['qty']."</span><span class=\"glyphicon glyphicon-trash pull pull-right\"></span></li>";
		} else {
		echo "<li class=\"list-group-item\" id=\"".$row['item_instance_id']."\"><span class=\"glyphicon glyphicon-unchecked\"></span>".$row['item_name']."<span class=\"multiplier\"></span><span class=\"qty\"></span><span class=\"glyphicon glyphicon-trash pull pull-right\"></span></li>";
		}
	}
}

?>

<script>
$(function() {
  $( "#skills" ).autocomplete({
    source: 'search.php',
    select: function(event, ui) {
      $('#label').append(ui.item.label);
      $('#id').append(ui.item.id);
    }
  });
});
</script>
</body>
</html>
