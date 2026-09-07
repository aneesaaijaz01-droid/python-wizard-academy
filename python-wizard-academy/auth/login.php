<?php
require_once '../config/config.php';
$error = '';
$success = $_GET['registered'] ?? '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';
  
  if($email && $pass){
    try{
      $pdo = getDB();
      // 1. Users check (gmail wale students)
      $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
      $stmt->execute([$email]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if($user && password_verify($pass, $user['password_hash'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['role'] = 'student';
        header("Location: ../student/dashboard.php");
        exit;
      }
      // 2. Admin check
      $stmt = $pdo->prepare("SELECT * FROM admins WHERE email=?");
      $stmt->execute([$email]);
      $admin = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if($admin && password_verify($pass, $admin['password_hash'])){
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['role'] = 'admin';
        header("Location: ../admin/dashboard.php");
        exit;
      }
      $error = "Invalid Gmail or Password! Try student@gmail.com / wizard123";
    }catch(Exception $e){
      $error = "Database error: ".$e->getMessage();
    }
  } else {
    $error = "Please enter Gmail and Password";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Log In — Python Wizard Academy</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700;900&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<style>
:root{--gold:#D4AF37;--gold-soft:#E8C75A;--dark:#0F0F1A}
body{background:#0F0F1A;font-family:'Poppins',sans-serif;color:white}
.font-mag{font-family:'Cinzel Decorative',serif}
.sky{position:fixed;inset:0;background:radial-gradient(1200px 600px at 50% -20%, #1e1b4b 0%, #0F0F1A 60%), radial-gradient(800px 400px at 100% 0%, rgba(212,175,55,0.15), transparent);z-index:0}
.glass{background:rgba(26,26,46,0.8);backdrop-filter:blur(16px);border:1px solid rgba(212,175,55,0.15);border-radius:24px;box-shadow:0 20px 60px rgba(0,0,0,0.5)}
.input-mag{width:100%;padding:14px 16px;border-radius:14px;background:rgba(0,0,0,0.4);border:1px solid rgba(255,255,255,0.1);color:white;outline:none;transition:.2s}
.input-mag:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(212,175,55,0.15)}
.btn-gold{background:linear-gradient(135deg, var(--gold), #B8941F);color:#000;font-weight:600;padding:13px;border-radius:14px;transition:.2s}
.btn-gold:hover{transform:translateY(-1px);box-shadow:0 10px 20px rgba(212,175,55,0.3)}
</style>
</head>
<body class="min-h-screen flex items-center justify-center px-4">
<div class="sky"></div>

<div class="relative z-10 w-full max-w-md">
  <div class="text-center mb-6">
    <a href="../index.php" class="text-4xl"></a>
    <h1 class="font-mag text-2xl mt-3" style="color:var(--gold);">Enter the Academy</h1>
    <p class="text-sm opacity-60 mt-1">Speak the password, wizard.</p>
  </div>

  <form method="POST" class="glass p-8 space-y-4">
    <?php if($error): ?>
      <div class="text-sm px-4 py-3 rounded-xl" style="background:rgba(248,113,113,.15); border:1px solid #f87171; color:#fca5a5;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if($success): ?>
      <div class="text-sm px-4 py-3 rounded-xl" style="background:rgba(34,197,94,.15); border:1px solid #4ade80; color:#86efac;">Account created! Now login with your Gmail.</div>
    <?php endif; ?>

    <div>
      <label class="text-sm block mb-1.5 opacity-80">Gmail</label>
      <input type="email" name="email" required class="input-mag" placeholder="you@gmail.com" value="student@gmail.com">
    </div>
    <div>
      <label class="text-sm block mb-1.5 opacity-80">Password</label>
      <input type="password" name="password" required class="input-mag" placeholder="••••••••" value="wizard123">
    </div>

    <button type="submit" class="btn-gold w-full">🔓 Log In</button>

    <!-- <div class="text-xs text-center opacity-60 pt-2 p-3 rounded-lg bg-black/20">
      <b>Demo Login (Gmail):</b><br>
      Student: <b>student@gmail.com / wizard123</b><br>
      Admin: <b>admin@gmail.com / wizard123</b>
    </div> -->

    <p class="text-sm text-center opacity-70">
      New wizard? <a href="register.php" class="underline" style="color:var(--gold-soft);">Register here</a>
      <span class="mx-2">•</span> <a href="../index.php" class="underline opacity-60">Back to Home</a>
    </p>
  </form>
</div>
</body>
</html>