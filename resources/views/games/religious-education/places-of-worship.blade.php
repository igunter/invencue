@extends('layouts.app')

@section('meta_title', 'Places of Worship — Religious Education Game for Kids')
@section('meta_blurb', 'A free religious education game for kids — match churches, mosques, temples, synagogues and gurdwaras to the religion that uses them.')
@section('meta_words', 'places of worship game, religious education game for kids, ks2 re game, church mosque temple synagogue gurdwara quiz')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-bank',
        'title' => 'Places of Worship',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Places of worship'],
        ],
        'aboutTitle' => 'About this places of worship game',
        'aboutText' => 'This free religious education game teaches young children about buildings where people worship — churches, mosques, synagogues, Hindu temples, gurdwaras and Buddhist temples — and which religion uses each one.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Where do Christians gather together to worship?", a: "A church" },
                { q: "What is a Muslim place of worship called?", a: "A mosque" },
                { q: "What is a Jewish place of worship called?", a: "A synagogue" },
                { q: "What is a Hindu place of worship called?", a: "A mandir (temple)" },
                { q: "What is a Sikh place of worship called?", a: "A gurdwara" },
                { q: "What is a Buddhist place of worship often called?", a: "A vihara (temple)" },
                { q: "What do we call a large, important church that is the centre of a bishop's area?", a: "A cathedral" },
                { q: "What is a place where Buddhist monks live and study called?", a: "A monastery" },
                { q: "In which building would you find a Torah scroll kept in a special cabinet called an ark?", a: "A synagogue" },
                { q: "In which building would you find a large open hall where free meals are served to everyone?", a: "A gurdwara" },
                { q: "In which building do Muslims face towards Makkah to pray?", a: "A mosque" },
                { q: "In which building might you see statues and images of deities such as Vishnu or Ganesh?", a: "A mandir (temple)" },
            ];

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

            window.ScienceQuiz.run({
                storageKey: 'placesOfWorshipGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const item = FACTS[randInt(0, FACTS.length - 1)];
                    return { category: type, correctText: item.a, questionText: item.q };
                },

                buildChoices: function(q) {
                    const distractors = shuffle(
                        FACTS.map(function(f) { return f.a; }).filter(function(a) { return a !== q.correctText; })
                    ).slice(0, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about which religion the building belongs to, and what happens inside it.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a places of worship superstar!",
            });
        })();
    </script>
@endpush
