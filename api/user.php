<?php
require '../include/All.php';


$user = new UserController($pdo);


switch ($method) {
    case "GET":
        if (!$id) {
            $userInfo = [];
            $allUser = $user->getAllUser();
            foreach ($allUser as $row) {
                $userInfo["data"][] = ["id" => $row['userId'], "name" => $row['userName'], "email" => $row['userEmail'], "password" => $row['password'], "imageUrl" => $row['userImageUrl']];
            }
            echo $allUser ? json_encode($userInfo) : json_encode(["Message" => "Not user yet"]);
        } else {

            $row = $user->getUser($id);
            $data["data"] = ["id" => $row['userId'], "name" => $row['userName'], "email" => $row['userEmail'], "password" => $row['password'], "imageUrl" => $row['userImageUrl']];
            echo json_encode($data);
        }
        break;

    case "POST":
        $postedUser = json_decode(file_get_contents("php://input"), true);
        $userName = $postedUser['name'] ?? "Undefined";
        $userEmail = $postedUser['email'] ?? "Undefined";
        $password = password_hash($postedUser['password'], PASSWORD_DEFAULT);

        if ($user->userExist($userName, $userEmail)) {
            http_response_code(409); //confilct
            echo json_encode(["success" => false, "error" => "User already exist"]);
            die();
        } 
        $user->addUser($userName, $userEmail, $password);
        break;

    case "DELETE":
        $user->removeUser($id);
        break;

    case "PATCH":
        $postedUser = json_decode(file_get_contents("php://input"), true);
        $userName = $postedUser['name'] ?? "Undefined";
        $userEmail = $postedUser['email'] ?? "Undefined";
        $password = $postedUser['password'] ?? "Undefined";
        $userImageUrl = $postedUser['userImageUrl'] ?? "Undefined";

        $user->updateUser($id, $userName, $userEmail, $password, $userImageUrl);
        break;
}
