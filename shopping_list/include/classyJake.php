<?php

class classyJake {
	
	private $title;
	private $conn;

	// create navigation
	private function navigation() {
		$navArr=array("Lists" => "listadmin.php", "Admin" => "masteradmin.php");
		$temp="";
        foreach($navArr as $key => $value) {
            $temp=$temp."<li";
            if(strtolower($this->title)==strtolower(str_replace(".php", "", $value))) {
                $temp=$temp." class=\"active\"";
            }
            $temp=$temp."><a href=\"".$value."\">".$key."</a></li>";
        }
        return $temp;
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
    <!-- jquery sortable -->
  	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>    
</head>
<body background="img/woodbackground.png">
END;
	} // end addHeader
	
	
	public function addNavigation() {

		
		// print page navigation
		echo <<<END
	<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4 col-xs-12">
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button class="navbar-toggle collapsed" aria-expanded="false" aria-controls="navbar" type="button" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>				
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="navbar-collapse collapse" id="navbar" aria-expanded="false" style="height: 1px;">
					<ul class="nav navbar-nav">
END;

echo $this->navigation();
			
		// continue printing page navigation
		echo <<<END
					<ul class="nav navbar-nav navbar-right"></ul>
					</ul>
				</div><!-- /.navbar-collapse -->	
			</div><!-- /.container-fluid -->
		</nav>
END;


	}

	private function createConn() {
		$servername = "localhost";
		$username = "root";
		$password = "";
		$db = "shopping_list";

		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $db);

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