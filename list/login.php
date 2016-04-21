<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);

?>
			<h2 class="well text-center">Login</h2>

			<form class="well" id="login">
				<div class="form-group">
					<label for="email">Email</label>
					<input type="email" class="form-control" id="email" name="email" placeholder="Email">
					<label for="password">Password</label>
					<input type="password" class="form-control" id="password" name="password" placeholder="*****">
				</div>
				<button type="submit" class="btn btn-default" id="loginCheck">Login</button>
				<h5 id="error" class="text-danger"></h5>
			</form>

		</div>
	</div>
</div>
	<script>
	$(function() {
		// set focus on email
		$('#email').focus();
		$("#login").submit(function(event) {
			event.preventDefault();
			var email = $("#email").val();
			var password = $("#password").val();
			Login(email, password);
		})
		// add new record function
		function Login(email, password) {
			$.ajax({
				type: "POST",
				url: "post/logincheck.php",
				data: {email: email, password: password},
				cache: false,
				success: function(response) {
					if(response==1) {
						window.location.href = "listadmin.php";
					} else {
						$("#error").text("Username or Password is invalid.");
					}
				}
			});
		}			
	});
	</script>
	</body>
</html>