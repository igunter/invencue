@extends('layouts.app')

@section('meta_title', 'Music Genres — Music Game for Kids')
@section('meta_blurb', 'A free music game — learn about pop, rock, jazz, classical, hip-hop and more, and what makes each genre distinct.')
@section('meta_words', 'music genres game, pop rock jazz classical, hip hop folk quiz, ks3 music game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-vinyl',
        'title' => 'Music Genres',
        'subtitle' => 'Read the clue, then work out the genre!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Genre facts'],
        ],
        'aboutTitle' => 'About this music genres game',
        'aboutText' => 'This free music game explores popular genres such as pop, rock, jazz, classical, hip-hop and folk, and the key features that make each one distinct.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const GENRES = {
                'Pop': 'Catchy tunes, a simple structure and mainstream appeal, often with verses and choruses.',
                'Rock': 'Strong beats, electric guitars and drums, often loud and energetic.',
                'Jazz': 'Improvisation, swung rhythms and instruments like saxophone and trumpet.',
                'Classical': 'Music written by composers like Mozart and Beethoven, often played by an orchestra.',
                'Hip-hop': 'Rhythmic spoken lyrics (rapping) over a strong beat.',
                'Folk': 'Traditional songs, often passed down and played on acoustic instruments.',
                'Reggae': 'A relaxed off-beat rhythm that started in Jamaica.',
                'Blues': 'Expressive, often sad music using blue notes, which influenced jazz and rock.',
                'Country': 'Storytelling lyrics, often about everyday life, with guitars and fiddles.',
                'Electronic (EDM)': 'Music made using synthesisers and computers, often for dancing.',
                'Reggaeton': 'A genre from Latin America mixing reggae, hip-hop and Latin rhythms.',
                'Soul': 'Emotional, gospel-influenced singing that grew out of African-American music.',
            };
            const GENRE_NAMES = Object.keys(GENRES);

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
                storageKey: 'musicGenresGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const genre = GENRE_NAMES[randInt(0, GENRE_NAMES.length - 1)];
                    return {
                        category: type,
                        label: genre,
                        correctText: GENRES[genre],
                        questionText: "Which of these best describes the '" + genre + "' genre?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(GENRE_NAMES, q.label, 3).map(function(g) { return GENRES[g]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about the instruments, rhythms and mood typically used in this genre.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You're a music genres superstar!",
            });
        })();
    </script>
@endpush
