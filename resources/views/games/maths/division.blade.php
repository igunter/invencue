@extends('layouts.app')

@section('meta_title', 'Divide — Division Game for Kids')
@section('meta_blurb', 'A free division game for primary school kids — pick the numbers to divide by, how many questions, and how much time per question.')
@section('meta_words', 'division game, division game for kids, maths game, primary school maths, learn division')

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
            transition: transform 0.1s ease, background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        }

        .table-toggle:hover {
            border-color: #adb5bd;
        }

        .table-toggle.active {
            background: #3ecb8c;
            border-color: transparent;
            color: #fff;
            box-shadow: 0 4px 10px rgba(78, 124, 255, 0.35);
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
            background: linear-gradient(160deg, #eef6ff, #eafaf3);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
        }

        .num-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 4.5rem;
            height: 4.5rem;
            padding: 0 0.5rem;
            border-radius: 1.1rem;
            font-size: 2.5rem;
            font-weight: 800;
            color: #fff;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        }

        .num-chip.chip-a { background: linear-gradient(135deg, #2dc8e7, #3ecb8c); }
        .num-chip.chip-b { background: linear-gradient(135deg, #3ecb8c, #82c91e); }

        .op-symbol {
            font-size: 2.25rem;
            font-weight: 800;
            color: #495057;
        }

        .visual-groups {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .visual-row {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }

        .visual-group {
            display: flex;
            flex-wrap: wrap;
            align-content: flex-start;
            gap: 2px;
            width: 4rem;
            padding: 0.35rem;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 0.6rem;
        }

        .visual-icon {
            font-size: 1.1rem;
            line-height: 1;
        }

        .visual-groups.size-md .visual-group {
            width: 3.25rem;
            padding: 0.25rem;
        }

        .visual-groups.size-md .visual-icon {
            font-size: 0.85rem;
        }

        .visual-groups.size-sm .visual-group {
            width: 2.4rem;
            padding: 0.15rem;
            gap: 1px;
        }

        .visual-groups.size-sm .visual-icon {
            font-size: 0.55rem;
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
            transition: transform 0.1s ease, background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
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
            .num-chip {
                min-width: 3.5rem;
                height: 3.5rem;
                font-size: 1.9rem;
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
            'blurb' => 'Pick your numbers, then answer as many divisions as you can!'
        ])

        <div class="mult-wrap">
            {{-- Setup screen --}}
            <div id="setupScreen">
                <div class="card mult-setup-card">
                    <div class="card-body p-4">
                        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-grid-3x3-gap"></i> Divide by which numbers?</h2>

                        <div class="mb-2" id="tableToggles">
                            @for ($i = 1; $i <= 12; $i++)
                                <button type="button" class="table-toggle" data-table="{{ $i }}">{{ $i }}</button>
                            @endfor
                        </div>

                        <div class="d-flex flex-wrap justify-content-center gap-2 my-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-pick-btn" id="pick2510Times">2, 5 &amp; 10</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-pick-btn" id="pick369Times">3, 6 &amp; 9</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-pick-btn" id="pickEvensTimes">Evens</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-pick-btn" id="pickOddsTimes">Odds</button>
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
                                    <option value="5">5 seconds</option>
                                    <option value="10" selected>10 seconds</option>
                                    <option value="15">15 seconds</option>
                                    <option value="20">20 seconds</option>
                                    <option value="30">30 seconds</option>
                                </select>
                            </div>
                        </div>

                        <div id="setupError" class="alert alert-warning mt-4 mb-0 d-none" role="alert">
                            Pick at least one number to divide by.
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
                            <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                                <span class="num-chip chip-a" id="factorA">?</span>
                                <span class="op-symbol">&divide;</span>
                                <span class="num-chip chip-b" id="factorB">?</span>
                                <span class="op-symbol">=</span>
                                <span class="op-symbol">?</span>
                            </div>

                            <div class="visual-groups" id="visualGroups"></div>
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
            <h2 class="h6 text-secondary text-uppercase mb-2">About this division game</h2>
            <p class="text-secondary small">This free division game helps kids get comfortable dividing by the times tables they already know. Pick the tables to divide by, choose how many questions and how much time per question, then work through as many divisions as you can. Practising division alongside multiplication reinforces both skills together &mdash; no accounts, no ads, just practice.</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const setupScreen = document.getElementById('setupScreen');
            const gameScreen = document.getElementById('gameScreen');
            const resultsScreen = document.getElementById('resultsScreen');

            const tableToggles = Array.from(document.querySelectorAll('.table-toggle'));
            const pickAllBtn = document.getElementById('pickAllBtn');
            const pickNoneBtn = document.getElementById('pickNoneBtn');
            const pick2510Times = document.getElementById('pick2510Times');
            const pick369Times = document.getElementById('pick369Times');
            const pickEvensTimes = document.getElementById('pickEvensTimes');
            const pickOddsTimes = document.getElementById('pickOddsTimes');
            const questionCountSelect = document.getElementById('questionCountSelect');
            const timeLimitSelect = document.getElementById('timeLimitSelect');
            const setupError = document.getElementById('setupError');
            const startBtn = document.getElementById('startBtn');
            const quitBtn = document.getElementById('quitBtn');

            const questionProgress = document.getElementById('questionProgress');
            const scoreDisplay = document.getElementById('scoreDisplay');
            const timerTrack = document.getElementById('timerTrack');
            const timerFill = document.getElementById('timerFill');
            const factorA = document.getElementById('factorA');
            const factorB = document.getElementById('factorB');
            const visualGroups = document.getElementById('visualGroups');
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

            const ICONS = ['⭐', '🍎', '🐝', '🎈', '🌟', '🐾'];

            let selectedTables = new Set();
            let settings = { questionCount: 10, timeLimit: 10 };
            let session = null; // { questions, index, score, timerId, timeLeft, answered }
            let paused = false;

            function getHint() {
                return 'Divide the first number by the second — how many times does it fit in?';
            }

            function loadSettings() {
                try {
                    const raw = localStorage.getItem('divideGame.settings');
                    if (!raw) return;
                    const saved = JSON.parse(raw);
                    if (Array.isArray(saved.tables)) {
                        saved.tables.forEach(function(t) {
                            if (Number.isInteger(t) && t >= 1 && t <= 12) selectedTables.add(t);
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
                    localStorage.setItem('divideGame.settings', JSON.stringify({
                        tables: Array.from(selectedTables),
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
                    btn.classList.toggle('active', selectedTables.has(t));
                });
            }

            tableToggles.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const t = parseInt(btn.dataset.table, 10);
                    if (selectedTables.has(t)) {
                        selectedTables.delete(t);
                    } else {
                        selectedTables.add(t);
                    }
                    setupError.classList.add('d-none');
                    renderToggles();
                });
            });

            pickAllBtn.addEventListener('click', function() {
                selectedTables = new Set([1,2,3,4,5,6,7,8,9,10,11,12]);
                setupError.classList.add('d-none');
                renderToggles();
            });

            pickNoneBtn.addEventListener('click', function() {
                selectedTables = new Set();
                renderToggles();
            });

            pick2510Times.addEventListener('click', function() {
                selectedTables = new Set([2, 5, 10]);
                setupError.classList.add('d-none');
                renderToggles();
            });

            pick369Times.addEventListener('click', function() {
                selectedTables = new Set([3, 6, 9]);
                setupError.classList.add('d-none');
                renderToggles();
            });

            pickEvensTimes.addEventListener('click', function() {
                selectedTables = new Set([2, 4, 6, 8, 10, 12]);
                setupError.classList.add('d-none');
                renderToggles();
            });

            pickOddsTimes.addEventListener('click', function() {
                selectedTables = new Set([1, 3, 5, 7, 9, 11]);
                setupError.classList.add('d-none');
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

            function buildQuestions(tables, count) {
                const tableList = Array.from(tables);
                const questions = [];
                let lastKey = null;
                for (let i = 0; i < count; i++) {
                    let divisor, quotient, key;
                    let attempts = 0;
                    do {
                        divisor = tableList[randInt(0, tableList.length - 1)];
                        quotient = randInt(1, 12);
                        key = divisor + '/' + quotient;
                        attempts++;
                    } while (key === lastKey && attempts < 10);
                    lastKey = key;
                    questions.push({ a: divisor * quotient, b: divisor, answer: quotient });
                }
                return questions;
            }

            function buildChoices(answer) {
                const choices = new Set([answer]);
                const deltas = [1, 2, 3, -1, -2, -3, 10, -10];
                let idx = 0;
                while (choices.size < 4 && idx < deltas.length) {
                    const candidate = answer + deltas[idx];
                    if (candidate > 0 && candidate !== answer) choices.add(candidate);
                    idx++;
                }
                while (choices.size < 4) {
                    const candidate = answer + randInt(-12, 12);
                    if (candidate > 0) choices.add(candidate);
                }
                const arr = Array.from(choices);
                for (let i = arr.length - 1; i > 0; i--) {
                    const j = randInt(0, i);
                    [arr[i], arr[j]] = [arr[j], arr[i]];
                }
                return arr;
            }

            function renderVisualGroups(groups, perGroup) {
                visualGroups.innerHTML = '';
                const total = groups * perGroup;

                visualGroups.classList.remove('size-md', 'size-sm');
                if (total > 60) {
                    visualGroups.classList.add('size-sm');
                } else if (total > 20) {
                    visualGroups.classList.add('size-md');
                }

                const icon = ICONS[randInt(0, ICONS.length - 1)];
                const cols = Math.ceil(Math.sqrt(groups));
                let row = null;

                for (let g = 0; g < groups; g++) {
                    if (g % cols === 0) {
                        row = document.createElement('div');
                        row.className = 'visual-row';
                        visualGroups.appendChild(row);
                    }

                    const group = document.createElement('div');
                    group.className = 'visual-group';
                    for (let n = 0; n < perGroup; n++) {
                        const span = document.createElement('span');
                        span.className = 'visual-icon';
                        span.textContent = icon;
                        group.appendChild(span);
                    }
                    row.appendChild(group);
                }
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

                factorA.textContent = q.a;
                factorB.textContent = q.b;
                renderVisualGroups(q.b, q.answer);

                feedbackBanner.classList.add('d-none');
                feedbackBanner.classList.remove('correct-banner', 'wrong-banner');
                continueBtn.classList.add('d-none');

                const choices = buildChoices(q.answer);
                answerGrid.innerHTML = '';
                choices.forEach(function(choice) {
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

            function revealCorrect(correctAnswer, chosenBtn) {
                Array.from(answerGrid.querySelectorAll('.answer-btn')).forEach(function(btn) {
                    if (parseInt(btn.textContent, 10) === correctAnswer) {
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
                const isCorrect = choice === q.answer;

                revealCorrect(q.answer, isCorrect ? null : btn);

                feedbackBanner.classList.remove('d-none');
                if (isCorrect) {
                    session.score++;
                    feedbackText.textContent = pickPraise();
                    feedbackBanner.classList.add('correct-banner');
                    playCorrectSound();
                } else {
                    feedbackText.textContent = q.a + ' ÷ ' + q.b + ' = ' + q.answer;
                    feedbackBanner.classList.add('wrong-banner');
                    playWrongSound();
                }

                scoreDisplay.innerHTML = '<i class="bi bi-star-fill text-warning"></i> Score: ' + session.score;

                if (isCorrect) {
                    setTimeout(nextQuestion, 1100);
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
                revealCorrect(q.answer, null);
                feedbackBanner.classList.remove('d-none');
                feedbackText.textContent = "Time's up! " + q.a + ' ÷ ' + q.b + ' = ' + q.answer;
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
                if (pct >= 0.9) message = "Amazing! You're a division superstar!";
                else if (pct >= 0.6) message = 'Well done — great effort!';

                resultsMessage.textContent = message;
                resultsStats.textContent = 'Paused ' + session.pauseCount + ' time' + (session.pauseCount === 1 ? '' : 's') + ' · Used ' + session.hintCount + ' hint' + (session.hintCount === 1 ? '' : 's') + '.';
            }

            function startGame() {
                if (selectedTables.size === 0) {
                    setupError.classList.remove('d-none');
                    return;
                }

                saveSettings();

                session = {
                    questions: buildQuestions(selectedTables, settings.questionCount),
                    index: 0,
                    score: 0,
                    timerId: null,
                    timeLeft: 0,
                    pauseCount: 0,
                    hintCount: 0,
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
            if (selectedTables.size === 0) {
                selectedTables = new Set([2, 5, 10]);
            }
            questionCountSelect.value = String(settings.questionCount);
            timeLimitSelect.value = String(settings.timeLimit);
            renderToggles();
        })();
    </script>
@endpush
