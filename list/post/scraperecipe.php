<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();
$uid=$_POST['uid'];
$sid=$_SESSION['user_id'];

// strip out url
$uid=str_replace('http://allrecipes.com/recipe/', '', $uid);
// strip out last slash
$uid=str_replace('/', '', $uid);
// reconstruct url
$url='http://allrecipes.com/recipe/'.$uid;

$user_agent = "Mozilla/5.0 (X11; Linux i686) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11";
ini_set('user_agent', $user_agent);

//start scrape process
$doc = new DOMDocument();
@$doc->loadHTMLFile($url);
$xpath = new DOMXpath($doc);

$sql = "SELECT COUNT(1) FROM master_recipes
  WHERE master_recipes.recipe_id='$uid'
  LIMIT 1";
$result=$conn->query($sql);
$row=mysqli_fetch_row($result);
$count=$row[0];

$success=1;

if($count!=0) {
  echo "Recipe already exists.";
} else {
  // title
  $elements = $xpath->query('//h1["recipe-summary__h1"]');
  if(!is_null($elements)) {
    foreach($elements as $element) {
      $nodes = $element->childNodes;
      foreach($nodes as $node) {
        // insert title
        $sql = "INSERT INTO master_recipes(recipe_id, user_id, recipe_name, recipe_location) VALUES('".$uid."','".$sid."','".$node->nodeValue."','".$url."')";
        // get auto-increment item_id from master_items
        if ($conn->query($sql) === TRUE) {
            $unique_id=$conn->insert_id;
        } else {
            $success="";
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
      }
    }
  }

  // ingredients
  $elements = $xpath->query('//span[contains(@itemprop,"ingredients")]');
  $count=0;
  if(!is_null($elements)) {
    foreach($elements as $element) {
      $nodes = $element->childNodes;
      foreach($nodes as $node) {
        // insert ingredients
        $sql = "INSERT INTO recipe_ingredients(unique_id, ingredient_order, ingredient_text) VALUES('".$unique_id."','".$count."','".$node->nodeValue."')";
        $count=$count+1;
        if ($conn->query($sql) === TRUE) {
        } else {
          $success="";
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
      }
    }
  }

  // directions
  $elements = $xpath->query('//span[contains(@class,"recipe-directions__list--item")]');
  $count=0;
  if(!is_null($elements)) {
    foreach($elements as $element) {
      $nodes = $element->childNodes;
      foreach($nodes as $node) {
        // insert directions
        $sql = "INSERT INTO recipe_directions(unique_id, directions_order, directions_text) VALUES('".$unique_id."','".$count."','".$node->nodeValue."')";
        $count=$count+1;
        if ($conn->query($sql) === TRUE) {
        } else {
          $success="";
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
      }
    }
  }
  echo $success;
}
?>
