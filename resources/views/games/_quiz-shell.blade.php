@php
    $tier = $tier ?? 'gcse';
@endphp
{{-- Shared setup/game/results markup for a science quiz game, driven by
     public/js/science-quiz.js. Pass $tier ('basic', 'intermediate' or
     'gcse' — controls the accent colour), $subtitle (used as the
     games-header blurb), $typeToggles (array of ['id' => ..., 'label' =>
     ...]), $aboutTitle and $aboutText — the including page's own script
     configures and starts window.ScienceQuiz against these same element
     ids. $category and $game come from the controller that rendered the
     including page. --}}
<div class="container-xl pb-2 quiz-tier-{{ $tier }}" id="quizShellRoot" data-category-slug="{{ $category->slug }}" data-game-slug="{{ $game->slug }}">
    @include('partials.games-header', [
        'category' => $category,
        'game' => $game,
        'blurb' => $subtitle,
    ])

    <div class="mult-wrap">
        {{-- Setup screen --}}
        <div id="setupScreen">
            <div class="card mult-setup-card">
                <div class="card-body p-4">
                    <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-question-circle"></i> Which questions?</h2>
                    <div class="mb-4" id="typeToggles">
                        @foreach ($typeToggles as $toggle)
                            <button type="button" class="choice-toggle active" data-type="{{ $toggle['id'] }}">{{ $toggle['label'] }}</button>
                        @endforeach
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
                        <span class="quiz-badge d-none" id="quizBadge"></span>
                        <p class="question-text-display" id="questionTextDisplay"></p>
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
        <h2 class="h6 text-secondary text-uppercase mb-2">{{ $aboutTitle }}</h2>
        <p class="text-secondary small">{{ $aboutText }}</p>
    </div>
</div>
