<?php

function createUniqId(): string
{
    $uniqId = uniqid(rand(), true) . '.' . bin2hex(random_bytes(16));
    return $uniqId;
}

function idExist($id, $pdo, $tableNmae, $fieldName)
{
    $query = $pdo->prepare("SELECT * FROM $tableNmae where $fieldName = :id");
    $query->execute([
        "id" => $id,
    ]);
    $result = $query->fetchAll();
    return count($result) > 0 ? true : false;
}
