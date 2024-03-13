<?php
require __DIR__ . DIRECTORY_SEPARATOR . 'Db.php';
require  __DIR__ . DIRECTORY_SEPARATOR . 'header.php';
require  __DIR__ . DIRECTORY_SEPARATOR . 'functions.php';
require  __DIR__ .  DIRECTORY_SEPARATOR . 'UserController.php';
require  __DIR__ .  DIRECTORY_SEPARATOR . 'EventController.php';
require  __DIR__ .  DIRECTORY_SEPARATOR . 'SavedEventController.php';
require  __DIR__ .  DIRECTORY_SEPARATOR . 'userRequestController.php';

$conn = new DB();
$pdo = $conn->connect();
$method = $_SERVER['REQUEST_METHOD'];
$uri = explode("/", $_SERVER['REQUEST_URI']);
$id = $uri[4] ?? null;
