<?php
require_once '../config/config.php';
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $name = trim($_POST['full_name']?? '');
  $email = trim($_POST['email']?? '');
  $pass = $_POST['password']?? '';
  $cpass = $_POST['confirm_password']?? '';

  if($pass!== $cpass){
    $error = "Passwords don't match!";
  } elseif(strlen($pass) < 4){
    $error = "Password at least 4 characters ka ho";
  } elseif($email === 'admin@gmail.com'){
    $error = "Ye email reserved hai, dusra gmail use karo";
  } else {
    try{
      $pdo = getDB();
      $username = explode('@', $email)[0]. rand(10,99);
      $hash = password_hash($pass, PASSWORD_DEFAULT);

      $stmt = $pdo->prepare("INSERT INTO users (full_name, username, email, password_hash, xp, level, streak) VALUES (?,?,?,?,0,1,0)");
      $stmt->execute([$name, $username, $email, $hash]);

      header("Location: login.php?registered=1");
      exit;
    }catch(Exception $e){
      if(strpos($e->getMessage(), 'Duplicate')!== false){
        $error = "Ye Gmail pehle se registered hai! Login karo.";
      } else {
        $error = "Error: ".$e->getMessage();
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — Python Wizard Academy</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700;900&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<style>
:root{--gold:#D4AF37;--gold-soft:#E8C75A}
body{background:#0F0F1A;font-family:'Poppins',sans-serif;color:white}
.font-mag{font-family:'Cinzel Decorative',serif}
.sky{position:fixed;inset:0;background:radial-gradient(1200px 600px at 50% -20%, #1e1b4b 0%, #0F0F1A 60%), radial-gradient(800px 400px at 100% 0%, rgba(212,175,55,0.15), transparent);z-index:0}
.glass{background:rgba(26,26,46,0.8);backdrop-filter:blur(16px);border:1px solid rgba(212,175,55,0.15);border-radius:24px}
.input-mag{width:100%;padding:13px 16px;border-radius:14px;background:rgba(0,0,0,0.4);border:1px solid rgba(255,255,255,0.1);color:white;outline:none}
.input-mag:focus{border-color:var(--gold)}
.btn-gold{background:linear-gradient(135deg, var(--gold), #B8941F);color:#000;font-weight:600;padding:13px;border-radius:14px;width:100%}
</style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-10">
<div class="sky"></div>

<div class="relative z-10 w-full max-w-md">
  <div class="text-center mb-6">
    <a href="../index.php" class="text-4xl">🏰</a>
    <h1 class="font-mag text-2xl mt-2" style="color:var(--gold);">Letter of Admission</h1>
    <p class="text-sm opacity-60 mt-1">Every wizard's journey begins with an owl.</p>
  </div>

  <form method="POST" class="glass p-8 space-y-4">
    <?php if($error):?>
      <div class="text-sm px-4 py-3 rounded-xl" style="background:rgba(248,113,113,.15); border:1px solid #f87171; color:#fca5a5;"><?= htmlspecialchars($error)?></div>
    <?php endif;?>

    <div>
      <label class="text-sm block mb-1 opacity-80">Full Name</label>
      <input type="text" name="full_name" required class="input-mag" placeholder="Harry Potter" value="<?= htmlspecialchars($_POST['full_name']?? '')?>">
    </div>
    <div>
      <label class="text-sm block mb-1 opacity-80">Gmail</label>
      <input type="email" name="email" required class="input-mag" placeholder="you@gmail.com" value="<?= htmlspecialchars($_POST['email']?? '')?>">
    </div>
    <div>
      <label class="text-sm block mb-1 opacity-80">Password</label>
      <input type="password" name="password" required minlength="4" class="input-mag" placeholder="At least 4 characters">
    </div>
    <div>
      <label class="text-sm block mb-1 opacity-80">Confirm Password</label>
      <input type="password" name="confirm_password" required class="input-mag" placeholder="••••••••">
    </div>

    <button type="submit" class="btn-gold"> Accept My Admission</button>

    <p class="text-sm text-center opacity-70 pt-2">
      Already enrolled? <a href="login.php" class="underline" style="color:var(--gold-soft);">Log in here</a>
    </p>
  </form>
</div>
</body>
</html>