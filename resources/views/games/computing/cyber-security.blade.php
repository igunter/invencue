@extends('layouts.app')

@section('meta_title', 'Cyber Security — GCSE Computer Science Game')
@section('meta_blurb', 'A free GCSE computer science game covering malware types, phishing and prevention methods.')
@section('meta_words', 'cyber security game, gcse computer science game, malware phishing quiz, virus worm trojan ransomware')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-bug',
        'title' => 'Cyber Security',
        'subtitle' => 'Pick your question types, then test your cyber security knowledge!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'malware', 'label' => 'Malware types'],
            ['id' => 'prevention', 'label' => 'Prevention methods'],
        ],
        'aboutTitle' => 'About this cyber security game',
        'aboutText' => 'This free GCSE computer science game covers cyber security threats — viruses, worms, trojans and ransomware — plus phishing and the prevention methods used to keep systems and data safe.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const MALWARE = {
                'Virus': 'Malicious code that attaches itself to files and spreads when the file is shared or run',
                'Worm': 'Malware that copies itself and spreads across a network without needing a host file',
                'Trojan': 'Malware disguised as legitimate software to trick users into installing it',
                'Ransomware': "Malware that encrypts a victim's files and demands payment to unlock them",
                'Spyware': "Malware that secretly monitors and collects a user's data and activity",
                'Adware': 'Software that automatically displays unwanted adverts, often bundled with free downloads',
                'Keylogger': 'Malware that records every key a user presses to steal passwords and data',
                'Rootkit': "Malware designed to hide its presence and give an attacker ongoing hidden access",
                'Botnet': 'A network of infected computers controlled remotely, often used to launch attacks',
                'Phishing email': 'A fake message pretending to be from a trusted source, tricking users into revealing details',
            };
            const MALWARE_NAMES = Object.keys(MALWARE);

            const PREVENTION = {
                'What software is designed to detect, block and remove malicious programs?': 'Anti-malware (antivirus) software',
                'What is it called when a message pretends to be from a trusted source to trick you into giving away information?': 'Phishing',
                'What barrier, either software or hardware, monitors and controls incoming and outgoing network traffic?': 'A firewall',
                'What should you regularly do to software and operating systems to fix security weaknesses?': 'Install updates (patches)',
                'What term describes a weakness in a system that attackers can exploit?': 'A vulnerability',
                'What security method requires two different types of proof of identity to log in?': 'Two-factor authentication (2FA)',
                'Why should you avoid clicking links in unexpected emails from unknown senders?': 'They could lead to phishing sites or install malware',
                'What is it called when a criminal tricks someone by relying on human trust rather than technology, such as pretending to be IT support on the phone?': 'Social engineering',
                'Why is it important to back up your files regularly?': 'So you can recover them if malware like ransomware locks or deletes them',
                'What should you check for on a website before entering payment details?': 'That the connection is secure (https and a padlock icon)',
            };
            const PREVENTION_QUESTIONS = Object.keys(PREVENTION);

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
                storageKey: 'cyberSecurityGame.settings',
                types: ['malware', 'prevention'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'malware') {
                        const name = MALWARE_NAMES[randInt(0, MALWARE_NAMES.length - 1)];
                        return {
                            category: type,
                            label: name,
                            correctText: MALWARE[name],
                            questionText: "What is '" + name + "'?",
                        };
                    }
                    const question = PREVENTION_QUESTIONS[randInt(0, PREVENTION_QUESTIONS.length - 1)];
                    return {
                        category: type,
                        label: question,
                        correctText: PREVENTION[question],
                        questionText: question,
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'malware') {
                        const distractors = pickOthers(MALWARE_NAMES, q.label, 3).map(function(m) { return MALWARE[m]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(PREVENTION_QUESTIONS, q.label, 3).map(function(p) { return PREVENTION[p]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'malware') {
                        return 'Think about how this threat spreads and what it does once it infects a system.';
                    }
                    return 'Think about the tools and habits that keep systems, accounts and data secure.';
                },

                explanationFor: function(q) {
                    if (q.category === 'malware') return q.label + ': ' + q.correctText;
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You've mastered cyber security!",
            });
        })();
    </script>
@endpush
