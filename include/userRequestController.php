<?php
class userRequestController
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllReq()
    {
        $query = $this->pdo->query("SELECT * FROM user_request INNER JOIN user ON user.userId = user_request.userId");
        $data = $query->fetchAll();
        return $data;
    }

    public function getReq($userId)
    {
        if (!idExist($userId, $this->pdo, "user", "userId")) {
            http_response_code(404);
            echo json_encode(["error" => "user not found"]);
            exit;
        }
        $query = $this->pdo->prepare("SELECT * FROM user_request WHERE  userId = :userId");
        $query->execute([
            "userId" => $userId,
        ]);
        $data = $query->fetchAll();
        if (count($data) <= 0) {
            http_response_code(404);
            echo json_encode(["error" => "Not request found"]);
            exit;
        }
        return $data;
    }

    public function removeReq($id)
    {
        if (!idExist($id, $this->pdo, "user_request", "reqId")) {
            http_response_code(404);
            echo json_encode(["error" => "Request not found"]);
            exit;
        }
        $query = $this->pdo->prepare("DELETE  FROM user_request WHERE reqId = :id");
        $query->execute([
            "id" => $id,
        ]);
        echo json_encode(["success" => "Request deleted successfully"]);
    }

    public function newReq($userId, $title, $desc, $state)
    {
        $query = $this->pdo->prepare("INSERT INTO user_request(reqId,reqTitle,reqDesc,reqState,userId) VALUES (:id, :title,:desc,:state,:userId)");
        $query->execute([
            "id" => createUniqId(),
            "title" => $title,
            "desc" => $desc,
            "state" => $state,
            "userId" => $userId
        ]);
        echo json_encode(["success" => "Request added successfully"]);
    }

    public function updateReqState($id, $state)
    {
        if (!idExist($id, $this->pdo, "user_request", "reqId")) {
            http_response_code(404);
            echo json_encode(["error" => "Request not found"]);
            exit;
        }
        $query = $this->pdo->prepare("UPDATE user_request SET reqState = :state WHERE reqId = :id");
        $query->execute([
            "id" => $id,
            "state" => $state,
        ]);
        echo json_encode(["success" => "Request updated successfully"]);
    }
}
