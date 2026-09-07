<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Python Wizard Academy</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Inter:wght@400;600&display=swap');
body{margin:0;background:#0F0F1A;color:white;font-family:'Inter',sans-serif}
h1{font-family:'Cinzel',serif}
.btn-gold{background:#D4AF37;color:black;padding:12px 28px;border-radius:999px;font-weight:bold;text-decoration:none;display:inline-block}
.btn-outline{border:1px solid rgba(255,255,255,0.2);color:white;padding:12px 28px;border-radius:999px;text-decoration:none;display:inline-block;margin-left:12px}
.card{background:#1A1A2E;border:1px solid rgba(212,175,55,0.2);border-radius:24px;padding:24px}
nav{display:flex;justify-content:space-between;padding:20px 40px;border-bottom:1px solid rgba(212,175,55,0.1);background:rgba(15,15,26,0.8);position:fixed;width:100%;top:0;box-sizing:border-box;backdrop-filter:blur(10px)}
</style>
</head>
<body>
<nav>
<div style="font-weight:bold;letter-spacing:1px">🧙 PYTHON WIZARD ACADEMY</div>
<div>
<a href="auth/login.php" style="color:white;margin-right:16px;text-decoration:none">Login</a>
<a href="auth/register.php" class="btn-gold">Join Academy</a>
</div>
</nav>

<div style="max-width:1200px;margin:0 auto;padding:140px 40px 60px;display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center">
<div>
<div style="background:rgba(212,175,55,0.1);border:1px solid rgba(212,175,55,0.2);color:#D4AF37;padding:6px 16px;border-radius:999px;font-size:12px;display:inline-block;margin-bottom:20px">✨ New Batch Starting</div>
<h1 style="font-size:56px;line-height:1.1;margin:0">Master Python<br><span style="color:#D4AF37">Unlock Your Potential</span></h1>
<p style="color:#999;margin-top:20px;font-size:18px;line-height:1.6">Join the most enchanting Python journey. From zero to wizard-level developer with interactive lessons.</p>
<div style="margin-top:32px">
<a href="auth/register.php" class="btn-gold">Start Your Quest →</a>
<a href="auth/login.php" class="btn-outline">Login</a>
</div>
<div style="display:flex;gap:32px;margin-top:40px">
<div><div style="font-size:24px;font-weight:bold">2.5k+</div><div style="font-size:12px;color:#666">Wizards Trained</div></div>
<div><div style="font-size:24px;font-weight:bold">4.9/5</div><div style="font-size:12px;color:#666">Rating</div></div>
<div><div style="font-size:24px;font-weight:bold">120+</div><div style="font-size:12px;color:#666">Lessons</div></div>
</div>
</div>

<div class="card">
<div style="display:flex;justify-content:space-between;font-size:12px;color:#666;margin-bottom:16px"><span>spell_cast.py</span><span>● Running</span></div>
<pre style="font-size:14px;line-height:1.7;margin:0;white-space:pre-wrap"><span style="color:#6A9955"># Cast your first Python spell ✨</span>
<span style="color:#569CD6">def</span> <span style="color:#DCDCAA">summon_wizard</span>(name):
    <span style="color:#569CD6">return</span> <span style="color:#CE9178">f"Welcome, {name}! Your journey begins..."</span>

wizard = summon_wizard(<span style="color:#CE9178">"Aneesa"</span>)
<span style="color:#569CD6">print</span>(wizard)
<span style="color:#6A9955"># ▶ Welcome, Aneesa! Your journey begins... 🧙</span></pre>
<button style="margin-top:24px;width:100%;padding:12px;background:#D4AF37;color:black;border:none;border-radius:12px;font-weight:bold;cursor:pointer">▶ Run Spell</button>
</div>
</div>

<div style="text-align:center;padding:40px;color:#555;font-size:14px">© 2026 Python Wizard Academy • Final Year Project</div>

</body>
</html>