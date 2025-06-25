<?php
require_once 'db.php';
$id = $_GET['id'] ?? 0;
$video = $pdo->prepare("SELECT video_file FROM theories WHERE id=? AND type='video' LIMIT 1");
$video->execute([$id]);
$videoFile = $video->fetchColumn();

$text = $pdo->prepare("SELECT content FROM theories WHERE id=? AND type='theory' LIMIT 1");
$text->execute([$id]);
$theoryText = $text->fetchColumn();

$tasks = $pdo->prepare("SELECT title, description FROM tasks WHERE theory_id=? ORDER BY sort_order");
$tasks->execute([$id]);
$taskList = $tasks->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
  'video' => $videoFile,
  'text' => $theoryText,
  'tasks' => $taskList
]);
