@extends('layouts.app')

@section('meta_title', 'Telling the Time — Clock Game for Kids')
@section('meta_blurb', 'A free telling the time game for kids — read an analogue clock face and pick the matching digital time.')
@section('meta_words', 'telling the time game, clock game for kids, read a clock, oclock half past quarter past, primary school maths, learn to tell the time')

@section('title', $category->name)

@push('styles')
    <style>
        .mult-wrap {
            max-width: 720px;
            margin: 0 auto;
        }

        #levelToggles {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem;
        }

        .choice-toggle {
            border-radius: 0.9rem;
            border: 2px solid #dee2e6;
            background: #fff;
            font-size: 1rem;
            font-weight: 700;
            color: #495057;
            padding: 0.6rem 1.25rem;
            transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        }

        .choice-toggle:hover {
            border-color: #adb5bd;
        }

        .choice-toggle.active {
            background: #3ecb8c;
            border-color: transparent;
            color: #fff;
            box-shadow: 0 4px 10px rgba(78, 124, 255, 0.35);
        }

        .mult-setup-card {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        }

        .start-btn {
            border-radius: 2rem;
            font-weight: 700;
            font-size: 1.15rem;
            padding: 0.75rem 1rem;
            background: #3ecb8c;
            border: none;
        }

        .start-btn:hover:not(:disabled),
        .start-btn:focus:not(:disabled) {
            background: #2fb377;
            color: #fff;
        }

        .start-btn:disabled {
            opacity: 0.5;
        }

        /* Game screen */
        .game-topbar {
            font-weight: 600;
        }

        .timer-track {
            height: 0.6rem;
            border-radius: 1rem;
            background: #e9ecef;
            overflow: hidden;
        }

        .timer-fill {
            height: 100%;
            background: linear-gradient(90deg, #3ecb8c, #2dc8e7);
            border-radius: 1rem;
            transition: width 0.1s linear, background 0.3s ease;
        }

        .timer-fill.timer-low {
            background: linear-gradient(90deg, #fd7e14, #dc3545);
        }

        .question-card {
            border: none;
            border-radius: 1.5rem;
            background: linear-gradient(160deg, #f0fbf6, #eef6ff);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
        }

        .target-badge {
            display: inline-block;
            font-size: 1.1rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            padding: 0.35rem 1rem;
            border-radius: 2rem;
            background: #3ecb8c;
            color: #fff;
            margin-bottom: 1rem;
        }

        .clock-svg-wrap {
            display: flex;
            justify-content: center;
        }

        .clock-svg-wrap svg {
            max-width: 100%;
            height: auto;
        }

        .answer-btn {
            border-radius: 1.1rem;
            border: 2px solid #dee2e6;
            background: #fff;
            font-size: 1.3rem;
            font-weight: 700;
            color: #343a40;
            padding: 1rem 0.5rem;
            min-height: 4.5rem;
            transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        }

        .answer-btn:hover:not(:disabled) {
            border-color: #adb5bd;
        }

        .answer-btn:disabled {
            opacity: 1;
        }

        .answer-btn.correct {
            background: linear-gradient(135deg, #40c057, #82c91e);
            border-color: transparent;
            color: #fff;
        }

        .answer-btn.wrong {
            background: linear-gradient(135deg, #fa5252, #e64980);
            border-color: transparent;
            color: #fff;
        }

        .feedback-banner {
            font-size: 1.15rem;
            font-weight: 700;
            border-radius: 1rem;
            padding: 0.75rem 1rem;
        }

        .feedback-banner.correct-banner {
            background: #d3f9d8;
            color: #2b8a3e;
        }

        .feedback-banner.wrong-banner {
            background: #ffe3e3;
            color: #c92a2a;
        }

        .continue-chevron-btn {
            flex-shrink: 0;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 50%;
            border: 2px solid currentColor;
            background: transparent;
            color: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: background 0.15s ease;
        }

        .continue-chevron-btn:hover {
            background: rgba(0, 0, 0, 0.08);
        }

        .question-card-wrap {
            position: relative;
        }

        .pause-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.94);
            border-radius: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 5;
        }

        .pause-overlay .bi {
            font-size: 2.5rem;
            color: #3ecb8c;
        }

        .hint-btn {
            border-radius: 2rem;
        }

        .hint-text {
            background: #fff8e1;
            border: 1px dashed #ffca28;
            border-radius: 0.75rem;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            color: #7a5b00;
            text-align: center;
        }

        /* Results screen */
        .results-card {
            border: none;
            border-radius: 1.5rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
        }

        .results-stars {
            font-size: 2.75rem;
            letter-spacing: 0.25rem;
        }

        .results-score {
            font-size: 3rem;
            font-weight: 800;
        }

        @keyframes pop-in {
            0% { transform: scale(0.85); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .pop-in {
            animation: pop-in 0.25s ease-out;
        }

        @media (max-width: 400px) {
            .answer-btn {
                font-size: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-xl pb-2">
        @include('partials.games-header', [
            'category' => $category,
            'game' => $game,
            'blurb' => 'Pick your time types, then read the clock!'
        ])

        <div class="mult-wrap">
            {{-- Setup screen --}}
            <div id="setupScreen">
                <div class="card mult-setup-card">
                    <div class="card-body p-4">
                        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-sliders"></i> Which time types?</h2>
                        <div class="mb-4" id="levelToggles">
                            <button type="button" class="choice-toggle active" data-level="oclock">O'clock</button>
                            <button type="button" class="choice-toggle active" data-level="half">Half past</button>
                            <button type="button" class="choice-toggle active" data-level="quarter">Quarter past/to</button>
                            <button type="button" class="choice-toggle active" data-level="five">5-minute intervals</button>
                        </div>

                        <div class="row g-4">
                            <div class="col-sm-6">
                                <h2 class="h6 text-uppercase text-secondary mb-2"><i class="bi bi-list-ol"></i> How many questions?</h2>
                                <select class="form-select" id="questionCountSelect">
                                    <option value="5">5 questions</option>
                                    <option value="10" selected>10 questions</option>
                                    <option value="15">15 questions</option>
                                    <option value="20">20 questions</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <h2 class="h6 text-uppercase text-secondary mb-2"><i class="bi bi-stopwatch"></i> Time per question?</h2>
                                <select class="form-select" id="timeLimitSelect">
                                    <option value="0">No time limit</option>
                                    <option value="10">10 seconds</option>
                                    <option value="15" selected>15 seconds</option>
                                    <option value="20">20 seconds</option>
                                    <option value="30">30 seconds</option>
                                </select>
                            </div>
                        </div>

                        <div id="setupError" class="alert alert-warning mt-4 mb-0 d-none" role="alert">
                            Pick at least one time type to play with.
                        </div>

                        <button type="button" class="btn start-btn text-white w-100 mt-4" id="startBtn">
                            <i class="bi bi-play-fill"></i> Start game
                        </button>
                    </div>
                </div>
            </div>

            {{-- Game screen --}}
            <div id="gameScreen" class="d-none">
                <div class="d-flex justify-content-between align-items-center mb-2 game-topbar">
                    <span id="questionProgress">Question 1 of 10</span>
                    <div class="d-flex align-items-center gap-3">
                        <span id="scoreDisplay"><i class="bi bi-star-fill text-warning"></i> Score: 0</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="pauseBtn" title="Pause timer">
                            <i class="bi bi-pause-fill"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="quitBtn" title="Quit game">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>

                <div class="timer-track mb-3" id="timerTrack">
                    <div class="timer-fill" id="timerFill" style="width: 100%;"></div>
                </div>

                <div class="question-card-wrap mb-3">
                    <div class="card question-card">
                        <div class="card-body p-4 text-center">
                            <span class="target-badge" id="targetBadge">READ THE CLOCK</span>
                            <div class="clock-svg-wrap" id="clockVisual"></div>
                        </div>
                    </div>
                    <div id="pauseOverlay" class="pause-overlay d-none">
                        <div class="text-center">
                            <i class="bi bi-pause-circle-fill d-block mb-2"></i>
                            <div class="fw-bold">Paused</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center align-items-center status-slot mb-3" id="hintRow">
                    <button type="button" class="btn btn-sm btn-outline-primary hint-btn" id="hintBtn">
                        <i class="bi bi-lightbulb"></i> Hint
                    </button>
                </div>
                <div id="hintText" class="hint-text mb-3 d-none"></div>

                <div id="feedbackBanner" class="feedback-banner status-slot mb-3 d-none d-flex align-items-center gap-3">
                    <span class="flex-grow-1 text-center" id="feedbackText"></span>
                    <button type="button" class="continue-chevron-btn d-none" id="continueBtn" title="Continue">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                <div class="row g-3" id="answerGrid"></div>
            </div>

            {{-- Results screen --}}
            <div id="resultsScreen" class="d-none">
                <div class="card results-card">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-3">Game over!</h2>
                        <div class="results-stars mb-2" id="resultsStars"></div>
                        <div class="results-score mb-1" id="resultsScore">0 / 0</div>
                        <p class="text-secondary mb-4" id="resultsMessage"></p>
                        <p class="text-secondary small mb-4" id="resultsStats"></p>

                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                            <button type="button" class="btn start-btn text-white" id="playAgainBtn">
                                <i class="bi bi-arrow-repeat"></i> Play again
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="changeSettingsBtn">
                                <i class="bi bi-sliders"></i> Change settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-center text-secondary small mt-4 mb-0">
            Runs entirely in your browser &mdash; no data is sent anywhere.
        </p>

        <div class="mult-wrap mt-5">
            <h2 class="h6 text-secondary text-uppercase mb-2">About this telling the time game</h2>
            <p class="text-secondary small">This free telling the time game helps young kids learn to read an analogue clock face and match it to the correct digital time. Choose which time types to include — o'clock, half past, quarter past/to, or any 5-minute interval — set your question count and time limit, then read as many clocks as you can. It's a simple, visual way to build a skill that's easy to practise but tricky to master.</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const GAME_SLUG = '{{ $game->slug }}';
            const setupScreen = document.getElementById('setupScreen');
            const gameScreen = document.getElementById('gameScreen');
            const resultsScreen = document.getElementById('resultsScreen');

            const levelToggles = Array.from(document.querySelectorAll('#levelToggles .choice-toggle'));
            const questionCountSelect = document.getElementById('questionCountSelect');
            const timeLimitSelect = document.getElementById('timeLimitSelect');
            const setupError = document.getElementById('setupError');
            const startBtn = document.getElementById('startBtn');
            const quitBtn = document.getElementById('quitBtn');

            const questionProgress = document.getElementById('questionProgress');
            const scoreDisplay = document.getElementById('scoreDisplay');
            const timerTrack = document.getElementById('timerTrack');
            const timerFill = document.getElementById('timerFill');
            const targetBadge = document.getElementById('targetBadge');
            const clockVisual = document.getElementById('clockVisual');
            const feedbackBanner = document.getElementById('feedbackBanner');
            const feedbackText = document.getElementById('feedbackText');
            const answerGrid = document.getElementById('answerGrid');
            const continueBtn = document.getElementById('continueBtn');
            const pauseBtn = document.getElementById('pauseBtn');
            const pauseOverlay = document.getElementById('pauseOverlay');
            const hintBtn = document.getElementById('hintBtn');
            const hintRow = document.getElementById('hintRow');
            const hintText = document.getElementById('hintText');

            const resultsStars = document.getElementById('resultsStars');
            const resultsScore = document.getElementById('resultsScore');
            const resultsMessage = document.getElementById('resultsMessage');
            const resultsStats = document.getElementById('resultsStats');
            const playAgainBtn = document.getElementById('playAgainBtn');
            const changeSettingsBtn = document.getElementById('changeSettingsBtn');

            let selectedLevels = new Set();
            let settings = { questionCount: 10, timeLimit: 15 };
            let session = null; // { questions, index, score, timerId, timeLeft, correctText }
            let paused = false;

            function getHint() {
                return 'The short hand shows the hour, the long hand shows the minutes — each number on the clock is 5 minutes.';
            }

            function loadSettings() {
                try {
                    const raw = localStorage.getItem('tellingTimeGame.settings');
                    if (!raw) return;
                    const saved = JSON.parse(raw);
                    if (Array.isArray(saved.levels)) {
                        saved.levels.forEach(function(l) {
                            if (l === 'oclock' || l === 'half' || l === 'quarter' || l === 'five') selectedLevels.add(l);
                        });
                    }
                    if (Number.isInteger(saved.questionCount)) settings.questionCount = saved.questionCount;
                    if (Number.isInteger(saved.timeLimit)) settings.timeLimit = saved.timeLimit;
                } catch (e) {
                    // ignore unavailable/malformed storage
                }
            }

            function saveSettings() {
                try {
                    localStorage.setItem('tellingTimeGame.settings', JSON.stringify({
                        levels: Array.from(selectedLevels),
                        questionCount: settings.questionCount,
                        timeLimit: settings.timeLimit,
                    }));
                } catch (e) {
                    // ignore unavailable storage
                }
            }

            function renderToggles() {
                levelToggles.forEach(function(btn) {
                    btn.classList.toggle('active', selectedLevels.has(btn.dataset.level));
                });
            }

            levelToggles.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const level = btn.dataset.level;
                    if (selectedLevels.has(level)) {
                        selectedLevels.delete(level);
                    } else {
                        selectedLevels.add(level);
                    }
                    setupError.classList.add('d-none');
                    renderToggles();
                });
            });

            questionCountSelect.addEventListener('change', function() {
                settings.questionCount = parseInt(questionCountSelect.value, 10);
            });

            timeLimitSelect.addEventListener('change', function() {
                settings.timeLimit = parseInt(timeLimitSelect.value, 10);
            });

            function randInt(min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }

            function playTone(frequency, duration, type) {
                try {
                    const AudioCtx = window.AudioContext || window.webkitAudioContext;
                    if (!AudioCtx) return;
                    const ctx = new AudioCtx();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = type || 'sine';
                    osc.frequency.value = frequency;
                    gain.gain.value = 0.08;
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start();
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + duration);
                    osc.stop(ctx.currentTime + duration);
                    osc.onended = function() { ctx.close(); };
                } catch (e) {
                    // audio unavailable — not fatal
                }
            }

            function playCorrectSound() {
                playTone(660, 0.12, 'triangle');
                setTimeout(function() { playTone(880, 0.15, 'triangle'); }, 100);
            }

            function playWrongSound() {
                playTone(220, 0.25, 'sawtooth');
            }

            function pad2(n) { return n < 10 ? '0' + n : String(n); }

            function buildQuestion(levels) {
                const level = levels[randInt(0, levels.length - 1)];
                const hour = randInt(1, 12);
                let minute;
                if (level === 'oclock') minute = 0;
                else if (level === 'half') minute = 30;
                else if (level === 'quarter') minute = Math.random() < 0.5 ? 15 : 45;
                else minute = randInt(0, 11) * 5; // 'five' — any 5-minute interval 0,5,...,55

                const correctText = hour + ':' + pad2(minute);
                return { level: level, hour: hour, minute: minute, correctText: correctText };
            }

            function buildQuestions(levels, count) {
                const levelList = Array.from(levels);
                const questions = [];
                let lastKey = null;
                for (let i = 0; i < count; i++) {
                    let q, key;
                    let attempts = 0;
                    do {
                        q = buildQuestion(levelList);
                        key = q.correctText;
                        attempts++;
                    } while (key === lastKey && attempts < 10);
                    lastKey = key;
                    questions.push(q);
                }
                return questions;
            }

            function buildChoices(q) {
                const correct = q.correctText;
                const choices = new Set([correct]);

                const candidates = [];
                // off-by-one hour (hour-hand misread)
                const nextHour = (q.hour % 12 === 0 ? 1 : q.hour + 1);
                const prevHour = (q.hour === 1 ? 12 : q.hour - 1);
                candidates.push(nextHour + ':' + pad2(q.minute));
                candidates.push(prevHour + ':' + pad2(q.minute));
                // quarter past/to confusion (mirrored minute)
                const mirroredMinute = (60 - q.minute) % 60;
                candidates.push(q.hour + ':' + pad2(mirroredMinute));
                // adjacent 5-minute values
                candidates.push(q.hour + ':' + pad2((q.minute + 5) % 60));
                candidates.push(q.hour + ':' + pad2((q.minute + 55) % 60));
                candidates.push(q.hour + ':' + pad2((q.minute + 10) % 60));

                let idx = 0;
                while (choices.size < 4 && idx < candidates.length) {
                    if (candidates[idx] !== correct) choices.add(candidates[idx]);
                    idx++;
                }

                // Fallback: small random hour/minute perturbations until we have 4 unique labels
                let guard = 0;
                while (choices.size < 4 && guard < 50) {
                    const dh = randInt(-2, 2);
                    const fiveStep = randInt(-3, 3) * 5;
                    let h = q.hour + dh;
                    if (h < 1) h += 12;
                    if (h > 12) h -= 12;
                    let m = (q.minute + fiveStep) % 60;
                    if (m < 0) m += 60;
                    const candidate = h + ':' + pad2(m);
                    if (candidate !== correct) choices.add(candidate);
                    guard++;
                }

                const arr = Array.from(choices);
                for (let i = arr.length - 1; i > 0; i--) {
                    const j = randInt(0, i);
                    [arr[i], arr[j]] = [arr[j], arr[i]];
                }
                return { correct: correct, choices: arr };
            }

            function pt(cx, cy, r, deg) {
                const rad = (deg - 90) * Math.PI / 180;
                return { x: cx + r * Math.cos(rad), y: cy + r * Math.sin(rad) };
            }

            function renderClock(hour, minute) {
                const cx = 110;
                const cy = 110;
                const r = 95;

                let svg = '<svg viewBox="0 0 220 220" width="260">';
                svg += '<circle cx="' + cx + '" cy="' + cy + '" r="' + r + '" fill="#eaf2ff" stroke="#3ecb8c" stroke-width="4" />';

                // tick marks
                for (let i = 0; i < 12; i++) {
                    const deg = i * 30;
                    const p1 = pt(cx, cy, 85, deg);
                    const p2 = pt(cx, cy, 95, deg);
                    svg += '<line x1="' + p1.x + '" y1="' + p1.y + '" x2="' + p2.x + '" y2="' + p2.y + '" stroke="#495057" stroke-width="2" />';
                }

                // hour numbers
                for (let n = 1; n <= 12; n++) {
                    const deg = n * 30;
                    const p = pt(cx, cy, 72, deg);
                    svg += '<text x="' + p.x + '" y="' + (p.y + 5) + '" text-anchor="middle" font-size="16" font-weight="700" fill="#343a40">' + n + '</text>';
                }

                const hourAngle = ((hour % 12) + minute / 60) * 30;
                const minuteAngle = minute * 6;
                const hourEnd = pt(cx, cy, 50, hourAngle);
                const minuteEnd = pt(cx, cy, 75, minuteAngle);

                svg += '<line x1="' + cx + '" y1="' + cy + '" x2="' + hourEnd.x + '" y2="' + hourEnd.y + '" stroke="#343a40" stroke-width="6" stroke-linecap="round" />';
                svg += '<line x1="' + cx + '" y1="' + cy + '" x2="' + minuteEnd.x + '" y2="' + minuteEnd.y + '" stroke="#343a40" stroke-width="4" stroke-linecap="round" />';
                svg += '<circle cx="' + cx + '" cy="' + cy + '" r="5" fill="#343a40" />';

                svg += '</svg>';

                clockVisual.innerHTML = svg;
            }

            function stopTimer() {
                if (session && session.timerId) {
                    clearInterval(session.timerId);
                    session.timerId = null;
                }
            }

            function tickTimer(limit) {
                session.timeLeft -= 0.1;
                const pct = Math.max(0, (session.timeLeft / limit) * 100);
                timerFill.style.width = pct + '%';
                if (pct < 25) timerFill.classList.add('timer-low');

                if (session.timeLeft <= 0) {
                    stopTimer();
                    handleTimeout();
                }
            }

            function startTimer() {
                const limit = settings.timeLimit;
                if (!limit) {
                    timerTrack.classList.add('d-none');
                    return;
                }
                timerTrack.classList.remove('d-none');
                session.timeLeft = limit;
                timerFill.style.width = '100%';
                timerFill.classList.remove('timer-low');
                session.timerId = setInterval(function() { tickTimer(limit); }, 100);
            }

            function resumeTimer() {
                const limit = settings.timeLimit;
                if (!limit || !session) return;
                session.timerId = setInterval(function() { tickTimer(limit); }, 100);
            }

            function togglePause() {
                if (!session || !settings.timeLimit || session.answered) return;
                paused = !paused;
                if (paused) {
                    session.pauseCount++;
                    stopTimer();
                    pauseOverlay.classList.remove('d-none');
                    lockAnswers();
                    pauseBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
                    pauseBtn.title = 'Resume';
                } else {
                    pauseOverlay.classList.add('d-none');
                    Array.from(answerGrid.querySelectorAll('.answer-btn')).forEach(function(btn) {
                        btn.disabled = false;
                    });
                    resumeTimer();
                    pauseBtn.innerHTML = '<i class="bi bi-pause-fill"></i>';
                    pauseBtn.title = 'Pause timer';
                }
            }

            function showQuestion() {
                const q = session.questions[session.index];
                session.answered = false;
                paused = false;
                pauseOverlay.classList.add('d-none');
                hintText.classList.add('d-none');
                hintText.textContent = '';
                hintRow.classList.remove('d-none');
                if (settings.timeLimit) {
                    pauseBtn.classList.remove('d-none');
                    pauseBtn.disabled = false;
                    pauseBtn.innerHTML = '<i class="bi bi-pause-fill"></i>';
                    pauseBtn.title = 'Pause timer';
                } else {
                    pauseBtn.classList.add('d-none');
                }

                questionProgress.textContent = 'Question ' + (session.index + 1) + ' of ' + session.questions.length;
                scoreDisplay.innerHTML = '<i class="bi bi-star-fill text-warning"></i> Score: ' + session.score;

                targetBadge.textContent = 'READ THE CLOCK';
                renderClock(q.hour, q.minute);

                feedbackBanner.classList.add('d-none');
                feedbackBanner.classList.remove('correct-banner', 'wrong-banner');
                continueBtn.classList.add('d-none');

                const built = buildChoices(q);
                session.correctText = built.correct;
                answerGrid.innerHTML = '';
                built.choices.forEach(function(choice) {
                    const col = document.createElement('div');
                    col.className = 'col-6';
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'answer-btn w-100 pop-in';
                    btn.textContent = choice;
                    btn.addEventListener('click', function() { handleAnswer(choice, btn); });
                    col.appendChild(btn);
                    answerGrid.appendChild(col);
                });

                startTimer();
            }

            function lockAnswers() {
                Array.from(answerGrid.querySelectorAll('.answer-btn')).forEach(function(btn) {
                    btn.disabled = true;
                });
            }

            function revealCorrect(correctText, chosenBtn) {
                Array.from(answerGrid.querySelectorAll('.answer-btn')).forEach(function(btn) {
                    if (btn.textContent === correctText) {
                        btn.classList.add('correct');
                    } else if (btn === chosenBtn) {
                        btn.classList.add('wrong');
                    }
                });
            }

            function handleAnswer(choice, btn) {
                stopTimer();
                session.answered = true;
                pauseBtn.classList.add('d-none');
                hintRow.classList.add('d-none');
                lockAnswers();
                const q = session.questions[session.index];
                const isCorrect = choice === session.correctText;

                revealCorrect(session.correctText, isCorrect ? null : btn);

                feedbackBanner.classList.remove('d-none');
                if (isCorrect) {
                    session.score++;
                    feedbackText.textContent = pickPraise();
                    feedbackBanner.classList.add('correct-banner');
                    playCorrectSound();
                } else {
                    feedbackText.textContent = 'The time shown is ' + q.correctText + '.';
                    feedbackBanner.classList.add('wrong-banner');
                    playWrongSound();
                }

                scoreDisplay.innerHTML = '<i class="bi bi-star-fill text-warning"></i> Score: ' + session.score;

                if (isCorrect) {
                    setTimeout(nextQuestion, 1500);
                } else {
                    continueBtn.classList.remove('d-none');
                }
            }

            function handleTimeout() {
                session.answered = true;
                pauseBtn.classList.add('d-none');
                hintRow.classList.add('d-none');
                lockAnswers();
                const q = session.questions[session.index];
                revealCorrect(session.correctText, null);
                feedbackBanner.classList.remove('d-none');
                feedbackText.textContent = "Time's up! The time shown is " + q.correctText + '.';
                feedbackBanner.classList.add('wrong-banner');
                playWrongSound();
                continueBtn.classList.remove('d-none');
            }

            function pickPraise() {
                const options = ['Nice one! 🎉', 'Correct! 🌟', 'Great job! 👏', "You've got it! 🚀", 'Brilliant! ⭐'];
                return options[randInt(0, options.length - 1)];
            }

            function nextQuestion() {
                session.index++;
                if (session.index >= session.questions.length) {
                    showResults();
                    return;
                }
                showQuestion();
            }

            function showResults() {
                gameScreen.classList.add('d-none');
                resultsScreen.classList.remove('d-none');

                const total = session.questions.length;
                const score = session.score;
                const pct = total > 0 ? (score / total) : 0;

                if (window.InvencueResults) {
                    window.InvencueResults.submit('maths', GAME_SLUG, score, total);
                }

                resultsScore.textContent = score + ' / ' + total;

                let stars = 1;
                if (pct >= 0.9) stars = 3;
                else if (pct >= 0.6) stars = 2;
                resultsStars.textContent = '⭐'.repeat(stars) + '☆'.repeat(3 - stars);

                let message = 'Keep practising — you\'ll get there!';
                if (pct >= 0.9) message = "Amazing! You're a clock-reading superstar!";
                else if (pct >= 0.6) message = 'Well done — great effort!';

                resultsMessage.textContent = message;
                resultsStats.textContent = 'Paused ' + session.pauseCount + ' time' + (session.pauseCount === 1 ? '' : 's') + ' · Used ' + session.hintCount + ' hint' + (session.hintCount === 1 ? '' : 's') + '.';
            }

            function startGame() {
                if (selectedLevels.size === 0) {
                    setupError.classList.remove('d-none');
                    return;
                }

                saveSettings();

                session = {
                    questions: buildQuestions(selectedLevels, settings.questionCount),
                    index: 0,
                    score: 0,
                    timerId: null,
                    timeLeft: 0,
                    pauseCount: 0,
                    hintCount: 0,
                    correctText: '',
                };

                setupScreen.classList.add('d-none');
                resultsScreen.classList.add('d-none');
                gameScreen.classList.remove('d-none');

                showQuestion();
            }

            startBtn.addEventListener('click', startGame);

            playAgainBtn.addEventListener('click', function() {
                resultsScreen.classList.add('d-none');
                startGame();
            });

            changeSettingsBtn.addEventListener('click', function() {
                resultsScreen.classList.add('d-none');
                setupScreen.classList.remove('d-none');
            });

            quitBtn.addEventListener('click', function() {
                stopTimer();
                paused = false;
                pauseOverlay.classList.add('d-none');
                gameScreen.classList.add('d-none');
                setupScreen.classList.remove('d-none');
            });

            continueBtn.addEventListener('click', function() {
                continueBtn.classList.add('d-none');
                nextQuestion();
            });

            pauseBtn.addEventListener('click', togglePause);

            hintBtn.addEventListener('click', function() {
                session.hintCount++;
                hintText.textContent = getHint(session.questions[session.index]);
                hintText.classList.remove('d-none');
            });

            loadSettings();
            if (selectedLevels.size === 0) {
                selectedLevels = new Set(['oclock', 'half']);
            }
            questionCountSelect.value = String(settings.questionCount);
            timeLimitSelect.value = String(settings.timeLimit);
            renderToggles();
        })();
    </script>
@endpush
