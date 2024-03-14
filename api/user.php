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
        if ($id) {
            $postedUser = json_decode(file_get_contents("php://input"), true);
            $userName = $_POST['name'] ?? "Undefined";
            $userEmail = $_POST['email'] ?? "Undefined";
            $oldPassword = $_POST['oldPassword'];
            $password = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
            $userImageUrl = $_POST['userImageUrl'] ?? "Undefined";

            $userInTable = $user->getUserInfo("userEmail", $userEmail)[0];
            if (!password_verify($oldPassword, $userInTable['password'])) {
                http_response_code(406);
                echo json_encode(["error" => "Wrong password, please try again"]);
                exit();
            }
            if (isset($_FILES['imageUrl'])) {
                $fileName = time() . $_FILES['imageUrl']['name'];
                $fileTmpName = $_FILES['imageUrl']["tmp_name"];
                $destination = $_SERVER["DOCUMENT_ROOT"] . "/Rofia/images" . "/" . $fileName;

                $user->updateUser($id, $userName, $userEmail, $password, $userImageUrl);
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
