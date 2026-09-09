@extends('layouts.app')

@section('meta_title', 'Trigonometry — Right Triangles Game')
@section('meta_blurb', 'A free trigonometry game — practise Pythagoras\' theorem and SOH-CAH-TOA to find missing sides of a right-angled triangle.')
@section('meta_words', 'trigonometry game, pythagoras game, soh cah toa, right triangle game, sin cos tan, gcse maths game, learn trigonometry')

@section('title', $category->name)

@push('styles')
    <style>
        .mult-wrap {
            max-width: 720px;
            margin: 0 auto;
        }

        #targetToggles,
        #ratioToggles,
        #angleToggles {
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
            background: linear-gradient(160deg, #eef2ff, #eef6ff);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
        }

        .target-badge {
            display: inline-block;
            font-size: 1rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            padding: 0.35rem 1rem;
            border-radius: 2rem;
            background: #7048e8;
            color: #fff;
            margin-bottom: 1rem;
        }

        .tri-svg-wrap {
            display: flex;
            justify-content: center;
        }

        .tri-svg-wrap svg {
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
            font-size: 1.1rem;
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
            'blurb' => 'Use Pythagoras\' theorem and SOH-CAH-TOA to find the missing side!'
        ])

        <div class="mult-wrap">
            {{-- Setup screen --}}
            <div id="setupScreen">
                <div class="card mult-setup-card">
                    <div class="card-body p-4">
                        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-triangle"></i> Which topics?</h2>
                        <div class="mb-4" id="targetToggles">
                            <button type="button" class="choice-toggle active" data-topic="pythagoras">Pythagoras' theorem</button>
                            <button type="button" class="choice-toggle active" data-topic="trig">Trigonometry (SOH-CAH-TOA)</button>
                        </div>

                        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-calculator"></i> Which ratios? <span class="text-secondary small text-lowercase">(for trigonometry questions)</span></h2>
                        <div class="mb-4" id="ratioToggles">
                            <button type="button" class="choice-toggle active" data-ratio="sin">Sine</button>
                            <button type="button" class="choice-toggle active" data-ratio="cos">Cosine</button>
                            <button type="button" class="choice-toggle active" data-ratio="tan">Tangent</button>
                        </div>

                        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-triangle"></i> Which angles? <span class="text-secondary small text-lowercase">(for trigonometry questions)</span></h2>
                        <div class="mb-2" id="angleToggles">
                            @foreach ([15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75] as $deg)
                                <button type="button" class="choice-toggle" data-angle="{{ $deg }}">{{ $deg }}&deg;</button>
                            @endforeach
                        </div>

                        <div class="d-flex flex-wrap justify-content-center gap-2 my-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-pick-btn" id="pickNiceBtn">30&deg;, 45&deg; &amp; 60&deg;</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-pick-btn" id="pickAllAnglesBtn">Select all</button>
                        </div>

                        <div class="row g-4 mt-2">
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

                        <p class="text-secondary small mt-3 mb-0">Trigonometry answers are rounded to 1 decimal place.</p>

                        <div id="setupError" class="alert alert-warning mt-3 mb-0 d-none" role="alert">
                            Pick at least one topic — and if Trigonometry is on, at least one ratio and one angle.
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
                            <span class="target-badge" id="targetBadge">Find the missing side</span>
                            <div class="tri-svg-wrap" id="triVisual"></div>
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
            <h2 class="h6 text-secondary text-uppercase mb-2">About this trigonometry game</h2>
            <p class="text-secondary small">This free trigonometry game gives GCSE-level students practice finding the missing side of a right-angled triangle using Pythagoras' theorem and SOH-CAH-TOA. Each question presents a triangle with two known values &mdash; work out whether to use Pythagoras or sin, cos or tan, then calculate the missing side. It's a quick way to build speed and confidence ahead of exams.</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const setupScreen = document.getElementById('setupScreen');
            const gameScreen = document.getElementById('gameScreen');
            const resultsScreen = document.getElementById('resultsScreen');

            const topicToggles = Array.from(document.querySelectorAll('#targetToggles .choice-toggle'));
            const ratioToggles = Array.from(document.querySelectorAll('#ratioToggles .choice-toggle'));
            const angleToggles = Array.from(document.querySelectorAll('#angleToggles .choice-toggle'));
            const pickNiceBtn = document.getElementById('pickNiceBtn');
            const pickAllAnglesBtn = document.getElementById('pickAllAnglesBtn');
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
            const triVisual = document.getElementById('triVisual');
            const feedbackBanner = document.getElementById('feedbackBanner');
            const feedbackText = document.getElementById('feedbackText');
            const continueBtn = document.getElementById('continueBtn');
            const answerGrid = document.getElementById('answerGrid');
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

            const TRIPLES = [[3,4,5],[6,8,10],[9,12,15],[5,12,13],[8,15,17],[7,24,25],[20,21,29]];
            const SIDE_NAMES = { opp: 'Opposite', adj: 'Adjacent', hyp: 'Hypotenuse' };
            const RATIO_PAIR = { sin: ['opp', 'hyp'], cos: ['adj', 'hyp'], tan: ['opp', 'adj'] };
            const TRIG_CONFIGS = [
                { ratio: 'sin', given: 'hyp', unknown: 'opp' },
                { ratio: 'sin', given: 'opp', unknown: 'hyp' },
                { ratio: 'cos', given: 'hyp', unknown: 'adj' },
                { ratio: 'cos', given: 'adj', unknown: 'hyp' },
                { ratio: 'tan', given: 'adj', unknown: 'opp' },
                { ratio: 'tan', given: 'opp', unknown: 'adj' },
            ];

            let selectedTopics = new Set();
            let selectedRatios = new Set();
            let selectedAngles = new Set();
            let settings = { questionCount: 10, timeLimit: 30 };
            let session = null; // { questions, index, score, timerId, timeLeft }
            let paused = false;

            function getHint(q) {
                if (q.kind === 'pythagoras') {
                    return "Use Pythagoras' theorem: a² + b² = c² (c is the hypotenuse, the longest side).";
                }
                return 'Use SOH-CAH-TOA: choose sin, cos or tan based on which two sides you know relative to the angle.';
            }

            function loadSettings() {
                try {
                    const raw = localStorage.getItem('trigonometryGame.settings');
                    if (!raw) return;
                    const saved = JSON.parse(raw);
                    if (Array.isArray(saved.topics)) {
                        saved.topics.forEach(function(t) {
                            if (t === 'pythagoras' || t === 'trig') selectedTopics.add(t);
                        });
                    }
                    if (Array.isArray(saved.ratios)) {
                        saved.ratios.forEach(function(r) {
                            if (RATIO_PAIR[r]) selectedRatios.add(r);
                        });
                    }
                    if (Array.isArray(saved.angles)) {
                        saved.angles.forEach(function(a) {
                            if (Number.isInteger(a) && a > 0 && a < 90) selectedAngles.add(a);
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
                    localStorage.setItem('trigonometryGame.settings', JSON.stringify({
                        topics: Array.from(selectedTopics),
                        ratios: Array.from(selectedRatios),
                        angles: Array.from(selectedAngles),
                        questionCount: settings.questionCount,
                        timeLimit: settings.timeLimit,
                    }));
                } catch (e) {
                    // ignore unavailable storage
                }
            }

            function renderToggles() {
                topicToggles.forEach(function(btn) {
                    btn.classList.toggle('active', selectedTopics.has(btn.dataset.topic));
                });
                ratioToggles.forEach(function(btn) {
                    btn.classList.toggle('active', selectedRatios.has(btn.dataset.ratio));
                });
                angleToggles.forEach(function(btn) {
                    btn.classList.toggle('active', selectedAngles.has(parseInt(btn.dataset.angle, 10)));
                });
            }

            topicToggles.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const topic = btn.dataset.topic;
                    if (selectedTopics.has(topic)) {
                        selectedTopics.delete(topic);
                    } else {
                        selectedTopics.add(topic);
                    }
                    setupError.classList.add('d-none');
                    renderToggles();
                });
            });

            ratioToggles.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const ratio = btn.dataset.ratio;
                    if (selectedRatios.has(ratio)) {
                        selectedRatios.delete(ratio);
                    } else {
                        selectedRatios.add(ratio);
                    }
                    setupError.classList.add('d-none');
                    renderToggles();
                });
            });

            angleToggles.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const angle = parseInt(btn.dataset.angle, 10);
                    if (selectedAngles.has(angle)) {
                        selectedAngles.delete(angle);
                    } else {
                        selectedAngles.add(angle);
                    }
                    setupError.classList.add('d-none');
                    renderToggles();
                });
            });

            pickNiceBtn.addEventListener('click', function() {
                selectedAngles = new Set([30, 45, 60]);
                setupError.classList.add('d-none');
                renderToggles();
            });

            pickAllAnglesBtn.addEventListener('click', function() {
                selectedAngles = new Set([15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75]);
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

            function generatePythagoras() {
                const triple = TRIPLES[randInt(0, TRIPLES.length - 1)].slice();
                const unknownIndex = randInt(0, 2);
                return {
                    kind: 'pythagoras',
                    legA: triple[0],
                    legB: triple[1],
                    hyp: triple[2],
                    unknownIndex: unknownIndex,
                    answer: triple[unknownIndex],
                };
            }

            function generateTrig(ratios, angles) {
                const ratioList = Array.from(ratios);
                const angleList = Array.from(angles);
                const configs = TRIG_CONFIGS.filter(function(c) { return ratioList.indexOf(c.ratio) !== -1; });
                const cfg = configs[randInt(0, configs.length - 1)];
                const angle = angleList[randInt(0, angleList.length - 1)];
                const given = randInt(5, 20);
                const rad = angle * Math.PI / 180;

                let opp, adj, hyp;
                if (cfg.given === 'hyp') {
                    hyp = given;
                    opp = hyp * Math.sin(rad);
                    adj = hyp * Math.cos(rad);
                } else if (cfg.given === 'opp') {
                    opp = given;
                    hyp = cfg.ratio === 'sin' ? opp / Math.sin(rad) : null;
                    adj = cfg.ratio === 'tan' ? opp / Math.tan(rad) : (hyp !== null ? Math.sqrt(Math.max(hyp * hyp - opp * opp, 0)) : null);
                } else {
                    adj = given;
                    hyp = cfg.ratio === 'cos' ? adj / Math.cos(rad) : null;
                    opp = cfg.ratio === 'tan' ? adj * Math.tan(rad) : (hyp !== null ? Math.sqrt(Math.max(hyp * hyp - adj * adj, 0)) : null);
                }

                if (hyp === null) {
                    hyp = Math.sqrt(opp * opp + adj * adj);
                }

                const values = { opp: opp, adj: adj, hyp: hyp };
                const answer = Math.round(values[cfg.unknown] * 10) / 10;

                return {
                    kind: 'trig',
                    ratio: cfg.ratio,
                    angle: angle,
                    givenSide: cfg.given,
                    unknownSide: cfg.unknown,
                    givenValue: given,
                    opp: values.opp,
                    adj: values.adj,
                    hyp: values.hyp,
                    answer: answer,
                };
            }

            function buildQuestions(topics, ratios, angles, count) {
                const topicList = Array.from(topics);
                const questions = [];
                let lastKey = null;
                for (let i = 0; i < count; i++) {
                    let q, key;
                    let attempts = 0;
                    do {
                        const topic = topicList[randInt(0, topicList.length - 1)];
                        q = topic === 'pythagoras' ? generatePythagoras() : generateTrig(ratios, angles);
                        key = JSON.stringify(q);
                        attempts++;
                    } while (key === lastKey && attempts < 10);
                    lastKey = key;
                    questions.push(q);
                }
                return questions;
            }

            function buildChoices(answer, isDecimal) {
                const choices = new Set([answer]);
                const deltas = isDecimal
                    ? [0.5, -0.5, 1, -1, 1.5, -1.5, 2, -2]
                    : [1, -1, 2, -2, 3, -3, 5, -5];
                let idx = 0;
                while (choices.size < 4 && idx < deltas.length) {
                    const candidate = Math.round((answer + deltas[idx]) * 10) / 10;
                    if (candidate > 0 && candidate !== answer) choices.add(candidate);
                    idx++;
                }
                while (choices.size < 4) {
                    const candidate = Math.round((answer + (Math.random() * 6 - 3)) * 10) / 10;
                    if (candidate > 0) choices.add(candidate);
                }
                const arr = Array.from(choices);
                for (let i = arr.length - 1; i > 0; i--) {
                    const j = randInt(0, i);
                    [arr[i], arr[j]] = [arr[j], arr[i]];
                }
                return arr;
            }

            function renderPythagorasTriangle(q) {
                const maxLeg = Math.max(q.legA, q.legB);
                const scale = 110 / maxLeg;
                const legAw = q.legA * scale;
                const legBh = q.legB * scale;
                const padLeft = 45;
                const padTop = 20;
                const viewW = legAw + padLeft + 30;
                const viewH = legBh + padTop + 30;

                const bl = { x: padLeft, y: padTop + legBh };
                const br = { x: padLeft + legAw, y: padTop + legBh };
                const tl = { x: padLeft, y: padTop };

                const legALabel = q.unknownIndex === 0 ? '?' : q.legA;
                const legBLabel = q.unknownIndex === 1 ? '?' : q.legB;
                const hypLabel = q.unknownIndex === 2 ? '?' : q.hyp;

                const svg =
                    '<svg viewBox="0 0 ' + viewW + ' ' + viewH + '" width="' + Math.min(viewW * 2, 320) + '">' +
                        '<polygon points="' + tl.x + ',' + tl.y + ' ' + bl.x + ',' + bl.y + ' ' + br.x + ',' + br.y + '" fill="#eaf2ff" stroke="#7048e8" stroke-width="3" />' +
                        '<rect x="' + bl.x + '" y="' + (bl.y - 14) + '" width="14" height="14" fill="none" stroke="#7048e8" stroke-width="2" />' +
                        '<text x="' + ((bl.x + br.x) / 2) + '" y="' + (bl.y + 20) + '" text-anchor="middle" font-size="15" font-weight="700" fill="#495057">' + legALabel + '</text>' +
                        '<text x="' + (bl.x - 12) + '" y="' + ((bl.y + tl.y) / 2 + 5) + '" text-anchor="end" font-size="15" font-weight="700" fill="#495057">' + legBLabel + '</text>' +
                        '<text x="' + ((tl.x + br.x) / 2 + 10) + '" y="' + ((tl.y + br.y) / 2 - 6) + '" text-anchor="middle" font-size="15" font-weight="700" fill="#495057">' + hypLabel + '</text>' +
                    '</svg>';

                triVisual.innerHTML = svg;
            }

            function renderTrigTriangle(q) {
                const maxSide = Math.max(q.opp, q.adj);
                const scale = 110 / maxSide;
                const adjW = q.adj * scale;
                const oppH = q.opp * scale;
                const padLeft = 45;
                const padTop = 20;
                const viewW = adjW + padLeft + 40;
                const viewH = oppH + padTop + 30;

                const A = { x: padLeft, y: padTop + oppH };
                const B = { x: padLeft + adjW, y: padTop + oppH };
                const C = { x: padLeft + adjW, y: padTop };

                const oppLabel = q.unknownSide === 'opp' ? '?' : (Math.round(q.opp * 10) / 10);
                const adjLabel = q.unknownSide === 'adj' ? '?' : (Math.round(q.adj * 10) / 10);
                const hypLabel = q.unknownSide === 'hyp' ? '?' : (Math.round(q.hyp * 10) / 10);

                const arcR = 24;
                const arcRad = q.angle * Math.PI / 180;
                const arcEndX = A.x + arcR * Math.cos(arcRad);
                const arcEndY = A.y - arcR * Math.sin(arcRad);
                const svg =
                    '<svg viewBox="0 0 ' + viewW + ' ' + viewH + '" width="' + Math.min(viewW * 2, 320) + '">' +
                        '<polygon points="' + A.x + ',' + A.y + ' ' + B.x + ',' + B.y + ' ' + C.x + ',' + C.y + '" fill="#eaf2ff" stroke="#7048e8" stroke-width="3" />' +
                        '<rect x="' + (B.x - 14) + '" y="' + (B.y - 14) + '" width="14" height="14" fill="none" stroke="#7048e8" stroke-width="2" />' +
                        '<path d="M ' + (A.x + arcR) + ' ' + A.y + ' A ' + arcR + ' ' + arcR + ' 0 0 0 ' + arcEndX + ' ' + arcEndY + '" fill="none" stroke="#7048e8" stroke-width="2" />' +
                        '<text x="' + (A.x + 26) + '" y="' + (A.y - 10) + '" font-size="13" font-weight="700" fill="#7048e8">' + q.angle + '&#176;</text>' +
                        '<text x="' + ((A.x + B.x) / 2) + '" y="' + (A.y + 20) + '" text-anchor="middle" font-size="15" font-weight="700" fill="#495057">' + adjLabel + '</text>' +
                        '<text x="' + (B.x + 12) + '" y="' + ((B.y + C.y) / 2 + 5) + '" text-anchor="start" font-size="15" font-weight="700" fill="#495057">' + oppLabel + '</text>' +
                        '<text x="' + ((A.x + C.x) / 2 - 10) + '" y="' + ((A.y + C.y) / 2) + '" text-anchor="middle" font-size="15" font-weight="700" fill="#495057">' + hypLabel + '</text>' +
                    '</svg>';

                triVisual.innerHTML = svg;
            }

            function renderTriangle(q) {
                if (q.kind === 'pythagoras') {
                    renderPythagorasTriangle(q);
                } else {
                    renderTrigTriangle(q);
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

                targetBadge.textContent = q.kind === 'pythagoras' ? 'Pythagoras — find the missing side' : 'Find the missing side (' + q.ratio + ')';
                renderTriangle(q);

                feedbackBanner.classList.add('d-none');
                feedbackBanner.classList.remove('correct-banner', 'wrong-banner');
                continueBtn.classList.add('d-none');

                const choices = buildChoices(q.answer, q.kind === 'trig');
                answerGrid.innerHTML = '';
                choices.forEach(function(choice) {
                    const col = document.createElement('div');
                    col.className = 'col-6';
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'answer-btn w-100 pop-in';
                    btn.textContent = choice;
                    btn.dataset.value = choice;
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
                    if (parseFloat(btn.dataset.value) === correctAnswer) {
                        btn.classList.add('correct');
                    } else if (btn === chosenBtn) {
                        btn.classList.add('wrong');
                    }
                });
            }

            function formulaText(q) {
                if (q.kind === 'pythagoras') {
                    if (q.unknownIndex === 2) {
                        return q.legA + '² + ' + q.legB + '² = ' + q.hyp + '² (' + (q.legA * q.legA) + ' + ' + (q.legB * q.legB) + ' = ' + (q.hyp * q.hyp) + ')';
                    }
                    const knownLeg = q.unknownIndex === 0 ? q.legB : q.legA;
                    const unknownLeg = q.answer;
                    return knownLeg + '² + ' + unknownLeg + '² = ' + q.hyp + '² (' + (knownLeg * knownLeg) + ' + ' + (unknownLeg * unknownLeg) + ' = ' + (q.hyp * q.hyp) + ')';
                }

                const ratioValue = Math.round(Math[q.ratio](q.angle * Math.PI / 180) * 100) / 100;
                const givenName = SIDE_NAMES[q.givenSide];
                const unknownName = SIDE_NAMES[q.unknownSide];
                const multiply = q.givenSide === 'hyp' || (q.ratio === 'tan' && q.givenSide === 'adj');
                const op = multiply ? '×' : '÷';
                return q.ratio + '(' + q.angle + '°) ≈ ' + ratioValue + ' → ' + unknownName + ' = ' + givenName + ' ' + op + ' ' + q.ratio + '(' + q.angle + '°) = ' + q.givenValue + ' ' + op + ' ' + ratioValue + ' = ' + q.answer;
            }

            function handleAnswer(choice, btn) {
                stopTimer();
                session.answered = true;
                pauseBtn.classList.add('d-none');
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
                    feedbackText.textContent = 'Not quite — ' + formulaText(q);
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
                if (pct >= 0.9) message = "Amazing! You're a trigonometry superstar!";
                else if (pct >= 0.6) message = 'Well done — great effort!';

                resultsMessage.textContent = message;
                resultsStats.textContent = 'Paused ' + session.pauseCount + ' time' + (session.pauseCount === 1 ? '' : 's') + ' · Used ' + session.hintCount + ' hint' + (session.hintCount === 1 ? '' : 's') + '.';
            }

            function startGame() {
                const needsTrig = selectedTopics.has('trig');
                if (selectedTopics.size === 0 || (needsTrig && (selectedRatios.size === 0 || selectedAngles.size === 0))) {
                    setupError.classList.remove('d-none');
                    return;
                }

                saveSettings();

                session = {
                    questions: buildQuestions(selectedTopics, selectedRatios, selectedAngles, settings.questionCount),
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
            if (selectedTopics.size === 0) {
                selectedTopics = new Set(['pythagoras', 'trig']);
            }
            if (selectedRatios.size === 0) {
                selectedRatios = new Set(['sin', 'cos', 'tan']);
            }
            if (selectedAngles.size === 0) {
                selectedAngles = new Set([30, 45, 60]);
            }
            questionCountSelect.value = String(settings.questionCount);
            timeLimitSelect.value = String(settings.timeLimit);
            renderToggles();
        })();
    </script>
@endpush
