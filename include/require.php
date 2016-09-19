<?php
//auth user and connect to database
require "include/auth.php";
require "header.php";
//get the request and form data
if(!empty($_GET)) extract($_GET);
if(!empty($_POST)) extract($_POST);
?>
