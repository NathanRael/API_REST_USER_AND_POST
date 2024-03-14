<?php
require '../include/All.php';
$request = new userRequestController($pdo);


switch ($method) {
    case "GET":
        if (!$id) {
            $json_data = [];
            $allReq = $request->getAllReq();
            foreach ($allReq as $req) {
                $json_data["data"][] = ["id" => $req['reqId'], "title" => $req['reqTitle'], "desc" => $req['reqDesc'], "state" => $req['reqState'], "user" => $req["userName"], "date" => $req["reqDate"]];
            }
            if (count($json_data) <= 0) {
                http_response_code(404);
                echo json_encode(["error" => "Not request yet"]);
                exit;
            }
            echo json_encode($json_data);
        } else {
            $datas = $request->getReq($id);
            foreach ($datas as $data) {
                $json_data["data"][] = ["id" => $data['reqId'], "title" => $data['reqTitle'], "desc" => $data['reqDesc'], "state" => $data['reqState'], "date" => $data["reqDate"]];
            }
            if (count($json_data) <= 0) {
                http_response_code(404);
                echo json_encode(["error" => "Not request yet"]);
                exit;
            }
            echo json_encode($json_data);
        }
        break;

    case "POST":
        $postedReq = json_decode(file_get_contents("php://input"), true);
        $title = $postedReq["title"];
        $desc = $postedReq['desc'];
        // $userId = $postedReq['userId'];
        $reqState = "envoyée";
        $request->newReq($id, $title, $desc, $reqState);
        break;

    case "DELETE":
        $request->removeReq($id);
        break;

    case "PATCH":
        if ($id) {
            $postedReq = json_decode(file_get_contents("php://input"), true);
            $reqState = $postedReq['state'];
            // $id = $postedReq['reqId'];
            $request->updateReqState($id, $reqState);
        } else {
            http_response_code(404);
            echo json_encode(['error' => "No request selected"]);
        }
        break;
}
