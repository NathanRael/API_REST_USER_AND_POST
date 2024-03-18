<?php

class UserController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public  function userExist($userName, $userEmail)
    {
        $query = $this->pdo->prepare("SELECT * FROM user where userName = :name OR userEmail = :email");
        $query->execute([
            "name" => $userName,
            "email" => $userEmail,
        ]);

        $result = $query->fetchAll();
        return count($result) > 0 ? true : false;
    }


    public function getUser($id): array
    {
        if (!idExist($id, $this->pdo, "user", "userId")) {
            http_response_code(404);
            echo json_encode(["success" => false, "Error" => "The userId : $id doesn't exist"]);
            die;
        }

        $query = $this->pdo->prepare("SELECT * FROM user WHERE userId = :id");
        $query->execute([
            "id" => $id,
        ]);

        $user = $query->fetch();
        return $user;
    }

    public function getUserInfo($infoTable, $info): array
    {
        $query = $this->pdo->prepare("SELECT * FROM user WHERE $infoTable = :infoName");
        $query->execute([
            "infoName" => $info,
        ]);
        $user = $query->fetchAll();
        return $user;
    }

    public function userInfoExist($array)
    {
        return count($array) > 0 ? true : false;
    }


    public function getAllUser(): array
    {
        $query = $this->pdo->query("SELECT * FROM user");
        $allUser = $query->fetchAll();

        return $allUser;
    }

    public function addUser($userName, $userEmail, $password, $userImage = null)
    {
        $query = $this->pdo->prepare("INSERT INTO user(userId,userName,userEmail,password, userImageUrl) VALUES (:id,:name,:email,:password, :imageUrl)");
        $query->execute([
            "id" => createUniqId(),
            "name" => strtolower($userName),
            "email" => strtolower($userEmail),
            "password" => $password,
            "imageUrl" => $userImage
        ]);
        echo json_encode(["success" => "User added successfully"]);
    }

    public function removeUser($id)
    {
        if (!idExist($id, $this->pdo, "user", "userId")) {
            echo json_encode(["success" => false, "error" => "The user doesn't exist"]);
            die;
        }
        $query = $this->pdo->prepare("DELETE FROM user WHERE userId = :id");
        $query->execute([
            "id" => $id,
        ]);

        echo json_encode(["success" => "User removed successfully"]);
    }

    public function updateUser($id, $userName, $userEmail, $password, $userImageUrl = null)
    {
        if (!idExist($id, $this->pdo, "user", "userId")) {
            http_response_code(404);
            echo json_encode(["success" => false, "error" => "The user doesn't exist"]);
            die;
        }

        $query = $this->pdo->prepare("UPDATE  user SET userEmail = :email, userName = :name, userImageUrl = :imageUrl, password = :password WHERE userId = :id");
        $query->execute([
            "id" => $id,
            "email" => $userEmail,
            "name" => $userName,
            "imageUrl" => $userImageUrl,
            "password" => $password
        ]);

        return $this->getUser($id);
    }


    public function logout()
    {
        session_unset();
        session_destroy();
        echo json_encode(["success" => "User logged out"]);
    }
}
