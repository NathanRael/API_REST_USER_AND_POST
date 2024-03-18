<?php
require '../include/All.php';

$savedEvent = new SavedEventController($pdo);

switch ($method) {
    case "GET":
        if (!$id) {
            $datas = $savedEvent->getAllSavedEvent();
            foreach ($datas as $data) {
                $json_data["data"][] = ["id" => $data['savedEventId'], "title" => $data['eventTitle'], "desc" => $data['eventDesc'], "date" => $data['eventPostDate'], "postId" => $data['eventId'], "userId" => $data['userId'], "userName" => $data['userName'], "imageUrl" => $data['eventImage']];
            }
            echo json_encode($json_data);
        } else {
            $datas = $savedEvent->getSavedEvent($id);
            foreach ($datas as $data) {
                $json_data["data"][] = ["id" => $data['savedEventId'], "title" => $data['eventTitle'], "desc" => $data['eventDesc'], "date" => $data['eventPostDate'], "postId" => $data['eventId'], "imageUrl" => $data['eventImage']];
            }
            echo json_encode($json_data);
        }

        break;
    case "POST":
        if (!$id) {
            echo json_encode(["error" => "No event id selected"]);
            die;
        }
        $data = json_decode(file_get_contents("php://input"), true);
        $userId = $data['userId'];
        $savedEvent->saveEvent($id, $userId);
        break;
    case "DELETE":
        if (!$id) {
            $savedEvent->removeAllSavedEvent();
        } else {
            $savedEvent->removeSavedEvent($id);
        }

        break;
}
