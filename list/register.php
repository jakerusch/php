<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);

?>
			<h2 class="well text-center">Registration</h2>

			<form class="well" id="register">
				<div class="form-group">
					<label for="email">Email</label>
					<input type="email" class="form-control" id="email" name="email" placeholder="Email">
					<label for="password">Password</label>
					<input type="password" class="form-control" id="password" name="password" placeholder="*****">
					<label for="password">Re-enter Password</label>
					<input type="password" class="form-control" id="password2" name="password2" placeholder="*****">
					<label for="firstName">First Name</label>
					<input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name">
					<label for="lastName">Last Name</label>
					<input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name">
				</div>
				<button type="submit" class="btn btn-default" id="loginCheck">Login</button>
				<h5 id="error" class="text-danger"></h5>
			</form>

		</div>
	</div>
</div>
	<script>
	$(function() {
		$("#register").submit(function(event) {
			event.preventDefault();
			if($("#password").val()!=$("#password2").val()) {
				$("#error").text("Passwords do not match.");
			} else if($("#email").val()=="" || $("#password").val()=="" || $("#firstName").val()=="" || $("#lastName").val()=="") {
				$("#error").text("Form is incomplete.");
			} else {
				var email = $("#email").val();
				var password = $("#password").val();
				var firstName = $("#firstName").val();
				var lastName = $("#lastName").val();
				// alert(email + " " + password + " " + first_name + " " + last_name);
				Register(email, password, firstName, lastName);
			}
		})
		// add new user record function
		function Register(email, password, firstName, lastName) {
			$.ajax({
				type: "POST",
				url: "post/registercheck.php",
				data: {email: email, password: password, first_name: firstName, last_name: lastName},
				cache: false,
				success: function(response) {
					if(response==1) {
						window.location.href = "locationadmin.php";
					} else {
						$("#error").text("Unable to complete request.");
					}
				}
			});
		}		
	});
	</script>
	</body>
</html>