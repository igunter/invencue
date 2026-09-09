@extends('layouts.app')

@section('meta_title', 'Perspective Basics — Art Game for Kids')
@section('meta_blurb', 'A free art game covering perspective basics — vanishing point, foreground, background and horizon line.')
@section('meta_words', 'perspective drawing game, vanishing point quiz, foreground background, ks3 art game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-signpost-split',
        'title' => 'Perspective Basics',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Perspective terms'],
        ],
        'aboutTitle' => 'About this perspective basics game',
        'aboutText' => 'This free art game covers the basics of drawing perspective, including vanishing points, the horizon line, foreground, background and how artists show depth.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Vanishing point': 'The point on the horizon where parallel lines in a drawing appear to meet.',
                'Horizon line': 'The horizontal line in a drawing where the sky appears to meet the ground.',
                'Foreground': 'The part of a picture that appears closest to the viewer.',
                'Background': 'The part of a picture that appears furthest away from the viewer.',
                'Middle ground': 'The part of a picture between the foreground and the background.',
                'One-point perspective': 'A drawing technique using a single vanishing point on the horizon.',
                'Two-point perspective': 'A drawing technique using two vanishing points on the horizon.',
                'Overlapping': 'Placing one object in front of another to show which is closer.',
                'Scale': 'Making objects smaller as they get further away to show distance.',
                'Eye level': 'The height from which the viewer is looking, matching the horizon line in a drawing.',
                'Depth': 'The illusion of distance between near and far objects in a flat picture.',
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
                storageKey: 'perspective-basicsGame.settings',
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
                        questionText: "What does '" + term + "' mean in a drawing?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Picture a drawing of a road disappearing into the distance — where does each part sit?';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You're a perspective superstar!",
            });
        })();
    </script>
@endpush
