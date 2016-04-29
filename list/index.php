<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);

?>
			<h2 class="well text-center">The Short List</h2>

			<div class="well">This is a web app that was designed and coded by Jacob Rusch to manage the shopping order of his weekly lists at HEB and Costco.</div>

		</div>
	</div>
</div>
	<script>
	$(function() {
	});
	</script>
	</body>
<footer>
<a href="http://www.beyondsecurity.com/vulnerability-scanner-verification/php-nwcc.rhcloud.com"><img src="https://seal.beyondsecurity.com/verification-images/php-nwcc.rhcloud.com/vulnerability-scanner-2.gif" alt="Website Security Test" border="0" /></a>
</footer>
</html>