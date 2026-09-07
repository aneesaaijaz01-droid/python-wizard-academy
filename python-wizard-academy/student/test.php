<?php
require_once '../config/config.php';
if(!isset($_SESSION['user_id'])){ header("Location:../auth/login.php"); exit; }
$pdo=getDB(); $u=$pdo->prepare("SELECT * FROM users WHERE id=?");
$u->execute([$_SESSION['user_id']]); $user=$u->fetch();
?>
<!DOCTYPE html><html><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Quizzes - PyWizard</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=Inter:wght@500;700&display=swap" rel="stylesheet">
<style>body{font-family:Inter;background:#0A0A0A}.font-mag{font-family:'Cinzel Decorative'}</style>
</head><body class="flex min-h-screen">
<aside class="w- bg-[#151515] border-r border-white/5 fixed h-screen p-5 hidden lg:flex flex-col justify-between">
<div><div class="font-bold text-white">PyWizard</div>
<nav class="mt-8 space-y-1">
<a href="dashboard.php" class="text-white/50 px-4 py-2.5 rounded-xl text- block">📊 Dashboard</a>
<a href="course.php" class="text-white/50 px-4 py-2.5 rounded-xl text- flex justify-between"><span>📚 My Course</span><span class="bg-white/10 text- px-2 rounded-full">65%</span></a>
<a href="test.php" class="bg-white text-black px-4 py-2.5 rounded-full text- font-bold block">🧠 Quizzes</a>
<a href="project.php" class="text-white/50 px-4 py-2.5 rounded-xl text- block">🧪 Projects</a>
<a href="progress.php" class="text-white/50 px-4 py-2.5 rounded-xl text- block">📈 Progress</a>
<a href="achievement.php" class="text-white/50 px-4 py-2.5 rounded-xl text- block">🏆 Achievements</a>
<a href="notification.php" class="text-white/50 px-4 py-2.5 rounded-xl text- flex justify-between"><span>🔔 Notifications</span><span class="bg-[#F5C518] text-black text- px-2 rounded-full font-bold">3</span></a>
</nav></div><a href="../auth/logout.php" class="text-white/30 text-xs">Logout</a>
</aside>
<div class="flex-1 lg:ml-">
<header class="h- bg-[#111] border-b border-white/5 flex items-center px-6"><h1 class="font-mag text-white">Quizzes</h1></header>
<main class="p-6 max-w- mx-auto">
<div class="bg-[#151515] border border-[#F5C518]/20 rounded- p-6">
<p class="text-white/40 text-">Question 1 of 10 • ⏱ 09:45</p>
<p class="text-white text- font-semibold mt-3">What is the correct way to create a variable in Python?</p>
<div class="mt-4 space-y-2 text-">
<label class="flex gap-3 bg-[#1E1E1E] p-3 rounded-xl text-white/60 border border-white/5"><input type="radio" name="q"> A. var x = 10</label>
<label class="flex gap-3 bg-emerald-500/10 border border-emerald-500/30 p-3 rounded-xl text-white"><input type="radio" checked> B. x = 10</label>
<label class="flex gap-3 bg-[#1E1E1E] p-3 rounded-xl text-white/60 border border-white/5"><input type="radio" name="q"> C. int x = 10</label>
<label class="flex gap-3 bg-[#1E1E1E] p-3 rounded-xl text-white/60 border border-white/5"><input type="radio" name="q"> D. x == 10</label>
</div>
<button class="mt-6 float-right bg-white text-black px-6 py-2 rounded-full text- font-bold">Next →</button>
</div>
</main></div></body></html>