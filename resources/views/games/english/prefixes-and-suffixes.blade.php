@extends('layouts.app')

@section('meta_title', 'Prefixes & Suffixes — English Game for Kids')
@section('meta_blurb', 'A free English game for kids — work out what a prefix or suffix means and how it changes a word.')
@section('meta_words', 'prefixes and suffixes game, word building game, ks2 english game, ks3 english game, vocabulary game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-diagram-3',
        'title' => 'Prefixes & Suffixes',
        'subtitle' => 'Pick your question types, then work out the meaning!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'prefixes', 'label' => 'Prefixes'],
            ['id' => 'suffixes', 'label' => 'Suffixes'],
        ],
        'aboutTitle' => 'About this prefixes & suffixes game',
        'aboutText' => 'This free English game explores how prefixes (added to the start of a word) and suffixes (added to the end) change a word\'s meaning. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const PREFIXES = {
                're-': 'Again (e.g. redo = do again)',
                'un-': 'Not (e.g. unhappy = not happy)',
                'pre-': 'Before (e.g. preview = see before)',
                'dis-': 'Not or opposite of (e.g. disagree = not agree)',
                'mis-': 'Wrongly (e.g. misspell = spell wrongly)',
                'over-': 'Too much (e.g. overcook = cook too much)',
                'anti-': 'Against (e.g. antisocial = against being social)',
                'sub-': 'Under (e.g. submarine = under the sea)',
                'inter-': 'Between (e.g. international = between nations)',
                'auto-': 'Self (e.g. autograph = self-written signature)',
            };
            const PREFIX_NAMES = Object.keys(PREFIXES);

            const SUFFIXES = {
                '-ful': 'Full of (e.g. joyful = full of joy)',
                '-less': 'Without (e.g. hopeless = without hope)',
                '-able': 'Able to be (e.g. washable = able to be washed)',
                '-ment': 'The result or act of (e.g. enjoyment)',
                '-ness': 'A state or quality of (e.g. happiness)',
                '-tion': 'The act or process of (e.g. celebration)',
                '-er': 'A person who does something (e.g. teacher = one who teaches)',
                '-ly': 'In a certain way (e.g. quickly = in a quick way)',
                '-ist': 'A person who does or believes in something (e.g. scientist)',
                '-ology': 'The study of (e.g. biology = the study of life)',
            };
            const SUFFIX_NAMES = Object.keys(SUFFIXES);

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
                storageKey: 'prefixes-and-suffixesGame.settings',
                types: ['prefixes', 'suffixes'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'prefixes') {
                        const p = PREFIX_NAMES[randInt(0, PREFIX_NAMES.length - 1)];
                        return {
                            category: type,
                            label: p,
                            correctText: PREFIXES[p],
                            questionText: "What does the prefix '" + p + "' mean?",
                        };
                    }
                    const s = SUFFIX_NAMES[randInt(0, SUFFIX_NAMES.length - 1)];
                    return {
                        category: type,
                        label: s,
                        correctText: SUFFIXES[s],
                        questionText: "What does the suffix '" + s + "' mean?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'prefixes') {
                        const distractors = pickOthers(PREFIX_NAMES, q.label, 3).map(function(p) { return PREFIXES[p]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(SUFFIX_NAMES, q.label, 3).map(function(s) { return SUFFIXES[s]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'prefixes') return 'A prefix goes at the start of a word and changes its meaning.';
                    return 'A suffix goes at the end of a word and changes its meaning or word type.';
                },

                explanationFor: function(q) {
                    return "'" + q.label + "' means: " + q.correctText;
                },

                masteryMessage: "Amazing! You're a prefixes and suffixes superstar!",
            });
        })();
    </script>
@endpush
