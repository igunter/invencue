@extends('layouts.app')

@section('meta_title', 'Circle Theorems — GCSE Maths Game')
@section('meta_blurb', 'A free circle theorems game for GCSE-level practice — use circle theorem rules to find missing angles on labelled diagrams.')
@section('meta_words', 'circle theorems game, circle geometry game, angle in a semicircle, angle at the centre, gcse maths game, learn circle theorems')

@section('title', $category->name)

@push('styles')
    <style>
        .mult-wrap {
            max-width: 720px;
            margin: 0 auto;
        }

        #theoremToggles {
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
            background: #8a5cff;
            border-color: transparent;
            color: #fff;
            box-shadow: 0 4px 10px rgba(138, 92, 255, 0.35);
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
            background: #8a5cff;
            border: none;
        }

        .start-btn:hover:not(:disabled),
        .start-btn:focus:not(:disabled) {
            background: #7444eb;
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
            background: linear-gradient(160deg, #fff9eb, #eef6ff);
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
            background: #8a5cff;
            color: #fff;
            margin-bottom: 1rem;
        }

        .theorem-question {
            font-size: 1rem;
            color: #495057;
            margin-top: 0.25rem;
            margin-bottom: 0.75rem;
        }

        .theorem-svg-wrap {
            display: flex;
            justify-content: center;
        }

        .theorem-svg-wrap svg {
            max-width: 100%;
            height: auto;
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
            color: #8a5cff;
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
            'blurb' => 'Pick your theorems, then find the missing angle!'
        ])

        <div class="mult-wrap">
            {{-- Setup screen --}}
            <div id="setupScreen">
                <div class="card mult-setup-card">
                    <div class="card-body p-4">
                        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-compass"></i> Which theorems?</h2>
                        <div class="mb-4" id="theoremToggles">
                            <button type="button" class="choice-toggle active" data-theorem="centre">Angle at the centre</button>
                            <button type="button" class="choice-toggle active" data-theorem="semicircle">Angle in a semicircle</button>
                            <button type="button" class="choice-toggle active" data-theorem="segment">Same segment</button>
                            <button type="button" class="choice-toggle active" data-theorem="cyclic">Cyclic quadrilateral</button>
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
                                    <option value="20">20 seconds</option>
                                    <option value="30" selected>30 seconds</option>
                                    <option value="45">45 seconds</option>
                                    <option value="60">60 seconds</option>
                                </select>
                            </div>
                        </div>

                        <div id="setupError" class="alert alert-warning mt-4 mb-0 d-none" role="alert">
                            Pick at least one theorem to play with.
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
                            <span class="target-badge" id="targetBadge">ANGLE AT THE CENTRE</span>
                            <div class="theorem-question" id="theoremQuestion"></div>
                            <div class="theorem-svg-wrap" id="theoremVisual"></div>
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
            <h2 class="h6 text-secondary text-uppercase mb-2">About this circle theorems game</h2>
            <p class="text-secondary small">This free circle theorems game gives GCSE-level students practice applying the key circle theorem rules — the angle at the centre, the angle in a semicircle, angles in the same segment, and opposite angles in a cyclic quadrilateral — to find missing angles on labelled diagrams. Choose which theorems to include, set your question count and time limit, then work through as many as you can. It's a solid way to build recognition of which rule applies to which diagram ahead of exams.</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const setupScreen = document.getElementById('setupScreen');
            const gameScreen = document.getElementById('gameScreen');
            const resultsScreen = document.getElementById('resultsScreen');

            const theoremToggles = Array.from(document.querySelectorAll('#theoremToggles .choice-toggle'));
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
            const theoremQuestion = document.getElementById('theoremQuestion');
            const theoremVisual = document.getElementById('theoremVisual');
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

            const ALL_THEOREMS = ['centre', 'semicircle', 'segment', 'cyclic'];

            let selectedTheorems = new Set();
            let settings = { questionCount: 10, timeLimit: 30 };
            let session = null; // { questions, index, score, timerId, timeLeft }
            let paused = false;

            function getHint(q) {
                if (q.theorem === 'centre') return 'The angle at the centre is always double the angle at the circumference on the same arc.';
                if (q.theorem === 'semicircle') return 'The angle in a semicircle is always 90° — the two other angles in the triangle add up to 90° as well.';
                if (q.theorem === 'segment') return 'Angles subtended by the same arc, from the same side, are always equal.';
                return 'Opposite angles in a cyclic quadrilateral always add up to 180°.';
            }

            function loadSettings() {
                try {
                    const raw = localStorage.getItem('circleTheoremsGame.settings');
                    if (!raw) return;
                    const saved = JSON.parse(raw);
                    if (Array.isArray(saved.theorems)) {
                        saved.theorems.forEach(function(t) {
                            if (ALL_THEOREMS.indexOf(t) !== -1) selectedTheorems.add(t);
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
                    localStorage.setItem('circleTheoremsGame.settings', JSON.stringify({
                        theorems: Array.from(selectedTheorems),
                        questionCount: settings.questionCount,
                        timeLimit: settings.timeLimit,
                    }));
                } catch (e) {
                    // ignore unavailable storage
                }
            }

            function renderToggles() {
                theoremToggles.forEach(function(btn) {
                    btn.classList.toggle('active', selectedTheorems.has(btn.dataset.theorem));
                });
            }

            theoremToggles.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const theorem = btn.dataset.theorem;
                    if (selectedTheorems.has(theorem)) {
                        selectedTheorems.delete(theorem);
                    } else {
                        selectedTheorems.add(theorem);
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

            function pickFrom(arr) {
                return arr[randInt(0, arr.length - 1)];
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

            function buildQuestion(theorems) {
                const theorem = theorems[randInt(0, theorems.length - 1)];

                if (theorem === 'centre') {
                    const given = pickFrom([40, 60, 70, 80, 100, 120, 140, 160]); // even, so /2 is exact
                    const answer = given / 2;
                    return {
                        theorem: theorem, given: given, answer: answer,
                        badge: 'ANGLE AT THE CENTRE',
                        prompt: 'The angle at the centre (O) is ' + given + '°. What is the angle at the circumference?',
                    };
                }

                if (theorem === 'semicircle') {
                    const given = pickFrom([20, 30, 40, 50, 60, 70]);
                    const answer = 90 - given;
                    return {
                        theorem: theorem, given: given, answer: answer,
                        badge: 'ANGLE IN A SEMICIRCLE',
                        prompt: 'AB is a diameter. Angle CAB is ' + given + '°. What is angle CBA?',
                    };
                }

                if (theorem === 'segment') {
                    const given = pickFrom([30, 40, 50, 60, 70, 80, 90]);
                    const answer = given; // angles in the same segment are equal
                    return {
                        theorem: theorem, given: given, answer: answer,
                        badge: 'ANGLES IN THE SAME SEGMENT',
                        prompt: 'Angle ACB is ' + given + '°. What is angle ADB (same segment)?',
                    };
                }

                // cyclic quadrilateral
                const given = pickFrom([50, 60, 70, 80, 90, 100, 110, 120]);
                const answer = 180 - given;
                return {
                    theorem: theorem, given: given, answer: answer,
                    badge: 'CYCLIC QUADRILATERAL',
                    prompt: 'ABCD is a cyclic quadrilateral. Angle A is ' + given + '°. What is the opposite angle C?',
                };
            }

            function buildQuestions(theorems, count) {
                const theoremList = Array.from(theorems);
                const questions = [];
                let lastKey = null;
                for (let i = 0; i < count; i++) {
                    let q, key;
                    let attempts = 0;
                    do {
                        q = buildQuestion(theoremList);
                        key = q.theorem + ':' + q.given;
                        attempts++;
                    } while (key === lastKey && attempts < 10);
                    lastKey = key;
                    questions.push(q);
                }
                return questions;
            }

            function buildChoices(q) {
                const answer = q.answer;
                const choices = new Set([answer]);

                // For "angle at the centre", forgetting to halve is the classic mistake.
                if (q.theorem === 'centre' && q.given !== answer && q.given > 0 && q.given <= 180) {
                    choices.add(q.given);
                }

                const deltas = [5, 10, 15, -5, -10, -15, 20, -20];
                let idx = 0;
                while (choices.size < 4 && idx < deltas.length) {
                    const candidate = answer + deltas[idx];
                    if (candidate > 0 && candidate <= 180 && candidate !== answer) choices.add(candidate);
                    idx++;
                }
                while (choices.size < 4) {
                    const candidate = answer + randInt(-25, 25);
                    if (candidate > 0 && candidate <= 180) choices.add(candidate);
                }

                const arr = Array.from(choices);
                for (let i = arr.length - 1; i > 0; i--) {
                    const j = randInt(0, i);
                    [arr[i], arr[j]] = [arr[j], arr[i]];
                }
                return arr;
            }

            // --- SVG diagram helpers -------------------------------------------------

            function pt(cx, cy, r, deg) {
                const rad = deg * Math.PI / 180;
                return { x: cx + r * Math.cos(rad), y: cy + r * Math.sin(rad) };
            }

            function anglePos(v, p1, p2, frac) {
                const mx = (p1.x + p2.x) / 2;
                const my = (p1.y + p2.y) / 2;
                return { x: v.x + (mx - v.x) * frac, y: v.y + (my - v.y) * frac };
            }

            function sub(a, b) { return { x: a.x - b.x, y: a.y - b.y }; }
            function addv(a, b) { return { x: a.x + b.x, y: a.y + b.y }; }
            function scalev(a, s) { return { x: a.x * s, y: a.y * s }; }
            function unit(a) {
                const len = Math.sqrt(a.x * a.x + a.y * a.y) || 1;
                return { x: a.x / len, y: a.y / len };
            }

            function svgLine(p1, p2, color, width, dash) {
                return '<line x1="' + p1.x.toFixed(1) + '" y1="' + p1.y.toFixed(1) + '" x2="' + p2.x.toFixed(1) + '" y2="' + p2.y.toFixed(1) + '" stroke="' + (color || '#495057') + '" stroke-width="' + (width || 2) + '"' + (dash ? ' stroke-dasharray="' + dash + '"' : '') + ' />';
            }

            function svgDot(p, r) {
                return '<circle cx="' + p.x.toFixed(1) + '" cy="' + p.y.toFixed(1) + '" r="' + (r || 4) + '" fill="#495057" />';
            }

            function pointLabel(p, text) {
                return '<text x="' + p.x.toFixed(1) + '" y="' + p.y.toFixed(1) + '" text-anchor="middle" dominant-baseline="middle" font-size="13" font-weight="700" fill="#343a40">' + text + '</text>';
            }

            function angleLabel(p, text, color) {
                return '<text x="' + p.x.toFixed(1) + '" y="' + p.y.toFixed(1) + '" text-anchor="middle" dominant-baseline="middle" font-size="14" font-weight="800" fill="' + color + '">' + text + '</text>';
            }

            function rightAngleMarker(v, p1, p2, size) {
                size = size || 12;
                const u1 = unit(sub(p1, v));
                const u2 = unit(sub(p2, v));
                const corner1 = addv(v, scalev(u1, size));
                const corner2 = addv(addv(v, scalev(u1, size)), scalev(u2, size));
                const corner3 = addv(v, scalev(u2, size));
                return '<polyline points="' + corner1.x.toFixed(1) + ',' + corner1.y.toFixed(1) + ' ' + corner2.x.toFixed(1) + ',' + corner2.y.toFixed(1) + ' ' + corner3.x.toFixed(1) + ',' + corner3.y.toFixed(1) + '" fill="none" stroke="#8a5cff" stroke-width="2" />';
            }

            function renderDiagram(q) {
                const cx = 130, cy = 110, r = 80;
                let svg = '';

                if (q.theorem === 'centre') {
                    const O = { x: cx, y: cy };
                    const A = pt(cx, cy, r, 125);
                    const B = pt(cx, cy, r, 55);
                    const C = pt(cx, cy, r, 270);
                    const labelA = pt(cx, cy, r + 18, 125);
                    const labelB = pt(cx, cy, r + 18, 55);
                    const labelC = pt(cx, cy, r + 18, 270);
                    const givenPos = anglePos(O, A, B, 0.4);
                    const qPos = anglePos(C, A, B, 0.35);

                    svg =
                        '<circle cx="' + cx + '" cy="' + cy + '" r="' + r + '" fill="#eaf2ff" stroke="#8a5cff" stroke-width="3" />' +
                        svgLine(O, A) + svgLine(O, B) + svgLine(C, A) + svgLine(C, B) +
                        svgDot(O, 3) + svgDot(A) + svgDot(B) + svgDot(C) +
                        pointLabel({ x: cx, y: cy - 14 }, 'O') +
                        pointLabel(labelA, 'A') + pointLabel(labelB, 'B') + pointLabel(labelC, 'C') +
                        angleLabel(givenPos, q.given + '°', '#8a5cff') +
                        angleLabel(qPos, '?', '#e64980');
                } else if (q.theorem === 'semicircle') {
                    const A = pt(cx, cy, r, 180);
                    const B = pt(cx, cy, r, 0);
                    const C = pt(cx, cy, r, 45);
                    const labelA = pt(cx, cy, r + 18, 180);
                    const labelB = pt(cx, cy, r + 18, 0);
                    const labelC = pt(cx, cy, r + 18, 45);
                    const givenPos = anglePos(A, C, B, 0.35);
                    const qPos = anglePos(B, C, A, 0.35);

                    svg =
                        '<circle cx="' + cx + '" cy="' + cy + '" r="' + r + '" fill="#eaf2ff" stroke="#8a5cff" stroke-width="3" />' +
                        svgLine(A, B) + svgLine(A, C) + svgLine(B, C) +
                        rightAngleMarker(C, A, B, 12) +
                        svgDot(A) + svgDot(B) + svgDot(C) +
                        pointLabel(labelA, 'A') + pointLabel(labelB, 'B') + pointLabel(labelC, 'C') +
                        angleLabel(givenPos, q.given + '°', '#8a5cff') +
                        angleLabel(qPos, '?', '#e64980');
                } else if (q.theorem === 'segment') {
                    const A = pt(cx, cy, r, 125);
                    const B = pt(cx, cy, r, 55);
                    const C = pt(cx, cy, r, 270);
                    const D = pt(cx, cy, r, 240);
                    const labelA = pt(cx, cy, r + 18, 125);
                    const labelB = pt(cx, cy, r + 18, 55);
                    const labelC = pt(cx, cy, r + 18, 270);
                    const labelD = pt(cx, cy, r + 18, 240);
                    const givenPos = anglePos(C, A, B, 0.35);
                    const qPos = anglePos(D, A, B, 0.35);

                    svg =
                        '<circle cx="' + cx + '" cy="' + cy + '" r="' + r + '" fill="#eaf2ff" stroke="#8a5cff" stroke-width="3" />' +
                        svgLine(A, B, '#adb5bd', 2, '4 3') +
                        svgLine(C, A) + svgLine(C, B) + svgLine(D, A) + svgLine(D, B) +
                        svgDot(A) + svgDot(B) + svgDot(C) + svgDot(D) +
                        pointLabel(labelA, 'A') + pointLabel(labelB, 'B') + pointLabel(labelC, 'C') + pointLabel(labelD, 'D') +
                        angleLabel(givenPos, q.given + '°', '#8a5cff') +
                        angleLabel(qPos, '?', '#e64980');
                } else {
                    // cyclic quadrilateral, points in order A, B, C, D around the circle
                    const A = pt(cx, cy, r, 300);
                    const B = pt(cx, cy, r, 20);
                    const C = pt(cx, cy, r, 100);
                    const D = pt(cx, cy, r, 190);
                    const labelA = pt(cx, cy, r + 18, 300);
                    const labelB = pt(cx, cy, r + 18, 20);
                    const labelC = pt(cx, cy, r + 18, 100);
                    const labelD = pt(cx, cy, r + 18, 190);
                    const givenPos = anglePos(A, D, B, 0.4);
                    const qPos = anglePos(C, B, D, 0.4);

                    svg =
                        '<circle cx="' + cx + '" cy="' + cy + '" r="' + r + '" fill="#eaf2ff" stroke="#8a5cff" stroke-width="3" />' +
                        svgLine(A, B) + svgLine(B, C) + svgLine(C, D) + svgLine(D, A) +
                        svgDot(A) + svgDot(B) + svgDot(C) + svgDot(D) +
                        pointLabel(labelA, 'A') + pointLabel(labelB, 'B') + pointLabel(labelC, 'C') + pointLabel(labelD, 'D') +
                        angleLabel(givenPos, q.given + '°', '#8a5cff') +
                        angleLabel(qPos, '?', '#e64980');
                }

                theoremVisual.innerHTML = '<svg viewBox="0 0 260 220" width="300">' + svg + '</svg>';
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

                targetBadge.textContent = q.badge;
                theoremQuestion.textContent = q.prompt;
                renderDiagram(q);

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
                    btn.textContent = choice + '°';
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

            function formulaText(q) {
                if (q.theorem === 'centre') return 'Angle at centre ÷ 2 = ' + q.given + '° ÷ 2 = ' + q.answer + '°.';
                if (q.theorem === 'semicircle') return '90° − ' + q.given + '° = ' + q.answer + '°.';
                if (q.theorem === 'segment') return 'Angles in the same segment are equal: ' + q.answer + '°.';
                return '180° − ' + q.given + '° = ' + q.answer + '°.';
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
                    feedbackText.textContent = formulaText(q);
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
                revealCorrect(q.answer, null);
                feedbackBanner.classList.remove('d-none');
                feedbackText.textContent = "Time's up! " + formulaText(q);
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
                if (pct >= 0.9) message = "Amazing! You're a circle theorems superstar!";
                else if (pct >= 0.6) message = 'Well done — great effort!';

                resultsMessage.textContent = message;
                resultsStats.textContent = 'Paused ' + session.pauseCount + ' time' + (session.pauseCount === 1 ? '' : 's') + ' · Used ' + session.hintCount + ' hint' + (session.hintCount === 1 ? '' : 's') + '.';
            }

            function startGame() {
                if (selectedTheorems.size === 0) {
                    setupError.classList.remove('d-none');
                    return;
                }

                saveSettings();

                session = {
                    questions: buildQuestions(selectedTheorems, settings.questionCount),
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
            if (selectedTheorems.size === 0) {
                selectedTheorems = new Set(ALL_THEOREMS);
            }
            questionCountSelect.value = String(settings.questionCount);
            timeLimitSelect.value = String(settings.timeLimit);
            renderToggles();
        })();
    </script>
@endpush
