<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require 'db.php';

// ── Notification bell logic ──────────────────────
$latestNotice = $pdo->query("SELECT MAX(id) as max_id FROM notices")->fetch(PDO::FETCH_ASSOC);
$latestId     = (int)($latestNotice['max_id'] ?? 0);

if (!isset($_SESSION['last_seen_notice'])) {
    $_SESSION['last_seen_notice'] = $latestId;
}

if (isset($_GET['seen_notices'])) {
    $_SESSION['last_seen_notice'] = $latestId;
    echo json_encode(['ok' => true]);
    exit();
}

$unseenCount = 0;
if ($latestId > $_SESSION['last_seen_notice']) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notices WHERE id > ?");
    $stmt->execute([$_SESSION['last_seen_notice']]);
    $unseenCount = (int)$stmt->fetchColumn();
}

// ── Fetch syllabus ───────────────────────────────
$syllabusAll = $pdo->query("SELECT * FROM syllabus ORDER BY semester, id")->fetchAll(PDO::FETCH_ASSOC);
$syllabusBySem = [];
foreach ($syllabusAll as $row) {
    $syllabusBySem[$row['semester']][] = $row;
}

// ── Fetch notes ──────────────────────────────────
$notesAll = $pdo->query("SELECT * FROM notes ORDER BY semester, id")->fetchAll(PDO::FETCH_ASSOC);
$notesBySem = [];
foreach ($notesAll as $row) {
    $notesBySem[$row['semester']][] = $row;
}

// ── Fetch past questions ─────────────────────────
$pqAll = $pdo->query("SELECT * FROM past_questions ORDER BY semester, id")->fetchAll(PDO::FETCH_ASSOC);
$pqBySem = [];
foreach ($pqAll as $row) {
    $pqBySem[$row['semester']][] = $row;
}

// ── Fetch mock test questions ─────────────────────
$mockQuestions = [];
try {
    $mqAll = $pdo->query("SELECT * FROM mock_questions ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($mqAll as $row) {
        $opts = ['A' => $row['opt_a'], 'B' => $row['opt_b'], 'C' => $row['opt_c'], 'D' => $row['opt_d']];
        $answers = [];
        foreach ($opts as $key => $text) {
            $answers[] = ['text' => $text, 'correct' => ($key === $row['correct_opt'])];
        }
        $mockQuestions[] = ['question' => $row['question'], 'answers' => $answers];
    }
} catch (Exception $e) { $mockQuestions = []; }

// ── Fetch notices ────────────────────────────────
$notices = $pdo->query("SELECT * FROM notices ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIT Portal</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .nav-notice-wrap {
            position: relative;
            display: inline-block;
        }
        .notif-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #e74c3c;
            color: #fff;
            font-size: .62rem;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            pointer-events: none;
            animation: popIn .3s ease both;
            box-shadow: 0 0 8px rgba(231,76,60,0.6);
        }
        @keyframes popIn {
            from { transform: scale(0); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }
        .notif-badge.hidden { display: none; }
        .new-label {
            display: inline-block;
            background: #e74c3c;
            color: #fff;
            font-size: .65rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
            margin-left: 10px;
            vertical-align: middle;
            animation: popIn .3s ease both;
        }
    </style>
</head>
<body>
    <div class="app">

        <!-- USER BAR -->
        <div class="user-bar">
            <span class="welcome-text">Hi, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
            <button class="profile-link" id="nav-profile" onclick="showSection('profile')">👤 Profile</button>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="admin.php" class="admin-link">⚙ Admin</a>
            <?php endif; ?>
            <a href="logout.php" class="logout-link">Logout</a>
        </div>

        <!-- NAV -->
        <div class="nav-sticky-wrap">
        <nav class="main-nav">
            <button class="nav-btn active" id="nav-syllabus" onclick="showSection('syllabus')">Syllabus</button>
            <button class="nav-btn" id="nav-notes" onclick="showSection('notes')">Notes</button>
            <button class="nav-btn" id="nav-quiz" onclick="showSection('quiz')">Mock Test</button>
            <div class="nav-notice-wrap">
                <button class="nav-btn" id="nav-notice" onclick="showSection('notice')">Notice</button>
                <span class="notif-badge <?= $unseenCount === 0 ? 'hidden' : '' ?>" id="notif-badge">
                    <?= $unseenCount > 9 ? '9+' : $unseenCount ?>
                </span>
            </div>
            <button class="nav-btn" id="nav-pqs" onclick="showSection('pqs')">PQ Solution</button>
            <button class="nav-btn" id="nav-about" onclick="showSection('about')">About</button>
        </nav>
        </div><!-- /.nav-sticky-wrap -->

        <!-- SYLLABUS SECTION -->
        <section id="section-syllabus">
            <h1>BIT Semester Syllabus</h1>
            <div class="container">
                <input type="text" id="searchBar" onkeyup="searchSubject()" placeholder="Search subjects (e.g. Java programming)...">
                <div class="sem-tabs">
                    <?php for($i=1;$i<=8;$i++): ?>
                    <button onclick="loadSyllabus(<?= $i ?>)">Sem <?= $i ?></button>
                    <?php endfor; ?>
                </div>
                <div class="display-card">
                    <h2 id="sem-title">Select a Semester</h2>
                    <div id="subject-list"></div>
                    <footer class="home-footer">
                        <p>© 2026 BIT Portal | Developed by Keshav Verma</p>
                    </footer>
                </div>
            </div>
        </section>

        <!-- NOTES SECTION -->
        <section id="section-notes" style="display:none;">
            <h1>BIT Study Notes</h1>
            <div class="container">
                <div class="sem-tabs">
                    <?php for($i=1;$i<=8;$i++): ?>
                    <button onclick="loadNotes(<?= $i ?>)">Sem <?= $i ?></button>
                    <?php endfor; ?>
                </div>
                <div class="display-card">
                    <h2 id="notes-title">Select a Semester for Notes</h2>
                    <div id="notes-list">
                        <p style="color:var(--text-muted);text-align:center;">Choose a semester to view available PDFs.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- QUIZ SECTION -->
        <section id="section-quiz" style="display:none;">
            <h1>BIT Entrance Preparation</h1>
            <div class="quiz">
                <div class="progress-wrapper">
                    <div class="progress-bar-bg">
                        <div id="progress-fill"></div>
                    </div>
                    <p id="question-stats">Question 1 of 20</p>
                </div>
                <h2 id="question">Loading Question...</h2>
                <div id="answer-btns"></div>
                <button id="next-btn">Next Question</button>
            </div>
        </section>

        <!-- NOTICE SECTION -->
        <section id="section-notice" style="display:none;">
            <h1>Official Notices</h1>
            <div class="container">
                <?php if (empty($notices)): ?>
                <div class="display-card notice-card">
                    <p style="color:var(--text-muted);text-align:center;padding:20px;">No notices posted yet.</p>
                </div>
                <?php else: ?>
                    <?php $lastSeen = $_SESSION['last_seen_notice']; ?>
                    <?php foreach ($notices as $notice): ?>
                    <?php $ext   = strtolower(pathinfo($notice['filename'], PATHINFO_EXTENSION)); ?>
                    <?php $isNew = (int)$notice['id'] > $lastSeen; ?>
                    <div class="display-card notice-card" style="margin-bottom:20px;">
                        <div class="notice-badge"><?= htmlspecialchars($notice['badge']) ?></div>
                        <?php if ($isNew): ?>
                            <span class="new-label">🔔 NEW</span>
                        <?php endif; ?>
                        <h2 class="notice-heading" style="margin-top:10px;"><?= htmlspecialchars($notice['title']) ?></h2>
                        <?php if ($ext === 'pdf'): ?>
                            <div style="margin-top:14px;">
                                <a href="uploads/notices/<?= urlencode($notice['filename']) ?>" target="_blank"
                                   style="color:var(--accent);font-weight:600;font-size:.88rem;">📄 View PDF Notice</a>
                            </div>
                        <?php else: ?>
                            <div class="notice-img-wrap" style="margin-top:14px;">
                                <img src="uploads/notices/<?= urlencode($notice['filename']) ?>"
                                     alt="<?= htmlspecialchars($notice['title']) ?>" class="notice-img"/>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- PQ SOLUTION SECTION -->
        <section id="section-pqs" style="display:none;">
            <h1>Past Question Solutions</h1>
            <div class="container">
                <div class="sem-tabs">
                    <?php for($i=1;$i<=8;$i++): ?>
                    <button onclick="loadPQS(<?= $i ?>)">Sem <?= $i ?></button>
                    <?php endfor; ?>
                </div>
                <div class="display-card">
                    <h2 id="pqs-title">Select a Semester</h2>
                    <div id="pqs-list">
                        <p style="color:var(--text-muted);text-align:center;">Choose a semester to view past question solutions.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- PROFILE SECTION -->
        <section id="section-profile" style="display:none;">
            <h1>My Profile</h1>
            <div class="container">
                <div class="display-card profile-card">
                    <div class="profile-avatar">
                        <?= strtoupper(substr($_SESSION['username'], 0, 1)) ?>
                    </div>
                    <div class="profile-meta">
                        <h2><?= htmlspecialchars($_SESSION['username']) ?></h2>
                        <p><?php
                            $urow = $pdo->prepare("SELECT email, created_at FROM users WHERE id=?");
                            $urow->execute([$_SESSION['user_id']]);
                            $udata = $urow->fetch(PDO::FETCH_ASSOC);
                            echo htmlspecialchars($udata['email']);
                        ?></p>
                        <span class="profile-badge <?= $_SESSION['role'] ?>"><?= $_SESSION['role'] ?></span>
                        <p style="font-size:.74rem;color:var(--text-muted);margin-top:6px;">
                            Member since <?= date('d M Y', strtotime($udata['created_at'])) ?>
                        </p>
                    </div>

                    <?php
                    $profileMsg = ''; $profileErr = '';
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
                        $newUsername = trim($_POST['new_username']);
                        $curPass     = $_POST['current_password'];
                        $newPass     = $_POST['new_password'];
                        $confPass    = $_POST['confirm_password'];

                        $ucheck = $pdo->prepare("SELECT * FROM users WHERE id=?");
                        $ucheck->execute([$_SESSION['user_id']]);
                        $ucheck = $ucheck->fetch(PDO::FETCH_ASSOC);

                        if (!password_verify($curPass, $ucheck['password'])) {
                            $profileErr = "Current password is incorrect.";
                        } elseif (empty($newUsername)) {
                            $profileErr = "Username cannot be empty.";
                        } else {
                            // Check username uniqueness
                            $dup = $pdo->prepare("SELECT id FROM users WHERE username=? AND id!=?");
                            $dup->execute([$newUsername, $_SESSION['user_id']]);
                            if ($dup->fetch()) {
                                $profileErr = "That username is already taken.";
                            } else {
                                if (!empty($newPass)) {
                                    if (strlen($newPass) < 6) {
                                        $profileErr = "New password must be at least 6 characters.";
                                    } elseif ($newPass !== $confPass) {
                                        $profileErr = "New passwords do not match.";
                                    } else {
                                        $hash = password_hash($newPass, PASSWORD_BCRYPT);
                                        $pdo->prepare("UPDATE users SET username=?, password=? WHERE id=?")
                                            ->execute([$newUsername, $hash, $_SESSION['user_id']]);
                                        $_SESSION['username'] = $newUsername;
                                        $profileMsg = "Profile and password updated successfully!";
                                    }
                                } else {
                                    $pdo->prepare("UPDATE users SET username=? WHERE id=?")
                                        ->execute([$newUsername, $_SESSION['user_id']]);
                                    $_SESSION['username'] = $newUsername;
                                    $profileMsg = "Username updated successfully!";
                                }
                            }
                        }
                    }
                    ?>
                    <?php if ($profileMsg): ?><div class="profile-msg ok"><?= $profileMsg ?></div><?php endif; ?>
                    <?php if ($profileErr): ?><div class="profile-msg err"><?= $profileErr ?></div><?php endif; ?>

                    <form method="POST" class="profile-form">
                        <label>Username</label>
                        <input type="text" name="new_username" value="<?= htmlspecialchars($_SESSION['username']) ?>" required>
                        <label>Email (cannot be changed)</label>
                        <input type="email" value="<?= htmlspecialchars($udata['email']) ?>" readonly>
                        <label>Current Password <span style="color:var(--error)">*</span></label>
                        <input type="password" name="current_password" placeholder="Required to save changes" required>
                        <label>New Password <span style="color:var(--text-muted);font-weight:400;">(leave blank to keep current)</span></label>
                        <input type="password" name="new_password" placeholder="min. 6 characters">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" placeholder="Repeat new password">
                        <button type="submit" name="update_profile" class="profile-submit">Save Changes</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- ABOUT SECTION -->
        <section id="section-about" style="display:none;">
            <h1>About BIT Program</h1>
            <div class="container">
                <div class="display-card" style="text-align:left;line-height:1.6;">
                    <h2>Bachelor Degree in Information Technology</h2>
                    <p><br><strong>Introduction:</strong><br>
                        BIT is the new program launched in the Nepali Year 2077 under the IT department. Bachelors in Information Technology (BIT) program of Tribhuvan University is designed by closely following the courses practiced in accredited international universities, subject to the condition that the intake students are twelve years of schooling in any stream or equivalent from any recognized board.
                        <br><br>
                        In addition to the foundation and core Information Technology courses, BIT offers several elective courses to meet the undergraduate academic program requirement and to fulfill the demand for development and implementation of new technology.
                        <br><br>
                        All undergraduate students are required to complete 120 credit hours of Information Technology and allied courses.
                    </p>
                    <h3>Objective:</h3>
                    <p>The main objective of BIT program of Tribhuvan University is to provide students intensive knowledge and skill to design, develop, and use information technology in different areas.</p>
                    <h3>Course Duration:</h3>
                    <p>The course consists of eight semesters (four academic years) with exams after each semester.</p>
                    <h4>Hours of Instruction:</h4>
                    <ul>
                        <li>Working days: 90 days per semester</li>
                        <li>3 credit (theory + lab): 3 lecture + 3 lab hours = 6 hrs/week</li>
                        <li>3 credit (theory only): 3 lecture + 2 tutorial = 5 hrs/week</li>
                    </ul>
                    <h4>Evaluation:</h4>
                    <p>
                        Theory: 20% internal + 80% external<br>
                        With lab: 20% internal + 20% lab + 60% external<br>
                        Minimum 40% required in each category.
                    </p>
                    <h4>Grading System:</h4>
                    <ul>
                        <li>Distinction: 80% and above</li>
                        <li>First Division: 70% and above</li>
                        <li>Second Division: 55% and above</li>
                        <li>Pass Division: 40% and above</li>
                    </ul>
                    <h4>Attendance Requirement:</h4>
                    <p>Students must maintain at least 80% attendance in each course.</p>
                    <h4>Fee Structure:</h4>
                    <p>
                        Free Competition (5): Rs. 50,500/-<br>
                        Donation (31): Rs. 3,30,500/-
                    </p>
                </div>
            </div>
        </section>

    <!-- THEME TOGGLE -->
    <button class="theme-toggle" id="themeToggle" title="Toggle light/dark mode">🌙</button>

    </div><!-- /.app -->

    <!-- DATA: must be BEFORE script.js -->
    <script>
        const syllabusData  = <?= json_encode($syllabusBySem) ?>;
        const notesData     = <?= json_encode($notesBySem) ?>;
        const pqData        = <?= json_encode($pqBySem) ?>;
        const dbQuestions   = <?= json_encode($mockQuestions) ?>;
    </script>
    <script src="script.js"></script>
    <script>
        (function() {
            const original = showSection;
            showSection = function(name) {
                original(name);
                if (name === 'notice') {
                    const badge = document.getElementById('notif-badge');
                    if (badge) badge.classList.add('hidden');
                    fetch('portal.php?seen_notices=1');
                }
            };
        })();

        // ── Theme Toggle ──────────────────────────────
        (function() {
            const btn  = document.getElementById('themeToggle');
            const body = document.body;
            const saved = localStorage.getItem('theme');
            if (saved === 'light') { body.classList.add('light-mode'); btn.textContent = '☀️'; }
            btn.addEventListener('click', () => {
                const isLight = body.classList.toggle('light-mode');
                btn.textContent = isLight ? '☀️' : '🌙';
                localStorage.setItem('theme', isLight ? 'light' : 'dark');
            });
        })();
    </script>
</body>
</html>