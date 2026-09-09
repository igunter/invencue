@extends('layouts.app')

@section('meta_title', 'Quadratic Equations — GCSE Maths Game')
@section('meta_blurb', 'A free quadratic equations game for GCSE-level practice — factorise and solve equations of the form x² + bx + c = 0.')
@section('meta_words', 'quadratic equations game, factorising quadratics, solve quadratic equation, gcse maths game, learn quadratics')

@section('title', $category->name)

@push('styles')
    <style>
        .mult-wrap {
            max-width: 720px;
            margin: 0 auto;
        }

        #tableToggles {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 0.5rem;
        }

        @media (min-width: 576px) {
            #tableToggles {
                grid-template-columns: repeat(12, 1fr);
            }
        }

        .table-toggle {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 0.9rem;
            border: 2px solid #dee2e6;
            background: #fff;
            font-size: 1.25rem;
            font-weight: 700;
            color: #495057;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        }

        .table-toggle:hover {
            border-color: #adb5bd;
        }

        .table-toggle.active {
            background: #7048e8;
            border-color: transparent;
            color: #fff;
            box-shadow: 0 4px 10px rgba(112, 72, 232, 0.35);
        }

        #typeToggles {
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
            background: #7048e8;
            border-color: transparent;
            color: #fff;
            box-shadow: 0 4px 10px rgba(112, 72, 232, 0.35);
        }

        .quick-pick-btn {
            border-radius: 2rem;
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
            background: #7048e8;
            border: none;
        }

        .start-btn:hover:not(:disabled),
        .start-btn:focus:not(:disabled) {
            background: #5f3dc4;
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
            background: linear-gradient(90deg, #20c997, #0dcaf0);
            border-radius: 1rem;
            transition: width 0.1s linear, background 0.3s ease;
        }

        .timer-fill.timer-low {
            background: linear-gradient(90deg, #fd7e14, #dc3545);
        }

        .question-card {
            border: none;
            border-radius: 1.5rem;
            background: linear-gradient(160deg, #fff9eb, #fff1e6);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
        }

        .equation-display {
            font-size: 2.25rem;
            font-weight: 800;
            color: #343a40;
            letter-spacing: 0.02em;
        }

        .answer-btn {
            border-radius: 1.1rem;
            border: 2px solid #dee2e6;
            background: #fff;
            font-size: 1.75rem;
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
            font-size: 1.4rem;
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
            color: #7048e8;
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
            .equation-display {
                font-size: 1.6rem;
            }

            .answer-btn {
                font-size: 1.4rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-xl pb-2">
        @include('partials.games-header', [
            'category' => $category,
            'game' => $game,
            'blurb' => 'Factorise each equation, then find both values of x!'
        ])

        <div class="mult-wrap">
            {{-- Setup screen --}}
            <div id="setupScreen">
                <div class="card mult-setup-card">
                    <div class="card-body p-4">
                        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-list-check"></i> Which root types?</h2>
                        <div class="mb-4" id="typeToggles">
                            <button type="button" class="choice-toggle active" data-type="positive">Positive roots only</button>
                            <button type="button" class="choice-toggle active" data-type="mixed">Include negative roots</button>
                        </div>

                        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-grid-3x3-gap"></i> Which root values?</h2>
                        <div class="mb-2" id="tableToggles">
                            @for ($i = 1; $i <= 9; $i++)
                                <button type="button" class="table-toggle" data-table="{{ $i }}">{{ $i }}</button>
                            @endfor
                        </div>

                        <div class="d-flex flex-wrap justify-content-center gap-2 my-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-pick-btn" id="pickAllBtn">Select all</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-pick-btn" id="pickNoneBtn">Clear</button>
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
                                    <option value="15">15 seconds</option>
                                    <option value="20">20 seconds</option>
                                    <option value="30" selected>30 seconds</option>
                                    <option value="45">45 seconds</option>
                                    <option value="60">60 seconds</option>
                                </select>
                            </div>
                        </div>

                        <div id="setupError" class="alert alert-warning mt-4 mb-0 d-none" role="alert">
                            Pick at least one root type and one root value to play with.
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
                            <div class="equation-display" id="equationText">x² − 7x + 12 = 0</div>
                            <div class="text-secondary mt-2">Solve for x</div>
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

                <div class="row g-2" id="answerGrid"></div>
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
            <h2 class="h6 text-secondary text-uppercase mb-2">About this quadratic equations game</h2>
            <p class="text-secondary small">This free quadratic equations game gives GCSE-level students practice factorising and solving equations of the form x² + bx + c = 0 to find both roots. Choose whether roots can be negative, pick your root-value range, set the question count and time limit, then solve as many as you can. It's a solid way to build speed and confidence with factorising ahead of exams.</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const setupScreen = document.getElementById('setupScreen');
            const gameScreen = document.getElementById('gameScreen');
            const resultsScreen = document.getElementById('resultsScreen');

            const typeToggles = Array.from(document.querySelectorAll('#typeToggles .choice-toggle'));
            const tableToggles = Array.from(document.querySelectorAll('.table-toggle'));
            const pickAllBtn = document.getElementById('pickAllBtn');
            const pickNoneBtn = document.getElementById('pickNoneBtn');
            const questionCountSelect = document.getElementById('questionCountSelect');
            const timeLimitSelect = document.getElementById('timeLimitSelect');
            const setupError = document.getElementById('setupError');
            const startBtn = document.getElementById('startBtn');
            const quitBtn = document.getElementById('quitBtn');

            const questionProgress = document.getElementById('questionProgress');
            const scoreDisplay = document.getElementById('scoreDisplay');
            const timerTrack = document.getElementById('timerTrack');
            const timerFill = document.getElementById('timerFill');
            const equationText = document.getElementById('equationText');
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

            let selectedTypes = new Set();
            let selectedNumbers = new Set();
            let settings = { questionCount: 10, timeLimit: 30 };
            let session = null; // { questions, index, score, timerId, timeLeft, answered, correctText }
            let paused = false;

            function getHint(q) {
                if (q.type === 'positive') return 'Both brackets will have a minus sign: find two positive numbers that multiply to give c and add to give b.';
                return 'Find two numbers that multiply to give c and add to give b — remember they can be negative.';
            }

            function loadSettings() {
                try {
                    const raw = localStorage.getItem('quadraticEquationsGame.settings');
                    if (!raw) return;
                    const saved = JSON.parse(raw);
                    if (Array.isArray(saved.numbers)) {
                        saved.numbers.forEach(function(n) {
                            if (Number.isInteger(n) && n >= 1 && n <= 9) selectedNumbers.add(n);
                        });
                    }
                    if (Array.isArray(saved.types)) {
                        saved.types.forEach(function(t) {
                            if (t === 'positive' || t === 'mixed') selectedTypes.add(t);
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
                    localStorage.setItem('quadraticEquationsGame.settings', JSON.stringify({
                        numbers: Array.from(selectedNumbers),
                        types: Array.from(selectedTypes),
                        questionCount: settings.questionCount,
                        timeLimit: settings.timeLimit,
                    }));
                } catch (e) {
                    // ignore unavailable storage
                }
            }

            function renderToggles() {
                tableToggles.forEach(function(btn) {
                    const t = parseInt(btn.dataset.table, 10);
                    btn.classList.toggle('active', selectedNumbers.has(t));
                });
                typeToggles.forEach(function(btn) {
                    btn.classList.toggle('active', selectedTypes.has(btn.dataset.type));
                });
            }

            tableToggles.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const t = parseInt(btn.dataset.table, 10);
                    if (selectedNumbers.has(t)) {
                        selectedNumbers.delete(t);
                    } else {
                        selectedNumbers.add(t);
                    }
                    setupError.classList.add('d-none');
                    renderToggles();
                });
            });

            typeToggles.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const type = btn.dataset.type;
                    if (selectedTypes.has(type)) {
                        selectedTypes.delete(type);
                    } else {
                        selectedTypes.add(type);
                    }
                    setupError.classList.add('d-none');
                    renderToggles();
                });
            });

            pickAllBtn.addEventListener('click', function() {
                selectedNumbers = new Set([1, 2, 3, 4, 5, 6, 7, 8, 9]);
                setupError.classList.add('d-none');
                renderToggles();
            });

            pickNoneBtn.addEventListener('click', function() {
                selectedNumbers = new Set();
                renderToggles();
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

            function pickFrom(set) {
                const arr = Array.from(set);
                return arr[randInt(0, arr.length - 1)];
            }

            function bracketText(r) {
                return r >= 0 ? '(x − ' + r + ')' : '(x + ' + Math.abs(r) + ')';
            }

            // Builds one solvable quadratic. Every returned question has two
            // exact integer roots — r1 <= r2 — such that (x - r1)(x - r2) = 0
            // expands to x^2 + bx + c = 0 with b = -(r1+r2), c = r1*r2.
            function buildQuestion(types, numbers) {
                const type = types[randInt(0, types.length - 1)];
                let r1, r2;
                if (type === 'positive') {
                    r1 = pickFrom(numbers);
                    r2 = pickFrom(numbers);
                } else {
                    r1 = pickFrom(numbers) * (Math.random() < 0.5 ? 1 : -1);
                    r2 = pickFrom(numbers) * (Math.random() < 0.5 ? 1 : -1);
                }
                const b = -(r1 + r2);
                const c = r1 * r2;

                let bText;
                if (b === 0) bText = '';
                else if (b === 1) bText = ' + x';
                else if (b === -1) bText = ' − x';
                else if (b > 0) bText = ' + ' + b + 'x';
                else bText = ' − ' + Math.abs(b) + 'x';

                const cText = c >= 0 ? ' + ' + c : ' − ' + Math.abs(c);
                const display = 'x²' + bText + cText + ' = 0';

                const lo = Math.min(r1, r2), hi = Math.max(r1, r2);
                const correctText = 'x = ' + lo + ' or x = ' + hi;

                return { type: type, r1: lo, r2: hi, display: display, correctText: correctText };
            }

            function buildQuestions(types, numbers, count) {
                const typeList = Array.from(types);
                const numberList = Array.from(numbers);
                const questions = [];
                let lastKey = null;
                for (let i = 0; i < count; i++) {
                    let q;
                    let attempts = 0;
                    do {
                        q = buildQuestion(typeList, numberList);
                        attempts++;
                    } while (q.display === lastKey && attempts < 10);
                    lastKey = q.display;
                    questions.push(q);
                }
                return questions;
            }

            function answerLabel(a, b) {
                return 'x = ' + a + ' or x = ' + b;
            }

            function buildChoices(q) {
                const correct = q.correctText;
                const choices = new Set([correct]);

                const candidates = [
                    answerLabel(q.r1 + 1, q.r2),
                    answerLabel(q.r1 - 1, q.r2),
                    answerLabel(q.r1, q.r2 + 1),
                    answerLabel(q.r1, q.r2 - 1),
                    answerLabel(-q.r1, q.r2),
                    answerLabel(q.r1, -q.r2),
                    answerLabel(q.r1 + 2, q.r2),
                    answerLabel(q.r1, q.r2 + 2),
                    answerLabel(q.r1 - 1, q.r2 + 1),
                    answerLabel(q.r1 + 1, q.r2 - 1),
                ];

                let idx = 0;
                while (choices.size < 4 && idx < candidates.length) {
                    if (candidates[idx] !== correct) choices.add(candidates[idx]);
                    idx++;
                }

                // Fallback: keep nudging random offsets until we have 4 unique labels
                let guard = 0;
                while (choices.size < 4 && guard < 50) {
                    const dr1 = randInt(-3, 3);
                    const dr2 = randInt(-3, 3);
                    if (dr1 !== 0 || dr2 !== 0) {
                        const candidate = answerLabel(q.r1 + dr1, q.r2 + dr2);
                        if (candidate !== correct) choices.add(candidate);
                    }
                    guard++;
                }

                const arr = Array.from(choices);
                for (let i = arr.length - 1; i > 0; i--) {
                    const j = randInt(0, i);
                    [arr[i], arr[j]] = [arr[j], arr[i]];
                }
                return { correct: correct, choices: arr };
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

                equationText.textContent = q.display;

                feedbackBanner.classList.add('d-none');
                feedbackBanner.classList.remove('correct-banner', 'wrong-banner');
                continueBtn.classList.add('d-none');

                const built = buildChoices(q);
                session.correctText = built.correct;
                answerGrid.innerHTML = '';
                built.choices.forEach(function(choice) {
                    const col = document.createElement('div');
                    col.className = 'col-12';
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
                    feedbackText.textContent = bracketText(q.r1) + bracketText(q.r2) + ' = 0, so ' + q.correctText;
                    feedbackBanner.classList.add('wrong-banner');
                    playWrongSound();
                }

                scoreDisplay.innerHTML = '<i class="bi bi-star-fill text-warning"></i> Score: ' + session.score;

                if (isCorrect) {
                    setTimeout(nextQuestion, 1300);
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
                feedbackText.textContent = "Time's up! " + bracketText(q.r1) + bracketText(q.r2) + ' = 0, so ' + q.correctText;
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

                resultsScore.textContent = score + ' / ' + total;

                let stars = 1;
                if (pct >= 0.9) stars = 3;
                else if (pct >= 0.6) stars = 2;
                resultsStars.textContent = '⭐'.repeat(stars) + '☆'.repeat(3 - stars);

                let message = 'Keep practising — you\'ll get there!';
                if (pct >= 0.9) message = "Amazing! You're a quadratics superstar!";
                else if (pct >= 0.6) message = 'Well done — great effort!';

                resultsMessage.textContent = message;
                resultsStats.textContent = 'Paused ' + session.pauseCount + ' time' + (session.pauseCount === 1 ? '' : 's') + ' · Used ' + session.hintCount + ' hint' + (session.hintCount === 1 ? '' : 's') + '.';
            }

            function startGame() {
                if (selectedTypes.size === 0 || selectedNumbers.size === 0) {
                    setupError.classList.remove('d-none');
                    return;
                }

                saveSettings();

                session = {
                    questions: buildQuestions(selectedTypes, selectedNumbers, settings.questionCount),
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
            if (selectedNumbers.size === 0) {
                selectedNumbers = new Set([1, 2, 3, 4, 5, 6]);
            }
            if (selectedTypes.size === 0) {
                selectedTypes = new Set(['positive', 'mixed']);
            }
            questionCountSelect.value = String(settings.questionCount);
            timeLimitSelect.value = String(settings.timeLimit);
            renderToggles();
        })();
    </script>
@endpush
