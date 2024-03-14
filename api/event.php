<?php
require "../include/All.php";
$event = new EventController($pdo);


switch ($method) {
    case "GET":

        if (!$id) {

            if (!empty($_GET['limit'])) {
                $limit =  $_GET['limit'];
                $datas = $event->getRecentEvent($limit);
            } else {
                $datas = $event->getAllEvent();
            }
            foreach ($datas as $data) {
                $json_data["data"][] = ["id" => $data['eventId'], "title" => $data['eventTitle'], "desc" => $data['eventDesc'], "date" => $data['eventPostDate'], "imageUrl" => $data['eventImage']];
            }

            echo json_encode($json_data);
        } else {
            $data = $event->getEvent($id);
            $json_data["data"] = ["id" => $data['eventId'], "title" => $data['eventTitle'], "desc" => $data['eventDesc'], "date" => $data['eventPostDate'], "imageUrl" => $data['eventImage']];
            echo json_encode($json_data);
        }
        break;
    case "POST":
        // $data = json_decode(file_get_contents("php://input"), true);
        $title = $_POST['title'];
        $desc = $_POST['desc'];
        $imageUrl = $_POST['imageUrl'] ?? null;
        if (isset($_FILES['imageUrl'])) {
            $fileName = time() . $_FILES['imageUrl']['name'];
            $fileTmpName = $_FILES['imageUrl']["tmp_name"];
            $destination = $_SERVER["DOCUMENT_ROOT"] . "/Rofia/images" . "/" . $fileName;

            $event->addEvent($title, $desc, $fileName);
            move_uploaded_file($fileTmpName, $destination);
        } else {
            $event->addEvent($title, $desc);
        }

        break;
    case "PATCH":
        // $data = json_decode(file_get_contents("php://input"), true);
        $title = $_POST['title'];
        $desc = $_POST['desc'];
        $imageUrl = $_POST['imageUrl'] ?? null;
        if (isset($_FILES['imageUrl'])) {
            $fileName = time() . $_FILES['imageUrl']['name'];
            $fileTmpName = $_FILES['imageUrl']["tmp_name"];
            $destination = $_SERVER["DOCUMENT_ROOT"] . "/Rofia/images" . "/" . $fileName;

            $event->updateEvent($id, $title, $desc, $imageUrl);
            move_uploaded_file($fileTmpName, $destination);
        } else {
            $event->updateEvent($id, $title, $desc);
        }

        break;
    case "DELETE":
        if ($id) {
            $event->removeEvent($id);
        } else {
            $event->removeAllEvent();
        }
        break;
}
