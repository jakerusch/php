<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/shopping_list/password_compat-master/lib/password.php");

class classyJake {
	
	private $title;
	private $conn;

	public function __construct() {
		// check for user session
		if(!isset($_SESSION['user_id'])) {
			// if not login page or logincheck script, redirect to login page
			if($_SERVER['REQUEST_URI']!=="/shoping_list/login.php" && $_SERVER['REQUEST_URI']!=="/shopping_list/post/logincheck.php" &&  $_SERVER['REQUEST_URI']!=="/list/temp/insertuser.php") {
				header("Location:http://php-nwcc.rhcloud.com/shopping_list/login.php");
				exit();
				// header("Location:localhost:81/shopping_list/login.php");
			}
		}
	}

	// create navigation
	private function navigation() {
		if($_SERVER['REQUEST_URI']!=="/shopping_list/login.php") {
			$navArr=array("Lists" => "listadmin.php", "Items" => "itemadmin.php", "Locations" => "locationadmin.php");
			$temp="<!-- Brand and toggle get grouped for better mobile display -->
					<div class=\"navbar-header\">
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
	
	public function getTitle() {
		return $this->title;
	}
	
	public function addHeader() {
		
		// print page header
		echo <<<END
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<meta charset="utf-8">
	<title>
END;

// capitalize name
echo ucwords($this->getTitle());

// continue printing page header
		echo <<<END
	</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
	<!-- jquery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
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
	<!-- icons -->
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
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
		$ver="p";

		if($ver=="p") {
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
		$this->conn = $conn;
	}

	public function getConn() {
		$this->createConn();
		return $this->conn;
	}
	
}
?>