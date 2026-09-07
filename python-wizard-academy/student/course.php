<?php
require_once '../config/config.php';
if(!isset($_SESSION['user_id'])){ header("Location: ../auth/login.php"); exit; }
$pdo=getDB(); $u=$pdo->prepare("SELECT * FROM users WHERE id=?"); $u->execute([$_SESSION['user_id']]); $user=$u->fetch();
$first = htmlspecialchars(explode(' ', $user['full_name'])[0]);
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>My Course - PyWizard</title><script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@500;700;800&family=Cinzel+Decorative:wght@700&display=swap" rel="stylesheet">
<style>body{font-family:Inter;background:#0A0A0A}.font-mag{font-family:'Cinzel Decorative'}</style>
</head><body class="flex min-h-screen overflow-x-hidden">
<aside class="w-[260px] bg-[#151515] border-r border-white/5 fixed h-screen left-0 top-0 p-5 hidden lg:flex flex-col justify-between">
<div><div class="font-bold text-white text-sm"><span class="bg-white text-black w-8 h-8 rounded-lg inline-flex items-center justify-center text-xs"><></span> PyWizard</div>
<nav class="mt-8 space-y-1">
<a href="dashboard.php" class="flex items-center justify-between text-white/50 px-4 py-2.5 rounded-xl text-[13px] hover:text-white"><span>📊 Dashboard</span></a>
<a href="course.php" class="flex items-center justify-between bg-white text-black px-4 py-2.5 rounded-full text-[13px] font-bold"><span>📚 My Course</span><span class="bg-emerald-500 text-white text-[10px] px-2 py-0.5 rounded-full">65%</span></a>
<a href="test.php" class="flex items-center justify-between text-white/50 px-4 py-2.5 rounded-xl text-[13px] hover:text-white"><span>🧠 Quizzes</span></a>
<a href="project.php" class="flex items-center justify-between text-white/50 px-4 py-2.5 rounded-xl text-[13px] hover:text-white"><span>🧪 Projects</span></a>
<a href="progress.php" class="flex items-center justify-between text-white/50 px-4 py-2.5 rounded-xl text-[13px] hover:text-white"><span>📈 Progress</span></a>
<a href="achievement.php" class="flex items-center justify-between text-white/50 px-4 py-2.5 rounded-xl text-[13px] hover:text-white"><span>🏆 Achievements</span></a>
<a href="notification.php" class="flex items-center justify-between text-white/50 px-4 py-2.5 rounded-xl text-[13px] hover:text-white"><span>🔔 Notifications</span><span class="bg-[#F5C518] text-black text-[10px] px-2 py-0.5 rounded-full font-bold">3</span></a>
</nav></div>
<a href="../auth/logout.php" class="text-white/30 text-[12px]">🚪 Logout</a>
</aside>

<div class="flex-1 lg:ml-[260px] w-full">
<header class="h-[64px] bg-[#111] border-b border-white/5 flex items-center justify-between px-6 sticky top-0"><div class="flex items-center gap-3"><span class="lg:hidden text-white font-bold">PyWizard</span><h1 class="font-mag text-white text-[18px]">My Course</h1></div><div class="w-8 h-8 bg-violet-500 rounded-full flex items-center justify-center font-bold text-white text-xs"><?= strtoupper($first[0]) ?></div></header>
<main class="p-6 max-w-[1200px] mx-auto space-y-6">
<div class="bg-[#151515] border border-[#F5C518]/20 rounded-[24px] p-6"><div class="flex justify-between"><div><p class="text-[#F5C518] text-[10px] tracking-widest font-bold">CONTINUE</p><h2 class="text-white font-bold text-[20px] mt-1">Python Basics</h2><p class="text-white/40 text-[12px]">Variables, Loops, Functions • 8/12 lessons</p></div><span class="bg-[#F5C518] text-black text-[12px] font-bold px-3 py-1 rounded-full h-fit">65%</span></div><div class="h-2 bg-white/10 rounded-full mt-4"><div class="h-full bg-[#F5C518] rounded-full" style="width:65%"></div></div><a href="lesson.php" class="mt-4 block bg-[#F5C518] text-black text-center py-2.5 rounded-full font-bold text-[13px]">Continue Lesson →</a></div>

<div class="grid md:grid-cols-3 gap-4">
<div class="bg-[#151515] border border-white/5 rounded-[20px] p-5"><div class="w-12 h-12 bg-emerald-500/15 rounded-full flex items-center justify-center text-xl">🐍</div><h3 class="text-white font-bold mt-3">Python Basics</h3><p class="text-white/40 text-[11px]">12 lessons • Beginner</p><div class="h-1.5 bg-white/10 rounded-full mt-3"><div class="h-full bg-emerald-400 rounded-full" style="width:65%"></div></div><div class="flex gap-2 mt-4"><a href="lesson.php" class="flex-1 bg-white text-black py-2 rounded-full text-[11px] font-bold text-center">Lessons</a><a href="test.php" class="flex-1 bg-white/10 text-white py-2 rounded-full text-[11px] text-center border border-white/10">Quiz</a></div></div>
<div class="bg-[#151515] border border-white/5 rounded-[20px] p-5 opacity-60"><div class="w-12 h-12 bg-purple-500/15 rounded-full flex items-center justify-center text-xl">🪄</div><h3 class="text-white font-bold mt-3">Hogwarts Logic 🔒</h3><p class="text-white/40 text-[11px]">8 lessons • Intermediate</p><div class="h-1.5 bg-white/10 rounded-full mt-3"><div class="h-full bg-purple-400 rounded-full" style="width:0%"></div></div><p class="mt-4 text-center py-2 rounded-full bg-white/5 text-white/30 text-[11px]">1200 XP needed</p></div>
<div class="bg-[#151515] border border-white/5 rounded-[20px] p-5 opacity-60"><div class="w-12 h-12 bg-lime-400/15 rounded-full flex items-center justify-center text-xl">🧪</div><h3 class="text-white font-bold mt-3">Data Potions 🔒</h3><p class="text-white/40 text-[11px]">10 lessons • Advanced</p><div class="h-1.5 bg-white/10 rounded-full mt-3"><div class="h-full bg-lime-400 rounded-full" style="width:0%"></div></div><p class="mt-4 text-center py-2 rounded-full bg-white/5 text-white/30 text-[11px]">3500 XP needed</p></div>
</div>
</main></div></body></html>
