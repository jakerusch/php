<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();
$location_instance_id=$_POST['location_instance_id'];
$item_name=$_POST['item_name'];

// check if item exists in master items
$sql="SELECT COUNT(*) FROM master_items
  WHERE master_items.item_name='$item_name'";
  $result=$conn->query($sql);
  $row=mysqli_fetch_row($result);
  $count=$row[0];

if($count==0) {
  // does not exist in master_items
  // add to master_items, then item_instances, then list

  // add to master items
  $sql1="INSERT INTO master_items(user_id, item_name) VALUES('1', '$item_name')";
  $result1=$conn->query($sql1);

  // get auto-increment item_id from master_items
  $item_id=$conn->insert_id;

  // get sort_order from item_instances (last number in instances for that location)
  $sql2="SELECT MAX(sort_order) FROM item_instances
    INNER JOIN location_instances ON location_instances.location_id=item_instances.location_id
    WHERE location_instances.location_instance_id='$location_instance_id'";
  $result2=$conn->query($sql2);
  $row2=mysqli_fetch_row($result2);
  $sort_order=$row2[0]+1;

  // get the location_id
  $sql3="SELECT location_instances.location_id
    FROM location_instances
    WHERE location_instance_id='$location_instance_id'";
  $result3=$conn->query($sql3);
  $row3=$result3->fetch_assoc();
  $location_id=$row3['location_id'];

  // add item into item_instances table
  $sql4="INSERT INTO item_instances(item_id, location_id, sort_order) VALUES('$item_id', '$location_id', '$sort_order')";
  $result4=$conn->query($sql4);
  $item_instance_id=$conn->insert_id;

  // add item to list
  $sql5="INSERT INTO lists(location_instance_id, item_instance_id, qty, checked_status) VALUES('$location_instance_id', '$item_instance_id', '1', '0')";

  // check for final successful insert
  if($conn->query($sql5)===TRUE) {
    echo 1;
  } else {
    echo "Error: " . $sql5 . "<br>" . $conn->error;
  }

} else {
  // item exists in master_items

  // check if item exists in item_instances
  $sql="SELECT COUNT(*) FROM item_instances
    INNER JOIN master_items ON master_items.item_id=item_instances.item_id
    WHERE master_items.item_name='$item_name'";
  $result=$conn->query($sql);
  $row=mysqli_fetch_row($result);
  $count=$row[0];

  if($count==0) {
    // item does not exist in item_instances

    // get item_id from master_items
    $sql1="SELECT item_instances.item_id
      FROM item_instances
      INNER JOIN master_items ON master_items.item_id=item_instances.item_id
      WHERE master_items.item_name='$item_name'";
    $result1=$conn->query($sql1);
    $row1=mysqli_fetch_row($result1);
    $item_id=$row1['item_id'];

    // get sort order from item_instances
    $sql2="SELECT MAX(sort_order) FROM item_instances
      INNER JOIN location_instances ON location_instances.location_id=item_instances.location_id
      WHERE location_instances.location_instance_id='$location_instance_id'";
    $result2=$conn->query($sql2);
    $row2=mysqli_fetch_row($result2);
    $sort_order=$row2[0]+1;

    // get the location_id
    $sql3="SELECT location_instances.location_id
      FROM location_instances
      WHERE location_instance_id='$location_instance_id'";
    $result3=$conn->query($sql3);
    $row3=$result3->fetch_assoc();
    $location_id=$row3['location_id'];

    // add item into item_instances table
    $sql4="INSERT INTO item_instances(item_id, location_id, sort_order) VALUES('$item_id', '$location_id', '$sort_order')";
    $result4=$conn->query($sql4);
    $item_instance_id=$conn->insert_id;

    // add item to list
    $sql5="INSERT INTO lists(location_instance_id, item_instance_id, qty, checked_status) VALUES('".$location_instance_id."', '".$item_instance_id."', '1', '0')";
    // if both were successfully inserted
    if($conn->query($sql5)===TRUE) {
      echo 1;
    } else {
      echo "Error: " . $sql5 . "<br>" . $conn->error;
    }

  } else {
    // item exists in item_instances
    // add item to list

    // get the location_id
    $sql="SELECT location_instances.location_id
      FROM location_instances
      WHERE location_instances.location_instance_id='$location_instance_id'";
    $result=$conn->query($sql);
    $row=$result->fetch_assoc();
    $location_id=$row['location_id'];

    // get item_id from master_items
    $sql1="SELECT item_instances.item_instance_id
      FROM item_instances
      INNER JOIN master_items ON master_items.item_id=item_instances.item_id
      WHERE master_items.item_name='$item_name'
      AND item_instances.location_id='$location_id'";
    $result1=$conn->query($sql1);
    $row1=$result1->fetch_assoc();
    $item_instance_id=$row1['item_instance_id'];

    // check if item exists in list
    $sql2="SELECT Count(*) FROM lists
      INNER JOIN item_instances ON item_instances.item_instance_id=lists.item_instance_id
      WHERE item_instances.location_id='$location_id'
      AND item_instances.item_instance_id='$item_instance_id'";
      $result2=$conn->query($sql2);
      $row2=mysqli_fetch_row($result2);
      $count=$row2[0];

    if($count==0) {
      // add item to list
      $sql5="INSERT INTO lists(location_instance_id, item_instance_id, qty, checked_status) VALUES('".$location_instance_id."', '".$item_instance_id."', '1', '0')";
      // if both were successfully inserted
      if($conn->query($sql5)===TRUE) {
        echo 1;
      } else {
        echo "Error: " . $sql5 . "<br>" . $conn->error;
      }
    } else {
      echo 1;
    }
  }
}

?>
