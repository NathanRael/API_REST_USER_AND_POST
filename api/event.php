<?php

use Notihnio\MultipartFormDataParser\MultipartFormDataParser;

require "../include/All.php";
// use Notihnio\MultipartFormDataParser;
$event = new EventController($pdo);


switch ($method) {
    case "GET":
        if (isset($_GET['search'])) {
            $datas = $event->searchEvent($_GET['search']);
            foreach ($datas as $data) {
                $json_data["data"][] = ["id" => $data['eventId'], "title" => $data['eventTitle'], "desc" => $data['eventDesc'], "date" => $data['eventPostDate'], "imageUrl" => $data['eventImage']];
            }
            echo json_encode($json_data);
            die;
        }
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
        $postMethod = $_POST['type'] ?? "POST";
        $title = $_POST['title'];
        $desc = $_POST['desc'];
        $imageDir = $_SERVER["DOCUMENT_ROOT"] . "/Rofia/images";

        if ($postMethod == "PUT") { // PATCH methods
            if (isset($_FILES['imageUrl'])) {

                $fileName = time() . $_FILES['imageUrl']['name'];
                $fileTmpName = $_FILES['imageUrl']["tmp_name"];
                $event->updateEvent($id, $title, $desc, $fileName);
                move_uploaded_file($fileTmpName, $imageDir . "/" . $fileName);
            } else {

                $event->updateEvent($id, $title, $desc);
            }
        } else {
            if (isset($_FILES['imageUrl'])) {
                $fileName = time() . $_FILES['imageUrl']['name'];
                $fileTmpName = $_FILES['imageUrl']["tmp_name"];
                $event->addEvent($title, $desc, $fileName);
                move_uploaded_file($fileTmpName, $imageDir . "/" . $fileName);
            } else {
                $event->addEvent($title, $desc);
            }
        }

        break;
    case "PUT":
        // using third-party library (notihnio)
        $request = MultipartFormDataParser::parse();
        $params = $request->params;
        $files = $request->files;

        $title = $params['title'];
        $desc = $params['desc'];
        if (isset($_FILES['imageUrl'])) {
            $fileName = time() . $files['imageUrl']['name'];
            $fileTmpName = $files['imageUrl']["tmp_name"];
            $destination = $_SERVER["DOCUMENT_ROOT"] . "/Rofia/images" . "/" . $fileName;
            $event->updateEvent($id, $title, $desc, $fileName);
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
