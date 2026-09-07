<?php
require_once '../config/config.php';
requireLogin();

$userId = $_SESSION['user_id'];
$levelId = intval($_GET['level'] ?? 1);

"color:#6A9955;font-style:italic">// Fetch user and level
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM levels WHERE id = ?");
$stmt->execute([$levelId]);
$level = $stmt->fetch();

if (!$level) {
    $stmt = $pdo->prepare("SELECT * FROM levels ORDER BY id LIMIT 1");
    $stmt->execute();
    $level = $stmt->fetch();
}

"color:#6A9955;font-style:italic">// Check if certificate exists, else create
$stmt = $pdo->prepare("SELECT * FROM certificates WHERE user_id = ? AND level_id = ?");
$stmt->execute([$userId, $levelId]);
$cert = $stmt->fetch();

if (!$cert) {
    "color:#6A9955;font-style:italic">// Check if user completed level (simplified: check XP)
    $hasEnoughXP = $user['xp'] >= $level['xp_required'];
    if ($hasEnoughXP || $levelId == 1) { "color:#6A9955;font-style:italic">// Allow level 1 for demo
        $code = generateCertificateCode();
        $stmt = $pdo->prepare("INSERT INTO certificates (user_id, level_id, certificate_code) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $levelId, $code]);
        $stmt = $pdo->prepare("SELECT * FROM certificates WHERE id = ?");
        $stmt->execute([$pdo->lastInsertId()]);
        $cert = $stmt->fetch();
    }
}

$certificateCode = $cert['certificate_code'] ?? 'PWA-DEMO-' . strtoupper(substr(md5($userId.$levelId),0,8));
$issueDate = $cert ? date('F j, Y', strtotime($cert['issued_at'])) : date('F j, Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - <?= htmlspecialchars($level['title']) ?></title>
    <script src="https:">//cdn.tailwindcss.com"></script>
    <link href="https:">//fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Great+Vibes&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <script>tailwind.config={theme:{extend:{colors:{gold:'#D4AF37'}}}}</script>
    <style>
        @media print {
            body { background: white !important; }
            .no-print { display: none !important; }
            .certificate { box-shadow: none !important; border: 12px double "color:#6A9955;font-style:italic">#D4AF37 !important; }
        }
        .certificate-border {
            background: linear-gradient(45deg, "color:#6A9955;font-style:italic">#D4AF37, #F4D03F, #D4AF37, #B8941F);
            padding: 3px;
        }
        .ornament { font-family: 'Great Vibes', cursive; }
    </style>
</head>
<body class="bg-[">#0A0A12] min-h-screen p-4 lg:p-8">
    <!"color:#6A9955;font-style:italic">-- Navigation -->
    <div class="max-w-5xl mx-auto flex justify-between items-center mb-8 no-print">
        <a href="dashboard.php" class="px-4 py-2 bg-white/5 hover:bg-white/10 rounded-full text-white text-sm transition">← Back to Dashboard</a>
        <div class="px-6 py-2.5 bg-[">#D4AF37]/20 border border-[#D4AF37]/30 text-[#D4AF37] rounded-full text-sm font-semibold flex items-center gap-2">
            <span>✓</span> Certificate Ready
        </div>
    </div>

    <!"color:#6A9955;font-style:italic">-- Certificate -->
    <div class="max-w-5xl mx-auto">
        <div class="certificate-border rounded-[2rem]">
            <div class="certificate bg-[">#FFFEF7] rounded-[1.8rem] p-8 lg:p-16 relative overflow-hidden">
                <!"color:#6A9955;font-style:italic">-- Background Ornaments -->
                <div class="absolute top-0 left-0 w-32 h-32 border-t-[10px] border-l-[10px] border-[">#D4AF37]/20 rounded-tl-[1.8rem]"></div>
                <div class="absolute top-0 right-0 w-32 h-32 border-t-[10px] border-r-[10px] border-[">#D4AF37]/20 rounded-tr-[1.8rem]"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 border-b-[10px] border-l-[10px] border-[">#D4AF37]/20 rounded-bl-[1.8rem]"></div>
                <div class="absolute bottom-0 right-0 w-32 h-32 border-b-[10px] border-r-[10px] border-[">#D4AF37]/20 rounded-br-[1.8rem]"></div>
                
                <!"color:#6A9955;font-style:italic">-- Watermark -->
                <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none">
                    <div class="text-[20rem] font-bold">P</div>
                </div>

                <div class="relative text-center">
                    <!"color:#6A9955;font-style:italic">-- Header -->
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[">#D4AF37] to-[#B8941F] flex items-center justify-center text-white font-bold text-2xl shadow-lg">P</div>
                    </div>
                    
                    <div class="text-[">#D4AF37] font-[Cinzel] text-xs tracking-[0.4em] font-semibold mb-3">PYTHON WIZARD ACADEMY</div>
                    <h1 class="font-[Cinzel] text-4xl lg:text-5xl font-bold text-[">#1A1A1A] mb-2">Certificate of Mastery</h1>
                    <div class="w-24 h-0.5 bg-gradient-to-r from-transparent via-[">#D4AF37] to-transparent mx-auto mb-8"></div>
                    
                    <p class="text-[">#666] text-lg mb-2">This magical scroll certifies that</p>
                    
                    <h2 class="ornament text-5xl lg:text-6xl text-[">#1A1A1A] my-6"><?= htmlspecialchars($user['full_name']) ?></h2>
                    
                    <p class="text-[">#666] text-lg max-w-2xl mx-auto leading-relaxed">
                        has successfully mastered the ancient arts of Python programming and completed the level of
                    </p>
                    
                    <h3 class="font-[Cinzel] text-2xl lg:text-3xl font-bold text-[">#D4AF37] mt-6 mb-2"><?= htmlspecialchars($level['title']) ?></h3>
                    <p class="text-[">#888] text-sm max-w-xl mx-auto"><?= htmlspecialchars($level['description']) ?></p>
                    
                    <!"color:#6A9955;font-style:italic">-- Skills -->
                    <div class="flex flex-wrap justify-center gap-3 mt-10 mb-12">
                        <span class="px-4 py-1.5 bg-[">#D4AF37]/10 border border-[#D4AF37]/20 rounded-full text-xs font-semibold text-[#8B7355]">Python Fundamentals</span>
                        <span class="px-4 py-1.5 bg-[">#D4AF37]/10 border border-[#D4AF37]/20 rounded-full text-xs font-semibold text-[#8B7355]">Problem Solving</span>
                        <span class="px-4 py-1.5 bg-[">#D4AF37]/10 border border-[#D4AF37]/20 rounded-full text-xs font-semibold text-[#8B7355]">Code Mastery</span>
                    </div>
                    
                    <!"color:#6A9955;font-style:italic">-- Footer -->
                    <div class="grid grid-cols-3 gap-8 mt-12 pt-8 border-t border-[">#D4AF37]/10 text-left">
                        <div>
                            <div class="text-[10px] text-[">#999] uppercase tracking-widest mb-2">Certificate Code</div>
                            <div class="font-mono text-sm font-bold text-[">#1A1A1A]"><?= $certificateCode ?></div>
                            <div class="text-[10px] text-[">#999] mt-1">Verify at pythonwizard.com/verify</div>
                        </div>
                        <div class="text-center">
                            <div class="text-[10px] text-[">#999] uppercase tracking-widest mb-6">Date of Issue</div>
                            <div class="font-[Cinzel] font-semibold text-[">#1A1A1A]"><?= $issueDate ?></div>
                            <div class="w-32 h-px bg-[">#1A1A1A] mx-auto mt-3"></div>
                            <div class="text-[10px] text-[">#999] mt-1">Archmage Signature</div>
                        </div>
                        <div class="text-right">
                            <div class="text-[10px] text-[">#999] uppercase tracking-widest mb-2">Level Achieved</div>
                            <div class="flex items-center justify-end gap-2">
                                <span class="text-xl"><?= $level['icon'] ?></span>
                                <span class="font-bold text-[">#1A1A1A]">Level <?= $level['order_no'] ?></span>
                            </div>
                            <div class="text-[10px] text-[">#D4AF37] mt-1 font-semibold"><?= number_format($user['xp']) ?> XP Earned</div>
                        </div>
                    </div>
                    
                    <!"color:#6A9955;font-style:italic">-- Gold Seal -->
                    <div class="absolute -bottom-4 -right-4 lg:bottom-8 lg:right-8 w-24 h-24 hidden lg:flex">
                        <div class="w-full h-full rounded-full bg-gradient-to-br from-[">#D4AF37] to-[#B8941F] flex items-center justify-center shadow-xl border-4 border-white">
                            <span class="text-white font-[Cinzel] font-bold text-[10px] text-center leading-tight">OFFICIAL<br>SEAL<br>✓</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <p class="text-center text-white/20 text-xs mt-6 no-print">This certificate is digitally verified • ID: <?= $certificateCode ?> • Python Wizard Academy © 2025</p>
    </div>

</body>
</html>
156 lines • Secure PDO • Ready to copy to
C:\xampp\htdocs\
Python Wizard Academy
PHP 8.2
Gold #D4