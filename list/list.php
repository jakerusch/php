<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn=$obj->getConn();
$sid=$_SESSION['user_id'];
$id=$_GET['id'];

// redirect if user does not have access
$sql="SELECT location_instances.location_instance_id, location_instances.location_id,
	master_locations.user_id
	FROM location_instances
	INNER JOIN master_locations ON location_instances.location_id=master_locations.location_id
	WHERE location_instances.location_instance_id='".$id."' AND master_locations.user_id='".$sid."'";
$result=$conn->query($sql);
$num_rows=$result->num_rows;
if($num_rows!==1) {
	header('Location:http://php-nwcc.rhcloud.com/list/listadmin.php');
}

// get current location instance
$sql = "SELECT location_instances.location_instance_id, DATE_FORMAT(location_instances.location_timestamp, '%c/%e/%Y') as timestamp,
	master_locations.location_name, location_instances.menu_hide
	FROM location_instances
	LEFT JOIN master_locations ON location_instances.location_id = master_locations.location_id
	WHERE location_instances.location_instance_id='".$id."'";

$result=$conn->query($sql);
$row = $result->fetch_assoc();
$menu_hide=$row['menu_hide'];

?>

			<form class="well" id="searchForm" >
				<div class="form-group" id="showHideTarget">
					<div class="text-center">

<?php
	echo "<h3 id=\"title\">".$row["location_name"]."<small> ".$row['timestamp']."</small></h3>";
	// echo "<h5><em>Double click to increase quantity.<br />Click and hold to set quantity to one.</em></h5>";
?>

					</div>
					    <div class="input-group dropdown">
					      <div class="input-group-btn">
					        <!-- <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Items <span class="caret"></span></button> -->
									<button class="btn btn-default dropdown-toggle" type="button" id="dropdown" name="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Items <span class="caret"></span></button>
					        <ul class="dropdown-menu">


<?php


// items user can add to list and hasn't already done so
$dropdown = "SELECT item_instances.item_instance_id, item_instances.item_id,
	item_instances.location_id, item_instances.sort_order,
	master_items.item_name, location_instances.location_instance_id
	FROM item_instances
	INNER JOIN master_items ON item_instances.item_id=master_items.item_id
	INNER JOIN master_locations ON item_instances.location_id=master_locations.location_id
	INNER JOIN location_instances ON master_locations.location_id=location_instances.location_id
	WHERE location_instances.location_instance_id='".$id."'
    AND item_instances.item_instance_id NOT IN
	    (SELECT lists.item_instance_id
		FROM lists
		INNER JOIN location_instances ON lists.location_instance_id=location_instances.location_instance_id
		INNER JOIN master_locations ON location_instances.location_id=master_locations.location_id
		INNER JOIN item_instances ON lists.item_instance_id=item_instances.item_instance_id
		INNER JOIN master_items ON item_instances.item_id=master_items.item_id
		WHERE lists.location_instance_id='".$id."')
    ORDER BY master_items.item_name ASC";

$result=$conn->query($dropdown);
while($row=$result->fetch_assoc()) {
	echo "<li id=\"".$row["item_instance_id"]."\"><a href=\"#\">".$row["item_name"]."</a></li>";
}

?>


					        </ul>
					      </div><!-- /btn-group -->
					      <!-- <input type="text" class="form-control" aria-label="..."> -->
								<input type="text" class="form-control " id="searchItem" placeholder="Search">
					    </div><!-- /input-group -->
						</div>
			</form>


			<ul class="list-group" id="myList">

<?php

// items user has added to list
$getList = "SELECT lists.location_instance_id, lists.item_instance_id, lists.qty,
	master_locations.location_name, master_items.item_name,
	item_instances.sort_order, lists.checked_status, lists.qty
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

			</ul>
		</div>
	</div>
</div>
	<script>
	$(function() {
		var mylatesttap;
		$("#myList li").click(function() {
			var now = new Date().getTime();
			var timesince = now - mylatesttap;
			if((timesince<600) && (timesince>0)) {
				var item_id = $(this).attr('id');
				var currentName = $(this).text();
				var qty = parseInt($(this).find("span.qty").text());
				if(isNaN(qty)) {
					qty=1;
				}
				var item_name;
				bootbox.prompt({
					title: "Item Quantity",
					inputType: 'select',
					value: qty,
					inputOptions: [
						{
						text: '1',
						value: '1'
						},
						{
						text: '2',
						value: '2'
						},
						{
						text: '3',
						value: '3'
						},
						{
						text: '4',
						value: '4'
						},
						{
						text: '5',
						value: '5'
						},
						{
						text: '6',
						value: '6'
						},
						{
						text: '7',
						value: '7'
						},
						{
						text: '8',
						value: '8'
						},
						{
						text: '9',
						value: '9'
						},
						{
						text: '10',
						value: '10'
						},
						{
						text: '11',
						value: '11'
						},
						{
						text: '12',
						value: '12'
						},
						{
						text: '13',
						value: '13'
						},
						{
						text: '14',
						value: '14'
						},
						{
						text: '15',
						value: '15'
						},
						{
						text: '16',
						value: '16'
						},
						{
						text: '17',
						value: '17'
						},
						{
						text: '18',
						value: '18'
						},
						{
						text: '19',
						value: '19'
						},
						{
						text: '20',
						value: '20'
						},
						{
						text: '21',
						value: '21'
						},
						{
						text: '22',
						value: '22'
						},
						{
						text: '23',
						value: '23'
						},
						{
						text: '24',
						value: '24'
						},
						{
						text: '25',
						value: '25'
						},
						{
						text: '26',
						value: '26'
						},
						{
						text: '27',
						value: '27'
						},
						{
						text: '28',
						value: '28'
						},
						{
						text: '29',
						value: '29'
						},
						{
						text: '30',
						value: '30'
						},
						{
						text: '31',
						value: '31'
						},
						{
						text: '32',
						value: '32'
						},
						{
						text: '33',
						value: '33'
						},
						{
						text: '34',
						value: '34'
						},
						{
						text: '35',
						value: '35'
						},
						{
						text: '36',
						value: '36'
						},
						{
						text: '37',
						value: '37'
						},
						{
						text: '38',
						value: '38'
						},
						{
						text: '39',
						value: '39'
						},
						{
						text: '40',
						value: '40'
						},
						{
						text: '41',
						value: '41'
						},
						{
						text: '42',
						value: '42'
						},
						{
						text: '43',
						value: '43'
						},
						{
						text: '44',
						value: '44'
						},
						{
						text: '45',
						value: '45'
						},
						{
						text: '46',
						value: '46'
						},
						{
						text: '47',
						value: '47'
						},
						{
						text: '48',
						value: '48'
						},
						{
						text: '49',
						value: '49'
						},
						{
						text: '50',
						value: '50'
						},
						{
						text: '51',
						value: '51'
						},
						{
						text: '52',
						value: '52'
						},
						{
						text: '53',
						value: '53'
						},
						{
						text: '54',
						value: '54'
						},
						{
						text: '55',
						value: '55'
						},
						{
						text: '56',
						value: '56'
						},
						{
						text: '57',
						value: '57'
						},
						{
						text: '58',
						value: '58'
						},
						{
						text: '59',
						value: '59'
						},
						{
						text: '60',
						value: '60'
						},
						{
						text: '61',
						value: '61'
						},
						{
						text: '62',
						value: '62'
						},
						{
						text: '63',
						value: '63'
						},
						{
						text: '64',
						value: '64'
						},
						{
						text: '65',
						value: '65'
						},
						{
						text: '66',
						value: '66'
						},
						{
						text: '67',
						value: '67'
						},
						{
						text: '68',
						value: '68'
						},
						{
						text: '69',
						value: '69'
						},
						{
						text: '70',
						value: '70'
						},
						{
						text: '71',
						value: '71'
						},
						{
						text: '72',
						value: '72'
						},
						{
						text: '73',
						value: '73'
						},
						{
						text: '74',
						value: '74'
						},
						{
						text: '75',
						value: '75'
						},
						{
						text: '76',
						value: '76'
						},
						{
						text: '77',
						value: '77'
						},
						{
						text: '78',
						value: '78'
						},
						{
						text: '79',
						value: '79'
						},
						{
						text: '80',
						value: '80'
						},
						{
						text: '81',
						value: '81'
						},
						{
						text: '82',
						value: '82'
						},
						{
						text: '83',
						value: '83'
						},
						{
						text: '84',
						value: '84'
						},
						{
						text: '85',
						value: '85'
						},
						{
						text: '86',
						value: '86'
						},
						{
						text: '87',
						value: '87'
						},
						{
						text: '88',
						value: '88'
						},
						{
						text: '89',
						value: '89'
						},
						{
						text: '90',
						value: '90'
						},
						{
						text: '91',
						value: '91'
						},
						{
						text: '92',
						value: '92'
						},
						{
						text: '93',
						value: '93'
						},
						{
						text: '94',
						value: '94'
						},
						{
						text: '95',
						value: '95'
						},
						{
						text: '96',
						value: '96'
						},
						{
						text: '97',
						value: '97'
						},
						{
						text: '98',
						value: '98'
						},
						{
						text: '99',
						value: '99'
						},
						{
						text: '100',
						value: '100'
						},
					],
					callback: function(result) {
						if(result) {
							UpdateQty(item_id, result);
						}
					}
				})
			}
			mylatesttap = new Date().getTime();
		});
		$("#searchItem:input").addClear();
		$("#searchItem").focus();
		// manually typed
		$("#searchForm").submit(function(event) {
			var item_name = $("#searchItem").val();
			AddListItemByName("<?php echo $id; ?>", item_name);
			event.preventDefault();
		})
		// selected from dropdown
		$("#searchItem").autocomplete({
	    source: 'post/search.php?id=' + "<?php echo $id; ?>",
	    select: function(event, ui) {
				AddListItem(ui.item.id);
				event.preventDefault();
	    }
	  });
		$(".dropdown li").click( function() {
		    var id = $(this).attr("id");
		    AddListItem(id);
		})
		function AddListItemByName(location_instance_id, item_name) {
			$.ajax({
				type: "POST",
				url: "post/addlistitembyname.php",
				data: {location_instance_id: "<?php echo $id ?>", item_name: item_name},
				cache: false,
				success: function(response) {
					if(response==1) {
						window.location.reload(true);
					// } else if(response==2) {
					// 	if(confirm("This item doesn't exist.  Would you like to add it?")) {
					// 		alert("Add item");
					// 	}
					// 	$("#searchItem:input").val('');
					} else {
						alert(response);
					}
				}
			})
		}
 		function AddListItem(item_instance_id) {
			$.ajax({
				type: "POST",
				url: "post/addlistitem.php",
				data: {location_instance_id: "<?php echo $id ?>", item_instance_id: item_instance_id},
				cache: false,
				success: function(response) {
					if(response==1) {
						window.location.reload(true);
					// } else if(response==2) {
					// 	if(confirm("This item doesn't exist.  Would you like to add it?")) {
					// 		alert("Add item");
					// 	}
					// 	$("#searchItem:input").val('');
					} else {
						alert(response);
					}
				}
			})
		}

		// $(document).on("taphold", "#myList li", function() {
		// 	var id = $(this).attr('id');
		// 	var qty = parseInt($(this).find("span.qty").text());
		// 	if(qty>1 && confirm("Do you want to set the quantity to 1?")) {
		// 		// set qty to 1 in db
		// 		UpdateQty(id, 1);
		// 		// hide qty
		// 		$(this).find("span.qty").text();
		// 		// remove " x "
		// 		$(this).find("span.multiplier").text();
		// 	}
		// }
		// var mylatesttap;
		// $(document).on("click", "#myList li", function() {
		// 	var now = new Date().getTime();
		// 	var timesince = now - mylatesttap;
		// 	if((timesince<600) && (timesince>0)) {
		// 		var id = $(this).attr('id');
		// 		var qty = parseInt($(this).find("span.qty").text()) + 1;
		// 		// if blank, turn to 2
		// 		if(isNaN(qty)) {
		// 			qty=2;
		// 		}
		// 		if(confirm("Do you want to set the quantity to "+qty+"?")) {
		// 			// set value
		// 			$(this).find("span.qty").text(UpdateQty(id, qty));
		// 			// add x if not found
		// 			if($(this).find("span.multiplier").text()=="") {
		// 				$(this).find("span.multiplier").text(" x ");
		// 			}
		// 		}
		// 	}
		//   mylatesttap = new Date().getTime();
		// });
		function UpdateQty(item_instance_id, qty) {
			$.ajax({
				type: "POST",
				url: "post/updateqty.php",
				data: {location_instance_id: "<?php echo $id; ?>", item_instance_id: item_instance_id, qty: qty},
				cache: false,
				success: function(response) {
					window.location.reload(false);
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
			$.ajax({
				type: "POST",
				url: "post/updatehidden.php",
				data: {location_instance_id: "<?php echo $id; ?>", item_instance_id: item_instance_id, status: status},
				cache: false,
				success: function(response) {
					// only refresh is item is unchecked and returned to list
					// forces sort order
					if(response==1) {
						if(refresh==true) {
							window.location.reload(true);
						}
					} else {
						alert(response);
					}
				}
			})
		}
		function DeleteLocationRecord(item_instance_id) {
			$.ajax({
				type: "POST",
				url: "post/deletelistitem.php",
				data: {location_instance_id: "<?php echo $id; ?>", item_instance_id: item_instance_id},
				cache: false,
				success: function(response) {
					if(response==1) {
						window.location.reload(true);
					} else {
						alert(response);
					}
				}
			})
		}
	});
	</script>
	</body>
</html>
