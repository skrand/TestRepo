<?php
include_once('libs/DB.php');

$db = new DB();


die();

// check that everything is there
if(!isset($_POST) || empty($_POST)) redirect();

if(!isset($_POST['name'])) redirect();
if(!isset($_POST['email'])) redirect();
if(!isset($_POST['password'])) redirect();
if(!isset($_POST['repassword'])) redirect();

if($_POST['password'] !== $_POST['repassword']) redirect();

if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) redirect();

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

// unique email?
if(!$db->uniqueInUser($email) === 1) redirect();

// insert into DB
$db->createUser($name, $email, $password);

redirect();

// redirect to login
function redirect(){
    header("Location: ./");
    exit();
}