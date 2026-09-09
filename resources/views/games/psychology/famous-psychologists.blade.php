@extends('layouts.app')

@section('meta_title', 'Famous Psychologists — GCSE Psychology Game')
@section('meta_blurb', 'A free GCSE psychology game covering famous psychologists — Pavlov, Skinner, Piaget, Freud, Milgram and more.')
@section('meta_words', 'famous psychologists game, gcse psychology game, pavlov skinner piaget freud milgram bandura')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-person-badge',
        'title' => 'Famous Psychologists',
        'subtitle' => 'Read the clue, then name the psychologist!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Famous psychologists'],
        ],
        'aboutTitle' => 'About this famous psychologists game',
        'aboutText' => 'This free GCSE psychology game covers well-known psychologists and what they are best known for, including Pavlov, Skinner, Piaget, Freud, Milgram, Bandura, Maslow, Bowlby and Ainsworth.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const PSYCHOLOGISTS = {
                'Ivan Pavlov': 'Discovered classical conditioning through his experiments on dogs salivating to a bell.',
                'B. F. Skinner': 'Developed the theory of operant conditioning, using reinforcement and punishment.',
                'Jean Piaget': "Developed a well-known theory of children's cognitive development in stages.",
                'Sigmund Freud': 'Founded psychoanalysis and the psychodynamic approach to psychology.',
                'Stanley Milgram': 'Studied obedience to authority in his famous 1960s experiments.',
                'Albert Bandura': 'Developed social learning theory and studied learning through observation, including the Bobo doll experiment.',
                'Abraham Maslow': 'Created the hierarchy of needs, a theory of human motivation.',
                'John Bowlby': 'Developed attachment theory, focusing on the bond between infants and caregivers.',
                'Mary Ainsworth': "Developed the 'Strange Situation' study to measure attachment styles in infants.",
                'Wilhelm Wundt': 'Opened the first psychology laboratory and is often called the father of experimental psychology.',
                'Elizabeth Loftus': 'Researched the reliability of eyewitness testimony and false memories.',
                'Carl Rogers': 'A key figure in humanistic psychology, known for person-centred therapy.',
            };
            const NAMES = Object.keys(PSYCHOLOGISTS);

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
                storageKey: 'famousPsychologistsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const name = NAMES[randInt(0, NAMES.length - 1)];
                    return {
                        category: type,
                        label: name,
                        correctText: name,
                        questionText: PSYCHOLOGISTS[name],
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(NAMES, q.label, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about which approach or famous study this psychologist is best known for.';
                },

                explanationFor: function(q) {
                    return q.correctText + ': ' + PSYCHOLOGISTS[q.correctText];
                },

                masteryMessage: "Amazing! You know your famous psychologists!",
            });
        })();
    </script>
@endpush
