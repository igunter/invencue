@extends('layouts.app')

@section('meta_title', 'Coordinates — Plotting & Reading Game for Kids')
@section('meta_blurb', 'A free coordinates game for school kids — read and plot points on a grid, work with all four quadrants, and find missing coordinates.')
@section('meta_words', 'coordinates game, plotting coordinates, reading coordinates, four quadrants, primary school maths, learn coordinates')

@section('title', $category->name)

@push('styles')
    <style>
        .mult-wrap {
            max-width: 720px;
            margin: 0 auto;
        }

        #typeToggles,
        #rangeToggles {
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
            background: #0d6efd;
            border-color: transparent;
            color: #fff;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.35);
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
            background: #0d6efd;
            border: none;
        }

        .start-btn:hover:not(:disabled),
        .start-btn:focus:not(:disabled) {
            background: #0b5ed7;
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
            background: #0d6efd;
            color: #fff;
            margin-bottom: 1rem;
        }

        .question-text {
            font-size: 0.95rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.75rem;
        }

        .grid-svg-wrap {
            display: flex;
            justify-content: center;
        }

        .grid-svg-wrap svg {
            max-width: 100%;
            height: auto;
        }

        .answer-btn {
            border-radius: 1.1rem;
            border: 2px solid #dee2e6;
            background: #fff;
            font-size: 1.15rem;
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
            color: #0d6efd;
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
            'blurb' => 'Pick your question types, then read the grid!'
        ])

        <div class="mult-wrap">
            {{-- Setup screen --}}
            <div id="setupScreen">
                <div class="card mult-setup-card">
                    <div class="card-body p-4">
                        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-list-check"></i> Question types</h2>
                        <div class="mb-4" id="typeToggles">
                            <button type="button" class="choice-toggle active" data-type="read">Read a point</button>
                            <button type="button" class="choice-toggle active" data-type="quadrant">Which quadrant?</button>
                            <button type="button" class="choice-toggle active" data-type="missing">Missing coordinate</button>
                        </div>

                        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-grid-3x3-gap"></i> Grid range</h2>
                        <div class="mb-4" id="rangeToggles">
                            <button type="button" class="choice-toggle active" data-range="positive">Positive only (first quadrant)</button>
                            <button type="button" class="choice-toggle active" data-range="all">All four quadrants</button>
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
                            Pick at least one question type and one grid range to play with.
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
                            <span class="target-badge" id="targetBadge">READ THE POINT</span>
                            <div class="question-text" id="questionText"></div>
                            <div class="grid-svg-wrap" id="gridVisual"></div>
                        </div>
                    </div>
                    <div id="pauseOverlay" class="pause-overlay d-none">
                        <div class="text-center">
                            <i class="bi bi-pause-circle-fill d-block mb-2"></i>
                            <div class="fw-bold">Paused</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center mb-3">
                    <button type="button" class="btn btn-sm btn-outline-primary hint-btn" id="hintBtn">
                        <i class="bi bi-lightbulb"></i> Hint
                    </button>
                </div>
                <div id="hintText" class="hint-text mb-3 d-none"></div>

                <div id="feedbackBanner" class="feedback-banner mb-3 d-none d-flex align-items-center gap-3">
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
            <h2 class="h6 text-secondary text-uppercase mb-2">About this coordinates game</h2>
            <p class="text-secondary small">This free coordinates game gives kids practice reading and plotting points on a coordinate grid, recognising which of the four quadrants a point sits in, and finding a missing coordinate. Choose which question types to include and whether to stick to positive numbers or use all four quadrants, set your question count and time limit, then work through as many as you can. It's a solid foundation for graphs and algebra later on.</p>
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
            const rangeToggles = Array.from(document.querySelectorAll('#rangeToggles .choice-toggle'));
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
            const questionText = document.getElementById('questionText');
            const gridVisual = document.getElementById('gridVisual');
            const feedbackBanner = document.getElementById('feedbackBanner');
            const feedbackText = document.getElementById('feedbackText');
            const answerGrid = document.getElementById('answerGrid');
            const continueBtn = document.getElementById('continueBtn');
            const pauseBtn = document.getElementById('pauseBtn');
            const pauseOverlay = document.getElementById('pauseOverlay');
            const hintBtn = document.getElementById('hintBtn');
            const hintText = document.getElementById('hintText');

            const resultsStars = document.getElementById('resultsStars');
            const resultsScore = document.getElementById('resultsScore');
            const resultsMessage = document.getElementById('resultsMessage');
            const resultsStats = document.getElementById('resultsStats');
            const playAgainBtn = document.getElementById('playAgainBtn');
            const changeSettingsBtn = document.getElementById('changeSettingsBtn');

            const ALL_TYPES = ['read', 'quadrant', 'missing'];
            const ALL_RANGES = ['positive', 'all'];
            const QUADRANTS = ['Quadrant 1', 'Quadrant 2', 'Quadrant 3', 'Quadrant 4'];

            let selectedTypes = new Set();
            let selectedRanges = new Set();
            let settings = { questionCount: 10, timeLimit: 15 };
            let session = null; // { questions, index, score, timerId, timeLeft, correctText }
            let paused = false;

            function getHint(q) {
                if (q.type === 'read') return 'Read the x-coordinate first (across), then the y-coordinate (up or down) — always (x, y).';
                if (q.type === 'quadrant') return 'Quadrant 1 is top-right (+,+), Quadrant 2 is top-left (−,+), Quadrant 3 is bottom-left (−,−), Quadrant 4 is bottom-right (+,−).';
                return 'The dashed line shows the coordinate you already know — read across or up/down to find where the point sits.';
            }

            function loadSettings() {
                try {
                    const raw = localStorage.getItem('coordinatesGame.settings');
                    if (!raw) return;
                    const saved = JSON.parse(raw);
                    if (Array.isArray(saved.types)) {
                        saved.types.forEach(function(t) {
                            if (ALL_TYPES.indexOf(t) !== -1) selectedTypes.add(t);
                        });
                    }
                    if (Array.isArray(saved.ranges)) {
                        saved.ranges.forEach(function(r) {
                            if (ALL_RANGES.indexOf(r) !== -1) selectedRanges.add(r);
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
                    localStorage.setItem('coordinatesGame.settings', JSON.stringify({
                        types: Array.from(selectedTypes),
                        ranges: Array.from(selectedRanges),
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
                rangeToggles.forEach(function(btn) {
                    btn.classList.toggle('active', selectedRanges.has(btn.dataset.range));
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

            rangeToggles.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const range = btn.dataset.range;
                    if (selectedRanges.has(range)) {
                        selectedRanges.delete(range);
                    } else {
                        selectedRanges.add(range);
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

            function randCoord(ranges) {
                const useAll = ranges.indexOf('all') !== -1 && (ranges.indexOf('positive') === -1 || Math.random() < 0.5);
                let x, y;
                if (useAll) {
                    do { x = randInt(-8, 8); } while (x === 0);
                    do { y = randInt(-8, 8); } while (y === 0);
                } else {
                    x = randInt(1, 8);
                    y = randInt(1, 8);
                }
                return { x: x, y: y };
            }

            function quadrantOf(x, y) {
                if (x > 0 && y > 0) return 'Quadrant 1';
                if (x < 0 && y > 0) return 'Quadrant 2';
                if (x < 0 && y < 0) return 'Quadrant 3';
                return 'Quadrant 4'; // x > 0 && y < 0
            }

            function coordText(x, y) {
                return '(' + x + ', ' + y + ')';
            }

            function questionPrompt(q) {
                if (q.type === 'read') return 'What are the coordinates of the point shown?';
                if (q.type === 'quadrant') return 'Which quadrant is the point in?';
                return q.prompt;
            }

            function buildQuestion(types, ranges) {
                const type = types[randInt(0, types.length - 1)];
                const rangeArr = Array.from(ranges);
                const p = randCoord(rangeArr);

                if (type === 'read') {
                    return { type: type, x: p.x, y: p.y, correctText: coordText(p.x, p.y), badge: 'READ THE POINT' };
                }

                if (type === 'quadrant') {
                    return { type: type, x: p.x, y: p.y, correctText: quadrantOf(p.x, p.y), badge: 'WHICH QUADRANT?' };
                }

                // missing — show one coordinate, ask for the other. Point is not drawn, only a guide line.
                const askX = Math.random() < 0.5;
                const known = askX ? p.y : p.x;
                const missing = askX ? p.x : p.y;
                return {
                    type: type, x: p.x, y: p.y, askX: askX, known: known, missing: missing,
                    correctText: String(missing),
                    badge: 'MISSING COORDINATE',
                    prompt: 'The point (' + (askX ? '?' : p.x) + ', ' + (askX ? p.y : '?') + ') lies on the grid shown. What is the missing ' + (askX ? 'x' : 'y') + '-coordinate?',
                };
            }

            function buildQuestions(types, ranges, count) {
                const typeList = Array.from(types);
                const questions = [];
                for (let i = 0; i < count; i++) {
                    questions.push(buildQuestion(typeList, ranges));
                }
                return questions;
            }

            function buildChoices(q) {
                let correctText, texts;

                if (q.type === 'read') {
                    correctText = q.correctText;
                    texts = new Set([correctText]);
                    const candidates = [
                        coordText(q.y, q.x),
                        coordText(-q.x, q.y),
                        coordText(q.x, -q.y),
                        coordText(-q.x, -q.y),
                    ];
                    candidates.forEach(function(t) {
                        if (t !== correctText) texts.add(t);
                    });
                    let guard = 0;
                    while (texts.size < 4 && guard < 20) {
                        const dx = randInt(-2, 2);
                        const dy = randInt(-2, 2);
                        const t = coordText(q.x + dx, q.y + dy);
                        if (t !== correctText) texts.add(t);
                        guard++;
                    }
                } else if (q.type === 'quadrant') {
                    correctText = q.correctText;
                    texts = new Set([correctText]);
                    QUADRANTS.forEach(function(t) {
                        if (t !== correctText) texts.add(t);
                    });
                } else {
                    correctText = q.correctText;
                    texts = new Set([correctText]);
                    const deltas = [1, -1, 2, -2, -q.missing * 2];
                    deltas.forEach(function(d) {
                        const candidate = q.missing + d;
                        const t = String(candidate);
                        if (t !== correctText) texts.add(t);
                    });
                    let guard = 0;
                    while (texts.size < 4 && guard < 20) {
                        const t = String(q.missing + randInt(-6, 6));
                        if (t !== correctText) texts.add(t);
                        guard++;
                    }
                }

                const arr = Array.from(texts).slice(0, 4);
                // ensure correct answer is present even if slice trimmed it (shouldn't happen given construction order, but be safe)
                if (arr.indexOf(correctText) === -1) {
                    arr[0] = correctText;
                }
                for (let i = arr.length - 1; i > 0; i--) {
                    const j = randInt(0, i);
                    [arr[i], arr[j]] = [arr[j], arr[i]];
                }
                return { choices: arr, correct: correctText };
            }

            function renderGrid(q) {
                const size = 240;
                const half = size / 2;
                const negative = q.x < 0 || q.y < 0;
                const scale = negative ? (half - 20) / 8 : (size - 40) / 8; // pixels per unit, leaving margin
                const originX = negative ? half : 20;
                const originY = negative ? half : size - 20;

                function toSvgX(gx) { return originX + gx * scale; }
                function toSvgY(gy) { return originY - gy * scale; } // flip y since SVG y grows downward

                const maxUnit = 8;
                const minUnit = negative ? -8 : 0;

                let grid = '';
                for (let u = minUnit; u <= maxUnit; u++) {
                    grid += '<line x1="' + toSvgX(u) + '" y1="' + toSvgY(minUnit) + '" x2="' + toSvgX(u) + '" y2="' + toSvgY(maxUnit) + '" stroke="#e9ecef" stroke-width="1" />';
                    grid += '<line x1="' + toSvgX(minUnit) + '" y1="' + toSvgY(u) + '" x2="' + toSvgX(maxUnit) + '" y2="' + toSvgY(u) + '" stroke="#e9ecef" stroke-width="1" />';
                }

                grid += '<line x1="' + toSvgX(minUnit) + '" y1="' + toSvgY(0) + '" x2="' + toSvgX(maxUnit) + '" y2="' + toSvgY(0) + '" stroke="#495057" stroke-width="2" />';
                grid += '<line x1="' + toSvgX(0) + '" y1="' + toSvgY(minUnit) + '" x2="' + toSvgX(0) + '" y2="' + toSvgY(maxUnit) + '" stroke="#495057" stroke-width="2" />';

                let pointMark = '';
                if (q.type !== 'missing') {
                    pointMark = '<circle cx="' + toSvgX(q.x) + '" cy="' + toSvgY(q.y) + '" r="6" fill="#e64980" />';
                } else if (q.askX) {
                    pointMark = '<line x1="' + toSvgX(minUnit) + '" y1="' + toSvgY(q.y) + '" x2="' + toSvgX(maxUnit) + '" y2="' + toSvgY(q.y) + '" stroke="#e64980" stroke-width="2" stroke-dasharray="4 3" />';
                } else {
                    pointMark = '<line x1="' + toSvgX(q.x) + '" y1="' + toSvgY(minUnit) + '" x2="' + toSvgX(q.x) + '" y2="' + toSvgY(maxUnit) + '" stroke="#e64980" stroke-width="2" stroke-dasharray="4 3" />';
                }

                gridVisual.innerHTML = '<svg viewBox="0 0 ' + size + ' ' + size + '" width="260">' + grid + pointMark + '</svg>';
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

                targetBadge.textContent = q.badge;
                questionText.textContent = questionPrompt(q);
                renderGrid(q);

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
                    feedbackText.textContent = 'The correct answer is ' + q.correctText + '.';
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
                lockAnswers();
                const q = session.questions[session.index];
                revealCorrect(session.correctText, null);
                feedbackBanner.classList.remove('d-none');
                feedbackText.textContent = "Time's up! The correct answer is " + q.correctText + '.';
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
                if (pct >= 0.9) message = "Amazing! You're a coordinates superstar!";
                else if (pct >= 0.6) message = 'Well done — great effort!';

                resultsMessage.textContent = message;
                resultsStats.textContent = 'Paused ' + session.pauseCount + ' time' + (session.pauseCount === 1 ? '' : 's') + ' · Used ' + session.hintCount + ' hint' + (session.hintCount === 1 ? '' : 's') + '.';
            }

            function startGame() {
                if (selectedTypes.size === 0 || selectedRanges.size === 0) {
                    setupError.classList.remove('d-none');
                    return;
                }

                saveSettings();

                session = {
                    questions: buildQuestions(selectedTypes, selectedRanges, settings.questionCount),
                    index: 0,
                    score: 0,
                    timerId: null,
                    timeLeft: 0,
                    pauseCount: 0,
                    hintCount: 0,
                    correctText: null,
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
                selectedTypes = new Set(ALL_TYPES);
            }
            if (selectedRanges.size === 0) {
                selectedRanges = new Set(ALL_RANGES);
            }
            questionCountSelect.value = String(settings.questionCount);
            timeLimitSelect.value = String(settings.timeLimit);
            renderToggles();
        })();
    </script>
@endpush
