<?php
session_start();
require "../include/All.php";
$adminPassword = '$2y$10$U5soZaw2FvYs4Epx6FgqXecVGAbiykkr2DgT.nq5aVW1lv5F.T7lO';
$user = new UserController($pdo);

if ($method == "POST") {
    $currrentUser = json_decode(file_get_contents("php://input"), true);

    if (isset($currrentUser['logout'])) {
        $user->logout();
        exit;
    }
    $userEmail = $currrentUser['email'] ?? "undefined";
    $password = $currrentUser['password'] ?? "undefined";

    $query = $user->getUserInfo("userEmail", $userEmail);
    if ($user->userInfoExist($query)) {
        $userInTable = $query[0];
        if (password_verify($password, $userInTable['password'])) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            // $_SESSION['user'] = [
            //     "id" => $userInTable['userId'],
            //     "roles" =>  password_verify($password, $adminPassword) ? "admin" : "client",
            //     "name" => $userInTable['userName'],
            //     "email" => $userInTable['userEmail'],
            //     "password" => $userInTable['password'],
            //     "imageUrl" => $userInTable['userImageUrl'] ?? null,
            // ];

            $session = setSession($userInTable['userId'], $userInTable['userName'], $userInTable['userEmail'], $userInTable['userImageUrl'], password_verify($password, $adminPassword) ? "admin" : "client");
            $data = ["success" => "User logged in successfully", "data" => $session];
            echo json_encode($data);
        } else {
            http_response_code(401);
            echo json_encode(["error" => "Invalid password"]);
        }
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Invalid Email"]);
        exit;
    }
} else if ($method == "GET") {
    if (isset($_SESSION['user'])) {
        echo json_encode(["data" => $_SESSION["user"]]);
    } else {
        // http_response_code(401);
        echo json_encode(["error" => "User not logged in"]);
    }
}
