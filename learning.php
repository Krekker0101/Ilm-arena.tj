<?php
session_start();
require_once 'db.php';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header('Location: login.php'); exit;
}

// Получаем предмет либо из прогресса
$subject = $_GET['subject'] ?? null;
if (!$subject) {
    // Получаем список предметов
    $subjects = [
        'math' => 'Математика',
        'physics' => 'Физика',
        'chemistry' => 'Химия'
    ];
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Выбор предмета</title>
        <style>
        body{background:#0a0a1a;color:#e0e0ff;font-family:'Orbitron';display:flex;align-items:center;justify-content:center;height:100vh;}
        .box{background:rgba(10,10,30,0.8);padding:40px;border-radius:12px;animation:pulse 2s infinite;}
        select,button{padding:12px; margin:10px; border-radius:8px; border:none; font-size:1rem;}
        button{cursor:pointer; background:linear-gradient(90deg,#00f0ff,#ff00dd);color:#000;}
        @keyframes pulse{0%{box-shadow:0 0 10px #00f0ff;}50%{box-shadow:0 0 30px #ff00dd;}100%{box-shadow:0 0 10px #00f0ff;}}
        </style>
    </head>
    <body>
    <div class="box">
        <h2>Выберите предмет для обучения</h2>
        <form id="subjectForm">
            <select name="subject">
                <?php foreach($subjects as $key=>$name): ?>
                    <option value="<?=$key?>"><?=$name?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Начать</button>
        </form>
    </div>
    <script>
        document.getElementById('subjectForm').onsubmit = function(e){
            e.preventDefault();
            const subj = this.subject.value;
            fetch('save_progress.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({user_id:<?=$userId?>,type:'learning_subject',data:subj})})
            .then(()=>location.href='learning.php?subject='+subj);
        };
    </script>
    </body>
    </html>
    <?php
    exit;
}

// Получаем уроки и задачи для выбранного предмета
$lessons = [
    'math' => [
        'video' => 'math_intro.mp4',
        'theories' => [
            ['title' => 'Теория 1', 'content' => 'Содержание теории 1'],
            ['title' => 'Теория 2', 'content' => 'Содержание теории 2']
        ],
        'tasks' => [
            ['title' => 'Задача 1', 'description' => 'Описание задачи 1'],
            ['title' => 'Задача 2', 'description' => 'Описание задачи 2']
        ]
    ],
    'physics' => [
        'video' => 'physics_intro.mp4',
        'theories' => [
            ['title' => 'Теория 1', 'content' => 'Содержание теории 1'],
            ['title' => 'Теория 2', 'content' => 'Содержание теории 2']
        ],
        'tasks' => [
            ['title' => 'Задача 1', 'description' => 'Описание задачи 1'],
            ['title' => 'Задача 2', 'description' => 'Описание задачи 2']
        ]
    ],
    'chemistry' => [
        'video' => 'chemistry_intro.mp4',
        'theories' => [
            ['title' => 'Теория 1', 'content' => 'Содержание теории 1'],
            ['title' => 'Теория 2', 'content' => 'Содержание теории 2']
        ],
        'tasks' => [
            ['title' => 'Задача 1', 'description' => 'Описание задачи 1'],
            ['title' => 'Задача 2', 'description' => 'Описание задачи 2']
        ]
    ]
];

$video = $lessons[$subject]['video'];
$theories = $lessons[$subject]['theories'];
$tasks = $lessons[$subject]['tasks'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Обучение</title>
    <style>
    :root{--b:#0a0a1a;--t:#e0e0ff;--a1:#00f0ff;--a2:#ff00dd;}
    body{margin:0;background:var(--b);color:var(--t);font-family:'Orbitron';}
    .container{max-width:800px;margin:140px auto 60px;padding:0 20px;}
    .section{margin-bottom:60px;animation:fadeInUp 1s ease;}
    @keyframes fadeInUp{from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);}}
    h2{font-size:2rem;border-bottom:2px solid var(--a1);padding-bottom:8px;}
    video{width:100%;border-radius:8px;}
    .nav-btn{display:block;margin:30px auto;padding:12px 24px;border:none;border-radius:30px;background:linear-gradient(90deg,var(--a1),var(--a2));color:#000;cursor:pointer;transition:transform .3s;}
    .nav-btn:hover{transform:scale(1.1);}
    </style>
</head>
<body>
<div class="container">
    <div class="section video">
        <h2>Видеоурок</h2>
        <video src="video/<?=$subject?>/<?=$video?>" controls autoplay></video>
        <button class="nav-btn" onclick="nextSection()">Далее</button>
    </div>
    <?php foreach($theories as $i=>$th): ?>
        <div class="section theory" style="display:none;">
            <h2><?=htmlspecialchars($th['title'])?></h2>
            <p><?=nl2br(htmlspecialchars($th['content']))?></p>
            <button class="nav-btn" onclick="nextSection()">Далее</button>
        </div>
    <?php endforeach; ?>
    <?php foreach($tasks as $j=>$task): ?>
        <div class="section task" style="display:none;">
            <h2>Задача <?= $j+1 ?></h2>
            <h3><?=htmlspecialchars($task['title'])?></h3>
            <p><?=nl2br(htmlspecialchars($task['description']))?></p>
            <button class="nav-btn" onclick="nextSection()">Далее</button>
        </div>
    <?php endforeach; ?>
</div>
<script>
let step = 0;
const sections = document.querySelectorAll('.section');
function nextSection() {
    if (step < sections.length - 1) {
        sections[step].style.display = 'none';
        step++;
        sections[step].style.display = 'block';
    }
}
</script>
</body>
</html>