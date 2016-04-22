<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn=$obj->getConn();

// $sql = "SELECT users.user_email, users.user_first_name, users.user_last_name FROM users";
$sql = "SELECT users.user_email, users.user_first_name, users.user_last_name, 'TRUE' as val
	FROM users
	INNER JOIN allowed_users ON users.user_email=allowed_users.user_email
	UNION
	SELECT allowed_users.user_email, NULL AS fn, NULL AS ln, 'FALSE' as val
	FROM allowed_users";
$result=$conn->query($sql);

?>

			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<form class="well" id="addNewUser">
						<div class="form-group">
							<label for="emailAddress">Email Address</label>
							<input type="email" class="form-control" id="emailAddress" name="emailAddress" placeholder="Email Address">
						</div>								
						<button type="submit" class="btn btn-default" id="addUser"><span class="glyphicon glyphicon-plus"></span> Add</button>
					</form>

					<table class="table table-hover table-striped well">
					<tr>
						<th class="text-center">Name</th>
						<th class="text-center">Email Address</th>
						<th class="text-center">Active</th>
					</tr>

<?php

while($row=$result->fetch_assoc()) {
	echo "<tr><td class=\"text-center\">".$row["user_first_name"]." ".$row["user_last_name"]."</td><td class=\"text-center\">".$row["user_email"]."</td><td class=\"text-center\">".$row["val"]."</tr>";
}

?>

					</table>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>
	<script>
	$(function() {
		// set focus on input box
		$('#emailAddress').focus();
		// add new list
		$("#addNewUser").submit(function(event) {
			event.preventDefault();
			var myVal = $("#emailAddress").val();
			if(myVal.trim().length>0) {
				AddItem(myVal);
			}
		})
		// add new record function
		function AddItem(user_email) {
			$.ajax({
				type: "POST",
				url: "post/addnewalloweduser.php",
				data: {user_email: user_email},
				cache: false,
				success: function(response) {
					if(response==1) {
						window.location.reload(true);	
					} else {
						alert(response);
					}
				}
			});
		}		
	});
	</script>
	</body>
</html>