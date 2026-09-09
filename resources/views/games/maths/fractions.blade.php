@extends('layouts.app')

@section('meta_title', 'Fractions & Decimals — Maths Game for Kids')
@section('meta_blurb', 'A free fractions and decimals game for school kids — compare fractions, convert to decimals, and simplify fractions.')
@section('meta_words', 'fractions game, decimals game, simplify fractions, compare fractions, maths game, primary school maths, learn fractions')

@section('title', $category->name)

@push('styles')
    <style>
        .mult-wrap {
            max-width: 720px;
            margin: 0 auto;
        }

        #targetToggles,
        #denomToggles {
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
            background: linear-gradient(160deg, #eef6ff, #f8f5ff);
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
            background: #4e7cff;
            color: #fff;
            margin-bottom: 1rem;
        }

        .frac {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            line-height: 1.1;
            font-weight: 800;
        }

        .frac-num {
            border-bottom: 2px solid currentColor;
            padding: 0 0.3rem 0.15rem;
        }

        .frac-den {
            padding: 0.15rem 0.3rem 0;
        }

        .frac-bar {
            display: flex;
            gap: 2px;
            height: 2.25rem;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 2px solid #4e7cff;
        }

        .frac-cell {
            flex: 1;
            background: #fff;
        }

        .frac-cell.filled {
            background: #4e7cff;
        }

        .compare-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            width: 8rem;
        }

        .compare-label {
            font-size: 1.1rem;
            font-weight: 800;
            color: #495057;
        }

        .compare-row {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1.5rem;
        }

        .compare-vs {
            font-size: 1.5rem;
            font-weight: 800;
            color: #f08c00;
        }

        .single-frac-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        .single-frac-wrap .frac-bar {
            width: 12rem;
        }

        .single-frac-wrap .frac {
            font-size: 2rem;
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
            display: flex;
            align-items: center;
            justify-content: center;
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

            .compare-col {
                width: 6rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-xl pb-2">
        @include('partials.games-header', [
            'category' => $category,
            'game' => $game,
            'blurb' => 'Compare, convert and simplify fractions!'
        ])

        <div class="mult-wrap">
            {{-- Setup screen --}}
            <div id="setupScreen">
                <div class="card mult-setup-card">
                    <div class="card-body p-4">
                        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-pie-chart"></i> Which skills?</h2>
                        <div class="mb-4" id="targetToggles">
                            <button type="button" class="choice-toggle active" data-topic="compare">Compare fractions</button>
                            <button type="button" class="choice-toggle active" data-topic="decimal">Convert to decimal</button>
                            <button type="button" class="choice-toggle active" data-topic="simplify">Simplify fractions</button>
                        </div>

                        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-grid-3x3-gap"></i> Which denominators?</h2>
                        <div class="mb-2" id="denomToggles">
                            @foreach ([2, 3, 4, 5, 6, 8, 10, 12] as $d)
                                <button type="button" class="choice-toggle" data-denom="{{ $d }}">1/{{ $d }}</button>
                            @endforeach
                        </div>

                        <div class="d-flex flex-wrap justify-content-center gap-2 my-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-pick-btn" id="pickEasyBtn">Halves, quarters &amp; tenths</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-pick-btn" id="pickAllDenomsBtn">Select all</button>
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
                                    <option value="10">10 seconds</option>
                                    <option value="15" selected>15 seconds</option>
                                    <option value="20">20 seconds</option>
                                    <option value="30">30 seconds</option>
                                </select>
                            </div>
                        </div>

                        <p class="text-secondary small mt-3 mb-0">Decimal answers are rounded to 2 decimal places.</p>

                        <div id="setupError" class="alert alert-warning mt-3 mb-0 d-none" role="alert">
                            Pick at least one skill and one denominator to play with.
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
                            <span class="target-badge" id="targetBadge">Compare these fractions</span>
                            <div id="fracVisual"></div>
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
            <h2 class="h6 text-secondary text-uppercase mb-2">About this fractions and decimals game</h2>
            <p class="text-secondary small">This free fractions and decimals game helps kids get to grips with comparing fractions, converting fractions to decimals, and simplifying fractions down to their lowest terms. Set your own question count and time limit, then work through a mix of questions covering each skill. Fractions and decimals trip a lot of kids up &mdash; regular short practice like this helps them click into place.</p>
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
            const denomToggles = Array.from(document.querySelectorAll('#denomToggles .choice-toggle'));
            const pickEasyBtn = document.getElementById('pickEasyBtn');
            const pickAllDenomsBtn = document.getElementById('pickAllDenomsBtn');
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
            const fracVisual = document.getElementById('fracVisual');
            const feedbackBanner = document.getElementById('feedbackBanner');
            const feedbackText = document.getElementById('feedbackText');
            const continueBtn = document.getElementById('continueBtn');
            const answerGrid = document.getElementById('answerGrid');
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

            let selectedTopics = new Set();
            let selectedDenoms = new Set();
            let settings = { questionCount: 10, timeLimit: 15 };
            let session = null; // { questions, index, score, timerId, timeLeft }
            let paused = false;

            function getHint(q) {
                if (q.kind === 'compare') return 'Convert both fractions to a common denominator, or compare their decimal values.';
                if (q.kind === 'decimal') return 'Divide the numerator by the denominator.';
                return 'Divide the numerator and denominator by their highest common factor.';
            }

            function loadSettings() {
                try {
                    const raw = localStorage.getItem('fractionsGame.settings');
                    if (!raw) return;
                    const saved = JSON.parse(raw);
                    if (Array.isArray(saved.topics)) {
                        saved.topics.forEach(function(t) {
                            if (t === 'compare' || t === 'decimal' || t === 'simplify') selectedTopics.add(t);
                        });
                    }
                    if (Array.isArray(saved.denoms)) {
                        saved.denoms.forEach(function(d) {
                            if (Number.isInteger(d) && d >= 2 && d <= 12) selectedDenoms.add(d);
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
                    localStorage.setItem('fractionsGame.settings', JSON.stringify({
                        topics: Array.from(selectedTopics),
                        denoms: Array.from(selectedDenoms),
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
                denomToggles.forEach(function(btn) {
                    btn.classList.toggle('active', selectedDenoms.has(parseInt(btn.dataset.denom, 10)));
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

            denomToggles.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const denom = parseInt(btn.dataset.denom, 10);
                    if (selectedDenoms.has(denom)) {
                        selectedDenoms.delete(denom);
                    } else {
                        selectedDenoms.add(denom);
                    }
                    setupError.classList.add('d-none');
                    renderToggles();
                });
            });

            pickEasyBtn.addEventListener('click', function() {
                selectedDenoms = new Set([2, 4, 10]);
                setupError.classList.add('d-none');
                renderToggles();
            });

            pickAllDenomsBtn.addEventListener('click', function() {
                selectedDenoms = new Set([2, 3, 4, 5, 6, 8, 10, 12]);
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

            function gcd(a, b) {
                return b === 0 ? a : gcd(b, a % b);
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

            function randFraction(denomList) {
                const den = denomList[randInt(0, denomList.length - 1)];
                const num = randInt(1, den - 1);
                return { num: num, den: den };
            }

            function generateCompare(denomList) {
                let f1 = randFraction(denomList);
                let f2 = randFraction(denomList);
                if (Math.random() < 0.15) {
                    const scaled = { num: f1.num * 2, den: f1.den * 2 };
                    if (scaled.den <= 24) f2 = scaled;
                }
                const v1 = f1.num / f1.den;
                const v2 = f2.num / f2.den;
                let answer;
                if (Math.abs(v1 - v2) < 1e-9) answer = 'equal';
                else answer = v1 > v2 ? 'a' : 'b';
                return { kind: 'compare', f1: f1, f2: f2, answer: answer };
            }

            function generateDecimal(denomList) {
                const f = randFraction(denomList);
                const answer = Math.round((f.num / f.den) * 100) / 100;
                return { kind: 'decimal', f: f, answer: answer };
            }

            function generateSimplify(denomList) {
                const baseDenoms = denomList.filter(function(d) { return d <= 6; });
                const pool = baseDenoms.length > 0 ? baseDenoms : [2, 3, 4, 5, 6];
                const den0 = pool[randInt(0, pool.length - 1)];
                let num0 = randInt(1, den0 - 1);
                let guard = 0;
                while (gcd(num0, den0) !== 1 && guard < 20) {
                    num0 = randInt(1, den0 - 1);
                    guard++;
                }
                const k = randInt(2, 4);
                return { kind: 'simplify', num: num0 * k, den: den0 * k, num0: num0, den0: den0 };
            }

            function buildQuestions(topics, denoms, count) {
                const topicList = Array.from(topics);
                const denomList = Array.from(denoms);
                const questions = [];
                let lastKey = null;
                for (let i = 0; i < count; i++) {
                    let q, key;
                    let attempts = 0;
                    do {
                        const topic = topicList[randInt(0, topicList.length - 1)];
                        if (topic === 'compare') q = generateCompare(denomList);
                        else if (topic === 'decimal') q = generateDecimal(denomList);
                        else q = generateSimplify(denomList);
                        key = JSON.stringify(q);
                        attempts++;
                    } while (key === lastKey && attempts < 10);
                    lastKey = key;
                    questions.push(q);
                }
                return questions;
            }

            function fracHtml(num, den) {
                return '<span class="frac"><span class="frac-num">' + num + '</span><span class="frac-den">' + den + '</span></span>';
            }

            function barHtml(num, den) {
                let cells = '';
                for (let i = 0; i < den; i++) {
                    cells += '<div class="frac-cell' + (i < num ? ' filled' : '') + '"></div>';
                }
                return '<div class="frac-bar">' + cells + '</div>';
            }

            function buildDecimalChoices(answer) {
                const choices = new Set([answer]);
                const deltas = [0.05, -0.05, 0.1, -0.1, 0.15, -0.15, 0.25, -0.25];
                let idx = 0;
                while (choices.size < 4 && idx < deltas.length) {
                    const candidate = Math.round((answer + deltas[idx]) * 100) / 100;
                    if (candidate > 0 && candidate !== answer) choices.add(candidate);
                    idx++;
                }
                while (choices.size < 4) {
                    const candidate = Math.round((answer + (Math.random() * 0.6 - 0.3)) * 100) / 100;
                    if (candidate > 0) choices.add(candidate);
                }
                const arr = Array.from(choices);
                for (let i = arr.length - 1; i > 0; i--) {
                    const j = randInt(0, i);
                    [arr[i], arr[j]] = [arr[j], arr[i]];
                }
                return arr;
            }

            function buildSimplifyChoices(num0, den0, num, den) {
                const answerStr = num0 + '/' + den0;
                const candidates = new Set([answerStr]);
                candidates.add(num + '/' + den);

                const tries = [
                    (num0 + 1) + '/' + den0,
                    Math.max(1, num0 - 1) + '/' + den0,
                    num0 + '/' + (den0 + 1),
                    (num0 + 2) + '/' + den0,
                ];
                tries.forEach(function(t) {
                    if (candidates.size >= 4) return;
                    const parts = t.split('/');
                    const n = parseInt(parts[0], 10);
                    const d = parseInt(parts[1], 10);
                    if (n >= 1 && d > n && t !== answerStr) candidates.add(t);
                });
                while (candidates.size < 4) {
                    const n = randInt(1, 6);
                    const d = randInt(n + 1, 8);
                    candidates.add(n + '/' + d);
                }
                const arr = Array.from(candidates);
                for (let i = arr.length - 1; i > 0; i--) {
                    const j = randInt(0, i);
                    [arr[i], arr[j]] = [arr[j], arr[i]];
                }
                return arr;
            }

            function renderVisual(q) {
                if (q.kind === 'compare') {
                    fracVisual.innerHTML =
                        '<div class="compare-row">' +
                            '<div class="compare-col"><span class="compare-label">A</span>' + barHtml(q.f1.num, q.f1.den) + fracHtml(q.f1.num, q.f1.den) + '</div>' +
                            '<span class="compare-vs">vs</span>' +
                            '<div class="compare-col"><span class="compare-label">B</span>' + barHtml(q.f2.num, q.f2.den) + fracHtml(q.f2.num, q.f2.den) + '</div>' +
                        '</div>';
                } else if (q.kind === 'decimal') {
                    fracVisual.innerHTML =
                        '<div class="single-frac-wrap">' + barHtml(q.f.num, q.f.den) + fracHtml(q.f.num, q.f.den) + '</div>';
                } else {
                    fracVisual.innerHTML =
                        '<div class="single-frac-wrap">' + barHtml(q.num, q.den) + fracHtml(q.num, q.den) + '</div>';
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

                if (q.kind === 'compare') {
                    targetBadge.textContent = 'Which fraction is bigger?';
                } else if (q.kind === 'decimal') {
                    targetBadge.textContent = 'What is this as a decimal?';
                } else {
                    targetBadge.textContent = 'Simplify this fraction';
                }

                renderVisual(q);

                feedbackBanner.classList.add('d-none');
                feedbackBanner.classList.remove('correct-banner', 'wrong-banner');
                continueBtn.classList.add('d-none');

                let choices, colClass, isHtml;
                if (q.kind === 'compare') {
                    choices = ['a', 'b', 'equal'];
                    for (let i = choices.length - 1; i > 0; i--) {
                        const j = randInt(0, i);
                        [choices[i], choices[j]] = [choices[j], choices[i]];
                    }
                    colClass = 'col-4';
                    isHtml = false;
                } else if (q.kind === 'decimal') {
                    choices = buildDecimalChoices(q.answer);
                    colClass = 'col-6';
                    isHtml = false;
                } else {
                    choices = buildSimplifyChoices(q.num0, q.den0, q.num, q.den);
                    colClass = 'col-6';
                    isHtml = true;
                }

                answerGrid.innerHTML = '';
                choices.forEach(function(choice) {
                    const col = document.createElement('div');
                    col.className = colClass;
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'answer-btn w-100 pop-in';
                    btn.dataset.value = String(choice);
                    if (q.kind === 'compare') {
                        btn.textContent = choice === 'a' ? 'A' : (choice === 'b' ? 'B' : 'Equal');
                    } else if (isHtml) {
                        btn.innerHTML = fracHtml.apply(null, String(choice).split('/').map(Number));
                    } else {
                        btn.textContent = choice;
                    }
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
                    if (btn.dataset.value === String(correctAnswer)) {
                        btn.classList.add('correct');
                    } else if (btn === chosenBtn) {
                        btn.classList.add('wrong');
                    }
                });
            }

            function correctAnswerFor(q) {
                return q.kind === 'simplify' ? (q.num0 + '/' + q.den0) : q.answer;
            }

            function formulaText(q) {
                if (q.kind === 'compare') {
                    const label = q.answer === 'equal' ? 'they are equal' : (q.answer === 'a' ? 'A is bigger' : 'B is bigger');
                    return q.f1.num + '/' + q.f1.den + ' = ' + Math.round((q.f1.num / q.f1.den) * 100) / 100 + ', ' + q.f2.num + '/' + q.f2.den + ' = ' + Math.round((q.f2.num / q.f2.den) * 100) / 100 + ' — ' + label + '.';
                }
                if (q.kind === 'decimal') {
                    return q.f.num + ' ÷ ' + q.f.den + ' = ' + q.answer;
                }
                const factor = q.num / q.num0;
                return q.num + '/' + q.den + ' ÷ ' + factor + ' = ' + q.num0 + '/' + q.den0;
            }

            function handleAnswer(choice, btn) {
                stopTimer();
                session.answered = true;
                pauseBtn.classList.add('d-none');
                hintRow.classList.add('d-none');
                lockAnswers();
                const q = session.questions[session.index];
                const correctAnswer = correctAnswerFor(q);
                const isCorrect = String(choice) === String(correctAnswer);

                revealCorrect(correctAnswer, isCorrect ? null : btn);

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
                hintRow.classList.add('d-none');
                lockAnswers();
                const q = session.questions[session.index];
                const correctAnswer = correctAnswerFor(q);
                revealCorrect(correctAnswer, null);
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
                if (pct >= 0.9) message = "Amazing! You're a fractions superstar!";
                else if (pct >= 0.6) message = 'Well done — great effort!';

                resultsMessage.textContent = message;
                resultsStats.textContent = 'Paused ' + session.pauseCount + ' time' + (session.pauseCount === 1 ? '' : 's') + ' · Used ' + session.hintCount + ' hint' + (session.hintCount === 1 ? '' : 's') + '.';
            }

            function startGame() {
                if (selectedTopics.size === 0 || selectedDenoms.size === 0) {
                    setupError.classList.remove('d-none');
                    return;
                }

                saveSettings();

                session = {
                    questions: buildQuestions(selectedTopics, selectedDenoms, settings.questionCount),
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
                selectedTopics = new Set(['compare', 'decimal', 'simplify']);
            }
            if (selectedDenoms.size === 0) {
                selectedDenoms = new Set([2, 3, 4, 5, 6, 8]);
            }
            questionCountSelect.value = String(settings.questionCount);
            timeLimitSelect.value = String(settings.timeLimit);
            renderToggles();
        })();
    </script>
@endpush
