<?php


class EventController
{
    private  $pdo;
    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function addEvent($title, $desc, $image = null)
    {

        $query = $this->pdo->prepare("INSERT INTO event(eventId, eventTitle, eventDesc, eventImage) VALUES  (:id,:title,:desc,:image)");
        $query->execute([
            "id" => createUniqId(),
            "title" => $title,
            "desc" => $desc,
            "image" => $image
        ]);
        echo json_encode(["success" => "Post added successfully"]);
    }

    public function getAllEvent()
    {
        $query = $this->pdo->query("SELECT * FROM event ORDER BY eventPostDate DESC");

        return $query->fetchAll();
    }
    public function getRecentEvent($limit)
    {
        $query = $this->pdo->prepare("SELECT * FROM event ORDER BY eventPostDate  DESC LIMIT :limit ");
        $query->bindParam(':limit', $limit, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll();
    }

    public function searchEvent($title)
    {
        $query = $this->pdo->prepare("SELECT * FROM event WHERE eventTitle LIKE :title");
        $query->execute([
            "title" => "%" . $title . "%",
        ]);
        $result = $query->fetchAll();
        // if (count($result) <= 0) {
        //     echo json_encode(["error" => "Pas d'evènement trouvé"]);
        //     die;
        // }
        return $result;
    }

    public function getEvent($id)
    {
        if (!idExist($id, $this->pdo, "event", "eventId")) {
            http_response_code(404);
            return json_encode(["success" => false, "error" => "Event not found"]);
        }

        $query = $this->pdo->prepare("SELECT * FROM event WHERE eventId = :id ORDER BY eventPostDate DESC");
        $query->execute([
            "id" => $id
        ]);
        return $query->fetch();
    }
    public function removeEvent($id)
    {
        $destination = $_SERVER["DOCUMENT_ROOT"] . "/Rofia/images" . "/";
        if (!idExist($id, $this->pdo, "event", "eventId")) {
            http_response_code(404);
            die(json_encode(["success" => false, "error" => "Unable to remove event, event not found"]));
        }
        $query = $this->pdo->prepare("DELETE FROM event WHERE eventId = :id");
        $query->execute([
            "id" => $id,
        ]);
        if (file_exists($destination . $this->getEvent($id)['eventImage'])) {
            unlink($destination . $this->getEvent($id)['eventImage']);
        }

        echo json_encode(["success" => "Event removed successfully"]);
    }

    public function removeAllEvent()
    {
        $query = $this->pdo->prepare("DELETE FROM event");

        echo json_encode(["success" => "Event removed successfully"]);
    }


    public function updateEvent($id, $title, $desc, $imageUrl = null)
    {
        if (!idExist($id, $this->pdo, "event", "eventId")) {
            echo json_encode(["success" => false, "error" => "Unable to update event, event not found"]);
            die;
        }

        $query = $this->pdo->prepare("UPDATE  event SET eventTitle = :title, eventDesc = :desc, eventImage = :imageUrl WHERE eventId = :id");
        $query->execute([
            "id" => $id,
            "title" => $title,
            "desc" => $desc,
            "imageUrl" => $imageUrl
        ]);
        echo json_encode(["success" => "Event $id updated successfully"]);
    }
}
