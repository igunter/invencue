@extends('layouts.app')

@section('meta_title', 'Story Elements — English Game for Kids')
@section('meta_blurb', 'A free English game for kids — match story words like character, setting and plot to their meanings.')
@section('meta_words', 'story elements game, character setting plot game, story structure for kids, ks1 english game, reading comprehension')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-book',
        'title' => 'Story Elements',
        'subtitle' => 'Read the term, then pick what it means!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Story elements'],
        ],
        'aboutTitle' => 'About this story elements game',
        'aboutText' => 'This free English game helps young kids learn the building blocks of a story — character, setting, plot, beginning, middle and end — so they can talk about the books they read.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Character': 'A person or animal who takes part in the story.',
                'Setting': 'Where and when a story takes place.',
                'Plot': 'The sequence of events that happen in a story.',
                'Beginning': 'The part of a story where we meet the characters and setting.',
                'Middle': 'The part of a story where the problem or exciting events happen.',
                'End': 'The part of a story where the problem is solved.',
                'Problem': 'The challenge or difficulty the main character faces in a story.',
                'Main character': 'The most important character that the story is mainly about.',
                'Narrator': 'The voice that tells the story to the reader.',
                'Villain': 'A character who causes trouble for the main character.',
                'Theme': 'The main message or lesson of a story.',
                'Dialogue': 'The words characters say to each other in a story.',
            };
            const TERM_NAMES = Object.keys(TERMS);

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

            function pickOthers(pool, exclude, count) {
                return shuffle(pool.filter(function(x) { return x !== exclude; })).slice(0, count);
            }

            window.ScienceQuiz.run({
                storageKey: 'story-elementsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                    return {
                        category: type,
                        label: term,
                        correctText: TERMS[term],
                        questionText: "What does '" + term + "' mean in a story?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about the parts every story needs: who, where, when and what happens.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You're a story elements superstar!",
            });
        })();
    </script>
@endpush
