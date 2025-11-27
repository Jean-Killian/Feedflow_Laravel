@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-10 mb-10">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">{{ $survey->title }}</h1>
    </div>

    <!-- Graphiques sur la même ligne -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex gap-6">
            <div class="flex-1">
                <h3 class="text-sm font-semibold mb-3">Réponses dans le temps</h3>
                <canvas id="participationChart" style="max-height: 200px;"></canvas>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-semibold mb-3">Types de questions</h3>
                <canvas id="distributionChart" style="max-height: 200px;"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4">détail des réponse</h2>
        
        <div class="space-y-6">
            @foreach($survey->questions as $question)
                <div class="border-b pb-4 last:border-b-0">
                    <h3 class="font-semibold mb-2">{{ $question->title }}</h3>
                    
                    @if($question->question_type === 'text')
                        <div class="bg-gray-50 p-3 rounded">
                            @forelse($question->answers as $answer)
                                <div class="bg-white p-2 mb-2 rounded border text-sm">
                                    {{ $answer->answer }}
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">Aucune réponse</p>
                            @endforelse
                        </div>
                    @else
                        <div class="bg-gray-50 p-3 rounded">
                            @php
                                $stats = [];
                                foreach($question->answers as $answer) {
                                    $value = $answer->answer;
                                    if (is_string($value) && json_decode($value)) {
                                        $decoded = json_decode($value, true);
                                        if (is_array($decoded)) {
                                            foreach($decoded as $opt) {
                                                $stats[$opt] = ($stats[$opt] ?? 0) + 1;
                                            }
                                        }
                                    } else {
                                        $stats[$value] = ($stats[$value] ?? 0) + 1;
                                    }
                                }
                                $total = count($stats) > 0 ? array_sum($stats) : 0;
                            @endphp

                            <div class="space-y-2">
                                @forelse($stats as $option => $count)
                                    @php
                                        $percentage = $total > 0 ? round(($count / $total) * 100) : 0;
                                    @endphp
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span>{{ $option }}</span>
                                            <span class="text-gray-600">{{ $count }} ({{ $percentage }}%)</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-4">
                                            <div class="bg-blue-600 h-4 rounded-full" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-sm">Aucune réponse</p>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique 1: Réponses dans le temps
    @php
        $answersByDate = $survey->answers
            ->groupBy(function($answer) {
                return $answer->created_at->format('Y-m-d');
            })
            ->map->count()
            ->sortKeys();
    @endphp
    
    const participationCtx = document.getElementById('participationChart').getContext('2d');
    new Chart(participationCtx, {
        type: 'line',
        data: {
            labels: [@foreach($answersByDate as $date => $count)"{{ \Carbon\Carbon::parse($date)->format('d/m') }}",@endforeach],
            datasets: [{
                label: 'Nombre de réponses',
                data: [@foreach($answersByDate as $date => $count){{ $count }},@endforeach],
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { 
                y: { 
                    beginAtZero: true, 
                    ticks: { stepSize: 1 } 
                } 
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Graphique 2: Types de questions
    @php $questionTypes = $survey->questions->groupBy('question_type')->map->count(); @endphp
    const distributionCtx = document.getElementById('distributionChart').getContext('2d');
    new Chart(distributionCtx, {
        type: 'pie',
        data: {
            labels: [@foreach($questionTypes as $type => $count)"{{ ucfirst($type) }}",@endforeach],
            datasets: [{
                data: [@foreach($questionTypes as $type => $count){{ $count }},@endforeach],
                backgroundColor: ['rgba(59, 130, 246, 0.7)', 'rgba(16, 185, 129, 0.7)', 'rgba(245, 158, 11, 0.7)', 'rgba(239, 68, 68, 0.7)']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endsection
