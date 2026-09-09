@extends('layouts.app')

@section('meta_title', 'Weather Watch — Kids Earth Science Game')
@section('meta_blurb', 'A free earth science game for young kids — spot the weather from a description, and learn what to wear for each type.')
@section('meta_words', 'weather watch game, kids earth science game, sunny rainy snowy windy stormy foggy, what to wear in weather')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-droplet-half',
        'title' => 'Weather Watch',
        'subtitle' => 'Pick your question types, then spot that weather!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'spot', 'label' => 'Spot the weather'],
            ['id' => 'clothing', 'label' => 'What to wear'],
        ],
        'aboutTitle' => 'About this weather watch game',
        'aboutText' => 'This free earth science game helps young kids spot different types of weather from a description, and learn what to wear for sunny, rainy, snowy and windy days. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const SPOT = {
                'Bright sun with no clouds in the sky': 'Sunny',
                'Grey clouds with water falling from them': 'Rainy',
                'White, cold flakes falling from the sky': 'Snowy',
                'Strong moving air that bends the trees': 'Windy',
                'A flash of light followed by a loud bang': 'Stormy',
                'Thick, low cloud you can barely see through': 'Foggy',
                'The sky is grey and covered with clouds, blocking the sun': 'Cloudy',
                'The ground and puddles are frozen solid and slippery': 'Icy',
                'Big piles of snow on the ground and icicles hanging from roofs': 'Snowy',
                'Dark clouds, thunder rumbling and bright flashes across the sky': 'Stormy',
            };
            const SPOT_NAMES = Object.keys(SPOT);
            const WEATHER_LIST = ['Sunny', 'Rainy', 'Snowy', 'Windy', 'Stormy', 'Foggy', 'Cloudy', 'Icy'];

            const CLOTHING = {
                'Sunny': 'Sunglasses, a hat and sun cream to protect your skin.',
                'Rainy': 'A raincoat, wellies, and maybe an umbrella.',
                'Snowy': 'A warm coat, gloves, a hat and snow boots.',
                'Windy': 'A windproof jacket, since loose things can blow around.',
                'Stormy': 'A raincoat and sturdy shoes — it is safest to stay indoors during lightning.',
                'Foggy': 'Bright or reflective clothing so that drivers and others can see you clearly.',
                'Cloudy': 'A light jacket, since it might not be sunny but usually is not freezing either.',
                'Icy': 'Boots with a good grip, warm layers and gloves to stay safe on slippery ground.',
                'Hot': 'Loose, light-coloured clothing, a hat, sunglasses and plenty of water.',
                'Frosty': 'A warm coat, gloves and a hat, plus boots with a good grip for icy pavements.',
            };
            const CLOTHING_WEATHER = Object.keys(CLOTHING);

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
                storageKey: 'weatherWatchGame.settings',
                types: ['spot', 'clothing'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'spot') {
                        const description = SPOT_NAMES[randInt(0, SPOT_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: SPOT[description],
                            questionText: description + ' — what is the weather?',
                        };
                    }
                    const weather = CLOTHING_WEATHER[randInt(0, CLOTHING_WEATHER.length - 1)];
                    return {
                        category: type,
                        label: weather,
                        correctText: CLOTHING[weather],
                        questionText: 'What should you wear on a ' + weather.toLowerCase() + ' day?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'spot') {
                        const distractors = pickOthers(WEATHER_LIST, q.correctText, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(CLOTHING_WEATHER, q.label, 3).map(function(w) { return CLOTHING[w]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'spot') {
                        return 'Think about what you can see, hear, or feel in this weather.';
                    }
                    return 'Think about staying cool and protected, dry, warm, or safe from strong air.';
                },

                explanationFor: function(q) {
                    if (q.category === 'clothing') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a weather watching superstar!",
            });
        })();
    </script>
@endpush
