<?php

use Notihnio\MultipartFormDataParser\MultipartFormDataParser;

require '../include/All.php';

$adminPassword = '$2y$10$U5soZaw2FvYs4Epx6FgqXecVGAbiykkr2DgT.nq5aVW1lv5F.T7lO';
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
        // $postedUser = json_decode(file_get_contents("php://input"), true);
        $postMethod = $_POST['type'] ?? "POST";
        $userName = $_POST['name'];
        $userEmail = $_POST['email'];
        $imageDir = $_SERVER["DOCUMENT_ROOT"] . "/Rofia/images";



        if ($postMethod  == "PUT") {
            $userInTable = $user->getUserInfo("userEmail", $userEmail)[0];
            $oldPassword = $_POST['oldPassword'];
            $password =  password_hash($_POST['newPassword'], PASSWORD_DEFAULT);

            if (!password_verify($oldPassword, $userInTable['password'])) {
                http_response_code(406);
                echo json_encode(["error" => "Wrong password, please try again"]);
                exit();
            }
            if (isset($_FILES['imageUrl'])) {
                $fileName = time() . $_FILES['imageUrl']['name'];
                $fileTmpName = $_FILES['imageUrl']["tmp_name"];

                $newUserInfo = $user->updateUser($id, $userName, $userEmail, $password, $fileName);
                echo json_encode([
                    "success" => "user updated successfuly",
                    "data" => [
                        "id" => $newUserInfo['userId'],
                        "roles" =>  password_verify($password, $adminPassword) ? "admin" : "client",
                        "name" => $newUserInfo['userName'],
                        "email" => $newUserInfo['userEmail'],
                        "password" => $newUserInfo['password'],
                        "imageUrl" => $newUserInfo['userImageUrl'] ?? null,
                    ]
                ]);
                move_uploaded_file($fileTmpName, $imageDir . "/" . $fileName);
            } else {
                $newUserInfo = $user->updateUser($id, $userName, $userEmail, $password);
                echo json_encode([
                    "success" => "user updated successfuly",
                    "data" => [
                        "id" => $newUserInfo['userId'],
                        "roles" =>  password_verify($password, $adminPassword) ? "admin" : "client",
                        "name" => $newUserInfo['userName'],
                        "email" => $newUserInfo['userEmail'],
                        "password" => $newUserInfo['password'],
                        "imageUrl" => $newUserInfo['userImageUrl'] ?? null,
                    ]
                ]);
            }
        } else {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            if ($user->userExist($userName, $userEmail)) {
                http_response_code(409); //confilct
                echo json_encode(["success" => false, "error" => "User already exist"]);
                die();
            }
            if (isset($_FILES['imageUrl'])) {
                $fileName = time() . $_FILES['imageUrl']['name'];
                $fileTmpName = $_FILES['imageUrl']["tmp_name"];
                $user->addUser($userName, $userEmail, $password, $fileName);
                move_uploaded_file($fileTmpName, $imageDir . "/" . $fileName);
            } else {
                $user->addUser($userName, $userEmail, $password);
            }
        }


        break;

    case "DELETE":
        $user->removeUser($id);
        break;

    case "PUT":
        if ($id) {
            $request = MultipartFormDataParser::parse();
            $params = $request->params;
            $files = $request->files;

            // $postedUser = json_decode(file_get_contents("php://input"), true);
            $userName = $params['name'] ?? "Undefined";
            $userEmail = $params['email'] ?? "Undefined";
            $oldPassword = $params['oldPassword'];
            $password = password_hash($params['newPassword'], PASSWORD_DEFAULT);
            // $userImageUrl = $files['imageUrl'] ?? "Undefined";

            $userInTable = $user->getUserInfo("userEmail", $userEmail)[0];
            if (!password_verify($oldPassword, $userInTable['password'])) {
                http_response_code(406);
                echo json_encode(["error" => "Wrong password, please try again"]);
                exit();
            }
            if (isset($files['imageUrl'])) {
                $fileName = time() . $files['imageUrl']['name'];
                $fileTmpName = $files['imageUrl']["tmp_name"];
                $destination = $_SERVER["DOCUMENT_ROOT"] . "/Rofia/images" . "/" . $fileName;

                $user->updateUser($id, $userName, $userEmail, $password, $fileName);
                move_uploaded_file($fileTmpName, $destination);
            } else {
                $user->updateUser($id, $userName, $userEmail, $password);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => "No user selected"]);
        }
        break;
}
