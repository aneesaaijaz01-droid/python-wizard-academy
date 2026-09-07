-- Python Wizard Academy - Complete Database Schema
-- XAMPP: Import via phpMyAdmin or mysql -u root -p python_wizard_db < database.sql

CREATE DATABASE IF NOT EXISTS python_wizard_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE python_wizard_db;

SET FOREIGN_KEY_CHECKS=0;

-- USERS
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS certificates;
DROP TABLE IF EXISTS student_badges;
DROP TABLE IF EXISTS badges;
DROP TABLE IF EXISTS xp_transactions;
DROP TABLE IF EXISTS project_submissions;
DROP TABLE IF EXISTS projects;
DROP TABLE IF EXISTS coding_submissions;
DROP TABLE IF EXISTS mcq_attempts;
DROP TABLE IF EXISTS progress;
DROP TABLE IF EXISTS coding_challenges;
DROP TABLE IF EXISTS mcq_options;
DROP TABLE IF EXISTS mcqs;
DROP TABLE IF EXISTS lessons;
DROP TABLE IF EXISTS topics;
DROP TABLE IF EXISTS levels;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    xp INT DEFAULT 0,
    level INT DEFAULT 1,
    streak INT DEFAULT 0,
    last_login DATETIME NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    bio TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    order_no INT NOT NULL,
    xp_required INT DEFAULT 0,
    icon VARCHAR(50) DEFAULT '🧙',
    color VARCHAR(20) DEFAULT '#D4AF37',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    slug VARCHAR(150) UNIQUE NOT NULL,
    description TEXT,
    order_no INT NOT NULL,
    is_locked_default BOOLEAN DEFAULT FALSE,
    xp_reward INT DEFAULT 50,
    icon VARCHAR(50) DEFAULT '📜',
    estimated_minutes INT DEFAULT 20,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (level_id) REFERENCES levels(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE lessons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    step_number INT NOT NULL COMMENT '1-7 steps',
    content_type ENUM('theory','example','practice','mcq','challenge','project','summary') NOT NULL,
    content TEXT NOT NULL,
    code_template TEXT NULL,
    solution TEXT NULL,
    hints TEXT NULL,
    xp_reward INT DEFAULT 20,
    order_no INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE mcqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lesson_id INT NOT NULL,
    question TEXT NOT NULL,
    explanation TEXT,
    xp_reward INT DEFAULT 10,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE mcq_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mcq_id INT NOT NULL,
    option_text VARCHAR(500) NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (mcq_id) REFERENCES mcqs(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE coding_challenges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lesson_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    starter_code TEXT,
    solution_code TEXT,
    test_cases JSON,
    difficulty ENUM('easy','medium','hard') DEFAULT 'easy',
    xp_reward INT DEFAULT 100,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    topic_id INT NOT NULL,
    lesson_id INT NULL,
    status ENUM('not_started','in_progress','completed') DEFAULT 'not_started',
    completed_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_lesson (user_id, lesson_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE mcq_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    mcq_id INT NOT NULL,
    selected_option_id INT NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (mcq_id) REFERENCES mcqs(id) ON DELETE CASCADE,
    FOREIGN KEY (selected_option_id) REFERENCES mcq_options(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE coding_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    challenge_id INT NOT NULL,
    code TEXT NOT NULL,
    status ENUM('pending','passed','failed') DEFAULT 'pending',
    output TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (challenge_id) REFERENCES coding_challenges(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    requirements TEXT,
    xp_reward INT DEFAULT 250,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (level_id) REFERENCES levels(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE project_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    project_id INT NOT NULL,
    github_link VARCHAR(500),
    description TEXT,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    feedback TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE xp_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount INT NOT NULL,
    source VARCHAR(100) NOT NULL,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255),
    icon VARCHAR(100) DEFAULT '🏅',
    criteria VARCHAR(255),
    xp_reward INT DEFAULT 50,
    rarity ENUM('common','rare','epic','legendary') DEFAULT 'common'
) ENGINE=InnoDB;

CREATE TABLE student_badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    badge_id INT NOT NULL,
    earned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_badge (user_id, badge_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (badge_id) REFERENCES badges(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    level_id INT NOT NULL,
    certificate_code VARCHAR(50) UNIQUE NOT NULL,
    issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (level_id) REFERENCES levels(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS=1;

-- SAMPLE DATA
INSERT INTO levels (id, title, description, order_no, xp_required, icon, color) VALUES
(1, 'Beginner Sorcerer', 'Awaken your inner Python magic. Foundations, syntax, and first spells.', 1, 0, '🌱', '#10B981'),
(2, 'Intermediate Enchanter', 'Master data structures, OOP, and craft powerful enchantments.', 2, 1500, '🔮', '#8B5CF6'),
(3, 'Master Wizard', 'Archmage level - APIs, async, deployment, and legendary projects.', 3, 5000, '🧙‍♂️', '#D4AF37');

INSERT INTO topics (level_id, title, slug, description, order_no, xp_reward, icon, estimated_minutes) VALUES
(1, 'Introduction to Python', 'intro-python', 'History, installation, and first program', 1, 50, '👋', 15),
(1, 'Variables & Data Types', 'variables', 'Understanding variables, numbers, and types', 2, 50, '📦', 20),
(1, 'Input & Output', 'input-output', 'Taking input and displaying output magically', 3, 50, '⌨️', 18),
(1, 'Operators', 'operators', 'Arithmetic, comparison, logical operators', 4, 60, '➗', 25),
(1, 'Strings Deep Dive', 'strings', 'String manipulation, slicing, formatting', 5, 70, '🔤', 30),
(1, 'Lists - Your Spellbook', 'lists', 'Lists, indexing, methods', 6, 80, '📜', 35),
(1, 'Tuples', 'tuples', 'Immutable collections', 7, 50, '🔗', 20),
(1, 'Dictionaries', 'dictionaries', 'Key-value magic', 8, 80, '🗝️', 30),
(1, 'Sets', 'sets', 'Unique collections and operations', 9, 60, '🔮', 22),
(1, 'Conditional Spells (If-Else)', 'if-else', 'Decision making in Python', 10, 70, '🔀', 28),
(1, 'Loops - Time Magic', 'loops', 'For and while loops', 11, 90, '🔄', 35),
(1, 'Functions', 'functions', 'Creating reusable spells', 12, 100, '⚙️', 40),
(1, 'Lambda & Higher Order', 'lambda', 'Anonymous functions and magic', 13, 80, '✨', 25),
(1, 'Modules & Imports', 'modules', 'Importing magical libraries', 14, 60, '📚', 20),
(1, 'File Handling', 'file-handling', 'Reading and writing scrolls', 15, 90, '📁', 30),
(1, 'Exception Handling', 'exceptions', 'Handling errors like a wizard', 16, 80, '🛡️', 28),
(1, 'OOP Basics', 'oop-basics', 'Classes and objects introduction', 17, 120, '🏛️', 45),
(1, 'Inheritance', 'inheritance', 'Extending your magical lineage', 18, 100, '🌳', 35),
(1, 'Iterators & Generators', 'iterators', 'Lazy evaluation magic', 19, 90, '🌀', 30),
(1, 'Decorators', 'decorators', 'Wrapping spells with power', 20, 110, '🎀', 35),
(1, 'Final Project: Wizard Game', 'final-project', 'Build your first Python game', 21, 300, '🎮', 90),
(1, 'Bonus: Python Tips & Tricks', 'tips-tricks', 'Pro wizard secrets', 22, 50, '💡', 15);

INSERT INTO badges (name, description, icon, criteria, xp_reward, rarity) VALUES
('First Spell', 'Completed your first lesson', '🌟', 'Complete 1 lesson', 25, 'common'),
('Code Novice', 'Wrote 10 lines of code', '📝', 'Submit 1 challenge', 50, 'common'),
('Streak Starter', '3-day learning streak', '🔥', '3 day streak', 75, 'rare'),
('Loop Master', 'Mastered loops chapter', '🔄', 'Complete loops topic', 100, 'rare'),
('Function Wizard', 'Created 5 functions', '⚙️', 'Complete functions', 100, 'rare'),
('Bug Hunter', 'Fixed 10 errors', '🐛', '10 submissions', 150, 'epic'),
('Python Sorcerer', 'Completed Level 1', '🧙', 'Complete level 1', 500, 'legendary');

-- Demo Users (password: wizard123)
-- Hash generated via: password_hash('wizard123', PASSWORD_DEFAULT)
INSERT INTO users (username, email, password, full_name, xp, level, streak) VALUES
('demo_student', 'student@wizard.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Aneesa Demo', 1240, 1, 7),
('wizard_apprentice', 'aneesa@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Aneesa Khan', 350, 1, 2);

INSERT INTO admins (username, email, password, full_name) VALUES
('admin', 'admin@wizard.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Archmage Admin'),
('aneesa_admin', 'aneesa@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Aneesa Admin');

-- Sample Lesson for Variables topic
INSERT INTO lessons (topic_id, title, step_number, content_type, content, code_template, solution, hints, xp_reward) VALUES
(2, 'What are Variables?', 1, 'theory', '<h2>Variables are like magical containers</h2><p>In Python, variables store data. Think of them as labeled jars where you keep your spell ingredients.</p><pre>wizard_name = "Gandalf"
wizard_age = 2019</pre>', NULL, NULL, 'Variables are case-sensitive', 20),
(2, 'Try it Yourself', 2, 'example', '<p>Let''s create variables and print them.</p>', 'name = ""
age = 0
print(name, age)', 'name = "Aneesa"
age = 21
print(name, age)', 'Use quotes for strings', 30);

INSERT INTO mcqs (lesson_id, question, explanation, xp_reward) VALUES
(1, 'Which keyword is used to define a variable in Python?', 'In Python, you directly assign value. No keyword like var or let needed.', 10);

INSERT INTO mcq_options (mcq_id, option_text, is_correct) VALUES
(1, 'var', FALSE),
(1, 'let', FALSE),
(1, 'No keyword needed, just assign', TRUE),
(1, 'define', FALSE);

INSERT INTO coding_challenges (lesson_id, title, description, starter_code, solution_code, test_cases, difficulty, xp_reward) VALUES
(2, 'Variable Swap Spell', 'Swap two variables without temp variable', 'a = 5
b = 10
# Swap here
print(a, b)', 'a = 5
b = 10
a, b = b, a
print(a, b)', '[{"input": "", "expected": "10 5"}]', 'easy', 50);
319 lines • Secure PDO • Ready to copy to
C:\xampp\htdocs\
Python Wizard Academy
PHP 8.2
Gold #D4