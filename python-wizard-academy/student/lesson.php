<?php
require_once '../config/config.php';
requireLogin();

$lessonId = intval($_GET['id'] ?? $_GET['lesson'] ?? 1);
$userId = $_SESSION['user_id'];

"color:#6A9955;font-style:italic">// Fetch lesson
$stmt = $pdo->prepare("
    SELECT l.*, t.title as topic_title, t.id as topic_id, lv.title as level_title 
    FROM lessons l 
    JOIN topics t ON l.topic_id = t.id 
    JOIN levels lv ON t.level_id = lv.id 
    WHERE l.id = ?
");
$stmt->execute([$lessonId]);
$lesson = $stmt->fetch();

if (!$lesson) {
    "color:#6A9955;font-style:italic">// Fallback: get first lesson
    $stmt = $pdo->prepare("SELECT l.*, t.title as topic_title, t.id as topic_id FROM lessons l JOIN topics t ON l.topic_id = t.id ORDER BY l.id LIMIT 1");
    $stmt->execute();
    $lesson = $stmt->fetch();
    if (!$lesson) die("No lessons found. Please import database.sql");
}

"color:#6A9955;font-style:italic">// Fetch all lessons in topic for stepper
$stmt = $pdo->prepare("SELECT * FROM lessons WHERE topic_id = ? ORDER BY step_number, order_no");
$stmt->execute([$lesson['topic_id']]);
$allLessons = $stmt->fetchAll();

"color:#6A9955;font-style:italic">// Fetch MCQ if exists
$stmt = $pdo->prepare("SELECT * FROM mcqs WHERE lesson_id = ? LIMIT 1");
$stmt->execute([$lessonId]);
$mcq = $stmt->fetch();
$mcqOptions = [];
if ($mcq) {
    $stmt = $pdo->prepare("SELECT * FROM mcq_options WHERE mcq_id = ?");
    $stmt->execute([$mcq['id']]);
    $mcqOptions = $stmt->fetchAll();
}

"color:#6A9955;font-style:italic">// Fetch challenge if exists
$stmt = $pdo->prepare("SELECT * FROM coding_challenges WHERE lesson_id = ? LIMIT 1");
$stmt->execute([$lessonId]);
$challenge = $stmt->fetch();

"color:#6A9955;font-style:italic">// Handle MCQ submission
$mcqResult = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mcq_option'])) {
    $selected = intval($_POST['mcq_option']);
    $stmt = $pdo->prepare("SELECT is_correct FROM mcq_options WHERE id = ? AND mcq_id = ?");
    $stmt->execute([$selected, $mcq['id']]);
    $isCorrect = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("INSERT INTO mcq_attempts (user_id, mcq_id, selected_option_id, is_correct) VALUES (?, ?, ?, ?)");
    $stmt->execute([$userId, $mcq['id'], $selected, $isCorrect ? 1 : 0]);
    
    $mcqResult = $isCorrect;
    if ($isCorrect) {
        addXP($userId, $mcq['xp_reward'], 'mcq', 'Solved MCQ: ' . $lesson['title']);
    }
}

"color:#6A9955;font-style:italic">// Handle code submission
$codeResult = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code_submit'])) {
    $code = $_POST['code'] ?? '';
    "color:#6A9955;font-style:italic">// Simulate evaluation (in real app, use sandbox)
    $status = (strlen($code) > 20) ? 'passed' : 'failed'; "color:#6A9955;font-style:italic">// simple check
    $stmt = $pdo->prepare("INSERT INTO coding_submissions (user_id, challenge_id, code, status, output) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $challenge['id'], $code, $status, 'Output simulated - Great job!']);
    $codeResult = $status;
    if ($status === 'passed') {
        addXP($userId, $challenge['xp_reward'], 'challenge', 'Solved: ' . $challenge['title']);
    }
}

"color:#6A9955;font-style:italic">// Mark progress
$stmt = $pdo->prepare("INSERT INTO progress (user_id, topic_id, lesson_id, status, completed_at) VALUES (?, ?, ?, 'in_progress', NULL) ON DUPLICATE KEY UPDATE status = VALUES(status)");
$stmt->execute([$userId, $lesson['topic_id'], $lessonId]);

$currentIndex = array_search($lessonId, array_column($allLessons, 'id'));
$prevLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
$nextLesson = $currentIndex < count($allLessons)-1 ? $allLessons[$currentIndex + 1] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($lesson['title']) ?> - Python Wizard</title>
    <script src="https:">//cdn.tailwindcss.com"></script>
    <link href="https:">//fonts.googleapis.com/css2?family=Cinzel:wght@600&family=Inter:wght@400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script>tailwind.config={theme:{extend:{colors:{gold:'#D4AF37', 'wizard-dark':'#0F0F1A', 'wizard-card':'#1A1A2E'}}}}</script>
</head>
<body class="bg-[">#0A0A12] text-white min-h-screen">
    <!"color:#6A9955;font-style:italic">-- Top Bar -->
    <header class="sticky top-0 z-20 bg-wizard-card/80 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="course.php" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center hover:bg-white/10 transition">←</a>
                <div>
                    <div class="text-[11px] text-white/40 uppercase tracking-widest"><?= htmlspecialchars($lesson['level_title'] ?? 'Beginner') ?> / <?= htmlspecialchars($lesson['topic_title']) ?></div>
                    <div class="font-semibold text-sm"><?= htmlspecialchars($lesson['title']) ?> • Step <?= $lesson['step_number'] ?>/7</div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <?php if($prevLesson): ?><a href="lesson.php?id=<?= $prevLesson['id'] ?>" class="px-4 py-2 bg-white/5 rounded-full text-sm hover:bg-white/10">← Prev</a><?php endif; ?>
                <?php if($nextLesson): ?><a href="lesson.php?id=<?= $nextLesson['id'] ?>" class="px-4 py-2 bg-gold text-wizard-dark rounded-full text-sm font-bold hover:opacity-90">Next →</a><?php endif; ?>
            </div>
        </div>
        <!"color:#6A9955;font-style:italic">-- Progress Steps -->
        <div class="max-w-7xl mx-auto px-6 pb-3">
            <div class="flex items-center gap-1.5">
                <?php foreach($allLessons as $idx => $l): ?>
                    <div class="flex-1 h-1 rounded-full <?= $l['id'] == $lessonId ? 'bg-gold' : ($idx < $currentIndex ? 'bg-green-500' : 'bg-white/10') ?>"></div>
                <?php endforeach; ?>
            </div>
            <div class="flex justify-between mt-2 text-[10px] text-white/30 uppercase tracking-wider">
                <span>Theory</span><span>Example</span><span>Practice</span><span>Quiz</span><span>Challenge</span><span>Project</span><span>Summary</span>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-6 grid lg:grid-cols-5 gap-6">
        <!"color:#6A9955;font-style:italic">-- Content -->
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-wizard-card border border-white/5 rounded-[1.5rem] p-8">
                <div class="inline-flex px-3 py-1 rounded-full bg-gold/10 border border-gold/20 text-gold text-xs font-semibold mb-4"><?= strtoupper($lesson['content_type']) ?> • +<?= $lesson['xp_reward'] ?> XP</div>
                <h1 class="font-[Cinzel] text-2xl font-bold mb-6"><?= htmlspecialchars($lesson['title']) ?></h1>
                <div class="prose prose-invert max-w-none text-white/80 leading-relaxed">
                    <?= $lesson['content'] ?>
                </div>
            </div>

            <!"color:#6A9955;font-style:italic">-- MCQ Section -->
            <?php if($mcq): ?>
            <div class="bg-wizard-card border border-white/5 rounded-[1.5rem] p-8">
                <h3 class="font-bold text-lg mb-4">🧠 Quick Check: <?= htmlspecialchars($mcq['question']) ?></h3>
                <?php if($mcqResult !== null): ?>
                    <div class="p-4 rounded-xl mb-4 <?= $mcqResult ? 'bg-green-500/10 border border-green-500/20 text-green-400' : 'bg-red-500/10 border border-red-500/20 text-red-400' ?>">
                        <?= $mcqResult ? '✅ Correct! +' . $mcq['xp_reward'] . ' XP earned!' : '❌ Not quite. Try again! Hint: ' . htmlspecialchars($mcq['explanation']) ?>
                    </div>
                <?php endif; ?>
                <form method="POST" class="space-y-3">
                    <?php foreach($mcqOptions as $opt): ?>
                        <label class="flex items-center gap-3 p-4 bg-[">#0F0F1A] border border-white/5 rounded-xl hover:border-gold/30 cursor-pointer transition">
                            <input type="radio" name="mcq_option" value="<?= $opt['id'] ?>" required class="accent-[">#D4AF37]">
                            <span class="text-sm"><?= htmlspecialchars($opt['option_text']) ?></span>
                        </label>
                    <?php endforeach; ?>
                    <button type="submit" class="mt-4 px-6 py-3 bg-gold text-wizard-dark font-bold rounded-full hover:opacity-90 transition">Submit Answer</button>
                </form>
            </div>
            <?php endif; ?>

            <!"color:#6A9955;font-style:italic">-- Coding Challenge -->
            <?php if($challenge): ?>
            <div class="bg-wizard-card border border-white/5 rounded-[1.5rem] p-8">
                <h3 class="font-bold text-lg mb-2">⚔️ Challenge: <?= htmlspecialchars($challenge['title']) ?></h3>
                <p class="text-white/50 text-sm mb-6"><?= htmlspecialchars($challenge['description']) ?></p>
                
                <?php if($codeResult): ?>
                    <div class="p-4 rounded-xl mb-4 <?= $codeResult === 'passed' ? 'bg-green-500/10 border border-green-500/20 text-green-400' : 'bg-yellow-500/10 border border-yellow-500/20 text-yellow-400' ?>">
                        <?= $codeResult === 'passed' ? '🎉 Challenge Passed! +' . $challenge['xp_reward'] . ' XP' : '⚠️ Keep trying! Your code needs some magic.' ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="bg-[">#0A0A12] border border-white/5 rounded-xl overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-2 bg-white/5 border-b border-white/5">
                            <span class="text-xs text-white/40 font-mono">main.py</span>
                            <span class="text-xs text-gold">Python 3.11 • <?= htmlspecialchars($challenge['difficulty']) ?></span>
                        </div>
                        <textarea name="code" rows="12" class="w-full bg-transparent p-4 font-mono text-sm text-white/80 focus:outline-none resize-none" placeholder="<?= htmlspecialchars($challenge['starter_code']) ?>"><?= htmlspecialchars($_POST['code'] ?? $challenge['starter_code']) ?></textarea>
                    </div>
                    <div class="flex gap-3 mt-4">
                        <button type="button" onclick="alert('Simulated Run: Output would appear here. In production, connect to Python execution API.')" class="px-6 py-3 bg-white/5 hover:bg-white/10 rounded-full text-sm font-medium transition">▶ Run Code</button>
                        <button type="submit" name="code_submit" value="1" class="px-6 py-3 bg-gold text-wizard-dark font-bold rounded-full hover:opacity-90 transition">Submit Challenge</button>
                    </div>
                </form>

                <?php if(!empty($challenge['hints'])): ?>
                    <details class="mt-6 bg-[">#0F0F1A] rounded-xl p-4 border border-white/5">
                        <summary class="cursor-pointer text-sm font-medium text-white/70">💡 Show Hints</summary>
                        <p class="text-white/50 text-sm mt-3"><?= htmlspecialchars($challenge['hints'] ?? $challenge['solution_code']) ?></p>
                    </details>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <!"color:#6A9955;font-style:italic">-- Right Sidebar -->
        <div class="lg:col-span-2 space-y-6">
            <!"color:#6A9955;font-style:italic">-- Code Editor Simulation -->
            <div class="bg-[">#0A0A12] border border-white/10 rounded-[1.5rem] overflow-hidden">
                <div class="flex items-center gap-2 px-5 py-3 border-b border-white/5 bg-wizard-card">
                    <div class="flex gap-1.5"><div class="w-3 h-3 rounded-full bg-red-500"></div><div class="w-3 h-3 rounded-full bg-yellow-500"></div><div class="w-3 h-3 rounded-full bg-green-500"></div></div>
                    <span class="text-white/30 text-xs font-mono ml-2">wizard_lab.py</span>
                    <span class="ml-auto text-[10px] px-2 py-1 bg-green-500/20 text-green-400 rounded-full">● Live</span>
                </div>
                <div class="p-5 font-mono text-sm leading-6">
                    <div class="text-white/20">"color:#6A9955;font-style:italic"># Try your spells here ✨</div>
                    <div><span class="text-purple-400">print</span>(<span class="text-green-300">"Hello, Wizard!"</span>)</div>
                    <div class="mt-4 pt-4 border-t border-white/5 text-white/30 text-xs">Output:</div>
                    <div class="text-green-400 text-sm mt-1">Hello, Wizard! 🧙‍♀️</div>
                </div>
                <div class="p-4 bg-wizard-card border-t border-white/5 flex gap-2">
                    <button onclick="this.nextElementSibling.style.display='block'" class="flex-1 py-2.5 bg-white/5 hover:bg-white/10 rounded-full text-sm transition">▶ Run</button>
                    <button class="flex-1 py-2.5 bg-gold text-wizard-dark font-bold rounded-full text-sm hover:opacity-90">Save Spell</button>
                    <div class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" onclick="this.style.display='none'">
                        <div class="bg-wizard-card border border-white/10 rounded-2xl p-6 max-w-sm w-full"><p class="text-sm">✨ Code executed successfully!<br><span class="text-white/50 text-xs">In production, this would call a Python sandbox API.</span></p></div>
                    </div>
                </div>
            </div>

            <!"color:#6A9955;font-style:italic">-- Lesson Navigation -->
            <div class="bg-wizard-card border border-white/5 rounded-2xl p-6">
                <h3 class="font-semibold mb-4">📜 Topic Lessons</h3>
                <div class="space-y-2">
                    <?php foreach($allLessons as $l): ?>
                        <a href="lesson.php?id=<?= $l['id'] ?>" class="flex items-center gap-3 p-3 rounded-xl <?= $l['id']==$lessonId ? 'bg-gold text-wizard-dark font-medium' : 'bg-[">#0F0F1A] hover:bg-white/5 text-white/70' ?> transition">
                            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs <?= $l['id']==$lessonId ? 'bg-wizard-dark text-gold' : 'bg-white/10' ?>"><?= $l['step_number'] ?></span>
                            <span class="text-sm truncate"><?= htmlspecialchars($l['title']) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
234 lines • Secure PDO • Ready to copy to
C:\xampp\htdocs\
Python Wizard Academy
PHP 8.2
Gold #D4