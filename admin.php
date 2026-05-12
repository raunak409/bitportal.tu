<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: portal.php");
    exit();
}
require 'db.php';

$msg = '';
$err = '';

// ── Create upload dirs ───────────────────────────
$uploadBase  = __DIR__ . '/uploads/';
$notesDir    = $uploadBase . 'notes/';
$noticesDir  = $uploadBase . 'notices/';
$syllabusDir = $uploadBase . 'syllabus/';
$pqsDir      = $uploadBase . 'pqs/';
foreach ([$uploadBase, $notesDir, $noticesDir, $syllabusDir, $pqsDir] as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0755, true);
}

// ── Add Syllabus Subject ─────────────────────────
if (isset($_POST['add_syllabus'])) {
    $sem     = (int)$_POST['semester'];
    $subject = trim($_POST['subject_name']);
    $credit  = trim($_POST['credit']);
    $sylfile = null;

    if ($sem && $subject) {
        if (!empty($_FILES['syllabus_file']['name']) && $_FILES['syllabus_file']['error'] === 0) {
            $ext = strtolower(pathinfo($_FILES['syllabus_file']['name'], PATHINFO_EXTENSION));
            if ($ext === 'pdf') {
                $sylfile = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['syllabus_file']['name']);
                move_uploaded_file($_FILES['syllabus_file']['tmp_name'], $syllabusDir . $sylfile);
            } else {
                $err = "Syllabus file must be a PDF.";
            }
        }
        if (!$err) {
            $pdo->prepare("INSERT INTO syllabus (semester, subject_name, credit, filename) VALUES (?,?,?,?)")
                ->execute([$sem, $subject, $credit, $sylfile]);
            $msg = "Subject added successfully!";
        }
    } else {
        $err = "Semester and subject name are required.";
    }
}

// ── Delete Syllabus ──────────────────────────────
if (isset($_GET['del_syllabus'])) {
    $row = $pdo->prepare("SELECT filename FROM syllabus WHERE id=?");
    $row->execute([(int)$_GET['del_syllabus']]);
    $r = $row->fetch(PDO::FETCH_ASSOC);
    if ($r && $r['filename'] && file_exists($syllabusDir . $r['filename'])) {
        unlink($syllabusDir . $r['filename']);
    }
    $pdo->prepare("DELETE FROM syllabus WHERE id=?")->execute([(int)$_GET['del_syllabus']]);
    header("Location: admin.php?tab=syllabus&msg=deleted");
    exit();
}

// ── Upload Note ──────────────────────────────────
if (isset($_POST['upload_note'])) {
    $sem   = (int)$_POST['note_semester'];
    $title = trim($_POST['note_title']);
    $file  = $_FILES['note_file'] ?? null;
    if ($sem && $title && $file && $file['error'] === 0) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext === 'pdf') {
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']);
            move_uploaded_file($file['tmp_name'], $notesDir . $filename);
            $pdo->prepare("INSERT INTO notes (semester, title, filename) VALUES (?,?,?)")
                ->execute([$sem, $title, $filename]);
            $msg = "Note uploaded successfully!";
        } else {
            $err = "Only PDF files allowed for notes.";
        }
    } else {
        $err = "All fields required.";
    }
}

// ── Delete Note ──────────────────────────────────
if (isset($_GET['del_note'])) {
    $row = $pdo->prepare("SELECT filename FROM notes WHERE id=?");
    $row->execute([(int)$_GET['del_note']]);
    $r = $row->fetch(PDO::FETCH_ASSOC);
    if ($r && file_exists($notesDir . $r['filename'])) unlink($notesDir . $r['filename']);
    $pdo->prepare("DELETE FROM notes WHERE id=?")->execute([(int)$_GET['del_note']]);
    header("Location: admin.php?tab=notes&msg=deleted");
    exit();
}

// ── Upload Notice ────────────────────────────────
if (isset($_POST['upload_notice'])) {
    $title = trim($_POST['notice_title']);
    $badge = trim($_POST['notice_badge']);
    $file  = $_FILES['notice_file'] ?? null;
    if ($title && $file && $file['error'] === 0) {
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp','pdf'];
        if (in_array($ext, $allowed)) {
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']);
            move_uploaded_file($file['tmp_name'], $noticesDir . $filename);
            $pdo->prepare("INSERT INTO notices (title, badge, filename) VALUES (?,?,?)")
                ->execute([$title, $badge, $filename]);
            $msg = "Notice uploaded successfully!";
        } else {
            $err = "Only image or PDF files allowed.";
        }
    } else {
        $err = "Title and file are required.";
    }
}

// ── Delete Notice ────────────────────────────────
if (isset($_GET['del_notice'])) {
    $row = $pdo->prepare("SELECT filename FROM notices WHERE id=?");
    $row->execute([(int)$_GET['del_notice']]);
    $r = $row->fetch(PDO::FETCH_ASSOC);
    if ($r && file_exists($noticesDir . $r['filename'])) unlink($noticesDir . $r['filename']);
    $pdo->prepare("DELETE FROM notices WHERE id=?")->execute([(int)$_GET['del_notice']]);
    header("Location: admin.php?tab=notices&msg=deleted");
    exit();
}

// ── Upload Past Question Solution ────────────────
if (isset($_POST['upload_pqs'])) {
    $sem   = (int)$_POST['pqs_semester'];
    $title = trim($_POST['pqs_title']);
    $file  = $_FILES['pqs_file'] ?? null;
    if ($sem && $title && $file && $file['error'] === 0) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext === 'pdf') {
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']);
            move_uploaded_file($file['tmp_name'], $pqsDir . $filename);
            $pdo->prepare("INSERT INTO past_questions (semester, title, filename) VALUES (?,?,?)")
                ->execute([$sem, $title, $filename]);
            $msg = "Past question solution uploaded successfully!";
        } else {
            $err = "Only PDF files allowed.";
        }
    } else {
        $err = "All fields required.";
    }
}

// ── Delete Past Question Solution ────────────────
if (isset($_GET['del_pqs'])) {
    $row = $pdo->prepare("SELECT filename FROM past_questions WHERE id=?");
    $row->execute([(int)$_GET['del_pqs']]);
    $r = $row->fetch(PDO::FETCH_ASSOC);
    if ($r && file_exists($pqsDir . $r['filename'])) unlink($pqsDir . $r['filename']);
    $pdo->prepare("DELETE FROM past_questions WHERE id=?")->execute([(int)$_GET['del_pqs']]);
    header("Location: admin.php?tab=pqs&msg=deleted");
    exit();
}

// ── User Role Change ─────────────────────────────
if (isset($_POST['toggle_role'])) {
    $uid     = (int)$_POST['user_id'];
    $newRole = $_POST['new_role'];
    if (in_array($newRole, ['user','admin'])) {
        $pdo->prepare("UPDATE users SET role=? WHERE id=?")->execute([$newRole, $uid]);
        $msg = "User role updated!";
    }
}

// ── Delete User ──────────────────────────────────
if (isset($_GET['delete_user'])) {
    $uid = (int)$_GET['delete_user'];
    if ($uid !== $_SESSION['user_id']) {
        $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$uid]);
    }
    header("Location: admin.php?tab=users");
    exit();
}

// ── Ensure mock_questions table exists ───────────
$pdo->exec("CREATE TABLE IF NOT EXISTS mock_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    opt_a VARCHAR(300) NOT NULL,
    opt_b VARCHAR(300) NOT NULL,
    opt_c VARCHAR(300) NOT NULL,
    opt_d VARCHAR(300) NOT NULL,
    correct_opt CHAR(1) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// ── Add Mock Question ────────────────────────────
if (isset($_POST['add_mock_question'])) {
    $question    = trim($_POST['mq_question']);
    $opt_a       = trim($_POST['mq_opt_a']);
    $opt_b       = trim($_POST['mq_opt_b']);
    $opt_c       = trim($_POST['mq_opt_c']);
    $opt_d       = trim($_POST['mq_opt_d']);
    $correct_opt = $_POST['mq_correct'];
    if ($question && $opt_a && $opt_b && $opt_c && $opt_d && in_array($correct_opt, ['A','B','C','D'])) {
        $pdo->prepare("INSERT INTO mock_questions (question, opt_a, opt_b, opt_c, opt_d, correct_opt) VALUES (?,?,?,?,?,?)")
            ->execute([$question, $opt_a, $opt_b, $opt_c, $opt_d, $correct_opt]);
        $msg = "Question added successfully!";
    } else {
        $err = "All fields are required and a correct answer must be selected.";
    }
}

// ── Delete Mock Question ─────────────────────────
if (isset($_GET['del_mock'])) {
    $pdo->prepare("DELETE FROM mock_questions WHERE id=?")->execute([(int)$_GET['del_mock']]);
    header("Location: admin.php?tab=mocktest&msg=deleted");
    exit();
}

// ── Fetch all data ───────────────────────────────
$activeTab       = $_GET['tab'] ?? 'syllabus';
$syllabusRows    = $pdo->query("SELECT * FROM syllabus ORDER BY semester, id")->fetchAll(PDO::FETCH_ASSOC);
$notesRows       = $pdo->query("SELECT * FROM notes ORDER BY semester, id")->fetchAll(PDO::FETCH_ASSOC);
$noticesRows     = $pdo->query("SELECT * FROM notices ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$pqsRows         = $pdo->query("SELECT * FROM past_questions ORDER BY semester, id")->fetchAll(PDO::FETCH_ASSOC);
$usersRows       = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$mockQuestRows   = $pdo->query("SELECT * FROM mock_questions ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') $msg = "Deleted successfully!";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel – BIT Portal</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .admin-tabs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 24px;
            justify-content: center;
        }
        .admin-tab-btn {
            font-family: 'Poppins', sans-serif;
            font-size: .78rem;
            font-weight: 600;
            padding: 10px 22px;
            border-radius: 50px;
            border: 1px solid var(--accent);
            background: transparent;
            color: var(--accent);
            cursor: pointer;
            text-decoration: none;
            transition: all .2s;
        }
        .admin-tab-btn:hover, .admin-tab-btn.active {
            background: var(--accent);
            color: #0b0f19;
        }
        .upload-form {
            background: var(--surface-2);
            border: 1px solid var(--border-mid);
            border-radius: 14px;
            padding: 22px;
            margin-bottom: 28px;
        }
        .upload-form h3 {
            font-size: .92rem;
            color: var(--accent);
            margin: 0 0 16px;
        }
        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
            flex: 1;
            min-width: 140px;
        }
        .form-group label {
            font-size: .75rem;
            color: var(--text-muted);
        }
        .form-group input,
        .form-group select {
            padding: 9px 12px;
            background: var(--surface);
            border: 1px solid var(--border-mid);
            border-radius: 8px;
            color: var(--text);
            font-family: 'Poppins', sans-serif;
            font-size: .82rem;
            outline: none;
        }
        .form-group input:focus,
        .form-group select:focus { border-color: var(--accent); }
        .form-group input[type="file"] { padding: 7px 10px; cursor: pointer; }
        .submit-btn {
            padding: 10px 24px;
            background: var(--accent);
            color: #0b0f19;
            border: none;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: .82rem;
            cursor: pointer;
            white-space: nowrap;
            transition: opacity .2s;
            align-self: flex-end;
        }
        .submit-btn:hover { opacity: .85; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td {
            padding: 11px 14px;
            text-align: left;
            font-size: .8rem;
            border-bottom: 1px solid var(--border);
        }
        .data-table th { color: var(--accent); font-weight: 600; }
        .data-table tr:hover td { background: var(--surface-2); }
        .del-link { color: #e74c3c; font-size: .75rem; font-weight: 600; text-decoration: none; }
        .del-link:hover { opacity: .75; }
        .msg-box { padding: 12px 16px; border-radius: 8px; font-size: .83rem; margin-bottom: 18px; font-weight: 500; }
        .msg-success { background: rgba(46,204,113,0.15); color: #2ecc71; border: 1px solid #2ecc71; }
        .msg-error   { background: rgba(231,76,60,0.15);  color: #e74c3c; border: 1px solid #e74c3c; }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .top-bar a { font-size: .8rem; color: var(--accent); text-decoration: none; }
        .sem-badge { background: var(--surface-3); color: var(--accent); padding: 2px 8px; border-radius: 4px; font-size: .7rem; font-weight: 600; }
        .pdf-link  { color: var(--accent); font-size: .75rem; text-decoration: none; font-weight: 600; }
        .pdf-link:hover { opacity: .8; }
        .no-pdf    { color: var(--text-muted); font-size: .75rem; }
        .role-select { background: var(--surface-2); color: var(--text); border: 1px solid var(--border-mid); border-radius: 5px; padding: 3px 7px; font-size: .75rem; font-family: 'Poppins', sans-serif; cursor: pointer; }
        .optional-tag { font-size: .68rem; color: var(--text-muted); margin-left: 4px; }
    </style>
</head>
<body>
<div class="app">

    <div class="top-bar">
        <h1 style="margin:0; font-size:1.3rem;">⚙ Admin Panel</h1>
        <div style="display:flex; gap:16px;">
            <a href="portal.php">← Back to Portal</a>
            <a href="logout.php" style="color:#e74c3c;">Logout</a>
        </div>
    </div>

    <?php if ($msg) echo "<div class='msg-box msg-success'>✓ $msg</div>"; ?>
    <?php if ($err) echo "<div class='msg-box msg-error'>✗ $err</div>"; ?>

    <!-- TABS -->
    <div class="admin-tabs">
        <a href="?tab=syllabus" class="admin-tab-btn <?= $activeTab==='syllabus'?'active':'' ?>">📚 Syllabus</a>
        <a href="?tab=notes"   class="admin-tab-btn <?= $activeTab==='notes'  ?'active':'' ?>">📄 Notes</a>
        <a href="?tab=notices" class="admin-tab-btn <?= $activeTab==='notices'?'active':'' ?>">📢 Notices</a>
        <a href="?tab=pqs"     class="admin-tab-btn <?= $activeTab==='pqs'    ?'active':'' ?>">📝 PQ Solutions</a>
        <a href="?tab=mocktest" class="admin-tab-btn <?= $activeTab==='mocktest'?'active':'' ?>">🧪 Mock Test</a>
        <a href="?tab=users"   class="admin-tab-btn <?= $activeTab==='users'  ?'active':'' ?>">👥 Users</a>
    </div>

    <!-- ═══ SYLLABUS TAB ═══ -->
    <?php if ($activeTab === 'syllabus'): ?>
    <div class="display-card">
        <div class="upload-form">
            <h3>➕ Add New Subject</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label>Semester</label>
                        <select name="semester">
                            <?php for($i=1;$i<=8;$i++) echo "<option value='$i'>Semester $i</option>"; ?>
                        </select>
                    </div>
                    <div class="form-group" style="flex:3;">
                        <label>Subject Name</label>
                        <input type="text" name="subject_name" placeholder="e.g. Object Oriented Programming" required>
                    </div>
                    <div class="form-group">
                        <label>Credit Hours</label>
                        <input type="text" name="credit" placeholder="e.g. 3">
                    </div>
                </div>
                <div class="form-row" style="margin-top:12px;">
                    <div class="form-group" style="flex:3;">
                        <label>Syllabus PDF <span class="optional-tag">(optional)</span></label>
                        <input type="file" name="syllabus_file" accept=".pdf">
                    </div>
                    <button type="submit" name="add_syllabus" class="submit-btn">Add Subject</button>
                </div>
            </form>
        </div>
        <table class="data-table">
            <thead><tr><th>Sem</th><th>Subject Name</th><th>Credit</th><th>Syllabus PDF</th><th>Action</th></tr></thead>
            <tbody>
            <?php if (empty($syllabusRows)): ?>
                <tr><td colspan="5" style="color:var(--text-muted);text-align:center;padding:20px;">No subjects added yet.</td></tr>
            <?php else: ?>
                <?php foreach ($syllabusRows as $row): ?>
                <tr>
                    <td><span class="sem-badge">Sem <?= $row['semester'] ?></span></td>
                    <td><?= htmlspecialchars($row['subject_name']) ?></td>
                    <td><?= htmlspecialchars($row['credit'] ?? '—') ?></td>
                    <td>
                        <?php if (!empty($row['filename'])): ?>
                            <a href="uploads/syllabus/<?= urlencode($row['filename']) ?>" target="_blank" class="pdf-link">📄 View PDF</a>
                        <?php else: ?>
                            <span class="no-pdf">No PDF</span>
                        <?php endif; ?>
                    </td>
                    <td><a href="?tab=syllabus&del_syllabus=<?= $row['id'] ?>" class="del-link" onclick="return confirm('Delete this subject?')">Delete</a></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ═══ NOTES TAB ═══ -->
    <?php elseif ($activeTab === 'notes'): ?>
    <div class="display-card">
        <div class="upload-form">
            <h3>📤 Upload Note</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label>Semester</label>
                        <select name="note_semester">
                            <?php for($i=1;$i<=8;$i++) echo "<option value='$i'>Semester $i</option>"; ?>
                        </select>
                    </div>
                    <div class="form-group" style="flex:2;">
                        <label>Note Title</label>
                        <input type="text" name="note_title" placeholder="e.g. C Programming - Let Us C" required>
                    </div>
                    <div class="form-group" style="flex:2;">
                        <label>PDF File</label>
                        <input type="file" name="note_file" accept=".pdf" required>
                    </div>
                    <button type="submit" name="upload_note" class="submit-btn">Upload</button>
                </div>
            </form>
        </div>
        <table class="data-table">
            <thead><tr><th>Sem</th><th>Title</th><th>File</th><th>Action</th></tr></thead>
            <tbody>
            <?php if (empty($notesRows)): ?>
                <tr><td colspan="4" style="color:var(--text-muted);text-align:center;padding:20px;">No notes uploaded yet.</td></tr>
            <?php else: ?>
                <?php foreach ($notesRows as $row): ?>
                <tr>
                    <td><span class="sem-badge">Sem <?= $row['semester'] ?></span></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><a href="uploads/notes/<?= urlencode($row['filename']) ?>" target="_blank" class="pdf-link">📄 View PDF</a></td>
                    <td><a href="?tab=notes&del_note=<?= $row['id'] ?>" class="del-link" onclick="return confirm('Delete this note?')">Delete</a></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ═══ NOTICES TAB ═══ -->
    <?php elseif ($activeTab === 'notices'): ?>
    <div class="display-card">
        <div class="upload-form">
            <h3>📤 Upload Notice (Image or PDF)</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group" style="flex:2;">
                        <label>Notice Title</label>
                        <input type="text" name="notice_title" placeholder="e.g. BIT 3rd Semester Exam Notice" required>
                    </div>
                    <div class="form-group">
                        <label>Badge Label</label>
                        <input type="text" name="notice_badge" placeholder="e.g. Exam Notice" value="Exam Notice">
                    </div>
                    <div class="form-group" style="flex:2;">
                        <label>Image or PDF File</label>
                        <input type="file" name="notice_file" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf" required>
                    </div>
                    <button type="submit" name="upload_notice" class="submit-btn">Upload</button>
                </div>
            </form>
        </div>
        <table class="data-table">
            <thead><tr><th>Title</th><th>Badge</th><th>File</th><th>Action</th></tr></thead>
            <tbody>
            <?php if (empty($noticesRows)): ?>
                <tr><td colspan="4" style="color:var(--text-muted);text-align:center;padding:20px;">No notices uploaded yet.</td></tr>
            <?php else: ?>
                <?php foreach ($noticesRows as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><span class="sem-badge"><?= htmlspecialchars($row['badge']) ?></span></td>
                    <td><a href="uploads/notices/<?= urlencode($row['filename']) ?>" target="_blank" class="pdf-link">View</a></td>
                    <td><a href="?tab=notices&del_notice=<?= $row['id'] ?>" class="del-link" onclick="return confirm('Delete this notice?')">Delete</a></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ═══ PQ SOLUTIONS TAB ═══ -->
    <?php elseif ($activeTab === 'pqs'): ?>
    <div class="display-card">
        <div class="upload-form">
            <h3>📤 Upload Past Question Solution</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label>Semester</label>
                        <select name="pqs_semester">
                            <?php for($i=1;$i<=8;$i++) echo "<option value='$i'>Semester $i</option>"; ?>
                        </select>
                    </div>
                    <div class="form-group" style="flex:2;">
                        <label>Title</label>
                        <input type="text" name="pqs_title" placeholder="e.g. C Programming - 2078 Solution" required>
                    </div>
                    <div class="form-group" style="flex:2;">
                        <label>PDF File</label>
                        <input type="file" name="pqs_file" accept=".pdf" required>
                    </div>
                    <button type="submit" name="upload_pqs" class="submit-btn">Upload</button>
                </div>
            </form>
        </div>
        <table class="data-table">
            <thead><tr><th>Sem</th><th>Title</th><th>File</th><th>Uploaded</th><th>Action</th></tr></thead>
            <tbody>
            <?php if (empty($pqsRows)): ?>
                <tr><td colspan="5" style="color:var(--text-muted);text-align:center;padding:20px;">No past question solutions uploaded yet.</td></tr>
            <?php else: ?>
                <?php foreach ($pqsRows as $row): ?>
                <tr>
                    <td><span class="sem-badge">Sem <?= $row['semester'] ?></span></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><a href="uploads/pqs/<?= urlencode($row['filename']) ?>" target="_blank" class="pdf-link">📄 View PDF</a></td>
                    <td style="color:var(--text-muted);font-size:.75rem;"><?= date('d M Y', strtotime($row['uploaded_at'])) ?></td>
                    <td><a href="?tab=pqs&del_pqs=<?= $row['id'] ?>" class="del-link" onclick="return confirm('Delete this solution?')">Delete</a></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ═══ MOCK TEST TAB ═══ -->
    <?php elseif ($activeTab === 'mocktest'): ?>
    <div class="display-card">
        <div class="upload-form">
            <h3>➕ Add New Mock Question</h3>
            <form method="POST">
                <div class="form-row">
                    <div class="form-group" style="flex:4;">
                        <label>Question</label>
                        <input type="text" name="mq_question" placeholder="e.g. Which data structure uses LIFO?" required>
                    </div>
                    <div class="form-group">
                        <label>Correct Answer</label>
                        <select name="mq_correct" required>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                </div>
                <div class="form-row" style="margin-top:12px;">
                    <div class="form-group">
                        <label>Option A</label>
                        <input type="text" name="mq_opt_a" placeholder="Option A" required>
                    </div>
                    <div class="form-group">
                        <label>Option B</label>
                        <input type="text" name="mq_opt_b" placeholder="Option B" required>
                    </div>
                    <div class="form-group">
                        <label>Option C</label>
                        <input type="text" name="mq_opt_c" placeholder="Option C" required>
                    </div>
                    <div class="form-group">
                        <label>Option D</label>
                        <input type="text" name="mq_opt_d" placeholder="Option D" required>
                    </div>
                    <button type="submit" name="add_mock_question" class="submit-btn">Add Question</button>
                </div>
            </form>
        </div>
        <p style="color:var(--text-muted);font-size:.82rem;margin-bottom:14px;">Total questions in pool: <strong style="color:var(--text)"><?= count($mockQuestRows) ?></strong></p>
        <table class="data-table">
            <thead><tr><th>#</th><th>Question</th><th>A</th><th>B</th><th>C</th><th>D</th><th>Correct</th><th>Action</th></tr></thead>
            <tbody>
            <?php if (empty($mockQuestRows)): ?>
                <tr><td colspan="8" style="color:var(--text-muted);text-align:center;padding:20px;">No questions added yet.</td></tr>
            <?php else: ?>
                <?php foreach ($mockQuestRows as $row): ?>
                <tr>
                    <td style="color:var(--text-muted);font-size:.72rem;"><?= $row['id'] ?></td>
                    <td style="max-width:240px;"><?= htmlspecialchars($row['question']) ?></td>
                    <td style="font-size:.75rem;color:var(--text-muted);"><?= htmlspecialchars($row['opt_a']) ?></td>
                    <td style="font-size:.75rem;color:var(--text-muted);"><?= htmlspecialchars($row['opt_b']) ?></td>
                    <td style="font-size:.75rem;color:var(--text-muted);"><?= htmlspecialchars($row['opt_c']) ?></td>
                    <td style="font-size:.75rem;color:var(--text-muted);"><?= htmlspecialchars($row['opt_d']) ?></td>
                    <td><span class="sem-badge" style="background:rgba(46,204,113,.15);color:#2ecc71;border:1px solid #2ecc71;"><?= htmlspecialchars($row['correct_opt']) ?></span></td>
                    <td><a href="?tab=mocktest&del_mock=<?= $row['id'] ?>" class="del-link" onclick="return confirm('Delete this question?')">Delete</a></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ═══ USERS TAB ═══ -->
    <?php elseif ($activeTab === 'users'): ?>
    <div class="display-card">
        <p style="color:var(--text-muted); font-size:.82rem; margin-bottom:16px;">Total users: <strong style="color:var(--text)"><?= count($usersRows) ?></strong></p>
        <table class="data-table">
            <thead><tr><th>#</th><th>Username</th><th>Email</th><th>Role</th><th>Joined</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($usersRows as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td>
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                        <select name="new_role" class="role-select" onchange="this.form.submit()">
                            <option value="user"  <?= $u['role']==='user' ?'selected':'' ?>>user</option>
                            <option value="admin" <?= $u['role']==='admin'?'selected':'' ?>>admin</option>
                        </select>
                        <input type="hidden" name="toggle_role" value="1">
                    </form>
                </td>
                <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                <td>
                    <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                        <a href="?tab=users&delete_user=<?= $u['id'] ?>" class="del-link" onclick="return confirm('Delete this user?')">Delete</a>
                    <?php else: ?>
                        <span style="color:var(--text-muted);font-size:.72rem;">you</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

</div>
</body>
</html>