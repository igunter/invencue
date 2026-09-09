@extends('layouts.app')

@section('meta_title', 'Shape Recognition — 2D & 3D Shapes Game for Kids')
@section('meta_blurb', 'A free shape recognition game for kids — name 2D and 3D shapes, or count their sides.')
@section('meta_words', 'shape recognition game, 2d shapes game, 3d shapes game, sides and corners, primary school maths, learn shapes')

@section('title', $category->name)

@push('styles')
    <style>
        .mult-wrap {
            max-width: 720px;
            margin: 0 auto;
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
            background: #3ecb8c;
            color: #fff;
            margin-bottom: 1rem;
        }

        .rec-shape-svg-wrap {
            display: flex;
            justify-content: center;
        }

        .rec-shape-svg-wrap svg {
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
            'blurb' => 'Pick your question types, then name the shape!'
        ])

        <div class="mult-wrap">
            {{-- Setup screen --}}
            <div id="setupScreen">
                <div class="card mult-setup-card">
                    <div class="card-body p-4">
                        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-question-circle"></i> Which questions?</h2>
                        <div class="mb-4" id="typeToggles">
                            <button type="button" class="choice-toggle active" data-type="name2d">Name the 2D shape</button>
                            <button type="button" class="choice-toggle active" data-type="name3d">Name the 3D shape</button>
                            <button type="button" class="choice-toggle active" data-type="sides">Count the sides</button>
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
                            Pick at least one question type to play with.
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
                            <span class="target-badge" id="targetBadge">WHAT SHAPE IS THIS?</span>
                            <div class="rec-shape-svg-wrap" id="shapeVisual2"></div>
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
            <h2 class="h6 text-secondary text-uppercase mb-2">About this shape recognition game</h2>
            <p class="text-secondary small">This free shape recognition game helps kids learn to name common 2D and 3D shapes and count their sides. Choose which question types to include, set your question count and time limit, then work through as many shapes as you can. It's a solid foundation for later geometry topics like area, perimeter and volume.</p>
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
            const shapeVisual2 = document.getElementById('shapeVisual2');
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

            const VALID_TYPES = ['name2d', 'name3d', 'sides'];

            const SHAPES_2D = [
                { name: 'Triangle', sides: 3 },
                { name: 'Square', sides: 4 },
                { name: 'Rectangle', sides: 4 },
                { name: 'Pentagon', sides: 5 },
                { name: 'Hexagon', sides: 6 },
                { name: 'Octagon', sides: 8 },
            ];

            const SHAPES_3D = ['Cube', 'Cuboid', 'Sphere', 'Cylinder', 'Cone', 'Pyramid'];

            let selectedTypes = new Set();
            let settings = { questionCount: 10, timeLimit: 15 };
            let session = null; // { questions, index, score, timerId, timeLeft }
            let paused = false;

            function getHint(q) {
                if (q.type === 'name2d') return 'Count the straight sides and corners to help work out which shape it is.';
                if (q.type === 'sides') return 'Trace around the edge of the shape and count each straight line.';
                return 'Think about whether it has flat faces, curved surfaces, or both.';
            }

            function loadSettings() {
                try {
                    const raw = localStorage.getItem('shapeRecognitionGame.settings');
                    if (!raw) return;
                    const saved = JSON.parse(raw);
                    if (Array.isArray(saved.types)) {
                        saved.types.forEach(function(t) {
                            if (VALID_TYPES.indexOf(t) !== -1) selectedTypes.add(t);
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
                    localStorage.setItem('shapeRecognitionGame.settings', JSON.stringify({
                        types: Array.from(selectedTypes),
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

            function pickFrom(arr) { return arr[randInt(0, arr.length - 1)]; }

            function buildQuestion(types) {
                const type = types[randInt(0, types.length - 1)];

                if (type === 'name2d' || type === 'sides') {
                    const shape = pickFrom(SHAPES_2D);
                    if (type === 'name2d') {
                        return { type: type, shape: shape, correctText: shape.name, badge: 'WHAT SHAPE IS THIS?' };
                    }
                    return { type: type, shape: shape, correctText: String(shape.sides), badge: 'HOW MANY SIDES?' };
                }

                // name3d
                const name = pickFrom(SHAPES_3D);
                return { type: type, shapeName: name, correctText: name, badge: 'WHAT SHAPE IS THIS?' };
            }

            function buildQuestions(types, count) {
                const typeList = Array.from(types);
                const questions = [];
                let lastKey = null;
                for (let i = 0; i < count; i++) {
                    let q, key;
                    let attempts = 0;
                    do {
                        q = buildQuestion(typeList);
                        key = q.type + ':' + q.correctText;
                        attempts++;
                    } while (key === lastKey && attempts < 10);
                    lastKey = key;
                    questions.push(q);
                }
                return questions;
            }

            function shuffle(arr) {
                const out = arr.slice();
                for (let i = out.length - 1; i > 0; i--) {
                    const j = randInt(0, i);
                    [out[i], out[j]] = [out[j], out[i]];
                }
                return out;
            }

            function buildChoices(q) {
                const correct = q.correctText;
                let pool = [];

                if (q.type === 'name2d') {
                    pool = SHAPES_2D.map(function(s) { return s.name; }).filter(function(n) { return n !== correct; });
                } else if (q.type === 'name3d') {
                    pool = SHAPES_3D.filter(function(n) { return n !== correct; });
                } else {
                    const sides = q.shape.sides;
                    pool = [sides - 1, sides + 1, sides + 2, sides - 2, sides + 3, sides + 4]
                        .filter(function(n) { return n > 0 && String(n) !== correct; })
                        .map(function(n) { return String(n); });
                }

                const shuffledPool = shuffle(pool);
                const distractors = [];
                shuffledPool.forEach(function(item) {
                    if (distractors.length < 3 && distractors.indexOf(item) === -1) {
                        distractors.push(item);
                    }
                });

                let fillOffset = 5;
                while (distractors.length < 3) {
                    let candidate;
                    if (q.type === 'sides') {
                        candidate = String(Math.max(1, q.shape.sides + fillOffset));
                    } else {
                        candidate = correct + ' ' + fillOffset;
                    }
                    if (candidate !== correct && distractors.indexOf(candidate) === -1) {
                        distractors.push(candidate);
                    }
                    fillOffset++;
                }

                return shuffle([correct].concat(distractors));
            }

            function polygonPoints(cx, cy, r, sides, rotationDeg) {
                const pts = [];
                for (let i = 0; i < sides; i++) {
                    const deg = rotationDeg + (360 / sides) * i;
                    const rad = deg * Math.PI / 180;
                    pts.push((cx + r * Math.cos(rad)).toFixed(1) + ',' + (cy + r * Math.sin(rad)).toFixed(1));
                }
                return pts.join(' ');
            }

            function render2DShape(shape) {
                let svg;
                if (shape.name === 'Rectangle') {
                    svg = '<svg viewBox="0 0 200 200" width="200"><rect x="40" y="70" width="120" height="60" fill="#eaf2ff" stroke="#3ecb8c" stroke-width="4" /></svg>';
                } else {
                    const points = polygonPoints(100, 100, 70, shape.sides, -90);
                    svg = '<svg viewBox="0 0 200 200" width="200"><polygon points="' + points + '" fill="#eaf2ff" stroke="#3ecb8c" stroke-width="4" stroke-linejoin="round" /></svg>';
                }
                shapeVisual2.innerHTML = svg;
            }

            function renderBox3D(w, h, depth) {
                const dx = depth * 0.6;
                const dy = depth * 0.4;
                const padLeft = 30;
                const frontTopY = 60;
                const frontBottomY = frontTopY + h;

                const t1x = padLeft, t1y = frontTopY;
                const t2x = padLeft + dx, t2y = frontTopY - dy;
                const t3x = padLeft + w + dx, t3y = frontTopY - dy;
                const t4x = padLeft + w, t4y = frontTopY;

                const s1x = padLeft + w, s1y = frontTopY;
                const s2x = padLeft + w, s2y = frontBottomY;
                const s3x = padLeft + w + dx, s3y = frontBottomY - dy;
                const s4x = t3x, s4y = t3y;

                return (
                    '<svg viewBox="0 0 200 200" width="200">' +
                        '<polygon points="' + s1x + ',' + s1y + ' ' + s2x + ',' + s2y + ' ' + s3x + ',' + s3y + ' ' + s4x + ',' + s4y + '" fill="#dbe9ff" stroke="#3ecb8c" stroke-width="3" stroke-linejoin="round" />' +
                        '<polygon points="' + t1x + ',' + t1y + ' ' + t2x + ',' + t2y + ' ' + t3x + ',' + t3y + ' ' + t4x + ',' + t4y + '" fill="#f2f8ff" stroke="#3ecb8c" stroke-width="3" stroke-linejoin="round" />' +
                        '<rect x="' + padLeft + '" y="' + frontTopY + '" width="' + w + '" height="' + h + '" fill="#eaf2ff" stroke="#3ecb8c" stroke-width="3" />' +
                    '</svg>'
                );
            }

            function renderCylinder3D() {
                const rx = 45;
                const ry = rx * 0.4;
                const heightPx = 90;
                const padLeft = 30;
                const topY = 40;
                const cx = padLeft + rx;
                const bottomY = topY + heightPx;

                return (
                    '<svg viewBox="0 0 200 200" width="200">' +
                        '<line x1="' + (cx - rx) + '" y1="' + topY + '" x2="' + (cx - rx) + '" y2="' + bottomY + '" stroke="#3ecb8c" stroke-width="3" />' +
                        '<line x1="' + (cx + rx) + '" y1="' + topY + '" x2="' + (cx + rx) + '" y2="' + bottomY + '" stroke="#3ecb8c" stroke-width="3" />' +
                        '<path d="M ' + (cx - rx) + ' ' + bottomY + ' A ' + rx + ' ' + ry + ' 0 0 0 ' + (cx + rx) + ' ' + bottomY + '" fill="#eaf2ff" stroke="#3ecb8c" stroke-width="3" />' +
                        '<rect x="' + (cx - rx) + '" y="' + topY + '" width="' + (rx * 2) + '" height="' + heightPx + '" fill="#eaf2ff" stroke="none" opacity="0.6" />' +
                        '<ellipse cx="' + cx + '" cy="' + topY + '" rx="' + rx + '" ry="' + ry + '" fill="#eaf2ff" stroke="#3ecb8c" stroke-width="3" />' +
                    '</svg>'
                );
            }

            function renderPyramid3D() {
                return (
                    '<svg viewBox="0 0 200 200" width="200">' +
                        '<polygon points="100,40 40,160 160,160" fill="#eaf2ff" stroke="#3ecb8c" stroke-width="4" stroke-linejoin="round" />' +
                        '<line x1="55" y1="150" x2="175" y2="150" stroke="#3ecb8c" stroke-width="3" />' +
                        '<line x1="40" y1="160" x2="55" y2="150" stroke="#3ecb8c" stroke-width="2" />' +
                        '<line x1="160" y1="160" x2="175" y2="150" stroke="#3ecb8c" stroke-width="2" />' +
                    '</svg>'
                );
            }

            function render3DShape(name) {
                let svg;
                if (name === 'Cube') {
                    svg = renderBox3D(70, 70, 70);
                } else if (name === 'Cuboid') {
                    svg = renderBox3D(100, 55, 55);
                } else if (name === 'Cylinder') {
                    svg = renderCylinder3D();
                } else if (name === 'Sphere') {
                    svg = '<svg viewBox="0 0 200 200" width="200"><circle cx="100" cy="100" r="70" fill="#eaf2ff" stroke="#3ecb8c" stroke-width="4" /><ellipse cx="80" cy="70" rx="25" ry="15" fill="#fff" opacity="0.6" /></svg>';
                } else if (name === 'Cone') {
                    svg = '<svg viewBox="0 0 200 200" width="200"><ellipse cx="100" cy="150" rx="60" ry="15" fill="#eaf2ff" stroke="#3ecb8c" stroke-width="4" /><line x1="40" y1="150" x2="100" y2="40" stroke="#3ecb8c" stroke-width="4" /><line x1="160" y1="150" x2="100" y2="40" stroke="#3ecb8c" stroke-width="4" /></svg>';
                } else {
                    svg = renderPyramid3D();
                }
                shapeVisual2.innerHTML = svg;
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
                if (q.type === 'name3d') {
                    render3DShape(q.shapeName);
                } else {
                    render2DShape(q.shape);
                }

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

            function revealCorrect(correctText, chosenBtn) {
                Array.from(answerGrid.querySelectorAll('.answer-btn')).forEach(function(btn) {
                    if (btn.textContent === correctText) {
                        btn.classList.add('correct');
                    } else if (btn === chosenBtn) {
                        btn.classList.add('wrong');
                    }
                });
            }

            function explanationText(q) {
                if (q.type === 'sides') {
                    return 'This shape has ' + q.correctText + ' sides.';
                }
                return 'This is a ' + q.correctText + '.';
            }

            function handleAnswer(choice, btn) {
                stopTimer();
                session.answered = true;
                pauseBtn.classList.add('d-none');
                hintRow.classList.add('d-none');
                lockAnswers();
                const q = session.questions[session.index];
                const isCorrect = choice === q.correctText;

                revealCorrect(q.correctText, isCorrect ? null : btn);

                feedbackBanner.classList.remove('d-none');
                if (isCorrect) {
                    session.score++;
                    feedbackText.textContent = pickPraise();
                    feedbackBanner.classList.add('correct-banner');
                    playCorrectSound();
                } else {
                    feedbackText.textContent = explanationText(q);
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
                revealCorrect(q.correctText, null);
                feedbackBanner.classList.remove('d-none');
                feedbackText.textContent = "Time's up! " + explanationText(q);
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
                if (pct >= 0.9) message = "Amazing! You're a shapes superstar!";
                else if (pct >= 0.6) message = 'Well done — great effort!';

                resultsMessage.textContent = message;
                resultsStats.textContent = 'Paused ' + session.pauseCount + ' time' + (session.pauseCount === 1 ? '' : 's') + ' · Used ' + session.hintCount + ' hint' + (session.hintCount === 1 ? '' : 's') + '.';
            }

            function startGame() {
                if (selectedTypes.size === 0) {
                    setupError.classList.remove('d-none');
                    return;
                }

                saveSettings();

                session = {
                    questions: buildQuestions(selectedTypes, settings.questionCount),
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
                selectedTypes = new Set(['name2d', 'name3d', 'sides']);
            }
            questionCountSelect.value = String(settings.questionCount);
            timeLimitSelect.value = String(settings.timeLimit);
            renderToggles();
        })();
    </script>
@endpush
