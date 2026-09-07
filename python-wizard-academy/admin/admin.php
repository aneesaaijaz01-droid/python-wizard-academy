<?php
require_once '../config/config.php';

// Admin login check
if(!isset($_SESSION['admin_id'])){
  header("Location: ../auth/login.php");
  exit;
}

$pdo = getDB();
$students = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$total_students = count($students);
$total_xp = $pdo->query("SELECT SUM(xp) FROM users")->fetchColumn() ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Hogwarts Academy</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<style>
:root{--gold:#D4AF37}
body{background:#0F0F1A;font-family:'Poppins',sans-serif;color:white}
.font-mag{font-family:'Cinzel Decorative',serif}
.glass{background:rgba(26,26,46,0.85);backdrop-filter:blur(16px);border:1px solid rgba(212,175,55,0.15);border-radius:20px}
.sky{position:fixed;inset:0;background:radial-gradient(1000px 500px at 20% -10%, #1e1b4b, transparent), radial-gradient(600px 400px at 90% 0%, rgba(212,175,55,0.12), transparent);z-index:0}
</style>
</head>
<body class="min-h-screen">
<div class="sky"></div>
<div class="relative z-10 max-w-6xl mx-auto p-4 md:p-8">

  <!-- Header -->
  <div class="flex justify-between items-center mb-8">
    <div>
      <h1 class="font-mag text-2xl" style="color:var(--gold)">🏰 Headmaster's Tower</h1>
      <p class="text-sm opacity-60">Admin Dashboard — All Wizards</p>
    </div>
    <a href="../auth/logout.php" class="px-5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-sm">Logout →</a>
  </div>

  <!-- Stats -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="glass p-6"><p class="text-sm opacity-60">Total Wizards</p><p class="text-3xl font-bold mt-1" style="color:var(--gold)"><?= $total_students ?></p></div>
    <div class="glass p-6"><p class="text-sm opacity-60">Total XP Earned</p><p class="text-3xl font-bold mt-1" style="color:var(--gold)"><?= $total_xp ?></p></div>
    <div class="glass p-6"><p class="text-sm opacity-60">Active Today</p><p class="text-3xl font-bold mt-1" style="color:var(--gold)"><?= $total_students ?></p><p class="text-xs opacity-50 mt-1">All Gmail accounts</p></div>
  </div>

  <!-- Students Table -->
  <div class="glass p-6 md:p-8">
    <h2 class="font-mag text-lg mb-4" style="color:var(--gold)">📜 Registered Wizards (Gmail)</h2>
    
    <?php if(empty($students)): ?>
      <p class="opacity-60 text-sm">Abhi koi student register nahi hua. Register page se naya Gmail add karo.</p>
    <?php else: ?>
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead><tr class="opacity-50 border-b border-white/10">
        <th class="text-left py-3">#</th><th class="text-left">Name</th><th class="text-left">Gmail</th><th class="text-left">Username</th><th class="text-left">XP</th><th class="text-left">Level</th><th class="text-left">Joined</th>
      </tr></thead>
      <tbody>
        <?php foreach($students as $s): ?>
        <tr class="border-b border-white/5 hover:bg-white/5">
          <td class="py-3"><?= $s['id'] ?></td>
          <td><?= htmlspecialchars($s['full_name']) ?></td>
          <td style="color:#E8C75A"><?= htmlspecialchars($s['email']) ?></td>
          <td class="opacity-70"><?= htmlspecialchars($s['username']) ?></td>
          <td><span class="px-2 py-1 rounded-full text-xs" style="background:rgba(212,175,55,0.2);color:var(--gold)"><?= $s['xp'] ?> XP</span></td>
          <td>Lvl <?= $s['level'] ?></td>
          <td class="opacity-60"><?= date('d M', strtotime($s['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
    <?php endif; ?>

    <div class="mt-6 flex gap-3">
      <a href="../auth/register.php" class="px-4 py-2 rounded-xl text-sm" style="background:var(--gold);color:black;font-weight:600">+ Add New Wizard</a>
      <a href="../student/dashboard.php" class="px-4 py-2 rounded-xl text-sm bg-white/10">View Student Side →</a>
    </div>
  </div>

</div>
</body>
</html>