@extends('layouts.app')

@section('meta_title', 'File Types & Storage — Computing Game for Kids')
@section('meta_blurb', 'A free computing game — match file extensions like .jpg and .mp3, and learn about storage devices.')
@section('meta_words', 'file types game, file extensions quiz, storage devices game, ks3 computing game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-hdd',
        'title' => 'File Types & Storage',
        'subtitle' => 'Pick your question types, then test your file & storage knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'filetypes', 'label' => 'File extensions'],
            ['id' => 'storage', 'label' => 'Storage devices'],
        ],
        'aboutTitle' => 'About this file types & storage game',
        'aboutText' => 'This free computing game covers common file extensions like .jpg, .mp3 and .docx, plus key facts about storage devices such as USB sticks, hard drives, SSDs and cloud storage.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FILETYPES = {
                '.jpg': 'An image (picture) file',
                '.mp3': 'An audio (music/sound) file',
                '.docx': 'A Word document (text) file',
                '.mp4': 'A video file',
                '.pdf': 'A document file that keeps its formatting (Portable Document Format)',
                '.png': 'An image file that supports transparency',
                '.xlsx': 'An Excel spreadsheet file',
                '.zip': 'A compressed (zipped) folder of files',
                '.txt': 'A plain text file',
                '.gif': 'A short animated image file',
            };
            const FILETYPE_NAMES = Object.keys(FILETYPES);

            const STORAGE = {
                'What small, portable storage device plugs into a USB port?': 'A USB stick (flash drive)',
                'What is the main storage device inside a computer that stores the operating system and files called?': 'The hard drive (or SSD)',
                'What do we call storing files on the internet instead of on your own device?': 'Cloud storage',
                'What type of storage device has no moving parts and is faster than a traditional hard drive?': 'An SSD (solid state drive)',
                'What round disc was once used to store music or software, like a CD or DVD?': 'An optical disc',
                "What is the benefit of storing files in the cloud rather than only on one device?": "You can access them from anywhere and they're backed up if your device breaks",
                'What is it called when you make a copy of your files in case the original is lost?': 'A backup',
                'What unit is commonly used to measure how much storage a device has, like 256 of them?': 'Gigabytes (GB)',
                'Which is generally faster: an SSD or a traditional hard disk drive (HDD)?': 'An SSD',
                'What do we call memory card storage often used in cameras and phones?': 'An SD card (memory card)',
            };
            const STORAGE_QUESTIONS = Object.keys(STORAGE);

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
                storageKey: 'fileTypesStorageGame.settings',
                types: ['filetypes', 'storage'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'filetypes') {
                        const ext = FILETYPE_NAMES[randInt(0, FILETYPE_NAMES.length - 1)];
                        return {
                            category: type,
                            label: ext,
                            correctText: FILETYPES[ext],
                            questionText: "What type of file has the extension '" + ext + "'?",
                        };
                    }
                    const question = STORAGE_QUESTIONS[randInt(0, STORAGE_QUESTIONS.length - 1)];
                    return {
                        category: type,
                        label: question,
                        correctText: STORAGE[question],
                        questionText: question,
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'filetypes') {
                        const distractors = pickOthers(FILETYPE_NAMES, q.label, 3).map(function(e) { return FILETYPES[e]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(STORAGE_QUESTIONS, q.label, 3).map(function(s) { return STORAGE[s]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'filetypes') {
                        return 'Think about whether this extension is usually a picture, sound, video or document.';
                    }
                    return 'Think about where files are physically kept, or whether they are kept online.';
                },

                explanationFor: function(q) {
                    if (q.category === 'filetypes') return q.label + ': ' + q.correctText;
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a file types & storage superstar!",
            });
        })();
    </script>
@endpush
