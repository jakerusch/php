<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/list/password_compat-master/lib/password.php");

class classyJake {

	private $title;
	private $conn;
	private $prod;

	public function __construct() {
		// easy check for test vs prod
		$this->prod=true;
		$this->createConn();
		// check for user session
		if(!isset($_SESSION['user_id'])) {
			// allow json request
			if(isset($_GET['token']) && !empty($_GET['token']) && $_GET['token']==='123456') {

			} else {
				// if not login page or logincheck script, redirect to login page
				if($_SERVER['REQUEST_URI']!=="/list/login.php" &&
					$_SERVER['REQUEST_URI']!=="/list/post/logincheck.php" &&
					$_SERVER['REQUEST_URI']!=="/list/logout.php") {
					if($this->prod==true) {
						header("Location:http://php-nwcc.rhcloud.com/list/login.php");
					} else {
						header("Location:http://localhost/list/login.php");
					}
				}
			}
		}
	}

	public function checkUser() {
		if(isset($_SESSION['user_id'])) {
			$sql = "SELECT users.access_id
				FROM users
				WHERE users.user_id='".$_SESSION['user_id']."'";
			$result = $this->conn->query($sql);
			$row=$result->fetch_assoc();
			return $row["access_id"];
		}
	}

	// create navigation
	private function navigation() {
		if($_SERVER['REQUEST_URI']!=="/list/login.php" && $_SERVER['REQUEST_URI']!=="/list/register.php" && $_SERVER['REQUEST_URI']!=="/list/logout.php") {
			// $navArr=array("Lists" => "listadmin.php", "Items" => "itemadmin.php", "Locations" => "locationadmin.php");
			// $navArr=array("Items" => "itemadmin.php", "Locations" => "locationadmin.php", "Recipes" => "recipesadmin.php");
			$navArr=array("Recipes" => "recipeadmin.php", "Locations" => "locationadmin.php", "Items" => "itemadmin.php");
			$temp="<!-- Brand and toggle get grouped for better mobile display -->
					<div class=\"navbar-header\">
      				<a class=\"navbar-brand\" href=\"listadmin.php\">The Short List <span class=\"glyphicon glyphicon-list-alt\"></span></a>
							<a class=\"navbar-brand\" href=\"meals.php\">Meals <span class=\"glyphicon glyphicon-grain\"></span></a>
					<button class=\"navbar-toggle collapsed\" aria-expanded=\"false\" aria-controls=\"navbar\" type=\"button\" data-toggle=\"collapse\" data-target=\".navbar-collapse\">
						<span class=\"sr-only\">Toggle navigation</span>
						<span class=\"icon-bar\"></span>
						<span class=\"icon-bar\"></span>
						<span class=\"icon-bar\"></span>
					</button>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class=\"navbar-collapse collapse\" id=\"navbar\" aria-expanded=\"false\" style=\"height: 1px;\"><ul class=\"nav navbar-nav\">";
	        foreach($navArr as $key => $value) {
	            $temp.="<li";
	            if(strtolower($this->title)==strtolower(str_replace(".php", "", $value))) {
	                $temp.=" class=\"active\"";
	            }
	            $temp.="><a href=\"".$value."\">".$key."</a></li>";
	        }
	        $temp.="</ul><ul class=\"nav navbar-nav navbar-right\"><li><a href=\"logout.php\">Logout</a></li></ul>
	        				</div><!-- /.navbar-collapse -->
						</div><!-- /.container-fluid -->
					</nav>";
	        return $temp;
	    }
	}

	public function createPage($title) {
		$this->title=$title;
		$this->addHeader();
		$this->addNavigation();
	}

	public function addHeader() {

		// print page header
		echo <<<END
<!DOCTYPE html>
<html lang="en">
<head>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
	<meta charset="utf-8">
	<title>
END;

// capitalize name
echo ucwords($this->title);

// continue printing page header
		echo <<<END
	</title>

	<!-- jQuery -->
		<script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
	<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
	<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <!-- jquery mobile -->
  	<!-- <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script> -->
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
	<!-- jquery sortable -->
  	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <!-- jquery UI Touch Punch (fix for sortable on touch devices), must be after jquery and jquery-ui -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
	<!-- jquery taphold -->
		<script src="js/jquery-taphold-master/taphold.js"></script>
	<!-- jquery bootstrap add clear -->
		<script src="js/bootstrap-add-clear-1.0.7/bootstrap-add-clear.js"></script>
		<script src="js/bootstrap-add-clear-1.0.7/bootstrap-add-clear.min.js"></script>
	<!-- bootbox -->
		<script src="js/bootbox.min.js"></script>
	<!-- moment.js -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
	<!-- icons -->
		<link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon" />
		<link rel="apple-touch-icon" href="img/apple-touch-icon.png" />
		<link rel="apple-touch-icon" sizes="57x57" href="img/apple-touch-icon-57x57.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png" />
		<link rel="apple-touch-icon" sizes="76x76" href="img/apple-touch-icon-76x76.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png" />
		<link rel="apple-touch-icon" sizes="120x120" href="img/apple-touch-icon-120x120.png" />
		<link rel="apple-touch-icon" sizes="144x144" href="img/apple-touch-icon-144x144.png" />
		<link rel="apple-touch-icon" sizes="152x152" href="img/apple-touch-icon-152x152.png" />
		<link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon-180x180.png" />
</head>
<body background="img/woodbackground.png">
END;
	} // end addHeader


	public function addNavigation() {


		// print page navigation
		echo <<<END
	<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2 col-xs-12">
		<nav class="navbar navbar-default">
			<div class="container-fluid">

END;

		echo $this->navigation();

	}

	private function createConn() {

		if($this->prod==true) {
			// for prod
			$conn = mysqli_connect(getenv('OPENSHIFT_MYSQL_DB_HOST'), getenv('OPENSHIFT_MYSQL_DB_USERNAME'), getenv('OPENSHIFT_MYSQL_DB_PASSWORD'), "shopping_list", getenv('OPENSHIFT_MYSQL_DB_PORT'));
		} else {
			// for local xampp
			$conn = mysqli_connect("localhost", "root", "", "shopping_list");
		}

		// Check connection
		if (!$conn) {
		    die("Connection failed: " . mysqli_connect_error());
		}
		$this->conn=$conn;
	}

	public function getConn() {
		$this->createConn();
		return $this->conn;
	}

}
?>
