<?php
require "../include/All.php";
$event = new EventController($pdo);

switch ($method) {
    case "GET":
        if (!$id) {
            $datas = $event->getAllEvent();
            foreach ($datas as $data) {
                $json_data["data"][] = ["id" => $data['eventId'], "title" => $data['eventTitle'], "desc" => $data['eventDesc'], "date" => $data['eventPostDate']];
            }

            echo json_encode($json_data);
        } else {
            $data = $event->getEvent($id);
            $json_data["data"] = ["id" => $data['eventId'], "title" => $data['eventTitle'], "desc" => $data['eventDesc'], "date" => $data['eventPostDate']];
            echo json_encode($json_data);
        }
        break;
    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        $title = $data['title'];
        $desc = $data['desc'];
        $imageUrl = $data['imageUrl'] ?? null;
        $event->addEvent($title, $desc, $imageUrl);
        break;
    case "PATCH":
        $data = json_decode(file_get_contents("php://input"), true);
        $title = $data['title'];
        $desc = $data['desc'];
        $imageUrl = $data['imageUrl'] ?? null;
        $event->updateEvent($id, $title, $desc, $imageUrl);
        break;
    case "DELETE":
        if (!$id) {
            $event->removeEvent($id);
        } else {
            $event->removeAllEvent();
        }
        break;
}
