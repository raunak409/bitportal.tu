// ── Navigation ──────────────────────────────────
function showSection(name) {
    const sections = ['syllabus', 'notes', 'quiz', 'notice', 'pqs', 'profile', 'about'];
    sections.forEach(s => {
        const el  = document.getElementById(`section-${s}`);
        const nav = document.getElementById(`nav-${s}`);
        if (el)  el.style.display  = (s === name) ? 'block' : 'none';
        if (nav) nav.classList.toggle('active', s === name);
    });

    // ── Notification: clear badge when Notice tab clicked ──
    if (name === 'notice') {
        const badge = document.getElementById('notif-badge');
        if (badge) badge.classList.add('hidden');
        fetch('portal.php?seen_notices=1');
    }
}

// ── Load Syllabus ───────────────────────────────
function loadSyllabus(sem) {
    document.querySelectorAll('#section-syllabus .sem-tabs button').forEach(function(b, i) {
        b.classList.toggle('active', i + 1 === sem);
    });
    const title = document.getElementById('sem-title');
    const list  = document.getElementById('subject-list');
    if (!title || !list) return;

    title.innerText = `Semester ${sem} Subjects`;
    list.innerHTML  = '';

    const subjects = syllabusData[sem] || [];

    if (subjects.length === 0) {
        list.innerHTML = `<p style="color:var(--text-muted);text-align:center;padding:20px;">No subjects added for Semester ${sem} yet.</p>`;
        return;
    }

    subjects.forEach(subject => {
        const item = document.createElement('div');
        item.className = 'subject-item';

        let pdfBtn = '';
        if (subject.filename) {
            pdfBtn = `
                <a href="uploads/syllabus/${encodeURIComponent(subject.filename)}" target="_blank" class="view-btn">📄 View</a>
                <a href="uploads/syllabus/${encodeURIComponent(subject.filename)}" download class="dl-btn">Download</a>
            `;
        }

        item.innerHTML = `
            <span>
                <strong>${subject.subject_name}</strong>
                ${subject.credit ? `<small style="background:#334155;color:#94a3b8;padding:2px 8px;border-radius:4px;margin-left:8px;">${subject.credit} Credit</small>` : ''}
            </span>
            <div>${pdfBtn}</div>
        `;
        list.appendChild(item);
    });
}

// ── Search Subjects ─────────────────────────────
function searchSubject() {
    const input = document.getElementById('searchBar').value.toLowerCase();
    const list  = document.getElementById('subject-list');
    const title = document.getElementById('sem-title');

    if (!input) return;

    list.innerHTML  = '';
    title.innerText = `Search results for: "${input}"`;

    let found = false;
    Object.values(syllabusData).forEach(subjects => {
        subjects.forEach(subject => {
            if (subject.subject_name.toLowerCase().includes(input)) {
                found = true;
                const item = document.createElement('div');
                item.className = 'subject-item';

                let pdfBtn = '';
                if (subject.filename) {
                    pdfBtn = `
                        <a href="uploads/syllabus/${encodeURIComponent(subject.filename)}" target="_blank" class="view-btn">📄 View</a>
                        <a href="uploads/syllabus/${encodeURIComponent(subject.filename)}" download class="dl-btn">Download</a>
                    `;
                }

                item.innerHTML = `
                    <span>
                        <strong>${subject.subject_name}</strong>
                        <small style="background:#334155;color:#94a3b8;padding:2px 8px;border-radius:4px;margin-left:8px;">Sem ${subject.semester}</small>
                        ${subject.credit ? `<small style="background:#334155;color:#94a3b8;padding:2px 8px;border-radius:4px;margin-left:4px;">${subject.credit} Credit</small>` : ''}
                    </span>
                    <div>${pdfBtn}</div>
                `;
                list.appendChild(item);
            }
        });
    });

    if (!found) {
        list.innerHTML = `<p style="color:var(--text-muted);text-align:center;padding:20px;">No subjects found matching "${input}".</p>`;
    }
}

// ── Load Notes ──────────────────────────────────
function loadNotes(sem) {
    document.querySelectorAll('#section-notes .sem-tabs button').forEach(function(b, i) {
        b.classList.toggle('active', i + 1 === sem);
    });
    const notesList  = document.getElementById('notes-list');
    const notesTitle = document.getElementById('notes-title');
    if (!notesList || !notesTitle) return;

    notesTitle.innerText = `Semester ${sem} Notes`;
    notesList.innerHTML  = '';

    const semNotes = notesData[sem] || [];

    if (semNotes.length === 0) {
        notesList.innerHTML = `<p style="color:var(--text-muted);text-align:center;padding:20px;">No notes uploaded for Semester ${sem} yet.</p>`;
        return;
    }

    semNotes.forEach(note => {
        const item = document.createElement('div');
        item.className = 'subject-item';
        item.innerHTML = `
            <span><strong>${note.title}</strong></span>
            <div>
                <a href="uploads/notes/${encodeURIComponent(note.filename)}" class="view-btn" target="_blank">View</a>
                <a href="uploads/notes/${encodeURIComponent(note.filename)}" download class="dl-btn">Download</a>
            </div>
        `;
        notesList.appendChild(item);
    });
}


// ── Load Past Question Solutions ────────────────
function loadPQS(sem) {
    const pqsList  = document.getElementById('pqs-list');
    const pqsTitle = document.getElementById('pqs-title');
    if (!pqsList || !pqsTitle) return;

    pqsTitle.innerText = `Semester ${sem} Past Question Solutions`;
    pqsList.innerHTML  = '';

    // Update active tab
    document.querySelectorAll('#section-pqs .sem-tabs button').forEach((btn, i) => {
        btn.classList.toggle('active', i + 1 === sem);
    });

    const semPQS = (typeof pqData !== 'undefined' && pqData[sem]) ? pqData[sem] : [];

    if (semPQS.length === 0) {
        pqsList.innerHTML = `<p style="color:var(--text-muted);text-align:center;padding:20px;">No past question solutions uploaded for Semester ${sem} yet.</p>`;
        return;
    }

    semPQS.forEach(pq => {
        const item = document.createElement('div');
        item.className = 'subject-item';
        item.innerHTML = `
            <span><strong>${pq.title}</strong></span>
            <div>
                <a href="uploads/pqs/${encodeURIComponent(pq.filename)}" class="view-btn" target="_blank">📄 View</a>
                <a href="uploads/pqs/${encodeURIComponent(pq.filename)}" download class="dl-btn">Download</a>
            </div>
        `;
        pqsList.appendChild(item);
    });
}

// ── Quiz ────────────────────────────────────────
const hardcodedQuestions = [
    {
        question: 'Which of the following is the fastest memory in a computer system?',
        answers: [
            { text: 'RAM',          correct: false },
            { text: 'Cache Memory', correct: true  },
            { text: 'Hard Disk',    correct: false },
            { text: 'Optical Disk', correct: false },
        ],
    },
    {
        question: 'The process of starting or restarting a computer is called:',
        answers: [
            { text: 'Loading',    correct: false },
            { text: 'Booting',    correct: true  },
            { text: 'Formatting', correct: false },
            { text: 'Processing', correct: false },
        ],
    },
    {
        question: 'Which protocol is used for sending emails?',
        answers: [
            { text: 'HTTP', correct: false },
            { text: 'FTP',  correct: false },
            { text: 'SMTP', correct: true  },
            { text: 'IP',   correct: false },
        ],
    },
    {
        question: 'What is the binary equivalent of decimal 10?',
        answers: [
            { text: '1010', correct: true  },
            { text: '1100', correct: false },
            { text: '1001', correct: false },
            { text: '1111', correct: false },
        ],
    },
    {
        question: 'Who is the father of WWW?',
        answers: [
            { text: 'Charles Babbage', correct: false },
            { text: 'Tim Berners-Lee', correct: true  },
            { text: 'Bill Gates',      correct: false },
            { text: 'Steve Jobs',      correct: false },
        ],
    }
];

// Use DB questions if any exist, otherwise fall back to hardcoded
const questions = (typeof dbQuestions !== 'undefined' && dbQuestions.length > 0)
    ? dbQuestions
    : hardcodedQuestions;

const questionElement = document.getElementById('question');
const answerButtons   = document.getElementById('answer-btns');
const nextButton      = document.getElementById('next-btn');
const progressFill    = document.getElementById('progress-fill');
const questionStats   = document.getElementById('question-stats');

const correctSound = new Audio('quick-ting.mp3');
const wrongSound   = new Audio('ceeday-huh-sound-effect.mp3'); // ← was missing, fixed

let currentQuestionIndex = 0;
let score             = 0;
let shuffledQuestions = [];
let timeLeft          = 10;
let timerInterval     = null;

const timerEl = document.createElement('div');
timerEl.id = 'timer-display';
if (answerButtons) answerButtons.parentNode.insertBefore(timerEl, answerButtons);

function startTimer() {
    clearInterval(timerInterval);
    timeLeft = 10;
    updateTimerUI();
    timerInterval = setInterval(() => {
        timeLeft--;
        updateTimerUI();
        if (timeLeft <= 0) { clearInterval(timerInterval); timeUp(); }
    }, 1000);
}

function updateTimerUI() {
    if (timerEl) {
        timerEl.textContent      = `⏱ ${timeLeft}s`;
        timerEl.style.fontWeight = 'bold';
        timerEl.style.color      = timeLeft <= 3 ? 'red' : 'white';
    }
}

function stopTimer() { clearInterval(timerInterval); }

function timeUp() {
    Array.from(answerButtons.children).forEach(btn => {
        if (btn.dataset.correct === 'true') btn.classList.add('correct');
        btn.disabled = true;
    });
    nextButton.style.display = 'block';
}

function startQuiz() {
    shuffledQuestions    = [...questions].sort(() => Math.random() - 0.5);
    currentQuestionIndex = 0;
    score                = 0;
    if (nextButton) nextButton.innerHTML = 'Next Question';
    showQuestion();
}

function showQuestion() {
    resetState();
    const currentQuestion = shuffledQuestions[currentQuestionIndex];
    const questionNo      = currentQuestionIndex + 1;
    if (questionElement) questionElement.innerHTML = `${questionNo}. ${currentQuestion.question}`;
    if (questionStats)   questionStats.innerHTML   = `Question ${questionNo} of ${shuffledQuestions.length}`;
    if (progressFill)    progressFill.style.width  = `${(questionNo / shuffledQuestions.length) * 100}%`;
    currentQuestion.answers.forEach(answer => {
        const button = document.createElement('button');
        button.innerHTML = answer.text;
        button.classList.add('btn');
        if (answer.correct) button.dataset.correct = answer.correct;
        button.addEventListener('click', selectAnswer);
        if (answerButtons) answerButtons.appendChild(button);
    });
    startTimer();
}

function resetState() {
    stopTimer();
    if (nextButton) nextButton.style.display = 'none';
    if (timerEl)    timerEl.textContent       = '';
    while (answerButtons && answerButtons.firstChild) {
        answerButtons.removeChild(answerButtons.firstChild);
    }
}

function selectAnswer(e) {
    stopTimer();
    const selectedBtn = e.target;
    const isCorrect   = selectedBtn.dataset.correct === 'true';
    if (isCorrect) {
        selectedBtn.classList.add('correct');
        score++;
        correctSound.play().catch(() => {});
    } else {
        selectedBtn.classList.add('incorrect');
        wrongSound.play().catch(() => {});
    }
    Array.from(answerButtons.children).forEach(btn => {
        if (btn.dataset.correct === 'true') btn.classList.add('correct');
        btn.disabled = true;
    });
    if (nextButton) nextButton.style.display = 'block';
}

function showScore() {
    resetState();
    const percentage = Math.round((score / shuffledQuestions.length) * 100);
    if (questionElement) questionElement.innerHTML = `🎉 You scored ${score} out of ${shuffledQuestions.length} (${percentage}%)`;
    if (questionStats)   questionStats.innerHTML   = 'Quiz Completed';
    if (nextButton) {
        nextButton.innerHTML     = 'Restart Quiz';
        nextButton.style.display = 'block';
    }
}

function handleNextButton() {
    currentQuestionIndex++;
    if (currentQuestionIndex < shuffledQuestions.length) showQuestion();
    else showScore();
}

if (nextButton) {
    nextButton.addEventListener('click', () => {
        if (currentQuestionIndex < shuffledQuestions.length) handleNextButton();
        else startQuiz();
    });
}

if (document.getElementById('section-quiz')) startQuiz();