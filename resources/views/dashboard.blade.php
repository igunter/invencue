@extends('layouts.app')

@section('meta_title', 'Dashboard - ' . config('app.name'))
@section('robots', 'noindex, nofollow')

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <h1 class="h4 mb-3">Dashboard</h1>
            <p class="mb-0">You're logged in as <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }}).</p>
        </div>
    </div>

    @if ($peerAvg !== null)
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="h6 text-uppercase text-secondary mb-3">How you compare</h2>
                <p class="mb-0">Your average score: <strong>{{ $userAvg }}%</strong> &mdash; Players your age: <strong>{{ $peerAvg }}%</strong></p>
            </div>
        </div>
    @elseif ($userAvg !== null)
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="h6 text-uppercase text-secondary mb-3">Your average score</h2>
                <p class="mb-0"><strong>{{ $userAvg }}%</strong></p>
            </div>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="h6 text-uppercase text-secondary mb-3">Your accuracy — last 4 weeks</h2>
            @if ($userAvg === null)
                <p class="text-secondary mb-0">Play a game to start tracking your progress here.</p>
            @else
                <canvas id="accuracyChart" height="90"></canvas>
            @endif
        </div>
    </div>

    @if ($byCategory->isNotEmpty())
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="h6 text-uppercase text-secondary mb-3">By subject</h2>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th class="text-end">Played</th>
                                        <th class="text-end">Accuracy</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($byCategory as $row)
                                        <tr>
                                            <td>{{ \Illuminate\Support\Str::headline($row->category_slug) }}</td>
                                            <td class="text-end">{{ $row->games_played }}</td>
                                            <td class="text-end">{{ round(100 * $row->total_score / $row->total_questions, 1) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="h6 text-uppercase text-secondary mb-3">By game</h2>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Game</th>
                                        <th class="text-end">Played</th>
                                        <th class="text-end">Accuracy</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($byGame as $row)
                                        <tr>
                                            <td>{{ \Illuminate\Support\Str::headline($row->game_slug) }}</td>
                                            <td class="text-end">{{ $row->games_played }}</td>
                                            <td class="text-end">{{ round(100 * $row->total_score / $row->total_questions, 1) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@if ($userAvg !== null)
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
        <script>
            (function() {
                const dailyAccuracy = @json($dailyAccuracy);
                const labels = Object.keys(dailyAccuracy);
                const data = Object.values(dailyAccuracy);

                new Chart(document.getElementById('accuracyChart'), {
                    type: 'line',
                    data: {
                        labels: labels.map(function(d) {
                            return new Date(d + 'T00:00:00').toLocaleDateString(undefined, { day: 'numeric', month: 'short' });
                        }),
                        datasets: [{
                            label: 'Accuracy %',
                            data: data,
                            spanGaps: false,
                            borderColor: '#4e7cff',
                            backgroundColor: 'rgba(78, 124, 255, .12)',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 3,
                        }],
                    },
                    options: {
                        scales: {
                            y: { min: 0, max: 100, ticks: { callback: function(v) { return v + '%'; } } },
                        },
                        plugins: { legend: { display: false } },
                    },
                });
            })();
        </script>
    @endpush
@endif
