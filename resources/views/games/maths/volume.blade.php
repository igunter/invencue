@extends('layouts.app')

@section('meta_title', 'Volume — 3D Shapes Game for Kids')
@section('meta_blurb', 'A free volume game for school kids — pick your 3D shapes and dimensions, then calculate the volume of cubes, cuboids, cylinders and triangular prisms.')
@section('meta_words', 'volume game, 3D shapes game, cuboid volume, cylinder volume, gcse maths game, learn volume')

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
            background: #4e7cff;
            border-color: transparent;
            color: #fff;
            box-shadow: 0 4px 10px rgba(78, 124, 255, 0.35);
        }

        #shapeToggles,
        #targetToggles {
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
            background: #4e7cff;
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
            background: #4e7cff;
            border: none;
        }

        .start-btn:hover:not(:disabled),
        .start-btn:focus:not(:disabled) {
            background: #3a68eb;
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
            background: #4e7cff;
            color: #fff;
            margin-bottom: 1rem;
        }

        .shape-svg-wrap {
            display: flex;
            justify-content: center;
        }

        .shape-svg-wrap svg {
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
            color: #4e7cff;
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
            'blurb' => 'Pick your shapes, then work out the volume!'
        ])

        <div class="mult-wrap">
            {{-- Setup screen --}}
            <div id="setupScreen">
                <div class="card mult-setup-card">
                    <div class="card-body p-4">
                        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-box-seam"></i> Which shapes?</h2>
                        <div class="mb-4" id="shapeToggles">
                            <button type="button" class="choice-toggle active" data-shape="cube">Cube</button>
                            <button type="button" class="choice-toggle active" data-shape="cuboid">Cuboid</button>
                            <button type="button" class="choice-toggle active" data-shape="cylinder">Cylinder</button>
                            <button type="button" class="choice-toggle active" data-shape="prism">Triangular Prism</button>
                        </div>

                        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-grid-3x3-gap"></i> Which dimensions (1&ndash;10)?</h2>
                        <div class="mb-2" id="tableToggles">
                            @for ($i = 1; $i <= 10; $i++)
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
                                    <option value="20" selected>20 seconds</option>
                                    <option value="30">30 seconds</option>
                                    <option value="45">45 seconds</option>
                                    <option value="60">60 seconds</option>
                                </select>
                            </div>
                        </div>

                        <div id="setupError" class="alert alert-warning mt-4 mb-0 d-none" role="alert">
                            Pick at least one shape and one dimension to play with.
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
                            <span class="target-badge" id="targetBadge">CUBE</span>
                            <div class="small text-secondary mb-2 d-none" id="approxNote">Use &pi; &asymp; 3.14, round to the nearest whole number</div>
                            <div class="shape-svg-wrap" id="shapeVisual"></div>
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
            <h2 class="h6 text-secondary text-uppercase mb-2">About this volume game</h2>
            <p class="text-secondary small">This free volume game gives kids practice applying the volume formulas for cubes, cuboids, cylinders and triangular prisms. Pick which shapes and dimensions to include, set your question count and time limit, then calculate the volume of each shape shown. It's a solid way to build the 3D measurement skills that come up throughout KS3 and GCSE maths.</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const setupScreen = document.getElementById('setupScreen');
            const gameScreen = document.getElementById('gameScreen');
            const resultsScreen = document.getElementById('resultsScreen');

            const shapeToggles = Array.from(document.querySelectorAll('#shapeToggles .choice-toggle'));
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
            const targetBadge = document.getElementById('targetBadge');
            const approxNote = document.getElementById('approxNote');
            const shapeVisual = document.getElementById('shapeVisual');
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

            const VALID_SHAPES = ['cube', 'cuboid', 'cylinder', 'prism'];

            let selectedShapes = new Set();
            let selectedDims = new Set();
            let settings = { questionCount: 10, timeLimit: 20 };
            let session = null; // { questions, index, score, timerId, timeLeft }
            let paused = false;

            function getHint(q) {
                if (q.shape === 'cube') return 'Volume = side³ (side × side × side).';
                if (q.shape === 'cuboid') return 'Volume = length × width × height.';
                if (q.shape === 'cylinder') return 'Volume = π × r² × height.';
                return 'Volume = area of the triangular end × length of the prism.';
            }

            function loadSettings() {
                try {
                    const raw = localStorage.getItem('volumeGame.settings');
                    if (!raw) return;
                    const saved = JSON.parse(raw);
                    if (Array.isArray(saved.dims)) {
                        saved.dims.forEach(function(t) {
                            if (Number.isInteger(t) && t >= 1 && t <= 10) selectedDims.add(t);
                        });
                    }
                    if (Array.isArray(saved.shapes)) {
                        saved.shapes.forEach(function(s) {
                            if (VALID_SHAPES.indexOf(s) !== -1) selectedShapes.add(s);
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
                    localStorage.setItem('volumeGame.settings', JSON.stringify({
                        dims: Array.from(selectedDims),
                        shapes: Array.from(selectedShapes),
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
                    btn.classList.toggle('active', selectedDims.has(t));
                });
                shapeToggles.forEach(function(btn) {
                    btn.classList.toggle('active', selectedShapes.has(btn.dataset.shape));
                });
            }

            tableToggles.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const t = parseInt(btn.dataset.table, 10);
                    if (selectedDims.has(t)) {
                        selectedDims.delete(t);
                    } else {
                        selectedDims.add(t);
                    }
                    setupError.classList.add('d-none');
                    renderToggles();
                });
            });

            shapeToggles.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const shape = btn.dataset.shape;
                    if (selectedShapes.has(shape)) {
                        selectedShapes.delete(shape);
                    } else {
                        selectedShapes.add(shape);
                    }
                    setupError.classList.add('d-none');
                    renderToggles();
                });
            });

            pickAllBtn.addEventListener('click', function() {
                selectedDims = new Set([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
                setupError.classList.add('d-none');
                renderToggles();
            });

            pickNoneBtn.addEventListener('click', function() {
                selectedDims = new Set();
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

            function buildQuestion(shapes, dims) {
                const shape = shapes[randInt(0, shapes.length - 1)];
                const d = function() { return dims[randInt(0, dims.length - 1)]; };

                if (shape === 'cube') {
                    const s = d();
                    return { shape: shape, s: s, answer: s * s * s, label: 'CUBE' };
                }
                if (shape === 'cuboid') {
                    const l = d(), w = d(), h = d();
                    return { shape: shape, l: l, w: w, h: h, answer: l * w * h, label: 'CUBOID' };
                }
                if (shape === 'cylinder') {
                    // keep radius small (1-6) so numbers stay sane even if dims pool goes to 10
                    const rPool = dims.filter(function(n) { return n <= 6; });
                    const pool = rPool.length ? rPool : dims;
                    const r = pool[randInt(0, pool.length - 1)];
                    const h = d();
                    const answer = Math.round(Math.PI * r * r * h);
                    return { shape: shape, r: r, h: h, answer: answer, label: 'CYLINDER', approx: true };
                }
                // prism: base * triHeight must be even so 0.5 * base * triHeight is a whole number
                let base = d(), triHeight = d();
                if ((base * triHeight) % 2 !== 0) {
                    triHeight = triHeight + 1 <= 10 ? triHeight + 1 : Math.max(1, triHeight - 1);
                }
                const length = d();
                const crossArea = 0.5 * base * triHeight;
                const answer = crossArea * length;
                return { shape: shape, base: base, triHeight: triHeight, length: length, answer: answer, label: 'TRIANGULAR PRISM' };
            }

            function buildQuestions(shapes, dims, count) {
                const shapeList = Array.from(shapes);
                const dimList = Array.from(dims);
                const questions = [];
                let lastKey = null;
                for (let i = 0; i < count; i++) {
                    let q, key;
                    let attempts = 0;
                    do {
                        q = buildQuestion(shapeList, dimList);
                        key = q.shape + ':' + q.answer + ':' + (q.s || '') + (q.l || '') + (q.w || '') + (q.h || '') + (q.r || '') + (q.base || '') + (q.triHeight || '') + (q.length || '');
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

                const scaleStep = Math.max(1, Math.round(answer * 0.1));
                const deltas = [1, -1, 2, -2, 3, -3, scaleStep, -scaleStep, scaleStep * 2, -scaleStep * 2, 5, -5, 10, -10];
                let idx = 0;
                while (choices.size < 4 && idx < deltas.length) {
                    const candidate = answer + deltas[idx];
                    if (candidate > 0 && candidate !== answer) choices.add(candidate);
                    idx++;
                }
                while (choices.size < 4) {
                    const candidate = answer + randInt(-scaleStep * 3, scaleStep * 3);
                    if (candidate > 0 && candidate !== answer) choices.add(candidate);
                }
                const arr = Array.from(choices);
                for (let i = arr.length - 1; i > 0; i--) {
                    const j = randInt(0, i);
                    [arr[i], arr[j]] = [arr[j], arr[i]];
                }
                return arr;
            }

            function renderBox(w, h, depth) {
                const scale = 10;
                const dx = depth * 6;
                const dy = depth * 4;
                const padLeft = 45;
                const frontTopY = 45;
                const rectW = w * scale;
                const rectH = h * scale;
                const frontBottomY = frontTopY + rectH;

                const viewW = padLeft + rectW + dx + 25;
                const viewH = frontBottomY + 25;

                // top face parallelogram
                const t1x = padLeft, t1y = frontTopY;
                const t2x = padLeft + dx, t2y = frontTopY - dy;
                const t3x = padLeft + rectW + dx, t3y = frontTopY - dy;
                const t4x = padLeft + rectW, t4y = frontTopY;

                // side face parallelogram
                const s1x = padLeft + rectW, s1y = frontTopY;
                const s2x = padLeft + rectW, s2y = frontBottomY;
                const s3x = padLeft + rectW + dx, s3y = frontBottomY - dy;
                const s4x = t3x, s4y = t3y;

                const depthLabelX = (t4x + t3x) / 2 + 8;
                const depthLabelY = (t4y + t3y) / 2 - 4;

                return (
                    '<svg viewBox="0 0 ' + viewW + ' ' + viewH + '" width="' + Math.min(viewW * 2, 340) + '">' +
                        '<polygon points="' + s1x + ',' + s1y + ' ' + s2x + ',' + s2y + ' ' + s3x + ',' + s3y + ' ' + s4x + ',' + s4y + '" fill="#dbe9ff" stroke="#4e7cff" stroke-width="3" stroke-linejoin="round" />' +
                        '<polygon points="' + t1x + ',' + t1y + ' ' + t2x + ',' + t2y + ' ' + t3x + ',' + t3y + ' ' + t4x + ',' + t4y + '" fill="#f2f8ff" stroke="#4e7cff" stroke-width="3" stroke-linejoin="round" />' +
                        '<rect x="' + padLeft + '" y="' + frontTopY + '" width="' + rectW + '" height="' + rectH + '" fill="#eaf2ff" stroke="#4e7cff" stroke-width="3" />' +
                        '<text x="' + (padLeft + rectW / 2) + '" y="' + (frontBottomY + 20) + '" text-anchor="middle" font-size="16" font-weight="700" fill="#495057">' + w + '</text>' +
                        '<text x="' + (padLeft - 10) + '" y="' + (frontTopY + rectH / 2 + 5) + '" text-anchor="end" font-size="16" font-weight="700" fill="#495057">' + h + '</text>' +
                        '<text x="' + depthLabelX + '" y="' + depthLabelY + '" text-anchor="start" font-size="16" font-weight="700" fill="#495057">' + depth + '</text>' +
                    '</svg>'
                );
            }

            function renderCylinder(r, h) {
                const rScale = 13;
                const heightScale = 11;
                const rx = Math.max(20, r * rScale);
                const ry = rx * 0.38;
                const heightPx = h * heightScale;
                const padLeft = 25;
                const topY = 32;
                const cx = padLeft + rx;
                const bottomY = topY + heightPx;
                const heightLabelX = cx + rx + 32;

                const viewW = heightLabelX + 20;
                const viewH = bottomY + 20;

                return (
                    '<svg viewBox="0 0 ' + viewW + ' ' + viewH + '" width="' + Math.min(viewW * 2, 340) + '">' +
                        '<line x1="' + (cx - rx) + '" y1="' + topY + '" x2="' + (cx - rx) + '" y2="' + bottomY + '" stroke="#4e7cff" stroke-width="3" />' +
                        '<line x1="' + (cx + rx) + '" y1="' + topY + '" x2="' + (cx + rx) + '" y2="' + bottomY + '" stroke="#4e7cff" stroke-width="3" />' +
                        '<path d="M ' + (cx - rx) + ' ' + bottomY + ' A ' + rx + ' ' + ry + ' 0 0 0 ' + (cx + rx) + ' ' + bottomY + '" fill="#eaf2ff" stroke="#4e7cff" stroke-width="3" />' +
                        '<rect x="' + (cx - rx) + '" y="' + topY + '" width="' + (rx * 2) + '" height="' + heightPx + '" fill="#eaf2ff" stroke="none" opacity="0.6" />' +
                        '<ellipse cx="' + cx + '" cy="' + topY + '" rx="' + rx + '" ry="' + ry + '" fill="#eaf2ff" stroke="#4e7cff" stroke-width="3" />' +
                        '<line x1="' + cx + '" y1="' + topY + '" x2="' + (cx + rx) + '" y2="' + topY + '" stroke="#495057" stroke-width="2" stroke-dasharray="4,3" />' +
                        '<text x="' + (cx + rx / 2) + '" y="' + (topY - 8) + '" text-anchor="middle" font-size="14" font-weight="700" fill="#495057">r = ' + r + '</text>' +
                        '<line x1="' + heightLabelX + '" y1="' + topY + '" x2="' + heightLabelX + '" y2="' + bottomY + '" stroke="#495057" stroke-width="2" stroke-dasharray="4,3" />' +
                        '<text x="' + (heightLabelX + 6) + '" y="' + (topY + heightPx / 2 + 5) + '" text-anchor="start" font-size="14" font-weight="700" fill="#495057">h = ' + h + '</text>' +
                    '</svg>'
                );
            }

            function renderPrism(base, triHeight, length) {
                const scale = 11;
                const lenDx = length * 7;
                const lenDy = length * 5;
                const padLeft = 40;
                const baseY = 120;

                const ax = padLeft, ay = baseY;
                const bx = padLeft + base * scale, by = baseY;
                const cx = padLeft, cy = baseY - triHeight * scale;

                const ax2 = ax + lenDx, ay2 = ay - lenDy;
                const bx2 = bx + lenDx, by2 = by - lenDy;
                const cx2 = cx + lenDx, cy2 = cy - lenDy;

                const viewW = bx2 + 25;
                const viewH = baseY + 25;

                const lenLabelX = (bx + bx2) / 2 + 8;
                const lenLabelY = (by + by2) / 2 + 2;

                return (
                    '<svg viewBox="0 0 ' + viewW + ' ' + viewH + '" width="' + Math.min(viewW * 2, 340) + '">' +
                        '<polygon points="' + cx + ',' + cy + ' ' + cx2 + ',' + cy2 + ' ' + bx2 + ',' + by2 + ' ' + bx + ',' + by + '" fill="#f2f8ff" stroke="#4e7cff" stroke-width="2" stroke-linejoin="round" />' +
                        '<line x1="' + ax + '" y1="' + ay + '" x2="' + ax2 + '" y2="' + ay2 + '" stroke="#4e7cff" stroke-width="2" stroke-dasharray="4,3" />' +
                        '<polygon points="' + ax2 + ',' + ay2 + ' ' + bx2 + ',' + by2 + ' ' + cx2 + ',' + cy2 + '" fill="none" stroke="#4e7cff" stroke-width="2" />' +
                        '<polygon points="' + ax + ',' + ay + ' ' + bx + ',' + by + ' ' + cx + ',' + cy + '" fill="#eaf2ff" stroke="#4e7cff" stroke-width="3" stroke-linejoin="round" />' +
                        '<text x="' + ((ax + bx) / 2) + '" y="' + (ay + 20) + '" text-anchor="middle" font-size="15" font-weight="700" fill="#495057">' + base + '</text>' +
                        '<text x="' + (ax - 10) + '" y="' + ((ay + cy) / 2 + 5) + '" text-anchor="end" font-size="15" font-weight="700" fill="#495057">' + triHeight + '</text>' +
                        '<text x="' + lenLabelX + '" y="' + lenLabelY + '" text-anchor="start" font-size="14" font-weight="700" fill="#495057">' + length + '</text>' +
                    '</svg>'
                );
            }

            function renderShape(q) {
                let svg = '';
                if (q.shape === 'cube') {
                    svg = renderBox(q.s, q.s, q.s);
                } else if (q.shape === 'cuboid') {
                    svg = renderBox(q.l, q.h, q.w);
                } else if (q.shape === 'cylinder') {
                    svg = renderCylinder(q.r, q.h);
                } else {
                    svg = renderPrism(q.base, q.triHeight, q.length);
                }
                shapeVisual.innerHTML = svg;
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

            function unitSuffix() {
                return ' u³';
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

                targetBadge.textContent = q.label;
                approxNote.classList.toggle('d-none', !q.approx);
                renderShape(q);

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
                    btn.textContent = choice + unitSuffix();
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
                if (q.shape === 'cube') {
                    return 'Volume = s³ = ' + q.s + '³ = ' + q.answer + ' u³';
                }
                if (q.shape === 'cuboid') {
                    return 'Volume = l × w × h = ' + q.l + ' × ' + q.w + ' × ' + q.h + ' = ' + q.answer + ' u³';
                }
                if (q.shape === 'cylinder') {
                    return 'Volume = π × r² × h ≈ 3.14 × ' + q.r + '² × ' + q.h + ' ≈ ' + q.answer + ' u³';
                }
                return 'Volume = ½ × base × height × length = ½ × ' + q.base + ' × ' + q.triHeight + ' × ' + q.length + ' = ' + q.answer + ' u³';
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
                if (pct >= 0.9) message = "Amazing! You're a volume superstar!";
                else if (pct >= 0.6) message = 'Well done — great effort!';

                resultsMessage.textContent = message;
                resultsStats.textContent = 'Paused ' + session.pauseCount + ' time' + (session.pauseCount === 1 ? '' : 's') + ' · Used ' + session.hintCount + ' hint' + (session.hintCount === 1 ? '' : 's') + '.';
            }

            function startGame() {
                if (selectedShapes.size === 0 || selectedDims.size === 0) {
                    setupError.classList.remove('d-none');
                    return;
                }

                saveSettings();

                session = {
                    questions: buildQuestions(selectedShapes, selectedDims, settings.questionCount),
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
            if (selectedDims.size === 0) {
                selectedDims = new Set([2, 3, 4, 5, 6]);
            }
            if (selectedShapes.size === 0) {
                selectedShapes = new Set(['cube', 'cuboid', 'cylinder', 'prism']);
            }
            questionCountSelect.value = String(settings.questionCount);
            timeLimitSelect.value = String(settings.timeLimit);
            renderToggles();
        })();
    </script>
@endpush
