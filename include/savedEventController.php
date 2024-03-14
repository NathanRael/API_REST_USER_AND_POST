<?php
class SavedEventController
{
    private  $pdo;
    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function saveEvent($eventId, $userId)
    {
        if (!idExist($userId, $this->pdo, "user", "userId")) {
            http_response_code(404);
            echo json_encode(["success" => false, "error" => "Unable to save event, The user doesn't exist"]);
            die;
        }
        if (!idExist($eventId, $this->pdo, "event", "eventId")) {
            http_response_code(404);
            echo json_encode(["success" => false, "error" => "Unable to save event, The event doesn't exist"]);
            die;
        }


        $query = $this->pdo->prepare("SELECT * FROM saved_event WHERE eventId = :eventId AND userId = :userId");
        $query->execute([
            "eventId" => $eventId,
            "userId" => $userId,
        ]);

        $data = $query->fetchAll();
        if (count($data) > 0) {
            http_response_code(404);
            echo json_encode(["success" => false, "error" => "Unable to save event, The event already exist"]);
            die;
        }
        $query = $this->pdo->prepare("INSERT INTO saved_event(savedEventId, eventId, userId) VALUES  (:id,:eventId, :userId)");
        $query->execute([
            "id" => createUniqId(),
            "eventId" => $eventId,
            "userId" => $userId,
        ]);
        echo json_encode(["success" => "Event saved successfully"]);
    }

    public function getAllSavedEvent()
    {

        $query = $this->pdo->query("SELECT event.eventId, event.eventTitle, event.eventDesc, event.eventImage, event.eventPostDate, user.userName, saved_event.userId, saved_event.savedEventId FROM event INNER JOIN saved_event ON event.eventId = saved_event.eventId INNER JOIN user ON saved_event.userId = user.userId");
        $datas = $query->fetchAll();
        if (count($datas) <= 0) {
            http_response_code(404);
            echo json_encode(["error" => "No event saved"]);
            exit;
        }
        return $datas;
    }
    public function getSavedEvent($userId)
    {
        if (!idExist($userId, $this->pdo, "user", "userId")) {
            http_response_code(404);
            echo json_encode(["success" => false, "error" => "Unable to get event, the user doesn't exist"]);
            die;
        }

        $query = $this->pdo->prepare("SELECT * FROM event INNER JOIN saved_event ON event.eventId = saved_event.eventId WHERE saved_event.userId = :userId");
        $query->execute([
            "userId" => $userId,
        ]);
        $datas = $query->fetchAll();
        if (count($datas) <= 0) {
            http_response_code(404);
            echo json_encode(["error" => "No event saved"]);
            exit;
        }
        return $datas;
    }

    public function removeSavedEvent($id)
    {
        if (!idExist($id, $this->pdo, "saved_event", "savedEventId")) {
            http_response_code(404);
            die(json_encode(["success" => false, "error" => "Unable to remove saved event, saved event not found"]));
        }
        $query = $this->pdo->prepare("DELETE FROM saved_event WHERE savedEventId = :id");
        $query->execute([
            "id" => $id
        ]);

        echo json_encode(["success" => "saved event $id removed successfully"]);
    }
    public function removeAllSavedEvent()
    {
        $this->pdo->query("DELETE FROM saved_event");
        echo json_encode(["success" => "All saved event removed successfully"]);
    }
}
