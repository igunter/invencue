/**
 * Shared engine for the GCSE science quiz games (Science Games section).
 * Each game page defines a small config object (question bank + builder
 * functions) and calls ScienceQuiz.run(config) — this file owns the
 * setup/timer/pause/hint/results UI so that logic isn't duplicated across
 * every subject's game page.
 */
window.ScienceQuiz = (function () {
    function randInt(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    function shuffle(arr) {
        const out = arr.slice();
        for (let i = out.length - 1; i > 0; i--) {
            const j = randInt(0, i);
            [out[i], out[j]] = [out[j], out[i]];
        }
        return out;
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
            osc.onended = function () { ctx.close(); };
        } catch (e) {
            // audio unavailable — not fatal
        }
    }

    function playCorrectSound() {
        playTone(660, 0.12, 'triangle');
        setTimeout(function () { playTone(880, 0.15, 'triangle'); }, 100);
    }

    function playWrongSound() {
        playTone(220, 0.25, 'sawtooth');
    }

    function pickPraise() {
        const options = ['Nice one! 🎉', 'Correct! 🌟', 'Great job! 👏', "You've got it! 🚀", 'Brilliant! ⭐'];
        return options[randInt(0, options.length - 1)];
    }

    function run(config) {
        const el = function (id) { return document.getElementById(id); };

        const setupScreen = el('setupScreen');
        const gameScreen = el('gameScreen');
        const resultsScreen = el('resultsScreen');

        const typeToggles = Array.from(document.querySelectorAll('#typeToggles .choice-toggle'));
        const questionCountSelect = el('questionCountSelect');
        const timeLimitSelect = el('timeLimitSelect');
        const setupError = el('setupError');
        const startBtn = el('startBtn');
        const quitBtn = el('quitBtn');

        const questionProgress = el('questionProgress');
        const scoreDisplay = el('scoreDisplay');
        const timerTrack = el('timerTrack');
        const timerFill = el('timerFill');
        const quizBadge = el('quizBadge');
        const questionTextDisplay = el('questionTextDisplay');
        const feedbackBanner = el('feedbackBanner');
        const feedbackText = el('feedbackText');
        const answerGrid = el('answerGrid');
        const continueBtn = el('continueBtn');
        const pauseBtn = el('pauseBtn');
        const pauseOverlay = el('pauseOverlay');
        const hintBtn = el('hintBtn');
        const hintText = el('hintText');
        const hintRow = el('hintRow');

        const resultsStars = el('resultsStars');
        const resultsScore = el('resultsScore');
        const resultsMessage = el('resultsMessage');
        const resultsStats = el('resultsStats');
        const playAgainBtn = el('playAgainBtn');
        const changeSettingsBtn = el('changeSettingsBtn');

        const VALID_TYPES = config.types;

        let selectedTypes = new Set();
        let settings = { questionCount: config.defaultQuestionCount || 10, timeLimit: config.defaultTimeLimit || 20 };
        let session = null; // { questions, index, score, timerId, timeLeft, pauseCount, hintCount }
        let paused = false;

        function loadSettings() {
            try {
                const raw = localStorage.getItem(config.storageKey);
                if (!raw) return;
                const saved = JSON.parse(raw);
                if (Array.isArray(saved.types)) {
                    saved.types.forEach(function (t) {
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
                localStorage.setItem(config.storageKey, JSON.stringify({
                    types: Array.from(selectedTypes),
                    questionCount: settings.questionCount,
                    timeLimit: settings.timeLimit,
                }));
            } catch (e) {
                // ignore unavailable storage
            }
        }

        function renderToggles() {
            typeToggles.forEach(function (btn) {
                btn.classList.toggle('active', selectedTypes.has(btn.dataset.type));
            });
        }

        typeToggles.forEach(function (btn) {
            btn.addEventListener('click', function () {
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

        questionCountSelect.addEventListener('change', function () {
            settings.questionCount = parseInt(questionCountSelect.value, 10);
        });

        timeLimitSelect.addEventListener('change', function () {
            settings.timeLimit = parseInt(timeLimitSelect.value, 10);
        });

        function buildQuestions(types, count) {
            const typeList = Array.from(types);
            const questions = [];
            let lastKey = null;
            for (let i = 0; i < count; i++) {
                let q, key;
                let attempts = 0;
                do {
                    const type = typeList[randInt(0, typeList.length - 1)];
                    q = config.buildQuestion(type);
                    key = q.category + ':' + q.correctText;
                    attempts++;
                } while (key === lastKey && attempts < 10);
                lastKey = key;
                questions.push(q);
            }
            return questions;
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
            session.timerId = setInterval(function () { tickTimer(limit); }, 100);
        }

        function resumeTimer() {
            const limit = settings.timeLimit;
            if (!limit || !session) return;
            session.timerId = setInterval(function () { tickTimer(limit); }, 100);
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
                Array.from(answerGrid.querySelectorAll('.answer-btn')).forEach(function (btn) {
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

            if (q.badge) {
                quizBadge.textContent = q.badge;
                quizBadge.className = 'quiz-badge' + (q.badgeVariant ? ' quiz-badge-' + q.badgeVariant : '');
                quizBadge.classList.remove('d-none');
            } else {
                quizBadge.classList.add('d-none');
            }
            questionTextDisplay.textContent = q.questionText;

            feedbackBanner.classList.add('d-none');
            feedbackBanner.classList.remove('correct-banner', 'wrong-banner');
            continueBtn.classList.add('d-none');

            const choices = config.buildChoices(q);
            answerGrid.innerHTML = '';
            choices.forEach(function (choice) {
                const col = document.createElement('div');
                col.className = config.gridColClass || 'col-12';
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'answer-btn w-100 pop-in';
                btn.textContent = choice;
                btn.addEventListener('click', function () { handleAnswer(choice, btn); });
                col.appendChild(btn);
                answerGrid.appendChild(col);
            });

            startTimer();
        }

        function lockAnswers() {
            Array.from(answerGrid.querySelectorAll('.answer-btn')).forEach(function (btn) {
                btn.disabled = true;
            });
        }

        function revealCorrect(correctText, chosenBtn) {
            Array.from(answerGrid.querySelectorAll('.answer-btn')).forEach(function (btn) {
                if (btn.textContent === correctText) {
                    btn.classList.add('correct');
                } else if (btn === chosenBtn) {
                    btn.classList.add('wrong');
                }
            });
        }

        function explanationText(q) {
            if (config.explanationFor) return config.explanationFor(q);
            return q.correctText;
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

            const quizShellRoot = document.getElementById('quizShellRoot');
            if (quizShellRoot && window.InvencueResults) {
                window.InvencueResults.submit(quizShellRoot.dataset.categorySlug, quizShellRoot.dataset.gameSlug, score, total);
            }

            resultsScore.textContent = score + ' / ' + total;

            let stars = 1;
            if (pct >= 0.9) stars = 3;
            else if (pct >= 0.6) stars = 2;
            resultsStars.textContent = '⭐'.repeat(stars) + '☆'.repeat(3 - stars);

            let message = 'Keep revising — you\'ll get there!';
            if (pct >= 0.9) message = config.masteryMessage || "Amazing! You've mastered this topic!";
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

        playAgainBtn.addEventListener('click', function () {
            resultsScreen.classList.add('d-none');
            startGame();
        });

        changeSettingsBtn.addEventListener('click', function () {
            resultsScreen.classList.add('d-none');
            setupScreen.classList.remove('d-none');
        });

        quitBtn.addEventListener('click', function () {
            stopTimer();
            paused = false;
            pauseOverlay.classList.add('d-none');
            gameScreen.classList.add('d-none');
            setupScreen.classList.remove('d-none');
        });

        continueBtn.addEventListener('click', function () {
            continueBtn.classList.add('d-none');
            nextQuestion();
        });

        pauseBtn.addEventListener('click', togglePause);

        hintBtn.addEventListener('click', function () {
            session.hintCount++;
            hintText.textContent = config.hintFor(session.questions[session.index]);
            hintText.classList.remove('d-none');
        });

        loadSettings();
        if (selectedTypes.size === 0) {
            selectedTypes = new Set(VALID_TYPES);
        }
        questionCountSelect.value = String(settings.questionCount);
        timeLimitSelect.value = String(settings.timeLimit);
        renderToggles();
    }

    return { run: run };
})();
