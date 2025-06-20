<?php

use Riotoon\Service\DbConnection;
use Riotoon\Repository\VoteRepository;

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(403);
    die();
}

$connection = DbConnection::GetConnection();

$vote = new VoteRepository();
$user = (int) $_SESSION['User'];
if ($_POST['vote'] == 1)
    $success = $vote->like(clean($_POST['id']), $user);
else
    $success = $vote->dislike(clean($_POST['id']), $user);

$req = $connection->prepare("SELECT likes, dislikes FROM webtoon WHERE w_id = :id");
$req->bindValue(':id', clean($_POST['id']));
$req->execute();
header('Content-type: application/json');
$record = $req->fetch(PDO::FETCH_ASSOC);
$record['success'] = $success;
die(json_encode($record));