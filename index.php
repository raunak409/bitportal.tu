<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: portal.php");
    exit();
}
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

    /* ── subtle grid pattern on bg ── */
    body::before {
        content: '';
        position: fixed;
        inset: 0;
        background-image:
            linear-gradient(var(--border) 1px, transparent 1px),
            linear-gradient(90deg, var(--border) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
        z-index: 0;
    }

    .app { position: relative; z-index: 1; }

    /* ── top bar ── */
    .lp-nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 0 0;
        margin-bottom: 0;
    }
    .lp-logo {
        font-size: .85rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        background: linear-gradient(130deg, #ffffff 30%, var(--accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-decoration: none;
    }
    .lp-nav-links { display: flex; gap: 8px; }
    .lp-btn-ghost {
        font-family: 'Poppins', sans-serif;
        font-size: .76rem;
        font-weight: 600;
        color: var(--text-muted);
        background: transparent;
        border: 1px solid var(--border-mid);
        border-radius: 8px;
        padding: 7px 18px;
        text-decoration: none;
        transition: color .18s, border-color .18s;
    }
    .lp-btn-ghost:hover { color: var(--text); border-color: var(--accent); }
    .lp-btn-solid {
        font-family: 'Poppins', sans-serif;
        font-size: .76rem;
        font-weight: 700;
        color: #0b0f19;
        background: var(--accent);
        border: 1px solid transparent;
        border-radius: 8px;
        padding: 7px 18px;
        text-decoration: none;
        transition: opacity .18s;
    }
    .lp-btn-solid:hover { opacity: .88; }

    /* ── hero ── */
    .hero-section {
        text-align: center;
        padding: 72px 0 52px;
        max-width: 600px;
        margin: 0 auto;
    }

    .hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: var(--glass);
        border: 1px solid var(--border-mid);
        border-radius: 50px;
        padding: 5px 14px;
        font-size: .7rem;
        font-weight: 600;
        letter-spacing: .05em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 24px;
        opacity: 0;
        animation: fadeUp .5s .05s ease forwards;
    }
    .hero-pill .pulse {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: var(--accent);
        animation: pulse-ring 1.8s ease-in-out infinite;
    }

    .hero-h1 {
        /* keep original h1 styles, just bigger */
        font-size: clamp(2rem, 6vw, 3.2rem) !important;
        letter-spacing: -1px !important;
        text-transform: none !important;
        line-height: 1.12 !important;
        margin-bottom: 16px !important;
        opacity: 0;
        animation: fadeUp .6s .15s ease forwards;
    }

    .hero-sub {
        font-size: .9rem;
        color: var(--text-muted);
        line-height: 1.78;
        margin-bottom: 32px;
        opacity: 0;
        animation: fadeUp .6s .25s ease forwards;
    }

    .hero-cta {
        display: flex;
        gap: 10px;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 48px;
        opacity: 0;
        animation: fadeUp .6s .35s ease forwards;
    }
    .cta-primary {
        font-family: 'Poppins', sans-serif;
        font-size: .85rem;
        font-weight: 700;
        color: #0b0f19;
        background: var(--accent);
        border: none;
        border-radius: 10px;
        padding: 12px 30px;
        text-decoration: none;
        transition: opacity .18s, transform .18s;
        position: relative;
        overflow: hidden;
    }
    .cta-primary::after {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,.15);
        opacity: 0;
        transition: opacity .18s;
    }
    .cta-primary:hover::after { opacity: 1; }
    .cta-primary:hover { transform: translateY(-1px); }
    .cta-secondary {
        font-family: 'Poppins', sans-serif;
        font-size: .85rem;
        font-weight: 600;
        color: var(--text);
        background: var(--glass);
        border: 1px solid var(--border-mid);
        border-radius: 10px;
        padding: 12px 26px;
        text-decoration: none;
        transition: border-color .18s, color .18s;
    }
    .cta-secondary:hover { border-color: var(--accent); color: var(--accent); }

    /* trust pills */
    .trust-row {
        display: flex;
        gap: 16px;
        justify-content: center;
        flex-wrap: wrap;
        opacity: 0;
        animation: fadeUp .6s .45s ease forwards;
    }
    .trust-pill {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: .72rem;
        color: var(--text-muted);
    }
    .trust-pill::before {
        content: '✓';
        color: var(--accent);
        font-weight: 700;
        font-size: .7rem;
    }

    /* ── stats bar ── */
    .stats-bar {
        display: flex;
        background: var(--surface);
        border: 1px solid var(--border-mid);
        border-radius: 14px;
        overflow: hidden;
        margin: 48px auto 0;
        max-width: 560px;
        opacity: 0;
        animation: fadeUp .6s .5s ease forwards;
    }
    .stat-cell {
        flex: 1;
        padding: 18px 8px;
        text-align: center;
        border-right: 1px solid var(--border);
    }
    .stat-cell:last-child { border-right: none; }
    .stat-num {
        font-size: 1.45rem;
        font-weight: 700;
        color: var(--accent);
        line-height: 1;
        margin-bottom: 4px;
    }
    .stat-lbl {
        font-size: .62rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .07em;
    }

    /* ── section label ── */
    .sec-eyebrow {
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: var(--accent);
        text-align: center;
        margin-bottom: 8px;
    }
    .sec-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text);
        text-align: center;
        margin-bottom: 22px;
        letter-spacing: -.2px;
    }

    /* ── feature cards ── */
    .feat-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        max-width: 800px;
        margin: 0 auto 56px;
    }
    .feat-grid .feat-card:first-child {
        grid-column: span 2;
        display: grid;
        grid-template-columns: auto 1fr;
        align-items: center;
        gap: 18px;
        text-align: left;
    }
    .feat-card {
        background: var(--glass);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 22px 18px;
        text-align: left;
        transition: border-color .2s, transform .2s;
        position: relative;
        overflow: hidden;
    }
    .feat-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at var(--mx,50%) var(--my,50%), var(--accent-glow) 0%, transparent 65%);
        opacity: 0;
        transition: opacity .3s;
        pointer-events: none;
    }
    .feat-card:hover { border-color: var(--border-mid); transform: translateY(-2px); }
    .feat-card:hover::before { opacity: 1; }

    .feat-icon-box {
        width: 44px; height: 44px;
        border-radius: 11px;
        background: var(--surface-2);
        border: 1px solid var(--border-mid);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
        margin-bottom: 14px;
    }
    .feat-card:first-child .feat-icon-box { margin-bottom: 0; }
    .feat-card h3 {
        font-size: .88rem;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 6px;
    }
    .feat-card p {
        font-size: .76rem;
        color: var(--text-muted);
        line-height: 1.65;
        margin: 0;
    }
    .feat-card:first-child .feat-stat-row {
        display: flex;
        gap: 18px;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid var(--border);
    }
    .fsr-item .fsr-n {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--accent);
        line-height: 1;
    }
    .fsr-item .fsr-l {
        font-size: .63rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    /* ── steps ── */
    .steps-wrap {
        max-width: 800px;
        margin: 0 auto 56px;
    }
    .steps-list {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        position: relative;
    }
    .steps-list::before {
        content: '';
        position: absolute;
        top: 21px; left: calc(100%/6);
        right: calc(100%/6);
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--border-mid) 30%, var(--accent) 50%, var(--border-mid) 70%, transparent);
    }
    .step-card {
        background: var(--glass);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 20px 16px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }
    .step-num {
        width: 42px; height: 42px;
        border-radius: 50%;
        background: var(--surface);
        border: 1px solid var(--border-mid);
        display: flex; align-items: center; justify-content: center;
        font-size: .82rem;
        font-weight: 700;
        color: var(--accent);
        position: relative; z-index: 1;
        flex-shrink: 0;
    }
    .step-card h4 { font-size: .85rem; font-weight: 700; color: var(--text); margin: 0; }
    .step-card p  { font-size: .74rem; color: var(--text-muted); margin: 0; line-height: 1.6; }

    /* ── contact ── */
    .contact-section {
        width: 100%;
        max-width: 800px;
        margin: 0 auto 50px;
        text-align: center;
        padding: 0;
    }
    .contact-section h2 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 16px;
        color: var(--text);
    }
    .contact-cards {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }
    .contact-card {
        background: var(--glass);
        border: 1px solid var(--border-mid);
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        min-width: 0;
        overflow: hidden;
        transition: border-color .2s, transform .18s;
    }
    .contact-card:hover { border-color: var(--accent); transform: translateY(-2px); }
    .contact-card .c-icon { font-size: 1.3rem; flex-shrink: 0; line-height: 1; }
    .contact-card .c-info { text-align: left; min-width: 0; flex: 1; overflow: hidden; }
    .contact-card .c-label { display: block; font-size: .62rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: .06em; margin: 0 0 3px; white-space: nowrap; }
    .contact-card .c-value { display: block; font-size: .8rem; font-weight: 600; color: var(--text); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; }

    /* ── footer ── */
    .site-footer {
        margin-top: 40px;
        padding: 18px 0 24px;
        text-align: center;
        border-top: 1px solid var(--border-mid);
        font-size: .72rem;
        color: var(--text-muted);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .site-footer a { color: var(--accent); text-decoration: none; }
    .footer-links { display: flex; gap: 16px; }
    .footer-links a { color: var(--text-muted); text-decoration: none; transition: color .18s; font-size: .72rem; }
    .footer-links a:hover { color: var(--text); }

    /* ── scroll reveal ── */
    .sr { opacity: 0; transform: translateY(18px); transition: opacity .5s ease, transform .5s ease; }
    .sr.in { opacity: 1; transform: none; }

    /* ── keyframes ── */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulse-ring {
        0%, 100% { box-shadow: 0 0 0 0 var(--accent-glow); }
        60%       { box-shadow: 0 0 0 6px rgba(56,189,248,0); }
    }

    /* ── responsive ── */
    @media (max-width: 600px) {
        .lp-nav { flex-direction: column; gap: 12px; }
        .feat-grid { grid-template-columns: 1fr; }
        .feat-grid .feat-card:first-child { grid-column: span 1; grid-template-columns: 1fr; }
        .steps-list { grid-template-columns: 1fr; gap: 10px; }
        .steps-list::before { display: none; }
        .contact-cards { grid-template-columns: 1fr; }
        .site-footer { flex-direction: column; text-align: center; }
        .trust-row { gap: 10px; }
    }
    @media (min-width: 601px) and (max-width: 768px) {
        .contact-cards { grid-template-columns: 1fr 1fr; }
        .contact-cards .contact-card:last-child { grid-column: span 2; }
    }

    </style>
</head>
<body>
<div class="app">

    <!-- TOP NAV -->
    <nav class="lp-nav">
        <a href="#" class="lp-logo">BIT Portal</a>
        <div class="lp-nav-links">
            <a href="register.php" class="lp-btn-ghost">Register</a>
            <a href="login.php"    class="lp-btn-solid">Login →</a>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero-section">
        <div class="hero-pill">
            <span class="pulse"></span>
            BIT Students — Semester 1 to 8
        </div>

        <h1 class="hero-h1">Your complete<br><span style="background:linear-gradient(130deg,#fff 30%,var(--accent));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">BIT study portal.</span></h1>

        <p class="hero-sub">Semester syllabus, study notes, timed mock tests and official notices — everything a BIT student needs, built and maintained by a BIT student.</p>

        <div class="trust-row">
            <span class="trust-pill">Free access</span>
            <span class="trust-pill">All 8 semesters</span>
            <span class="trust-pill">No hidden fees</span>
            <span class="trust-pill">Dark &amp; light mode</span>
        </div>

        <!-- stats -->
        <div class="stats-bar">
            <div class="stat-cell">
                <div class="stat-num">8</div>
                <div class="stat-lbl">Semesters</div>
            </div>
            <div class="stat-cell">
                <div class="stat-num">50+</div>
                <div class="stat-lbl">Subjects</div>
            </div>
            <div class="stat-cell">
                <div class="stat-num">100+</div>
                <div class="stat-lbl">Notes PDFs</div>
            </div>
            <div class="stat-cell">
                <div class="stat-num">100%</div>
                <div class="stat-lbl">Free</div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <p class="sec-eyebrow sr">What's inside</p>
    <p class="sec-title sr">Everything you need to succeed</p>

    <div class="feat-grid sr">

        <!-- wide card -->
        <div class="feat-card" data-gc>
            <div class="feat-icon-box">📚</div>
            <div>
                <h3>Complete Syllabus</h3>
                <p>All 8 semesters with subject names, credit hours, and downloadable PDFs. Search any subject across every semester instantly.</p>
                <div class="feat-stat-row">
                    <div class="fsr-item"><div class="fsr-n">8</div><div class="fsr-l">Semesters</div></div>
                    <div class="fsr-item"><div class="fsr-n">50+</div><div class="fsr-l">Subjects</div></div>
                    <div class="fsr-item"><div class="fsr-n">100%</div><div class="fsr-l">Free</div></div>
                </div>
            </div>
        </div>

        <div class="feat-card" data-gc>
            <div class="feat-icon-box">📄</div>
            <h3>Study Notes</h3>
            <p>Faculty-uploaded notes per subject. View in your browser or download as PDF — no extra steps.</p>
        </div>

        <div class="feat-card" data-gc>
            <div class="feat-icon-box">🧠</div>
            <h3>Mock Tests</h3>
            <p>Timed 10-second-per-question quizzes with instant feedback, sound effects and a final score card.</p>
        </div>

        <div class="feat-card" data-gc>
            <div class="feat-icon-box">📢</div>
            <h3>Official Notices</h3>
            <p>Exam timetables and announcements with a red badge alert so you never miss anything important.</p>
        </div>

        <div class="feat-card" data-gc>
            <div class="feat-icon-box">📝</div>
            <h3>Past Question Solutions</h3>
            <p>Solved PQS sorted by semester — the fastest way to prepare for final exams.</p>
        </div>

    </div>

    <!-- STEPS -->
    <p class="sec-eyebrow sr">How it works</p>
    <p class="sec-title sr">Ready in 3 steps</p>

    <div class="steps-wrap sr">
        <div class="steps-list">
            <div class="step-card">
                <div class="step-num">01</div>
                <h4>Create Account</h4>
                <p>Register with your name and email. Free, takes under a minute.</p>
            </div>
            <div class="step-card">
                <div class="step-num">02</div>
                <h4>Pick Semester</h4>
                <p>Choose your semester and instantly access all resources.</p>
            </div>
            <div class="step-card">
                <div class="step-num">03</div>
                <h4>Study &amp; Test</h4>
                <p>Download notes, practice quizzes, and check notices.</p>
            </div>
        </div>
    </div>

    <!-- CONTACT -->
    <div class="contact-section sr">
        <h2>Contact Us</h2>
        <div class="contact-cards">
            <a href="tel:9800865780" class="contact-card">
                <span class="c-icon">📞</span>
                <div class="c-info">
                    <span class="c-label">Phone</span>
                    <span class="c-value">9800865780</span>
                </div>
            </a>
            <a href="mailto:keshavv409@gmail.com" class="contact-card">
                <span class="c-icon">✉️</span>
                <div class="c-info">
                    <span class="c-label">Email</span>
                    <span class="c-value">keshavv409@gmail.com</span>
                </div>
            </a>
            <a href="https://www.facebook.com/raunak.razz.165" target="_blank" rel="noopener noreferrer" class="contact-card">
                <span class="c-icon">👤</span>
                <div class="c-info">
                    <span class="c-label">Facebook</span>
                    <span class="c-value">Keshav Verma</span>
                </div>
            </a>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="site-footer">
        <span>© 2026 BIT Portal · Developed by <a href="mailto:keshavv409@gmail.com">Keshav Verma</a></span>
    </footer>

</div><!-- /.app -->

<script>
/* cursor glow on feature cards */
document.querySelectorAll('[data-gc]').forEach(function(c) {
    c.addEventListener('mousemove', function(e) {
        var r = c.getBoundingClientRect();
        c.style.setProperty('--mx', ((e.clientX - r.left) / r.width * 100) + '%');
        c.style.setProperty('--my', ((e.clientY - r.top) / r.height * 100) + '%');
    });
});

/* scroll reveal */
(function() {
    var els = document.querySelectorAll('.sr');
    var io = new IntersectionObserver(function(entries) {
        entries.forEach(function(e, i) {
            if (e.isIntersecting) {
                setTimeout(function() { e.target.classList.add('in'); }, i * 60);
                io.unobserve(e.target);
            }
        });
    }, { threshold: .1 });
    els.forEach(function(el) { io.observe(el); });
})();
</script>
</body>
</html>