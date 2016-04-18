<?php
require_once($_SERVER['DOCUMENT_ROOT']."/shopping_list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
session_destroy();
header("Location:http://php-nwcc.rhcloud.com/shopping_list/login.php");
?>