<?php
ini_set("session.cookie_domain", "localhost");
session_set_cookie_params(3600, '/', 'localhost');
if (!isset($_SESSION)) {
    session_start();
}
header("Access-Control-Allow-Origin: http://localhost:5173");
header('Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, X-CSRF-Token, Accept');
header('Access-Control-Allow-Methods: GET,POST,DELETE,PATCH,OPTIONS,PUT');
header('Access-Control-Allow-Credentials: true');
