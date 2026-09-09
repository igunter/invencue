@extends('layouts.app')

@section('meta_title', 'Databases & the Web — GCSE Computer Science Game')
@section('meta_blurb', 'A free GCSE computer science game covering basic HTML tags, databases, tables, records and cookies.')
@section('meta_words', 'databases web game, gcse computer science game, HTML tags quiz, database table record cookies')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-database',
        'title' => 'Databases & the Web',
        'subtitle' => 'Pick your question types, then test your databases & web knowledge!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'html', 'label' => 'HTML tags'],
            ['id' => 'database', 'label' => 'Databases'],
        ],
        'aboutTitle' => 'About this databases & the web game',
        'aboutText' => 'This free GCSE computer science game covers basic HTML tags used to build webpages, plus key database vocabulary — tables, records, fields and cookies.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const HTML_TAGS = {
                '<html>': 'Marks the start and end of the whole HTML document',
                '<head>': 'Contains information about the page, like its title, not shown directly on the page',
                '<body>': 'Contains the visible content of the webpage',
                '<title>': 'Sets the text shown in the browser tab',
                '<p>': 'Defines a paragraph of text',
                '<a>': 'Creates a hyperlink to another page or resource',
                '<img>': 'Embeds an image on the page',
                '<h1>': 'Defines the largest, most important heading on a page',
                '<ul>': 'Defines an unordered (bulleted) list',
                '<table>': 'Defines a table of data on the page',
            };
            const HTML_TAG_NAMES = Object.keys(HTML_TAGS);

            const DATABASE = {
                'What do we call an organised collection of data stored electronically so it can be easily accessed?': 'A database',
                "What do we call a single entry in a database, like one person's details?": 'A record',
                'What do we call a structured set of data arranged in rows and columns within a database?': 'A table',
                "What do we call a single piece of information in a record, like just the 'name' part?": 'A field',
                'What field is often used to uniquely identify each record in a table, like a customer ID?': 'A primary key',
                'What language is commonly used to ask questions of (query) a database?': 'SQL (Structured Query Language)',
                'What small text file does a website store on your device to remember information about you, like login status?': 'A cookie',
                'Why might a website use cookies to remember items in a shopping basket?': 'So the basket contents stay saved as the user browses between pages',
                'What term describes linking two tables together using a shared field, like a customer ID?': 'A relationship (relational database)',
                "What must a company consider under data protection law when storing people's personal data in a database?": 'Keeping it secure and only using it for the stated purpose',
            };
            const DATABASE_QUESTIONS = Object.keys(DATABASE);

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
                storageKey: 'databasesWebGame.settings',
                types: ['html', 'database'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'html') {
                        const tag = HTML_TAG_NAMES[randInt(0, HTML_TAG_NAMES.length - 1)];
                        return {
                            category: type,
                            label: tag,
                            correctText: HTML_TAGS[tag],
                            questionText: "What does the HTML tag '" + tag + "' do?",
                        };
                    }
                    const question = DATABASE_QUESTIONS[randInt(0, DATABASE_QUESTIONS.length - 1)];
                    return {
                        category: type,
                        label: question,
                        correctText: DATABASE[question],
                        questionText: question,
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'html') {
                        const distractors = pickOthers(HTML_TAG_NAMES, q.label, 3).map(function(t) { return HTML_TAGS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(DATABASE_QUESTIONS, q.label, 3).map(function(d) { return DATABASE[d]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'html') {
                        return 'Think about what part of a webpage this tag controls — structure, text, images or links.';
                    }
                    return 'Think about how data is organised into tables, records and fields.';
                },

                explanationFor: function(q) {
                    if (q.category === 'html') return q.label + ': ' + q.correctText;
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You've mastered databases & the web!",
            });
        })();
    </script>
@endpush
