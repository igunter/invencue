@extends('layouts.app')

@section('meta_title', 'Percentages — Maths Game for Kids')
@section('meta_blurb', 'A free percentages game for school kids — find percentages of numbers and practise percentage increase and decrease.')
@section('meta_words', 'percentages game, percentage of a number, percentage increase decrease, gcse maths game, primary school maths, learn percentages')

@section('title', $category->name)

@push('styles')
    <style>
        .mult-wrap {
            max-width: 720px;
            margin: 0 auto;
        }

        #percentToggles {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.5rem;
        }

        @media (min-width: 576px) {
            #percentToggles {
                grid-template-columns: repeat(7, 1fr);
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
            background: linear-gradient(160deg, #eef6ff, #f0fbf6);
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
            background: #7048e8;
            color: #fff;
            margin-bottom: 1rem;
        }

        .percent-question {
            font-size: 1.6rem;
            font-weight: 800;
            color: #343a40;
            margin-top: 0.5rem;
        }

        @media (max-width: 400px) {
            .percent-question { font-size: 1.25rem; }
        }

        .answer-btn {
            border-radius: 1.1rem;
            border: 2px solid #dee2e6;
            background: #fff;
            font-size: 1.4rem;
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
            font-size: 1.25rem;
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
            .answer-btn {
                font-size: 1.1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-xl pb-2">
        @include('partials.games-header', [
            'category' => $category,
            'game' => $game,
            'blurb' => 'Pick your percentages, then work out the answer!'
        ])

        <div class="mult-wrap">
            {{-- Setup screen --}}
            <div id="setupScreen">
                <div class="card mult-setup-card">
                    <div class="card-body p-4">
                        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-calculator"></i> Which question types?</h2>
                        <div class="mb-4" id="typeToggles">
                            <button type="button" class="choice-toggle active" data-type="find">Find X%</button>
                            <button type="button" class="choice-toggle active" data-type="increase">Percentage increase</button>
                            <button type="button" class="choice-toggle active" data-type="decrease">Percentage decrease</button>
                        </div>

                        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-grid-3x3-gap"></i> Which percentages?</h2>
                        <div class="mb-2" id="percentToggles">
                            <button type="button" class="table-toggle active" data-percent="5">5%</button>
                            <button type="button" class="table-toggle active" data-percent="10">10%</button>
                            <button type="button" class="table-toggle active" data-percent="15">15%</button>
                            <button type="button" class="table-toggle active" data-percent="20">20%</button>
                            <button type="button" class="table-toggle active" data-percent="25">25%</button>
                            <button type="button" class="table-toggle active" data-percent="30">30%</button>
                            <button type="button" class="table-toggle active" data-percent="40">40%</button>
                            <button type="button" class="table-toggle active" data-percent="50">50%</button>
                            <button type="button" class="table-toggle active" data-percent="60">60%</button>
                            <button type="button" class="table-toggle active" data-percent="70">70%</button>
                            <button type="button" class="table-toggle active" data-percent="75">75%</button>
                            <button type="button" class="table-toggle active" data-percent="80">80%</button>
                            <button type="button" class="table-toggle active" data-percent="90">90%</button>
                        </div>

                        <div class="d-flex flex-wrap justify-content-center gap-2 my-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-pick-btn" id="pickCommonBtn">Common (10, 20, 25, 50)</button>
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
                                    <option value="10">10 seconds</option>
                                    <option value="15" selected>15 seconds</option>
                                    <option value="20">20 seconds</option>
                                    <option value="30">30 seconds</option>
                                    <option value="45">45 seconds</option>
                                </select>
                            </div>
                        </div>

                        <div id="setupError" class="alert alert-warning mt-4 mb-0 d-none" role="alert">
                            Pick at least one question type and one percentage to play with.
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
                            <span class="target-badge" id="targetBadge">FIND 25%</span>
                            <div class="percent-question" id="percentQuestion">What is 25% of 80?</div>
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
            <h2 class="h6 text-secondary text-uppercase mb-2">About this percentages game</h2>
            <p class="text-secondary small">This free percentages game gives kids practice finding a percentage of a number, as well as working out percentage increase and decrease — three of the most common percentage problems in school maths. Pick which question types and percentages to include, set your question count and time limit, then work through as many as you can. Regular practice like this builds the mental maths shortcuts that make percentages click.</p>
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
            const percentToggles = Array.from(document.querySelectorAll('#percentToggles .table-toggle'));
            const pickCommonBtn = document.getElementById('pickCommonBtn');
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
            const targetBadge = document.getElementById('targetBadge');
            const percentQuestion = document.getElementById('percentQuestion');
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

            const ALL_PERCENTS = [5, 10, 15, 20, 25, 30, 40, 50, 60, 70, 75, 80, 90];
            const ALL_TYPES = ['find', 'increase', 'decrease'];

            let selectedTypes = new Set();
            let selectedPercents = new Set();
            let settings = { questionCount: 10, timeLimit: 15 };
            let session = null; // { questions, index, score, timerId, timeLeft }
            let paused = false;

            function getHint(q) {
                if (q.type === 'find') return 'Divide the percentage by 100, then multiply by the number.';
                if (q.type === 'increase') return 'Work out the percentage of the number, then add it on.';
                return 'Work out the percentage of the number, then take it away.';
            }

            function loadSettings() {
                try {
                    const raw = localStorage.getItem('percentagesGame.settings');
                    if (!raw) return;
                    const saved = JSON.parse(raw);
                    if (Array.isArray(saved.types)) {
                        saved.types.forEach(function(t) {
                            if (ALL_TYPES.indexOf(t) !== -1) selectedTypes.add(t);
                        });
                    }
                    if (Array.isArray(saved.percents)) {
                        saved.percents.forEach(function(p) {
                            if (ALL_PERCENTS.indexOf(p) !== -1) selectedPercents.add(p);
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
                    localStorage.setItem('percentagesGame.settings', JSON.stringify({
                        types: Array.from(selectedTypes),
                        percents: Array.from(selectedPercents),
                        questionCount: settings.questionCount,
                        timeLimit: settings.timeLimit,
                    }));
                } catch (e) {
                    // ignore unavailable storage
                }
            }

            function renderToggles() {
                typeToggles.forEach(function(btn) {
                    btn.classList.toggle('active', selectedTypes.has(btn.dataset.type));
                });
                percentToggles.forEach(function(btn) {
                    const p = parseInt(btn.dataset.percent, 10);
                    btn.classList.toggle('active', selectedPercents.has(p));
                });
            }

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

            percentToggles.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const p = parseInt(btn.dataset.percent, 10);
                    if (selectedPercents.has(p)) {
                        selectedPercents.delete(p);
                    } else {
                        selectedPercents.add(p);
                    }
                    setupError.classList.add('d-none');
                    renderToggles();
                });
            });

            pickCommonBtn.addEventListener('click', function() {
                selectedPercents = new Set([10, 20, 25, 50]);
                setupError.classList.add('d-none');
                renderToggles();
            });

            pickAllBtn.addEventListener('click', function() {
                selectedPercents = new Set(ALL_PERCENTS);
                setupError.classList.add('d-none');
                renderToggles();
            });

            pickNoneBtn.addEventListener('click', function() {
                selectedPercents = new Set();
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

            // Base numbers are always multiples of 20 (20..200), and all offered
            // percentages are multiples of 5, so percent * base / 100 is always
            // an integer: (percent/5) * (base/20) is an integer * integer.
            function buildQuestion(types, percents) {
                const type = types[randInt(0, types.length - 1)];
                const percent = pickFrom(percents);
                const base = 20 * randInt(1, 10); // 20..200
                const part = (percent * base) / 100; // guaranteed integer

                if (type === 'find') {
                    return { type: type, percent: percent, base: base, part: part, answer: part, display: 'What is ' + percent + '% of ' + base + '?', steps: [percent + '% of ' + base + ' = ' + base + ' × ' + percent + ' ÷ 100 = ' + part] };
                }
                if (type === 'increase') {
                    const answer = base + part;
                    return { type: type, percent: percent, base: base, part: part, answer: answer, display: 'Increase ' + base + ' by ' + percent + '%.', steps: [percent + '% of ' + base + ' = ' + part, base + ' + ' + part + ' = ' + answer] };
                }
                // decrease
                const answer = base - part;
                return { type: type, percent: percent, base: base, part: part, answer: answer, display: 'Decrease ' + base + ' by ' + percent + '%.', steps: [percent + '% of ' + base + ' = ' + part, base + ' − ' + part + ' = ' + answer] };
            }

            function buildQuestions(types, percents, count) {
                const typeList = Array.from(types);
                const percentList = Array.from(percents);
                const questions = [];
                let lastKey = null;
                for (let i = 0; i < count; i++) {
                    let q, key;
                    let attempts = 0;
                    do {
                        q = buildQuestion(typeList, percentList);
                        key = q.type + ':' + q.percent + ':' + q.base;
                        attempts++;
                    } while (key === lastKey && attempts < 10);
                    lastKey = key;
                    questions.push(q);
                }
                return questions;
            }

            function buildChoices(q) {
                const answer = q.answer;
                const base = q.base;
                const choices = new Set([answer]);

                const step1 = Math.round(base * 0.05) || 1;
                const step2 = Math.round(base * 0.1) || 2;
                const deltas = [step1, -step1, step2, -step2, 5, -5, 10, -10];

                let idx = 0;
                while (choices.size < 4 && idx < deltas.length) {
                    const candidate = answer + deltas[idx];
                    if (candidate > 0 && candidate !== answer) choices.add(candidate);
                    idx++;
                }
                while (choices.size < 4) {
                    const candidate = answer + randInt(-10, 10);
                    if (candidate > 0 && candidate !== answer) choices.add(candidate);
                }
                const arr = Array.from(choices);
                for (let i = arr.length - 1; i > 0; i--) {
                    const j = randInt(0, i);
                    [arr[i], arr[j]] = [arr[j], arr[i]];
                }
                return arr;
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

            function badgeText(q) {
                if (q.type === 'find') return 'FIND ' + q.percent + '%';
                if (q.type === 'increase') return 'INCREASE BY ' + q.percent + '%';
                return 'DECREASE BY ' + q.percent + '%';
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

                targetBadge.textContent = badgeText(q);
                percentQuestion.textContent = q.display;

                feedbackBanner.classList.add('d-none');
                feedbackBanner.classList.remove('correct-banner', 'wrong-banner');
                continueBtn.classList.add('d-none');

                const choices = buildChoices(q);
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
                    feedbackText.textContent = q.steps.join(' → ');
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
                revealCorrect(q.answer, null);
                feedbackBanner.classList.remove('d-none');
                feedbackText.textContent = "Time's up! " + q.steps.join(' → ');
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
                if (pct >= 0.9) message = "Amazing! You're a percentages superstar!";
                else if (pct >= 0.6) message = 'Well done — great effort!';

                resultsMessage.textContent = message;
                resultsStats.textContent = 'Paused ' + session.pauseCount + ' time' + (session.pauseCount === 1 ? '' : 's') + ' · Used ' + session.hintCount + ' hint' + (session.hintCount === 1 ? '' : 's') + '.';
            }

            function startGame() {
                if (selectedTypes.size === 0 || selectedPercents.size === 0) {
                    setupError.classList.remove('d-none');
                    return;
                }

                saveSettings();

                session = {
                    questions: buildQuestions(selectedTypes, selectedPercents, settings.questionCount),
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
            if (selectedTypes.size === 0) {
                selectedTypes = new Set(['find', 'increase', 'decrease']);
            }
            if (selectedPercents.size === 0) {
                selectedPercents = new Set([10, 20, 25, 50]);
            }
            questionCountSelect.value = String(settings.questionCount);
            timeLimitSelect.value = String(settings.timeLimit);
            renderToggles();
        })();
    </script>
@endpush
